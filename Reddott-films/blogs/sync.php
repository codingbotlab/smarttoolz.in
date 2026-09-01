<?php
declare(strict_types=1);

/*
 * Reddott Films blog bulk sync / article builder.
 *
 * This endpoint is intentionally disabled until a server-side secret is set.
 * Set REDDOTT_BLOG_SYNC_SECRET in the hosting environment (preferred), or
 * provide Reddott-films/blogs/sync.local.php returning ['secret' => '...'].
 * Never commit the secret.
 *
 * Usage:
 *   /Reddott-films/blogs/sync.php?key=YOUR_SECRET&limit=10
 *
 * Run repeatedly with a small limit. Existing DB records are reused, so the
 * operation is resumable and does not need to process all videos at once.
 */
require_once __DIR__.'/data.php';
require_once __DIR__.'/content-engine.php';
require_once __DIR__.'/transcript.php';
require_once __DIR__.'/db.php';

function rfSyncSecret(): string {
    $env=trim((string)(getenv('REDDOTT_BLOG_SYNC_SECRET')?:''));
    if($env!=='') return $env;
    $local=__DIR__.'/sync.local.php';
    if(is_file($local)){
        $cfg=require $local;
        if(is_array($cfg)) return trim((string)($cfg['secret']??''));
    }
    return '';
}
function rfSyncOut(array $data,int $status=200): never {
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    exit;
}
function rfSyncApi(string $url,string $token): array {
    $raw=reddottYoutubeHttpGet($url,$token);
    $data=json_decode($raw,true);
    if(!is_array($data)) throw new RuntimeException('Invalid YouTube response.');
    if(isset($data['error'])) throw new RuntimeException((string)($data['error']['message']??'YouTube API error.'));
    return $data;
}
function rfSyncCountWords(string $text): int {
    if($text==='') return 0;
    preg_match_all('/[\p{L}\p{N}]+/u',$text,$m);
    return count($m[0]??[]);
}

if(($_SERVER['REQUEST_METHOD']??'GET')!=='GET') rfSyncOut(['ok'=>false,'error'=>'GET required.'],405);
$expected=rfSyncSecret();
$key=(string)($_GET['key']??'');
if($expected==='' || $key==='' || !hash_equals($expected,$key)) rfSyncOut(['ok'=>false,'error'=>'Unauthorized.'],401);

$limit=max(1,min(25,(int)($_GET['limit']??10)));
$cursor=trim((string)($_GET['cursor']??''));
$only=trim((string)($_GET['video_id']??''));

try{
    rfBlogEnsureTables();
    if(!reddottYoutubeConnection()) throw new RuntimeException('YouTube is not connected.');
    $token=reddottYoutubeAccessToken();

    $items=[];
    $next='';
    if($only!==''){
        if(!preg_match('/^[A-Za-z0-9_-]{6,}$/',$only)) throw new RuntimeException('Invalid video_id.');
        $r=rfSyncApi('https://www.googleapis.com/youtube/v3/videos?'.http_build_query(['part'=>'snippet,status','id'=>$only],'','&',PHP_QUERY_RFC3986),$token);
        $items=$r['items']??[];
    }else{
        $ch=rfSyncApi('https://www.googleapis.com/youtube/v3/channels?'.http_build_query(['part'=>'contentDetails','mine'=>'true'],'','&',PHP_QUERY_RFC3986),$token);
        $channel=$ch['items'][0]??null;
        if(!$channel) throw new RuntimeException('No connected YouTube channel found.');
        $uploads=(string)($channel['contentDetails']['relatedPlaylists']['uploads']??'');
        if($uploads==='') throw new RuntimeException('Uploads playlist not found.');
        $p=['part'=>'snippet','playlistId'=>$uploads,'maxResults'=>$limit];
        if($cursor!=='') $p['pageToken']=$cursor;
        $pi=rfSyncApi('https://www.googleapis.com/youtube/v3/playlistItems?'.http_build_query($p,'','&',PHP_QUERY_RFC3986),$token);
        $ids=[];
        foreach(($pi['items']??[]) as $row){$id=(string)($row['snippet']['resourceId']['videoId']??'');if($id!=='')$ids[]=$id;}
        if($ids){
            $vr=rfSyncApi('https://www.googleapis.com/youtube/v3/videos?'.http_build_query(['part'=>'snippet,status','id'=>implode(',',$ids)],'','&',PHP_QUERY_RFC3986),$token);
            $items=$vr['items']??[];
        }
        $next=(string)($pi['nextPageToken']??'');
    }

    $done=0;$generated=0;$review=0;$errors=[];
    foreach($items as $video){
        try{
            $s=$video['snippet']??[];
            $id=(string)($video['id']??'');
            $title=trim((string)($s['title']??''));
            $description=trim((string)($s['description']??''));
            $published=(string)($s['publishedAt']??'');
            $thumb=(string)($s['thumbnails']['maxres']['url']??$s['thumbnails']['high']['url']??$s['thumbnails']['medium']['url']??'');
            $privacy=(string)($video['status']['privacyStatus']??'');
            if($id===''||$title==='') continue;

            $transcript='';
            $transcriptStatus='missing';
            $saved=rfBlogGetTranscript($id);
            if($saved && ($saved['status']??'')==='available'){
                $transcript=(string)($saved['transcript']??'');
                $transcriptStatus='available';
            }else{
                $transcript=reddottYoutubeTranscript($id,$token);
                $transcriptStatus=$transcript!==''?'available':'missing';
                rfBlogSaveTranscript($id,$transcript,$transcriptStatus);
            }

            $book=rfArticle($title,$description,$transcript);
            $sourceWords=rfSyncCountWords(trim($description."\n".$transcript));
            $articleHtml='<p>'.($book['intro']??'').'</p><p><strong>Source context:</strong> '.htmlspecialchars((string)($book['sourceSummary']??''),ENT_QUOTES,'UTF-8').'</p>';
            foreach(($book['sections']??[]) as $section){
                $articleHtml.='<h2>'.htmlspecialchars((string)$section[0],ENT_QUOTES,'UTF-8').'</h2><p>'.htmlspecialchars((string)$section[1],ENT_QUOTES,'UTF-8').'</p>';
            }
            if($description!=='') $articleHtml.='<h2>What the creator published</h2><p>'.nl2br(htmlspecialchars($description,ENT_QUOTES,'UTF-8')).'</p>';
            $articleWords=rfSyncCountWords(strip_tags($articleHtml));
            $indexable=($articleWords>=700 && $articleWords<=1200 && $sourceWords>=240 && $description!=='');
            $status=$indexable?'generated':'review';

            rfBlogSaveVideo($id,(string)($s['channelId']??''),$title,$description,$published,$thumb,$privacy,$sourceWords,(int)($book['transcript_word_count']??0),$transcriptStatus,$indexable);
            rfBlogSaveArticle($id,$book,$articleHtml,$indexable);
            $done++;$indexable?$generated++:$review++;
        }catch(Throwable $e){$errors[]=['video_id'=>(string)($video['id']??''),'error'=>$e->getMessage()];}
    }

    rfBlogAuditUpdate($done,$generated,$review,count($errors));
    rfSyncOut(['ok'=>true,'processed'=>$done,'indexable_generated'=>$generated,'needs_review'=>$review,'errors'=>$errors,'next_cursor'=>$next,'has_more'=>$next!=='','limit'=>$limit]);
}catch(Throwable $e){rfSyncOut(['ok'=>false,'error'=>$e->getMessage()],500);}

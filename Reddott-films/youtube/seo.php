<?php
/* Reddott Films YouTube SEO planner/updater.
 * Protected by REDDOTT_YOUTUBE_SEO_SECRET. It NEVER invents video facts:
 * suggestions are derived from each video's title and description.
 * Default mode is preview. Actual YouTube writes require apply=1.
 */
declare(strict_types=1);
require_once __DIR__.'/lib.php';
require_once __DIR__.'/../blogs/db.php';
require_once __DIR__.'/../blogs/content-engine.php';

function seoOut(array $x,int $status=200):never{http_response_code($status);header('Content-Type: application/json; charset=utf-8');echo json_encode($x,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);exit;}
function seoSecret():string{
 $v=trim((string)(getenv('REDDOTT_YOUTUBE_SEO_SECRET')?:''));
 if($v!=='')return $v;
 $f=__DIR__.'/seo.local.php';
 if(is_file($f)){ $c=require $f; if(is_array($c))return trim((string)($c['secret']??'')); }
 return '';
}
function seoApi(string $url,string $token):array{$r=json_decode(reddottYoutubeHttpGet($url,$token),true);if(!is_array($r))throw new RuntimeException('Invalid YouTube response.');if(isset($r['error']))throw new RuntimeException((string)($r['error']['message']??'YouTube API error.'));return $r;}
function seoTerms(string $title,string $description):array{
 $raw=rfWords($title.' '.$description); $out=[];
 foreach($raw as $w){$w=preg_replace('/[^a-z0-9]+/i','',strtolower($w));if($w!==''&&!in_array($w,$out,true))$out[]=$w;if(count($out)>=12)break;}
 return $out;
}
function seoTitle(string $title,string $description):string{
 $t=trim(preg_replace('/\s+/',' ',$title)??$title);$terms=seoTerms($t,$description);
 if(count($terms)<1)return $t;
 // Preserve the original title whenever it is already descriptive. Otherwise append
 // only source-derived terms, without claims, clickbait or invented promises.
 if(mb_strlen($t)<=70)return $t;
 return mb_substr($t,0,67).'...';
}
function seoDescription(string $title,string $description):string{
 $d=trim(preg_replace('/\s+/',' ',$description)??$description);
 $t=trim($title);
 $lead=$d!==''?$d:"This Reddott Films video covers $t.";
 $text=$lead."\n\nIn this video, the focus is: $t. Watch the complete video for the creator's full presentation and context.\n\nReddott Films — practical videos, creative content and useful viewing guides.";
 return mb_substr($text,0,4900);
}
function seoTags(string $title,string $description):array{
 $terms=seoTerms($title,$description);$tags=[];
 foreach($terms as $x){$tags[]=$x;}
 $tags[]='reddott films';$tags[]='smarttoolz';
 return array_values(array_unique(array_slice($tags,0,15)));
}

$key=(string)($_GET['key']??'');$secret=seoSecret();if($secret===''||$key===''||!hash_equals($secret,$key))seoOut(['ok'=>false,'error'=>'Unauthorized.'],401);
if(!reddottYoutubeConnection())seoOut(['ok'=>false,'error'=>'YouTube is not connected.'],400);
$apply=((string)($_GET['apply']??'0'))==='1';$limit=max(1,min(10,(int)($_GET['limit']??5)));$token=reddottYoutubeAccessToken();
try{
 $ch=seoApi('https://www.googleapis.com/youtube/v3/channels?'.http_build_query(['part'=>'contentDetails','mine'=>'true'],'','&',PHP_QUERY_RFC3986),$token);$channel=$ch['items'][0]??null;if(!$channel)throw new RuntimeException('Channel not found.');
 $uploads=(string)($channel['contentDetails']['relatedPlaylists']['uploads']??'');if($uploads==='')throw new RuntimeException('Uploads playlist not found.');
 $p=['part'=>'snippet','playlistId'=>$uploads,'maxResults'=>$limit];if(isset($_GET['cursor'])&&trim((string)$_GET['cursor'])!=='')$p['pageToken']=trim((string)$_GET['cursor']);
 $pi=seoApi('https://www.googleapis.com/youtube/v3/playlistItems?'.http_build_query($p,'','&',PHP_QUERY_RFC3986),$token);$ids=[];foreach(($pi['items']??[]) as $i){$id=(string)($i['snippet']['resourceId']['videoId']??'');if($id!=='')$ids[]=$id;}
 if(!$ids)seoOut(['ok'=>true,'mode'=>$apply?'apply':'preview','processed'=>0,'next_cursor'=>(string)($pi['nextPageToken']??'')]);
 $vr=seoApi('https://www.googleapis.com/youtube/v3/videos?'.http_build_query(['part'=>'snippet','id'=>implode(',',$ids)],'','&',PHP_QUERY_RFC3986),$token);$result=[];$updated=0;
 foreach(($vr['items']??[]) as $v){$s=$v['snippet']??[];$id=(string)($v['id']??'');$title=trim((string)($s['title']??''));$desc=trim((string)($s['description']??''));$tags=seoTags($title,$desc);$newTitle=seoTitle($title,$desc);$newDesc=seoDescription($title,$desc);$result[]=['video_id'=>$id,'current_title'=>$title,'suggested_title'=>$newTitle,'suggested_description'=>$newDesc,'suggested_tags'=>$tags];
  if($apply){
   $body=['id'=>$id,'snippet'=>['title'=>$newTitle,'description'=>$newDesc,'categoryId'=>(string)($s['categoryId']??'22')]];
   if($tags)$body['snippet']['tags']=$tags;
   $url='https://www.googleapis.com/youtube/v3/videos?part=snippet';
   reddottYoutubeHttpRequest('PUT',$url,$token,json_encode($body,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES));$updated++;
  }
 }
 seoOut(['ok'=>true,'mode'=>$apply?'apply':'preview','processed'=>count($result),'updated'=>$updated,'items'=>$result,'next_cursor'=>(string)($pi['nextPageToken']??''),'has_more'=>!empty($pi['nextPageToken'])]);
}catch(Throwable $e){seoOut(['ok'=>false,'error'=>$e->getMessage()],500);}

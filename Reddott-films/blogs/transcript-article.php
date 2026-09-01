<?php
declare(strict_types=1);
require_once __DIR__.'/data.php';
require_once __DIR__.'/transcript.php';
require_once __DIR__.'/content-engine.php';

/**
 * Transcript-aware article context. Captions are optional: when available they
 * are used to ground additional topic terms and sections. We never fabricate
 * transcript text or pretend a transcript exists when YouTube does not expose it.
 */
function rfTranscriptContext(string $title,string $description,string $transcript): array {
    $text=trim(preg_replace('/\s+/',' ',$transcript)??'');
    if($text==='') return ['available'=>false,'text'=>'','keywords'=>[],'sentences'=>[]];
    $words=rfWords($title.' '.$description.' '.$text);
    $sent=preg_split('/(?<=[.!?])\s+/', $text)?:[];
    $sent=array_values(array_filter(array_map('trim',$sent)));
    return ['available'=>true,'text'=>$text,'keywords'=>array_slice($words,0,12),'sentences'=>array_slice($sent,0,12)];
}
function rfTranscriptEnhancement(string $subject,array $ctx): array {
    if(!$ctx['available']) return [
        'heading'=>'Video details and source verification',
        'body'=>"YouTube has not exposed a usable caption track for this video through the available integration. For that reason, this article does not invent spoken content. The title and published description remain the source for video-specific claims, while the original video should be watched for dialogue, narration and other details."
    ];
    $terms=implode(', ',array_slice($ctx['keywords'],0,8));
    $sample=implode(' ',array_slice($ctx['sentences'],0,3));
    return [
        'heading'=>'What the available transcript adds',
        'body'=>"The available caption text gives additional language-level context around $subject. Recurring terms include $terms. A short excerpt of the available transcript is used only as source context: ".htmlspecialchars($sample,ENT_QUOTES,'UTF-8').". Readers should still use the complete video for tone, visuals, timing and any meaning that depends on presentation."
    ];
}

$id=trim((string)($_GET['id']??''));
if(!preg_match('/^[A-Za-z0-9_-]{6,}$/',$id)){http_response_code(400);exit('Invalid video.');}
try{
    if(!reddottYoutubeConnection()) throw new RuntimeException('YouTube is not connected.');
    $token=reddottYoutubeAccessToken();
    $r=reddottBlogYoutube('https://www.googleapis.com/youtube/v3/videos?'.http_build_query(['part'=>'snippet,status','id'=>$id],'','&',PHP_QUERY_RFC3986),$token);
    $v=$r['items'][0]??null;
    if(!$v) throw new RuntimeException('Video not found.');
    $s=$v['snippet']??[];
    $title=trim((string)($s['title']??'Reddott Films video'));
    $description=trim((string)($s['description']??''));
    $transcript=reddottVideoTranscript($id,$token);
    $ctx=rfTranscriptContext($title,$description,$transcript);
    $book=rfArticle($title,$description);
    $enh=rfTranscriptEnhancement($book['subject'],$ctx);
    $canonical='https://smarttoolz.in/Reddott-films/blogs/'.rawurlencode(reddottBlogSlug($title)).'/'.rawurlencode($id).'/';
    $meta=mb_substr(strip_tags($book['intro']),0,155);
}catch(Throwable $e){error_log('Reddott transcript article: '.$e->getMessage());http_response_code(503);exit('Article temporarily unavailable.');}
?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title><?=htmlspecialchars($book['headline'],ENT_QUOTES,'UTF-8')?> | Reddott Films</title><meta name="description" content="<?=htmlspecialchars($meta,ENT_QUOTES,'UTF-8')?>"><link rel="canonical" href="<?=htmlspecialchars($canonical,ENT_QUOTES,'UTF-8')?>"><meta name="robots" content="<?=strlen($description)>=240?'index,follow,max-image-preview:large':'noindex,follow'?>"><script type="application/ld+json"><?=json_encode(['@context'=>'https://schema.org','@type'=>'Article','headline'=>$book['headline'],'description'=>$meta,'mainEntityOfPage'=>['@type'=>'WebPage','@id'=>$canonical],'author'=>['@type'=>'Organization','name'=>'Reddott Films'],'publisher'=>['@type'=>'Organization','name'=>'Reddott Films']],JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE)?></script><style>body{margin:0;background:#0b0d12;color:#f5f7fb;font-family:Arial,sans-serif}.wrap{width:min(900px,calc(100% - 32px));margin:32px auto}.card{background:#151922;border:1px solid #2a3140;border-radius:18px;padding:24px;margin-bottom:18px}.article p,.article li{font-size:17px;line-height:1.85;color:#d9deea}.article h2{font-size:26px;margin-top:34px}.tag{display:inline-block;border:1px solid #343d4e;border-radius:999px;padding:7px 10px;margin:4px;color:#cbd3e0;font-size:13px}.video{position:relative;padding-top:56.25%;overflow:hidden;border-radius:14px;background:#000;margin:20px 0}.video iframe{position:absolute;inset:0;width:100%;height:100%;border:0}.muted{color:#aeb7c6}</style></head><body><main class="wrap"><header class="card"><p class="muted">Reddott Films · <?=htmlspecialchars($book['topic'],ENT_QUOTES,'UTF-8')?></p><h1><?=htmlspecialchars($book['headline'],ENT_QUOTES,'UTF-8')?></h1><?php foreach($book['keywords'] as $k):?><span class="tag"><?=htmlspecialchars($k,ENT_QUOTES,'UTF-8')?></span><?php endforeach;?></header><article class="card article"><div class="video"><iframe src="https://www.youtube.com/embed/<?=htmlspecialchars($id,ENT_QUOTES,'UTF-8')?>" title="<?=htmlspecialchars($title,ENT_QUOTES,'UTF-8')?>" loading="lazy" allowfullscreen></iframe></div><p><?=$book['intro']?></p><?php foreach($book['sections'] as $section):?><h2><?=htmlspecialchars($section[0],ENT_QUOTES,'UTF-8')?></h2><p><?=htmlspecialchars($section[1],ENT_QUOTES,'UTF-8')?></p><?php endforeach;?><h2><?=htmlspecialchars($enh['heading'],ENT_QUOTES,'UTF-8')?></h2><p><?=$enh['body']?></p><?php if($description):?><h2>Creator-published description</h2><p><?=nl2br(htmlspecialchars($description,ENT_QUOTES,'UTF-8'))?></p><?php endif;?><h2>Search intent and useful questions</h2><p>This page is built to answer informational searches around <?=htmlspecialchars($book['subject'],ENT_QUOTES,'UTF-8')?> without relying on keyword repetition. It explains the topic, provides a viewing framework and identifies what can and cannot be verified from the published source.</p><h2>Frequently asked questions</h2><?php foreach($book['faq'] as $q=>$a):?><h3><?=htmlspecialchars($q,ENT_QUOTES,'UTF-8')?></h3><p><?=htmlspecialchars($a,ENT_QUOTES,'UTF-8')?></p><?php endforeach;?><p><a href="https://www.youtube.com/watch?v=<?=rawurlencode($id)?>" target="_blank" rel="noopener">Watch the original video on YouTube</a></p></article><footer class="card muted">Editorial note: transcript-derived context is used only when a caption track is actually available. No unseen scenes, quotes, statistics or claims are invented. Review content before monetization.</footer></main></body></html>

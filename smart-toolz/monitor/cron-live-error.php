<?php
declare(strict_types=1);

/**
 * SmartToolz live-error crawler.
 * Crawls up to 2000 same-host PAGE URLs per run.
 * External CDN/OAuth/assets are never converted into local URLs.
 * No Discord, OpenAI, or GitHub secrets are used.
 */
$baseUrl='https://smarttoolz.in/smart-toolz/';
$reportFile=__DIR__.'/latest-report.json';
$maxPages=2000;
$timeout=12;
$crawlerVersion='4.1';

function write_report(string $file,array $report):void{
    @file_put_contents($file,json_encode($report,JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE).PHP_EOL,LOCK_EX);
}
function fetch_url(string $url,int $timeout):array{
    if(!function_exists('curl_init'))throw new RuntimeException('PHP cURL extension is not enabled');
    $ch=curl_init($url);
    curl_setopt_array($ch,[CURLOPT_RETURNTRANSFER=>true,CURLOPT_FOLLOWLOCATION=>false,CURLOPT_MAXREDIRS=>0,CURLOPT_CONNECTTIMEOUT=>5,CURLOPT_TIMEOUT=>$timeout,CURLOPT_USERAGENT=>'SmartToolz-Live-Crawler/4.1']);
    $body=curl_exec($ch);$status=(int)curl_getinfo($ch,CURLINFO_HTTP_CODE);$type=(string)curl_getinfo($ch,CURLINFO_CONTENT_TYPE);$redirect=(string)curl_getinfo($ch,CURLINFO_REDIRECT_URL);$error=curl_error($ch);curl_close($ch);
    if($body===false)throw new RuntimeException($error!==''?$error:'HTTP request failed');
    return[$status,$type,(string)$body,$redirect];
}
function crawlable(string $url,string $host):bool{
    if($url===''||preg_match('/(?:\$\d+|\{\{|\}\})/',$url))return false;
    if(preg_match('/^https?:\/\/[^\/]+\/https?:\/\//i',$url))return false;
    $p=parse_url($url);if($p===false)return false;
    if(!in_array(strtolower((string)($p['scheme']??'')),['http','https'],true))return false;
    if(strtolower((string)($p['host']??''))!==$host)return false;
    $path=(string)($p['path']??'/');
    if(str_contains($path,'://')||preg_match('#^/https?://#i',$path))return false;
    if(preg_match('/\.(?:css|js|png|jpe?g|gif|svg|webp|ico|woff2?|ttf|eot|pdf|zip)$/i',$path))return false;
    return true;
}
function abs_url(string $base,string $href):?string{
    $href=trim(html_entity_decode($href,ENT_QUOTES|ENT_HTML5,'UTF-8'));
    if($href===''||preg_match('/^(#|mailto:|tel:|javascript:|data:|blob:)/i',$href))return null;
    if(preg_match('#(^|/)https?://#i',$href))return null;
    $bp=parse_url($base);if(!$bp||empty($bp['host']))return null;
    if(str_starts_with($href,'//'))return($bp['scheme']??'https').':'.$href;
    if(preg_match('/^https?:\/\//i',$href))return$href;
    $origin=($bp['scheme']??'https').'://'.$bp['host'].(isset($bp['port'])?':'.$bp['port']:'');
    if(str_starts_with($href,'/'))return$origin.$href;
    $dir=rtrim(str_replace('\\','/',dirname($bp['path']??'/')),'/');
    return$origin.($dir?'/'.$dir:'').'/'.ltrim($href,'/');
}
function queue_links(string $pageUrl,string $html,string $host,array &$queue,array &$queued,array &$seen,int $maxPages):void{
    if(count($queue)>=$maxPages*2)return;
    preg_match_all('/<a\b[^>]*?href\s*=\s*["\']([^"\']+)["\']/i',$html,$m);
    foreach($m[1]??[] as $href){
        $next=abs_url($pageUrl,$href);if(!$next||!crawlable($next,$host))continue;
        $next=preg_replace('/#.*$/','',$next);
        if(!$next||isset($seen[$next])||isset($queued[$next]))continue;
        $queued[$next]=true;$queue[]=$next;
        if(count($queue)>=$maxPages*2)break;
    }
}
function seed_sources(string $host,array &$queue,array &$queued,int $timeout):void{
    foreach(['https://smarttoolz.in/sitemap.xml','https://smarttoolz.in/robots.txt','https://smarttoolz.in/smart-toolz/sitemap.xml'] as $source){
        try{[$status,$type,$body,$redirect]=fetch_url($source,$timeout);}catch(Throwable){continue;}
        if($status>=400)continue;
        if(str_ends_with(strtolower((string)parse_url($source,PHP_URL_PATH)),'robots.txt')){
            if(preg_match_all('/^sitemap:\s*(\S+)/im',$body,$sm))foreach($sm[1] as $s){$s=trim($s);if(crawlable($s,$host)&&!isset($queued[$s])){$queued[$s]=true;$queue[]=$s;}}
            continue;
        }
        if(preg_match_all('/<loc>\s*(.*?)\s*<\/loc>/is',$body,$locs))foreach($locs[1] as $loc){$loc=trim(html_entity_decode($loc,ENT_QUOTES|ENT_HTML5,'UTF-8'));if(crawlable($loc,$host)&&!isset($queued[$loc])){$queued[$loc]=true;$queue[]=$loc;}}
    }
}

$started=gmdate('c');
write_report($reportFile,['generatedAt'=>$started,'baseUrl'=>$baseUrl,'crawlerVersion'=>$crawlerVersion,'status'=>'running','pagesChecked'=>0,'errorCount'=>0,'errors'=>[],'checked'=>[],'maxPages'=>$maxPages]);

try{
    $host=strtolower((string)parse_url($baseUrl,PHP_URL_HOST));
    $queue=[$baseUrl];$queued=[$baseUrl=>true];$seen=[];$checked=[];$errors=[];
    seed_sources($host,$queue,$queued,$timeout);
    while($queue&&count($checked)<$maxPages){
        $url=preg_replace('/#.*$/','',(string)array_shift($queue));
        if(!$url||isset($seen[$url])||!crawlable($url,$host))continue;
        $seen[$url]=true;
        try{
            [$status,$type,$html,$redirect]=fetch_url($url,$timeout);
            $item=['url'=>$url,'status'=>$status,'contentType'=>$type];if($redirect!=='')$item['redirectTo']=$redirect;$checked[]=$item;
            if($status>=400)$errors[]=['type'=>'HTTP','url'=>$url,'detail'=>'HTTP '.$status];
            if($status>=200&&$status<300&&stripos($type,'text/html')!==false){
                $lower=strtolower($html);
                foreach(['fatal error','uncaught error','uncaught exception','parse error','allowed memory size','call to undefined function','call to a member function','internal server error'] as $marker){
                    $pos=strpos($lower,$marker);
                    if($pos!==false){$errors[]=['type'=>'PAGE_ERROR','url'=>$url,'detail'=>trim((string)preg_replace('/\s+/',' ',substr($html,max(0,$pos-300),1000))),'marker'=>$marker];break;}
                }
                queue_links($url,$html,$host,$queue,$queued,$seen,$maxPages);
            }
        }catch(Throwable $e){
            $checked[]=['url'=>$url,'status'=>null,'contentType'=>''];
            $errors[]=['type'=>'REQUEST','url'=>$url,'detail'=>$e->getMessage()];
        }
    }
    $report=['generatedAt'=>gmdate('c'),'baseUrl'=>$baseUrl,'crawlerVersion'=>$crawlerVersion,'status'=>$errors?'error':'clean','pagesChecked'=>count($checked),'errorCount'=>count($errors),'errors'=>$errors,'checked'=>$checked,'maxPages'=>$maxPages,'queueRemaining'=>count($queue)];
    write_report($reportFile,$report);
    echo json_encode($report,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE).PHP_EOL;
    exit($errors?1:0);
}catch(Throwable $e){
    $report=['generatedAt'=>gmdate('c'),'baseUrl'=>$baseUrl,'crawlerVersion'=>$crawlerVersion,'status'=>'error','pagesChecked'=>0,'errorCount'=>1,'errors'=>[['type'=>'CRON_FATAL','url'=>$baseUrl,'detail'=>$e->getMessage()]],'checked'=>[],'maxPages'=>$maxPages];
    write_report($reportFile,$report);
    echo json_encode($report,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE).PHP_EOL;
    exit(1);
}

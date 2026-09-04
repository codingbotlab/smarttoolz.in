<?php
declare(strict_types=1);

/**
 * SmartToolz live-error crawler.
 * This file is self-contained and can be run either by Hostinger Cron CLI
 * or by an HTTP request. It does not use Discord, OpenAI, or GitHub secrets.
 */
$baseUrl='https://smarttoolz.in/smart-toolz/';
$reportFile=__DIR__.'/latest-report.json';
$maxPages=30;
$timeout=12;

function write_report(string $file,array $report):void{
    @file_put_contents($file,json_encode($report,JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE).PHP_EOL,LOCK_EX);
}
function fetch_url(string $url,int $timeout):array{
    if(!function_exists('curl_init')) throw new RuntimeException('PHP cURL extension is not enabled');
    $ch=curl_init($url);
    curl_setopt_array($ch,[CURLOPT_RETURNTRANSFER=>true,CURLOPT_FOLLOWLOCATION=>true,CURLOPT_MAXREDIRS=>5,CURLOPT_CONNECTTIMEOUT=>5,CURLOPT_TIMEOUT=>$timeout,CURLOPT_USERAGENT=>'SmartToolz-Live-Crawler/1.1']);
    $body=curl_exec($ch);$status=(int)curl_getinfo($ch,CURLINFO_HTTP_CODE);$type=(string)curl_getinfo($ch,CURLINFO_CONTENT_TYPE);$error=curl_error($ch);curl_close($ch);
    if($body===false) throw new RuntimeException($error?:'HTTP request failed');
    return [$status,$type,(string)$body];
}
function abs_url(string $base,string $href):?string{
    $href=trim($href);if($href===''||preg_match('/^(#|mailto:|tel:|javascript:|data:)/i',$href))return null;
    $bp=parse_url($base);if(!$bp||empty($bp['host']))return null;
    $hp=parse_url($href);
    if(isset($hp['scheme']))return in_array(strtolower($hp['scheme']),['http','https'],true)?$href:null;
    $origin=($bp['scheme']??'https').'://'.$bp['host'].(isset($bp['port'])?':'.$bp['port']:'');
    if(str_starts_with($href,'//'))return ($bp['scheme']??'https').':'.$href;
    if(str_starts_with($href,'/'))return $origin.$href;
    return rtrim($origin.dirname($bp['path']??'/'),'/').'/'.ltrim($href,'/');
}

$started=gmdate('c');
write_report($reportFile,['generatedAt'=>$started,'baseUrl'=>$baseUrl,'status'=>'running','pagesChecked'=>0,'errorCount'=>0,'errors'=>[],'checked'=>[]]);

try{
    $host=strtolower((string)parse_url($baseUrl,PHP_URL_HOST));
    $queue=[$baseUrl];$seen=[];$checked=[];$errors=[];
    while($queue&&count($checked)<$maxPages){
        $url=preg_replace('/#.*$/','',(string)array_shift($queue));
        if(!$url||isset($seen[$url]))continue;$seen[$url]=true;
        try{
            [$status,$type,$html]=fetch_url($url,$timeout);
            $checked[]=['url'=>$url,'status'=>$status,'contentType'=>$type];
            if($status<200||$status>=400)$errors[]=['type'=>'HTTP','url'=>$url,'detail'=>'HTTP '.$status];
            $lower=strtolower($html);
            foreach(['fatal error','uncaught error','uncaught exception','parse error','internal server error','allowed memory size','call to undefined function'] as $marker){
                $pos=strpos($lower,$marker);if($pos!==false){$excerpt=preg_replace('/\s+/',' ',substr($html,max(0,$pos-250),900));$errors[]=['type'=>'PAGE_ERROR','url'=>$url,'detail'=>trim((string)$excerpt),'marker'=>$marker];break;}
            }
            if(stripos($type,'text/html')!==false){
                preg_match_all('/<a[^>]+href=["\']([^"\'#]+)["\']/i',$html,$m);
                foreach($m[1]??[] as $href){$next=abs_url($url,$href);if(!$next)continue;if(strtolower((string)parse_url($next,PHP_URL_HOST))!==$host)continue;if(!isset($seen[$next])&&count($queue)<$maxPages*2)$queue[]=$next;}
            }
        }catch(Throwable $e){$checked[]=['url'=>$url,'status'=>null,'contentType'=>''];$errors[]=['type'=>'REQUEST','url'=>$url,'detail'=>$e->getMessage()];}
    }
    $report=['generatedAt'=>gmdate('c'),'baseUrl'=>$baseUrl,'status'=>$errors?'error':'clean','pagesChecked'=>count($checked),'errorCount'=>count($errors),'errors'=>$errors,'checked'=>$checked];
    write_report($reportFile,$report);
    echo json_encode($report,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE).PHP_EOL;
    exit($errors?1:0);
}catch(Throwable $e){
    $report=['generatedAt'=>gmdate('c'),'baseUrl'=>$baseUrl,'status'=>'error','pagesChecked'=>0,'errorCount'=>1,'errors'=>[['type'=>'CRON_FATAL','url'=>$baseUrl,'detail'=>$e->getMessage()]],'checked'=>[]];
    write_report($reportFile,$report);echo json_encode($report,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE).PHP_EOL;exit(1);
}

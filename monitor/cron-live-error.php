<?php
declare(strict_types=1);

// Self-contained Hostinger Cron entrypoint for a deployment rooted at
// /public_html/smart-toolz. No nested script is required.
$reportFile = __DIR__ . '/latest-report.json';
$logFile = __DIR__ . '/cron.log';
$baseUrl = getenv('CRAWLER_BASE_URL') ?: 'https://smarttoolz.in/smart-toolz/';
$maxPages = max(1, (int)(getenv('CRAWLER_MAX_PAGES') ?: 40));
$timeout = max(1000, (int)(getenv('CRAWLER_TIMEOUT_MS') ?: 15000));

function monitor_log(string $message): void { global $logFile; @file_put_contents($logFile, '['.gmdate('c').'] '.$message.PHP_EOL, FILE_APPEND|LOCK_EX); }
function monitor_report(array $report): void { global $reportFile; @file_put_contents($reportFile, json_encode($report, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE).PHP_EOL, LOCK_EX); }
function monitor_fetch(string $url,int $timeout): array {
    if(!function_exists('curl_init')) throw new RuntimeException('PHP cURL extension is not enabled');
    $ch=curl_init($url);curl_setopt_array($ch,[CURLOPT_RETURNTRANSFER=>true,CURLOPT_FOLLOWLOCATION=>true,CURLOPT_MAXREDIRS=>5,CURLOPT_CONNECTTIMEOUT_MS=>min(5000,$timeout),CURLOPT_TIMEOUT_MS=>$timeout,CURLOPT_USERAGENT=>'SmartToolz-Live-Crawler/1.0']);
    $body=curl_exec($ch);$errno=curl_errno($ch);$err=curl_error($ch);$status=(int)curl_getinfo($ch,CURLINFO_HTTP_CODE);$type=(string)curl_getinfo($ch,CURLINFO_CONTENT_TYPE);curl_close($ch);
    if($body===false||$errno)throw new RuntimeException($err?:"cURL error $errno");return [$status,$type,(string)$body];
}
function monitor_crawl(string $base,int $max,int $timeout): array {
    $host=strtolower((string)parse_url($base,PHP_URL_HOST));$queue=[$base];$seen=[];$checked=[];$errors=[];
    while($queue&&count($checked)<$max){$url=preg_replace('/#.*$/','',(string)array_shift($queue));if($url===''||isset($seen[$url]))continue;$seen[$url]=1;
        try{[$status,$type,$html]=monitor_fetch($url,$timeout);$checked[]=['url'=>$url,'status'=>$status,'contentType'=>$type];if($status<200||$status>=400)$errors[]=['type'=>'HTTP','url'=>$url,'detail'=>"HTTP $status"];
            $lower=strtolower($html);foreach(['fatal error','uncaught error','uncaught exception','parse error','maximum execution time','allowed memory size','call to undefined function','call to a member function','internal server error'] as $marker){$p=strpos($lower,$marker);if($p!==false){$errors[]=['type'=>'PAGE_ERROR','url'=>$url,'detail'=>trim((string)preg_replace('/\s+/',' ',substr($html,max(0,$p-250),900)))];break;}}
            if(stripos($type,'text/html')!==false){preg_match_all('/(?:href|src|action)=["\']([^"\']+)["\']/i',$html,$m);foreach($m[1]??[] as $raw){if(preg_match('/^(#|mailto:|javascript:|tel:)/i',$raw))continue;$parts=parse_url($raw);if(isset($parts['scheme'])&&!in_array(strtolower($parts['scheme']),['http','https'],true))continue;if(isset($parts['host'])&&strtolower($parts['host'])!==$host)continue;$next=isset($parts['scheme'])?$raw:(str_starts_with($raw,'/')?'https://'.$host.$raw:rtrim($base,'/').'/'.ltrim($raw,'/'));$next=preg_replace('/#.*$/','',$next);if(!isset($seen[$next])&&count($queue)<$max*3)$queue[]=$next;}}
        }catch(Throwable $e){$checked[]=['url'=>$url,'status'=>null,'contentType'=>''];$errors[]=['type'=>'REQUEST','url'=>$url,'detail'=>$e->getMessage()];}
    }return [$checked,$errors];
}

monitor_log('START php='.PHP_VERSION.' sapi='.PHP_SAPI.' cwd='.getcwd());
register_shutdown_function(function():void{$e=error_get_last();if($e&&in_array($e['type'],[E_ERROR,E_PARSE,E_CORE_ERROR,E_COMPILE_ERROR],true))monitor_log('FATAL '.$e['message'].' @ '.$e['file'].':'.$e['line']);else monitor_log('END memory='.memory_get_peak_usage(true));});
try{
    monitor_report(['generatedAt'=>gmdate('c'),'baseUrl'=>$baseUrl,'status'=>'running','pagesChecked'=>0,'errorCount'=>0,'errors'=>[],'checked'=>[]]);
    [$checked,$errors]=monitor_crawl($baseUrl,$maxPages,$timeout);
    $report=['generatedAt'=>gmdate('c'),'baseUrl'=>$baseUrl,'status'=>$errors?'error':'ok','pagesChecked'=>count($checked),'errorCount'=>count($errors),'errors'=>$errors,'checked'=>$checked];monitor_report($report);monitor_log('CRAWL pages='.count($checked).' errors='.count($errors));echo json_encode($report,JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE).PHP_EOL;exit($errors?1:0);
}catch(Throwable $e){$report=['generatedAt'=>gmdate('c'),'baseUrl'=>$baseUrl,'status'=>'error','pagesChecked'=>0,'errorCount'=>1,'errors'=>[['type'=>'CRON_FATAL','url'=>$baseUrl,'detail'=>$e->getMessage()]],'checked'=>[]];monitor_report($report);monitor_log('UNCAUGHT '.get_class($e).': '.$e->getMessage());echo json_encode($report,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE).PHP_EOL;exit(1);}

<?php
declare(strict_types=1);

/**
 * SmartToolz live error crawler.
 * Hostinger Cron: every 5 minutes.
 *
 * Writes monitor/latest-report.json and monitor/cron.log so a silent Cron
 * execution is diagnosable even when Hostinger's View Output is blank.
 */

$baseUrl = getenv('CRAWLER_BASE_URL') ?: 'https://smarttoolz.in/smart-toolz/';
$maxPages = max(1, (int)(getenv('CRAWLER_MAX_PAGES') ?: 40));
$timeout = max(1000, (int)(getenv('CRAWLER_TIMEOUT_MS') ?: 15000));
$discord = getenv('DISCORD_WEBHOOK_URL') ?: '';
$openaiKey = getenv('OPENAI_API_KEY') ?: '';
$openaiModel = getenv('OPENAI_MODEL') ?: 'gpt-5';
$githubToken = getenv('GITHUB_TOKEN') ?: '';
$githubRepo = getenv('GITHUB_REPO') ?: 'codingbotlab/smarttoolz.in';
$githubBranch = getenv('GITHUB_BRANCH') ?: 'main';
$maxAttempts = max(1, min(3, (int)(getenv('FIX_MAX_ATTEMPTS') ?: 3)));
$reportFile = __DIR__ . '/latest-report.json';
$logFile = __DIR__ . '/cron.log';

function log_line(string $message): void {
    global $logFile;
    $line = '[' . date('c') . '] ' . $message . PHP_EOL;
    @file_put_contents($logFile, $line, FILE_APPEND | LOCK_EX);
}

function http_get(string $url, int $timeout): array {
    if (!function_exists('curl_init')) throw new RuntimeException('PHP cURL extension is not enabled');
    $ch = curl_init($url);
    curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER=>true,CURLOPT_FOLLOWLOCATION=>true,CURLOPT_MAXREDIRS=>5,CURLOPT_CONNECTTIMEOUT_MS=>min(5000,$timeout),CURLOPT_TIMEOUT_MS=>$timeout,CURLOPT_USERAGENT=>'SmartToolz-Hostinger-Cron/1.1',CURLOPT_HTTPHEADER=>['Accept: text/html,application/xhtml+xml,*/*;q=0.8']]);
    $body = curl_exec($ch);
    $errno = curl_errno($ch); $error = curl_error($ch); $status = (int)curl_getinfo($ch,CURLINFO_HTTP_CODE); $type = (string)curl_getinfo($ch,CURLINFO_CONTENT_TYPE);
    curl_close($ch);
    if ($body === false || $errno) throw new RuntimeException($error ?: "cURL error $errno");
    return [$status,$type,(string)$body];
}

function add_error(array &$errors,string $type,string $url,string $detail,array $extra=[]): void {
    $errors[] = array_merge(['type'=>$type,'url'=>$url,'detail'=>$detail],$extra);
}

function discord_send(string $webhook,string $content): void {
    if (!$webhook || !function_exists('curl_init')) return;
    $ch=curl_init($webhook);
    curl_setopt_array($ch,[CURLOPT_POST=>true,CURLOPT_RETURNTRANSFER=>true,CURLOPT_HTTPHEADER=>['Content-Type: application/json'],CURLOPT_POSTFIELDS=>json_encode(['content'=>substr($content,0,1900)],JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES),CURLOPT_TIMEOUT=>15]);
    curl_exec($ch); curl_close($ch);
}

function crawl(string $baseUrl,int $maxPages,int $timeout): array {
    $originHost = strtolower((string)parse_url($baseUrl,PHP_URL_HOST));
    $seen=[]; $queue=[$baseUrl]; $checked=[]; $errors=[];
    while($queue && count($checked)<$maxPages){
        $url=array_shift($queue); $url=preg_replace('/#.*$/','',$url);
        if(!$url || isset($seen[$url])) continue;
        $seen[$url]=true;
        try{
            [$status,$type,$text]=http_get($url,$timeout);
            $checked[]=['url'=>$url,'status'=>$status,'contentType'=>$type];
            if($status<200 || $status>=400) add_error($errors,'HTTP',$url,"HTTP $status");
            $markers=['fatal error','uncaught error','uncaught exception','parse error','maximum execution time','allowed memory size','call to undefined function','call to a member function','internal server error'];
            $lower=strtolower($text);
            foreach($markers as $marker){
                $i=strpos($lower,$marker);
                if($i!==false){$excerpt=preg_replace('/\s+/',' ',substr($text,max(0,$i-250),900));add_error($errors,'PAGE_ERROR',$url,trim((string)$excerpt),['marker'=>$marker]);break;}
            }
            if(stripos($type,'text/html')!==false){
                preg_match_all('/(?:href|src|action)=["\']([^"\']+)["\']/i',$text,$matches);
                foreach($matches[1]??[] as $raw){
                    if(str_starts_with($raw,'#')||str_starts_with($raw,'mailto:')||str_starts_with($raw,'javascript:')) continue;
                    $u=parse_url($raw);
                    if(isset($u['scheme'])&&!in_array(strtolower($u['scheme']),['http','https'],true)) continue;
                    if(isset($u['host'])&&strtolower($u['host'])!==$originHost) continue;
                    $next=$raw;
                    if(!isset($u['scheme'])) $next=rtrim($baseUrl,'/').'/'.ltrim($raw,'/');
                    $next=preg_replace('/#.*$/','',$next);
                    if(!isset($seen[$next])&&count($queue)<$maxPages*3)$queue[]=$next;
                }
            }
        }catch(Throwable $e){
            $checked[]=['url'=>$url,'status'=>null,'contentType'=>'']; add_error($errors,'REQUEST',$url,$e->getMessage());
        }
    }
    return [$checked,$errors];
}

function write_report(string $file,string $baseUrl,array $checked,array $errors): array {
    $report=['generatedAt'=>gmdate('c'),'baseUrl'=>$baseUrl,'pagesChecked'=>count($checked),'errorCount'=>count($errors),'errors'=>$errors,'checked'=>$checked];
    @file_put_contents($file,json_encode($report,JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE),LOCK_EX);
    return $report;
}

function github_request(string $method,string $url,string $token,?array $payload=null): array {
    if(!$token) throw new RuntimeException('GITHUB_TOKEN is missing');
    $ch=curl_init($url); $headers=['Accept: application/vnd.github+json','Authorization: Bearer '.$token,'X-GitHub-Api-Version: 2022-11-28'];
    curl_setopt_array($ch,[CURLOPT_RETURNTRANSFER=>true,CURLOPT_CUSTOMREQUEST=>$method,CURLOPT_HTTPHEADER=>$headers,CURLOPT_TIMEOUT=>30]);
    if($payload!==null){$headers[]='Content-Type: application/json';curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode($payload,JSON_UNESCAPED_SLASHES));}
    $body=curl_exec($ch);$status=(int)curl_getinfo($ch,CURLINFO_HTTP_CODE);$err=curl_error($ch);curl_close($ch);
    if($body===false)throw new RuntimeException('GitHub cURL: '.$err);
    $json=json_decode($body,true);if($status>=300)throw new RuntimeException("GitHub API $status: ".substr($body,0,1000));
    return [$status,is_array($json)?$json:[]];
}

function apply_ai_fix(array $report,string $key,string $model): ?array {
    if(!$key||!$report['errors'])return null;
    $prompt="You are a conservative production bug fixer for SmartToolz. Analyze ONLY these live crawler errors. Return STRICT JSON with summary and files. Each file must contain complete replacement UTF-8 content. Only modify files required for the concrete error. Do not add dependencies, secrets, unrelated refactors, or change public URLs unless required. Allowed source paths begin with smart-toolz/ or monitor/.\n\nLIVE REPORT:\n".json_encode($report['errors'],JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
    $ch=curl_init('https://api.openai.com/v1/responses');
    curl_setopt_array($ch,[CURLOPT_POST=>true,CURLOPT_RETURNTRANSFER=>true,CURLOPT_HTTPHEADER=>['Authorization: Bearer '.$key,'Content-Type: application/json'],CURLOPT_POSTFIELDS=>json_encode(['model'=>$model,'input'=>$prompt]),CURLOPT_TIMEOUT=>90]);
    $body=curl_exec($ch);$status=(int)curl_getinfo($ch,CURLINFO_HTTP_CODE);$err=curl_error($ch);curl_close($ch);
    if($body===false||$status>=300)throw new RuntimeException('OpenAI API '.$status.': '.($err?:substr((string)$body,0,1200)));
    $data=json_decode($body,true);$text=$data['output_text']??'';
    if(!$text&&isset($data['output']))foreach($data['output'] as $item)foreach(($item['content']??[]) as $part)$text.=$part['text']??'';
    if(preg_match('/\{[\s\S]*\}/',$text,$m))$text=$m[0];
    $patch=json_decode($text,true);if(!is_array($patch)||!isset($patch['files'])||!is_array($patch['files']))throw new RuntimeException('AI returned invalid patch JSON');
    return $patch;
}

log_line('START pid='.getmypid().' php='.PHP_VERSION.' sapi='.PHP_SAPI.' cwd='.getcwd());
register_shutdown_function(function(){ $e=error_get_last(); if($e && in_array($e['type'],[E_ERROR,E_PARSE,E_CORE_ERROR,E_COMPILE_ERROR],true)){log_line('FATAL type='.$e['type'].' file='.$e['file'].' line='.$e['line'].' message='.$e['message']);}else{log_line('END peak_memory='.memory_get_peak_usage(true));}});

try{
    [$checked,$errors]=crawl($baseUrl,$maxPages,$timeout);
    $report=write_report($reportFile,$baseUrl,$checked,$errors);
    log_line('CRAWL pages='.count($checked).' errors='.count($errors));
    if($errors){
        $summary="🔎 SmartToolz LIVE ERROR\nURL: $baseUrl\nPages: ".count($checked)."\nErrors: ".count($errors)."\nTime: ".$report['generatedAt'];
        foreach(array_slice($errors,0,8) as $i=>$e)$summary.="\n\n".($i+1).". {$e['type']}\n{$e['url']}\n".substr($e['detail'],0,700);
        discord_send($discord,$summary); log_line('DISCORD error report attempted');
    }
    if($errors&&$openaiKey&&$githubToken){
        for($attempt=1;$attempt<=$maxAttempts;$attempt++){
            try{
                $patch=apply_ai_fix($report,$openaiKey,$openaiModel);if(!$patch||empty($patch['files']))break;$changed=[];
                foreach($patch['files'] as $file){
                    $path=(string)($file['path']??'');$content=$file['content']??null;
                    if(!$path||!is_string($content)||$content==='')throw new RuntimeException('Rejected empty patch');
                    if(!str_starts_with($path,'smart-toolz/')&&!str_starts_with($path,'monitor/'))throw new RuntimeException('Rejected path: '.$path);
                    $api="https://api.github.com/repos/{$githubRepo}/contents/".str_replace('%2F','/',rawurlencode($path)).'?ref='.rawurlencode($githubBranch);
                    [, $existing]=github_request('GET',$api,$githubToken);$sha=$existing['sha']??null;
                    $payload=['message'=>'Auto-fix live error: '.substr((string)($patch['summary']??'production error'),0,120),'content'=>base64_encode($content),'branch'=>$githubBranch];if($sha)$payload['sha']=$sha;
                    github_request('PUT',$api,$githubToken,$payload);$changed[]=$path;
                }
                log_line('FIX attempt='.$attempt.' changed='.implode(',',$changed));
                discord_send($discord,"🔧 SmartToolz Auto-Fix pushed\nAttempt: $attempt/$maxAttempts\n".implode(', ',$changed)."\n".($patch['summary']??'Fix pushed'));
                sleep(45);[$checked,$errors]=crawl($baseUrl,$maxPages,$timeout);$report=write_report($reportFile,$baseUrl,$checked,$errors);log_line('VERIFY attempt='.$attempt.' pages='.count($checked).' errors='.count($errors));
                if(!$errors){discord_send($discord,"✅ SmartToolz Auto-Fix verified\nLive crawl is clean after attempt $attempt.");break;}
            }catch(Throwable $e){log_line('FIX_ERROR attempt='.$attempt.' '.$e->getMessage());discord_send($discord,"🔴 SmartToolz Auto-Fix error\nAttempt $attempt/$maxAttempts\n".$e->getMessage());break;}
        }
    }elseif($errors){
        log_line('AUTOFIX skipped; missing '.(!$openaiKey?'OPENAI_API_KEY ':'').(!$githubToken?'GITHUB_TOKEN':'').'.');
    }
    echo json_encode($report,JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE).PHP_EOL;
    exit($report['errorCount']?1:0);
}catch(Throwable $e){
    log_line('UNCAUGHT '.get_class($e).': '.$e->getMessage().' @ '.$e->getFile().':'.$e->getLine());
    $report=['generatedAt'=>gmdate('c'),'baseUrl'=>$baseUrl,'pagesChecked'=>0,'errorCount'=>1,'errors'=>[['type'=>'CRON_FATAL','url'=>$baseUrl,'detail'=>$e->getMessage()]],'checked'=>[]];
    @file_put_contents($reportFile,json_encode($report,JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE),LOCK_EX);
    echo json_encode($report,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE).PHP_EOL;
    exit(1);
}

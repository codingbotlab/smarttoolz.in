<?php
declare(strict_types=1);
const KEDDY_GOOGLE_CLIENT_ID='52634723911-fe1ha1bs7p8nloc5phn0eimrg4qgism3.apps.googleusercontent.com';
const KEDDY_REDIRECT_URI='https://smarttoolz.in/reddott-films-youtube-oauth.php';
function cfg():array{
    $p=__DIR__.'/config.php';
    if(is_file($p)) {$c=require $p; if(is_array($c)) return array_merge($c,['google_client_id'=>getenv('KEDDY_GOOGLE_CLIENT_ID')?:($c['google_client_id']??KEDDY_GOOGLE_CLIENT_ID),'google_client_secret'=>getenv('KEDDY_GOOGLE_CLIENT_SECRET')?:($c['google_client_secret']??''),'redirect_uri'=>getenv('KEDDY_REDIRECT_URI')?:($c['redirect_uri']??KEDDY_REDIRECT_URI)]);}
    $runtime=__DIR__.'/data/credentials.php';
    if(is_file($runtime)){
        $c=require $runtime;
        if(is_array($c)) return array_merge(['app_secret'=>getenv('KEDDY_APP_SECRET')?:'CHANGE-ME','redirect_uri'=>KEDDY_REDIRECT_URI,'ai_url'=>getenv('KEDDY_AI_URL')?:'','ai_key'=>getenv('KEDDY_AI_KEY')?:'','ai_model'=>getenv('KEDDY_AI_MODEL')?:'gpt-4o-mini','database'=>__DIR__.'/data/keddy.sqlite'],['google_client_id'=>getenv('KEDDY_GOOGLE_CLIENT_ID')?:KEDDY_GOOGLE_CLIENT_ID,'google_client_secret'=>getenv('KEDDY_GOOGLE_CLIENT_SECRET')?:($c['google_client_secret']??'')],$c,['google_client_id'=>getenv('KEDDY_GOOGLE_CLIENT_ID')?:($c['google_client_id']??KEDDY_GOOGLE_CLIENT_ID),'redirect_uri'=>getenv('KEDDY_REDIRECT_URI')?:($c['redirect_uri']??KEDDY_REDIRECT_URI)]);
    }
    $c=require __DIR__.'/config.php.example';
    return array_merge($c,['google_client_id'=>getenv('KEDDY_GOOGLE_CLIENT_ID')?:KEDDY_GOOGLE_CLIENT_ID,'google_client_secret'=>getenv('KEDDY_GOOGLE_CLIENT_SECRET')?:($c['google_client_secret']??''),'redirect_uri'=>getenv('KEDDY_REDIRECT_URI')?:KEDDY_REDIRECT_URI]);
}
function db():PDO{static $p;if($p)return$p;$c=cfg();$d=dirname($c['database']);if(!is_dir($d))mkdir($d,0755,true);$p=new PDO('sqlite:'.$c['database']);$p->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);$p->exec('CREATE TABLE IF NOT EXISTS state(k TEXT PRIMARY KEY,v TEXT NOT NULL)');return$p;}
function gv(string$k,?string$d=null):?string{$q=db()->prepare('SELECT v FROM state WHERE k=?');$q->execute([$k]);$v=$q->fetchColumn();return$v===false?$d:(string)$v;}
function sv(string$k,string$v):void{$q=db()->prepare('INSERT INTO state(k,v) VALUES(?,?) ON CONFLICT(k) DO UPDATE SET v=excluded.v');$q->execute([$k,$v]);}
function gj(string$k,$d=null){$v=gv($k);if($v===null)return$d;$x=json_decode($v,true);return$x===null?$d:$x;}
function sj(string$k,$v):void{sv($k,json_encode($v,JSON_UNESCAPED_UNICODE));}
function req(string$u,string$m='GET',array$h=[],?string$b=null):array{$c=curl_init($u);curl_setopt_array($c,[CURLOPT_RETURNTRANSFER=>1,CURLOPT_CUSTOMREQUEST=>$m,CURLOPT_HTTPHEADER=>$h,CURLOPT_TIMEOUT=>25]);if($b!==null)curl_setopt($c,CURLOPT_POSTFIELDS,$b);$r=curl_exec($c);$code=(int)curl_getinfo($c,CURLINFO_HTTP_CODE);$e=curl_error($c);curl_close($c);if($r===false)throw new RuntimeException($e?:'HTTP error');$j=json_decode($r,true)?:[];if($code>=400)throw new RuntimeException('HTTP '.$code.': '.($j['error_description']??$j['error']['message']??$r));return$j;}
function bundledGoogleCredentials():array{
    $path=dirname(__DIR__).'/client_secret_52634723911-fe1ha1bs7p8nloc5phn0eimrg4qgism3.apps.googleusercontent.com.json';
    if(!is_file($path)||!is_readable($path)) return [];
    $j=json_decode((string)file_get_contents($path),true);
    return is_array($j['web']??null)?$j['web']:[];
}
function googleCredentials():array{
    $c=cfg();
    $envId=trim((string)(getenv('KEDDY_GOOGLE_CLIENT_ID')?:''));
    $envSecret=trim((string)(getenv('KEDDY_GOOGLE_CLIENT_SECRET')?:''));
    if($envId!==''&&$envSecret!=='') return ['id'=>$envId,'secret'=>$envSecret,'redirect'=>trim((string)(getenv('KEDDY_REDIRECT_URI')?:KEDDY_REDIRECT_URI))];
    $b=bundledGoogleCredentials();
    $id=trim((string)($b['client_id']??KEDDY_GOOGLE_CLIENT_ID));
    $secret=trim((string)($b['client_secret']??''));
    if($id!==''&&$secret!=='') return ['id'=>$id,'secret'=>$secret,'redirect'=>KEDDY_REDIRECT_URI];
    $id=trim((string)($c['google_client_id']??KEDDY_GOOGLE_CLIENT_ID));
    $secret=trim((string)($c['google_client_secret']??''));
    $redirect=trim((string)($c['redirect_uri']??KEDDY_REDIRECT_URI));
    if($id===''||$secret==='') throw new RuntimeException('Keddy Google OAuth credentials are not configured.');
    return ['id'=>$id,'secret'=>$secret,'redirect'=>$redirect];
}
function token():string{$t=gj('oauth');if(!$t||empty($t['access_token']))throw new RuntimeException('YouTube is not connected.');if(!empty($t['expires_at'])&&time()<(int)$t['expires_at']-60)return$t['access_token'];$c=googleCredentials();$j=req('https://oauth2.googleapis.com/token','POST',['Content-Type: application/x-www-form-urlencoded'],http_build_query(['client_id'=>$c['id'],'client_secret'=>$c['secret'],'refresh_token'=>$t['refresh_token']??'','grant_type'=>'refresh_token']));$t['access_token']=$j['access_token'];$t['expires_at']=time()+(int)($j['expires_in']??3600);sj('oauth',$t);return$t['access_token'];}
function yt(string$p,string$m='GET',?array$data=null):array{$h=['Authorization: Bearer '.token(),'Accept: application/json'];$b=null;if($data!==null){$h[]='Content-Type: application/json';$b=json_encode($data,JSON_UNESCAPED_UNICODE);}return req('https://www.googleapis.com/youtube/v3/'.ltrim($p,'/'),$m,$h,$b);}
function findLive():?array{$j=yt('liveBroadcasts?part=id,snippet,status&broadcastStatus=active&broadcastType=all&maxResults=5');foreach($j['items']??[]as$x){if(!empty($x['snippet']['liveChatId']))return['id'=>$x['id'],'chat'=>$x['snippet']['liveChatId'],'title'=>$x['snippet']['title']??'Live'];}return null;}
function sendMsg(string$m):array{$id=gv('chat_id');if(!$id)throw new RuntimeException('No live chat found. Start the YouTube live first.');return yt('liveChat/messages?part=snippet','POST',['snippet'=>['liveChatId'=>$id,'type'=>'textMessageEvent','textMessageDetails'=>['messageText'=>trim($m)]]]);}
function tasks():array{return gj('tasks',['2 minutes: comfortably baithi raho aur chat ke messages ka reply karo. 👀','2 minutes: seated Q&A round — chat se ek interesting question ka answer do. 💬','2 minutes: camera ki taraf smile karke viewers ko welcome karo. 😊','2 minutes: apni current live feeling chat ke saath share karo. ❤️','2 minutes: seated rapid-fire — chat ke 3 short questions ka answer do. ⚡','2 minutes: seated break — paani piyo aur relax karo. 🧘','2 minutes: viewers se ek fun topic choose karne ko bolo. 🎯','2 minutes: active viewers ko thank you bolo. 🙌']);}
function session():array{return gj('session',['active'=>false,'started'=>0,'index'=>0,'last'=>0,'duration'=>60,'interval'=>2]);}
function saveS(array$s):void{sj('session',$s);}
function startS():array{$l=findLive();if(!$l)throw new RuntimeException('No active YouTube live found. Start the live first.');sv('chat_id',$l['chat']);sv('live_id',$l['id']);sv('chat_token','');$s=['active'=>true,'started'=>time(),'index'=>0,'last'=>0,'duration'=>(int)gv('duration','60'),'interval'=>max(2,(int)gv('interval','2'))];saveS($s);sendMsg('🧑‍🏫 Keddy: Instructor session START! Sabhi tasks seated hain. Ready? Chalo shuru karte hain! 🔥');return$s;}
function stopS():void{$s=session();$s['active']=false;saveS($s);}
function nextS(bool$send=true):array{$a=tasks();$s=session();$i=(int)$s['index']%count($a);$msg='🎯 Keddy Instructor — Task '.($i+1).': '.$a[$i];if($send)sendMsg($msg);$s['index']=$i+1;$s['last']=time();saveS($s);return['task'=>$a[$i],'index'=>$i+1];}
function statusText():string{$s=session();if(!$s['active'])return'🤖 Keddy: Instructor session inactive hai.';$e=max(0,time()-(int)$s['started']);$r=max(0,(int)$s['duration']*60-$e);return'📊 Keddy: '.floor($e/60).' min complete • '.ceil($r/60).' min remaining • next task in '.(int)$s['interval'].' min. 🔥';}
function handleCmd(string$text):?string{$cmd=strtolower((string)(preg_split('/\s+/',trim($text))[0]??''));$a=tasks();$s=session();return match($cmd){ '!task'=>$s['active']?'🎯 Current task: '.$a[max(0,(int)$s['index']-1)%count($a)]:'🤖 Keddy: Session inactive hai.', '!next'=>nextS()['task'], '!status'=>statusText(), '!break'=>'🧘 Keddy: 2-minute seated break. Relax, paani piyo aur chat ke saath baat karo. ❤️', '!help'=>'🤖 Keddy commands: !task • !next • !status • !break • !help',default=>null};}
function poll():void{$id=gv('chat_id');if(!$id)return;$u='liveChat/messages?liveChatId='.rawurlencode($id).'&part=id,snippet,authorDetails&maxResults=200';$p=gv('chat_token');if($p)$u.='&pageToken='.rawurlencode($p);try{$j=yt($u);}catch(Throwable$e){return;}if(!empty($j['nextPageToken']))sv('chat_token',$j['nextPageToken']);foreach($j['items']??[]as$x){$t=$x['snippet']['displayMessage']??'';$r=$t?handleCmd($t):null;if($r!==null)try{sendMsg($r);}catch(Throwable$e){}}}
function tick():array{$l=null;try{$l=findLive();}catch(Throwable$e){}$s=session();if($l){if(gv('chat_id')!==$l['chat']){sv('chat_id',$l['chat']);sv('live_id',$l['id']);sv('chat_token','');}if($s['active']){$e=time()-(int)$s['started'];if($e>=(int)$s['duration']*60){sendMsg('🏁 Keddy: Instructor session complete! Great job! ❤️');stopS();}elseif(!(int)$s['last']||time()-(int)$s['last']>=(int)$s['interval']*60)nextS();}poll();}elseif($s['active'])stopS();return['live'=>$l,'session'=>session()];}

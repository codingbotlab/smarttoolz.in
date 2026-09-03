<?php
declare(strict_types=1);
session_start();
require __DIR__.'/bot.php';

if(empty($_SESSION['keddy_engine_token']))$_SESSION['keddy_engine_token']=bin2hex(random_bytes(24));
$engineToken=$_SESSION['keddy_engine_token'];

function engineModeratorStatus(string $chatId):array{
    try{
        $bot=botChannel();
        $botId=(string)($bot['id']??'');
        if($botId==='')return ['ok'=>false,'confirmed'=>false,'reason'=>'Bot channel ID unavailable'];
        $j=yt('liveChat/moderators?part=snippet&liveChatId='.rawurlencode($chatId).'&maxResults=50');
        foreach($j['items']??[] as $row){
            $id=(string)($row['snippet']['moderatorDetails']['channelId']??'');
            if($id!==$botId)continue;
            try{
                $ownerId=(string)gv('oauth_channel_id','');
                if($ownerId!=='')keddyDataMarkModeratedChannel($ownerId,(string)gv('live_id',''),$chatId,'moderator_verified',['bot_channel_id'=>$botId,'verification'=>'liveChatModerators.list']);
            }catch(Throwable $e){}
            return ['ok'=>true,'confirmed'=>true,'reason'=>'Keddy Bot BTS is listed as a YouTube live-chat moderator'];
        }
        return ['ok'=>true,'confirmed'=>false,'reason'=>'Keddy Bot BTS is not listed as a moderator on this live chat'];
    }catch(Throwable $e){
        return ['ok'=>false,'confirmed'=>false,'reason'=>$e->getMessage()?:'Moderator verification failed'];
    }
}

if(isset($_GET['pulse'])){
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    try{
        $provided=(string)($_GET['token']??'');
        if(!$provided||!hash_equals($engineToken,$provided)){http_response_code(403);echo json_encode(['ok'=>false,'error'=>'Forbidden'],JSON_UNESCAPED_UNICODE);exit;}

        $r=tick();
        $live=$r['live']??null;
        $moderator=false;
        $moderatorReason='No active live';

        if(is_array($live)&&!empty($live['chat'])){
            // Moderator verification is expensive compared with normal chat polling.
            // Cache the result for 10 minutes instead of calling the moderators endpoint every pulse.
            $modChat=(string)gv('engine_mod_chat_id','');
            $modAt=(int)gv('engine_mod_checked_at','0');
            $modOk=(int)gv('engine_mod_confirmed','0')===1;
            $modReason=(string)gv('engine_mod_reason','');
            if($modChat!==$live['chat']||time()-$modAt>=600){
                $check=engineModeratorStatus((string)$live['chat']);
                $modOk=(bool)$check['confirmed'];
                $modReason=(string)$check['reason'];
                sv('engine_mod_chat_id',(string)$live['chat']);
                sv('engine_mod_checked_at',(string)time());
                sv('engine_mod_confirmed',$modOk?'1':'0');
                sv('engine_mod_reason',$modReason);
            }
            $moderator=$modOk;
            $moderatorReason=$modReason?:'Moderator status cached';
            try{maybeSocialTimer();}catch(Throwable $e){}
        }
        echo json_encode(['ok'=>true,'live'=>!empty($live),'live_id'=>(string)($live['id']??''),'chat_id'=>(string)($live['chat']??''),'title'=>(string)($live['title']??''),'moderator'=>$moderator,'moderator_reason'=>$moderatorReason,'session'=>$r['session']??session(),'server_time'=>time(),'engine_interval_seconds'=>15],JSON_UNESCAPED_UNICODE);
    }catch(Throwable $e){http_response_code(500);echo json_encode(['ok'=>false,'error'=>$e->getMessage()],JSON_UNESCAPED_UNICODE);}exit;
}
function engH($v):string{return htmlspecialchars((string)$v,ENT_QUOTES,'UTF-8');}
?>
<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Keddy • Live Engine</title><style>
*{box-sizing:border-box}body{margin:0;background:#080b10;color:#f5f7fb;font:14px/1.5 system-ui,-apple-system,Segoe UI,sans-serif}main{max-width:1050px;margin:auto;padding:24px}.top{display:flex;justify-content:space-between;align-items:center;gap:15px;margin-bottom:18px}.brand{font-size:30px;font-weight:950}.brand span{color:#a78bfa}.btn{display:inline-block;padding:11px 15px;border-radius:12px;background:#202734;color:#fff;text-decoration:none;font-weight:800}.hero,.card{background:#111620;border:1px solid #252d3b;border-radius:20px;padding:20px}.hero{background:linear-gradient(135deg,#121824,#171127)}h1{margin:0 0 7px;font-size:34px}.muted{color:#94a0b3}.status{display:flex;align-items:center;gap:10px;margin-top:18px;padding:14px;border-radius:14px;background:#0e131b;border:1px solid #283140}.dot{width:11px;height:11px;border-radius:50%;background:#64748b;box-shadow:0 0 0 5px #1b2230}.dot.live{background:#22c55e;box-shadow:0 0 0 5px #123521}.dot.warn{background:#f59e0b;box-shadow:0 0 0 5px #392a0e}.big{font-size:22px;font-weight:900}.grid{display:grid;grid-template-columns:repeat(3,1fr);gap:13px;margin-top:16px}.metric{background:#0e131b;border:1px solid #252d39;border-radius:15px;padding:16px}.value{font-size:26px;font-weight:900;margin-top:4px}.log{margin-top:16px;max-height:340px;overflow:auto;background:#0b0f15;border:1px solid #252d39;border-radius:15px;padding:12px}.line{padding:8px 4px;border-bottom:1px solid #1e2631;font-family:ui-monospace,SFMono-Regular,Menlo,monospace;font-size:12px}.ok{color:#86efac}.warn{color:#facc15}.bad{color:#fda4af}.actions{display:flex;gap:10px;flex-wrap:wrap;margin-top:16px}.primary{background:#7c3aed}.footer{margin-top:15px;color:#707b8e;font-size:12px}.quota{display:inline-flex;align-items:center;gap:8px;margin-top:12px;padding:8px 11px;border-radius:10px;background:#13261d;color:#86efac;border:1px solid #224233;font-size:12px;font-weight:800}@media(max-width:760px){main{padding:15px}.grid{grid-template-columns:1fr 1fr}h1{font-size:27px}}@media(max-width:480px){.grid{grid-template-columns:1fr}.top{align-items:flex-start;flex-direction:column}}
</style></head><body><main>
<div class="top"><div class="brand">🤖 Keddy <span>Live Engine</span></div><a class="btn" href="index.php">← Dashboard</a></div>
<section class="hero"><div class="muted">Browser-powered always-ready live session</div><h1>Live ke poore time Keddy active rakho.</h1><div class="muted">Ye page open rehne par Keddy active YouTube live detect karega, chat read karega, moderation verify karega aur replies process karega. Koi scheduled server Cron Job required nahi hai.</div><div class="quota">🛡️ Quota-safe engine • 15s live cycle • moderator verification cached 10 min</div><div class="status"><span id="dot" class="dot"></span><div><div class="big" id="status">Engine starting…</div><div class="muted" id="detail">Live detect hone ka wait.</div></div></div><div class="actions"><button class="btn primary" id="toggle">Pause Engine</button><button class="btn" id="pulse">Run Check Now</button></div></section>
<div class="grid"><div class="metric"><div class="muted">Live</div><div class="value" id="live">—</div></div><div class="metric"><div class="muted">Moderator</div><div class="value" id="mod">—</div></div><div class="metric"><div class="muted">Last Check</div><div class="value" id="last">—</div></div><div class="metric"><div class="muted">Checks</div><div class="value" id="checks">0</div></div><div class="metric"><div class="muted">Chat ID</div><div class="value" style="font-size:14px;word-break:break-all" id="chat">—</div></div><div class="metric"><div class="muted">Live ID</div><div class="value" style="font-size:14px;word-break:break-all" id="liveId">—</div></div></div>
<section class="card" style="margin-top:16px"><h2>Engine Log</h2><div class="log" id="log"></div><div class="footer">Keep this tab open during the live. Closing the tab stops the browser-side engine; the saved DB data remains safe.</div></section>
</main><script>
const token=<?=json_encode($engineToken)?>;let running=true,checks=0,timer=null;const $=id=>document.getElementById(id);function addLog(text,cls=''){const d=document.createElement('div');d.className='line '+cls;d.textContent=new Date().toLocaleTimeString()+'  '+text;$('log').prepend(d);while($('log').children.length>80)$('log').lastChild.remove();}function setState(kind,title,detail){$('status').textContent=title;$('detail').textContent=detail;$('dot').className='dot '+kind;}
async function pulse(){if(!running)return;try{const r=await fetch('live-engine.php?pulse=1&token='+encodeURIComponent(token)+'&_='+Date.now(),{cache:'no-store',credentials:'same-origin'});const j=await r.json();checks++;$('checks').textContent=checks;if(!j.ok)throw new Error(j.error||'Engine error');$('last').textContent=new Date().toLocaleTimeString();$('live').textContent=j.live?(j.title||'LIVE'):'Idle';$('mod').textContent=j.live?(j.moderator?'MOD ON':'NOT VERIFIED'):'—';$('chat').textContent=j.chat_id||'—';$('liveId').textContent=j.live_id||'—';if(j.live){if(j.moderator){setState('live','Keddy is LIVE + MOD ON',j.moderator_reason);addLog('Live detected • chat polling + moderation cycle executed','ok');}else{setState('warn','Keddy is LIVE',j.moderator_reason||'Moderator not verified');addLog('Live detected • moderation status not confirmed','warn');}}else{setState('','Waiting for live…','Engine is running and will auto-detect the next live.');addLog('No active live • engine remains ready');}}
catch(e){setState('warn','Engine backing off…',e.message);addLog('Check failed: '+e.message,'bad');}}
function schedule(){clearInterval(timer);timer=setInterval(pulse,15000);} $('toggle').onclick=()=>{running=!running;$('toggle').textContent=running?'Pause Engine':'Resume Engine';if(running){setState('','Resuming…','Running check now.');pulse();schedule();}else{setState('warn','Engine paused','Resume when the live begins.');clearInterval(timer);}};$('pulse').onclick=()=>{if(!running){running=true;$('toggle').textContent='Pause Engine';schedule();}pulse();};addLog('Browser live engine started • 15s quota-safe cycle','ok');pulse();schedule();window.addEventListener('beforeunload',()=>clearInterval(timer));
</script></body></html>

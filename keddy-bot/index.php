<?php
declare(strict_types=1);
session_start();
require __DIR__.'/bot.php';

if (empty($_SESSION['keddy_heartbeat_token'])) {
    $_SESSION['keddy_heartbeat_token'] = bin2hex(random_bytes(24));
}
$heartbeatToken = $_SESSION['keddy_heartbeat_token'];
$error='';
$notice='';
if(isset($_GET['connected'])) $notice='YouTube connected successfully. Start your live, then start Keddy.';
if($_SERVER['REQUEST_METHOD']==='POST'){
    try{
        $a=$_POST['action']??'';
        if($a==='start') startS();
        elseif($a==='stop') stopS();
        elseif($a==='next') nextS();
        elseif($a==='reconnect') { sv('oauth',''); sv('chat_id',''); sv('live_id',''); sv('chat_token',''); header('Location: oauth.php'); exit; }
        elseif($a==='save'){
            sv('interval',(string)max(2,(int)$_POST['interval']));
            sv('duration',(string)max(2,(int)$_POST['duration']));
            $raw=preg_split('/\r\n|\r|\n/',trim((string)$_POST['tasks']));
            $raw=array_values(array_filter(array_map('trim',$raw)));
            if($raw) sj('tasks',$raw);
            $notice='Keddy settings saved.';
        }
    }catch(Throwable$e){$error=$e->getMessage();}
}
$connected=!!gv('oauth');
$ss=session();
$tasks=tasks();
function h($x):string{return htmlspecialchars((string)$x,ENT_QUOTES,'UTF-8');}
?>
<!doctype html>
<html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Keddy Bot</title>
<style>*{box-sizing:border-box}body{margin:0;background:#0b0d12;color:#f4f6fb;font:15px system-ui}main{max-width:1100px;margin:auto;padding:28px}.top{display:flex;justify-content:space-between;align-items:center;margin-bottom:24px}.brand{font-size:28px;font-weight:800}.brand span{color:#8b5cf6}.pill,.stat,.code{background:#151923;border-radius:12px;padding:10px 14px}.grid{display:grid;grid-template-columns:1.4fr .9fr;gap:18px}.card{background:#121620;border:1px solid #252b38;border-radius:18px;padding:22px}.hero{min-height:230px}.h1{font-size:34px;margin:8px 0}.muted{color:#9aa3b5}.stats{display:flex;gap:12px;margin:18px 0}.stat{flex:1}.num{font-size:25px;font-weight:800}.actions{display:flex;gap:10px;flex-wrap:wrap}.btn{border:0;border-radius:11px;padding:12px 18px;background:#7c3aed;color:white;font-weight:700;cursor:pointer;text-decoration:none}.secondary{background:#252b38}.danger{background:#b4233d}.notice,.error{padding:12px;border-radius:10px;margin-bottom:16px}.notice{background:#123b2a}.error{background:#4a1721}label{display:block;margin:16px 0 7px;font-weight:700}input,textarea{width:100%;background:#0b0e14;border:1px solid #2b3240;color:#fff;border-radius:10px;padding:11px;font:inherit}textarea{min-height:260px}.footer{margin-top:18px;color:#727b8c;font-size:13px}@media(max-width:800px){main{padding:16px}.grid{grid-template-columns:1fr}.h1{font-size:28px}.stats{flex-direction:column}}</style></head>
<body><main>
<div class="top"><div class="brand">🤖 Keddy <span>Bot</span></div><div class="pill">● <?= $connected?'YouTube Connected':'YouTube Not Connected' ?></div></div>
<?php if($notice):?><div class="notice"><?=h($notice)?></div><?php endif;?>
<?php if($error):?><div class="error">⚠️ <?=h($error)?></div><?php endif;?>
<div class="grid"><section class="card hero"><div class="muted">SmartToolz • YouTube Live Instructor</div><h1 class="h1">Your live, with a real-time seated instructor.</h1><p class="muted">Keddy posts the next task every configured interval and answers chat commands.</p>
<div class="stats"><div class="stat"><div class="muted">Session</div><div class="num"><?=$ss['active']?'LIVE':'OFF'?></div></div><div class="stat"><div class="muted">Interval</div><div class="num"><?=h($ss['interval'])?>m</div></div><div class="stat"><div class="muted">Duration</div><div class="num"><?=h($ss['duration'])?>m</div></div></div>
<div class="actions"><?php if(!$connected):?><a class="btn" href="oauth.php">Connect YouTube</a><?php else:?><form method="post"><input type="hidden" name="action" value="start"><button class="btn">▶ Start Keddy</button></form><form method="post"><input type="hidden" name="action" value="next"><button class="btn secondary">⏭ Next Task</button></form><form method="post"><input type="hidden" name="action" value="stop"><button class="btn danger">■ Stop</button></form><form method="post"><input type="hidden" name="action" value="reconnect"><button class="btn secondary">↻ Reconnect YouTube</button></form><?php endif;?></div></section>
<aside class="card"><h2>Chat commands</h2><div class="code">!task</div><div class="muted">Current seated task</div><div class="code">!next</div><div class="muted">Move to next task</div><div class="code">!status</div><div class="muted">Show session progress</div><div class="code">!break</div><div class="muted">Announce seated break</div><div class="code">!help</div><div class="muted">Show commands</div></aside></div>
<section class="card" style="margin-top:18px"><h2>Instructor settings</h2><form method="post"><input type="hidden" name="action" value="save"><label>Task interval (minutes)</label><input type="number" min="2" name="interval" value="<?=h($ss['interval'])?>"><label>Session duration (minutes)</label><input type="number" min="2" name="duration" value="<?=h($ss['duration'])?>"><label>Seated task list — one task per line</label><textarea name="tasks"><?=h(implode("\n",$tasks))?></textarea><div class="actions" style="margin-top:12px"><button class="btn">Save Settings</button></div></form><div class="footer">Built-in tasks are seated. Keep OAuth/API secrets outside Git.</div></section>
</main>
<script>
const keddyToken = <?=json_encode($heartbeatToken)?>;
const keddyActive = <?= $ss['active'] ? 'true' : 'false' ?>;
if(keddyActive){
  const tick=()=>fetch('cron.php?heartbeat=1&token='+encodeURIComponent(keddyToken),{cache:'no-store'}).catch(()=>{});
  tick();
  setInterval(tick,120000);
}
</script>
</body></html>

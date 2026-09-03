<?php
declare(strict_types=1);
session_start();
require __DIR__.'/bot.php';
require_once __DIR__.'/comments-engine.php';

$notice='';$error='';
if(isset($_GET['run'])&&$_GET['run']==='1'){
    $r=runViewerVideoCommentTick();
    if(!empty($r['error']))$error=$r['error'];
    elseif(!empty($r['posted']))$notice='✅ Keddy commented on a registered Keddy user\'s video. The action is in history below.';
    elseif(($r['reason']??'')==='no_registered_keddy_users')$notice='ℹ️ No registered Keddy users yet. A YouTube channel becomes a Keddy user after connecting it to Keddy.';
    elseif(($r['reason']??'')==='cooldown')$notice='⏳ Hourly cooldown is still active.';
    else $notice='ℹ️ Hourly check completed. No eligible new video found among registered Keddy users.';
}

$stats=commentStats();
$rows=commentHistory(100);
$users=registeredKeddyUsers(false);
$next=max(0,3600-(time()-(int)gv('viewer_comments_last_run','0')));
function vh($x):string{return htmlspecialchars((string)$x,ENT_QUOTES,'UTF-8');}
?>
<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Keddy • User Video Comments</title>
<style>*{box-sizing:border-box}body{margin:0;background:#090b10;color:#f4f7fb;font:14px/1.5 system-ui,-apple-system,Segoe UI,sans-serif}main{max-width:1280px;margin:auto;padding:24px}.top{display:flex;justify-content:space-between;align-items:center;gap:15px;margin-bottom:20px}.brand{font-size:26px;font-weight:850}.brand span{color:#a78bfa}.muted{color:#94a0b4}.btn{display:inline-block;border:0;border-radius:11px;padding:11px 15px;background:#7c3aed;color:#fff;text-decoration:none;font-weight:750}.btn.secondary{background:#1b2230}.notice,.error{padding:12px 14px;border-radius:12px;margin-bottom:16px}.notice{background:#103a2b}.error{background:#481c24}.stats{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:16px}.card{background:#111620;border:1px solid #252c39;border-radius:18px;padding:18px}.num{font-size:26px;font-weight:850;margin-top:3px}.hero{display:flex;justify-content:space-between;align-items:center;gap:18px;margin-bottom:16px}.hero h1{margin:0 0 5px;font-size:30px}.tableWrap{overflow:auto}.table{width:100%;border-collapse:collapse;min-width:920px}.table th,.table td{padding:13px 10px;border-bottom:1px solid #242a36;text-align:left;vertical-align:top}.table th{color:#aeb7c7;font-size:12px;text-transform:uppercase;letter-spacing:.05em}.user{font-weight:750}.comment{max-width:430px}.video a{color:#c4b5fd;text-decoration:none}.status{display:inline-flex;padding:4px 8px;border-radius:99px;font-size:12px;font-weight:700;background:#123a2b;color:#86efac}.status.failed{background:#491d26;color:#fda4af}.small{font-size:12px;color:#7f8a9e}.empty{text-align:center;padding:40px 10px;color:#8994a7}.usergrid{display:grid;grid-template-columns:repeat(3,1fr);gap:10px}.usercard{background:#0f141d;border:1px solid #252c39;border-radius:13px;padding:13px}.dot{display:inline-block;width:8px;height:8px;border-radius:50%;background:#4ade80;margin-right:7px}@media(max-width:900px){main{padding:15px}.stats{grid-template-columns:repeat(2,1fr)}.hero{align-items:flex-start;flex-direction:column}.usergrid{grid-template-columns:1fr}.hero h1{font-size:25px}}</style></head>
<body><main>
<div class="top"><div class="brand">🤖 Keddy <span>User Video Comments</span></div><a class="btn secondary" href="index.php">← Dashboard</a></div>
<?php if($notice):?><div class="notice"><?=vh($notice)?></div><?php endif;?>
<?php if($error):?><div class="error">⚠️ <?=vh($error)?></div><?php endif;?>
<div class="card hero"><div><h1>Registered Keddy Users</h1><div class="muted">Keddy sirf neeche registered/connected users ki public videos ko target karega — random YouTube commenters ko nahi.</div></div><a class="btn" href="?run=1">▶ Run hourly check now</a></div>
<div class="stats"><div class="card"><div class="muted">Registered users</div><div class="num"><?=count($users)?></div></div><div class="card"><div class="muted">Comments today</div><div class="num"><?=$stats['today']?></div></div><div class="card"><div class="muted">All posted</div><div class="num"><?=$stats['total']?></div></div><div class="card"><div class="muted">Next check</div><div class="num" id="countdown">--:--</div></div></div>
<div class="card" style="margin-bottom:16px"><h2>Who Keddy can target</h2><?php if(!$users):?><div class="empty">Abhi registry empty hai. Jab koi streamer <b>Connect YouTube</b> karke Keddy use karega, uska channel automatically yahan add ho jayega.</div><?php else:?><div class="usergrid"><?php foreach($users as $u):?><div class="usercard"><div><span class="dot"></span><strong><?=vh($u['channel_title']?:'YouTube channel')?></strong></div><div class="small" style="margin-top:5px"><?=vh($u['channel_id'])?></div><div class="small" style="margin-top:5px">Registered <?=date('d M Y, h:i A',(int)$u['registered_at'])?></div></div><?php endforeach;?></div><?php endif;?></div>
<div class="card"><h2>Comment History</h2><div class="muted" style="margin-bottom:12px">Har entry proof hai: kis registered Keddy user ki video par, kya comment hua, aur kab.</div><div class="tableWrap"><table class="table"><thead><tr><th>Registered User</th><th>Video</th><th>What Keddy commented</th><th>When</th><th>Status</th></tr></thead><tbody>
<?php if(!$rows):?><tr><td colspan="5" class="empty">Abhi koi targeted Keddy-user video comment nahi hua.</td></tr>
<?php else: foreach($rows as $r):?><tr><td><div class="user"><?=vh($r['viewer_name'])?></div><div class="small"><?=vh($r['viewer_channel_id'])?></div></td><td class="video"><a href="<?=vh($r['video_url'])?>" target="_blank" rel="noopener"><?=vh($r['video_title'])?></a></td><td class="comment"><?=vh($r['comment_text'])?></td><td><?=date('d M Y, h:i A',(int)$r['created_at'])?></td><td><span class="status <?=$r['status']==='failed'?'failed':''?>"><?=vh(strtoupper($r['status']))?></span><?php if($r['error_text']):?><div class="small" style="margin-top:5px"><?=vh($r['error_text'])?></div><?php endif;?></td></tr><?php endforeach; endif;?>
</tbody></table></div></div>
<div class="small" style="margin-top:14px">Protection: maximum 1 successful comment per hourly cycle, registered-user targeting only, and same user + same video cannot receive another posted comment.</div>
</main><script>let left=<?=json_encode($next)?>;const e=document.getElementById('countdown');function tick(){left=Math.max(0,left);e.textContent=String(Math.floor(left/60)).padStart(2,'0')+':'+String(left%60).padStart(2,'0');left--}tick();setInterval(tick,1000);</script></body></html>

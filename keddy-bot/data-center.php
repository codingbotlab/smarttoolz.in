<?php
declare(strict_types=1);
session_start();
require __DIR__.'/bot.php';
require_once __DIR__.'/comments-engine.php';

keddyDataInit();
keddyUsersInit();

function dcRows(string $sql,array $params=[]):array{
    $q=db()->prepare($sql);$q->execute($params);return $q->fetchAll(PDO::FETCH_ASSOC)?:[];
}
function dcCount(string $sql,array $params=[]):int{
    $q=db()->prepare($sql);$q->execute($params);return (int)$q->fetchColumn();
}
function dcVal(string $sql,array $params=[]){
    $q=db()->prepare($sql);$q->execute($params);return $q->fetchColumn();
}
function dcJson(string $raw):string{
    $v=json_decode($raw,true);
    if(!is_array($v))return '—';
    return (string)count($v).' fields';
}
function dcH($v):string{return htmlspecialchars((string)$v,ENT_QUOTES,'UTF-8');}
function dcTime($v):string{return $v?date('d M Y, h:i A',(int)$v):'—';}
function dcBytes(int $bytes):string{
    $u=['B','KB','MB','GB'];$i=0;$n=(float)$bytes;while($n>=1024&&$i<3){$n/=1024;$i++;}return number_format($n,2).' '.$u[$i];
}

$dbFile=(string)(cfg()['database']??(__DIR__.'/data/keddy.sqlite'));
$dbSize=is_file($dbFile)?filesize($dbFile):0;
$stats=[
    'managed_channels'=>dcCount('SELECT COUNT(*) FROM managed_channels'),
    'moderated_channels'=>dcCount('SELECT COUNT(*) FROM managed_channels WHERE moderation_enabled=1'),
    'registered_users'=>dcCount('SELECT COUNT(*) FROM keddy_registered_users WHERE enabled=1'),
    'registered_total'=>dcCount('SELECT COUNT(*) FROM keddy_registered_users'),
    'youtube_users'=>dcCount('SELECT COUNT(*) FROM youtube_users'),
    'videos'=>dcCount('SELECT COUNT(*) FROM youtube_videos'),
    'chat_messages'=>dcCount('SELECT COUNT(*) FROM youtube_chat_messages'),
    'moderation_events'=>dcCount('SELECT COUNT(*) FROM moderation_events'),
    'live_broadcasts'=>dcCount('SELECT COUNT(*) FROM live_broadcasts'),
    'channel_snapshots'=>dcCount('SELECT COUNT(*) FROM channel_snapshots'),
    'channel_events'=>dcCount('SELECT COUNT(*) FROM managed_channel_events'),
    'comments_posted'=>dcCount("SELECT COUNT(*) FROM viewer_comment_history WHERE status='posted'"),
    'comments_failed'=>dcCount("SELECT COUNT(*) FROM viewer_comment_history WHERE status='failed'"),
];

$channels=dcRows('SELECT mc.*,ku.enabled AS user_enabled FROM managed_channels mc LEFT JOIN keddy_registered_users ku ON ku.channel_id=mc.channel_id ORDER BY mc.last_seen_at DESC LIMIT 100');
$recentVideos=dcRows('SELECT video_id,channel_id,title,description,published_at,duration,privacy_status,license,live_broadcast_content,last_seen_at FROM youtube_videos ORDER BY last_seen_at DESC LIMIT 100');
$recentUsers=dcRows('SELECT channel_id,display_name,custom_url,description,published_at,country,subscriber_count,view_count,video_count,last_seen_at FROM youtube_users ORDER BY last_seen_at DESC LIMIT 100');
$recentChat=dcRows('SELECT message_id,channel_id,author_channel_id,author_name,message_text,published_at,captured_at FROM youtube_chat_messages ORDER BY captured_at DESC LIMIT 100');
$recentMod=dcRows('SELECT channel_id,live_broadcast_id,message_id,target_channel_id,target_name,action,severity,reason,hit,offense,created_at FROM moderation_events ORDER BY created_at DESC LIMIT 100');
$recentComments=commentHistory(100);
?>
<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Keddy • Data Center</title>
<style>
*{box-sizing:border-box}body{margin:0;background:#080b10;color:#f4f7fb;font:14px/1.5 system-ui,-apple-system,Segoe UI,sans-serif}main{max-width:1450px;margin:auto;padding:24px}.top{display:flex;justify-content:space-between;align-items:center;gap:16px;margin-bottom:20px}.brand{font-size:27px;font-weight:900}.brand span{color:#a78bfa}.muted{color:#95a1b4}.btn{display:inline-block;padding:11px 15px;border-radius:11px;background:#7c3aed;color:#fff;text-decoration:none;font-weight:800}.btn.secondary{background:#202734}.hero{background:linear-gradient(135deg,#121824,#171127);border:1px solid #2a3140;border-radius:20px;padding:22px;margin-bottom:16px}.hero h1{margin:0 0 6px;font-size:32px}.stats{display:grid;grid-template-columns:repeat(6,1fr);gap:12px;margin-bottom:16px}.card{background:#111620;border:1px solid #252d3b;border-radius:18px;padding:18px}.num{font-size:28px;font-weight:900}.label{color:#94a0b4;font-size:12px;text-transform:uppercase;letter-spacing:.05em}.sub{font-size:12px;color:#778296;margin-top:4px}.section{margin-top:16px}.section h2{margin:0 0 12px}.tableWrap{overflow:auto;max-height:520px}.table{width:100%;border-collapse:collapse;min-width:1050px}.table th,.table td{padding:11px 9px;border-bottom:1px solid #232a36;text-align:left;vertical-align:top}.table th{font-size:11px;color:#aeb7c6;text-transform:uppercase;letter-spacing:.05em;position:sticky;top:0;background:#111620}.pill{display:inline-flex;padding:4px 8px;border-radius:99px;background:#162b24;color:#86efac;font-weight:800;font-size:11px}.pill.warn{background:#392d13;color:#facc15}.pill.red{background:#402029;color:#fda4af}.mono{font-family:ui-monospace,SFMono-Regular,Menlo,monospace;font-size:12px}.clip{max-width:330px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}.grid2{display:grid;grid-template-columns:1fr 1fr;gap:16px}.empty{text-align:center;padding:35px;color:#7e899c}.storage{display:flex;justify-content:space-between;gap:12px;align-items:center}.note{font-size:12px;color:#748095;margin-top:8px}.accent{color:#c4b5fd}@media(max-width:1150px){.stats{grid-template-columns:repeat(3,1fr)}}@media(max-width:800px){main{padding:15px}.stats{grid-template-columns:repeat(2,1fr)}.grid2{grid-template-columns:1fr}.hero h1{font-size:26px}}
</style></head><body><main>
<div class="top"><div class="brand">🤖 Keddy <span>Data Center</span></div><a class="btn secondary" href="index.php">← Dashboard</a></div>
<section class="hero"><h1>Everything Keddy has stored</h1><div class="muted">Channel, users, videos, live chats, moderation, comments aur historical snapshots — sab ka live database view.</div><div class="storage" style="margin-top:15px"><div><b>SQLite database:</b> <span class="mono"><?=dcH(basename($dbFile))?></span></div><div class="accent"><b><?=dcH(dcBytes((int)$dbSize))?></b></div></div><div class="note">OAuth access/refresh tokens ko analytics tables mein duplicate nahi kiya jata.</div></section>
<div class="stats">
<?php foreach([['managed_channels','Managed channels'],['moderated_channels','Moderated channels'],['registered_users','Keddy users'],['youtube_users','YouTube users'],['videos','Videos'],['chat_messages','Chat messages'],['moderation_events','Mod events'],['live_broadcasts','Live broadcasts'],['channel_snapshots','Snapshots'],['channel_events','Channel events'],['comments_posted','Comments posted'],['comments_failed','Comments failed']] as $s):?><div class="card"><div class="label"><?=dcH($s[1])?></div><div class="num"><?=dcH($stats[$s[0]])?></div></div><?php endforeach;?></div>

<section class="card section"><h2>📺 Managed / Moderated Channels — <?=dcH($stats['managed_channels'])?></h2><div class="tableWrap"><table class="table"><thead><tr><th>Channel</th><th>Handle</th><th>Country</th><th>Subscribers</th><th>Views</th><th>Videos</th><th>Keddy User</th><th>Moderation</th><th>Last Seen</th></tr></thead><tbody><?php if(!$channels):?><tr><td colspan="9" class="empty">Abhi channel data nahi hai.</td></tr><?php else:foreach($channels as $r):?><tr><td><b><?=dcH($r['title'])?></b><div class="mono"><?=dcH($r['channel_id'])?></div></td><td><?=dcH($r['handle']?:$r['custom_url'])?></td><td><?=dcH($r['country']?:'—')?></td><td><?=dcH($r['subscriber_count']??'—')?></td><td><?=dcH($r['view_count']??'—')?></td><td><?=dcH($r['video_count']??'—')?></td><td><?=!empty($r['registered_user'])?'<span class="pill">REGISTERED</span>':'<span class="pill warn">DISCOVERED</span>'?></td><td><?=!empty($r['moderation_enabled'])?'<span class="pill">MOD ON</span>':'<span class="pill warn">OFF</span>'?></td><td><?=dcH(dcTime($r['last_seen_at']))?></td></tr><?php endforeach;endif;?></tbody></table></div></section>

<div class="grid2 section">
<section class="card"><h2>👤 YouTube Users — <?=dcH($stats['youtube_users'])?></h2><div class="tableWrap"><table class="table"><thead><tr><th>User</th><th>Country</th><th>Subscribers</th><th>Views</th><th>Videos</th><th>Last Seen</th></tr></thead><tbody><?php if(!$recentUsers):?><tr><td colspan="6" class="empty">No users captured yet.</td></tr><?php else:foreach($recentUsers as $r):?><tr><td><b><?=dcH($r['display_name'])?></b><div class="mono"><?=dcH($r['channel_id'])?></div><div class="small clip"><?=dcH($r['description'])?></div></td><td><?=dcH($r['country']?:'—')?></td><td><?=dcH($r['subscriber_count']??'—')?></td><td><?=dcH($r['view_count']??'—')?></td><td><?=dcH($r['video_count']??'—')?></td><td><?=dcH(dcTime($r['last_seen_at']))?></td></tr><?php endforeach;endif;?></tbody></table></div></section>
<section class="card"><h2>🎬 Videos — <?=dcH($stats['videos'])?></h2><div class="tableWrap"><table class="table"><thead><tr><th>Video</th><th>Channel</th><th>Published</th><th>Duration</th><th>Privacy</th><th>Type</th><th>Last Seen</th></tr></thead><tbody><?php if(!$recentVideos):?><tr><td colspan="7" class="empty">No videos captured yet.</td></tr><?php else:foreach($recentVideos as $r):?><tr><td><b class="clip" style="display:block"><?=dcH($r['title'])?></b><div class="small clip"><?=dcH($r['description'])?></div><div class="mono"><?=dcH($r['video_id'])?></div></td><td class="mono"><?=dcH($r['channel_id'])?></td><td><?=dcH($r['published_at']?:'—')?></td><td><?=dcH($r['duration']?:'—')?></td><td><?=dcH($r['privacy_status']?:'—')?></td><td><?=dcH($r['live_broadcast_content']?:'none')?></td><td><?=dcH(dcTime($r['last_seen_at']))?></td></tr><?php endforeach;endif;?></tbody></table></div></section></div>

<div class="grid2 section">
<section class="card"><h2>💬 Chat Messages — <?=dcH($stats['chat_messages'])?></h2><div class="tableWrap"><table class="table"><thead><tr><th>Author</th><th>Message</th><th>Published</th><th>Captured</th></tr></thead><tbody><?php if(!$recentChat):?><tr><td colspan="4" class="empty">No live chat data yet.</td></tr><?php else:foreach($recentChat as $r):?><tr><td><b><?=dcH($r['author_name'])?></b><div class="mono"><?=dcH($r['author_channel_id'])?></div></td><td><?=dcH($r['message_text'])?></td><td><?=dcH($r['published_at']?:'—')?></td><td><?=dcH(dcTime($r['captured_at']))?></td></tr><?php endforeach;endif;?></tbody></table></div></section>
<section class="card"><h2>🛡️ Moderation Events — <?=dcH($stats['moderation_events'])?></h2><div class="tableWrap"><table class="table"><thead><tr><th>Target</th><th>Action</th><th>Severity</th><th>Reason</th><th>Offense</th><th>When</th></tr></thead><tbody><?php if(!$recentMod):?><tr><td colspan="6" class="empty">No moderation events yet.</td></tr><?php else:foreach($recentMod as $r):?><tr><td><b><?=dcH($r['target_name'])?></b><div class="mono"><?=dcH($r['target_channel_id'])?></div></td><td><?=in_array($r['action'],['ban','timeout'],true)?'<span class="pill red">'.dcH(strtoupper($r['action'])).'</span>':'<span class="pill warn">'.dcH(strtoupper($r['action'])).'</span>'?></td><td><?=dcH($r['severity'])?></td><td><?=dcH($r['reason'])?> <span class="small"><?=dcH($r['hit'])?></span></td><td><?=dcH($r['offense']??'—')?></td><td><?=dcH(dcTime($r['created_at']))?></td></tr><?php endforeach;endif;?></tbody></table></div></section></div>

<section class="card section"><h2>💬 Keddy Comment History — <?=dcH($stats['comments_posted'])?> posted / <?=dcH($stats['comments_failed'])?> failed</h2><div class="tableWrap"><table class="table"><thead><tr><th>Registered User</th><th>Video</th><th>Comment</th><th>When</th><th>Status</th></tr></thead><tbody><?php if(!$recentComments):?><tr><td colspan="5" class="empty">No comment history yet.</td></tr><?php else:foreach($recentComments as $r):?><tr><td><b><?=dcH($r['viewer_name'])?></b><div class="mono"><?=dcH($r['viewer_channel_id'])?></div></td><td><a class="accent" target="_blank" rel="noopener" href="<?=dcH($r['video_url'])?>"><?=dcH($r['video_title'])?></a><div class="mono"><?=dcH($r['video_id'])?></div></td><td class="clip"><?=dcH($r['comment_text'])?></td><td><?=dcH(dcTime($r['created_at']))?></td><td><?=($r['status']==='posted'?'<span class="pill">POSTED</span>':'<span class="pill red">FAILED</span>')?></td></tr><?php endforeach;endif;?></tbody></table></div></section>

<section class="card section"><h2>💾 What is retained for future expansion?</h2><div class="cmdgrid" style="display:grid;grid-template-columns:repeat(3,1fr);gap:10px"><div class="card"><b>Channels</b><div class="muted">Profile, counts, topics, branding, status, raw API snapshots.</div></div><div class="card"><b>Users</b><div class="muted">Channel identity plus activity-visible YouTube metadata.</div></div><div class="card"><b>Videos</b><div class="muted">Metadata, duration, privacy/status, tags/statistics payloads.</div></div><div class="card"><b>Live</b><div class="muted">Broadcast + live chat identity and history.</div></div><div class="card"><b>Moderation</b><div class="muted">Action, severity, reason, target, offense trail.</div></div><div class="card"><b>Comments</b><div class="muted">User, video, exact comment, timestamp and status.</div></div></div></section>
</main></body></html>

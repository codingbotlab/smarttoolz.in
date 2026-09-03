<?php
declare(strict_types=1);
session_start();
require __DIR__.'/bot.php';
require_once __DIR__.'/comments-engine.php';

function dcEnsureTables():void{
    $tables=[
        "CREATE TABLE IF NOT EXISTS live_broadcasts(live_id TEXT PRIMARY KEY,channel_id TEXT NOT NULL DEFAULT '',live_chat_id TEXT NOT NULL DEFAULT '',title TEXT NOT NULL DEFAULT '',published_at TEXT NOT NULL DEFAULT '',scheduled_start TEXT NOT NULL DEFAULT '',actual_start TEXT NOT NULL DEFAULT '',privacy_status TEXT NOT NULL DEFAULT '',raw_json TEXT NOT NULL DEFAULT '{}',first_seen_at INTEGER NOT NULL DEFAULT 0,last_seen_at INTEGER NOT NULL DEFAULT 0)",
        "CREATE TABLE IF NOT EXISTS youtube_users(channel_id TEXT PRIMARY KEY,display_name TEXT NOT NULL DEFAULT '',custom_url TEXT NOT NULL DEFAULT '',description TEXT NOT NULL DEFAULT '',published_at TEXT NOT NULL DEFAULT '',country TEXT NOT NULL DEFAULT '',profile_image_url TEXT NOT NULL DEFAULT '',uploads_playlist_id TEXT NOT NULL DEFAULT '',subscriber_count INTEGER,view_count INTEGER,video_count INTEGER,hidden_subscriber_count INTEGER,made_for_kids INTEGER,branding_json TEXT NOT NULL DEFAULT '{}',content_details_json TEXT NOT NULL DEFAULT '{}',statistics_json TEXT NOT NULL DEFAULT '{}',status_json TEXT NOT NULL DEFAULT '{}',raw_json TEXT NOT NULL DEFAULT '{}',first_seen_at INTEGER NOT NULL DEFAULT 0,last_seen_at INTEGER NOT NULL DEFAULT 0)",
        "CREATE TABLE IF NOT EXISTS managed_channel_events(id INTEGER PRIMARY KEY AUTOINCREMENT,channel_id TEXT NOT NULL,live_broadcast_id TEXT NOT NULL DEFAULT '',live_chat_id TEXT NOT NULL DEFAULT '',event_type TEXT NOT NULL,details_json TEXT NOT NULL DEFAULT '{}',created_at INTEGER NOT NULL DEFAULT 0)"
    ];
    foreach($tables as $sql){try{db()->exec($sql);}catch(Throwable $e){}}
}
function dcRows(string $sql):array{try{$q=db()->query($sql);return$q->fetchAll(PDO::FETCH_ASSOC)?:[];}catch(Throwable $e){return[];}}
function dcCount(string $sql):int{try{return(int)db()->query($sql)->fetchColumn();}catch(Throwable $e){return 0;}}
function dcH($v):string{return htmlspecialchars((string)$v,ENT_QUOTES,'UTF-8');}
function dcTime($v):string{return$v?date('d M Y, h:i A',(int)$v):'—';}
function dcBytes(int $b):string{$u=['B','KB','MB','GB'];$i=0;$n=(float)$b;while($n>=1024&&$i<3){$n/=1024;$i++;}return number_format($n,2).' '.$u[$i];}
function dcNum($v):string{return number_format((int)$v);}

try{
    keddyDataInit();keddyUsersInit();dcEnsureTables();
    $dbFile=(string)(cfg()['database']??(__DIR__.'/data/keddy.sqlite'));
    $dbSize=is_file($dbFile)?(int)filesize($dbFile):0;
    $stats=[
        'managed_channels'=>dcCount('SELECT COUNT(*) FROM managed_channels'),
        'moderated_channels'=>dcCount('SELECT COUNT(*) FROM managed_channels WHERE moderation_enabled=1'),
        'registered_users'=>dcCount('SELECT COUNT(*) FROM keddy_registered_users WHERE enabled=1'),
        'youtube_users'=>dcCount('SELECT COUNT(*) FROM youtube_users'),
        'videos'=>dcCount('SELECT COUNT(*) FROM youtube_videos'),
        'chat_messages'=>dcCount('SELECT COUNT(*) FROM youtube_chat_messages'),
        'moderation_events'=>dcCount('SELECT COUNT(*) FROM moderation_events'),
        'live_broadcasts'=>dcCount('SELECT COUNT(*) FROM live_broadcasts'),
        'snapshots'=>dcCount('SELECT COUNT(*) FROM channel_snapshots'),
        'channel_events'=>dcCount('SELECT COUNT(*) FROM managed_channel_events'),
        'comments_posted'=>dcCount("SELECT COUNT(*) FROM viewer_comment_history WHERE status='posted'"),
        'comments_failed'=>dcCount("SELECT COUNT(*) FROM viewer_comment_history WHERE status='failed'")
    ];
    $channels=dcRows('SELECT * FROM managed_channels ORDER BY last_seen_at DESC LIMIT 200');
    $users=dcRows('SELECT channel_id,display_name,custom_url,description,published_at,country,subscriber_count,view_count,video_count,last_seen_at FROM youtube_users ORDER BY last_seen_at DESC LIMIT 200');
    $videos=dcRows('SELECT video_id,channel_id,title,description,published_at,duration,privacy_status,license,live_broadcast_content,last_seen_at FROM youtube_videos ORDER BY last_seen_at DESC LIMIT 200');
    $chat=dcRows('SELECT message_id,channel_id,author_channel_id,author_name,message_text,published_at,captured_at FROM youtube_chat_messages ORDER BY captured_at DESC LIMIT 200');
    $mods=dcRows('SELECT channel_id,live_broadcast_id,message_id,target_channel_id,target_name,action,severity,reason,hit,offense,created_at FROM moderation_events ORDER BY created_at DESC LIMIT 200');
    $lives=dcRows('SELECT live_id,channel_id,title,live_chat_id,published_at,actual_start,last_seen_at FROM live_broadcasts ORDER BY last_seen_at DESC LIMIT 100');
    $comments=commentHistory(200);
}catch(Throwable $e){
    http_response_code(500);
    echo '<!doctype html><meta charset="utf-8"><style>body{font:16px system-ui;background:#07090d;color:#fff;padding:32px}pre{background:#111722;padding:18px;border-radius:14px;white-space:pre-wrap;border:1px solid #232b3a}a{color:#c4b5fd}</style><h1>Keddy Data Center</h1><p>Database dashboard could not initialize.</p><pre>'.dcH($e->getMessage()).'</pre><a href="index.php">← Dashboard</a>';
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="theme-color" content="#07090d">
<title>Keddy • Data Center</title>
<style>
:root{--bg:#07090d;--panel:#0d1119;--panel2:#111722;--line:#202838;--text:#f6f7fb;--muted:#8e99ab;--soft:#b7c0cf;--purple:#a78bfa;--green:#34d399;--yellow:#fbbf24;--red:#fb7185;--cyan:#67e8f9;--shadow:0 18px 50px rgba(0,0,0,.22)}
*{box-sizing:border-box}html{scroll-behavior:smooth}body{margin:0;background:radial-gradient(circle at 85% -10%,rgba(167,139,250,.12),transparent 30%),radial-gradient(circle at 8% 8%,rgba(103,232,249,.07),transparent 28%),var(--bg);color:var(--text);font:14px/1.5 Inter,ui-sans-serif,system-ui,-apple-system,Segoe UI,sans-serif}a{color:inherit}button,.btn{font:inherit}
.shell{max-width:1540px;margin:auto;padding:24px}.topbar{display:flex;align-items:center;justify-content:space-between;gap:18px;margin-bottom:18px}.brandRow{display:flex;align-items:center;gap:13px}.logo{width:46px;height:46px;display:grid;place-items:center;border-radius:14px;background:linear-gradient(135deg,#7c3aed,#22d3ee);box-shadow:0 10px 30px rgba(124,58,237,.25);font-size:23px}.brand{font-size:25px;font-weight:900;letter-spacing:-.03em}.brand small{display:block;color:var(--muted);font-size:12px;font-weight:700;letter-spacing:.02em;margin-top:1px}.actions{display:flex;gap:9px;flex-wrap:wrap}.btn{display:inline-flex;align-items:center;gap:7px;text-decoration:none;padding:10px 14px;border-radius:11px;border:1px solid var(--line);background:#121824;color:#fff;font-weight:800;cursor:pointer}.btn:hover{border-color:#3b465c;background:#151c28}.btn.primary{background:#f4f2ff;color:#0c0e14;border-color:#f4f2ff}.btn.primary:hover{background:#fff}.hero{position:relative;overflow:hidden;border:1px solid #293246;border-radius:22px;padding:25px;background:linear-gradient(135deg,#111725 0%,#161126 52%,#0d1518 100%);box-shadow:var(--shadow);margin-bottom:16px}.hero:after{content:"";position:absolute;width:240px;height:240px;border-radius:50%;right:-60px;top:-110px;background:radial-gradient(circle,rgba(167,139,250,.18),transparent 70%);pointer-events:none}.heroHead{display:flex;justify-content:space-between;align-items:flex-start;gap:20px}.eyebrow{color:#c4b5fd;text-transform:uppercase;font-size:11px;font-weight:900;letter-spacing:.14em}.hero h1{font-size:33px;line-height:1.1;margin:7px 0 7px;letter-spacing:-.04em}.hero p{margin:0;color:var(--soft);max-width:820px}.liveBadge{display:inline-flex;align-items:center;gap:8px;padding:9px 12px;border-radius:999px;background:#0d211b;border:1px solid #1d523e;color:#9ff1ca;font-weight:900;white-space:nowrap}.dot{width:8px;height:8px;border-radius:50%;background:var(--green);box-shadow:0 0 0 5px rgba(52,211,153,.12)}.metaRow{display:flex;align-items:center;justify-content:space-between;gap:15px;margin-top:20px;padding-top:15px;border-top:1px solid rgba(255,255,255,.08);color:var(--muted);font-size:12px}.mono{font:12px ui-monospace,SFMono-Regular,Menlo,Consolas,monospace}
.stats{display:grid;grid-template-columns:repeat(6,1fr);gap:12px;margin-bottom:12px}.stat{position:relative;min-height:112px;padding:16px 17px;background:linear-gradient(180deg,#101620,#0d121a);border:1px solid var(--line);border-radius:17px;box-shadow:0 10px 25px rgba(0,0,0,.12)}.statLabel{color:var(--muted);font-size:11px;text-transform:uppercase;letter-spacing:.08em;font-weight:900}.statNum{font-size:28px;font-weight:950;letter-spacing:-.04em;margin-top:10px}.statHint{font-size:11px;color:#707c8f;margin-top:2px}.accentLine{position:absolute;left:17px;right:17px;bottom:11px;height:2px;background:linear-gradient(90deg,var(--purple),transparent);opacity:.5}
.section{background:rgba(13,17,25,.92);border:1px solid var(--line);border-radius:19px;overflow:hidden;box-shadow:0 12px 36px rgba(0,0,0,.12);margin-top:14px}.sectionHead{padding:17px 18px;display:flex;align-items:center;justify-content:space-between;gap:12px;border-bottom:1px solid var(--line);background:linear-gradient(180deg,rgba(255,255,255,.018),transparent)}.sectionTitle{display:flex;align-items:center;gap:10px}.icon{width:34px;height:34px;display:grid;place-items:center;border-radius:10px;background:#171e2b;border:1px solid #283144}.section h2{font-size:16px;margin:0;letter-spacing:-.015em}.count{color:var(--muted);font-size:12px;font-weight:800}.tableWrap{overflow:auto;max-height:485px}.table{border-collapse:collapse;width:100%;min-width:940px}.table th,.table td{padding:12px 14px;text-align:left;border-bottom:1px solid rgba(37,45,59,.78);vertical-align:top}.table th{position:sticky;top:0;z-index:2;background:#111722;color:#8f9bad;font-size:10px;text-transform:uppercase;letter-spacing:.09em;font-weight:900}.table tbody tr:hover{background:rgba(167,139,250,.035)}.table tbody tr:last-child td{border-bottom:0}.channelName{display:flex;align-items:center;gap:10px}.avatar{width:34px;height:34px;border-radius:10px;display:grid;place-items:center;background:linear-gradient(135deg,#202938,#151b26);border:1px solid #2c3546;color:#cdd5e2;font-weight:900;flex:0 0 auto}.channelMeta{min-width:0}.channelMeta b{display:block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:230px}.sub{font-size:11px;color:#778398}.pill{display:inline-flex;align-items:center;gap:6px;padding:5px 8px;border-radius:999px;font-size:10px;font-weight:950;letter-spacing:.04em;border:1px solid transparent}.pill.green{color:#8df0c4;background:#0c241c;border-color:#1a4c39}.pill.yellow{color:#fbd46b;background:#2c210d;border-color:#5c4315}.pill.red{color:#fda4b1;background:#2b1118;border-color:#5d2030}.pill.blue{color:#8bdff0;background:#0d2228;border-color:#1a4d59}.clip{max-width:340px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}.empty{text-align:center;padding:44px!important;color:#6f7b8e!important}.small{font-size:11px;color:#758197}.muted{color:var(--muted)}.right{text-align:right}.link{color:#c4b5fd;text-decoration:none;font-weight:800}.link:hover{text-decoration:underline}.grid{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-top:14px}.storage{display:flex;justify-content:space-between;gap:15px;align-items:center}.storageBox{display:flex;align-items:center;gap:10px}.storageIcon{width:38px;height:38px;border-radius:11px;display:grid;place-items:center;background:#171d29;border:1px solid #2a3344}.future{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;padding:14px}.futureCard{padding:15px;border:1px solid #222b39;border-radius:14px;background:#0f141d}.futureCard strong{display:block;margin-bottom:4px}.footer{padding:18px 2px 5px;color:#626d7d;font-size:11px;display:flex;justify-content:space-between;gap:15px;flex-wrap:wrap}
@media(max-width:1280px){.stats{grid-template-columns:repeat(3,1fr)}}@media(max-width:900px){.shell{padding:15px}.heroHead{flex-direction:column}.grid{grid-template-columns:1fr}.stats{grid-template-columns:repeat(2,1fr)}.future{grid-template-columns:1fr}.topbar{align-items:flex-start}.hero h1{font-size:28px}}@media(max-width:560px){.stats{grid-template-columns:1fr 1fr}.stat{min-height:98px}.statNum{font-size:23px}.actions{width:100%}.btn{flex:1;justify-content:center}.metaRow{align-items:flex-start;flex-direction:column}.storage{align-items:flex-start;flex-direction:column}}
</style>
</head>
<body>
<div class="shell">
  <header class="topbar">
    <div class="brandRow">
      <div class="logo">🤖</div>
      <div class="brand">Keddy <span style="color:#a78bfa">Data Center</span><small>Live moderation &amp; data intelligence</small></div>
    </div>
    <div class="actions">
      <button class="btn" onclick="location.reload()">↻ Refresh</button>
      <a class="btn" href="viewer-comments.php">💬 Comments</a>
      <a class="btn primary" href="index.php">← Dashboard</a>
    </div>
  </header>

  <section class="hero">
    <div class="heroHead">
      <div>
        <div class="eyebrow">KEDDY / STORAGE OVERVIEW</div>
        <h1>Everything Keddy is keeping track of.</h1>
        <p>Channels, registered users, YouTube profiles, videos, live broadcasts, chat, moderation events, snapshots and comment history — all in one place.</p>
      </div>
      <div class="liveBadge"><span class="dot"></span> Data Center Online</div>
    </div>
    <div class="metaRow">
      <div>SQLite <span class="mono" style="color:#b9c3d2"><?=dcH(basename($dbFile))?></span></div>
      <div class="storageBox"><span>Database size</span><strong style="color:#fff"><?=dcH(dcBytes($dbSize))?></strong></div>
      <div>Updated <?=dcH(date('d M Y, h:i A'))?></div>
    </div>
  </section>

  <section class="stats">
    <?php foreach([
      ['managed_channels','Managed channels','Connected / tracked'],
      ['moderated_channels','Moderated','Bot moderator confirmed'],
      ['registered_users','Keddy users','Registered channels'],
      ['youtube_users','YouTube users','Captured channel profiles'],
      ['videos','Videos','Stored video metadata'],
      ['chat_messages','Chat messages','Captured live chat']
    ] as $s): ?>
      <div class="stat"><div class="statLabel"><?=dcH($s[1])?></div><div class="statNum"><?=dcNum($stats[$s[0]])?></div><div class="statHint"><?=dcH($s[2])?></div><div class="accentLine"></div></div>
    <?php endforeach; ?>
  </section>
  <section class="stats">
    <?php foreach([
      ['moderation_events','Moderation events','Warnings / timeouts / bans'],
      ['live_broadcasts','Live broadcasts','Detected live sessions'],
      ['snapshots','Snapshots','Channel API captures'],
      ['channel_events','Channel events','Moderator lifecycle'],
      ['comments_posted','Comments posted','Successful creator comments'],
      ['comments_failed','Comments failed','Failed comment attempts']
    ] as $s): ?>
      <div class="stat"><div class="statLabel"><?=dcH($s[1])?></div><div class="statNum"><?=dcNum($stats[$s[0]])?></div><div class="statHint"><?=dcH($s[2])?></div><div class="accentLine"></div></div>
    <?php endforeach; ?>
  </section>

  <section class="section" id="channels">
    <div class="sectionHead"><div class="sectionTitle"><div class="icon">📺</div><div><h2>Managed / Moderated Channels</h2><div class="count"><?=dcNum($stats['managed_channels'])?> channel(s)</div></div></div><span class="pill blue">LIVE DATA</span></div>
    <div class="tableWrap"><table class="table"><thead><tr><th>Channel</th><th>Handle</th><th>Country</th><th>Subscribers</th><th>Views</th><th>Videos</th><th>Registered</th><th>Moderation</th><th>Last Seen</th></tr></thead><tbody>
    <?php if(!$channels): ?><tr><td colspan="9" class="empty">No managed channel data yet.</td></tr>
    <?php else: foreach($channels as $r): ?><tr>
      <td><div class="channelName"><div class="avatar">📡</div><div class="channelMeta"><b><?=dcH($r['title'])?></b><span class="sub mono"><?=dcH($r['channel_id'])?></span></div></div></td>
      <td><?=dcH($r['handle']?:$r['custom_url']?:'—')?></td><td><?=dcH($r['country']?:'—')?></td><td><b><?=dcNum($r['subscriber_count']??0)?></b></td><td><?=dcNum($r['view_count']??0)?></td><td><?=dcNum($r['video_count']??0)?></td>
      <td><?=!empty($r['registered_user'])?'<span class="pill green">● YES</span>':'<span class="pill yellow">● NO</span>'?></td>
      <td><?=!empty($r['moderation_enabled'])?'<span class="pill green">● MOD ON</span>':'<span class="pill yellow">● OFF</span>'?></td>
      <td><?=dcH(dcTime($r['last_seen_at']))?></td>
    </tr><?php endforeach; endif; ?></tbody></table></div>
  </section>

  <div class="grid">
    <section class="section"><div class="sectionHead"><div class="sectionTitle"><div class="icon">👤</div><div><h2>YouTube Users</h2><div class="count"><?=dcNum($stats['youtube_users'])?> profiles</div></div></div></div>
      <div class="tableWrap"><table class="table"><thead><tr><th>User</th><th>Country</th><th>Subscribers</th><th>Views</th><th>Videos</th><th>Last Seen</th></tr></thead><tbody>
      <?php if(!$users): ?><tr><td colspan="6" class="empty">No users captured yet.</td></tr><?php else: foreach($users as $r): ?><tr><td><div class="channelMeta"><b><?=dcH($r['display_name'])?></b><span class="sub mono"><?=dcH($r['channel_id'])?></span><span class="small clip"><?=dcH($r['description'])?></span></div></td><td><?=dcH($r['country']?:'—')?></td><td><?=dcNum($r['subscriber_count']??0)?></td><td><?=dcNum($r['view_count']??0)?></td><td><?=dcNum($r['video_count']??0)?></td><td><?=dcH(dcTime($r['last_seen_at']))?></td></tr><?php endforeach; endif; ?></tbody></table></div>
    </section>
    <section class="section"><div class="sectionHead"><div class="sectionTitle"><div class="icon">🎬</div><div><h2>Videos</h2><div class="count"><?=dcNum($stats['videos'])?> stored</div></div></div></div>
      <div class="tableWrap"><table class="table"><thead><tr><th>Video</th><th>Channel</th><th>Published</th><th>Duration</th><th>Privacy</th><th>Type</th></tr></thead><tbody>
      <?php if(!$videos): ?><tr><td colspan="6" class="empty">No video data yet.</td></tr><?php else: foreach($videos as $r): ?><tr><td><b class="clip"><?=dcH($r['title'])?></b><div class="small clip"><?=dcH($r['description'])?></div><div class="sub mono"><?=dcH($r['video_id'])?></div></td><td class="mono"><?=dcH($r['channel_id'])?></td><td><?=dcH($r['published_at']?:'—')?></td><td><?=dcH($r['duration']?:'—')?></td><td><?=dcH($r['privacy_status']?:'—')?></td><td><?=dcH($r['live_broadcast_content']?:'none')?></td></tr><?php endforeach; endif; ?></tbody></table></div>
    </section>
  </div>

  <div class="grid">
    <section class="section"><div class="sectionHead"><div class="sectionTitle"><div class="icon">💬</div><div><h2>Live Chat</h2><div class="count"><?=dcNum($stats['chat_messages'])?> messages captured</div></div></div></div>
      <div class="tableWrap"><table class="table"><thead><tr><th>Author</th><th>Message</th><th>Published</th><th>Captured</th></tr></thead><tbody>
      <?php if(!$chat): ?><tr><td colspan="4" class="empty">No chat data yet.</td></tr><?php else: foreach($chat as $r): ?><tr><td><b><?=dcH($r['author_name'])?></b><div class="sub mono"><?=dcH($r['author_channel_id'])?></div></td><td><?=dcH($r['message_text'])?></td><td><?=dcH($r['published_at']?:'—')?></td><td><?=dcH(dcTime($r['captured_at']))?></td></tr><?php endforeach; endif; ?></tbody></table></div>
    </section>
    <section class="section"><div class="sectionHead"><div class="sectionTitle"><div class="icon">🛡️</div><div><h2>Moderation Events</h2><div class="count"><?=dcNum($stats['moderation_events'])?> recorded</div></div></div></div>
      <div class="tableWrap"><table class="table"><thead><tr><th>Target</th><th>Action</th><th>Severity</th><th>Reason</th><th>Offense</th><th>When</th></tr></thead><tbody>
      <?php if(!$mods): ?><tr><td colspan="6" class="empty">No moderation events yet.</td></tr><?php else: foreach($mods as $r): ?><tr><td><b><?=dcH($r['target_name'])?></b><div class="sub mono"><?=dcH($r['target_channel_id'])?></div></td><td><?=in_array($r['action'],['ban','timeout'],true)?'<span class="pill red">'.dcH(strtoupper($r['action'])).'</span>':'<span class="pill yellow">'.dcH(strtoupper($r['action'])).'</span>'?></td><td><b><?=dcH($r['severity'])?></b></td><td><?=dcH($r['reason'])?><div class="small"><?=dcH($r['hit'])?></div></td><td><?=dcH($r['offense']??'—')?></td><td><?=dcH(dcTime($r['created_at']))?></td></tr><?php endforeach; endif; ?></tbody></table></div>
    </section>
  </div>

  <div class="grid">
    <section class="section"><div class="sectionHead"><div class="sectionTitle"><div class="icon">📡</div><div><h2>Live Broadcasts</h2><div class="count"><?=dcNum($stats['live_broadcasts'])?> detected</div></div></div></div>
      <div class="tableWrap"><table class="table"><thead><tr><th>Broadcast</th><th>Channel</th><th>Chat ID</th><th>Start</th><th>Last Seen</th></tr></thead><tbody>
      <?php if(!$lives): ?><tr><td colspan="5" class="empty">No live broadcasts captured.</td></tr><?php else: foreach($lives as $r): ?><tr><td><b class="clip"><?=dcH($r['title'])?></b><div class="sub mono"><?=dcH($r['live_id'])?></div></td><td class="mono"><?=dcH($r['channel_id'])?></td><td class="mono"><?=dcH($r['live_chat_id'])?></td><td><?=dcH($r['actual_start']?:$r['published_at']?:'—')?></td><td><?=dcH(dcTime($r['last_seen_at']))?></td></tr><?php endforeach; endif; ?></tbody></table></div>
    </section>
    <section class="section"><div class="sectionHead"><div class="sectionTitle"><div class="icon">💬</div><div><h2>Keddy Comments</h2><div class="count"><?=dcNum($stats['comments_posted'])?> posted · <?=dcNum($stats['comments_failed'])?> failed</div></div></div><a class="link" href="viewer-comments.php">Open comments →</a></div>
      <div class="tableWrap"><table class="table"><thead><tr><th>Registered User</th><th>Video</th><th>Comment</th><th>When</th><th>Status</th></tr></thead><tbody>
      <?php if(!$comments): ?><tr><td colspan="5" class="empty">No comments yet.</td></tr><?php else: foreach($comments as $r): ?><tr><td><b><?=dcH($r['viewer_name'])?></b><div class="sub mono"><?=dcH($r['viewer_channel_id'])?></div></td><td><a class="link clip" target="_blank" rel="noopener" href="<?=dcH($r['video_url'])?>"><?=dcH($r['video_title'])?></a><div class="sub mono"><?=dcH($r['video_id'])?></div></td><td><?=dcH($r['comment_text'])?></td><td><?=dcH(dcTime($r['created_at']))?></td><td><?=$r['status']==='posted'?'<span class="pill green">● POSTED</span>':'<span class="pill red">● FAILED</span>'?></td></tr><?php endforeach; endif; ?></tbody></table></div>
    </section>
  </div>

  <section class="section"><div class="sectionHead"><div class="sectionTitle"><div class="icon">🧠</div><div><h2>Data retained for future Keddy features</h2><div class="count">Structured + raw API snapshots</div></div></div></div>
    <div class="future">
      <div class="futureCard"><strong>📺 Channels</strong><span class="muted">Profile, counters, topics, branding, status and raw snapshots.</span></div>
      <div class="futureCard"><strong>👤 Users</strong><span class="muted">Captured YouTube channel metadata linked to observed activity.</span></div>
      <div class="futureCard"><strong>🎬 Videos</strong><span class="muted">Metadata, descriptions, tags/statistics, privacy and status.</span></div>
      <div class="futureCard"><strong>📡 Live + Chat</strong><span class="muted">Broadcast identity, chat messages, authors and timestamps.</span></div>
      <div class="futureCard"><strong>🛡️ Moderation</strong><span class="muted">Target, action, severity, reason, offense and event history.</span></div>
      <div class="futureCard"><strong>💬 Comments</strong><span class="muted">Registered target, video, exact text, time and result.</span></div>
    </div>
  </section>

  <footer class="footer"><span>Keddy Data Center · <?=dcH(KEDDY_BOT_CHANNEL_NAME)?></span><span>Refresh after a live/cron run to see the newest captured data.</span></footer>
</div>
</body>
</html>
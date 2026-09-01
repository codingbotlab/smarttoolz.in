<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';
$admin = requireAdmin();
$db = adminDb();

function ae(mixed $v): string { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
function redirectTab(string $tab): never { header('Location: /smart-toolz/admin/?tab=' . rawurlencode($tab)); exit; }

$tab = (string)($_GET['tab'] ?? 'dashboard');
$allowedTabs = ['dashboard','analytics','ads','settings','users'];
if (!in_array($tab, $allowedTabs, true)) $tab = 'dashboard';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyAdminCsrf();
    $action = (string)($_POST['action'] ?? '');

    try {
        if ($action === 'ad_save') {
            $id = (int)($_POST['id'] ?? 0);
            $key = trim((string)($_POST['ad_key'] ?? ''));
            $enabled = !empty($_POST['enabled']) ? 1 : 0;
            $code = (string)($_POST['ad_code'] ?? '');
            if ($key === '' || !preg_match('/^[a-zA-Z0-9_.-]{1,80}$/', $key)) throw new RuntimeException('Invalid ad key.');
            if ($id > 0) {
                $q = $db->prepare('UPDATE ads_settings SET ad_key=?,enabled=?,ad_code=? WHERE id=?');
                $q->execute([$key,$enabled,$code,$id]);
                adminAudit('update_ad','ads_settings',(string)$id,$key);
            } else {
                $q = $db->prepare('INSERT INTO ads_settings(ad_key,enabled,ad_code) VALUES(?,?,?)');
                $q->execute([$key,$enabled,$code]);
                adminAudit('create_ad','ads_settings',(string)$db->lastInsertId(),$key);
            }
            redirectTab('ads');
        }

        if ($action === 'ad_delete') {
            $id = (int)($_POST['id'] ?? 0);
            if ($id > 0) { $q=$db->prepare('DELETE FROM ads_settings WHERE id=?'); $q->execute([$id]); adminAudit('delete_ad','ads_settings',(string)$id); }
            redirectTab('ads');
        }

        if ($action === 'setting_save') {
            $id = (int)($_POST['id'] ?? 0);
            $key = trim((string)($_POST['setting_key'] ?? ''));
            $value = (string)($_POST['setting_value'] ?? '');
            $type = (string)($_POST['setting_type'] ?? 'text');
            $description = trim((string)($_POST['description'] ?? ''));
            $enabled = !empty($_POST['enabled']) ? 1 : 0;
            if ($key === '' || !preg_match('/^[a-zA-Z0-9_.-]{1,120}$/', $key)) throw new RuntimeException('Invalid setting key.');
            if (!in_array($type,['text','number','boolean','json'],true)) $type='text';
            if ($type === 'json' && $value !== '') json_decode($value, true, 512, JSON_THROW_ON_ERROR);
            if ($id > 0) {
                $q=$db->prepare('UPDATE smarttoolz_settings SET setting_key=?,setting_value=?,setting_type=?,description=?,enabled=? WHERE id=?');
                $q->execute([$key,$value,$type,$description,$enabled,$id]); adminAudit('update_setting','smarttoolz_settings',(string)$id,$key);
            } else {
                $q=$db->prepare('INSERT INTO smarttoolz_settings(setting_key,setting_value,setting_type,description,enabled) VALUES(?,?,?,?,?)');
                $q->execute([$key,$value,$type,$description,$enabled]); adminAudit('create_setting','smarttoolz_settings',(string)$db->lastInsertId(),$key);
            }
            redirectTab('settings');
        }

        if ($action === 'setting_delete') {
            $id=(int)($_POST['id']??0);
            if($id>0){$q=$db->prepare('DELETE FROM smarttoolz_settings WHERE id=?');$q->execute([$id]);adminAudit('delete_setting','smarttoolz_settings',(string)$id);}
            redirectTab('settings');
        }

        if ($action === 'user_role') {
            $id=(int)($_POST['id']??0);
            $role=(string)($_POST['role']??'user');
            if(!in_array($role,['user','admin','superadmin'],true)) $role='user';
            $q=$db->prepare('SELECT email FROM creator_users WHERE id=? LIMIT 1');$q->execute([$id]);$email=(string)$q->fetchColumn();
            if(strtolower($email)===strtolower(SMARTTOOLZ_ADMIN_EMAIL)) $role='admin';
            $q=$db->prepare('UPDATE creator_users SET role=? WHERE id=?');$q->execute([$role,$id]);
            if(adminTableExists($db,'users') && adminColumnExists($db,'users','role') && $email!==''){$q=$db->prepare('UPDATE users SET role=? WHERE LOWER(email)=LOWER(?)');$q->execute([$role,$email]);}
            adminAudit('change_user_role','creator_users',(string)$id,$email . ' => ' . $role);
            redirectTab('users');
        }

        if ($action === 'user_credits') {
            $id=(int)($_POST['id']??0);$credits=max(0,(int)($_POST['credits']??0));$daily=max(0,(int)($_POST['daily_credits']??0));
            $q=$db->prepare('UPDATE creator_users SET credits=?,daily_credits=? WHERE id=?');$q->execute([$credits,$daily,$id]);
            adminAudit('update_user_credits','creator_users',(string)$id,'credits='.$credits.',daily='.$daily); redirectTab('users');
        }
    } catch (Throwable $e) {
        $_SESSION['admin_flash'] = ['type'=>'error','text'=>$e->getMessage()];
        redirectTab($tab);
    }
}

$flash=$_SESSION['admin_flash']??null;unset($_SESSION['admin_flash']);
$stats=[];
foreach([
    'Users'=>"SELECT COUNT(*) FROM creator_users",
    'Admins'=>"SELECT COUNT(*) FROM creator_users WHERE role IN ('admin','superadmin')",
    'Ads'=>"SELECT COUNT(*) FROM ads_settings",
    'Enabled Ads'=>"SELECT COUNT(*) FROM ads_settings WHERE enabled=1",
    'Settings'=>"SELECT COUNT(*) FROM smarttoolz_settings",
    'Tool Uses'=>"SELECT COUNT(*) FROM tool_usage"
] as $label=>$sql){try{$stats[$label]=(int)$db->query($sql)->fetchColumn();}catch(Throwable $e){$stats[$label]=0;}}

$ads=$db->query('SELECT * FROM ads_settings ORDER BY id DESC')->fetchAll(PDO::FETCH_ASSOC);
$settings=$db->query('SELECT * FROM smarttoolz_settings ORDER BY setting_key ASC')->fetchAll(PDO::FETCH_ASSOC);
$users=$db->query('SELECT id,name,email,role,credits,daily_credits,plan,created_at FROM creator_users ORDER BY id DESC LIMIT 200')->fetchAll(PDO::FETCH_ASSOC);

$editAd=null;$editSetting=null;
if($tab==='ads' && isset($_GET['edit'])){ $q=$db->prepare('SELECT * FROM ads_settings WHERE id=?');$q->execute([(int)$_GET['edit']]);$editAd=$q->fetch(PDO::FETCH_ASSOC)?:null; }
if($tab==='settings' && isset($_GET['edit'])){ $q=$db->prepare('SELECT * FROM smarttoolz_settings WHERE id=?');$q->execute([(int)$_GET['edit']]);$editSetting=$q->fetch(PDO::FETCH_ASSOC)?:null; }

$analytics=[];
foreach([
 'Visitors (7d)'=>"SELECT COUNT(*) FROM analytics_visitors WHERE first_seen >= DATE_SUB(NOW(),INTERVAL 7 DAY)",
 'Page Views (7d)'=>"SELECT COUNT(*) FROM analytics_pageviews WHERE viewed_at >= DATE_SUB(NOW(),INTERVAL 7 DAY)",
 'Live Now'=>"SELECT COUNT(*) FROM analytics_live WHERE last_seen >= DATE_SUB(NOW(),INTERVAL 5 MINUTE)",
 'Events (7d)'=>"SELECT COUNT(*) FROM analytics_events WHERE viewed_at >= DATE_SUB(NOW(),INTERVAL 7 DAY)"
] as $label=>$sql){try{$analytics[$label]=(int)$db->query($sql)->fetchColumn();}catch(Throwable $e){$analytics[$label]=0;}}
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>SmartToolz Admin</title><style>
:root{--p:#635bff;--ink:#172033;--muted:#707b8e;--bg:#f6f8fc;--line:#e4e8f0;--danger:#d92d20}*{box-sizing:border-box}body{margin:0;font-family:Inter,Arial,sans-serif;color:var(--ink);background:var(--bg)}a{text-decoration:none;color:inherit}.layout{display:grid;grid-template-columns:235px 1fr;min-height:100vh}.side{background:#fff;border-right:1px solid var(--line);padding:22px 14px;position:sticky;top:0;height:100vh}.brand{display:flex;gap:9px;align-items:center;font-weight:900;font-size:18px;padding:8px 10px 22px}.brand i{width:38px;height:38px;border-radius:12px;display:grid;place-items:center;background:linear-gradient(135deg,#635bff,#916cff);color:#fff;font-style:normal}.nav{display:grid;gap:5px}.nav a{padding:11px 12px;border-radius:10px;color:#596477;font-size:13px;font-weight:750}.nav a:hover,.nav a.active{background:#eeedff;color:var(--p)}.bottom{position:absolute;bottom:18px;left:14px;right:14px}.main{padding:28px;max-width:1500px;width:100%;margin:auto}.top{display:flex;justify-content:space-between;align-items:center;margin-bottom:22px}.top h1{margin:0;font-size:28px}.top p{margin:4px 0 0;color:var(--muted);font-size:13px}.admin-pill{background:#eeedff;color:var(--p);padding:8px 12px;border-radius:999px;font-size:12px;font-weight:800}.flash{padding:12px 15px;border-radius:12px;background:#fff4e5;color:#9a5a00;margin-bottom:16px;border:1px solid #ffd9a8}.grid{display:grid;grid-template-columns:repeat(4,1fr);gap:14px}.stat{background:#fff;border:1px solid var(--line);border-radius:16px;padding:18px}.stat strong{display:block;font-size:25px}.stat small{color:var(--muted)}.panel{background:#fff;border:1px solid var(--line);border-radius:16px;padding:20px;margin-top:18px}.panel h2{margin:0 0 15px;font-size:18px}.table-wrap{overflow:auto}table{width:100%;border-collapse:collapse;font-size:12px}th,td{text-align:left;padding:11px 9px;border-bottom:1px solid #edf0f4;vertical-align:top}th{color:#596477;background:#fafbfe}input,select,textarea{width:100%;border:1px solid #dce1e9;border-radius:10px;padding:10px 11px;font:inherit;font-size:12px;outline:none;background:#fff}textarea{min-height:150px;resize:vertical}.form-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:12px}.field label{display:block;font-size:11px;font-weight:800;color:#596477;margin:0 0 5px}.field.full{grid-column:1/-1}.actions{display:flex;gap:8px;flex-wrap:wrap;margin-top:13px}.btn{border:0;border-radius:10px;padding:10px 14px;background:#eef0f5;color:#263044;font-weight:800;font-size:12px;cursor:pointer}.primary{background:var(--p);color:#fff}.danger{background:#fff0ef;color:var(--danger)}.mini{padding:7px 9px;font-size:11px}.inline{display:flex;gap:6px;align-items:center}.inline input,.inline select{min-width:100px}.muted{color:var(--muted)}.tag{display:inline-block;padding:4px 8px;border-radius:999px;background:#f0f2f7;font-size:10px;font-weight:800}.tag.admin{background:#eeedff;color:var(--p)}.tag.enabled{background:#eaf8ef;color:#167347}.split{display:grid;grid-template-columns:1fr 1.6fr;gap:18px}.notice{padding:14px;background:#f7f8fc;border:1px solid var(--line);border-radius:12px;color:#596477;font-size:12px;line-height:1.6}.kpi-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:12px}.kpi{padding:16px;background:#f8f9fd;border:1px solid var(--line);border-radius:13px}.kpi b{display:block;font-size:22px}.kpi span{font-size:11px;color:var(--muted)}@media(max-width:1000px){.layout{grid-template-columns:1fr}.side{position:relative;height:auto;border-right:0;border-bottom:1px solid var(--line)}.bottom{position:static;margin-top:15px}.grid,.kpi-grid{grid-template-columns:repeat(2,1fr)}.split{grid-template-columns:1fr}}@media(max-width:600px){.main{padding:16px}.grid,.kpi-grid,.form-grid{grid-template-columns:1fr}.top{align-items:flex-start;gap:12px;flex-direction:column}}
</style></head><body><div class="layout"><aside class="side"><div class="brand"><i>🛠️</i> SmartToolz</div><nav class="nav">
<a class="<?= $tab==='dashboard'?'active':'' ?>" href="?tab=dashboard">📊 Dashboard</a><a class="<?= $tab==='analytics'?'active':'' ?>" href="?tab=analytics">📈 Analytics</a><a class="<?= $tab==='ads'?'active':'' ?>" href="?tab=ads">📢 Ads Settings</a><a class="<?= $tab==='settings'?'active':'' ?>" href="?tab=settings">⚙️ DB Settings</a><a class="<?= $tab==='users'?'active':'' ?>" href="?tab=users">👥 Users & Roles</a><a href="/analytics/" target="_blank">↗ Full Analytics</a></nav><div class="bottom"><a class="btn" style="display:block;text-align:center" href="/smart-toolz/">← Back to site</a></div></aside><main class="main"><div class="top"><div><h1><?=ae(ucwords($tab))?></h1><p>SmartToolz administration and database controls.</p></div><span class="admin-pill">ADMIN · <?=ae($admin['email'])?></span></div><?php if($flash): ?><div class="flash"><?=ae($flash['text']??'Done')?></div><?php endif; ?>
<?php if($tab==='dashboard'): ?><div class="grid"><?php foreach($stats as $k=>$v): ?><div class="stat"><strong><?=number_format($v)?></strong><small><?=ae($k)?></small></div><?php endforeach; ?></div><div class="panel"><h2>Admin controls</h2><div class="notice">This panel is restricted to accounts with <b>admin</b> or <b>superadmin</b> role. The requested account <b><?=ae(SMARTTOOLZ_ADMIN_EMAIL)?></b> is automatically promoted to <b>admin</b> when the admin schema is initialized. Ads, site settings, roles and credits are changed through prepared SQL statements with CSRF protection.</div></div>
<?php elseif($tab==='analytics'): ?>
<?php
$aaRows = function(string $sql, array $params = []) use ($db): array {
    try { $q=$db->prepare($sql); $q->execute($params); return $q->fetchAll(PDO::FETCH_ASSOC); }
    catch(Throwable $e){ return []; }
};
$aaCount = function(string $sql, array $params = []) use ($db): int {
    try { $q=$db->prepare($sql); $q->execute($params); return (int)$q->fetchColumn(); }
    catch(Throwable $e){ return 0; }
};
$aaPages = $aaRows("SELECT page_path, COUNT(*) total FROM analytics_pageviews WHERE viewed_at >= DATE_SUB(NOW(), INTERVAL 7 DAY) GROUP BY page_path ORDER BY total DESC LIMIT 8");
$aaCountries = $aaRows("SELECT COALESCE(NULLIF(country,''),'Unknown') label, COUNT(*) total FROM analytics_pageviews WHERE viewed_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) GROUP BY country ORDER BY total DESC LIMIT 10");
$aaDevices = $aaRows("SELECT COALESCE(NULLIF(device,''),'Unknown') label, COUNT(*) total FROM analytics_pageviews WHERE viewed_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) GROUP BY device ORDER BY total DESC LIMIT 8");
$aaBrowsers = $aaRows("SELECT COALESCE(NULLIF(browser,''),'Unknown') label, COUNT(*) total FROM analytics_pageviews WHERE viewed_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) GROUP BY browser ORDER BY total DESC LIMIT 8");
$aaHourly = $aaRows("SELECT DATE_FORMAT(viewed_at,'%H:00') label, COUNT(*) total FROM analytics_pageviews WHERE viewed_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR) GROUP BY HOUR(viewed_at) ORDER BY HOUR(viewed_at)");
$aaLive = $aaRows("SELECT visitor_id,country_code,city,device,browser,page_path,last_seen FROM analytics_live WHERE last_seen >= DATE_SUB(NOW(),INTERVAL 5 MINUTE) ORDER BY last_seen DESC LIMIT 50");
$aaDownloads = $aaCount("SELECT COUNT(*) FROM download_tracking WHERE created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)");
$aaUses = $aaCount("SELECT COUNT(*) FROM tool_usage WHERE created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)");
$maxHour = max(1, max(array_map(fn($r)=>(int)$r['total'],$aaHourly ?: [['total'=>1]])));
?>
<style>
#st-advanced-analytics{margin-top:18px}.st-tabs{display:flex;gap:7px;flex-wrap:wrap;margin-bottom:14px}.st-tab{border:1px solid #e0e4ec;background:#fff;color:#596477;padding:9px 12px;border-radius:10px;font-size:11px;font-weight:800;cursor:pointer}.st-tab.active{background:#635bff;color:#fff;border-color:#635bff}.st-pane{display:none}.st-pane.active{display:block}.st-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:10px}.st-mini{padding:13px;border:1px solid #e5e9f0;background:#fff;border-radius:12px}.st-mini b{display:block;font-size:20px}.st-mini span{font-size:10px;color:#788397}.st-cols{display:grid;grid-template-columns:1.2fr 1fr;gap:14px;margin-top:14px}.st-chart{display:flex;align-items:end;gap:6px;height:150px;padding:12px 7px 4px;background:#f8f9fd;border:1px solid #e8ebf1;border-radius:12px}.st-col{flex:1;min-width:8px;display:flex;align-items:end;height:100%}.st-bar{width:100%;background:#635bff;border-radius:5px 5px 2px 2px;min-height:3px}.st-bars{display:grid;gap:7px}.st-line{display:flex;justify-content:space-between;font-size:10px;color:#596477}.st-track{height:7px;background:#edf0f4;border-radius:99px;overflow:hidden;margin-top:3px}.st-fill{height:100%;background:#635bff}.st-map{position:relative;min-height:230px;background:linear-gradient(180deg,#f7f9ff,#eef2fb);border:1px solid #e2e7f0;border-radius:14px;overflow:hidden}.st-map:before{content:'🌍';position:absolute;inset:0;display:grid;place-items:center;font-size:105px;opacity:.14}.st-map-inner{position:relative;z-index:1;padding:14px}.st-live{max-height:420px;overflow:auto}.st-live-row{display:grid;grid-template-columns:62px 1fr 1fr 1fr 1.4fr 120px;gap:7px;padding:8px 5px;border-bottom:1px solid #edf0f4;font-size:10px;align-items:center}.st-live-head{font-weight:900;color:#596477;background:#fafbfe;position:sticky;top:0}.st-dot{display:inline-flex;align-items:center;gap:5px;color:#168b51;font-weight:900}.st-dot:before{content:'';width:7px;height:7px;border-radius:50%;background:#19a463}.st-links{display:grid;grid-template-columns:repeat(4,1fr);gap:8px}.st-link{display:block;padding:11px;border:1px solid #e3e7ef;border-radius:10px;background:#fff;font-size:11px;font-weight:800;color:#475268}.st-link:hover{border-color:#635bff;color:#635bff}.st-note{padding:10px 12px;background:#f7f8fc;border:1px solid #e7eaf0;border-radius:11px;color:#677287;font-size:10px;line-height:1.5}.st-search{margin-bottom:8px}.st-search input{max-width:360px}.st-kicker{font-size:10px;color:#8992a2;margin-top:3px}@media(max-width:1000px){.st-grid{grid-template-columns:repeat(2,1fr)}.st-cols{grid-template-columns:1fr}.st-links{grid-template-columns:repeat(2,1fr)}}@media(max-width:600px){.st-grid{grid-template-columns:1fr}.st-links{grid-template-columns:1fr}.st-live-row{grid-template-columns:55px 1fr 1fr 1fr}.st-live-row>*:nth-child(n+5){display:none}}
</style>
<div id="st-advanced-analytics" class="panel">
  <div class="st-note"><b>Advanced Analytics</b> · compact admin view. Live visitors refresh every second without reloading this page.</div>
  <div class="st-tabs" role="tablist" aria-label="Analytics sections">
    <button class="st-tab active" data-st-tab="overview" type="button">Overview</button>
    <button class="st-tab" data-st-tab="traffic" type="button">Traffic</button>
    <button class="st-tab" data-st-tab="geo" type="button">Geo Map</button>
    <button class="st-tab" data-st-tab="tech" type="button">Technology</button>
    <button class="st-tab" data-st-tab="live" type="button">Live <span id="stLiveBadge"><?=count($aaLive)?></span></button>
    <button class="st-tab" data-st-tab="tools" type="button">Tools</button>
  </div>

  <section class="st-pane active" data-st-pane="overview">
    <div class="st-grid">
      <div class="st-mini"><b><?=$analytics['Visitors (7d)']??0?></b><span>Visitors · 7 days</span></div>
      <div class="st-mini"><b><?=$analytics['Page Views (7d)']??0?></b><span>Page views · 7 days</span></div>
      <div class="st-mini"><b><?=count($aaLive)?></b><span>Live right now</span></div>
      <div class="st-mini"><b><?=$aaUses?></b><span>Tool uses · 24h</span></div>
    </div>
    <div class="st-cols">
      <div class="panel" style="margin-top:0"><h2>Top Pages</h2><div class="st-bars"><?php $m=max(1,max(array_map(fn($r)=>(int)$r['total'],$aaPages ?: [['total'=>1]]))); foreach($aaPages as $r): ?><div><div class="st-line"><span><?=ae($r['page_path'])?></span><b><?=number_format((int)$r['total'])?></b></div><div class="st-track"><div class="st-fill" style="width:<?=((int)$r['total']/$m*100)?>%"></div></div></div><?php endforeach; ?></div></div>
      <div class="panel" style="margin-top:0"><h2>24h Snapshot</h2><div class="st-note">Downloads: <b><?=$aaDownloads?></b><br>Tool uses: <b><?=$aaUses?></b><br>Live window: <b>5 minutes</b></div></div>
    </div>
  </section>

  <section class="st-pane" data-st-pane="traffic">
    <div class="panel" style="margin-top:0"><h2>Hourly Traffic · Last 24 Hours</h2><div class="st-chart"><?php foreach($aaHourly as $r): ?><div class="st-col" title="<?=ae($r['label'])?> · <?=number_format((int)$r['total'])?>"><div class="st-bar" style="height:<?=max(3,((int)$r['total']/$maxHour)*100)?>%"></div></div><?php endforeach; ?></div><div class="st-kicker">Bar height = page views in that hour.</div></div>
  </section>

  <section class="st-pane" data-st-pane="geo">
    <div class="st-cols">
      <div class="panel" style="margin-top:0"><h2>Geo Map</h2><div class="st-map"><div class="st-map-inner"><b>Visitor distribution</b><div class="st-kicker">Top countries</div><?php foreach($aaCountries as $r): ?><div style="margin-top:8px"><div class="st-line"><span><?=ae($r['label'])?></span><b><?=number_format((int)$r['total'])?></b></div><div class="st-track"><div class="st-fill" style="width:<?=((int)$r['total']/max(1,(int)($aaCountries[0]['total']??1))*100)?>%"></div></div></div><?php endforeach; ?></div></div></div>
      <div class="panel" style="margin-top:0"><h2>Top Cities</h2><div class="st-bars"><?php $aaCities=$aaRows("SELECT COALESCE(NULLIF(city,''),'Unknown') label, COUNT(*) total FROM analytics_pageviews WHERE viewed_at >= DATE_SUB(NOW(),INTERVAL 30 DAY) GROUP BY city ORDER BY total DESC LIMIT 10"); foreach($aaCities as $r): ?><div class="st-line"><span><?=ae($r['label'])?></span><b><?=number_format((int)$r['total'])?></b></div><?php endforeach; ?></div></div>
    </div>
  </section>

  <section class="st-pane" data-st-pane="tech">
    <div class="st-cols">
      <div class="panel" style="margin-top:0"><h2>Devices</h2><div class="st-bars"><?php $m=max(1,(int)($aaDevices[0]['total']??1)); foreach($aaDevices as $r): ?><div><div class="st-line"><span><?=ae($r['label'])?></span><b><?=number_format((int)$r['total'])?></b></div><div class="st-track"><div class="st-fill" style="width:<?=((int)$r['total']/$m*100)?>%"></div></div></div><?php endforeach; ?></div></div>
      <div class="panel" style="margin-top:0"><h2>Browsers</h2><div class="st-bars"><?php foreach($aaBrowsers as $r): ?><div class="st-line"><span><?=ae($r['label'])?></span><b><?=number_format((int)$r['total'])?></b></div><?php endforeach; ?></div></div>
    </div>
  </section>

  <section class="st-pane" data-st-pane="live">
    <div class="st-search"><input id="stLiveSearch" type="search" placeholder="Search page, country, city, browser..."></div>
    <div class="st-live panel" style="margin-top:0"><div class="st-live-row st-live-head"><span>Status</span><span>Location</span><span>Device</span><span>Browser</span><span>Current Page</span><span>Last Seen</span></div><div id="stLiveRows"><?php foreach($aaLive as $r): ?><div class="st-live-row"><span class="st-dot">LIVE</span><span><?=ae(($r['country_code']??'Unknown').' '.($r['city']??''))?></span><span><?=ae($r['device']??'Unknown')?></span><span><?=ae($r['browser']??'Unknown')?></span><span><?=ae($r['page_path']??'-')?></span><span><?=ae($r['last_seen']??'-')?></span></div><?php endforeach; ?></div></div>
  </section>

  <section class="st-pane" data-st-pane="tools">
    <div class="st-links">
      <a class="st-link" href="/analytics/">📊 Full Analytics</a>
      <a class="st-link" href="/analytics/advanced.php">🚀 Advanced Reports</a>
      <a class="st-link" href="/analytics/live.php" target="_blank">🟢 Live API</a>
      <a class="st-link" href="/smart-toolz/admin/?tab=analytics">🔄 Refresh Analytics</a>
      <a class="st-link" href="/smart-toolz/admin/?tab=users">👥 Users & Roles</a>
      <a class="st-link" href="/smart-toolz/admin/?tab=settings">⚙️ DB Settings</a>
      <a class="st-link" href="/smart-toolz/admin/?tab=ads">📢 Ads Settings</a>
      <a class="st-link" href="/smart-toolz/admin/">🏠 Admin Dashboard</a>
    </div>
  </section>
</div>
<script>
(()=>{
 const root=document.getElementById('st-advanced-analytics'); if(!root)return;
 root.querySelectorAll('.st-tab').forEach(btn=>btn.addEventListener('click',()=>{root.querySelectorAll('.st-tab').forEach(x=>x.classList.remove('active'));root.querySelectorAll('.st-pane').forEach(x=>x.classList.remove('active'));btn.classList.add('active');root.querySelector('[data-st-pane="'+btn.dataset.stTab+'"]').classList.add('active')}));
 const search=document.getElementById('stLiveSearch'), rows=document.getElementById('stLiveRows');
 function render(list){rows.innerHTML=list.map(r=>`<div class="st-live-row"><span class="st-dot">LIVE</span><span>${esc((r.country_code||'Unknown')+' '+(r.city||''))}</span><span>${esc(r.device||'Unknown')}</span><span>${esc(r.browser||'Unknown')}</span><span>${esc(r.page_path||'-')}</span><span>${esc(r.last_seen||'-')}</span></div>`).join('')||'<div class="st-note">No live visitors.</div>';document.getElementById('stLiveBadge').textContent=list.length}
 const esc=s=>String(s??'').replace(/[&<>\"]/g,m=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;','\\':'&#92;'}[m]));
 let cache=[]; search?.addEventListener('input',()=>{const q=search.value.toLowerCase();render(cache.filter(r=>(JSON.stringify(r)).toLowerCase().includes(q)))});
 async function live(){try{const r=await fetch('/analytics/live.php?_='+Date.now(),{cache:'no-store'});const d=await r.json();if(d.ok){cache=d.visitors||[];if(!search.value)render(cache);else search.dispatchEvent(new Event('input'))}}catch(e){}}
 live();setInterval(live,1000);
})();
</script>
<?php elseif($tab==='ads'): ?><div class="split"><section class="panel"><h2><?= $editAd?'Edit Ad':'Add Ad' ?></h2><form method="post"><input type="hidden" name="csrf" value="<?=ae(adminCsrf())?>"><input type="hidden" name="action" value="ad_save"><input type="hidden" name="id" value="<?=ae($editAd['id']??0)?>"><div class="form-grid"><div class="field"><label>Ad Key</label><input name="ad_key" required placeholder="top_desktop" value="<?=ae($editAd['ad_key']??'')?>"></div><div class="field"><label>Status</label><label class="inline"><input style="width:auto" type="checkbox" name="enabled" value="1" <?=!empty($editAd['enabled'])?'checked':''?>> Enabled</label></div><div class="field full"><label>Ad Code</label><textarea name="ad_code" placeholder="Paste ad/network HTML or script here"><?=ae($editAd['ad_code']??'')?></textarea></div></div><div class="actions"><button class="btn primary">Save Ad</button><?php if($editAd): ?><a class="btn" href="?tab=ads">Cancel</a><?php endif; ?></div></form></section><section class="panel"><h2>Ad Slots</h2><div class="table-wrap"><table><tr><th>Key</th><th>Status</th><th>Updated</th><th>Actions</th></tr><?php foreach($ads as $a): ?><tr><td><b><?=ae($a['ad_key'])?></b></td><td><span class="tag <?=!empty($a['enabled'])?'enabled':''?>"><?=!empty($a['enabled'])?'Enabled':'Disabled'?></span></td><td class="muted"><?=ae($a['updated_at'])?></td><td><a class="btn mini" href="?tab=ads&edit=<?=(int)$a['id']?>">Edit</a> <form style="display:inline" method="post"><input type="hidden" name="csrf" value="<?=ae(adminCsrf())?>"><input type="hidden" name="action" value="ad_delete"><input type="hidden" name="id" value="<?=(int)$a['id']?>"><button class="btn mini danger" onclick="return confirm('Delete this ad slot?')">Delete</button></form></td></tr><?php endforeach; ?></table></div></section></div>
<?php elseif($tab==='settings'): ?><div class="split"><section class="panel"><h2><?= $editSetting?'Edit Setting':'Add DB Setting' ?></h2><form method="post"><input type="hidden" name="csrf" value="<?=ae(adminCsrf())?>"><input type="hidden" name="action" value="setting_save"><input type="hidden" name="id" value="<?=ae($editSetting['id']??0)?>"><div class="form-grid"><div class="field"><label>Setting Key</label><input name="setting_key" required placeholder="site_name" value="<?=ae($editSetting['setting_key']??'')?>"></div><div class="field"><label>Type</label><select name="setting_type"><?php foreach(['text','number','boolean','json'] as $t): ?><option value="<?=$t?>" <?=($editSetting['setting_type']??'text')===$t?'selected':''?>><?=$t?></option><?php endforeach; ?></select></div><div class="field full"><label>Value</label><textarea name="setting_value" placeholder="Setting value"><?=ae($editSetting['setting_value']??'')?></textarea></div><div class="field full"><label>Description</label><input name="description" value="<?=ae($editSetting['description']??'')?>"></div><div class="field"><label>Status</label><label class="inline"><input style="width:auto" type="checkbox" name="enabled" value="1" <?=($editSetting===null||!empty($editSetting['enabled']))?'checked':''?>> Enabled</label></div></div><div class="actions"><button class="btn primary">Save Setting</button><?php if($editSetting): ?><a class="btn" href="?tab=settings">Cancel</a><?php endif; ?></div></form></section><section class="panel"><h2>DB Settings Table</h2><div class="table-wrap"><table><tr><th>Key</th><th>Type</th><th>Value</th><th>Status</th><th>Actions</th></tr><?php foreach($settings as $s): ?><tr><td><b><?=ae($s['setting_key'])?></b><br><small class="muted"><?=ae($s['description'])?></small></td><td><?=ae($s['setting_type'])?></td><td><div style="max-width:280px;white-space:pre-wrap;word-break:break-word"><?=ae(mb_strimwidth((string)$s['setting_value'],0,300,'…'))?></div></td><td><span class="tag <?=!empty($s['enabled'])?'enabled':''?>"><?=!empty($s['enabled'])?'Enabled':'Disabled'?></span></td><td><a class="btn mini" href="?tab=settings&edit=<?=(int)$s['id']?>">Edit</a> <form style="display:inline" method="post"><input type="hidden" name="csrf" value="<?=ae(adminCsrf())?>"><input type="hidden" name="action" value="setting_delete"><input type="hidden" name="id" value="<?=(int)$s['id']?>"><button class="btn mini danger" onclick="return confirm('Delete this setting?')">Delete</button></form></td></tr><?php endforeach; ?></table></div></section></div>
<?php elseif($tab==='users'): ?><div class="panel"><h2>Users & Roles</h2><div class="notice" style="margin-bottom:14px">Role changes apply to the Creator account used by Google login. <b><?=ae(SMARTTOOLZ_ADMIN_EMAIL)?></b> is locked to admin so it cannot accidentally be demoted.</div><div class="table-wrap"><table><tr><th>User</th><th>Role</th><th>Plan</th><th>Credits</th><th>Created</th></tr><?php foreach($users as $u): ?><tr><td><b><?=ae($u['name'])?></b><br><span class="muted"><?=ae($u['email'])?></span></td><td><form method="post"><input type="hidden" name="csrf" value="<?=ae(adminCsrf())?>"><input type="hidden" name="action" value="user_role"><input type="hidden" name="id" value="<?=(int)$u['id']?>"><select name="role" onchange="this.form.submit()" <?=strtolower((string)$u['email'])===strtolower(SMARTTOOLZ_ADMIN_EMAIL)?'disabled':''?>><?php foreach(['user','admin','superadmin'] as $r): ?><option value="<?=$r?>" <?=($u['role']??'user')===$r?'selected':''?>><?=$r?></option><?php endforeach; ?></select><?php if(strtolower((string)$u['email'])===strtolower(SMARTTOOLZ_ADMIN_EMAIL)): ?><input type="hidden" name="role" value="admin"><?php endif; ?></form></td><td><?=ae($u['plan'])?></td><td><form class="inline" method="post"><input type="hidden" name="csrf" value="<?=ae(adminCsrf())?>"><input type="hidden" name="action" value="user_credits"><input type="hidden" name="id" value="<?=(int)$u['id']?>"><input type="number" name="credits" min="0" value="<?=(int)$u['credits']?>" title="Purchased credits"><input type="number" name="daily_credits" min="0" value="<?=(int)$u['daily_credits']?>" title="Daily credits"><button class="btn mini">Save</button></form></td><td class="muted"><?=ae($u['created_at'])?></td></tr><?php endforeach; ?></table></div></div><?php endif; ?>
</main></div></body></html>

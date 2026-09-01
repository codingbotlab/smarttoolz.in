<?php
declare(strict_types=1);
require_once __DIR__ . '/bootstrap.php';
$admin = requireAdmin();
$db = adminDb();

function av4h(mixed $v): string { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
$tab = (string)($_GET['tab'] ?? 'dashboard');
$allowed = ['dashboard','analytics','downloads','ads','settings','users','database'];
if (!in_array($tab, $allowed, true)) $tab = 'dashboard';

$tabFile = __DIR__ . '/tabs/' . $tab . '.php';
if (!is_file($tabFile)) $tabFile = __DIR__ . '/tabs/dashboard.php';

$live = 0;
try { $q=$db->query("SELECT COUNT(*) FROM analytics_live WHERE last_seen>=DATE_SUB(NOW(),INTERVAL 2 MINUTE)"); $live=(int)$q->fetchColumn(); } catch(Throwable $e) {}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>SmartToolz Admin</title>
<link rel="stylesheet" href="/smart-toolz/admin/admin.css?v=20260901-v4">
<style>
.av4{min-height:100vh;background:#f7f9fc}.av4-shell{display:grid;grid-template-columns:232px minmax(0,1fr);min-height:100vh}.av4-side{background:#fff;border-right:1px solid #e7ebf2;padding:16px 12px;position:sticky;top:0;height:100vh}.av4-brand{display:flex;gap:10px;align-items:center;padding:7px 9px 20px;font-weight:900}.av4-mark{width:40px;height:40px;border-radius:13px;display:grid;place-items:center;background:linear-gradient(135deg,#635bff,#9185ff);color:#fff}.av4-sub{display:block;color:#8a94a4;font-size:9px;margin-top:2px}.av4-nav{display:grid;gap:5px}.av4-nav a{display:flex;gap:9px;align-items:center;padding:10px 11px;border-radius:10px;color:#5f6b7d;font-size:11px;font-weight:850}.av4-nav a:hover,.av4-nav a.active{background:#eeedff;color:#635bff}.av4-sep{height:1px;background:#edf0f4;margin:9px 7px}.av4-foot{position:absolute;left:12px;right:12px;bottom:12px;padding:10px;border:1px solid #e7ebf2;border-radius:11px;background:#fafbfe;font-size:9px;color:#7f8999}.av4-main{min-width:0;padding:18px 20px}.av4-top{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:14px}.av4-title h1{margin:0;font-size:24px}.av4-title p{margin:3px 0 0;color:#7b8697;font-size:10px}.av4-live{padding:8px 11px;border:1px solid #e5e9f0;background:#fff;border-radius:999px;font-size:10px;font-weight:900}.av4-dot{display:inline-block;width:7px;height:7px;border-radius:50%;background:#16a36a;box-shadow:0 0 0 4px #16a36a18;margin-right:5px}.av4-content{min-width:0}@media(max-width:900px){.av4-shell{grid-template-columns:1fr}.av4-side{position:relative;height:auto;border-right:0;border-bottom:1px solid #e7ebf2}.av4-foot{position:static;margin-top:12px}.av4-main{padding:14px}}
</style>
</head><body class="av4"><div class="av4-shell">
<aside class="av4-side">
<div class="av4-brand"><div class="av4-mark">ST</div><div>SmartToolz<span class="av4-sub">Admin Control Center</span></div></div>
<nav class="av4-nav">
<a class="<?= $tab==='dashboard'?'active':'' ?>" href="?tab=dashboard">🏠 Dashboard</a>
<a class="<?= $tab==='analytics'?'active':'' ?>" href="?tab=analytics">📈 Analytics</a>
<a class="<?= $tab==='downloads'?'active':'' ?>" href="?tab=downloads">📥 Downloads</a>
<a class="<?= $tab==='ads'?'active':'' ?>" href="?tab=ads">📣 Ads Manager</a>
<a class="<?= $tab==='settings'?'active':'' ?>" href="?tab=settings">⚙️ Settings</a>
<a class="<?= $tab==='users'?'active':'' ?>" href="?tab=users">👥 Users & Credits</a>
<div class="av4-sep"></div>
<a class="<?= $tab==='database'?'active':'' ?>" href="?tab=database">🗄️ DB Explorer</a>
<a href="/analytics/">🌐 Full Analytics</a>
<a href="/analytics/advanced.php">🚀 Advanced Analytics</a>
<a href="/analytics/resolve-geo.php">🌍 Geo Resolver</a>
<a href="/smart-toolz/">← SmartToolz</a>
</nav>
<div class="av4-foot"><strong><?=av4h($admin['name']??'Admin')?></strong><br><?=av4h($admin['email']??'')?><br><span style="color:#16a36a;font-weight:900">● ADMIN ONLINE</span></div>
</aside>
<main class="av4-main">
<div class="av4-top"><div class="av4-title"><h1><?=av4h(ucfirst($tab))?></h1><p>SmartToolz live control center</p></div><div class="av4-live"><span class="av4-dot"></span><span data-av4-live><?=number_format($live)?></span> live now</div></div>
<div class="av4-content"><?php require $tabFile; ?></div>
</main></div>
<script>(function(){async function live(){try{const r=await fetch('/analytics/live.php?t='+Date.now(),{credentials:'same-origin',cache:'no-store'});if(!r.ok)return;const d=await r.json();document.querySelectorAll('[data-av4-live]').forEach(e=>e.textContent=Number(d.count||0).toLocaleString());}catch(e){}}live();setInterval(live,1000)})();</script>
</body></html>

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
try {
    $q = $db->query("SELECT COUNT(*) FROM analytics_live WHERE last_seen >= DATE_SUB(NOW(), INTERVAL 2 MINUTE)");
    $live = (int)$q->fetchColumn();
} catch (Throwable) {}
$flash = (string)($_SESSION['admin_flash'] ?? '');
unset($_SESSION['admin_flash']);
$labels = [
    'dashboard' => ['Dashboard','Live site overview'],
    'analytics' => ['Analytics','Traffic, audience and acquisition intelligence'],
    'downloads' => ['Downloads','Download activity and conversion details'],
    'ads' => ['Ads Manager','Placements, status and ad inventory'],
    'settings' => ['Settings','Application configuration'],
    'users' => ['Users & Credits','Accounts, roles and balances'],
    'database' => ['DB Explorer','Database schema and table browser'],
];
[$pageTitle,$pageSubtitle] = $labels[$tab];
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?=av4h($pageTitle)?> · SmartToolz Admin</title>
<link rel="stylesheet" href="/smart-toolz/admin/admin-tabs.css?v=20260901-v3">
<link rel="stylesheet" href="/smart-toolz/admin/admin-v5.css?v=20260901-v2">
</head>
<body class="av4">
<div class="av4-shell">
<aside class="av4-side">
  <div class="av4-brand">
    <div class="av4-mark">ST</div>
    <div>SmartToolz<span class="av4-sub">Admin Control Center</span></div>
  </div>
  <div class="av4-nav-label">CONTROL</div>
  <nav class="av4-nav">
    <a class="<?= $tab==='dashboard'?'active':'' ?>" href="?tab=dashboard">🏠<span>Dashboard</span></a>
    <a class="<?= $tab==='analytics'?'active':'' ?>" href="?tab=analytics">📈<span>Analytics</span></a>
    <a class="<?= $tab==='downloads'?'active':'' ?>" href="?tab=downloads">📥<span>Downloads</span></a>
    <a class="<?= $tab==='users'?'active':'' ?>" href="?tab=users">👥<span>Users & Credits</span></a>
    <a class="<?= $tab==='ads'?'active':'' ?>" href="?tab=ads">📣<span>Ads Manager</span></a>
    <a class="<?= $tab==='settings'?'active':'' ?>" href="?tab=settings">⚙️<span>Settings</span></a>
    <div class="av4-sep"></div>
    <div class="av4-nav-label">SYSTEM</div>
    <a class="<?= $tab==='database'?'active':'' ?>" href="?tab=database">🗄️<span>DB Explorer</span></a>
    <a href="/analytics/">🌐<span>Full Analytics</span></a>
    <a href="/analytics/advanced.php">🚀<span>Advanced Analytics</span></a>
    <a href="/analytics/live.php">🟢<span>Realtime API</span></a>
    <a href="/analytics/resolve-geo.php">🌍<span>Geo Resolver</span></a>
  </nav>
  <div class="av4-side-bottom">
    <a class="av4-site-link" href="/smart-toolz/">← Back to SmartToolz</a>
    <div class="av4-foot">
      <strong><?=av4h($admin['name']??'Admin')?></strong>
      <span><?=av4h($admin['email']??'')?></span>
      <em><i></i> Admin online</em>
    </div>
  </div>
</aside>
<main class="av4-main">
  <header class="av4-top">
    <div class="av4-title">
      <div class="av4-eyebrow">SMARTTOOLZ · ADMIN</div>
      <h1><?=av4h($pageTitle)?></h1>
      <p><?=av4h($pageSubtitle)?></p>
    </div>
    <div class="av4-top-actions">
      <a class="av4-top-btn" href="?tab=dashboard">⌂ Home</a>
      <div class="av4-live"><span class="av4-dot"></span><span data-av4-live><?=number_format($live)?></span> live now</div>
    </div>
  </header>
  <?php if($flash): ?><div class="av4-notice">✓ <?=av4h($flash)?></div><?php endif; ?>
  <div class="av4-content"><?php require $tabFile; ?></div>
</main>
</div>
<script src="/smart-toolz/admin/admin.js?v=20260901-v2" defer></script>
<script>
(function(){
  async function live(){
    try{
      const r=await fetch('/analytics/live.php?t='+Date.now(),{credentials:'same-origin',cache:'no-store'});
      if(!r.ok)return;
      const d=await r.json();
      document.querySelectorAll('[data-av4-live]').forEach(e=>e.textContent=Number(d.count||0).toLocaleString());
    }catch(e){}
  }
  live(); setInterval(live,1000);
})();
</script>
</body>
</html>

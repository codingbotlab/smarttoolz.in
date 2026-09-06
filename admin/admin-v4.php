<?php
declare(strict_types=1);
require_once __DIR__ . '/bootstrap.php';
$admin = requireAdmin();
$db = adminDb();
function av4h(mixed $v): string { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
$tab = (string)($_GET['tab'] ?? 'dashboard');
$allowed = ['dashboard','analytics','user-events','downloads','ads','settings','users','database','categories'];
if (!in_array($tab, $allowed, true)) $tab = 'dashboard';
$tabFile = __DIR__ . '/tabs/' . $tab . '.php';
if (!is_file($tabFile)) $tabFile = __DIR__ . '/tabs/dashboard.php';
$live = 0;
try { $q = $db->query("SELECT COUNT(*) FROM analytics_live WHERE last_seen >= DATE_SUB(NOW(), INTERVAL 2 MINUTE)"); $live = (int)$q->fetchColumn(); } catch (Throwable) {}
$flash = (string)($_SESSION['admin_flash'] ?? ''); unset($_SESSION['admin_flash']);
$labels = [
    'dashboard' => ['Dashboard','Live site overview'],
    'analytics' => ['Analytics','Traffic, audience and acquisition intelligence'],
    'user-events' => ['User Events','Lifetime visitor history and investigation'],
    'downloads' => ['Downloads','Download activity and conversion details'],
    'ads' => ['Ads Manager','Placements, status and ad inventory'],
    'settings' => ['Settings','Application configuration'],
    'users' => ['Users & Credits','Accounts, roles and balances'],
    'database' => ['DB Explorer','Database schema and table browser'],
    'categories' => ['Categories','Assign tools to public categories dynamically'],
];
[$pageTitle,$pageSubtitle] = $labels[$tab];
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?=av4h($pageTitle)?> · SmartToolz Admin</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:FILL,wght,GRAD,opsz@0,100..700,-25..200,20..48" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/brands.min.css">
<link rel="stylesheet" href="/smart-toolz/admin/admin-tabs.css?v=20260901-v3">
<link rel="stylesheet" href="/smart-toolz/admin/admin-v5.css?v=20260901-v2">
<style>.material-symbols-outlined{font-size:21px;line-height:1;vertical-align:-.2em;font-variation-settings:'FILL' 0,'wght' 500,'GRAD' 0,'opsz' 24}.av4-nav a .material-symbols-outlined{width:22px;text-align:center;margin-right:4px}.av4-top-btn .material-symbols-outlined{font-size:18px}.av4-notice .material-symbols-outlined{font-size:18px}</style>
</head>
<body class="av4">
<div class="av4-shell">
<aside class="av4-side">
  <div class="av4-brand"><div class="av4-mark">ST</div><div>SmartToolz<span class="av4-sub">Admin Control Center</span></div></div>
  <div class="av4-nav-label">CONTROL</div>
  <nav class="av4-nav">
    <a class="<?= $tab==='dashboard'?'active':'' ?>" href="?tab=dashboard"><span class="material-symbols-outlined">dashboard</span><span>Dashboard</span></a>
    <a class="<?= $tab==='analytics'?'active':'' ?>" href="?tab=analytics"><span class="material-symbols-outlined">analytics</span><span>Analytics</span></a>
    <a class="<?= $tab==='user-events'?'active':'' ?>" href="?tab=user-events"><span class="material-symbols-outlined">manage_search</span><span>User Events</span></a>
    <a class="<?= $tab==='downloads'?'active':'' ?>" href="?tab=downloads"><span class="material-symbols-outlined">download</span><span>Downloads</span></a>
    <a class="<?= $tab==='users'?'active':'' ?>" href="?tab=users"><span class="material-symbols-outlined">group</span><span>Users & Credits</span></a>
    <a class="<?= $tab==='ads'?'active':'' ?>" href="?tab=ads"><span class="material-symbols-outlined">campaign</span><span>Ads Manager</span></a>
    <a class="<?= $tab==='settings'?'active':'' ?>" href="?tab=settings"><span class="material-symbols-outlined">settings</span><span>Settings</span></a>
    <a class="<?= $tab==='categories'?'active':'' ?>" href="?tab=categories"><span class="material-symbols-outlined">category</span><span>Categories</span></a>
    <div class="av4-sep"></div><div class="av4-nav-label">SYSTEM</div>
    <a class="<?= $tab==='database'?'active':'' ?>" href="?tab=database"><span class="material-symbols-outlined">database</span><span>DB Explorer</span></a>
    <a href="/analytics/"><span class="material-symbols-outlined">language</span><span>Full Analytics</span></a>
    <a href="/analytics/advanced.php"><span class="material-symbols-outlined">rocket_launch</span><span>Advanced Analytics</span></a>
    <a href="/analytics/live.php"><span class="material-symbols-outlined">radio_button_checked</span><span>Realtime API</span></a>
    <a href="/analytics/resolve-geo.php"><span class="material-symbols-outlined">public</span><span>Geo Resolver</span></a>
  </nav>
  <div class="av4-side-bottom"><a class="av4-site-link" href="/smart-toolz/"><span class="material-symbols-outlined">arrow_back</span> Back to SmartToolz</a><div class="av4-foot"><strong><?=av4h($admin['name']??'Admin')?></strong><span><?=av4h($admin['email']??'')?></span><em><i></i> Admin online</em></div></div>
</aside>
<main class="av4-main">
  <header class="av4-top"><div class="av4-title"><div class="av4-eyebrow">SMARTTOOLZ · ADMIN</div><h1><?=av4h($pageTitle)?></h1><p><?=av4h($pageSubtitle)?></p></div><div class="av4-top-actions"><a class="av4-top-btn" href="?tab=dashboard"><span class="material-symbols-outlined">home</span> Home</a><div class="av4-live"><span class="av4-dot"></span><span data-av4-live><?=number_format($live)?></span> live now</div></div></header>
  <?php if($flash): ?><div class="av4-notice"><span class="material-symbols-outlined">check_circle</span> <?=av4h($flash)?></div><?php endif; ?>
  <div class="av4-content"><?php require $tabFile; ?></div>
</main>
</div>
<script src="/smart-toolz/admin/admin.js?v=20260901-v2" defer></script>
<script>(function(){async function live(){try{const r=await fetch('/analytics/live.php?t='+Date.now(),{credentials:'same-origin',cache:'no-store'});if(!r.ok)return;const d=await r.json();document.querySelectorAll('[data-av4-live]').forEach(e=>e.textContent=Number(d.count||0).toLocaleString())}catch(e){}}live();setInterval(live,1000)})();</script>
</body></html>
<?php
declare(strict_types=1);
require_once __DIR__ . '/lib/settings.php';
$siteName = smarttoolz_setting_string('site_name', 'SmartToolz');
$siteTagline = smarttoolz_setting_string('site_tagline', 'Free, fast and simple online tools.');
$script = basename((string)($_SERVER['SCRIPT_NAME'] ?? ''));
$isHome = in_array($script, ['index.php','home.php'], true);
$isAllTools = $script === 'tool.php';
$isToolPage = !$isHome && !$isAllTools && str_contains((string)($_SERVER['SCRIPT_NAME'] ?? ''), '/tools/');
$slug = basename($script, '.php');
$toolTitle = ucwords(str_replace(['-','_'], ' ', strtolower($slug ?: 'Online Tool')));
$toolReportUrl = '/report-tool.php?tool=' . rawurlencode($slug) . '&name=' . rawurlencode($toolTitle) . '&url=' . rawurlencode('/tools/' . $slug . '.php');
?>
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,500,0,0&display=swap" rel="stylesheet"><link rel="stylesheet" href="/assets/css/smarttoolz.css">
<header class="site-header"><nav class="navbar" aria-label="Primary navigation">
<a class="logo" href="/" aria-label="SmartToolz home"><span class="logo-icon"><span class="material-symbols-rounded">build</span></span><span><?=htmlspecialchars($siteName,ENT_QUOTES,'UTF-8')?></span></a>
<div class="nav-links"><a href="/" <?=$isHome?'aria-current="page"':''?>><span class="material-symbols-rounded">home</span> Home</a><a href="/tool.php" <?=$isAllTools?'aria-current="page"':''?>><span class="material-symbols-rounded">apps</span> All Tools</a><a href="/tool.php#categories"><span class="material-symbols-rounded">category</span> Categories</a></div>
</nav></header>
<?php if ($isToolPage): ?>
<section class="tool-seo-intro"><div><span class="tool-free-badge">FREE ONLINE TOOL</span><h2>Free Online <?=htmlspecialchars($toolTitle,ENT_QUOTES,'UTF-8')?></h2><p><?=htmlspecialchars($siteName,ENT_QUOTES,'UTF-8')?> <?=htmlspecialchars($toolTitle,ENT_QUOTES,'UTF-8')?> helps you complete this task quickly and easily. <?=htmlspecialchars($siteTagline,ENT_QUOTES,'UTF-8')?></p></div><div class="how-use"><h3><span class="material-symbols-rounded">menu_book</span> How to Use</h3><ol><li>Enter, upload or select your data.</li><li>Choose your options and click the main action button.</li><li>Review the result and download or copy it.</li></ol></div></section>
<div class="tool-support-actions"><a class="tool-support-card report" href="<?=htmlspecialchars($toolReportUrl,ENT_QUOTES,'UTF-8')?>"><span class="support-icon"><span class="material-symbols-rounded">bug_report</span></span><span><strong>Report a Problem</strong><small>Tool not working, wrong result, error or upload issue</small></span><span class="material-symbols-rounded arrow">arrow_forward</span></a><a class="tool-support-card request" href="/request-tool.php"><span class="support-icon"><span class="material-symbols-rounded">lightbulb</span></span><span><strong>Request a New Tool</strong><small>Tell us what you want SmartToolz to build next</small></span><span class="material-symbols-rounded arrow">arrow_forward</span></a></div>
<?php endif; ?>

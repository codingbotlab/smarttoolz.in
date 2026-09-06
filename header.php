<?php
declare(strict_types=1);
$siteName='SmartToolz';
$siteTagline='Free, fast and simple online tools.';
$path=(string)($_SERVER['SCRIPT_NAME']??'');
$script=basename($path);
$isHome=in_array($script,['index.php','home.php'],true)&&!str_contains($path,'/tools/');
$isAllTools=$script==='tool.php';
$isToolPage=str_contains($path,'/tools/');
$dir=basename(dirname($path));
$toolTitle=$dir&&$dir!=='tools'?ucwords(str_replace(['-','_'],' ',strtolower($dir))):'Online Tool';
$cssFile=__DIR__.'/assets/css/smarttoolz.css';
?>
<?php if (is_file($cssFile)): ?><style id="smarttoolz-css"><?php readfile($cssFile); ?></style><?php endif; ?>
<style id="smarttoolz-tool-layout">
.tool-seo-intro{width:min(1240px,calc(100% - 32px));margin:0 auto;padding:44px 0 28px;display:grid;grid-template-columns:minmax(0,1fr) minmax(320px,420px);gap:40px;align-items:start}.tool-free-badge{display:inline-flex;align-items:center;padding:7px 11px;border:1px solid #dedbff;border-radius:999px;background:#eeedff;color:var(--brand,#635bff);font-size:10px;font-weight:900;letter-spacing:1px}.tool-seo-intro h2{margin:14px 0 9px;font-size:clamp(28px,4vw,40px);line-height:1.12;letter-spacing:-1.5px}.tool-seo-intro>div>p{max-width:720px;margin:0;color:var(--muted,#667085);font-size:14px;line-height:1.7}.how-use{padding:20px 22px;background:#fff;border:1px solid var(--line,#e6e8ef);border-radius:16px;box-shadow:0 12px 35px rgba(16,24,40,.05)}.how-use h3{margin:0 0 10px;font-size:14px}.how-use ol{margin:0;padding-left:20px;color:var(--muted,#667085);font-size:12px;line-height:1.8}.how-use li+li{margin-top:3px}@media(max-width:800px){.tool-seo-intro{grid-template-columns:1fr;gap:18px;padding:32px 0 20px}.how-use{padding:18px}}@media(max-width:560px){.tool-seo-intro{width:calc(100% - 24px);padding:26px 0 16px}.tool-seo-intro h2{font-size:28px;letter-spacing:-1px}}
</style>
<header class="site-header"><nav class="navbar" aria-label="Primary navigation"><a class="logo" href="/" aria-label="SmartToolz home"><span class="logo-icon"><span aria-hidden="true">✦</span></span><span><?=htmlspecialchars($siteName,ENT_QUOTES,'UTF-8')?></span></a><div class="nav-links"><a href="/" <?=$isHome?'aria-current="page"':''?>>Home</a><a href="/tool.php" <?=$isAllTools?'aria-current="page"':''?>>All Tools</a><a href="/tool.php#categories">Categories</a></div></nav></header>
<?php if($isToolPage): ?><section class="tool-seo-intro"><div><span class="tool-free-badge">FREE ONLINE TOOL</span><h2>Free Online <?=htmlspecialchars($toolTitle,ENT_QUOTES,'UTF-8')?></h2><p><?=htmlspecialchars($siteName,ENT_QUOTES,'UTF-8')?> <?=htmlspecialchars($toolTitle,ENT_QUOTES,'UTF-8')?> helps you complete this task quickly and easily. <?=htmlspecialchars($siteTagline,ENT_QUOTES,'UTF-8')?></p></div><div class="how-use"><h3>How to Use</h3><ol><li>Enter, upload or select your data.</li><li>Choose your options and click the main action button.</li><li>Review the result and download or copy it.</li></ol></div></section><?php endif; ?>

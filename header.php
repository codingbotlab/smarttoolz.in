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
<header class="site-header"><nav class="navbar" aria-label="Primary navigation"><a class="logo" href="/" aria-label="SmartToolz home"><span class="logo-icon"><span aria-hidden="true">✦</span></span><span><?=htmlspecialchars($siteName,ENT_QUOTES,'UTF-8')?></span></a><div class="nav-links"><a href="/" <?=$isHome?'aria-current="page"':''?>>Home</a><a href="/tool.php" <?=$isAllTools?'aria-current="page"':''?>>All Tools</a><a href="/tool.php#categories">Categories</a></div></nav></header>
<?php if($isToolPage): ?><section class="tool-seo-intro"><div><span class="tool-free-badge">FREE ONLINE TOOL</span><h2>Free Online <?=htmlspecialchars($toolTitle,ENT_QUOTES,'UTF-8')?></h2><p><?=htmlspecialchars($siteName,ENT_QUOTES,'UTF-8')?> <?=htmlspecialchars($toolTitle,ENT_QUOTES,'UTF-8')?> helps you complete this task quickly and easily. <?=htmlspecialchars($siteTagline,ENT_QUOTES,'UTF-8')?></p></div><div class="how-use"><h3>How to Use</h3><ol><li>Enter, upload or select your data.</li><li>Choose your options and click the main action button.</li><li>Review the result and download or copy it.</li></ol></div></section><?php endif; ?>

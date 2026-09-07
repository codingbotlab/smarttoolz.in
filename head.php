<?php
declare(strict_types=1);
require_once __DIR__ . '/bootstrap.php';
$assetVersion = '2026090703';
$isHowToPage = str_starts_with(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/', '/how-to');
?>
<script async src="https://pagead2.googlesyndication.com/adsbygoogle.js?client=ca-pub-9562924671441246"></script>
<script async src="https://www.googletagmanager.com/gtag/js?id=G-WBXX9J5G3H"></script>
<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments)}gtag('js',new Date());gtag('config','G-WBXX9J5G3H');</script>
<link rel="preconnect" href="https://cdn.jsdelivr.net">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
<link rel="stylesheet" href="/assets/css/smarttoolz.css?v=<?= $assetVersion ?>">
<link rel="stylesheet" href="/assets/css/site-polish.css?v=<?= $assetVersion ?>">
<link rel="stylesheet" href="/assets/css/tool-pages.css?v=<?= $assetVersion ?>">
<link rel="stylesheet" href="/assets/css/tools.css?v=<?= $assetVersion ?>">
<?php if (!$isHowToPage): ?>
<style>
.site-header{width:100%!important}
.site-header .header-inner{width:min(1450px,calc(100% - 64px))!important;min-height:76px!important;margin-left:auto!important;margin-right:auto!important;display:flex!important;align-items:center!important}
.site-header .header-search{width:286px!important;height:42px!important;display:flex!important;align-items:center!important}
.site-header .header-search svg{width:18px!important;height:18px!important;max-width:18px!important;max-height:18px!important;display:block!important;flex:0 0 18px!important}
.site-header .theme-toggle,.site-header .mobile-menu{width:42px!important;height:42px!important}
.site-header .theme-toggle svg,.site-header .mobile-menu svg{width:20px!important;height:20px!important;max-width:20px!important;max-height:20px!important;display:block!important}
@media(max-width:1180px){.site-header .header-inner{width:calc(100% - 64px)!important}.site-header .header-search{width:220px!important}}
@media(max-width:820px){.site-header .main-nav,.site-header .header-search{display:none!important}.site-header .header-inner{width:calc(100% - 32px)!important}.site-header .mobile-menu{display:grid!important}}
@media(max-width:520px){.site-header .header-inner{width:calc(100% - 20px)!important}}
</style>
<?php endif; ?>
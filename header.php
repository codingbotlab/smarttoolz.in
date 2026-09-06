<?php
declare(strict_types=1);

if (defined('SMARTTOOLZ_HEADER_LOADED')) {
    return;
}
define('SMARTTOOLZ_HEADER_LOADED', true);

$siteName = 'SmartToolz';
$siteTagline = 'Free, fast and simple online tools.';
$requestPath = (string)(parse_url((string)($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH) ?? '/');
$script = basename((string)($_SERVER['SCRIPT_NAME'] ?? ''));
$normalizedPath = '/' . trim($requestPath, '/');

$isHome = ($normalizedPath === '/' || in_array($script, ['index.php', 'home.php'], true)) && strpos($requestPath, '/tools/') === false;
$isToolPage = strpos($requestPath, '/tools/') !== false;
$isCategoryPage = strpos($requestPath, '/category/') === 0;
$isAllTools = $script === 'tool.php' && !$isCategoryPage;

$toolSlug = $isToolPage ? trim(basename(rtrim($requestPath, '/'))) : '';
$toolTitle = $toolSlug !== ''
    ? ucwords(str_replace(['-', '_'], ' ', strtolower($toolSlug)))
    : 'Online Tool';

$categorySlug = $isCategoryPage ? trim(basename(rtrim($requestPath, '/'))) : '';
$categoryTitle = $categorySlug !== ''
    ? ucwords(str_replace(['-', '_'], ' ', strtolower($categorySlug)))
    : 'All Tools';

$canonicalPath = $requestPath !== '' ? $requestPath : '/';
if ($canonicalPath !== '/' && ($isToolPage || $isCategoryPage) && substr($canonicalPath, -1) !== '/') {
    $canonicalPath .= '/';
}
$canonicalPath = '/' . ltrim($canonicalPath, '/');
$canonicalUrl = 'https://smarttoolz.in' . ($canonicalPath === '' ? '/' : $canonicalPath);

if ($isToolPage) {
    $seoTitle = 'Free Online ' . $toolTitle . ' | SmartToolz';
    $seoDescription = 'Use SmartToolz ' . $toolTitle . ' online for free. Fast, simple and easy to use in your browser with no signup required.';
} elseif ($isCategoryPage) {
    $seoTitle = $categoryTitle . ' — Free Online Tools | SmartToolz';
    $seoDescription = 'Browse free ' . $categoryTitle . ' on SmartToolz. Simple online tools that work in your browser without signup.';
} elseif ($isAllTools) {
    $seoTitle = 'All Free Online Tools | SmartToolz';
    $seoDescription = 'Browse free SmartToolz online tools for images, PDFs, text, developers, calculators and everyday tasks.';
} elseif ($isHome) {
    $seoTitle = 'SmartToolz — Free Online Tools for Images, PDF, Text & More';
    $seoDescription = 'SmartToolz provides free, fast and simple online tools for images, PDFs, text, developers and everyday tasks.';
} else {
    $pageName = ucwords(str_replace(['-', '_', '.'], ' ', pathinfo($script, PATHINFO_FILENAME)));
    $seoTitle = ($pageName !== '' ? $pageName : 'SmartToolz') . ' | SmartToolz';
    $seoDescription = 'SmartToolz provides free, fast and simple online tools for everyday tasks.';
}

$escape = static function (string $value): string {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
};

$seoHead = '';
$seoHead .= '<meta name="robots" content="index,follow,max-image-preview:large">' . "\n";
$seoHead .= '<meta name="description" content="' . $escape($seoDescription) . '">' . "\n";
$seoHead .= '<link rel="canonical" href="' . $escape($canonicalUrl) . '">' . "\n";
$seoHead .= '<meta property="og:type" content="website">' . "\n";
$seoHead .= '<meta property="og:site_name" content="SmartToolz">' . "\n";
$seoHead .= '<meta property="og:title" content="' . $escape($seoTitle) . '">' . "\n";
$seoHead .= '<meta property="og:description" content="' . $escape($seoDescription) . '">' . "\n";
$seoHead .= '<meta property="og:url" content="' . $escape($canonicalUrl) . '">' . "\n";
$seoHead .= '<meta name="twitter:card" content="summary">' . "\n";
$seoHead .= '<meta name="twitter:title" content="' . $escape($seoTitle) . '">' . "\n";
$seoHead .= '<meta name="twitter:description" content="' . $escape($seoDescription) . '">' . "\n";
$seoHead .= '<meta name="theme-color" content="#635bff">' . "\n";
$seoHead .= '<link rel="stylesheet" href="/assets/css/smarttoolz.css">' . "\n";

if ($isToolPage) {
    $schema = json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'WebApplication',
        'name' => $toolTitle . ' - SmartToolz',
        'url' => $canonicalUrl,
        'description' => $seoDescription,
        'applicationCategory' => 'UtilitiesApplication',
        'operatingSystem' => 'Any',
        'offers' => [
            '@type' => 'Offer',
            'price' => '0',
            'priceCurrency' => 'USD'
        ]
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

    if (is_string($schema)) {
        $seoHead .= '<script type="application/ld+json">' . $schema . '</script>' . "\n";
    }
}

$headerHtml = '<header class="site-header"><div class="site-header-inner"><a class="logo" href="/" aria-label="SmartToolz home"><span class="logo-icon" aria-hidden="true">✦</span><span>' . $escape($siteName) . '</span></a><nav class="nav-links" aria-label="Primary navigation"><a href="/"' . ($isHome ? ' aria-current="page"' : '') . '>Home</a><a href="/tool.php"' . ($isAllTools ? ' aria-current="page"' : '') . '>All Tools</a><a href="/tool.php#categories">Categories</a></nav></div></header>';

$toolIntroHtml = '';
if ($isToolPage) {
    $toolIntroHtml = '<section class="tool-seo-intro" aria-labelledby="tool-seo-title"><div class="tool-seo-inner"><div class="tool-seo-copy"><span class="tool-free-badge">FREE ONLINE TOOL</span><h2 id="tool-seo-title">Free Online ' . $escape($toolTitle) . '</h2><p>' . $escape($siteName . ' ' . $toolTitle . ' helps you complete this task quickly and easily. ' . $siteTagline) . '</p></div><div class="how-use"><h3>How to Use</h3><ol><li>Enter, upload or select your data.</li><li>Choose your options and click the main action button.</li><li>Review the result and download or copy it.</li></ol></div></div></section>';
}

$headerCss = <<<'CSS'
<style id="smarttoolz-header-css">
html,body{width:100%;max-width:100%;margin:0;padding:0;overflow-x:hidden}
.site-header{width:100%;max-width:none;margin:0;padding:0;background:#fff;border-bottom:1px solid var(--line,#e6e8ef)}
.site-header-inner{width:100%;max-width:none;box-sizing:border-box;min-height:68px;margin:0;padding:0 32px;display:flex;align-items:center;justify-content:space-between;gap:24px}
.logo{display:inline-flex;align-items:center;gap:10px;color:#111827;text-decoration:none;font-size:18px;font-weight:900;letter-spacing:-.5px;white-space:nowrap}
.logo-icon{width:34px;height:34px;display:grid;place-items:center;border-radius:10px;background:#635bff;color:#fff;font-size:18px;line-height:1}
.nav-links{display:flex;align-items:center;gap:24px}
.nav-links a{color:#475467;text-decoration:none;font-size:13px;font-weight:700}
.nav-links a:hover,.nav-links a[aria-current="page"]{color:#635bff}
.tool-seo-intro{width:100%;max-width:none;box-sizing:border-box;margin:0;padding:44px 32px 28px;background:#fff}
.tool-seo-inner{width:100%;max-width:1400px;margin:0 auto;display:grid;grid-template-columns:minmax(0,1fr) minmax(320px,420px);gap:40px;align-items:start}
.tool-free-badge{display:inline-flex;align-items:center;padding:7px 11px;border:1px solid #dedbff;border-radius:999px;background:#eeedff;color:#635bff;font-size:10px;font-weight:900;letter-spacing:1px}
.tool-seo-copy h2{margin:14px 0 9px;font-size:clamp(28px,4vw,40px);line-height:1.12;letter-spacing:-1.5px;color:#111827}
.tool-seo-copy p{max-width:760px;margin:0;color:#667085;font-size:14px;line-height:1.7}
.how-use{padding:20px 22px;background:#fff;border:1px solid #e6e8ef;border-radius:16px;box-shadow:0 12px 35px rgba(16,24,40,.05)}
.how-use h3{margin:0 0 10px;color:#111827;font-size:14px}
.how-use ol{margin:0;padding-left:20px;color:#667085;font-size:12px;line-height:1.8}
.how-use li+li{margin-top:3px}
@media(max-width:800px){.site-header-inner{padding:0 20px}.nav-links{gap:14px}.tool-seo-intro{padding:32px 20px 20px}.tool-seo-inner{grid-template-columns:1fr;gap:18px}}
@media(max-width:560px){.site-header-inner{min-height:60px;padding:0 14px}.logo{font-size:16px}.logo-icon{width:30px;height:30px}.nav-links{gap:10px}.nav-links a{font-size:11px}.tool-seo-intro{padding:26px 14px 16px}.tool-seo-copy h2{font-size:28px;letter-spacing:-1px}}
</style>
CSS;

ob_start(static function (string $html) use ($seoHead, $seoTitle, $headerHtml, $toolIntroHtml, $headerCss): string {
    if (stripos($html, '<head') !== false) {
        $html = preg_replace('~<meta[^>]+name=["\']description["\'][^>]*>~i', '', $html) ?? $html;
        $html = preg_replace('~<meta[^>]+name=["\']robots["\'][^>]*>~i', '', $html) ?? $html;
        $html = preg_replace('~<link[^>]+rel=["\']canonical["\'][^>]*>~i', '', $html) ?? $html;
        $html = preg_replace('~<meta[^>]+property=["\']og:[^"\']+["\'][^>]*>~i', '', $html) ?? $html;
        $html = preg_replace('~<meta[^>]+name=["\']twitter:[^"\']+["\'][^>]*>~i', '', $html) ?? $html;
        if (stripos($html, '</title>') !== false) {
            $html = preg_replace('~<title>.*?</title>~is', '<title>' . htmlspecialchars($seoTitle, ENT_QUOTES, 'UTF-8') . '</title>', $html, 1) ?? $html;
        }
        $html = preg_replace('~</head>~i', $seoHead . $headerCss . '</head>', $html, 1) ?? $html;
    }

    if (stripos($html, '<body') !== false) {
        $html = preg_replace('~(<body[^>]*>)~i', '$1' . $headerHtml . $toolIntroHtml, $html, 1) ?? $html;
    }

    return $html;
});

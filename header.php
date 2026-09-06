<?php
declare(strict_types=1);

$siteName = 'SmartToolz';
$siteTagline = 'Free, fast and simple online tools.';
$path = (string)($_SERVER['SCRIPT_NAME'] ?? '');
$requestPath = (string)(parse_url((string)($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH) ?? '/');
$script = basename($path);
$isLegacyHome = rtrim($requestPath, '/') === '/smart-toolz';
$isHome = in_array($script, ['index.php', 'home.php'], true) && strpos($path, '/tools/') === false;
$isToolPage = strpos($requestPath, '/tools/') !== false;
$isCategoryPage = strpos(rtrim($requestPath, '/'), '/category/') === 0;
$isAllTools = $script === 'tool.php' && !$isCategoryPage;
$dir = basename(dirname($path));
$toolTitle = $dir && $dir !== 'tools' ? ucwords(str_replace(['-', '_'], ' ', strtolower($dir))) : 'Online Tool';
$categorySlug = $isCategoryPage ? trim(basename(rtrim($requestPath, '/'))) : '';
$categoryTitle = $categorySlug ? ucwords(str_replace('-', ' ', $categorySlug)) : 'All Tools';
$cssFile = __DIR__ . '/assets/css/smarttoolz.css';

$canonicalPath = ($requestPath === '/smart-toolz/' || $requestPath === '/smart-toolz') ? '/' : $requestPath;
$canonicalPath = '/' . ltrim($canonicalPath, '/');
if ($canonicalPath !== '/' && ($isToolPage || $isCategoryPage) && substr($canonicalPath, -1) !== '/') {
    $canonicalPath .= '/';
}
$canonicalUrl = 'https://smarttoolz.in' . $canonicalPath;

$seoTitle = $isToolPage
    ? 'Free Online ' . $toolTitle . ' | SmartToolz'
    : ($isCategoryPage
        ? $categoryTitle . ' — Free Online Tools | SmartToolz'
        : ($isAllTools
            ? 'All Free Online Tools | SmartToolz'
            : (($isHome || $isLegacyHome)
                ? 'SmartToolz — Free Online Tools for Images, PDF, Text & More'
                : ucwords(str_replace(['-', '_', '.'], ' ', pathinfo($script, PATHINFO_FILENAME))) . ' | SmartToolz')));

$seoDescription = $isToolPage
    ? 'Use SmartToolz ' . $toolTitle . ' online for free. Fast, simple and easy to use in your browser with no signup required.'
    : ($isCategoryPage
        ? 'Browse free ' . $categoryTitle . ' on SmartToolz. Simple online tools that work in your browser without signup.'
        : ($isAllTools
            ? 'Browse free SmartToolz online tools for images, PDFs, text, developers, calculators and everyday tasks.'
            : 'SmartToolz provides free, fast and simple online tools for images, PDFs, text, developers and everyday tasks.'));

/* Keep SEO markup as real whitespace, not the literal characters \\n. */
$seoHead = "\n";
$seoHead .= '<meta name="robots" content="index,follow,max-image-preview:large">' . "\n";
$seoHead .= '<meta name="description" content="' . htmlspecialchars($seoDescription, ENT_QUOTES, 'UTF-8') . '">' . "\n";
$seoHead .= '<link rel="canonical" href="' . htmlspecialchars($canonicalUrl, ENT_QUOTES, 'UTF-8') . '">' . "\n";
$seoHead .= '<meta property="og:type" content="website">' . "\n";
$seoHead .= '<meta property="og:site_name" content="SmartToolz">' . "\n";
$seoHead .= '<meta property="og:title" content="' . htmlspecialchars($seoTitle, ENT_QUOTES, 'UTF-8') . '">' . "\n";
$seoHead .= '<meta property="og:description" content="' . htmlspecialchars($seoDescription, ENT_QUOTES, 'UTF-8') . '">' . "\n";
$seoHead .= '<meta property="og:url" content="' . htmlspecialchars($canonicalUrl, ENT_QUOTES, 'UTF-8') . '">' . "\n";
$seoHead .= '<meta name="twitter:card" content="summary">' . "\n";
$seoHead .= '<meta name="twitter:title" content="' . htmlspecialchars($seoTitle, ENT_QUOTES, 'UTF-8') . '">' . "\n";
$seoHead .= '<meta name="twitter:description" content="' . htmlspecialchars($seoDescription, ENT_QUOTES, 'UTF-8') . '">' . "\n";
$seoHead .= '<meta name="theme-color" content="#635bff">' . "\n";

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
    $seoHead .= '<script type="application/ld+json">' . $schema . '</script>' . "\n";
}

$headerHtml = '<header class="site-header"><nav class="navbar" aria-label="Primary navigation"><a class="logo" href="/" aria-label="SmartToolz home"><span class="logo-icon"><span aria-hidden="true">✦</span></span><span>' . htmlspecialchars($siteName, ENT_QUOTES, 'UTF-8') . '</span></a><div class="nav-links"><a href="/" ' . ($isHome || $isLegacyHome ? 'aria-current="page"' : '') . '>Home</a><a href="/tool.php" ' . ($isAllTools ? 'aria-current="page"' : '') . '>All Tools</a><a href="/tool.php#categories">Categories</a></div></nav></header>';

$toolIntroHtml = '';
if ($isToolPage) {
    $toolIntroHtml = '<section class="tool-seo-intro"><div><span class="tool-free-badge">FREE ONLINE TOOL</span><h2>Free Online ' . htmlspecialchars($toolTitle, ENT_QUOTES, 'UTF-8') . '</h2><p>' . htmlspecialchars($siteName, ENT_QUOTES, 'UTF-8') . ' ' . htmlspecialchars($toolTitle, ENT_QUOTES, 'UTF-8') . ' helps you complete this task quickly and easily. ' . htmlspecialchars($siteTagline, ENT_QUOTES, 'UTF-8') . '</p></div><div class="how-use"><h3>How to Use</h3><ol><li>Enter, upload or select your data.</li><li>Choose your options and click the main action button.</li><li>Review the result and download or copy it.</li></ol></div></section>';
}

$cssHtml = is_file($cssFile) ? '<style id="smarttoolz-css">' . file_get_contents($cssFile) . '</style>' : '';
$layoutCss = '<style id="smarttoolz-tool-layout">.tool-seo-intro{width:min(1240px,calc(100% - 32px));margin:0 auto;padding:44px 0 28px;display:grid;grid-template-columns:minmax(0,1fr) minmax(320px,420px);gap:40px;align-items:start}.tool-free-badge{display:inline-flex;align-items:center;padding:7px 11px;border:1px solid #dedbff;border-radius:999px;background:#eeedff;color:var(--brand,#635bff);font-size:10px;font-weight:900;letter-spacing:1px}.tool-seo-intro h2{margin:14px 0 9px;font-size:clamp(28px,4vw,40px);line-height:1.12;letter-spacing:-1.5px}.tool-seo-intro>div>p{max-width:720px;margin:0;color:var(--muted,#667085);font-size:14px;line-height:1.7}.how-use{padding:20px 22px;background:#fff;border:1px solid var(--line,#e6e8ef);border-radius:16px;box-shadow:0 12px 35px rgba(16,24,40,.05)}.how-use h3{margin:0 0 10px;font-size:14px}.how-use ol{margin:0;padding-left:20px;color:var(--muted,#667085);font-size:12px;line-height:1.8}.how-use li+li{margin-top:3px}@media(max-width:800px){.tool-seo-intro{grid-template-columns:1fr;gap:18px;padding:32px 0 20px}.how-use{padding:18px}}@media(max-width:560px){.tool-seo-intro{width:calc(100% - 24px);padding:26px 0 16px}.tool-seo-intro h2{font-size:28px;letter-spacing:-1px}}</style>';

ob_start(function (string $html) use ($seoHead, $seoTitle, $headerHtml, $toolIntroHtml, $cssHtml, $layoutCss): string {
    if (stripos($html, '<head') !== false) {
        $html = preg_replace('~<meta[^>]+name=["\']description["\'][^>]*>~i', '', $html) ?? $html;
        $html = preg_replace('~<meta[^>]+name=["\']robots["\'][^>]*>~i', '', $html) ?? $html;
        $html = preg_replace('~<link[^>]+rel=["\']canonical["\'][^>]*>~i', '', $html) ?? $html;
        $html = preg_replace('~<meta[^>]+property=["\']og:[^"\']+["\'][^>]*>~i', '', $html) ?? $html;
        $html = preg_replace('~<meta[^>]+name=["\']twitter:[^"\']+["\'][^>]*>~i', '', $html) ?? $html;
        if (stripos($html, '</title>') !== false && stripos($html, '<title>') !== false && strpos($html, '/tools/') !== false) {
            $html = preg_replace('~<title>.*?</title>~is', '<title>' . htmlspecialchars($seoTitle, ENT_QUOTES, 'UTF-8') . '</title>', $html, 1) ?? $html;
        }
        $html = preg_replace('~</head>~i', $seoHead . $cssHtml . $layoutCss . '</head>', $html, 1) ?? $html;
    }
    if (stripos($html, '<body') !== false) {
        $html = preg_replace('~(<body[^>]*>)~i', '$1' . $headerHtml . $toolIntroHtml, $html, 1) ?? $html;
    }
    return $html;
});

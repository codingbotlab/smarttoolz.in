<?php
declare(strict_types=1);

header('Content-Type: application/xml; charset=UTF-8');

$base = 'https://smarttoolz.in';
$entries = [];

// Keep only canonical, public, indexable pages in the XML sitemap.
$add = static function (string $url, ?int $mtime = null) use (&$entries): void {
    $entries[$url] = $mtime;
};

$coreOrder = [
    '/' => __DIR__ . '/index.php',
    '/tools/' => __DIR__ . '/tools/index.php',
    '/how-to/' => __DIR__ . '/how-to/list.php',
    '/blog/' => __DIR__ . '/blog/index.php',
    '/sitemap/' => __DIR__ . '/sitemap/index.php',
    '/about.php' => __DIR__ . '/about.php',
    '/contact.php' => __DIR__ . '/contact.php',
    '/privacy.php' => __DIR__ . '/privacy.php',
    '/terms.php' => __DIR__ . '/terms.php',
];
foreach ($coreOrder as $url => $file) {
    $add($url, is_file($file) ? (filemtime($file) ?: null) : null);
}

// Tools: include only real tool directories that contain their matching PHP file.
$toolsDir = __DIR__ . '/tools';
$toolSlugs = [];
if (is_dir($toolsDir)) {
    foreach (scandir($toolsDir) ?: [] as $dir) {
        if ($dir === '.' || $dir === '..') continue;
        if (!preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $dir)) continue;
        $path = $toolsDir . '/' . $dir;
        $toolFile = $path . '/' . $dir . '.php';
        if (!is_dir($path) || !is_file($toolFile)) continue;

        $toolSlugs[] = $dir;
        $add('/tools/' . $dir . '/', filemtime($toolFile) ?: null);
    }
}
sort($toolSlugs, SORT_STRING);

// Each real tool gets one matching How-To guide URL.
$guideFile = __DIR__ . '/how-to/index.php';
$guideMtime = is_file($guideFile) ? (filemtime($guideFile) ?: null) : null;
foreach ($toolSlugs as $slug) {
    $add('/how-to/' . $slug . '/', $guideMtime);
}

// Blogs: include actual article PHP files only; skip routing files.
$blogDir = __DIR__ . '/blog';
if (is_dir($blogDir)) {
    $blogSlugs = [];
    foreach (scandir($blogDir) ?: [] as $file) {
        if (!preg_match('/^([a-z0-9]+(?:-[a-z0-9]+)*)\.php$/', $file, $m)) continue;
        if (in_array($m[1], ['index', 'article'], true)) continue;
        $blogSlugs[] = [$m[1], $blogDir . '/' . $file];
    }
    usort($blogSlugs, static fn(array $a, array $b): int => strcmp($a[0], $b[0]));
    foreach ($blogSlugs as [$slug, $file]) {
        $add('/blog/' . $slug . '/', filemtime($file) ?: null);
    }
}

// Sitemap order: core pages, then tools, How-To guides and blog articles.
$groups = [
    static fn(string $url): bool => array_key_exists($url, $coreOrder),
    static fn(string $url): bool => str_starts_with($url, '/tools/'),
    static fn(string $url): bool => str_starts_with($url, '/how-to/'),
    static fn(string $url): bool => str_starts_with($url, '/blog/'),
];
$ordered = [];
foreach ($groups as $groupIndex => $matches) {
    $urls = array_keys(array_filter($entries, static fn($mtime, $url) => $matches($url), ARRAY_FILTER_USE_BOTH));
    if ($groupIndex === 0) {
        $urls = array_values(array_filter(array_keys($coreOrder), static fn(string $url): bool => array_key_exists($url, $entries)));
    } else {
        sort($urls, SORT_STRING);
    }
    foreach ($urls as $url) {
        $ordered[$url] = $entries[$url];
    }
}
$entries = $ordered;

echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
echo "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
foreach ($entries as $url => $mtime) {
    echo "  <url>\n";
    echo '    <loc>' . htmlspecialchars($base . $url, ENT_XML1, 'UTF-8') . "</loc>\n";
    if ($mtime) {
        echo '    <lastmod>' . gmdate('c', $mtime) . "</lastmod>\n";
    }
    echo "  </url>\n";
}
echo "</urlset>\n";

<?php
declare(strict_types=1);

header('Content-Type: application/xml; charset=UTF-8');

$base = 'https://smarttoolz.in';
$entries = [];

// Keep only canonical, public, indexable pages in the XML sitemap.
$add = static function (string $url, ?int $mtime = null) use (&$entries): void {
    $entries[$url] = $mtime;
};

$rootFiles = [
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
foreach ($rootFiles as $url => $file) {
    $add($url, is_file($file) ? filemtime($file) ?: null : null);
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
        $mtime = filemtime($toolFile) ?: null;
        $add('/tools/' . $dir . '/', $mtime);
    }
}
sort($toolSlugs, SORT_STRING);

// Each real tool gets one matching, indexable How-To guide URL.
foreach ($toolSlugs as $slug) {
    $guideFile = __DIR__ . '/how-to/index.php';
    $mtime = is_file($guideFile) ? (filemtime($guideFile) ?: null) : null;
    $add('/how-to/' . $slug . '/', $mtime);
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

// Stable ordering: core pages first, then tools, guides and articles alphabetically.
$coreOrder = ['/', '/tools/', '/how-to/', '/blog/', '/sitemap/', '/about.php', '/contact.php', '/privacy.php', '/terms.php'];
$ordered = [];
foreach ($coreOrder as $url) {
    if (array_key_exists($url, $entries)) {
        $ordered[$url] = $entries[$url];
        unset($entries[$url]);
    }
}
uksort($entries, static function (string $a, string $b): int {
    $group = static function (string $url): int {
        if (str_starts_with($url, '/tools/')) return 1;
        if (str_starts_with($url, '/how-to/')) return 2;
        if (str_starts_with($url, '/blog/')) return 3;
        return 4;
    };
    return ($group($a) <=> $group($b)) ?: strcmp($a, $b);
});
$entries += $ordered;

// Remove duplicate URLs while preserving the deliberate sitemap order.
$entries = array_unique($entries, SORT_REGULAR);

$formatLastmod = static function (?int $timestamp): ?string {
    if (!$timestamp) return null;
    return gmdate('c', $timestamp);
};

echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
echo "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
foreach ($entries as $url => $mtime) {
    echo "  <url>\n";
    echo '    <loc>' . htmlspecialchars($base . $url, ENT_XML1, 'UTF-8') . "</loc>\n";
    if ($lastmod = $formatLastmod($mtime)) {
        echo '    <lastmod>' . htmlspecialchars($lastmod, ENT_XML1, 'UTF-8') . "</lastmod>\n";
    }
    echo "  </url>\n";
}
echo "</urlset>\n";

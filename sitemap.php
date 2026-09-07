<?php
declare(strict_types=1);

header('Content-Type: application/xml; charset=UTF-8');

$base = 'https://smarttoolz.in';
$urls = [
    '/',
    '/tools/',
    '/blog/',
    '/sitemap/',
    '/about.php',
    '/contact.php',
    '/privacy.php',
    '/terms.php',
];

// Add every real tool URL automatically.
$toolsDir = __DIR__ . '/tools';
$toolSlugs = [];
if (is_dir($toolsDir)) {
    foreach (scandir($toolsDir) ?: [] as $dir) {
        if ($dir === '.' || $dir === '..') continue;
        $path = $toolsDir . '/' . $dir;
        $toolFile = $path . '/' . $dir . '.php';
        if (is_dir($path) && is_file($toolFile) && preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $dir)) {
            $toolSlugs[] = $dir;
            $urls[] = '/tools/' . $dir . '/';
        }
    }
}

// Add an indexable How To Use URL for every real tool automatically.
foreach ($toolSlugs as $slug) {
    $urls[] = '/how-to/' . $slug . '/';
}

// Add every real blog article automatically.
$blogDir = __DIR__ . '/blog';
if (is_dir($blogDir)) {
    foreach (scandir($blogDir) ?: [] as $file) {
        if (!preg_match('/^([a-z0-9]+(?:-[a-z0-9]+)*)\.php$/', $file, $m)) continue;
        if (in_array($m[1], ['index', 'article'], true)) continue;
        $urls[] = '/blog/' . $m[1] . '/';
    }
}

$urls = array_values(array_unique($urls));
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
echo "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
foreach ($urls as $url) {
    echo '  <url><loc>' . htmlspecialchars($base . $url, ENT_XML1, 'UTF-8') . "</loc></url>\n";
}
echo "</urlset>\n";

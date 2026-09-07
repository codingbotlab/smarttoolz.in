<?php
declare(strict_types=1);

header('Content-Type: application/xml; charset=UTF-8');

$base = 'https://smarttoolz.in';
$urls = [
    '/',
    '/tools/',
    '/about.php',
    '/contact.php',
    '/privacy.php',
    '/terms.php',
];

$toolsDir = __DIR__ . '/tools';
if (is_dir($toolsDir)) {
    foreach (scandir($toolsDir) ?: [] as $dir) {
        if ($dir === '.' || $dir === '..') continue;
        $path = $toolsDir . '/' . $dir;
        $toolFile = $path . '/' . $dir . '.php';
        // Only publish URLs that have a real matching tool entry point.
        if (is_dir($path) && is_file($toolFile) && preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $dir)) {
            $urls[] = '/tools/' . $dir . '/';
        }
    }
}

$urls = array_values(array_unique($urls));
echo '<?xml version="1.0" encoding="UTF-8"?>\n';
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">\n';
foreach ($urls as $url) {
    echo '  <url><loc>' . htmlspecialchars($base . $url, ENT_XML1, 'UTF-8') . '</loc></url>\n';
}
echo '</urlset>\n';

<?php
declare(strict_types=1);
require_once __DIR__ . '/data.php';
header('Content-Type: application/xml; charset=UTF-8');
$items=[]; try { $items=reddottBlogVideos(); } catch(Throwable $e) { $items=[]; }
echo '<?xml version="1.0" encoding="UTF-8"?>';
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
echo '<url><loc>https://smarttoolz.in/Reddott-films/blogs/</loc></url>';
foreach($items as $v){ if(strlen(trim((string)$v['description']))<240) continue; $loc='https://smarttoolz.in/Reddott-films/blogs/'.rawurlencode(reddottBlogSlug((string)$v['title'])).'/'.rawurlencode((string)$v['id']).'/'; echo '<url><loc>'.htmlspecialchars($loc,ENT_XML1,'UTF-8').'</loc><lastmod>'.htmlspecialchars(substr((string)$v['published'],0,10),ENT_XML1,'UTF-8').'</lastmod></url>'; }
echo '</urlset>';

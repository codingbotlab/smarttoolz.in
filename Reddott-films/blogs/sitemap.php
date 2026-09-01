<?php
declare(strict_types=1);
require_once __DIR__ . '/index.php';
// The blog index deliberately filters out records without enough source material.
// This sitemap is kept simple and follows the same quality gate.
header('Content-Type: application/xml; charset=UTF-8');
$items=[];
try { $items=videos(); } catch(Throwable $e) { $items=[]; }
function smSlug(string $title): string { $s=strtolower(trim($title)); $s=preg_replace('/[^a-z0-9]+/','-',$s) ?? ''; return trim($s,'-') ?: 'video'; }
echo '<?xml version="1.0" encoding="UTF-8"?>';
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
echo '<url><loc>https://smarttoolz.in/Reddott-films/blogs/</loc></url>';
foreach($items as $v){ if(strlen(trim((string)$v['description']))<240) continue; $loc='https://smarttoolz.in/Reddott-films/blogs/'.rawurlencode(smSlug((string)$v['title'])).'/'.rawurlencode((string)$v['id']).'/'; echo '<url><loc>'.htmlspecialchars($loc,ENT_XML1,'UTF-8').'</loc><lastmod>'.htmlspecialchars(substr((string)$v['published'],0,10),ENT_XML1,'UTF-8').'</lastmod></url>'; }
echo '</urlset>';

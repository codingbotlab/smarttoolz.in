<?php
declare(strict_types=1);

function smarttoolz_blogs(): array
{
    $dir = dirname(__DIR__) . '/blog';
    if (!is_dir($dir)) return [];
    $posts = [];
    foreach (glob($dir . '/*.php') ?: [] as $file) {
        if (basename($file) === 'index.php') continue;
        $slug = basename($file, '.php');
        if (!preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $slug)) continue;
        $title = ucwords(str_replace('-', ' ', $slug));
        $posts[] = [
            'slug' => $slug,
            'title' => $title,
            'url' => '/blog/'.$slug.'/',
            'thumbnail' => '/blog/'.$slug.'.svg'
        ];
    }
    usort($posts, static fn($a,$b) => strcmp($b['slug'], $a['slug']));
    return $posts;
}

<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/bootstrap.php';

$slug = isset($_GET['slug']) ? trim((string) $_GET['slug']) : '';
if (!preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $slug)) {
    http_response_code(404);
    require dirname(__DIR__) . '/error.php?code=404';
    exit;
}

$file = __DIR__ . '/' . $slug . '.php';
if (!is_file($file) || basename($file) === 'article.php' || basename($file) === 'index.php') {
    http_response_code(404);
    require dirname(__DIR__) . '/error.php?code=404';
    exit;
}

require $file;

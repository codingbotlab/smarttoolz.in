<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

// Reuse the site's single source of truth from tool.php without rendering HTML.
define('SMARTTOOLZ_HOME_REGISTRY', true);
require __DIR__ . '/tool.php';

$publicTools = array_values(array_map(
    static fn(array $tool): array => [
        'name' => (string)($tool['name'] ?? ''),
        'url' => (string)($tool['url'] ?? ''),
        'category' => (string)($tool['category'] ?? ''),
    ],
    is_array($tools ?? null) ? $tools : []
));

echo json_encode(
    ['tools' => $publicTools, 'count' => count($publicTools)],
    JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
);

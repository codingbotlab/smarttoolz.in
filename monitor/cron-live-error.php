<?php
declare(strict_types=1);

// Compatibility entrypoint for Hostinger deployments rooted at public_html.
// The canonical crawler lives at smart-toolz/monitor/cron-live-error.php.
$target = dirname(__DIR__) . '/smart-toolz/monitor/cron-live-error.php';
if (!is_file($target)) {
    $report = [
        'generatedAt' => gmdate('c'),
        'baseUrl' => 'https://smarttoolz.in/smart-toolz/',
        'status' => 'error',
        'pagesChecked' => 0,
        'errorCount' => 1,
        'errors' => [[
            'type' => 'CRON_STARTUP',
            'url' => 'https://smarttoolz.in/smart-toolz/',
            'detail' => 'Canonical crawler not found: ' . $target
        ]],
        'checked' => []
    ];
    @file_put_contents(__DIR__ . '/latest-report.json', json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    echo json_encode($report, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . PHP_EOL;
    exit(1);
}
require $target;

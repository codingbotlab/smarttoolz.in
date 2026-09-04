<?php
declare(strict_types=1);

/**
 * Hostinger Cron entrypoint.
 *
 * This file is intentionally self-reporting: it always creates a public
 * latest-report.json, even if the main crawler cannot start.
 */

$publicDir = __DIR__;
$publicReport = $publicDir . '/latest-report.json';
$started = gmdate('c');
$rootScript = dirname(__DIR__, 2) . '/monitor/cron-live-error.php';

function write_public_report(string $file, array $report): void {
    @file_put_contents(
        $file,
        json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . PHP_EOL,
        LOCK_EX
    );
}

// Heartbeat is written BEFORE the crawler starts, so the dashboard never
// depends on Hostinger's Cron Output panel.
write_public_report($publicReport, [
    'generatedAt' => $started,
    'baseUrl' => 'https://smarttoolz.in/smart-toolz/',
    'status' => 'running',
    'pagesChecked' => 0,
    'errorCount' => 0,
    'errors' => [],
    'checked' => [],
    'cron' => ['entrypoint' => 'smart-toolz/monitor/cron-live-error.php']
]);

if (!is_file($rootScript)) {
    $message = "Crawler script not found: {$rootScript}";
    $report = [
        'generatedAt' => gmdate('c'),
        'baseUrl' => 'https://smarttoolz.in/smart-toolz/',
        'status' => 'error',
        'pagesChecked' => 0,
        'errorCount' => 1,
        'errors' => [[
            'type' => 'CRON_STARTUP',
            'url' => 'https://smarttoolz.in/smart-toolz/',
            'detail' => $message
        ]],
        'checked' => [],
        'cron' => ['entrypoint' => 'smart-toolz/monitor/cron-live-error.php']
    ];
    write_public_report($publicReport, $report);
    echo json_encode($report, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . PHP_EOL;
    exit(1);
}

// Capture both stdout and stderr from the real crawler. Hostinger's UI may
// show a blank output panel, so the captured diagnostics are also persisted.
$command = '/usr/bin/php ' . escapeshellarg($rootScript) . ' 2>&1';
$output = [];
$exitCode = 1;
exec($command, $output, $exitCode);
$combined = trim(implode(PHP_EOL, $output));

$rootReport = dirname(__DIR__, 2) . '/monitor/latest-report.json';
if (is_file($rootReport)) {
    $copied = @copy($rootReport, $publicReport);
    if (!$copied) {
        $existing = json_decode((string)@file_get_contents($rootReport), true);
        if (is_array($existing)) {
            $existing['cron']['wrapper'] = 'copy-failed';
            $existing['cron']['wrapperOutput'] = $combined;
            write_public_report($publicReport, $existing);
        }
    }
} else {
    // No root report means the crawler failed before publishing its report.
    $report = [
        'generatedAt' => gmdate('c'),
        'baseUrl' => 'https://smarttoolz.in/smart-toolz/',
        'status' => 'error',
        'pagesChecked' => 0,
        'errorCount' => 1,
        'errors' => [[
            'type' => 'CRAWLER_NO_REPORT',
            'url' => 'https://smarttoolz.in/smart-toolz/',
            'detail' => $combined !== '' ? $combined : 'Crawler exited without creating monitor/latest-report.json'
        ]],
        'checked' => [],
        'cron' => [
            'entrypoint' => 'smart-toolz/monitor/cron-live-error.php',
            'exitCode' => $exitCode,
            'output' => $combined
        ]
    ];
    write_public_report($publicReport, $report);
}

// Persist a small execution log independently of the crawler.
@file_put_contents(
    $publicDir . '/cron-wrapper.log',
    '[' . gmdate('c') . '] exit=' . $exitCode . ' output=' . $combined . PHP_EOL,
    FILE_APPEND | LOCK_EX
);

// Print captured output for Hostinger's View Output when supported.
echo ($combined !== '' ? $combined : 'SmartToolz crawler executed; see monitor/latest-report.json') . PHP_EOL;
exit((int)$exitCode);

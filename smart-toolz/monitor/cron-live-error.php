<?php
declare(strict_types=1);

/**
 * Hostinger Cron compatibility wrapper.
 *
 * Hostinger currently runs this path:
 * /public_html/smart-toolz/monitor/cron-live-error.php
 *
 * The main crawler lives in the repository root monitor/ directory.
 * Run it, then mirror its latest report into the public smart-toolz/monitor
 * directory used by the Live Error Monitor page.
 */

$rootScript = dirname(__DIR__, 2) . '/monitor/cron-live-error.php';
if (!is_file($rootScript)) {
    fwrite(STDERR, "Crawler script not found: {$rootScript}\n");
    exit(1);
}

$command = '/usr/bin/php ' . escapeshellarg($rootScript);
passthru($command, $exitCode);

$rootReport = dirname(__DIR__, 2) . '/monitor/latest-report.json';
$publicDir = __DIR__;
$publicReport = $publicDir . '/latest-report.json';

if (is_file($rootReport)) {
    if (!is_dir($publicDir)) {
        @mkdir($publicDir, 0755, true);
    }
    @copy($rootReport, $publicReport);
}

exit((int)$exitCode);

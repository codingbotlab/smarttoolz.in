<?php
declare(strict_types=1);

/**
 * SmartToolz live error crawler.
 * Hostinger Cron: every 5 minutes.
 *
 * No Discord webhook and no OpenAI API are used.
 * Writes monitor/latest-report.json and monitor/cron.log.
 */

$baseUrl = getenv('CRAWLER_BASE_URL') ?: 'https://smarttoolz.in/smart-toolz/';
$maxPages = max(1, (int)(getenv('CRAWLER_MAX_PAGES') ?: 40));
$timeout = max(1000, (int)(getenv('CRAWLER_TIMEOUT_MS') ?: 15000));
$reportFile = __DIR__ . '/latest-report.json';
$logFile = __DIR__ . '/cron.log';

function log_line(string $message): void {
    global $logFile;
    @file_put_contents($logFile, '[' . date('c') . '] ' . $message . PHP_EOL, FILE_APPEND | LOCK_EX);
}

function http_get(string $url, int $timeout): array {
    if (!function_exists('curl_init')) {
        throw new RuntimeException('PHP cURL extension is not enabled');
    }
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_MAXREDIRS => 5,
        CURLOPT_CONNECTTIMEOUT_MS => min(5000, $timeout),
        CURLOPT_TIMEOUT_MS => $timeout,
        CURLOPT_USERAGENT => 'SmartToolz-Hostinger-Cron/1.2',
        CURLOPT_HTTPHEADER => ['Accept: text/html,application/xhtml+xml,*/*;q=0.8'],
    ]);
    $body = curl_exec($ch);
    $errno = curl_errno($ch);
    $error = curl_error($ch);
    $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $type = (string) curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    curl_close($ch);
    if ($body === false || $errno) {
        throw new RuntimeException($error ?: "cURL error $errno");
    }
    return [$status, $type, (string) $body];
}

function add_error(array &$errors, string $type, string $url, string $detail, array $extra = []): void {
    $errors[] = array_merge([
        'type' => $type,
        'url' => $url,
        'detail' => $detail,
    ], $extra);
}

function absolute_url(string $baseUrl, string $raw): ?string {
    $raw = trim($raw);
    if ($raw === '' || str_starts_with($raw, '#') || str_starts_with($raw, 'mailto:') || str_starts_with($raw, 'javascript:') || str_starts_with($raw, 'tel:')) return null;
    $base = parse_url($baseUrl);
    if (!$base || empty($base['host'])) return null;
    $u = parse_url($raw);
    if (isset($u['scheme']) && !in_array(strtolower($u['scheme']), ['http', 'https'], true)) return null;
    if (isset($u['host'])) return $raw;
    $origin = ($base['scheme'] ?? 'https') . '://' . $base['host'];
    if (isset($base['port'])) $origin .= ':' . $base['port'];
    if (str_starts_with($raw, '//')) return ($base['scheme'] ?? 'https') . ':' . $raw;
    if (str_starts_with($raw, '/')) return $origin . $raw;
    $basePath = $base['path'] ?? '/';
    $dir = rtrim(str_replace('\\', '/', dirname($basePath)), '/');
    return $origin . ($dir ? $dir . '/' : '/') . ltrim($raw, '/');
}

function crawl(string $baseUrl, int $maxPages, int $timeout): array {
    $originHost = strtolower((string) parse_url($baseUrl, PHP_URL_HOST));
    $seen = [];
    $queue = [$baseUrl];
    $checked = [];
    $errors = [];

    while ($queue && count($checked) < $maxPages) {
        $url = array_shift($queue);
        $url = preg_replace('/#.*$/', '', (string) $url);
        if (!$url || isset($seen[$url])) continue;
        $seen[$url] = true;

        try {
            [$status, $type, $text] = http_get($url, $timeout);
            $checked[] = ['url' => $url, 'status' => $status, 'contentType' => $type];

            if ($status < 200 || $status >= 400) {
                add_error($errors, 'HTTP', $url, "HTTP $status");
            }

            $markers = [
                'fatal error', 'uncaught error', 'uncaught exception', 'parse error',
                'maximum execution time', 'allowed memory size', 'call to undefined function',
                'call to a member function', 'internal server error'
            ];
            $lower = strtolower($text);
            foreach ($markers as $marker) {
                $i = strpos($lower, $marker);
                if ($i !== false) {
                    $excerpt = preg_replace('/\s+/', ' ', substr($text, max(0, $i - 250), 900));
                    add_error($errors, 'PAGE_ERROR', $url, trim((string) $excerpt), ['marker' => $marker]);
                    break;
                }
            }

            if (stripos($type, 'text/html') !== false) {
                preg_match_all('/(?:href|src|action)=["\']([^"\']+)["\']/i', $text, $matches);
                foreach ($matches[1] ?? [] as $raw) {
                    $next = absolute_url($baseUrl, $raw);
                    if (!$next) continue;
                    $nextHost = strtolower((string) parse_url($next, PHP_URL_HOST));
                    if ($nextHost !== $originHost) continue;
                    $next = preg_replace('/#.*$/', '', $next);
                    if (!isset($seen[$next]) && count($queue) < $maxPages * 3) $queue[] = $next;
                }
            }
        } catch (Throwable $e) {
            $checked[] = ['url' => $url, 'status' => null, 'contentType' => ''];
            add_error($errors, 'REQUEST', $url, $e->getMessage());
        }
    }

    return [$checked, $errors];
}

function write_report(string $file, string $baseUrl, array $checked, array $errors): array {
    $report = [
        'generatedAt' => gmdate('c'),
        'baseUrl' => $baseUrl,
        'pagesChecked' => count($checked),
        'errorCount' => count($errors),
        'errors' => $errors,
        'checked' => $checked,
    ];
    @file_put_contents($file, json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), LOCK_EX);
    return $report;
}

log_line('START pid=' . getmypid() . ' php=' . PHP_VERSION . ' sapi=' . PHP_SAPI . ' cwd=' . getcwd());
register_shutdown_function(function (): void {
    global $logFile;
    $e = error_get_last();
    if ($e && in_array($e['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR], true)) {
        @file_put_contents($logFile, '[' . date('c') . '] FATAL type=' . $e['type'] . ' file=' . $e['file'] . ' line=' . $e['line'] . ' message=' . $e['message'] . PHP_EOL, FILE_APPEND | LOCK_EX);
    } else {
        log_line('END peak_memory=' . memory_get_peak_usage(true));
    }
});

try {
    [$checked, $errors] = crawl($baseUrl, $maxPages, $timeout);
    $report = write_report($reportFile, $baseUrl, $checked, $errors);
    log_line('CRAWL pages=' . count($checked) . ' errors=' . count($errors));

    echo json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . PHP_EOL;
    exit($report['errorCount'] ? 1 : 0);
} catch (Throwable $e) {
    log_line('UNCAUGHT ' . get_class($e) . ': ' . $e->getMessage() . ' @ ' . $e->getFile() . ':' . $e->getLine());
    $report = [
        'generatedAt' => gmdate('c'),
        'baseUrl' => $baseUrl,
        'pagesChecked' => 0,
        'errorCount' => 1,
        'errors' => [['type' => 'CRON_FATAL', 'url' => $baseUrl, 'detail' => $e->getMessage()]],
        'checked' => [],
    ];
    @file_put_contents($reportFile, json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), LOCK_EX);
    echo json_encode($report, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . PHP_EOL;
    exit(1);
}

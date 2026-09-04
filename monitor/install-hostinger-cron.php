<?php
/**
 * One-time Hostinger Cron installer for SmartToolz.
 *
 * This is intentionally CLI-only. It uses the Hostinger API to create the
 * monitoring cron and refuses to run from a public web request.
 *
 * Required environment variables:
 *   HOSTINGER_API_TOKEN
 *   HOSTINGER_ACCOUNT_USERNAME
 * Optional:
 *   HOSTINGER_CRON_TIME (default: */5 * * * *)
 *   SMARTTOOLZ_ROOT (default: /home/<username>/domains/smarttoolz.in/public_html/smart-toolz)
 */

declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("CLI only\n");
}

$token = getenv('HOSTINGER_API_TOKEN') ?: '';
$username = getenv('HOSTINGER_ACCOUNT_USERNAME') ?: '';
$time = getenv('HOSTINGER_CRON_TIME') ?: '*/5 * * * *';
$root = getenv('SMARTTOOLZ_ROOT') ?: ($username ? "/home/{$username}/domains/smarttoolz.in/public_html/smart-toolz" : '');

if ($token === '' || $username === '' || $root === '') {
    fwrite(STDERR, "Missing HOSTINGER_API_TOKEN, HOSTINGER_ACCOUNT_USERNAME or SMARTTOOLZ_ROOT.\n");
    exit(2);
}

$command = "/usr/bin/php {$root}/monitor/cron-live-error.php";
$api = "https://api.hostinger.com/api/hosting/v1/accounts/" . rawurlencode($username) . "/cron-jobs";

function request_json(string $method, string $url, string $token, ?array $payload = null): array {
    $ch = curl_init($url);
    $headers = [
        'Accept: application/json',
        'Content-Type: application/json',
        'Authorization: Bearer ' . $token,
    ];
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_TIMEOUT => 30,
    ]);
    if ($payload !== null) curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload, JSON_UNESCAPED_SLASHES));
    $body = curl_exec($ch);
    $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    if ($body === false) throw new RuntimeException('Hostinger API cURL: ' . $error);
    $json = json_decode((string)$body, true);
    if ($status >= 300) throw new RuntimeException('Hostinger API HTTP ' . $status . ': ' . substr((string)$body, 0, 1000));
    return [$status, is_array($json) ? $json : []];
}

[, $jobs] = request_json('GET', $api, $token);
$jobs = isset($jobs['data']) && is_array($jobs['data']) ? $jobs['data'] : $jobs;

foreach ($jobs as $job) {
    if (!is_array($job)) continue;
    if (($job['command'] ?? '') === $command && ($job['time'] ?? '') === $time) {
        echo "Already installed: " . ($job['uid'] ?? 'unknown') . "\n";
        exit(0);
    }
}

[, $created] = request_json('POST', $api, $token, [
    'time' => $time,
    'command' => $command,
]);

echo "Created SmartToolz Hostinger Cron: " . ($created['uid'] ?? 'unknown') . "\n";
echo "Schedule: {$time}\n";
echo "Command: {$command}\n";

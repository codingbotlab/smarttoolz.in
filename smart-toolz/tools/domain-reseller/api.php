<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

$action = $_GET['action'] ?? '';
$domain = trim(strtolower($_GET['domain'] ?? ''));

function json_error(string $message, int $status = 400): never
{
    http_response_code($status);
    echo json_encode(['error' => $message], JSON_UNESCAPED_SLASHES);
    exit;
}

if ($action !== 'check') {
    json_error('Unknown action', 404);
}

if ($domain === '' || !preg_match('/^(?=.{1,253}$)([a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?\.)+[a-z]{2,63}$/', $domain)) {
    json_error('Invalid domain');
}

// Keep credentials on the server. Never put them in GitHub or browser JavaScript.
$resellerId = getenv('DOMAINNAMEAPI_RESELLER_ID');
$apiKey     = getenv('DOMAINNAMEAPI_API_KEY');
$baseUrl    = getenv('DOMAINNAMEAPI_BASE_URL') ?: 'https://ote.domainresellerapi.com';

if (!$resellerId || !$apiKey) {
    json_error('Domain API credentials are not configured on the server', 503);
}

$lastDot = strrpos($domain, '.');
$name = substr($domain, 0, $lastDot);
$tld  = substr($domain, $lastDot + 1);

$url = rtrim($baseUrl, '/') . '/api/domain/check?' . http_build_query([
    'domainNames' => $name,
    'tlds' => $tld,
    'period' => 1,
    'command' => 'create',
]);

$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 15,
    CURLOPT_CONNECTTIMEOUT => 8,
    CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
    CURLOPT_USERPWD => $resellerId . ':' . $apiKey,
    CURLOPT_HTTPHEADER => ['Accept: application/json'],
]);

$body = curl_exec($ch);
$curlError = curl_error($ch);
$status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($body === false || $curlError !== '') {
    json_error('Unable to reach domain provider', 502);
}

$data = json_decode($body, true);
if (!is_array($data)) {
    json_error('Invalid response from domain provider', 502);
}

$rows = $data;
if (isset($data['data']) && is_array($data['data'])) $rows = $data['data'];
if (isset($data['result']) && is_array($data['result'])) $rows = $data['result'];
$row = (isset($rows[0]) && is_array($rows[0])) ? $rows[0] : $rows;

$statusValue = strtolower((string)($row['Status'] ?? $row['status'] ?? ''));
$available = in_array($statusValue, ['available', 'ok', 'success'], true);
$priceValue = $row['Price'] ?? $row['price'] ?? null;
$currency = $row['Currency'] ?? $row['currency'] ?? '';
$price = $priceValue !== null ? (string)$priceValue . ($currency !== '' ? ' ' . $currency : '') : '—';

if ($status >= 400) {
    json_error((string)($row['Message'] ?? $row['message'] ?? 'Domain provider returned an error'), 502);
}

echo json_encode([
    'domain' => $domain,
    'available' => $available,
    'status' => $statusValue ?: 'unknown',
    'price' => $price,
    'connected' => true,
], JSON_UNESCAPED_SLASHES);

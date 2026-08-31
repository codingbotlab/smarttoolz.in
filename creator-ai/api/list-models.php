<?php
declare(strict_types=1);

require_once __DIR__ . '/../auth/config.php';
requireLogin();

header('Content-Type: application/json; charset=utf-8');

$key = defined('GEMINI_API_KEY')
    ? trim((string)GEMINI_API_KEY)
    : '';

if ($key === '') {
    http_response_code(500);

    echo json_encode([
        'ok' => false,
        'error' => 'Gemini API key missing.'
    ]);

    exit;
}

$url =
    'https://generativelanguage.googleapis.com/v1beta/models?key=' .
    rawurlencode($key);

$ch = curl_init($url);

curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json'
    ]
]);

$response = curl_exec($ch);

$httpCode = (int)curl_getinfo(
    $ch,
    CURLINFO_HTTP_CODE
);

curl_close($ch);

if ($response === false) {
    http_response_code(502);

    echo json_encode([
        'ok' => false,
        'error' => 'Unable to connect to Gemini.'
    ]);

    exit;
}

$result = json_decode(
    $response,
    true
);

if (!is_array($result)) {
    http_response_code(502);

    echo json_encode([
        'ok' => false,
        'error' => 'Gemini returned invalid JSON.'
    ]);

    exit;
}

if ($httpCode < 200 || $httpCode >= 300) {
    http_response_code($httpCode);

    echo json_encode([
        'ok' => false,
        'error' =>
            $result['error']['message']
            ?? 'Gemini model request failed.',
        'raw' => $result
    ], JSON_PRETTY_PRINT);

    exit;
}

$models = [];

foreach (($result['models'] ?? []) as $model) {

    $methods =
        $model['supportedGenerationMethods']
        ?? [];

    $models[] = [
        'name' =>
            $model['name'] ?? '',

        'displayName' =>
            $model['displayName'] ?? '',

        'methods' =>
            $methods
    ];
}

echo json_encode([
    'ok' => true,
    'models' => $models
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

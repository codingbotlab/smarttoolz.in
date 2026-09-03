<?php
declare(strict_types=1);

// Keddy server configuration. This file is generated for the current SmartToolz deployment.
// Environment variables override these values when present.
$clientJson = __DIR__ . '/../client_secret_52634723911-fe1ha1bs7p8nloc5phn0eimrg4qgism3.apps.googleusercontent.com.json';
$client = [];
if (is_file($clientJson)) {
    $raw = json_decode((string)file_get_contents($clientJson), true);
    $client = is_array($raw['web'] ?? null) ? $raw['web'] : [];
}

return [
    'google_client_id' => getenv('KEDDY_GOOGLE_CLIENT_ID') ?: ($client['client_id'] ?? ''),
    'google_client_secret' => getenv('KEDDY_GOOGLE_CLIENT_SECRET') ?: ($client['client_secret'] ?? ''),
    'app_secret' => getenv('KEDDY_APP_SECRET') ?: 'CsulLQ8DZBXXjLpnscEbmjlLzRDQx6qOeVMct7dhIqI',
    'redirect_uri' => getenv('KEDDY_REDIRECT_URI') ?: 'https://smarttoolz.in/keddy-bot/oauth-callback.php',
    'ai_url' => getenv('KEDDY_AI_URL') ?: '',
    'ai_key' => getenv('KEDDY_AI_KEY') ?: '',
    'ai_model' => getenv('KEDDY_AI_MODEL') ?: 'gpt-4o-mini',
    'database' => __DIR__ . '/data/keddy.sqlite',
];

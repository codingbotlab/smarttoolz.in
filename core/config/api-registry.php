<?php
declare(strict_types=1);

/**
 * Central API registry.
 * Values are read from environment variables; never place real keys here.
 */
return [
    'database' => [
        'host' => getenv('DB_HOST') ?: 'localhost',
        'name' => getenv('DB_NAME') ?: '',
        'user' => getenv('DB_USER') ?: '',
        'password' => getenv('DB_PASS') ?: '',
    ],
    'google' => [
        'client_id' => getenv('GOOGLE_CLIENT_ID') ?: '',
        'client_secret' => getenv('GOOGLE_CLIENT_SECRET') ?: '',
        'redirect_uri' => getenv('GOOGLE_REDIRECT_URI') ?: 'https://smarttoolz.in/creator-ai/auth/google-callback.php',
    ],
    'openai' => [
        'api_key' => getenv('OPENAI_API_KEY') ?: '',
    ],
    'gemini' => [
        'api_key' => getenv('GEMINI_API_KEY') ?: '',
        'model' => getenv('GEMINI_MODEL') ?: 'Gemini 3.5 Flash Lite',
    ],
    'razorpay' => [
        'key_id' => getenv('RAZORPAY_KEY_ID') ?: '',
        'key_secret' => getenv('RAZORPAY_KEY_SECRET') ?: '',
        'webhook_secret' => getenv('RAZORPAY_WEBHOOK_SECRET') ?: '',
    ],
    'youtube' => [
        'client_id' => getenv('REDDOTT_YOUTUBE_CLIENT_ID') ?: '',
        'client_secret' => getenv('REDDOTT_YOUTUBE_CLIENT_SECRET') ?: '',
        'client_config' => getenv('REDDOTT_YOUTUBE_CLIENT_CONFIG') ?: '',
    ],
];

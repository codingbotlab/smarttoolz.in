<?php
declare(strict_types=1);

require_once __DIR__ . '/../auth/config.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

try {

    requireLogin();

    $userId = (int)($_SESSION['user_id'] ?? 0);

    if ($userId <= 0) {
        http_response_code(401);

        echo json_encode([
            'ok' => false,
            'error' => 'Please login again.'
        ]);

        exit;
    }

    /*
    |--------------------------------------------------------------------------
    | Gemini API key
    |--------------------------------------------------------------------------
    */

    $apiKey = '';

    if (defined('GEMINI_API_KEY')) {
        $apiKey = trim((string)GEMINI_API_KEY);
    }

    if ($apiKey === '' && defined('GOOGLE_API_KEY')) {
        $apiKey = trim((string)GOOGLE_API_KEY);
    }

    if ($apiKey === '') {
        http_response_code(500);

        echo json_encode([
            'ok' => false,
            'error' => 'Gemini API key is not configured.'
        ]);

        exit;
    }

    /*
    |--------------------------------------------------------------------------
    | Live model
    |--------------------------------------------------------------------------
    |
    | Use the Live model available in your Gemini project.
    |
    */

    $liveModel = 'gemini-2.5-flash-native-audio-dialog';

    if (defined('GEMINI_LIVE_MODEL')) {
        $configured =
            trim((string)GEMINI_LIVE_MODEL);

        if ($configured !== '') {
            $liveModel = $configured;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Optional session id
    |--------------------------------------------------------------------------
    */

    $raw =
        file_get_contents('php://input');

    $input =
        json_decode(
            $raw ?: '{}',
            true
        );

    if (!is_array($input)) {
        $input = [];
    }

    $sessionId =
        (int)(
            $input['session_id']
            ?? 0
        );

    /*
    |--------------------------------------------------------------------------
    | IMPORTANT
    |--------------------------------------------------------------------------
    |
    | Never send the Gemini API key to the browser.
    |
    | This endpoint returns only the information required by
    | your application to establish the voice service.
    |
    */

    echo json_encode([
        'ok' => true,

        'user_id' => $userId,

        'session_id' =>
            $sessionId,

        'mode' => 'live',

        'model' =>
            $liveModel,

        'provider' => 'gemini',

        'audio' => [
            'input' => [
                'mime_type' =>
                    'audio/pcm;rate=16000'
            ],

            'output' => [
                'mime_type' =>
                    'audio/pcm'
            ]
        ],

        'features' => [
            'audio_input' => true,
            'audio_output' => true,
            'transcription' => true,
            'text_response' => true
        ],

        /*
        |--------------------------------------------------------------------------
        | Browser should connect to YOUR websocket bridge.
        |--------------------------------------------------------------------------
        */

        'websocket' =>
            '/creator-ai/api/live-ws'
    ]);

    exit;

} catch (Throwable $e) {

    error_log(
        'Creator AI Live API Error: ' .
        $e->getMessage()
    );

    http_response_code(500);

    echo json_encode([
        'ok' => false,
        'error' =>
            'Unable to initialize voice mode.'
    ]);

    exit;
}
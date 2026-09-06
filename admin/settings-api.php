<?php
declare(strict_types=1);

// JSON-only endpoint: never allow notices/warnings to corrupt the response.
if (ob_get_level() === 0) ob_start();
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');

require_once __DIR__ . '/bootstrap.php';

function settingsJson(array $data, int $status = 200): never
{
    while (ob_get_level() > 0) {
        ob_end_clean();
    }
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

try {
    requireAdmin();

    if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
        settingsJson(['ok' => false, 'message' => 'POST required.'], 405);
    }

    // Accept either JSON or regular form POST, so this endpoint is resilient.
    $contentType = strtolower((string)($_SERVER['CONTENT_TYPE'] ?? ''));
    $payload = [];

    if (str_contains($contentType, 'application/json')) {
        $raw = (string)file_get_contents('php://input');
        $payload = json_decode($raw, true);
        if (!is_array($payload)) {
            settingsJson(['ok' => false, 'message' => 'Invalid JSON payload.'], 400);
        }
    } else {
        $payload = $_POST;
    }

    $csrf = (string)($payload['csrf'] ?? '');
    if ($csrf === '') {
        settingsJson(['ok' => false, 'message' => 'Missing security token. Reload the page and try again.'], 419);
    }
    if (!hash_equals(adminCsrf(), $csrf)) {
        settingsJson(['ok' => false, 'message' => 'Security token expired. Reload the page and try again.'], 419);
    }

    $items = $payload['settings'] ?? [];
    if (!is_array($items) || !$items) {
        settingsJson(['ok' => false, 'message' => 'No settings supplied.'], 400);
    }

    $db = adminDb();
    $db->beginTransaction();

    $upsert = $db->prepare(
        'INSERT INTO smarttoolz_settings
            (setting_key, setting_value, setting_type, description, enabled)
         VALUES (?, ?, ?, ?, ?)
         ON DUPLICATE KEY UPDATE
            setting_value=VALUES(setting_value),
            setting_type=VALUES(setting_type),
            description=VALUES(description),
            enabled=VALUES(enabled)'
    );

    $saved = [];

    foreach ($items as $item) {
        if (!is_array($item)) {
            continue;
        }

        $key = trim((string)($item['key'] ?? ''));
        $value = (string)($item['value'] ?? '');
        $type = (string)($item['type'] ?? 'text');
        $description = trim((string)($item['description'] ?? ''));
        $enabled = !empty($item['enabled']) ? 1 : 0;

        if ($key === '' || !preg_match('/^[a-zA-Z0-9_.-]{1,120}$/', $key)) {
            throw new RuntimeException('Invalid setting key: ' . $key);
        }

        if (!in_array($type, ['text', 'number', 'boolean', 'json'], true)) {
            throw new RuntimeException('Invalid setting type for: ' . $key);
        }

        if ($type === 'number') {
            if ($value === '' || !is_numeric($value)) {
                throw new RuntimeException('Invalid number for: ' . $key);
            }
        }

        if ($type === 'boolean') {
            $value = in_array(strtolower(trim($value)), ['1', 'true', 'yes', 'on'], true) ? '1' : '0';
        }

        if ($type === 'json' && trim($value) !== '') {
            json_decode($value, true, 512, JSON_THROW_ON_ERROR);
        }

        $upsert->execute([$key, $value, $type, $description, $enabled]);
        $saved[] = $key;
        adminAudit('update_setting', 'smarttoolz_settings', $key, 'Group save');
    }

    $db->commit();

    settingsJson([
        'ok' => true,
        'message' => 'Settings saved successfully.',
        'saved' => $saved,
        'count' => count($saved)
    ]);
} catch (Throwable $e) {
    if (isset($db) && $db instanceof PDO && $db->inTransaction()) {
        $db->rollBack();
    }
    error_log('SmartToolz settings API: ' . $e->getMessage());
    settingsJson([
        'ok' => false,
        'message' => $e->getMessage() ?: 'Unable to save settings.'
    ], 400);
}

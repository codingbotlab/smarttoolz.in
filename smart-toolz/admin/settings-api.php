<?php
declare(strict_types=1);
require_once __DIR__ . '/bootstrap.php';

$admin = requireAdmin();
header('Content-Type: application/json; charset=utf-8');

try {
    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
        http_response_code(405);
        echo json_encode(['ok'=>false,'message'=>'POST required.']);
        exit;
    }

    verifyAdminCsrf();
    $payload = json_decode((string)file_get_contents('php://input'), true);
    if (!is_array($payload)) {
        http_response_code(400);
        echo json_encode(['ok'=>false,'message'=>'Invalid JSON payload.']);
        exit;
    }

    $items = $payload['settings'] ?? [];
    if (!is_array($items) || !$items) {
        http_response_code(400);
        echo json_encode(['ok'=>false,'message'=>'No settings supplied.']);
        exit;
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
        if (!is_array($item)) continue;

        $key = trim((string)($item['key'] ?? ''));
        $value = (string)($item['value'] ?? '');
        $type = (string)($item['type'] ?? 'text');
        $description = trim((string)($item['description'] ?? ''));
        $enabled = !empty($item['enabled']) ? 1 : 0;

        if ($key === '' || !preg_match('/^[a-zA-Z0-9_.-]{1,120}$/', $key)) {
            throw new RuntimeException('Invalid setting key: ' . $key);
        }
        if (!in_array($type, ['text','number','boolean','json'], true)) {
            throw new RuntimeException('Invalid setting type for: ' . $key);
        }
        if ($type === 'number' && $value !== '' && !is_numeric($value)) {
            throw new RuntimeException('Invalid number for: ' . $key);
        }
        if ($type === 'boolean') {
            $value = in_array(strtolower(trim($value)), ['1','true','yes','on'], true) ? '1' : '0';
        }
        if ($type === 'json' && $value !== '') {
            json_decode($value, true, 512, JSON_THROW_ON_ERROR);
        }

        $upsert->execute([$key, $value, $type, $description, $enabled]);
        $saved[] = $key;
        adminAudit('update_setting', 'smarttoolz_settings', $key, 'Group save');
    }

    $db->commit();

    echo json_encode([
        'ok' => true,
        'message' => 'Settings saved successfully.',
        'saved' => $saved
    ], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    if (isset($db) && $db instanceof PDO && $db->inTransaction()) {
        $db->rollBack();
    }
    error_log('SmartToolz settings API: ' . $e->getMessage());
    http_response_code(400);
    echo json_encode(['ok'=>false,'message'=>$e->getMessage()]);
}

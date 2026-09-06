<?php
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/creator-ai/auth/config.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

$userId = (int)($_SESSION['user_id'] ?? 0);
if ($userId <= 0) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'reason' => 'login_required']);
    exit;
}

try {
    $db = db();
    $action = (string)($_REQUEST['action'] ?? 'list');

    if ($action === 'read') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $q = $db->prepare('UPDATE smarttoolz_notifications SET read_at=NOW() WHERE id=? AND user_id=?');
            $q->execute([$id, $userId]);
        }
    } elseif ($action === 'read_all') {
        $q = $db->prepare('UPDATE smarttoolz_notifications SET read_at=NOW() WHERE user_id=? AND read_at IS NULL');
        $q->execute([$userId]);
    }

    $q = $db->prepare('SELECT COUNT(*) FROM smarttoolz_notifications WHERE user_id=? AND read_at IS NULL');
    $q->execute([$userId]);
    $unread = (int)$q->fetchColumn();

    $q = $db->prepare('SELECT id,type,title,message,read_at,created_at FROM smarttoolz_notifications WHERE user_id=? ORDER BY id DESC LIMIT 25');
    $q->execute([$userId]);

    echo json_encode([
        'ok' => true,
        'unread' => $unread,
        'notifications' => $q->fetchAll(PDO::FETCH_ASSOC),
    ], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    error_log('SmartToolz notifications API: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['ok' => false, 'message' => 'Unable to load notifications.']);
}

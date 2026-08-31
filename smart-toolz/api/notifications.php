<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/lib/trials.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

$userId = smarttoolz_current_user_id();
if (!$userId) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'reason' => 'login_required']);
    exit;
}

try {
    smarttoolz_trial_install();
    $db = smarttoolz_trial_db();
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

    echo json_encode([
        'ok' => true,
        'unread' => smarttoolz_notification_unread_count($userId),
        'notifications' => smarttoolz_notification_list($userId, 25),
    ], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    error_log('SmartToolz notifications API: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['ok' => false, 'message' => 'Unable to load notifications.']);
}

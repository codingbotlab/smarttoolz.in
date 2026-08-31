<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/lib/trials.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

$userId = smarttoolz_current_user_id();
if (!$userId) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'reason' => 'login_required', 'message' => 'Please login to use your 5 free trials per tool.']);
    exit;
}

try {
    $tool = (string)($_REQUEST['tool'] ?? '');
    $action = (string)($_REQUEST['action'] ?? 'status');

    if ($action === 'consume') {
        $result = smarttoolz_trial_consume($userId, $tool);
        if (!$result['ok']) {
            http_response_code(429);
            $result['message'] = 'Your 5 free trials for this tool are finished. Please choose another tool or upgrade your plan.';
        }
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
        exit;
    }

    $result = smarttoolz_trial_status($userId, $tool);
    echo json_encode(['ok' => true] + $result, JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    error_log('SmartToolz trial API: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['ok' => false, 'message' => 'Unable to check trial status right now.']);
}

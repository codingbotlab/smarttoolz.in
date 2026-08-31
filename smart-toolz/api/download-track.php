<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) session_start();
header('Content-Type: application/json; charset=utf-8');

try {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/creator-ai/auth/config.php';
    $pdo = db();

    $tool = trim((string)($_POST['tool'] ?? ''));
    $page = trim((string)($_POST['page'] ?? ''));
    if (!preg_match('/^[a-zA-Z0-9_-]{1,150}$/', $tool)) throw new InvalidArgumentException('Invalid tool.');
    if ($page === '' || strlen($page) > 500) $page = '/smart-toolz/tools/' . $tool . '.php';
    if (!str_starts_with($page, '/')) $page = '/' . $page;

    $userId = (int)($_SESSION['user_id'] ?? 0);
    $guestId = (string)($_COOKIE['smarttoolz_guest_key'] ?? $_SESSION['smarttoolz_guest_key'] ?? '');
    if ($guestId !== '' && !preg_match('/^[a-f0-9]{64}$/', $guestId)) $guestId = '';

    $pdo->exec("CREATE TABLE IF NOT EXISTS smarttoolz_download_activity (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id BIGINT UNSIGNED NULL,
        guest_id CHAR(64) NULL,
        tool_slug VARCHAR(150) NOT NULL,
        page_url VARCHAR(500) NOT NULL,
        ip_address VARCHAR(45) NULL,
        user_agent VARCHAR(500) NULL,
        downloaded_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_user (user_id), INDEX idx_guest (guest_id), INDEX idx_tool (tool_slug), INDEX idx_date (downloaded_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    $stmt = $pdo->prepare('INSERT INTO smarttoolz_download_activity (user_id,guest_id,tool_slug,page_url,ip_address,user_agent) VALUES (?,?,?,?,?,?)');
    $stmt->execute([
        $userId > 0 ? $userId : null,
        $userId > 0 ? null : ($guestId !== '' ? $guestId : null),
        strtolower($tool),
        $page,
        substr((string)($_SERVER['REMOTE_ADDR'] ?? ''), 0, 45),
        substr((string)($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 500)
    ]);

    echo json_encode(['ok'=>true,'download_count'=>(int)$pdo->query('SELECT COUNT(*) FROM smarttoolz_download_activity')->fetchColumn()]);
} catch (Throwable $e) {
    error_log('SmartToolz download tracking: '.$e->getMessage());
    echo json_encode(['ok'=>false,'message'=>'Tracking unavailable']);
}

<?php
declare(strict_types=1);

/* SmartToolz application bootstrap: session, database and safe schema upgrades. */
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    session_start();
}

const SMARTTOOLZ_DB_HOST = 'localhost';
const SMARTTOOLZ_DB_NAME = 'u969897784_assetsbox';
const SMARTTOOLZ_DB_USER = 'u969897784_maya';

function smarttoolz_db(): PDO {
    static $pdo;
    if ($pdo instanceof PDO) return $pdo;
    $pass = getenv('SMARTTOOLZ_DB_PASS') ?: '';
    if ($pass === '') {
        throw new RuntimeException('Database password is not configured. Set SMARTTOOLZ_DB_PASS on the server.');
    }
    $pdo = new PDO('mysql:host='.SMARTTOOLZ_DB_HOST.';dbname='.SMARTTOOLZ_DB_NAME.';charset=utf8mb4', SMARTTOOLZ_DB_USER, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    smarttoolz_install_schema($pdo);
    return $pdo;
}

function smarttoolz_install_schema(PDO $pdo): void {
    static $done = false;
    if ($done) return;
    $done = true;
    $queries = [
        "CREATE TABLE IF NOT EXISTS tool_usage (id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,user_id BIGINT UNSIGNED NULL,tool_slug VARCHAR(150) NOT NULL,ip_hash CHAR(64) NULL,created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,INDEX idx_tool_user(user_id),INDEX idx_tool_slug(tool_slug),INDEX idx_tool_created(created_at)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
        "CREATE TABLE IF NOT EXISTS user_favorite_tools (user_id BIGINT UNSIGNED NOT NULL,tool_slug VARCHAR(150) NOT NULL,created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,PRIMARY KEY(user_id,tool_slug)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
        "CREATE TABLE IF NOT EXISTS user_settings (user_id BIGINT UNSIGNED PRIMARY KEY,settings_json JSON NOT NULL,updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
    ];
    foreach ($queries as $sql) { try { $pdo->exec($sql); } catch (Throwable $e) { error_log('SmartToolz schema: '.$e->getMessage()); } }
}

function smarttoolz_h(string $v): string { return htmlspecialchars($v, ENT_QUOTES, 'UTF-8'); }
function smarttoolz_track(string $slug): void {
    try {
        $uid = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        smarttoolz_db()->prepare('INSERT INTO tool_usage(user_id,tool_slug,ip_hash) VALUES(?,?,?)')->execute([$uid ?: null,$slug,$ip ? hash('sha256',$ip) : null]);
    } catch (Throwable $e) { error_log('SmartToolz tracking: '.$e->getMessage()); }
}

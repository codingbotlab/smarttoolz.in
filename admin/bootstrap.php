<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/creator-ai/auth/config.php';

const SMARTTOOLZ_ADMIN_EMAIL = 'maddyhunk30@gmail.com';

function adminDb(): PDO { return db(); }

function adminTableExists(PDO $db, string $table): bool
{
    $q = $db->prepare("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = ?");
    $q->execute([$table]);
    return (int)$q->fetchColumn() > 0;
}

function adminColumnExists(PDO $db, string $table, string $column): bool
{
    $q = $db->prepare("SELECT COUNT(*) FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = ? AND column_name = ?");
    $q->execute([$table, $column]);
    return (int)$q->fetchColumn() > 0;
}

function adminInstall(): void
{
    static $done = false;
    if ($done) return;
    $done = true;
    $db = adminDb();

    foreach (['users', 'creator_users'] as $table) {
        if (adminTableExists($db, $table) && !adminColumnExists($db, $table, 'role')) {
            try { $db->exec("ALTER TABLE `{$table}` ADD COLUMN role VARCHAR(20) NOT NULL DEFAULT 'user'"); } catch (Throwable $e) { error_log('SmartToolz role migration: '.$e->getMessage()); }
        }
    }

    $db->exec("CREATE TABLE IF NOT EXISTS smarttoolz_settings (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        setting_key VARCHAR(120) NOT NULL UNIQUE,
        setting_value LONGTEXT NULL,
        setting_type VARCHAR(20) NOT NULL DEFAULT 'text',
        description VARCHAR(255) NULL,
        enabled TINYINT(1) NOT NULL DEFAULT 1,
        updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_settings_enabled (enabled)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    $db->exec("CREATE TABLE IF NOT EXISTS smarttoolz_admin_audit (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        admin_user_id BIGINT UNSIGNED NULL,
        action VARCHAR(80) NOT NULL,
        target_type VARCHAR(80) NULL,
        target_id VARCHAR(120) NULL,
        details TEXT NULL,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_admin_audit_created (created_at),
        INDEX idx_admin_audit_user (admin_user_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    foreach ([
        'site_name' => ['SmartToolz','text','Website brand/name'],
        'site_tagline' => ['Free, Fast & Simple Tools','text','Default site tagline'],
        'guest_trial_limit' => ['5','number','Free guest uses per tool before login is required'],
        'guest_trial_enabled' => ['1','boolean','Allow non-logged-in visitors to use free trials'],
        'download_tracking_enabled' => ['1','boolean','Record successful tool downloads'],
        'analytics_enabled' => ['1','boolean','Enable site analytics/pageview tracking'],
        'analytics_live_enabled' => ['1','boolean','Maintain live visitor presence data'],
        'analytics_live_refresh_seconds' => ['1','number','Admin live dashboard refresh interval'],
        'maintenance_mode' => ['0','boolean','Show maintenance mode to non-admin visitors'],
        'timezone' => ['Asia/Kolkata','text','Application display timezone'],
        'max_upload_mb' => ['20','number','Default client upload limit in MB'],
        'admin_session_minutes' => ['120','number','Preferred admin session duration in minutes'],
        'ads_enabled' => ['1','boolean','Allow database-configured ads to render'],
    ] as $key => [$value,$type,$description]) {
        try {
            $q = $db->prepare('INSERT IGNORE INTO smarttoolz_settings(setting_key,setting_value,setting_type,description,enabled) VALUES(?,?,?,?,1)');
            $q->execute([$key,$value,$type,$description]);
        } catch (Throwable $e) { error_log('SmartToolz default setting '.$key.': '.$e->getMessage()); }
    }

    foreach (['users', 'creator_users'] as $table) {
        if (adminTableExists($db, $table) && adminColumnExists($db, $table, 'role')) {
            try {
                $q = $db->prepare("UPDATE `{$table}` SET role='admin' WHERE LOWER(email)=LOWER(?)");
                $q->execute([SMARTTOOLZ_ADMIN_EMAIL]);
            } catch (Throwable $e) { error_log('SmartToolz admin role seed: '.$e->getMessage()); }
        }
    }
}

function currentAdmin(): ?array
{
    if (empty($_SESSION['user_id'])) return null;
    try {
        $q = adminDb()->prepare('SELECT id,name,email,avatar,role FROM creator_users WHERE id=? LIMIT 1');
        $q->execute([(int)$_SESSION['user_id']]);
        $u = $q->fetch(PDO::FETCH_ASSOC);
        return $u ?: null;
    } catch (Throwable $e) {
        error_log('SmartToolz currentAdmin: '.$e->getMessage());
        return null;
    }
}

function requireAdmin(): array
{
    adminInstall();
    $user = currentAdmin();
    if (!$user || !in_array((string)($user['role'] ?? 'user'), ['admin','superadmin'], true)) {
        if (empty($_SESSION['user_id'])) header('Location: /creator-ai/auth/google-login.php');
        else { http_response_code(403); header('Content-Type: text/html; charset=utf-8'); }
        exit('<!doctype html><meta charset="utf-8"><title>Access denied</title><style>body{font-family:Arial;background:#f7f8fc;padding:60px;color:#172033}main{max-width:620px;margin:auto;background:#fff;padding:30px;border:1px solid #e5e8ef;border-radius:18px}a{color:#635bff;font-weight:700}</style><main><h1>Admin access required</h1><p>Your account does not have admin permission.</p><a href="/smart-toolz/">Back to SmartToolz</a></main>');
    }
    return $user;
}

function adminCsrf(): string
{
    if (empty($_SESSION['smarttoolz_admin_csrf'])) $_SESSION['smarttoolz_admin_csrf'] = bin2hex(random_bytes(32));
    return (string)$_SESSION['smarttoolz_admin_csrf'];
}

function verifyAdminCsrf(): void
{
    $token = (string)($_POST['csrf'] ?? '');
    if (!hash_equals(adminCsrf(), $token)) { http_response_code(419); exit('Invalid security token. Please reload the admin panel.'); }
}

function adminAudit(string $action, ?string $targetType = null, ?string $targetId = null, ?string $details = null): void
{
    try {
        $q = adminDb()->prepare('INSERT INTO smarttoolz_admin_audit(admin_user_id,action,target_type,target_id,details) VALUES(?,?,?,?,?)');
        $q->execute([$_SESSION['user_id'] ?? null, $action, $targetType, $targetId, $details]);
    } catch (Throwable $e) { error_log('SmartToolz admin audit: ' . $e->getMessage()); }
}

adminInstall();

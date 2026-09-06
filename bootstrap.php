<?php
declare(strict_types=1);

require_once __DIR__ . '/lib/settings.php';

$tz = smarttoolz_setting_string('timezone', 'Asia/Kolkata');
if ($tz !== '' && in_array($tz, timezone_identifiers_list(), true)) {
    date_default_timezone_set($tz);
}

const SMARTTOOLZ_DB_HOST = 'localhost';
const SMARTTOOLZ_DB_NAME = 'u969897784_assetsbox';
const SMARTTOOLZ_DB_USER = 'u969897784_maya';

function smarttoolz_db(): PDO {
    static $pdo = null;
    if ($pdo instanceof PDO) return $pdo;
    $pass = getenv('SMARTTOOLZ_DB_PASS') ?: '';
    if ($pass === '') throw new RuntimeException('Database password is not configured.');
    $pdo = new PDO(
        'mysql:host='.SMARTTOOLZ_DB_HOST.';dbname='.SMARTTOOLZ_DB_NAME.';charset=utf8mb4',
        SMARTTOOLZ_DB_USER,
        $pass,
        [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,PDO::ATTR_EMULATE_PREPARES=>false]
    );
    return $pdo;
}

function smarttoolz_h(string $v): string {
    return htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
}

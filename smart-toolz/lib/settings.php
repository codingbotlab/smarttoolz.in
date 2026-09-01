<?php
declare(strict_types=1);

if (!function_exists('smarttoolz_setting')) {
    /**
     * Read a SmartToolz setting from smarttoolz_settings.
     * Returns $default when the table/key is unavailable.
     */
    function smarttoolz_setting(string $key, mixed $default = null): mixed
    {
        static $cache = [];
        static $loaded = false;

        if (array_key_exists($key, $cache)) {
            return $cache[$key];
        }

        if (!$loaded) {
            $loaded = true;
            try {
                require_once $_SERVER['DOCUMENT_ROOT'] . '/creator-ai/auth/config.php';
                $db = db();
                $q = $db->query('SELECT setting_key, setting_value, setting_type, enabled FROM smarttoolz_settings WHERE enabled=1');
                foreach ($q->fetchAll(PDO::FETCH_ASSOC) as $row) {
                    $k = (string)($row['setting_key'] ?? '');
                    if ($k === '') continue;
                    $value = (string)($row['setting_value'] ?? '');
                    $type = (string)($row['setting_type'] ?? 'text');
                    if ($type === 'number') {
                        $value = is_numeric($value) ? (float)$value : 0;
                        if (is_int((int)$value)) $value = (int)$value;
                    } elseif ($type === 'boolean') {
                        $value = in_array(strtolower($value), ['1','true','yes','on'], true);
                    } elseif ($type === 'json') {
                        $decoded = json_decode($value, true);
                        $value = json_last_error() === JSON_ERROR_NONE ? $decoded : $value;
                    }
                    $cache[$k] = $value;
                }
            } catch (Throwable $e) {
                error_log('SmartToolz settings read: ' . $e->getMessage());
            }
        }

        return array_key_exists($key, $cache) ? $cache[$key] : $default;
    }

    function smarttoolz_setting_int(string $key, int $default): int
    {
        return max(0, (int)smarttoolz_setting($key, $default));
    }

    function smarttoolz_setting_bool(string $key, bool $default = false): bool
    {
        return (bool)smarttoolz_setting($key, $default);
    }
}

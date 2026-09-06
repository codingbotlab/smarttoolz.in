<?php
declare(strict_types=1);

/**
 * SmartToolz runtime settings.
 *
 * All application code should read configurable behaviour through these
 * helpers instead of hard-coding values. Missing/broken settings always
 * fall back to safe defaults so the site keeps working.
 */

if (!function_exists('smarttoolz_settings_all')) {
    function smarttoolz_settings_all(): array
    {
        static $settings = null;
        if (is_array($settings)) {
            return $settings;
        }

        $settings = [];
        try {
            require_once $_SERVER['DOCUMENT_ROOT'] . '/creator-ai/auth/config.php';
            $db = db();
            $q = $db->query(
                'SELECT setting_key, setting_value, setting_type, enabled
                 FROM smarttoolz_settings'
            );
            foreach ($q->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $key = trim((string)($row['setting_key'] ?? ''));
                if ($key === '') {
                    continue;
                }

                $value = (string)($row['setting_value'] ?? '');
                $type = (string)($row['setting_type'] ?? 'text');
                $enabled = (int)($row['enabled'] ?? 0) === 1;

                if (!$enabled) {
                    continue;
                }

                switch ($type) {
                    case 'number':
                        $value = is_numeric($value) ? (float)$value : 0;
                        if (fmod((float)$value, 1.0) === 0.0) {
                            $value = (int)$value;
                        }
                        break;

                    case 'boolean':
                        $value = in_array(strtolower($value), ['1', 'true', 'yes', 'on'], true);
                        break;

                    case 'json':
                        $decoded = json_decode($value, true);
                        $value = json_last_error() === JSON_ERROR_NONE ? $decoded : null;
                        break;

                    default:
                        // text
                        break;
                }

                $settings[$key] = $value;
            }
        } catch (Throwable $e) {
            error_log('SmartToolz settings read: ' . $e->getMessage());
        }

        return $settings;
    }

    function smarttoolz_setting(string $key, mixed $default = null): mixed
    {
        $settings = smarttoolz_settings_all();
        return array_key_exists($key, $settings) ? $settings[$key] : $default;
    }

    function smarttoolz_setting_int(string $key, int $default = 0): int
    {
        return (int)smarttoolz_setting($key, $default);
    }

    function smarttoolz_setting_float(string $key, float $default = 0.0): float
    {
        return (float)smarttoolz_setting($key, $default);
    }

    function smarttoolz_setting_bool(string $key, bool $default = false): bool
    {
        return (bool)smarttoolz_setting($key, $default);
    }

    function smarttoolz_setting_string(string $key, string $default = ''): string
    {
        $value = smarttoolz_setting($key, $default);
        if (is_array($value) || is_object($value)) {
            return $default;
        }
        return (string)$value;
    }

    function smarttoolz_setting_json(string $key, array $default = []): array
    {
        $value = smarttoolz_setting($key, $default);
        return is_array($value) ? $value : $default;
    }
}

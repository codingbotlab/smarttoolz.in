<?php
declare(strict_types=1);

/** SmartToolz runtime settings. Authentication/admin dependencies are intentionally removed. */
if (!function_exists('smarttoolz_settings_all')) {
    function smarttoolz_settings_all(): array
    {
        static $settings = null;
        if (is_array($settings)) return $settings;
        $settings = [];

        try {
            $host = getenv('SMARTTOOLZ_DB_HOST') ?: 'localhost';
            $name = getenv('SMARTTOOLZ_DB_NAME') ?: 'u969897784_assetsbox';
            $user = getenv('SMARTTOOLZ_DB_USER') ?: 'u969897784_maya';
            $pass = getenv('SMARTTOOLZ_DB_PASS') ?: '';
            if ($pass === '') return $settings;

            $db = new PDO(
                'mysql:host='.$host.';dbname='.$name.';charset=utf8mb4',
                $user,
                $pass,
                [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]
            );
            $q = $db->query('SELECT setting_key, setting_value, setting_type, enabled FROM smarttoolz_settings');
            foreach ($q->fetchAll() as $row) {
                $key = trim((string)($row['setting_key'] ?? ''));
                if ($key === '' || (int)($row['enabled'] ?? 0) !== 1) continue;
                $value = (string)($row['setting_value'] ?? '');
                switch ((string)($row['setting_type'] ?? 'text')) {
                    case 'number':
                        $value = is_numeric($value) ? (float)$value : 0;
                        if (fmod((float)$value, 1.0) === 0.0) $value = (int)$value;
                        break;
                    case 'boolean':
                        $value = in_array(strtolower($value), ['1','true','yes','on'], true);
                        break;
                    case 'json':
                        $decoded = json_decode($value, true);
                        $value = json_last_error() === JSON_ERROR_NONE ? $decoded : null;
                        break;
                }
                $settings[$key] = $value;
            }
        } catch (Throwable $e) {
            error_log('SmartToolz settings read: '.$e->getMessage());
        }
        return $settings;
    }

    function smarttoolz_setting(string $key, mixed $default=null): mixed {
        $settings = smarttoolz_settings_all();
        return array_key_exists($key, $settings) ? $settings[$key] : $default;
    }
    function smarttoolz_setting_int(string $key, int $default=0): int { return (int)smarttoolz_setting($key,$default); }
    function smarttoolz_setting_float(string $key, float $default=0.0): float { return (float)smarttoolz_setting($key,$default); }
    function smarttoolz_setting_bool(string $key, bool $default=false): bool { return (bool)smarttoolz_setting($key,$default); }
    function smarttoolz_setting_string(string $key, string $default=''): string {
        $value=smarttoolz_setting($key,$default);
        return (is_array($value)||is_object($value)) ? $default : (string)$value;
    }
    function smarttoolz_setting_json(string $key, array $default=[]): array {
        $value=smarttoolz_setting($key,$default);
        return is_array($value) ? $value : $default;
    }
}

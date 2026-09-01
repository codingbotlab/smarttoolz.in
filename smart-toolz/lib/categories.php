<?php
declare(strict_types=1);

if (!function_exists('smarttoolz_category_db')) {
    function smarttoolz_category_db(): PDO {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/creator-ai/auth/config.php';
        return db();
    }

    function smarttoolz_category_install(): void {
        static $done = false;
        if ($done) return;
        $done = true;
        try {
            $db = smarttoolz_category_db();
            $db->exec("CREATE TABLE IF NOT EXISTS smarttoolz_categories (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                slug VARCHAR(100) NOT NULL UNIQUE,
                name VARCHAR(120) NOT NULL,
                icon VARCHAR(20) NOT NULL DEFAULT '🛠️',
                description VARCHAR(255) NOT NULL DEFAULT '',
                sort_order INT NOT NULL DEFAULT 0,
                enabled TINYINT(1) NOT NULL DEFAULT 1,
                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX idx_category_enabled_sort(enabled,sort_order)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
            $db->exec("CREATE TABLE IF NOT EXISTS smarttoolz_tool_categories (
                tool_slug VARCHAR(150) PRIMARY KEY,
                category_id INT UNSIGNED NOT NULL,
                updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                CONSTRAINT fk_tool_category_category FOREIGN KEY(category_id) REFERENCES smarttoolz_categories(id) ON DELETE CASCADE,
                INDEX idx_tool_category_category(category_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
        } catch (Throwable $e) {
            error_log('SmartToolz categories install: ' . $e->getMessage());
        }
    }

    function smarttoolz_category_slug(string $name): string {
        $slug = strtolower(trim($name));
        $slug = preg_replace('/[^a-z0-9]+/','-', $slug) ?? '';
        return trim($slug,'-');
    }

    function smarttoolz_category_seed(array $defaults): void {
        smarttoolz_category_install();
        try {
            $db = smarttoolz_category_db();
            $q = $db->prepare('INSERT IGNORE INTO smarttoolz_categories(slug,name,icon,description,sort_order,enabled) VALUES(?,?,?,?,?,1)');
            foreach ($defaults as $i => $row) {
                $q->execute([
                    smarttoolz_category_slug((string)$row[0]),
                    (string)$row[0],
                    (string)($row[1] ?? '🛠️'),
                    (string)($row[2] ?? ''),
                    $i + 1
                ]);
            }
        } catch (Throwable $e) {
            error_log('SmartToolz category seed: ' . $e->getMessage());
        }
    }

    function smarttoolz_category_assignments(): array {
        smarttoolz_category_install();
        try {
            $q = smarttoolz_category_db()->query('SELECT t.tool_slug,c.name AS category_name,c.slug AS category_slug,c.icon AS category_icon FROM smarttoolz_tool_categories t INNER JOIN smarttoolz_categories c ON c.id=t.category_id WHERE c.enabled=1');
            $out = [];
            foreach ($q->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $out[(string)$row['tool_slug']] = $row;
            }
            return $out;
        } catch (Throwable) {
            return [];
        }
    }

    function smarttoolz_apply_tool_categories(array &$tools): void {
        smarttoolz_category_install();
        $defaults = [];
        foreach ($tools as $tool) {
            $name = trim((string)($tool['category'] ?? ''));
            if ($name !== '' && !isset($defaults[$name])) {
                $defaults[$name] = [$name, '🛠️', 'SmartToolz ' . $name . ' collection'];
            }
        }
        if ($defaults) smarttoolz_category_seed(array_values($defaults));
        $map = smarttoolz_category_assignments();
        foreach ($tools as &$tool) {
            $slug = basename((string)($tool['url'] ?? ''), '.php');
            if (isset($map[$slug])) {
                $tool['category'] = (string)$map[$slug]['category_name'];
                $tool['category_slug'] = (string)$map[$slug]['category_slug'];
                $tool['category_icon'] = (string)$map[$slug]['category_icon'];
            } else {
                $tool['category_slug'] = smarttoolz_category_slug((string)($tool['category'] ?? ''));
            }
        }
        unset($tool);
    }
}

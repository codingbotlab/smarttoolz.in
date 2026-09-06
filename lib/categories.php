<?php
declare(strict_types=1);

if (!function_exists('smarttoolz_category_slug')) {
    function smarttoolz_category_slug(string $name): string {
        $slug = strtolower(trim($name));
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug) ?? '';
        return trim($slug, '-');
    }

    function smarttoolz_apply_tool_categories(array &$tools): void {
        foreach ($tools as &$tool) {
            $category = trim((string)($tool['category'] ?? ''));
            $tool['category_slug'] = smarttoolz_category_slug($category);
            $tool['category_icon'] = '🛠️';
        }
        unset($tool);
    }
}

<?php
declare(strict_types=1);

/* SmartToolz-owned fallback for Material Symbols. Keeps icons visible even when the external font is blocked. */
ob_start(static function (string $html): string {
    $icons = [
        'menu_book' => '<path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/><path d="M8 6h8M8 10h8"/>',
        'home' => '<path d="m3 10 9-7 9 7"/><path d="M5 9v11h14V9"/><path d="M9 20v-6h6v6"/>',
        'apps' => '<rect x="4" y="4" width="6" height="6" rx="1"/><rect x="14" y="4" width="6" height="6" rx="1"/><rect x="4" y="14" width="6" height="6" rx="1"/><rect x="14" y="14" width="6" height="6" rx="1"/>',
        'build' => '<path d="M14.7 6.3a5 5 0 0 0-6.4 6.4L3 18l3 3 5.3-5.3a5 5 0 0 0 6.4-6.4l-3 3-3-3 3-3Z"/>',
        'menu' => '<path d="M4 6h16M4 12h16M4 18h16"/>',
        'library_books' => '<path d="M4 3h13a2 2 0 0 1 2 2v16H6a2 2 0 0 1-2-2V3Z"/><path d="M7 7h8M7 11h8M7 15h5"/><path d="M19 7h1v14H7"/>',
        'help_center' => '<circle cx="12" cy="12" r="9"/><path d="M9.5 9a2.5 2.5 0 1 1 4.2 1.8c-.9.8-1.7 1.2-1.7 2.7"/><path d="M12 16h.01"/>',
        'arrow_forward' => '<path d="M4 12h15"/><path d="m13 6 6 6-6 6"/>',
        'tune' => '<path d="M4 6h16M4 12h16M4 18h16"/><circle cx="9" cy="6" r="2"/><circle cx="15" cy="12" r="2"/><circle cx="11" cy="18" r="2"/>',
        'search' => '<circle cx="11" cy="11" r="6.5"/><path d="m16 16 5 5"/>',
        'school' => '<path d="m3 10 9-6 9 6-9 6-9-6Z"/><path d="M7 13v4c3 2 7 2 10 0v-4M21 10v6"/>',
        'person' => '<circle cx="12" cy="8" r="3.5"/><path d="M5 21a7 7 0 0 1 14 0"/>'
    ];

    return preg_replace_callback(
        '/<span class="material-symbols-outlined"([^>]*)>([^<]+)<\/span>/i',
        static function (array $m) use ($icons): string {
            $name = trim($m[2]);
            if (!isset($icons[$name])) return $m[0];
            $attrs = $m[1];
            return '<span class="material-symbols-outlined st-icon-fallback"'.$attrs.' aria-hidden="true"><svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" style="display:block;width:1em;height:1em">'.$icons[$name].'</svg></span>';
        },
        $html
    ) ?? $html;
});

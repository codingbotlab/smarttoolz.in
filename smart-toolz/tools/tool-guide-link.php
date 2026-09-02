<?php
/**
 * SmartToolz Knowledge Base CTA injector for individual tool pages.
 * This file is loaded automatically from tools/.user.ini and injects the
 * matching guide link into the rendered page without changing tool logic.
 */
if (PHP_SAPI === 'cli') {
    return;
}

$scriptPath = (string)($_SERVER['SCRIPT_NAME'] ?? '');
if (!str_contains($scriptPath, '/smart-toolz/tools/')) {
    return;
}

$slug = basename($scriptPath, '.php');
if ($slug === '' || $slug === 'tool-guide-link' || str_starts_with($slug, '_')) {
    return;
}

$title = ucwords(str_replace(['-', '_'], ' ', strtolower($slug)));
$url = '/knowledge-base/' . rawurlencode($slug) . '/article/';

ob_start(static function (string $html) use ($url, $title): string {
    if (str_contains($html, 'smarttoolz-kb-guide')) {
        return $html;
    }

    $safeUrl = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
    $safeTitle = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
    $cta = '<div class="smarttoolz-kb-guide" style="margin:0 0 20px;padding:14px 16px;border:1px solid #dedcff;border-radius:14px;background:#f7f6ff">'
        . '<a href="' . $safeUrl . '" aria-label="Read the detailed ' . $safeTitle . ' guide" style="display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:8px;text-decoration:none;color:#4f46d8;font-weight:800">'
        . '<span>📖 Read the Detailed ' . $safeTitle . ' Guide →</span>'
        . '<small style="color:#6b7280;font-size:12px;font-weight:600">Step-by-step instructions, tips &amp; visual walkthrough</small>'
        . '</a></div>';

    if (preg_match('/<body(?:\s[^>]*)?>/i', $html, $m, PREG_OFFSET_CAPTURE)) {
        $end = $m[0][1] + strlen($m[0][0]);
        return substr($html, 0, $end) . "\n" . $cta . "\n" . substr($html, $end);
    }

    return $cta . "\n" . $html;
});

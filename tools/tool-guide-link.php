<?php
/**
 * SmartToolz Knowledge Base CTA injector for individual tool pages.
 * The Knowledge Base directory outside smart-toolz/ is the source of truth.
 * Only add a CTA when the matching guide is actually defined there.
 */
if (PHP_SAPI === 'cli') {
    return;
}

$scriptPath = (string)($_SERVER['SCRIPT_NAME'] ?? '');
if (!str_contains($scriptPath, '/smart-toolz/tools/')) {
    return;
}

$slug = basename($scriptPath, '.php');
if ($slug === '' || $slug === 'tool-guide-link' || $slug === 'kb-guide-cta' || str_ends_with($slug, '-guide') || str_starts_with($slug, '_')) {
    return;
}

/* Scan the real Knowledge Base guide registry instead of guessing. */
$guideFile = dirname(__DIR__, 2) . '/knowledge-base/guide.php';
if (!is_file($guideFile) || !is_readable($guideFile)) {
    return;
}

$guideSource = @file_get_contents($guideFile);
if ($guideSource === false) {
    return;
}

$guidePattern = '/[\'\"]([a-z0-9][a-z0-9_-]*)[\'\"]\s*=>\s*\$G\s*\(/i';
if (!preg_match_all($guidePattern, $guideSource, $guideMatches)) {
    return;
}

$guideSlugs = array_fill_keys($guideMatches[1], true);
if (!isset($guideSlugs[$slug])) {
    return;
}

$url = '/knowledge-base/' . rawurlencode($slug) . '/article/';
$title = ucwords(str_replace(['-', '_'], ' ', strtolower($slug)));

ob_start(static function (string $html) use ($url, $title): string {
    if (str_contains($html, 'smarttoolz-kb-guide')) {
        return $html;
    }

    $safeUrl = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
    $safeTitle = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');

    $cta = '<div class="smarttoolz-kb-guide" aria-label="Knowledge Base guide">'
        . '<a href="' . $safeUrl . '" aria-label="Read the detailed ' . $safeTitle . ' guide">'
        . '<span class="smarttoolz-kb-guide-icon" aria-hidden="true">📖</span>'
        . '<span class="smarttoolz-kb-guide-copy">'
        . '<strong>Guide: How to use this tool</strong>'
        . '<small>Step-by-step instructions, tips &amp; visual walkthrough</small>'
        . '</span>'
        . '<span class="smarttoolz-kb-guide-arrow" aria-hidden="true">→</span>'
        . '</a>'
        . '</div>'
        . '<style>.smarttoolz-kb-guide{width:100%;margin:14px 0 0}.smarttoolz-kb-guide a{display:flex;align-items:center;justify-content:flex-start;gap:10px;width:100%;padding:11px 14px;border:1px solid rgba(99,91,255,.18);border-radius:12px;background:linear-gradient(135deg,#f1efff,#faf9ff);color:#5146d5;text-decoration:none;box-sizing:border-box;transition:transform .18s ease,box-shadow .18s ease,border-color .18s ease}.smarttoolz-kb-guide a:hover{transform:translateY(-1px);border-color:rgba(99,91,255,.35);box-shadow:0 8px 22px rgba(99,91,255,.12)}.smarttoolz-kb-guide-icon{font-size:18px;line-height:1}.smarttoolz-kb-guide-copy{display:flex;flex:1;min-width:0;flex-direction:column;gap:2px}.smarttoolz-kb-guide-copy strong{font-size:12px;line-height:1.3}.smarttoolz-kb-guide-copy small{color:#727b8c;font-size:10px;line-height:1.4;font-weight:600}.smarttoolz-kb-guide-arrow{font-size:18px;font-weight:900;line-height:1}.smarttoolz-kb-guide a:focus-visible{outline:3px solid rgba(99,91,255,.22);outline-offset:2px}@media(max-width:560px){.smarttoolz-kb-guide-copy small{font-size:9px}.smarttoolz-kb-guide a{padding:10px 12px}}</style>';

    $pattern = '/(<(?:div|section|article)\b[^>]*class=["\'][^"\']*\btool-seo-intro\b[^"\']*["\'][^>]*>)/i';
    if (preg_match($pattern, $html, $m, PREG_OFFSET_CAPTURE)) {
        $insertAt = $m[0][1] + strlen($m[0][0]);
        return substr($html, 0, $insertAt) . "\n" . $cta . "\n" . substr($html, $insertAt);
    }

    return $html;
});

<?php
/**
 * SmartToolz: automatically inject each tool's own Knowledge Base guide CTA.
 * Loaded through tools/.user.ini so individual tool files stay untouched.
 */
if (defined('SMARTTOOLZ_TOOL_GUIDE_LINK')) {
    return;
}
define('SMARTTOOLZ_TOOL_GUIDE_LINK', true);

$script = (string)($_SERVER['SCRIPT_NAME'] ?? '');
$base = basename($script);

// Only act on actual tool pages, never on this helper itself.
if ($base === '' || $base === 'tool-guide-link.php' || strpos($script, '/smart-toolz/tools/') === false) {
    return;
}

$slug = pathinfo($base, PATHINFO_FILENAME);
if ($slug === '' || strpos($slug, '_') === 0) {
    return;
}

ob_start(static function ($html) use ($slug) {
    $guideUrl = '/knowledge-base/' . rawurlencode($slug) . '/article/';

    // Age Calculator already has a native CTA; avoid duplicating it.
    if (strpos($html, $guideUrl) !== false) {
        return $html;
    }

    $title = ucwords(str_replace(['-', '_'], ' ', $slug));
    $safeTitle = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
    $safeUrl = htmlspecialchars($guideUrl, ENT_QUOTES, 'UTF-8');

    $style = <<<'CSS'
<style id="smarttoolz-tool-guide-link">
.smarttoolz-guide-cta{display:flex;align-items:center;justify-content:space-between;gap:14px;margin:0 0 20px;padding:15px 18px;border:1px solid #dedcff;border-radius:14px;background:linear-gradient(135deg,#f8f7ff,#fff);text-decoration:none;color:#4f46d8;box-shadow:0 5px 18px rgba(79,70,216,.07);transition:transform .2s ease,box-shadow .2s ease,border-color .2s ease}
.smarttoolz-guide-cta:hover{transform:translateY(-2px);box-shadow:0 9px 24px rgba(79,70,216,.13);border-color:#bdb8ff}
.smarttoolz-guide-cta-main{font-weight:800;font-size:15px;line-height:1.4}.smarttoolz-guide-cta-sub{display:block;margin-top:3px;color:#6b7280;font-size:12px;font-weight:600}.smarttoolz-guide-cta-arrow{font-size:18px;white-space:nowrap}
@media(max-width:800px){.smarttoolz-guide-cta{align-items:flex-start;flex-direction:column;gap:5px}.smarttoolz-guide-cta-arrow{font-size:14px}}
</style>
CSS;

    $cta = '<a class="smarttoolz-guide-cta" href="' . $safeUrl . '" aria-label="Read the detailed ' . $safeTitle . ' guide"><span><span class="smarttoolz-guide-cta-main">📖 Read the Detailed ' . $safeTitle . ' Guide →</span><small class="smarttoolz-guide-cta-sub">Step-by-step instructions, tips &amp; visual walkthrough</small></span><span class="smarttoolz-guide-cta-arrow">Open Guide&nbsp;→</span></a>';

    // Put the CTA at the top of the tool area, before its first child.
    $result = preg_replace('/(<(?:main|section|div)\b[^>]*class=["\'][^"\']*\btool\b[^"\']*["\'][^>]*>)/i', '$1' . $style . $cta, $html, 1, $count);
    if ($count > 0) {
        return $result;
    }

    // Safe fallback for pages whose tool wrapper differs.
    $result = preg_replace('/(<body\b[^>]*>)/i', '$1' . $style . $cta, $html, 1, $count);
    return $count > 0 ? $result : $html;
});

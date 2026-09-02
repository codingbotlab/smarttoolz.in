<?php
if (PHP_SAPI === 'cli') return;
$path = (string)($_SERVER['SCRIPT_NAME'] ?? '');
if (!str_contains($path, '/smart-toolz/tools/')) return;
$slug = basename($path, '.php');
if ($slug === '' || $slug === 'kb-guide-cta' || str_ends_with($slug, '-guide') || str_starts_with($slug, '_')) return;

// The Knowledge Base is outside smart-toolz/tools and uses one shared guide.php.
// Scan that registry for the real tool guide before adding the CTA.
$guideFile = dirname(__DIR__, 2) . '/knowledge-base/guide.php';
if (!is_file($guideFile) || !is_readable($guideFile)) return;
$guideSource = (string)file_get_contents($guideFile);
if ($guideSource === '') return;

$guideSlugs = [];
if (preg_match_all("~['\"]([a-z0-9]+(?:-[a-z0-9]+)*)['\"]\\s*=>\\s*\\$G\\s*\\(~i", $guideSource, $matches)) {
    $guideSlugs = array_unique($matches[1]);
}

$guideSlug = null;
foreach ($guideSlugs as $candidate) {
    if (hash_equals($candidate, $slug)) {
        $guideSlug = $candidate;
        break;
    }
}
if ($guideSlug === null) return;

$url = '/knowledge-base/' . rawurlencode($guideSlug) . '/article/';

ob_start(static function (string $html) use ($url): string {
    if (str_contains($html, 'smarttoolz-kb-guide')) return $html;
    $safe = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
    $cta = '<div class="smarttoolz-kb-guide"><a href="'.$safe.'" aria-label="Read the Knowledge Base guide for this tool"><span aria-hidden="true">📖</span><strong>Guide: How to use this tool</strong><span aria-hidden="true">→</span></a></div>';
    $css = '<style>.smarttoolz-kb-guide{width:100%;margin:14px 0 0}.smarttoolz-kb-guide a{display:flex;align-items:center;gap:10px;width:100%;box-sizing:border-box;padding:11px 14px;border:1px solid rgba(99,91,255,.18);border-radius:12px;background:linear-gradient(135deg,#f1efff,#faf9ff);color:#5146d5;text-decoration:none;font-size:12px;font-weight:800}.smarttoolz-kb-guide a span:last-child{margin-left:auto;font-size:18px}.smarttoolz-kb-guide a:hover{transform:translateY(-1px);box-shadow:0 8px 22px rgba(99,91,255,.12)}</style>';
    $pattern = '/(<(?:div|section|article)\b[^>]*class=["\'][^"\']*\btool-seo-intro\b[^"\']*["\'][^>]*>)/i';
    if (preg_match($pattern, $html, $m, PREG_OFFSET_CAPTURE)) {
        $at = $m[0][1] + strlen($m[0][0]);
        return substr($html, 0, $at)."\n".$cta.$css."\n".substr($html, $at);
    }
    return $html;
});

<?php
declare(strict_types=1);

ob_start();
require_once __DIR__ . '/home.php';
$html = ob_get_clean();

$promoFile = __DIR__ . '/learning-hub-promo.php';
if (is_file($promoFile)) {
    ob_start();
    require $promoFile;
    $promo = ob_get_clean();
    $html = str_ireplace('</body>', $promo . "\n</body>", $html);
}

// Only the main index is extended for the new content destinations.
$youtubeUrl = getenv('SMARTTOOLZ_YOUTUBE_URL') ?: 'https://www.youtube.com/';
$quickLinks = '<div class="st-content-links" aria-label="SmartToolz content links"><a href="/knowledge-base/">📚 How-to Knowledge Base</a><a href="' . htmlspecialchars($youtubeUrl, ENT_QUOTES, 'UTF-8') . '" target="_blank" rel="noopener noreferrer">▶ YouTube</a></div><style>.st-content-links{max-width:1180px;margin:18px auto;padding:0 20px;display:flex;justify-content:flex-end;gap:10px;flex-wrap:wrap}.st-content-links a{display:inline-flex;align-items:center;gap:7px;padding:10px 14px;border:1px solid #e2e6ef;border-radius:12px;background:#fff;color:#172033;text-decoration:none;font-size:13px;font-weight:800;box-shadow:0 5px 18px rgba(20,30,60,.06)}.st-content-links a:hover{transform:translateY(-1px)}</style>';
$html = str_ireplace('</body>', $quickLinks . "\n</body>", $html);

// Three.js visual layer: isolated in a standalone module so PHP stays safe.
$threeJs = <<<'HTML'
<style>
#st-three-hero{position:absolute;inset:0;z-index:0;pointer-events:auto;overflow:hidden;border-radius:32px;opacity:.95}
#st-three-hero canvas{display:block;width:100%!important;height:100%!important}
.st-three-shell{position:relative;min-height:360px;margin:0 auto;max-width:1180px;padding:0 20px;pointer-events:none}
.st-three-shell>*{pointer-events:auto}
@media(max-width:760px){#st-three-hero{opacity:.42}.st-three-shell{min-height:300px}}
@media(prefers-reduced-motion:reduce){#st-three-hero{opacity:.28}}
</style>
<div class="st-three-shell" aria-hidden="true"><div id="st-three-hero"></div></div>
<script type="module" src="/smart-toolz/threejs-home.js"></script>
HTML;
$html = str_ireplace('</body>', $threeJs . "\n</body>", $html);

echo $html;

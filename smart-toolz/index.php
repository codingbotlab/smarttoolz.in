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

// Main homepage content destinations.
$youtubeUrl = getenv('SMARTTOOLZ_YOUTUBE_URL') ?: 'https://www.youtube.com/';

$sitePromo = '<div class="st-site-promo" role="region" aria-label="SmartToolz site promotion">'
    . '<div class="st-site-promo-inner">'
    . '<div class="st-site-promo-copy"><span class="st-site-promo-badge">✨ SMARTTOOLZ</span><strong>All your useful tools, learning &amp; guides in one place.</strong><span>Image • PDF • Developer • Text • Utility • Media tools and more.</span></div>'
    . '<div class="st-site-promo-links">'
    . '<a href="/smart-toolz/">🛠️ Explore Tools</a>'
    . '<a href="/knowledge-base/">📚 How-to Guides</a>'
    . '<a href="/learning-hub/">🎓 Learning Hub</a>'
    . '<a href="/Reddott-films/blogs/">🎬 Video Blog</a>'
    . '<a href="' . htmlspecialchars($youtubeUrl, ENT_QUOTES, 'UTF-8') . '" target="_blank" rel="noopener noreferrer">▶ YouTube</a>'
    . '</div>'
    . '</div>'
    . '</div>'
    . '<style>'
    . '.st-site-promo{width:100%;box-sizing:border-box;background:linear-gradient(135deg,#111827,#243b67 55%,#4f46e5);color:#fff;position:relative;z-index:20;box-shadow:0 5px 22px rgba(15,23,42,.16)}'
    . '.st-site-promo-inner{max-width:1240px;margin:0 auto;padding:11px 20px;display:flex;align-items:center;justify-content:space-between;gap:18px}'
    . '.st-site-promo-copy{display:flex;align-items:center;gap:10px;flex-wrap:wrap;font-size:12px;line-height:1.35}'
    . '.st-site-promo-copy strong{font-size:14px}'
    . '.st-site-promo-badge{font-size:11px;font-weight:900;letter-spacing:.7px;padding:5px 8px;border:1px solid rgba(255,255,255,.28);border-radius:999px;background:rgba(255,255,255,.1)}'
    . '.st-site-promo-links{display:flex;align-items:center;justify-content:flex-end;gap:7px;flex-wrap:wrap}'
    . '.st-site-promo-links a{display:inline-flex;align-items:center;gap:5px;padding:7px 10px;border:1px solid rgba(255,255,255,.22);border-radius:9px;background:rgba(255,255,255,.1);color:#fff;text-decoration:none;font-size:11px;font-weight:800;white-space:nowrap;transition:transform .15s ease,background .15s ease}'
    . '.st-site-promo-links a:hover{transform:translateY(-1px);background:rgba(255,255,255,.18)}'
    . '@media(max-width:900px){.st-site-promo-inner{align-items:flex-start;flex-direction:column;gap:9px}.st-site-promo-links{justify-content:flex-start}}'
    . '@media(max-width:560px){.st-site-promo-inner{padding:10px 14px}.st-site-promo-copy span:last-child{display:none}.st-site-promo-links{width:100%;overflow-x:auto;flex-wrap:nowrap;padding-bottom:2px}.st-site-promo-links a{font-size:10px;padding:7px 9px}}'
    . '</style>';

// Keep the promotion at the very top of the homepage, immediately after <body>.
$html = preg_replace('/(<body\\b[^>]*>)/i', '$1' . $sitePromo, $html, 1) ?? $html;

echo $html;

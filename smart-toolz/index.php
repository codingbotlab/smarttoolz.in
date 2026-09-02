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

$youtubeUrl = getenv('SMARTTOOLZ_YOUTUBE_URL') ?: 'https://www.youtube.com/';

$sitePromo = '<div class="st-site-promo" role="region" aria-label="SmartToolz site promotion">'
    . '<div class="st-site-promo-inner">'
    . '<div class="st-site-promo-copy"><span class="st-site-promo-badge"><span class="material-symbols-rounded">auto_awesome</span> SMARTTOOLZ</span><strong>All your useful tools, learning &amp; guides in one place.</strong><span>Image • PDF • Developer • Text • Utility • Media tools and more.</span></div>'
    . '<div class="st-site-promo-links">'
    . '<a href="/smart-toolz/"><span class="material-symbols-rounded">build</span> Explore Tools</a>'
    . '<a href="/knowledge-base/"><span class="material-symbols-rounded">menu_book</span> How-to Guides</a>'
    . '<a href="/learning-hub/"><span class="material-symbols-rounded">school</span> Learning Hub</a>'
    . '<a href="/Reddott-films/blogs/"><span class="material-symbols-rounded">movie</span> Video Blog</a>'
    . '<a href="' . htmlspecialchars($youtubeUrl, ENT_QUOTES, 'UTF-8') . '" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-youtube"></i> YouTube</a>'
    . '</div>'
    . '</div>'
    . '</div>'
    . '<style>'
    . '.st-site-promo{width:100%;box-sizing:border-box;background:linear-gradient(135deg,#111827,#243b67 55%,#4f46e5);color:#fff;position:relative;z-index:20;box-shadow:0 5px 22px rgba(15,23,42,.16)}'
    . '.st-site-promo-inner{max-width:1240px;margin:0 auto;padding:11px 20px;display:flex;align-items:center;justify-content:space-between;gap:18px}'
    . '.st-site-promo-copy{display:flex;align-items:center;gap:10px;flex-wrap:wrap;font-size:12px;line-height:1.35}'
    . '.st-site-promo-copy strong{font-size:14px}'
    . '.st-site-promo-badge{display:inline-flex;align-items:center;gap:5px;font-size:11px;font-weight:900;letter-spacing:.7px;padding:5px 8px;border:1px solid rgba(255,255,255,.28);border-radius:999px;background:rgba(255,255,255,.1)}'
    . '.st-site-promo-badge .material-symbols-rounded{font-size:15px}'
    . '.st-site-promo-links{display:flex;align-items:center;justify-content:flex-end;gap:7px;flex-wrap:wrap}'
    . '.st-site-promo-links a{display:inline-flex;align-items:center;gap:5px;padding:7px 10px;border:1px solid rgba(255,255,255,.22);border-radius:9px;background:rgba(255,255,255,.1);color:#fff;text-decoration:none;font-size:11px;font-weight:800;white-space:nowrap;transition:transform .15s ease,background .15s ease}'
    . '.st-site-promo-links a:hover{transform:translateY(-1px);background:rgba(255,255,255,.18)}'
    . '.st-site-promo-links .material-symbols-rounded{font-size:15px}'
    . '.st-site-promo-links .fa-brands{font-size:13px}'
    . '.st-hero-tool{width:min(560px,100%);margin:18px auto 0;display:flex;align-items:center;gap:13px;padding:12px 14px;border:1px solid rgba(99,91,255,.16);border-radius:16px;background:rgba(255,255,255,.9);box-shadow:0 15px 35px rgba(30,35,80,.1);color:#172033;text-decoration:none;transition:transform .18s ease,box-shadow .18s ease,border-color .18s ease}'
    . '.st-hero-tool:hover{transform:translateY(-3px);box-shadow:0 20px 45px rgba(30,35,80,.14);border-color:#cfcaff}'
    . '.st-hero-tool-icon{width:48px;height:48px;flex:0 0 48px;display:grid;place-items:center;border-radius:13px;background:linear-gradient(135deg,#635bff,#8b7cff);color:#fff}'
    . '.st-hero-tool-icon .material-symbols-rounded{font-size:25px}'
    . '.st-hero-tool-copy{min-width:0;flex:1}'
    . '.st-hero-tool-copy small{display:block;color:#635bff;font-size:9px;font-weight:900;letter-spacing:1px;margin-bottom:3px}'
    . '.st-hero-tool-copy strong{display:block;font-size:14px;line-height:1.25}'
    . '.st-hero-tool-copy span{display:block;margin-top:3px;color:#707b8e;font-size:10.5px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}'
    . '.st-hero-tool-arrow{color:#635bff;display:grid;place-items:center}'
    . '@media(max-width:900px){.st-site-promo-inner{align-items:flex-start;flex-direction:column;gap:9px}.st-site-promo-links{justify-content:flex-start}}'
    . '@media(max-width:560px){.st-site-promo-inner{padding:10px 14px}.st-site-promo-copy span:last-child{display:none}.st-site-promo-links{width:100%;overflow-x:auto;flex-wrap:nowrap;padding-bottom:2px}.st-site-promo-links a{font-size:10px;padding:7px 9px}.st-hero-tool{margin-top:14px;padding:10px 11px}.st-hero-tool-icon{width:43px;height:43px;flex-basis:43px}.st-hero-tool-copy strong{font-size:13px}.st-hero-tool-copy span{font-size:9.5px}}'
    . '</style>';

$html = preg_replace('/(<body\\b[^>]*>)/i', '$1' . $sitePromo, $html, 1) ?? $html;

$heroTool = '<a class="st-hero-tool" href="/smart-toolz/tools/image-background-remover.php" aria-label="Open Image Background Remover">'
    . '<span class="st-hero-tool-icon"><span class="material-symbols-rounded">content_cut</span></span>'
    . '<span class="st-hero-tool-copy"><small>FEATURED TOOL</small><strong>Image Background Remover</strong><span>Remove backgrounds from images in a few clicks.</span></span>'
    . '<span class="st-hero-tool-arrow"><span class="material-symbols-rounded">arrow_forward</span></span>'
    . '</a>';

$heroImg = '<img class="hero-art" src="/smart-toolz/assets/home/hero-tools.svg" alt="SmartToolz tools illustration">';
if (strpos($html, $heroImg) !== false) {
    $html = str_replace($heroImg, $heroImg . $heroTool, $html);
}

echo $html;

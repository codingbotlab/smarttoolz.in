<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/bootstrap.php';
require_once __DIR__ . '/../lib/blog.php';
require_once __DIR__ . '/../lib/tools.php';

$slug = isset($_GET['slug']) ? trim((string) $_GET['slug']) : '';
if (!preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $slug)) {
    http_response_code(404);
    $_GET['code'] = 404;
    require dirname(__DIR__) . '/error.php';
    exit;
}

$file = __DIR__ . '/' . $slug . '.php';
if (!is_file($file) || basename($file) === 'article.php' || basename($file) === 'index.php') {
    http_response_code(404);
    $_GET['code'] = 404;
    require dirname(__DIR__) . '/error.php';
    exit;
}

ob_start();
require $file;
$html = ob_get_clean();

preg_match('/<title>(.*?)<\/title>/is', $html, $titleMatch);
$title = trim(strip_tags($titleMatch[1] ?? ucwords(str_replace('-', ' ', $slug))));

preg_match('/<span class="eyebrow">(.*?)<\/span>/is', $html, $categoryMatch);
$category = trim(strip_tags($categoryMatch[1] ?? 'SMARTTOOLZ GUIDE'));

preg_match('/<p class="lead text-muted">(.*?)<\/p>/is', $html, $introMatch);
$intro = trim(strip_tags($introMatch[1] ?? 'Practical tips and useful information from SmartToolz.'));

preg_match('/<article[^>]*>(.*?)<\/article>/is', $html, $articleMatch);
$articleBody = $articleMatch[1] ?? '<p>This article is currently unavailable.</p>';
$articleBody = preg_replace('/^\s*<span class="eyebrow">.*?<\/span>\s*/is', '', $articleBody, 1);
$articleBody = preg_replace('/^\s*<h1[^>]*>.*?<\/h1>\s*/is', '', $articleBody, 1);
$articleBody = preg_replace('/^\s*<p class="lead text-muted">.*?<\/p>\s*/is', '', $articleBody, 1);

$thumbnail = '/blog/' . $slug . '.svg';
$thumbFile = __DIR__ . '/' . $slug . '.svg';
if (!is_file($thumbFile)) $thumbnail = '/assets/img/blog-default.svg';

$posts = smarttoolz_blogs();
$latest = array_values(array_filter($posts, static fn($p) => $p['slug'] !== $slug));
$latest = array_slice($latest, 0, 5);
$tools = array_slice(smarttoolz_tools(), 0, 5);

$hero = '<main class="blog-article-page">'
    . '<div class="article-breadcrumb"><a href="/">Home</a><span>›</span><a href="/blog/">Blog</a><span>›</span><span>' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '</span></div>'
    . '<section class="article-hero">'
    . '<div class="article-hero-copy"><span class="eyebrow">' . htmlspecialchars($category, ENT_QUOTES, 'UTF-8') . '</span>'
    . '<h1>' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '</h1>'
    . '<p>' . htmlspecialchars($intro, ENT_QUOTES, 'UTF-8') . '</p>'
    . '<div class="article-meta"><span>📖 SmartToolz Guide</span><span>✓ Practical &amp; Free</span></div></div>'
    . '<div class="article-hero-media"><img src="' . htmlspecialchars($thumbnail, ENT_QUOTES, 'UTF-8') . '" width="1200" height="630" fetchpriority="high" alt="' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '"></div>'
    . '</section>'
    . '<div class="article-layout"><article class="article-content">' . $articleBody . '</article>'
    . '<aside class="article-sidebar">'
    . '<section class="sidebar-card sidebar-cta"><span class="sidebar-icon">✦</span><h2>Explore SmartToolz</h2><p>Use our free browser-based tools to get everyday digital tasks done quickly.</p><a href="/tools/" class="sidebar-button">Browse All Tools →</a></section>'
    . '<section class="sidebar-card"><h2>Latest Articles</h2><div class="sidebar-posts">';
foreach ($latest as $post) {
    $hero .= '<a class="sidebar-post" href="' . htmlspecialchars($post['url'], ENT_QUOTES, 'UTF-8') . '"><img src="' . htmlspecialchars($post['thumbnail'], ENT_QUOTES, 'UTF-8') . '" width="160" height="84" loading="lazy" alt=""><span>' . htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8') . '</span></a>';
}
$hero .= '</div><a class="sidebar-more" href="/blog/">View all articles →</a></section>'
    . '<section class="sidebar-card"><h2>Useful Tools</h2><div class="sidebar-tools">';
foreach ($tools as $tool) {
    $hero .= '<a href="' . htmlspecialchars($tool['url'], ENT_QUOTES, 'UTF-8') . '"><span>' . htmlspecialchars($tool['name'], ENT_QUOTES, 'UTF-8') . '</span><b>→</b></a>';
}
$hero .= '</div></section></aside></div></main>';

$html = preg_replace('/<main\s+class="container py-5"[^>]*>.*?<\/main>/is', $hero, $html, 1);
if ($html === null) $html = '';

$css = <<<'CSS'
<style>
.blog-article-page{width:min(1180px,calc(100% - 32px));margin:0 auto 80px;color:#111936}.article-breadcrumb{display:flex;gap:9px;align-items:center;padding:18px 0 14px;font-size:11px;color:#7a849b;white-space:nowrap;overflow:hidden}.article-breadcrumb a{color:#5360d8;text-decoration:none;font-weight:700}.article-breadcrumb span:last-child{overflow:hidden;text-overflow:ellipsis}.article-hero{display:grid;grid-template-columns:1.02fr .98fr;min-height:390px;overflow:hidden;border:1px solid #e2e8f5;border-radius:24px;background:linear-gradient(135deg,#f3f5ff,#edf7ff);box-shadow:0 18px 55px rgba(30,45,90,.08);margin-bottom:22px}.article-hero-copy{padding:52px 48px;display:flex;flex-direction:column;justify-content:center}.article-hero-copy h1{font-size:clamp(36px,4vw,56px);line-height:1.05;letter-spacing:-2.5px;margin:17px 0 15px}.article-hero-copy>p{font-size:16px;line-height:1.75;color:#5f6b83;max-width:620px;margin:0}.eyebrow{display:inline-flex;width:max-content;padding:7px 11px;border-radius:999px;background:#eeebff;color:#5a45f5;font-size:9px;font-weight:900;letter-spacing:1.5px}.article-meta{display:flex;gap:18px;flex-wrap:wrap;margin-top:25px;font-size:11px;color:#68738a;font-weight:700}.article-hero-media{min-height:390px;padding:22px;display:flex;align-items:center;justify-content:center}.article-hero-media img{width:100%;height:100%;max-height:346px;object-fit:cover;border-radius:18px;box-shadow:0 15px 35px rgba(39,55,100,.13);background:#fff}.article-layout{display:grid;grid-template-columns:minmax(0,1fr) 320px;gap:22px;align-items:start}.article-content{background:#fff;border:1px solid #e5e9f2;border-radius:22px;padding:42px 48px;box-shadow:0 12px 40px rgba(16,24,40,.045);font-size:15px;line-height:1.8}.article-content h2{font-size:27px;line-height:1.25;letter-spacing:-.7px;margin:34px 0 12px}.article-content h2:first-child{margin-top:0}.article-content p{margin:0 0 18px;color:#33405d}.article-content ul,.article-content ol{padding-left:24px;color:#33405d;margin-bottom:22px}.article-content li{margin:7px 0}.article-content a{color:#5042dc;font-weight:700}.article-content hr{border:0;border-top:1px solid #e8ebf3;margin:30px 0}.article-sidebar{display:flex;flex-direction:column;gap:18px;position:sticky;top:85px}.sidebar-card{background:#fff;border:1px solid #e3e8f2;border-radius:19px;padding:22px;box-shadow:0 10px 30px rgba(16,24,40,.045)}.sidebar-card h2{font-size:18px;margin:0 0 12px;letter-spacing:-.3px}.sidebar-card p{font-size:12px;line-height:1.65;color:#68738a;margin:0 0 17px}.sidebar-cta{background:linear-gradient(145deg,#f1efff,#f7faff)}.sidebar-icon{display:grid;place-items:center;width:38px;height:38px;border-radius:11px;background:#e8e4ff;color:#5945f5;margin-bottom:12px}.sidebar-button{display:block;text-align:center;padding:12px 14px;border-radius:11px;background:#5740f4;color:#fff!important;text-decoration:none;font-size:12px;font-weight:800;box-shadow:0 8px 20px rgba(87,64,244,.22)}.sidebar-posts{display:flex;flex-direction:column;gap:13px}.sidebar-post{display:grid;grid-template-columns:64px 1fr;gap:11px;align-items:center;text-decoration:none;color:#17213f}.sidebar-post img{width:64px;height:42px;object-fit:cover;border-radius:8px;border:1px solid #e5e8f0}.sidebar-post span{font-size:11px;line-height:1.4;font-weight:750}.sidebar-more{display:block;margin-top:17px;padding-top:14px;border-top:1px solid #edf0f5;text-decoration:none;font-size:11px;font-weight:800}.sidebar-tools{display:flex;flex-direction:column}.sidebar-tools a{display:flex;justify-content:space-between;gap:12px;padding:11px 0;border-bottom:1px solid #eef0f5;text-decoration:none;color:#27314b;font-size:12px}.sidebar-tools a:last-child{border-bottom:0}.sidebar-tools b{color:#5b4bf2}@media(max-width:900px){.article-hero{grid-template-columns:1fr}.article-hero-media{min-height:280px;padding:0 25px 25px}.article-hero-media img{max-height:300px}.article-layout{grid-template-columns:1fr}.article-sidebar{position:static;display:grid;grid-template-columns:1fr 1fr}.sidebar-cta{grid-column:1/-1}}@media(max-width:600px){.blog-article-page{width:calc(100% - 20px)}.article-breadcrumb{padding:14px 0}.article-hero-copy{padding:34px 25px 25px}.article-hero-copy h1{letter-spacing:-1.8px}.article-hero-copy>p{font-size:14px}.article-hero-media{min-height:210px;padding:0 15px 15px}.article-content{padding:28px 22px;font-size:14px}.article-content h2{font-size:23px;margin-top:29px}.article-sidebar{display:flex}}
</style>
CSS;

$html = preg_replace('/<\/head>/i', $css . '</head>', $html, 1) ?? $html;
echo $html;

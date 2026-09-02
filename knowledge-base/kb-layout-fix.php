<?php
// Knowledge Base compatibility + visual walkthrough layer.
// This file is auto-prepended by knowledge-base/.user.ini.

if (PHP_SAPI !== 'cli') {
    $requestPath = (string)(parse_url((string)($_SERVER['REQUEST_URI'] ?? ''), PHP_URL_PATH) ?? '');
    $toolSlug = trim((string)($_GET['tool'] ?? ''));
    $isVisualEndpoint = str_ends_with($requestPath, '/knowledge-base/visual.php');
    $isGuideRequest = $toolSlug !== '' && str_contains($requestPath, '/knowledge-base/') && !$isVisualEndpoint;

    if ($isGuideRequest) {
        ob_start(static function (string $html) use ($toolSlug): string {
            $visualUrl = '/knowledge-base/visual.php?tool=' . rawurlencode($toolSlug);
            $block = '<section class="kb-visual-walkthrough" aria-label="Visual walkthrough">'
                . '<div class="kb-visual-head"><div><span class="kb-visual-kicker">VISUAL WALKTHROUGH</span><h2>See the exact workflow</h2><p>Use this visual as a quick reference while following the steps on the tool page.</p></div>'
                . '<a href="' . htmlspecialchars($visualUrl, ENT_QUOTES, 'UTF-8') . '" target="_blank" rel="noopener">Open visual</a></div>'
                . '<div class="kb-visual-frame"><img src="' . htmlspecialchars($visualUrl, ENT_QUOTES, 'UTF-8') . '" alt="SmartToolz visual walkthrough" loading="lazy"></div>'
                . '</section>';

            if (stripos($html, 'kb-visual-walkthrough') !== false) {
                return $html;
            }
            if (stripos($html, '</main>') !== false) {
                return preg_replace('~</main>~i', $block . '</main>', $html, 1) ?? ($html . $block);
            }
            if (stripos($html, '</body>') !== false) {
                return preg_replace('~</body>~i', $block . '</body>', $html, 1) ?? ($html . $block);
            }
            return $html . $block;
        });
    }
}
?>
<style>
.layout{display:grid!important;grid-template-columns:minmax(245px,265px) minmax(0,1fr)!important;align-items:start!important;width:min(1420px,calc(100% - 28px))!important;margin:22px auto!important;gap:20px!important}
.side{width:auto!important;min-width:0!important}
.main{display:block!important;width:auto!important;min-width:0!important;max-width:none!important}
.hero,.grid2,.steps,.tips,.mistakes,.faq,.related{width:100%!important;max-width:none!important}
.grid2{display:grid!important;grid-template-columns:minmax(0,1fr) minmax(0,1fr)!important}
.related-grid{width:100%!important}
.kb-visual-walkthrough{margin:24px 0;padding:20px;border:1px solid #e4e8f0;border-radius:20px;background:#fff;box-shadow:0 10px 28px rgba(23,32,51,.06)}
.kb-visual-head{display:flex;align-items:flex-start;justify-content:space-between;gap:18px;margin-bottom:16px}
.kb-visual-kicker{display:inline-block;font-size:11px;font-weight:800;letter-spacing:.12em;color:#635bff;margin-bottom:5px}
.kb-visual-head h2{margin:0 0 5px;font-size:24px;line-height:1.2;color:#172033}
.kb-visual-head p{margin:0;color:#697487;font-size:14px}
.kb-visual-head a{flex:0 0 auto;text-decoration:none;border:1px solid #dfe3ec;border-radius:10px;padding:9px 13px;color:#172033;font-weight:700;font-size:13px;background:#fafbff}
.kb-visual-frame{overflow:hidden;border-radius:16px;border:1px solid #e1e6ef;background:#f6f8fc}
.kb-visual-frame img{display:block;width:100%;height:auto}
@media(max-width:700px){
  .layout{display:block!important;width:calc(100% - 20px)!important}
  .side{position:fixed!important;z-index:1200!important;left:12px!important;top:76px!important;width:min(320px,calc(100% - 24px))!important;height:calc(100vh - 88px)!important;max-height:none!important;transform:translateX(-120%)!important;transition:.22s!important}
  .side.open{transform:translateX(0)!important}
  .grid2{grid-template-columns:1fr!important}
  .kb-visual-walkthrough{padding:14px;border-radius:16px}
  .kb-visual-head{display:block}
  .kb-visual-head a{display:inline-block;margin-top:12px}
}
</style>

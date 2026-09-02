<?php
// Knowledge Base compatibility + article navigation layer.
// This file is auto-prepended by knowledge-base/.user.ini.

if (PHP_SAPI !== 'cli') {
    $requestPath = (string)(parse_url((string)($_SERVER['REQUEST_URI'] ?? ''), PHP_URL_PATH) ?? '');
    $toolSlug = trim((string)($_GET['tool'] ?? ''));
    $isVisualEndpoint = str_ends_with($requestPath, '/knowledge-base/visual.php');
    $isGuideRequest = $toolSlug !== '' && str_contains($requestPath, '/knowledge-base/') && !$isVisualEndpoint;

    if ($isGuideRequest) {
        ob_start(static function (string $html) use ($toolSlug): string {
            $guideUrl = '/knowledge-base/' . rawurlencode($toolSlug) . '/article/';
            $toolUrl = '/smart-toolz/tools/' . rawurlencode($toolSlug) . '.php';
            $kbUrl = '/knowledge-base/';
            $visualUrl = '/knowledge-base/visual.php?tool=' . rawurlencode($toolSlug);

            $nav = '<section class="kb-article-links" aria-label="Guide navigation">'
                . '<div class="kb-link-card kb-link-primary">'
                . '<span class="kb-link-icon">▣</span><div><strong>Detailed Guide</strong><small>Read the complete step-by-step instructions for this tool.</small></div>'
                . '<a href="' . htmlspecialchars($guideUrl, ENT_QUOTES, 'UTF-8') . '">Open guide →</a></div>'
                . '<div class="kb-link-card">'
                . '<span class="kb-link-icon">↗</span><div><strong>Open Tool</strong><small>Jump directly to the tool and follow the guide alongside it.</small></div>'
                . '<a href="' . htmlspecialchars($toolUrl, ENT_QUOTES, 'UTF-8') . '">Use tool →</a></div>'
                . '<div class="kb-link-card">'
                . '<span class="kb-link-icon">◈</span><div><strong>Reference Tools</strong><small>Browse related and other SmartToolz guides from the Knowledge Base.</small></div>'
                . '<a href="' . htmlspecialchars($kbUrl, ENT_QUOTES, 'UTF-8') . '">Browse guides →</a></div>'
                . '<div class="kb-link-card">'
                . '<span class="kb-link-icon">▤</span><div><strong>Visual Walkthrough</strong><small>Quick visual reference for the tool workflow.</small></div>'
                . '<a href="' . htmlspecialchars($visualUrl, ENT_QUOTES, 'UTF-8') . '" target="_blank" rel="noopener">View visual →</a></div>'
                . '</section>';

            if (stripos($html, 'kb-article-links') !== false) {
                return $html;
            }
            if (stripos($html, '</main>') !== false) {
                return preg_replace('~</main>~i', $nav . '</main>', $html, 1) ?? ($html . $nav);
            }
            if (stripos($html, '</body>') !== false) {
                return preg_replace('~</body>~i', $nav . '</body>', $html, 1) ?? ($html . $nav);
            }
            return $html . $nav;
        });
    }
}
?>
<style>
/* Global KB width override: keep the existing components/design, remove the centered max-width shell. */
html,body{width:100%!important;max-width:none!important}
.kb-nav,.kb-main,.kb-footer,.footer-inner,.footer-bottom,.layout,.main,.footer,.bottom{width:100%!important;max-width:none!important}
.kb-nav{margin-left:0!important;margin-right:0!important}
.kb-layout,.layout{margin-left:0!important;margin-right:0!important}
.layout{display:grid!important;grid-template-columns:minmax(245px,265px) minmax(0,1fr)!important;align-items:start!important;padding-left:28px!important;padding-right:28px!important;gap:20px!important}
.side{width:auto!important;min-width:0!important}.main{display:block!important;min-width:0!important}
.hero,.grid2,.steps,.tips,.mistakes,.faq,.related{width:100%!important;max-width:none!important}.grid2{display:grid!important;grid-template-columns:minmax(0,1fr) minmax(0,1fr)!important}.related-grid{width:100%!important}
.kb-article-links{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px;margin:24px 0;padding:16px;border:1px solid #e1e6ef;border-radius:20px;background:#f8f9fd}
.kb-link-card{display:flex;align-items:center;gap:12px;min-width:0;padding:14px;background:#fff;border:1px solid #e2e6ef;border-radius:14px}.kb-link-card.kb-link-primary{border-color:#d6d1ff;background:#fbfaff}
.kb-link-icon{flex:0 0 36px;width:36px;height:36px;display:grid;place-items:center;border-radius:10px;background:#eeedff;color:#635bff;font-weight:900;font-size:17px}
.kb-link-card div{min-width:0;flex:1}.kb-link-card strong{display:block;color:#172033;font-size:13px}.kb-link-card small{display:block;color:#697487;font-size:11px;line-height:1.45;margin-top:3px}.kb-link-card a{flex:0 0 auto;text-decoration:none;color:#635bff;font-size:11px;font-weight:900;white-space:nowrap}
@media(max-width:700px){.layout{display:block!important;width:100%!important;margin-left:0!important;margin-right:0!important;padding-left:10px!important;padding-right:10px!important}.side{position:fixed!important;z-index:1200!important;left:12px!important;top:76px!important;width:min(320px,calc(100% - 24px))!important;height:calc(100vh - 88px)!important;max-height:none!important;transform:translateX(-120%)!important;transition:.22s!important}.side.open{transform:translateX(0)!important}.grid2{grid-template-columns:1fr!important}.kb-article-links{grid-template-columns:1fr;padding:12px}.kb-link-card{align-items:flex-start}.kb-link-card a{margin-top:4px}}
</style>

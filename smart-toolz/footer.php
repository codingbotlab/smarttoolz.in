<?php
/* Shared SmartToolz support footer. Safe to include from any page. */
$stFooterToolSlug = trim((string)($_GET['tool'] ?? ''));
$stFooterToolName = trim((string)($_GET['name'] ?? ''));
$stFooterToolUrl = trim((string)($_GET['url'] ?? ''));
if ($stFooterToolSlug === '' && isset($_SERVER['SCRIPT_NAME'])) {
    $stFooterPath = (string)$_SERVER['SCRIPT_NAME'];
    if (str_contains($stFooterPath, '/smart-toolz/tools/')) {
        $stFooterToolSlug = basename($stFooterPath, '.php');
    }
}
if ($stFooterToolName === '') {
    $stFooterToolName = $stFooterToolSlug !== ''
        ? ucwords(str_replace(['-', '_'], ' ', $stFooterToolSlug))
        : 'SmartToolz';
}
if ($stFooterToolUrl === '' && $stFooterToolSlug !== '') {
    $stFooterToolUrl = '/smart-toolz/tools/' . rawurlencode($stFooterToolSlug) . '.php';
}
$stFooterReport = '/smart-toolz/report-tool.php';
$stFooterRequest = '/smart-toolz/request-tool.php';
if ($stFooterToolSlug !== '') {
    $stFooterQuery = http_build_query([
        'tool' => $stFooterToolSlug,
        'name' => $stFooterToolName,
        'url'  => $stFooterToolUrl,
    ]);
    $stFooterReport .= '?' . $stFooterQuery;
    $stFooterRequest .= '?' . $stFooterQuery;
}
?>
<section class="st-feedback-strip" aria-label="SmartToolz feedback">
  <div class="st-feedback-wrap">
    <div class="st-feedback-head">
      <div>
        <span class="st-feedback-kicker">HELP SMARTTOOLZ GET BETTER</span>
        <h2>Found a problem or missing a tool?</h2>
        <p>Tell us what you need. Your feedback goes directly into our support queue.</p>
      </div>
    </div>
    <div class="st-feedback-actions">
      <a class="st-feedback-card st-report" href="<?= htmlspecialchars($stFooterReport, ENT_QUOTES, 'UTF-8') ?>">
        <span class="st-feedback-icon"><span class="material-symbols-rounded">bug_report</span></span>
        <span class="st-feedback-copy"><strong>Report a Problem</strong><small>Tool not working, wrong result, error or upload issue</small></span>
        <span class="st-feedback-arrow material-symbols-rounded">arrow_forward</span>
      </a>
      <a class="st-feedback-card st-request" href="<?= htmlspecialchars($stFooterRequest, ENT_QUOTES, 'UTF-8') ?>">
        <span class="st-feedback-icon"><span class="material-symbols-rounded">lightbulb</span></span>
        <span class="st-feedback-copy"><strong>Request a New Tool</strong><small>Tell us what you want SmartToolz to build next</small></span>
        <span class="st-feedback-arrow material-symbols-rounded">arrow_forward</span>
      </a>
    </div>
  </div>
</section>
<style>
.st-feedback-strip{width:100%;margin:56px 0 0;padding:0 0 28px;background:#f7f9fd}
.st-feedback-wrap{width:min(1200px,calc(100% - 28px));margin:auto}
.st-feedback-head{margin-bottom:12px}
.st-feedback-kicker{display:inline-flex;align-items:center;padding:6px 9px;border-radius:999px;background:#eeedff;color:#635bff;font-size:9px;font-weight:900;letter-spacing:.55px}
.st-feedback-head h2{margin:9px 0 4px;color:#172033;font-size:23px;line-height:1.2;letter-spacing:-.55px}
.st-feedback-head p{margin:0;color:#7a8393;font-size:11px;line-height:1.6}
.st-feedback-actions{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.st-feedback-card{display:flex;align-items:center;gap:12px;min-height:72px;padding:12px 14px;border:1px solid #e3e6ee;border-radius:15px;background:#fff;text-decoration:none;box-shadow:0 8px 24px rgba(30,35,80,.035);transition:transform .18s ease,box-shadow .18s ease,border-color .18s ease}
.st-feedback-card:hover{transform:translateY(-2px);box-shadow:0 14px 30px rgba(30,35,80,.08);border-color:#d5d0ff}
.st-feedback-card.st-request{background:linear-gradient(135deg,#fff 0,#f8f7ff 100%)}
.st-feedback-icon{width:40px;height:40px;flex:0 0 40px;display:grid;place-items:center;border-radius:12px}
.st-report .st-feedback-icon{background:#fff0f1;color:#e5484d}
.st-request .st-feedback-icon{background:#efedff;color:#635bff}
.st-feedback-icon .material-symbols-rounded{font-size:21px}
.st-feedback-copy{min-width:0;display:flex;flex-direction:column;gap:4px}
.st-feedback-copy strong{color:#172033;font-size:12px;font-weight:900}
.st-feedback-copy small{color:#7a8393;font-size:9px;line-height:1.45}
.st-feedback-arrow{margin-left:auto;color:#8791a2;font-size:19px}
@media(max-width:650px){.st-feedback-strip{margin-top:38px}.st-feedback-actions{grid-template-columns:1fr}.st-feedback-head h2{font-size:20px}.st-feedback-card{min-height:68px}}
</style>

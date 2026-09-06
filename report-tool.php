<?php
declare(strict_types=1);
require_once __DIR__ . '/lib/feedback.php';

$toolSlug = trim((string)($_GET['tool'] ?? $_POST['tool_slug'] ?? ''));
$toolName = trim((string)($_GET['name'] ?? $_POST['tool_name'] ?? ''));
$toolUrl = trim((string)($_GET['url'] ?? $_POST['tool_url'] ?? ''));
if ($toolSlug === '' && isset($_SERVER['HTTP_REFERER'])) {
    $refPath = (string)(parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH) ?? '');
    if (str_contains($refPath, '/smart-toolz/tools/')) $toolSlug = basename($refPath, '.php');
}
if ($toolName === '') $toolName = ucwords(str_replace(['-', '_'], ' ', $toolSlug ?: 'SmartToolz tool'));
if ($toolUrl === '') $toolUrl = $toolSlug !== '' ? '/smart-toolz/tools/' . rawurlencode($toolSlug) . '.php' : '';

[$defaultName, $defaultEmail] = smarttoolz_feedback_user_context();
$message = '';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim((string)($_POST['name'] ?? $defaultName));
    $email = trim((string)($_POST['email'] ?? $defaultEmail));
    $issueType = trim((string)($_POST['issue_type'] ?? 'Tool not working'));
    $description = trim((string)($_POST['description'] ?? ''));
    $steps = trim((string)($_POST['steps'] ?? ''));
    $honeypot = trim((string)($_POST['website'] ?? ''));

    if ($honeypot !== '') {
        $error = 'Something went wrong. Please try again.';
    } elseif (!smarttoolz_feedback_rate_ok('issue', 300)) {
        $error = 'Please wait a few minutes before sending another report.';
    } elseif ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif ($description === '' || mb_strlen($description) < 8) {
        $error = 'Please describe what went wrong.';
    } else {
        try {
            $env = json_encode([
                'user_agent' => substr((string)($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 400),
                'language' => (string)($_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '')
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            $uid = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;
            $pdo = smarttoolz_db();
            $pdo->prepare('INSERT INTO tool_issue_reports(user_id,name,email,tool_name,tool_slug,tool_url,issue_type,description,steps,environment,ip_hash) VALUES(?,?,?,?,?,?,?,?,?,?,?)')
                ->execute([$uid ?: null, $name !== '' ? $name : null, $email !== '' ? $email : null, $toolName, $toolSlug ?: 'unknown', $toolUrl ?: null, $issueType, $description, $steps !== '' ? $steps : null, $env, ($_SERVER['REMOTE_ADDR'] ?? '') !== '' ? hash('sha256', (string)$_SERVER['REMOTE_ADDR']) : null]);
            $id = (int)$pdo->lastInsertId();
            $body = "New SmartToolz tool issue report\n\nReport #{$id}\nTool: {$toolName}\nSlug: {$toolSlug}\nURL: {$toolUrl}\nIssue type: {$issueType}\nName: " . ($name ?: 'Not provided') . "\nEmail: " . ($email ?: 'Not provided') . "\n\nWhat happened:\n{$description}\n\nSteps / details:\n" . ($steps ?: 'Not provided') . "\n\nEnvironment:\n{$env}\n";
            $sent = smarttoolz_feedback_mail('[SmartToolz] Tool not working — ' . $toolName, $body, $email ?: null);
            $message = $sent ? 'Report received! We have saved it and emailed the SmartToolz team.' : 'Report received and saved. Email notification could not be sent by the server right now.';
            $_POST = [];
        } catch (Throwable $e) {
            error_log('SmartToolz issue report: ' . $e->getMessage());
            $error = 'We could not save the report right now. Please try again.';
        }
    }
}
?>
<?php require_once __DIR__ . '/header.php'; ?>
<style>
.feedback-page{width:min(980px,calc(100% - 28px));margin:38px auto 70px}.feedback-hero{padding:34px;border:1px solid #e4e7ef;border-radius:24px;background:radial-gradient(circle at 90% 0,#eceaff 0,transparent 35%),linear-gradient(135deg,#fff,#f7f7ff);box-shadow:0 18px 50px rgba(30,35,80,.06)}.eyebrow{display:inline-flex;gap:7px;align-items:center;padding:7px 10px;border-radius:999px;background:#eeedff;color:#635bff;font-size:10px;font-weight:900;letter-spacing:.5px}.feedback-hero h1{margin:13px 0 8px;font-size:clamp(30px,5vw,48px);letter-spacing:-1.8px}.feedback-hero p{margin:0;color:#707b8e;line-height:1.7;font-size:14px}.tool-pill{display:inline-flex;align-items:center;gap:7px;margin-top:18px;padding:9px 12px;border:1px solid #e2e5ed;background:#fff;border-radius:12px;color:#172033;font-size:12px;font-weight:800}.form-card{margin-top:18px;background:#fff;border:1px solid #e4e7ef;border-radius:22px;padding:26px;box-shadow:0 18px 50px rgba(30,35,80,.05)}.row{display:grid;grid-template-columns:1fr 1fr;gap:14px}.field{display:grid;gap:7px;margin-bottom:15px}.field label{font-size:11px;font-weight:850;color:#394459}.field input,.field select,.field textarea{width:100%;box-sizing:border-box;border:1px solid #dfe3eb;border-radius:12px;padding:12px 13px;font:inherit;font-size:13px;outline:none;background:#fff}.field textarea{min-height:130px;resize:vertical;line-height:1.6}.field input:focus,.field select:focus,.field textarea:focus{border-color:#aca6ff;box-shadow:0 0 0 4px #635bff12}.actions{display:flex;align-items:center;gap:10px;flex-wrap:wrap}.btn{display:inline-flex;align-items:center;gap:7px;border:1px solid #dfe3eb;border-radius:12px;padding:12px 15px;font-size:12px;font-weight:850;text-decoration:none;cursor:pointer}.btn.primary{border-color:transparent;background:linear-gradient(135deg,#635bff,#846fff);color:#fff;box-shadow:0 9px 22px #635bff25}.btn.ghost{background:#fff;color:#596477}.notice{margin-bottom:16px;padding:13px 15px;border-radius:13px;font-size:12px;line-height:1.55}.notice.ok{background:#ecfbf3;border:1px solid #c9efd9;color:#127449}.notice.err{background:#fff2f2;border:1px solid #ffd5d5;color:#b83239}.hp{position:absolute;left:-9999px;width:1px;height:1px;opacity:0}@media(max-width:650px){.feedback-page{width:min(100% - 20px,980px)}.feedback-hero{padding:25px 20px}.form-card{padding:20px}.row{grid-template-columns:1fr}.actions .btn{flex:1;justify-content:center}}
</style>
<main class="feedback-page">
<section class="feedback-hero"><span class="eyebrow"><span class="material-symbols-rounded">bug_report</span> TOOL SUPPORT</span><h1>Something not working?</h1><p>Tell us exactly what broke. We will save the report, keep the tool details with it, and send the SmartToolz team an email notification.</p><div class="tool-pill"><span class="material-symbols-rounded">build</span><?=smarttoolz_h($toolName)?></div></section>
<section class="form-card">
<?php if ($message !== ''): ?><div class="notice ok">✅ <?=smarttoolz_h($message)?></div><?php endif; ?>
<?php if ($error !== ''): ?><div class="notice err">⚠️ <?=smarttoolz_h($error)?></div><?php endif; ?>
<form method="post" novalidate>
<input type="hidden" name="tool_slug" value="<?=smarttoolz_h($toolSlug)?>"><input type="hidden" name="tool_name" value="<?=smarttoolz_h($toolName)?>"><input type="hidden" name="tool_url" value="<?=smarttoolz_h($toolUrl)?>">
<div class="hp"><label>Website<input name="website" tabindex="-1" autocomplete="off"></label></div>
<div class="row"><div class="field"><label for="name">Your name</label><input id="name" name="name" maxlength="120" value="<?=smarttoolz_h((string)($_POST['name'] ?? $defaultName))?>" placeholder="Optional"></div><div class="field"><label for="email">Email</label><input id="email" name="email" type="email" maxlength="190" value="<?=smarttoolz_h((string)($_POST['email'] ?? $defaultEmail))?>" placeholder="Optional, for reply"></div></div>
<div class="field"><label for="issue_type">What is happening?</label><select id="issue_type" name="issue_type"><option>Tool not working</option><option>Wrong result</option><option>Error / crash</option><option>Upload / download problem</option><option>Slow or stuck</option><option>Other</option></select></div>
<div class="field"><label for="description">What went wrong? *</label><textarea id="description" name="description" required maxlength="5000" placeholder="Example: I uploaded a PNG, clicked Convert, and nothing happened."><?=smarttoolz_h((string)($_POST['description'] ?? ''))?></textarea></div>
<div class="field"><label for="steps">Steps to reproduce</label><textarea id="steps" name="steps" maxlength="5000" placeholder="What did you click or enter before the problem appeared?"><?=smarttoolz_h((string)($_POST['steps'] ?? ''))?></textarea></div>
<div class="actions"><button class="btn primary" type="submit"><span class="material-symbols-rounded">send</span> Send Report</button><a class="btn ghost" href="/smart-toolz/tool.php"><span class="material-symbols-rounded">apps</span> Browse Tools</a></div>
</form></section></main>
<?php require_once __DIR__ . '/footer.php'; ?>

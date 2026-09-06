<?php
declare(strict_types=1);
require_once __DIR__ . '/lib/feedback.php';
$toolSlug = trim((string)($_GET['tool'] ?? $_POST['tool_slug'] ?? ''));
$toolName = trim((string)($_GET['name'] ?? $_POST['tool_name'] ?? ''));
$toolUrl = trim((string)($_GET['url'] ?? $_POST['tool_url'] ?? ''));
if ($toolName === '') $toolName = ucwords(str_replace(['-','_'], ' ', $toolSlug ?: 'SmartToolz tool'));
$message = '';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim((string)($_POST['name'] ?? ''));
    $email = trim((string)($_POST['email'] ?? ''));
    $issueType = trim((string)($_POST['issue_type'] ?? 'Tool not working'));
    $description = trim((string)($_POST['description'] ?? ''));
    $steps = trim((string)($_POST['steps'] ?? ''));
    if (trim((string)($_POST['website'] ?? '')) !== '') $error = 'Something went wrong. Please try again.';
    elseif ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) $error = 'Please enter a valid email address.';
    elseif ($description === '' || mb_strlen($description) < 8) $error = 'Please describe what went wrong.';
    else {
        try {
            $pdo = smarttoolz_db();
            $pdo->prepare('INSERT INTO tool_issue_reports(user_id,name,email,tool_name,tool_slug,tool_url,issue_type,description,steps,environment) VALUES(NULL,?,?,?,?,?,?,?,?,?)')
                ->execute([$name !== '' ? $name : null,$email !== '' ? $email : null,$toolName,$toolSlug ?: 'unknown',$toolUrl ?: null,$issueType,$description,$steps !== '' ? $steps : null,null]);
            $id = (int)$pdo->lastInsertId();
            $body = "New SmartToolz tool issue report\n\nReport #{$id}\nTool: {$toolName}\nSlug: {$toolSlug}\nURL: {$toolUrl}\nIssue type: {$issueType}\nName: " . ($name ?: 'Not provided') . "\nEmail: " . ($email ?: 'Not provided') . "\n\nWhat happened:\n{$description}\n\nSteps / details:\n" . ($steps ?: 'Not provided') . "\n";
            $sent = smarttoolz_feedback_mail('[SmartToolz] Tool issue — ' . $toolName, $body, $email ?: null);
            $message = $sent ? 'Report received and saved.' : 'Report received and saved. Email notification could not be sent right now.';
            $_POST = [];
        } catch (Throwable $e) {
            error_log('SmartToolz issue report: '.$e->getMessage());
            $error = 'We could not save the report right now. Please try again.';
        }
    }
}
?>
<?php require_once __DIR__ . '/header.php'; ?>
<style>body{margin:0;background:#f6f8fc;color:#172033;font-family:Inter,system-ui,Arial,sans-serif}.page{width:min(850px,calc(100% - 24px));margin:35px auto 60px}.card{background:#fff;border:1px solid #e5e9f0;border-radius:20px;padding:26px;box-shadow:0 14px 40px rgba(20,30,70,.05)}h1{margin:0 0 8px;font-size:clamp(30px,5vw,44px)}p{color:#707b8e;line-height:1.65}.field{display:grid;gap:7px;margin:15px 0}.field label{font-size:12px;font-weight:800}.field input,.field select,.field textarea{width:100%;box-sizing:border-box;border:1px solid #dfe3eb;border-radius:11px;padding:12px;font:inherit}.field textarea{min-height:130px;resize:vertical}.row{display:grid;grid-template-columns:1fr 1fr;gap:12px}.actions{display:flex;gap:9px;flex-wrap:wrap}.btn{display:inline-flex;padding:11px 16px;border:0;border-radius:10px;background:#635bff;color:#fff;font-weight:800;text-decoration:none;cursor:pointer}.btn.alt{background:#edf0f5;color:#344055}.notice{padding:12px;border-radius:10px;margin-bottom:14px;font-size:13px}.ok{background:#ecfbf3;color:#127449}.err{background:#fff0f0;color:#b4232d}.hp{position:absolute;left:-9999px}@media(max-width:650px){.row{grid-template-columns:1fr}}</style>
<main class="page"><section class="card"><h1>Report a problem</h1><p>Help us improve SmartToolz by describing the problem with this tool.</p><p><strong><?=smarttoolz_h($toolName)?></strong></p><?php if($message):?><div class="notice ok">✅ <?=smarttoolz_h($message)?></div><?php endif;?><?php if($error):?><div class="notice err">⚠️ <?=smarttoolz_h($error)?></div><?php endif;?><form method="post"><input type="hidden" name="tool_slug" value="<?=smarttoolz_h($toolSlug)"><input type="hidden" name="tool_name" value="<?=smarttoolz_h($toolName)"><input type="hidden" name="tool_url" value="<?=smarttoolz_h($toolUrl)"><div class="hp"><input name="website" tabindex="-1" autocomplete="off"></div><div class="row"><div class="field"><label>Name</label><input name="name" maxlength="120"></div><div class="field"><label>Email</label><input name="email" type="email" maxlength="190"></div></div><div class="field"><label>Issue type</label><select name="issue_type"><option>Tool not working</option><option>Wrong result</option><option>Error / crash</option><option>Upload / download problem</option><option>Slow or stuck</option><option>Other</option></select></div><div class="field"><label>What went wrong? *</label><textarea name="description" required maxlength="5000"></textarea></div><div class="field"><label>Steps to reproduce</label><textarea name="steps" maxlength="5000"></textarea></div><div class="actions"><button class="btn" type="submit">Send Report</button><a class="btn alt" href="/">Browse Tools</a></div></form></section></main>
<?php require_once __DIR__ . '/footer.php'; ?>
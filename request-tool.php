<?php
declare(strict_types=1);
require_once __DIR__ . '/lib/feedback.php';

[$defaultName, $defaultEmail] = smarttoolz_feedback_user_context();
$message = '';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim((string)($_POST['name'] ?? $defaultName));
    $email = trim((string)($_POST['email'] ?? $defaultEmail));
    $toolName = trim((string)($_POST['tool_name'] ?? ''));
    $description = trim((string)($_POST['description'] ?? ''));
    $useCase = trim((string)($_POST['use_case'] ?? ''));
    $honeypot = trim((string)($_POST['website'] ?? ''));

    if ($honeypot !== '') {
        $error = 'Something went wrong. Please try again.';
    } elseif (!smarttoolz_feedback_rate_ok('request', 300)) {
        $error = 'Please wait a few minutes before sending another request.';
    } elseif ($toolName === '' || mb_strlen($toolName) < 2) {
        $error = 'Please enter the tool you want us to build.';
    } elseif ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif ($description === '' || mb_strlen($description) < 8) {
        $error = 'Please tell us what the tool should do.';
    } else {
        try {
            $uid = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;
            $pdo = smarttoolz_db();
            $pdo->prepare('INSERT INTO tool_requests(user_id,name,email,tool_name,description,use_case,source,ip_hash) VALUES(?,?,?,?,?,?,?,?)')
                ->execute([$uid ?: null, $name !== '' ? $name : null, $email !== '' ? $email : null, $toolName, $description, $useCase !== '' ? $useCase : null, 'website', ($_SERVER['REMOTE_ADDR'] ?? '') !== '' ? hash('sha256', (string)$_SERVER['REMOTE_ADDR']) : null]);
            $id = (int)$pdo->lastInsertId();
            $body = "New SmartToolz tool request\n\nRequest #{$id}\nRequested tool: {$toolName}\nName: " . ($name ?: 'Not provided') . "\nEmail: " . ($email ?: 'Not provided') . "\n\nWhat it should do:\n{$description}\n\nWhy they need it:\n" . ($useCase ?: 'Not provided') . "\n\nSource: website\n";
            $sent = smarttoolz_feedback_mail('[SmartToolz] New tool request — ' . $toolName, $body, $email ?: null);
            $message = $sent ? 'Great! Your tool idea is saved and the SmartToolz team has been emailed.' : 'Great! Your tool idea is saved. Email notification could not be sent by the server right now.';
            $_POST = [];
        } catch (Throwable $e) {
            error_log('SmartToolz tool request: ' . $e->getMessage());
            $error = 'We could not save the request right now. Please try again.';
        }
    }
}
?>
<?php require_once __DIR__ . '/header.php'; ?>
<style>
.request-page{width:min(980px,calc(100% - 28px));margin:38px auto 70px}.request-hero{padding:36px;border:1px solid #e4e7ef;border-radius:24px;background:radial-gradient(circle at 92% 0,#e5e2ff 0,transparent 34%),linear-gradient(135deg,#fff,#f7f7ff);box-shadow:0 18px 50px rgba(30,35,80,.06)}.eyebrow{display:inline-flex;align-items:center;gap:7px;padding:7px 10px;border-radius:999px;background:#eeedff;color:#635bff;font-size:10px;font-weight:900;letter-spacing:.5px}.request-hero h1{margin:13px 0 8px;font-size:clamp(30px,5vw,50px);letter-spacing:-1.9px}.request-hero p{margin:0;max-width:720px;color:#707b8e;line-height:1.75;font-size:14px}.ideas{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-top:22px}.idea{padding:12px;border:1px solid #e5e7ef;background:#fff;border-radius:13px}.idea b{display:block;font-size:11px;margin-bottom:4px}.idea span{color:#7a8495;font-size:10px;line-height:1.5}.form-card{margin-top:18px;background:#fff;border:1px solid #e4e7ef;border-radius:22px;padding:26px;box-shadow:0 18px 50px rgba(30,35,80,.05)}.row{display:grid;grid-template-columns:1fr 1fr;gap:14px}.field{display:grid;gap:7px;margin-bottom:15px}.field label{font-size:11px;font-weight:850;color:#394459}.field input,.field textarea{width:100%;box-sizing:border-box;border:1px solid #dfe3eb;border-radius:12px;padding:12px 13px;font:inherit;font-size:13px;outline:none;background:#fff}.field textarea{min-height:125px;resize:vertical;line-height:1.6}.field input:focus,.field textarea:focus{border-color:#aca6ff;box-shadow:0 0 0 4px #635bff12}.actions{display:flex;align-items:center;gap:10px;flex-wrap:wrap}.btn{display:inline-flex;align-items:center;gap:7px;border:1px solid #dfe3eb;border-radius:12px;padding:12px 15px;font-size:12px;font-weight:850;text-decoration:none;cursor:pointer}.btn.primary{border-color:transparent;background:linear-gradient(135deg,#635bff,#846fff);color:#fff;box-shadow:0 9px 22px #635bff25}.btn.ghost{background:#fff;color:#596477}.notice{margin-bottom:16px;padding:13px 15px;border-radius:13px;font-size:12px;line-height:1.55}.notice.ok{background:#ecfbf3;border:1px solid #c9efd9;color:#127449}.notice.err{background:#fff2f2;border:1px solid #ffd5d5;color:#b83239}.hp{position:absolute;left:-9999px;width:1px;height:1px;opacity:0}@media(max-width:650px){.request-page{width:min(100% - 20px,980px)}.request-hero{padding:25px 20px}.form-card{padding:20px}.row,.ideas{grid-template-columns:1fr}.actions .btn{flex:1;justify-content:center}}
</style>
<main class="request-page">
<section class="request-hero"><span class="eyebrow"><span class="material-symbols-rounded">lightbulb</span> BUILD THE NEXT TOOL</span><h1>What should we build next?</h1><p>Tell us the tool you wish SmartToolz had. Every request is saved separately so we can spot the ideas people really want.</p><div class="ideas"><div class="idea"><b>🧠 Describe the idea</b><span>What should the tool do?</span></div><div class="idea"><b>⚡ Keep it practical</b><span>A clear use case helps us build faster.</span></div><div class="idea"><b>🚀 Shape SmartToolz</b><span>Your request can become a real tool.</span></div></div></section>
<section class="form-card">
<?php if ($message !== ''): ?><div class="notice ok">✅ <?=smarttoolz_h($message)?></div><?php endif; ?>
<?php if ($error !== ''): ?><div class="notice err">⚠️ <?=smarttoolz_h($error)?></div><?php endif; ?>
<form method="post" novalidate>
<div class="hp"><label>Website<input name="website" tabindex="-1" autocomplete="off"></label></div>
<div class="row"><div class="field"><label for="tool_name">Tool name *</label><input id="tool_name" name="tool_name" maxlength="180" required value="<?=smarttoolz_h((string)($_POST['tool_name'] ?? ''))?>" placeholder="Example: Instagram Caption Formatter"></div><div class="field"><label for="email">Email</label><input id="email" name="email" type="email" maxlength="190" value="<?=smarttoolz_h((string)($_POST['email'] ?? $defaultEmail))?>" placeholder="Optional, for updates"></div></div>
<div class="field"><label for="description">What should the tool do? *</label><textarea id="description" name="description" required maxlength="6000" placeholder="Explain the input, output and the main job of the tool."><?=smarttoolz_h((string)($_POST['description'] ?? ''))?></textarea></div>
<div class="field"><label for="use_case">How would you use it?</label><textarea id="use_case" name="use_case" maxlength="4000" placeholder="Tell us what you would use this tool for."><?=smarttoolz_h((string)($_POST['use_case'] ?? ''))?></textarea></div>
<div class="field"><label for="name">Your name</label><input id="name" name="name" maxlength="120" value="<?=smarttoolz_h((string)($_POST['name'] ?? $defaultName))?>" placeholder="Optional"></div>
<div class="actions"><button class="btn primary" type="submit"><span class="material-symbols-rounded">rocket_launch</span> Submit Tool Idea</button><a class="btn ghost" href="/smart-toolz/tool.php"><span class="material-symbols-rounded">apps</span> Explore Existing Tools</a></div>
</form></section></main>
<?php require_once __DIR__ . '/footer.php'; ?>

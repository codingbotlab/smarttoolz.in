<?php
declare(strict_types=1);
require_once dirname(__DIR__, 2) . '/lib/tool-page.php';
$tool = [
    'title' => 'Free Password Rule Audit',
    'description' => 'Check a password against common character and length rules locally.',
    'url' => 'https://smarttoolz.in/tools/password-rule-audit/'
];
smarttoolz_tool_page_start($tool);
?>
<main class="tool-page">
<section class="tool-intro">
    <h1>Password Rule Audit</h1>
    <p>Check common password rules in your browser.</p>
</section>
<section class="tool-workspace">
    <div class="tool-panel">
        <label for="passwordInput">Password</label>
        <input id="passwordInput" type="password" autocomplete="new-password" placeholder="Enter password">
        <button id="auditButton" type="button" class="tool-btn">Audit Password</button>
        <pre id="auditOutput" aria-live="polite"></pre>
    </div>
</section>
</main>
<script>
(() => {
    const passwordInput = document.getElementById('passwordInput');
    const auditButton = document.getElementById('auditButton');
    const auditOutput = document.getElementById('auditOutput');
    if (!passwordInput || !auditButton || !auditOutput) return;

    auditButton.addEventListener('click', () => {
        const value = passwordInput.value;
        const checks = [
            `Length 12+: ${value.length >= 12 ? 'PASS' : 'FAIL'}`,
            `Uppercase: ${/[A-Z]/.test(value) ? 'PASS' : 'FAIL'}`,
            `Lowercase: ${/[a-z]/.test(value) ? 'PASS' : 'FAIL'}`,
            `Number: ${/\d/.test(value) ? 'PASS' : 'FAIL'}`,
            `Symbol: ${/[^A-Za-z0-9]/.test(value) ? 'PASS' : 'FAIL'}`
        ];
        auditOutput.textContent = checks.join('\n');
    });
})();
</script>
<?php smarttoolz_tool_page_end(); ?>

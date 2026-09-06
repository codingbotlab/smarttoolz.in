<?php
declare(strict_types=1);
require_once dirname(__DIR__, 2) . '/lib/tool-page.php';
$tool = [
    'title' => 'Free Password Strength Checker',
    'description' => 'Check password strength instantly with a simple browser-based security test.',
    'url' => 'https://smarttoolz.in/tools/password-strength-checker/'
];
smarttoolz_tool_page_start($tool);
?>
<main class="tool-page">
  <section class="tool-intro">
    <h1>Password Strength Checker</h1>
    <p>Check how strong your password is without sending it to a server.</p>
  </section>
  <section class="tool-workspace">
    <div class="tool-panel">
      <label for="password">Password</label>
      <input id="password" type="password" autocomplete="off" placeholder="Enter a password">
      <button id="check" class="tool-btn" type="button">Check Strength</button>
      <div id="result" aria-live="polite">Enter a password to check.</div>
      <div id="details"></div>
    </div>
  </section>
</main>
<style>
.tool-page{max-width:820px;margin:0 auto;padding:48px 20px 72px}.tool-intro{text-align:center;margin-bottom:28px}.tool-intro h1{margin:0 0 10px;font-size:clamp(34px,5vw,52px);letter-spacing:-2px}.tool-intro p{margin:0;color:#667085}.tool-workspace{background:#fff;border:1px solid #e7eaf0;border-radius:22px;padding:24px;box-shadow:0 16px 45px rgba(16,24,40,.07)}.tool-panel{display:grid;gap:12px}.tool-panel label{font-size:12px;font-weight:800;color:#344054}.tool-panel input{width:100%;box-sizing:border-box;padding:14px;border:1px solid #dfe3eb;border-radius:11px;font-size:15px;outline:0}.tool-panel input:focus{border-color:#6b57ff}.tool-btn{border:0;border-radius:11px;padding:13px 18px;background:#5541ff;color:#fff;font-weight:800;cursor:pointer}.tool-btn:hover{opacity:.92}#result{margin-top:8px;padding:15px;border-radius:12px;background:#f6f7fb;font-weight:800}#details{color:#667085;font-size:13px;line-height:1.7}
</style>
<script>
(() => {
  const password = document.getElementById('password');
  const check = document.getElementById('check');
  const result = document.getElementById('result');
  const details = document.getElementById('details');

  function evaluate() {
    const value = password.value;
    if (!value) {
      result.textContent = 'Enter a password to check.';
      details.textContent = '';
      return;
    }
    let score = 0;
    const rules = [
      [value.length >= 12, '12+ characters'],
      [value.length >= 16, '16+ characters'],
      [/[a-z]/.test(value), 'lowercase letter'],
      [/[A-Z]/.test(value), 'uppercase letter'],
      [/[0-9]/.test(value), 'number'],
      [/[^A-Za-z0-9]/.test(value), 'special character']
    ];
    score = rules.filter(r => r[0]).length;
    if (/(.)\1{2,}/.test(value)) score--;
    score = Math.max(0, Math.min(6, score));
    const labels = ['Very weak', 'Weak', 'Fair', 'Good', 'Strong', 'Very strong', 'Excellent'];
    result.textContent = labels[score];
    const passed = rules.filter(r => r[0]).map(r => r[1]);
    details.textContent = `Score: ${score}/6 • ${passed.length ? 'Detected: ' + passed.join(', ') : 'Add more character variety and length.'}`;
  }

  check.addEventListener('click', evaluate);
  password.addEventListener('input', evaluate);
})();
</script>
<?php smarttoolz_tool_page_end(); ?>

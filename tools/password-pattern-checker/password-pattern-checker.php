<?php
declare(strict_types=1);
require_once dirname(__DIR__, 2) . '/lib/tool-page.php';

$tool = [
    'title' => 'Free Password Pattern Checker',
    'description' => 'Check a password for repeated characters, keyboard sequences and simple predictable patterns.',
    'url' => 'https://smarttoolz.in/tools/password-pattern-checker/'
];
smarttoolz_tool_page_start($tool);
?>
<main class="tool-page">
  <section class="tool-intro">
    <span class="tool-kicker">PASSWORD SECURITY</span>
    <h1>Password Pattern Checker</h1>
    <p>Detect obvious repeated, sequential and predictable patterns in a password.</p>
  </section>
  <section class="tool-workspace">
    <div class="tool-panel">
      <label for="pattern-password">Password</label>
      <input id="pattern-password" type="password" placeholder="Enter password" autocomplete="off">
      <button id="pattern-check" class="tool-btn" type="button">Check Pattern</button>
      <div id="pattern-result" aria-live="polite"></div>
    </div>
  </section>
</main>
<script>
(() => {
  const input = document.getElementById('pattern-password');
  const button = document.getElementById('pattern-check');
  const result = document.getElementById('pattern-result');

  const sequences = [
    '0123','1234','2345','3456','4567','5678','6789',
    '9876','8765','7654','6543','5432','4321','3210',
    'abcd','bcde','cdef','defg','efgh','qwer','asdf','zxcv'
  ];

  button.addEventListener('click', () => {
    const value = input.value;
    if (!value) {
      result.textContent = 'Enter a password first.';
      return;
    }

    const repeated = /(.)\1{2,}/i.test(value);
    const lower = value.toLowerCase();
    const sequential = sequences.some(sequence => lower.includes(sequence));
    const repeatedPair = /(.{2})\1{1,}/i.test(value);
    const obvious = /^(.)\1+$/.test(value);

    const findings = [];
    if (repeated) findings.push('3+ repeated characters');
    if (repeatedPair) findings.push('repeated character pair');
    if (sequential) findings.push('simple sequence');
    if (obvious) findings.push('same character throughout');

    if (findings.length) {
      result.innerHTML = '<strong>Patterns found:</strong> ' + findings.join(' · ');
    } else {
      result.innerHTML = '<strong>No obvious patterns found.</strong> This is only a pattern check, not a complete security assessment.';
    }
  });
})();
</script>
<?php smarttoolz_tool_page_end(); ?>

<?php
declare(strict_types=1);
require_once dirname(__DIR__, 2) . '/lib/tool-page.php';

$tool = [
    'title' => 'Free Password Hash Generator',
    'description' => 'Generate SHA-256 hashes from passwords locally in your browser.',
    'url' => 'https://smarttoolz.in/tools/password-hash-generator/'
];
smarttoolz_tool_page_start($tool);
?>
<main class="tool-page">
  <section class="tool-intro">
    <span class="tool-kicker">PASSWORD SECURITY</span>
    <h1>Password Hash Generator</h1>
    <p>Generate a SHA-256 hash locally in your browser.</p>
  </section>
  <section class="tool-workspace">
    <div class="tool-panel">
      <label for="password-input">Password</label>
      <input id="password-input" type="password" placeholder="Enter password" autocomplete="off">
      <button id="hash-button" class="tool-btn" type="button">Generate Hash</button>
      <textarea id="hash-output" rows="5" readonly placeholder="SHA-256 hash will appear here..."></textarea>
      <button id="copy-button" class="tool-btn" type="button">Copy Hash</button>
      <p id="hash-status" aria-live="polite"></p>
    </div>
  </section>
</main>
<script>
(() => {
  const input = document.getElementById('password-input');
  const button = document.getElementById('hash-button');
  const output = document.getElementById('hash-output');
  const copy = document.getElementById('copy-button');
  const status = document.getElementById('hash-status');

  button.addEventListener('click', async () => {
    if (!input.value) {
      output.value = '';
      status.textContent = 'Enter a password first.';
      return;
    }
    try {
      const buffer = await crypto.subtle.digest('SHA-256', new TextEncoder().encode(input.value));
      output.value = Array.from(new Uint8Array(buffer), byte => byte.toString(16).padStart(2, '0')).join('');
      status.textContent = 'Hash generated locally.';
    } catch (error) {
      output.value = '';
      status.textContent = 'Unable to generate hash in this browser.';
    }
  });

  copy.addEventListener('click', async () => {
    if (!output.value) {
      status.textContent = 'Generate a hash first.';
      return;
    }
    try {
      await navigator.clipboard.writeText(output.value);
      status.textContent = 'Hash copied.';
    } catch (error) {
      output.select();
      document.execCommand('copy');
      status.textContent = 'Hash copied.';
    }
  });
})();
</script>
<?php smarttoolz_tool_page_end(); ?>

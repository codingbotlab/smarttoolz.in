<?php
declare(strict_types=1);
require_once dirname(__DIR__, 2) . '/lib/tool-page.php';
$tool = [
    'title' => 'Free Password Token Generator',
    'description' => 'Generate cryptographically random token strings using Web Crypto.',
    'url' => 'https://smarttoolz.in/tools/password-token-generator/'
];
smarttoolz_tool_page_start($tool);
?>
<main class="tool-page">
<section class="tool-intro"><h1>Password Token Generator</h1><p>Generate secure random token strings locally in your browser.</p></section>
<section class="tool-workspace"><div class="tool-panel">
<label for="token-length">Length</label>
<input id="token-length" type="number" min="8" max="256" value="32">
<button id="generate-token" class="tool-btn" type="button">Generate Token</button>
<textarea id="token-output" rows="5" readonly placeholder="Your token will appear here..."></textarea>
<button id="copy-token" class="tool-btn" type="button">Copy Token</button>
</div></section>
</main>
<script>
(() => {
  const lengthInput = document.getElementById('token-length');
  const generateButton = document.getElementById('generate-token');
  const output = document.getElementById('token-output');
  const copyButton = document.getElementById('copy-token');

  function generateToken() {
    const length = Math.min(256, Math.max(8, Number.parseInt(lengthInput.value, 10) || 32));
    lengthInput.value = length;
    const bytes = new Uint8Array(length);
    window.crypto.getRandomValues(bytes);
    let token = '';
    const alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_';
    for (const byte of bytes) token += alphabet[byte % alphabet.length];
    output.value = token;
  }

  generateButton.addEventListener('click', generateToken);
  copyButton.addEventListener('click', async () => {
    if (!output.value) generateToken();
    await navigator.clipboard.writeText(output.value);
    copyButton.textContent = 'Copied';
    setTimeout(() => { copyButton.textContent = 'Copy Token'; }, 1000);
  });
  generateToken();
})();
</script>
<?php smarttoolz_tool_page_end(); ?>

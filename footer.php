<?php
declare(strict_types=1);

if (defined('SMARTTOOLZ_FOOTER_RENDERED')) {
    return;
}
define('SMARTTOOLZ_FOOTER_RENDERED', true);
?>
<footer class="site-footer">
  <div class="footer-inner">
    <div>
      <div class="footer-brand">SmartToolz</div>
      <p>Free, fast and simple online tools.</p>
    </div>
    <nav class="footer-links" aria-label="Footer navigation">
      <a href="/">Home</a>
      <a href="/tool.php">All Tools</a>
      <a href="/about.php">About</a>
      <a href="/contact.php">Contact</a>
      <a href="/privacy-policy.php">Privacy</a>
      <a href="/cookies.php">Cookies</a>
      <a href="/terms.php">Terms</a>
      <a href="/disclaimer.php">Disclaimer</a>
    </nav>
  </div>
  <div class="footer-bottom">© <?= date('Y') ?> SmartToolz. Built for useful everyday work.</div>
</footer>
<script defer src="/assets/js/smarttoolz.js"></script>

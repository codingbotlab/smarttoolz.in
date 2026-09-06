<?php
declare(strict_types=1);
require_once __DIR__ . '/bootstrap.php';
?>
<footer class="site-footer">
  <div class="footer-inner">
    <div><strong>SmartToolz</strong><p>Free browser-based tools for everyday tasks.</p></div>
    <nav class="footer-links" aria-label="Footer navigation">
      <a href="/">Home</a><a href="/tools/">All Tools</a><a href="/about.php">About</a>
      <a href="/privacy.php">Privacy</a><a href="/terms.php">Terms</a><a href="/contact.php">Contact</a>
    </nav>
  </div>
  <div class="footer-bottom">© <?= date('Y') ?> SmartToolz. All rights reserved.</div>
</footer>

<?php
declare(strict_types=1);
require_once __DIR__ . '/bootstrap.php';
?>
<footer class="site-footer">
  <div class="footer-inner footer-grid">
    <div class="footer-brand">
      <a class="footer-logo" href="/" aria-label="SmartToolz home"><span>Smart</span>Toolz</a>
      <p>Free, focused online tools for images, PDFs, text, development and everyday digital work.</p>
      <div class="footer-badges"><span>✓ Free to use</span><span>✓ Browser based</span></div>
    </div>
    <div class="footer-column"><h2>Tools</h2><a href="/tools/">All Tools</a><a href="/#popular">Popular Tools</a><a href="/#categories">Categories</a></div>
    <div class="footer-column"><h2>Company</h2><a href="/about.php">About</a><a href="/contact.php">Contact</a></div>
    <div class="footer-column"><h2>Legal</h2><a href="/privacy.php">Privacy</a><a href="/terms.php">Terms</a></div>
  </div>
  <div class="footer-bottom"><span>© <?= date('Y') ?> SmartToolz. All rights reserved.</span><span>Built for simple, useful online tasks.</span></div>
</footer>

<footer class="site-footer"><div class="footer-inner"><div><span class="footer-brand">SmartToolz</span><span> — free tools for everyday work.</span></div><nav class="footer-links" aria-label="Footer"><a href="/">Home</a><a href="/tool.php">All Tools</a><a href="/about.php">About</a><a href="/contact.php">Contact</a><a href="/privacy-policy.php">Privacy</a><a href="/cookie-policy.php">Cookies</a><a href="/terms.php">Terms</a><a href="/disclaimer.php">Disclaimer</a></nav></div></footer>
<script defer src="/assets/js/smarttoolz.js"></script>
<script>
(function () {
  function removeDuplicateRelatedTools() {
    var sections = Array.prototype.slice.call(document.querySelectorAll('section'));
    var hasSpecificRelated = false;
    sections.forEach(function (section) {
      var heading = section.querySelector('h2,h3');
      if (heading && heading.textContent.trim().toLowerCase() === 'related image tools') {
        hasSpecificRelated = true;
      }
    });
    if (!hasSpecificRelated) return;
    sections.forEach(function (section) {
      var heading = section.querySelector('h2,h3');
      if (!heading) return;
      if (heading.textContent.trim().toLowerCase() === 'related tools') {
        section.remove();
      }
    });
  }
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', removeDuplicateRelatedTools);
  } else {
    removeDuplicateRelatedTools();
  }
})();
</script>

<?php
if (defined('SMARTTOOLZ_FOOTER_LOADED')) { return; }
define('SMARTTOOLZ_FOOTER_LOADED', true);
?>
<style id="smarttoolz-shared-footer-css">
.site-footer{padding:34px 0;background:#fff;border-top:1px solid #e6e8ef;flex:0 0 auto}.footer-inner{width:min(1240px,calc(100% - 32px));margin:auto;display:flex;justify-content:space-between;gap:20px;align-items:center;color:#667085;font-size:11px}.footer-brand{font-weight:850;color:#101828}.footer-links{display:flex;flex-wrap:wrap;gap:15px}.footer-links a:hover{text-decoration:underline;color:#635bff}.related-tools{width:min(1080px,calc(100% - 28px));margin:38px auto 0;padding-top:28px;border-top:1px solid #e6e8ef}.related-tools-head{display:flex;align-items:end;justify-content:space-between;gap:20px;margin-bottom:16px}.related-tools-label{display:inline-flex;padding:6px 9px;border-radius:999px;background:#eeedff;color:#635bff;font-size:9px;font-weight:900;letter-spacing:1px}.related-tools-head h2{margin:10px 0 5px;font-size:24px;letter-spacing:-.8px;color:#101828}.related-tools-head p{margin:0;color:#667085;font-size:12px}.related-tools-head>a{color:#635bff;font-size:12px;font-weight:850;white-space:nowrap}.related-tool-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:12px}.related-tool-card{display:flex;min-height:180px;flex-direction:column;padding:17px;background:#fff;border:1px solid #e6e8ef;border-radius:16px;transition:.2s;box-shadow:0 10px 30px rgba(16,24,40,.035);text-decoration:none!important;color:#101828!important}.related-tool-card:hover{transform:translateY(-4px);border-color:#d8d4ff;box-shadow:0 18px 40px rgba(16,24,40,.08)}.related-tool-icon{width:42px;height:42px;display:grid;place-items:center;margin-bottom:13px;border-radius:12px;background:#f0efff;color:#635bff;font-size:19px}.related-tool-card strong{font-size:13px;line-height:1.25;color:#101828}.related-tool-card>span:not(.related-tool-icon){margin-top:6px;color:#667085;font-size:11px;line-height:1.55}.related-tool-card em{margin-top:auto;padding-top:13px;color:#635bff;font-size:10px;font-style:normal;font-weight:850}@media(max-width:900px){.related-tool-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}@media(max-width:800px){.related-tools-head{align-items:start;flex-direction:column}.related-tool-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}@media(max-width:560px){.footer-inner{flex-direction:column;text-align:center}.related-tools{width:calc(100% - 24px);margin-top:28px}.related-tool-grid{grid-template-columns:1fr}}
</style>
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

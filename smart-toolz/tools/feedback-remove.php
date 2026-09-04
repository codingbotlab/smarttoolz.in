<?php
/* SmartToolz: remove every legacy report/request UI from tool pages. */
if (PHP_SAPI === 'cli') return;
$path = (string)($_SERVER['SCRIPT_NAME'] ?? '');
if (!str_contains($path, '/smart-toolz/tools/')) return;

/* Keep the Knowledge Base guide injector independent from the feedback UI. */
$kb = __DIR__ . '/kb-guide-cta.php';
if (is_file($kb) && is_readable($kb)) require_once $kb;

/* Hide immediately and prevent flash before DOM cleanup. */
echo '<style id="smarttoolz-feedback-remove">.tool-support-actions,.st-feedback-strip,.st-feedback-card,.smarttoolz-feedback-actions{display:none!important}</style>';
?>
<script>
(function(){
  'use strict';
  function removeLegacy(){
    document.querySelectorAll('.tool-support-actions,.st-feedback-strip,.st-feedback-card,.smarttoolz-feedback-actions').forEach(function(el){el.remove();});
  }
  function start(){
    removeLegacy();
    if(window.MutationObserver && document.body){
      new MutationObserver(removeLegacy).observe(document.body,{childList:true,subtree:true});
    }
  }
  if(document.readyState==='loading') document.addEventListener('DOMContentLoaded',start,{once:true});
  else start();
})();
</script>

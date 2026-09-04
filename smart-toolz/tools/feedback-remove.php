<?php
/* SmartToolz: temporarily remove all report/request UI from tool pages. */
if (PHP_SAPI === 'cli') return;
$path = (string)($_SERVER['SCRIPT_NAME'] ?? '');
if (!str_contains($path, '/smart-toolz/tools/')) return;
$kb = __DIR__ . '/kb-guide-cta.php';
if (is_file($kb) && is_readable($kb)) require_once $kb;
echo '<style id="smarttoolz-feedback-remove">.tool-support-actions,.st-feedback-strip{display:none!important}</style>';
?>
<script>
(function(){
  'use strict';
  function remove(){
    document.querySelectorAll('.tool-support-actions,.st-feedback-strip').forEach(function(el){el.remove();});
  }
  if(document.readyState==='loading') document.addEventListener('DOMContentLoaded',remove,{once:true});
  else remove();
})();
</script>

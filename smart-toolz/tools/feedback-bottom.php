<?php
/* SmartToolz universal bottom feedback injector. */
if (PHP_SAPI === 'cli') return;

$path = (string)($_SERVER['SCRIPT_NAME'] ?? '');
if (!str_contains($path, '/smart-toolz/tools/')) return;

$slug = basename($path, '.php');
if ($slug === '' || str_starts_with($slug, '_') || $slug === 'kb-guide-cta' || $slug === 'feedback-bottom') return;

/* Preserve the existing Knowledge Base guide injector. */
$kb = __DIR__ . '/kb-guide-cta.php';
if (is_file($kb) && is_readable($kb)) {
    require_once $kb;
}

/* Hide legacy support markup as early as possible. */
echo '<style id="smarttoolz-feedback-hide">.tool-support-actions,.st-feedback-strip{display:none!important}</style>';
?>
<script>
(function(){
  'use strict';
  if (window.__smartToolzFeedbackBottomLoaded) return;
  window.__smartToolzFeedbackBottomLoaded = true;

  function mount(){
    if(!document.body) return;

    var path=window.location.pathname||'';
    var match=path.match(/\/smart-toolz\/tools\/([^/]+)\.php$/i);
    if(!match) return;

    /* Remove all legacy/top copies created by header.php, footer.php or cache. */
    document.querySelectorAll('.tool-support-actions').forEach(function(el){el.remove();});
    document.querySelectorAll('.st-feedback-strip').forEach(function(el){el.remove();});

    var slug=decodeURIComponent(match[1]);
    var name=slug.replace(/[-_]+/g,' ').replace(/\b\w/g,function(m){return m.toUpperCase();});
    var qs=new URLSearchParams({tool:slug,name:name,url:path}).toString();

    var section=document.createElement('section');
    section.className='st-feedback-strip';
    section.setAttribute('aria-label','SmartToolz feedback');
    section.innerHTML='<div class="st-feedback-wrap">'+
      '<div class="st-feedback-head">'+
        '<span class="st-feedback-kicker">HELP SMARTTOOLZ GET BETTER</span>'+ 
        '<h2>Found a problem or missing a tool?</h2>'+ 
        '<p>Tell us what you need. Your feedback goes directly into our support queue.</p>'+ 
      '</div>'+ 
      '<div class="st-feedback-actions">'+
        '<a class="st-feedback-card st-report" href="/smart-toolz/report-tool.php?'+qs+'">'+
          '<span class="st-feedback-icon"><span class="material-symbols-rounded">bug_report</span></span>'+ 
          '<span class="st-feedback-copy"><strong>Report a Problem</strong><small>Tool not working, wrong result, error or upload issue</small></span>'+ 
          '<span class="st-feedback-arrow material-symbols-rounded">arrow_forward</span>'+ 
        '</a>'+ 
        '<a class="st-feedback-card st-request" href="/smart-toolz/request-tool.php?'+qs+'">'+
          '<span class="st-feedback-icon"><span class="material-symbols-rounded">lightbulb</span></span>'+ 
          '<span class="st-feedback-copy"><strong>Request a New Tool</strong><small>Tell us what you want SmartToolz to build next</small></span>'+ 
          '<span class="st-feedback-arrow material-symbols-rounded">arrow_forward</span>'+ 
        '</a>'+ 
      '</div>'+ 
    '</div>';

    if (!document.getElementById('smarttoolz-feedback-bottom-style')) {
      var style=document.createElement('style');
      style.id='smarttoolz-feedback-bottom-style';
      style.textContent='.st-feedback-strip{display:block!important;width:100%;box-sizing:border-box;margin:52px 0 0;padding:30px 0 38px;background:#f7f9fd}.st-feedback-wrap{width:min(1200px,calc(100% - 28px));margin:auto}.st-feedback-head{margin-bottom:14px}.st-feedback-kicker{display:inline-flex;align-items:center;padding:6px 10px;border-radius:999px;background:#eeedff;color:#635bff;font-size:9px;font-weight:900;letter-spacing:.55px}.st-feedback-head h2{margin:9px 0 4px;color:#172033;font-size:23px;line-height:1.2;letter-spacing:-.55px}.st-feedback-head p{margin:0;color:#7a8393;font-size:11px;line-height:1.6}.st-feedback-actions{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px}.st-feedback-card{display:flex;align-items:center;gap:12px;min-height:74px;padding:12px 15px;box-sizing:border-box;border:1px solid #e3e6ee;border-radius:16px;background:#fff;text-decoration:none;box-shadow:0 8px 24px rgba(30,35,80,.035);transition:transform .18s ease,box-shadow .18s ease,border-color .18s ease}.st-feedback-card:hover{transform:translateY(-2px);box-shadow:0 14px 30px rgba(30,35,80,.08);border-color:#d5d0ff}.st-feedback-card.st-request{background:linear-gradient(135deg,#fff,#f8f7ff)}.st-feedback-icon{width:42px;height:42px;flex:0 0 42px;display:grid;place-items:center;border-radius:12px}.st-report .st-feedback-icon{background:#fff0f1;color:#e5484d}.st-request .st-feedback-icon{background:#efedff;color:#635bff}.st-feedback-copy{min-width:0;display:flex;flex-direction:column;gap:4px}.st-feedback-copy strong{color:#172033;font-size:12px;font-weight:900}.st-feedback-copy small{color:#7a8393;font-size:9px;line-height:1.45}.st-feedback-arrow{margin-left:auto;color:#8791a2;font-size:19px}@media(max-width:650px){.st-feedback-strip{margin-top:38px;padding:24px 0 30px}.st-feedback-actions{grid-template-columns:1fr}.st-feedback-head h2{font-size:20px}.st-feedback-card{min-height:68px}}';
      document.head.appendChild(style);
    }

    /* Actual last section of the page, after the site's footer as well. */
    document.body.appendChild(section);
  }

  if(document.readyState==='loading') document.addEventListener('DOMContentLoaded',mount,{once:true});
  else mount();
})();
</script>

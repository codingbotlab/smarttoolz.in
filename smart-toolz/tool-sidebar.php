<?php
declare(strict_types=1);

/*
 * SmartToolz tool pages are intentionally full-width.
 *
 * The old shared All Tools rail is retired across every individual tool.
 * Tool-specific documentation now lives in the Knowledge Base and is linked
 * automatically into the shared How to Use card when a tool page is present.
 */
?>
<style>
/* Normalize legacy tool wrappers that were capped at ~1200/1320px. */
body > .page-layout,
body > .wrap,
body > .tool-page,
body > .tool-container,
body > .tool-wrapper,
body > main.page-layout,
body > main.wrap,
body > main.tool-page,
body > main.tool-container,
body > main.tool-wrapper{width:100%!important;max-width:none!important}
.page-layout,.wrap,.tool-page,.tool-container,.tool-wrapper{max-width:none!important}

/* Shared Knowledge Base CTA. */
.how-use .smarttoolz-guide-link{
  display:flex;
  align-items:center;
  justify-content:center;
  gap:8px;
  width:100%;
  box-sizing:border-box;
  margin-top:13px;
  padding:10px 13px;
  border:1px solid rgba(99,91,255,.18);
  border-radius:10px;
  background:linear-gradient(135deg,#635bff,#806fff);
  color:#fff;
  text-decoration:none;
  font-size:11px;
  font-weight:850;
  line-height:1.2;
  box-shadow:0 7px 18px rgba(99,91,255,.18);
  transition:transform .18s ease,box-shadow .18s ease,filter .18s ease;
}
.how-use .smarttoolz-guide-link:hover{
  transform:translateY(-1px);
  box-shadow:0 10px 24px rgba(99,91,255,.25);
  filter:saturate(1.05);
}
.how-use .smarttoolz-guide-link:focus-visible{outline:3px solid rgba(99,91,255,.22);outline-offset:2px}
.how-use .smarttoolz-guide-link .material-symbols-rounded{font-size:17px;flex:0 0 auto}
.how-use .smarttoolz-guide-note{margin-top:7px;text-align:center;color:#8a93a5;font-size:9.5px;line-height:1.45}
</style>
<script>
(()=>{
  const addGuide=()=>{
    const how=document.querySelector('.how-use');
    if(!how||how.querySelector('.smarttoolz-guide-link'))return;

    const file=(location.pathname.split('/').pop()||'').replace(/\.php$/,'');
    if(!file)return;

    const href=`/knowledge-base/${encodeURIComponent(file)}/article/`;
    const link=document.createElement('a');
    link.className='smarttoolz-guide-link';
    link.href=href;
    link.title='Read the detailed guide for this tool';
    link.setAttribute('aria-label','Read the Knowledge Base guide for this tool');
    link.innerHTML='<span class="material-symbols-rounded" aria-hidden="true">menu_book</span><span>Guide: How to use this tool →</span>';

    const note=document.createElement('div');
    note.className='smarttoolz-guide-note';
    note.textContent='Step-by-step guide, tips & visual walkthrough.';

    const ol=how.querySelector('ol');
    if(ol){
      ol.insertAdjacentElement('afterend',link);
      link.insertAdjacentElement('afterend',note);
    }else{
      how.appendChild(link);
      how.appendChild(note);
    }
  };

  if(document.readyState==='loading'){
    document.addEventListener('DOMContentLoaded',addGuide,{once:true});
  }else{
    addGuide();
  }
})();
</script>
<?php
return;

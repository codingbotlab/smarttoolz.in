<?php
declare(strict_types=1);

/*
 * SmartToolz individual tool pages are intentionally full-width.
 *
 * This file remains a compatibility include for legacy tools. The shared
 * All Tools rail is retired from individual tool pages, while this small
 * stylesheet normalizes common legacy page wrappers to use the full viewport.
 */
?>
<style>
/* Full-width tool canvas: remove legacy 1200px/1320px wrapper caps. */
body > .page-layout,
body > .wrap,
body > .tool-page,
body > .tool-container,
body > .tool-wrapper,
body > main.page-layout,
body > main.wrap,
body > main.tool-page,
body > main.tool-container,
body > main.tool-wrapper {
  width:100% !important;
  max-width:none !important;
}

/* Common inner tool shells should also fill the available tool canvas. */
.page-layout,
.wrap,
.tool-page,
.tool-container,
.tool-wrapper {
  max-width:none !important;
}

/* Tool-specific guide button inside the shared How to Use card. */
.how-use .smarttoolz-guide-link{display:flex;align-items:center;justify-content:center;gap:8px;width:100%;box-sizing:border-box;margin-top:13px;padding:10px 13px;border:1px solid rgba(99,91,255,.18);border-radius:10px;background:linear-gradient(135deg,#635bff,#806fff);color:#fff;text-decoration:none;font-size:11px;font-weight:850;line-height:1.2;box-shadow:0 7px 18px rgba(99,91,255,.18);transition:transform .18s ease,box-shadow .18s ease,filter .18s ease}
.how-use .smarttoolz-guide-link:hover{transform:translateY(-1px);box-shadow:0 10px 24px rgba(99,91,255,.25);filter:saturate(1.05)}
.how-use .smarttoolz-guide-link:focus-visible{outline:3px solid rgba(99,91,255,.22);outline-offset:2px}
.how-use .smarttoolz-guide-link .material-symbols-rounded{font-size:17px;flex:0 0 auto}
.how-use .smarttoolz-guide-note{margin-top:7px;text-align:center;color:#8a93a5;font-size:9.5px;line-height:1.45}
</style>
<script>
(()=>{
  const addGuide=async()=>{
    const how=document.querySelector('.how-use');
    if(!how||how.querySelector('.smarttoolz-guide-link'))return;
    const slug=(location.pathname.split('/').pop()||'').replace(/\.php$/,'');
    if(!slug||slug==='image-background-remover')return;
    const candidates=[
      `/smart-toolz/tools/${slug}-guide.php`,
      `/knowledge-base/${slug}/article/`
    ];
    for(const href of candidates){
      try{
        const r=await fetch(href,{method:'HEAD',cache:'no-store'});
        if(!r.ok)continue;
        const link=document.createElement('a');
        link.className='smarttoolz-guide-link';
        link.href=href;
        link.innerHTML='<span class="material-symbols-rounded">menu_book</span><span>Guide: How to use this tool →</span>';
        const note=document.createElement('div');
        note.className='smarttoolz-guide-note';
        note.textContent='Step-by-step guide, tips & visual walkthrough.';
        how.appendChild(link);how.appendChild(note);
        return;
      }catch(e){}
    }
  };
  if(document.readyState==='loading')document.addEventListener('DOMContentLoaded',addGuide,{once:true});else addGuide();
})();
</script>
<?php
return;

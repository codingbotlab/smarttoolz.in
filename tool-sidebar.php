<?php
declare(strict_types=1);

/*
 * SmartToolz tool pages are intentionally full-width.
 *
 * The old shared All Tools rail is retired across every individual tool.
 * Tool-specific documentation now lives in the Knowledge Base and is linked
 * automatically into tool pages when a matching guide exists.
 */
?>
<style>
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

.smarttoolz-guide-link{
  display:flex;
  align-items:center;
  justify-content:center;
  gap:8px;
  box-sizing:border-box;
  margin-top:13px;
  padding:11px 15px;
  border:1px solid rgba(99,91,255,.18);
  border-radius:11px;
  background:linear-gradient(135deg,#635bff,#806fff);
  color:#fff!important;
  text-decoration:none!important;
  font-size:11px;
  font-weight:850;
  line-height:1.2;
  box-shadow:0 7px 18px rgba(99,91,255,.18);
  transition:transform .18s ease,box-shadow .18s ease,filter .18s ease;
}
.smarttoolz-guide-link:hover{transform:translateY(-1px);box-shadow:0 10px 24px rgba(99,91,255,.25);filter:saturate(1.05)}
.smarttoolz-guide-link:focus-visible{outline:3px solid rgba(99,91,255,.22);outline-offset:2px}
.smarttoolz-guide-link .material-symbols-rounded{font-size:17px;flex:0 0 auto}
.smarttoolz-guide-note{margin-top:7px;text-align:center;color:#8a93a5;font-size:9.5px;line-height:1.45}

/* Background Remover has its own Vision Studio UI, so place the guide directly under the hero. */
.image-remover-guide{width:min(760px,100%);margin:0 auto 18px}
.image-remover-guide .smarttoolz-guide-link{margin-top:0}
</style>
<script>
(()=>{
  const makeLink=()=>{
    const link=document.createElement('a');
    link.className='smarttoolz-guide-link';
    link.href='/knowledge-base/image-background-remover/article/';
    link.title='Read the detailed guide for this tool';
    link.setAttribute('aria-label','Read the Knowledge Base guide for Image Background Remover');
    link.innerHTML='<span class="material-symbols-rounded" aria-hidden="true">menu_book</span><span>Guide: How to use this tool →</span>';
    return link;
  };

  const note=()=>{
    const n=document.createElement('div');
    n.className='smarttoolz-guide-note';
    n.textContent='Step-by-step guide, tips & visual walkthrough.';
    return n;
  };

  const addGuide=()=>{
    const isBgRemover=/\/image-background-remover\.php$/i.test(location.pathname);
    if(isBgRemover){
      if(document.querySelector('.image-remover-guide'))return;
      const hero=document.querySelector('.hero');
      if(!hero)return;
      const block=document.createElement('div');
      block.className='image-remover-guide';
      block.appendChild(makeLink());
      block.appendChild(note());
      hero.insertAdjacentElement('afterend',block);
      return;
    }

    const how=document.querySelector('.how-use');
    if(!how||how.querySelector('.smarttoolz-guide-link'))return;

    const file=(location.pathname.split('/').pop()||'').replace(/\.php$/,'');
    if(!file)return;

    const link=document.createElement('a');
    link.className='smarttoolz-guide-link';
    link.href=`/knowledge-base/${encodeURIComponent(file)}/article/`;
    link.title='Read the detailed guide for this tool';
    link.setAttribute('aria-label','Read the Knowledge Base guide for this tool');
    link.innerHTML='<span class="material-symbols-rounded" aria-hidden="true">menu_book</span><span>Guide: How to use this tool →</span>';

    const n=note();
    const ol=how.querySelector('ol');
    if(ol){
      ol.insertAdjacentElement('afterend',link);
      link.insertAdjacentElement('afterend',n);
    }else{
      how.appendChild(link);
      how.appendChild(n);
    }
  };

  const boot=()=>{
    addGuide();
    /* Custom studio markup can settle after the shared include on slower loads. */
    setTimeout(addGuide,100);
    setTimeout(addGuide,500);
  };

  if(document.readyState==='loading'){
    document.addEventListener('DOMContentLoaded',boot,{once:true});
  }else{
    boot();
  }
})();
</script>
<?php
return;

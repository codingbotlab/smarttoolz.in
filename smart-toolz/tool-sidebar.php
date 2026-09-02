<?php
declare(strict_types=1);

/*
 * SmartToolz tool pages are intentionally full-width.
 *
 * The old shared All Tools rail is retired across every individual tool.
 * Keep the dedicated Background Remover guide injection because that page
 * has its own editor controls and the guide belongs in its How to Use card.
 */

$currentSlug = basename((string)($_SERVER['SCRIPT_NAME'] ?? ''), '.php');

if ($currentSlug === 'image-background-remover') {
    ?>
    <style>
      .how-use .smarttoolz-guide-link{display:flex;align-items:center;justify-content:center;gap:8px;width:100%;box-sizing:border-box;margin-top:13px;padding:10px 13px;border:1px solid rgba(99,91,255,.18);border-radius:10px;background:linear-gradient(135deg,#635bff,#806fff);color:#fff;text-decoration:none;font-size:11px;font-weight:850;line-height:1.2;box-shadow:0 7px 18px rgba(99,91,255,.18);transition:transform .18s ease,box-shadow .18s ease,filter .18s ease}
      .how-use .smarttoolz-guide-link:hover{transform:translateY(-1px);box-shadow:0 10px 24px rgba(99,91,255,.25);filter:saturate(1.05)}
      .how-use .smarttoolz-guide-link:focus-visible{outline:3px solid rgba(99,91,255,.22);outline-offset:2px}
      .how-use .smarttoolz-guide-link .material-symbols-rounded{font-size:17px;flex:0 0 auto}
      .how-use .smarttoolz-guide-note{margin-top:7px;text-align:center;color:#8a93a5;font-size:9.5px;line-height:1.45}
    </style>
    <script>
    (()=>{
      const addGuide=()=>{
        const how=document.querySelector('.how-use');
        if(!how||how.querySelector('.smarttoolz-guide-link'))return;
        const link=document.createElement('a');
        link.className='smarttoolz-guide-link';
        link.href='/smart-toolz/tools/image-background-remover-guide.php';
        link.innerHTML='<span class="material-symbols-rounded">menu_book</span><span>Guide: How to use this tool →</span>';
        const note=document.createElement('div');
        note.className='smarttoolz-guide-note';
        note.textContent='Step-by-step guide to get the best results.';
        how.appendChild(link);how.appendChild(note);
      };
      if(document.readyState==='loading')document.addEventListener('DOMContentLoaded',addGuide,{once:true});else addGuide();
    })();
    </script>
    <?php
}

return;

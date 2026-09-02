<?php ?>
<footer class="site-footer">
  <div class="footer-inner">
    <div><strong>SmartToolz</strong><span> — Simple, fast and useful online tools.</span></div>
    <nav>
      <a href="/smart-toolz/">Home</a>
      <a href="/smart-toolz/tool.php">All Tools</a>
      <a href="/smart-toolz/account.php">My Account</a>
      <a href="/smart-toolz/saas/payout.php">Referral Payout</a>
      <a href="/creator-ai/">AI Dashboard</a>
      <a href="/creator-ai/auth/logout.php">Logout</a>
    </nav>
  </div>
</footer>
<style>
.site-footer{border-top:1px solid #e8ebf2;background:#fff;color:#687287}
.footer-inner{width:min(1400px,calc(100% - 28px));margin:auto;padding:26px 0;display:flex;justify-content:space-between;gap:20px;font-size:13px}
.footer-inner nav{display:flex;gap:18px;flex-wrap:wrap}
.footer-inner a{color:#596477;text-decoration:none}
.footer-inner a:hover{color:#635bff}
.material-symbols-outlined,.material-symbols-rounded,.material-symbols-sharp{font-size:1.2em;line-height:1;vertical-align:-.18em;font-variation-settings:'FILL' 0,'wght' 500,'GRAD' 0,'opsz' 24}
.fa-brands{font-size:1.05em;line-height:1;vertical-align:-.08em}
@media(max-width:650px){.footer-inner{flex-direction:column}}
</style>
<script>
(()=>{
  const add=(rel,href)=>{
    if(document.querySelector('link[data-smarttoolz-icons="'+rel+'"]'))return;
    const l=document.createElement('link');
    l.rel='stylesheet';
    l.href=href;
    l.dataset.smarttoolzIcons=rel;
    document.head.appendChild(l);
  };
  add('material-symbols','https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:FILL,wght,GRAD,opsz@0,100..700,-25..200,20..48');
  add('fontawesome-brands','https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/brands.min.css');

  const map={
    '🛠️':'build','☰':'menu','🛡️':'shield','🔔':'notifications','⚡':'bolt','🔒':'lock','🎁':'redeem','🔐':'lock',
    '→':'arrow_forward','←':'arrow_back','✓':'check_circle','×':'close','✕':'close','📥':'download','📤':'upload',
    '📋':'content_copy','🗑️':'delete','✏️':'edit','⚙️':'settings','🔍':'search','❤️':'favorite','⭐':'star',
    'ℹ️':'info','❓':'help','⚠️':'warning','✅':'check_circle','❌':'cancel','🔗':'link','🌐':'language',
    '📄':'description','📁':'folder','🖼️':'image','🎨':'palette','🔄':'sync','▶️':'play_arrow','⏸️':'pause',
    '⏹️':'stop','⏱️':'timer','📊':'analytics','👥':'group','👤':'person','🏠':'home','🧰':'construction',
    '🔑':'key','💾':'save','📌':'push_pin'
  };
  const esc=s=>s.replace(/[.*+?^${}()|[\]\\]/g,'\\$&');
  const keys=Object.keys(map).sort((a,b)=>b.length-a.length);
  const re=new RegExp(keys.map(esc).join('|'),'g');
  const walker=document.createTreeWalker(document.body,NodeFilter.SHOW_TEXT,{
    acceptNode:n=>{
      if(!n.nodeValue||!re.test(n.nodeValue))return NodeFilter.FILTER_REJECT;
      const p=n.parentElement;
      if(!p||p.closest('script,style,textarea,input,.material-symbols-outlined,.material-symbols-rounded,.material-symbols-sharp'))return NodeFilter.FILTER_REJECT;
      re.lastIndex=0;
      return NodeFilter.FILTER_ACCEPT;
    }
  });
  const nodes=[];
  while(walker.nextNode())nodes.push(walker.currentNode);
  nodes.forEach(n=>{
    const frag=document.createDocumentFragment();
    let last=0;
    const text=n.nodeValue;
    re.lastIndex=0;
    let m;
    while((m=re.exec(text))){
      if(m.index>last)frag.appendChild(document.createTextNode(text.slice(last,m.index)));
      const s=document.createElement('span');
      s.className='material-symbols-outlined';
      s.textContent=map[m[0]];
      s.setAttribute('aria-hidden','true');
      frag.appendChild(s);
      last=m.index+m[0].length;
    }
    if(last<text.length)frag.appendChild(document.createTextNode(text.slice(last)));
    n.parentNode.replaceChild(frag,n);
  });
})();
</script>
<script src="/analytics/site-events.js?v=20260902-1" defer></script>

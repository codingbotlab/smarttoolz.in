document.addEventListener('DOMContentLoaded',()=>{
  const $=s=>document.querySelector(s), $$=s=>document.querySelectorAll(s);
  document.querySelectorAll('[data-tab-toggle]').forEach(b=>b.addEventListener('click',()=>{
    const id=b.getAttribute('data-tab-toggle');
    document.querySelectorAll('[data-tab-panel]').forEach(p=>p.hidden=p.id!==id);
    document.querySelectorAll('[data-tab-toggle]').forEach(x=>x.classList.toggle('active',x===b));
  }));
  document.querySelectorAll('[data-open-modal]').forEach(b=>b.addEventListener('click',()=>{
    const m=document.getElementById(b.getAttribute('data-open-modal')); if(m)m.classList.add('open');
  }));
  document.querySelectorAll('[data-close-modal]').forEach(b=>b.addEventListener('click',()=>{
    const m=b.closest('.modal-backdrop'); if(m)m.classList.remove('open');
  }));
  document.querySelectorAll('.modal-backdrop').forEach(m=>m.addEventListener('click',e=>{if(e.target===m)m.classList.remove('open')}));
  const liveBox=$('[data-live-box]');
  const liveCount=$('[data-live-count]');
  async function refreshLive(){
    if(!liveBox && !liveCount)return;
    try{
      const r=await fetch('/smart-toolz/admin/dashboard-live.php?ajax=1',{credentials:'same-origin',cache:'no-store',headers:{'X-Requested-With':'XMLHttpRequest'}});
      if(!r.ok)return;
      const d=await r.json();
      if(liveCount && typeof d.live_now!=='undefined') liveCount.textContent=Number(d.live_now).toLocaleString();
      if(liveBox && Array.isArray(d.live)){
        liveBox.innerHTML=d.live.slice(0,30).map(x=>`<div class="live-item"><span class="live-dot"></span><div class="live-main"><div class="live-title">${esc(x.page_path||'/')}</div><div class="live-sub">${esc([x.country||'Unknown',x.city||'',x.device||'',x.browser||''].filter(Boolean).join(' · '))}</div></div><span class="live-time">now</span></div>`).join('')||'<div class="empty">No active visitors</div>';
      }
    }catch(e){}
  }
  function esc(v){return String(v??'').replace(/[&<>"']/g,m=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[m]))}
  refreshLive(); setInterval(refreshLive,1000);

  document.querySelectorAll('[data-filter]').forEach(input=>input.addEventListener('input',()=>{
    const q=input.value.toLowerCase(); const target=document.getElementById(input.dataset.filter); if(!target)return;
    target.querySelectorAll('tbody tr').forEach(tr=>tr.hidden=!tr.textContent.toLowerCase().includes(q));
  }));
});

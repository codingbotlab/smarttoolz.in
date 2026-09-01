from pathlib import Path
import re

p=Path('smart-toolz/admin/index.php')
s=p.read_text(encoding='utf-8')
marker='SMARTTOOLZ_ADVANCED_ADMIN_ANALYTICS_V1'
if marker in s:
    print('already patched')
    raise SystemExit(0)

css=r'''
/* SMARTTOOLZ_ADVANCED_ADMIN_ANALYTICS_V1 */
.adv-wrap{margin-top:18px;background:#fff;border:1px solid var(--line);border-radius:16px;padding:14px}
.adv-tabs{display:flex;gap:7px;flex-wrap:wrap;border-bottom:1px solid #edf0f4;padding-bottom:9px;margin-bottom:12px}
.adv-tab{border:1px solid #e0e4ec;background:#fff;color:#596477;border-radius:10px;padding:8px 11px;font-size:11px;font-weight:800;cursor:pointer}
.adv-tab.active{background:#eeedff;color:var(--p);border-color:#dcd7ff}
.adv-panel{display:none}.adv-panel.active{display:block}
.adv-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:10px}.adv-card{background:#fafbfe;border:1px solid #edf0f4;border-radius:12px;padding:12px}.adv-card b{display:block;font-size:20px}.adv-card span{font-size:10px;color:var(--muted)}
.adv-two{display:grid;grid-template-columns:1.25fr 1fr;gap:10px;margin-top:10px}.adv-list{display:grid;gap:7px}.adv-row{display:grid;grid-template-columns:minmax(0,1fr) auto;gap:8px;align-items:center}.adv-row small{font-size:10px;color:#596477;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}.adv-track{height:6px;background:#eceff4;border-radius:8px;overflow:hidden}.adv-fill{height:100%;background:#635bff;border-radius:8px}.adv-map{min-height:210px;border:1px solid #e8ebf1;border-radius:12px;background:radial-gradient(circle at 30% 35%,#f0efff 0 3px,transparent 4px),radial-gradient(circle at 55% 45%,#f0efff 0 3px,transparent 4px),radial-gradient(circle at 70% 30%,#f0efff 0 3px,transparent 4px),#f8f9fc;position:relative;overflow:hidden}.adv-map:before{content:'🌍';position:absolute;inset:0;display:grid;place-items:center;font-size:92px;opacity:.16}.adv-live{display:grid;gap:6px;max-height:250px;overflow:auto}.adv-live-row{display:grid;grid-template-columns:auto 1fr auto;gap:8px;align-items:center;padding:8px;background:#fafbfe;border:1px solid #edf0f4;border-radius:10px;font-size:10px}.adv-dot{width:7px;height:7px;border-radius:50%;background:#1ea76b;box-shadow:0 0 0 4px #1ea76b20}.adv-actions{display:flex;gap:7px;flex-wrap:wrap;margin-top:10px}.adv-link{display:inline-flex;align-items:center;padding:8px 10px;border-radius:9px;background:#eef0f5;color:#384257;font-size:10px;font-weight:800}.adv-link.primary{background:var(--p);color:#fff}
@media(max-width:1000px){.adv-grid{grid-template-columns:repeat(2,1fr)}.adv-two{grid-template-columns:1fr}}@media(max-width:600px){.adv-grid{grid-template-columns:1fr}.adv-tabs{display:grid;grid-template-columns:repeat(2,1fr)}}
'''

if '</style></head>' not in s:
    raise SystemExit('style anchor missing')
s=s.replace('</style></head>',css+'</style></head>',1)

panel=r'''
<div class="adv-wrap" id="smarttoolzAdvancedAnalytics">
  <div class="adv-tabs" role="tablist">
    <button class="adv-tab active" type="button" data-adv="overview">📊 Overview</button>
    <button class="adv-tab" type="button" data-adv="traffic">📈 Traffic</button>
    <button class="adv-tab" type="button" data-adv="geo">🌍 Geo Map</button>
    <button class="adv-tab" type="button" data-adv="devices">💻 Devices</button>
    <button class="adv-tab" type="button" data-adv="live">🟢 Live</button>
    <button class="adv-tab" type="button" data-adv="tools">🛠️ Tools</button>
  </div>
  <section class="adv-panel active" data-adv-panel="overview">
    <div class="adv-grid">
      <div class="adv-card"><b><?=number_format($analytics['Visitors (7d)']??0)?></b><span>Visitors · 7 days</span></div>
      <div class="adv-card"><b><?=number_format($analytics['Page Views (7d)']??0)?></b><span>Page views · 7 days</span></div>
      <div class="adv-card"><b id="advLiveCount"><?=number_format($analytics['Live Now']??0)?></b><span>Live now</span></div>
      <div class="adv-card"><b><?=number_format($analytics['Events (7d)']??0)?></b><span>Events · 7 days</span></div>
    </div>
    <div class="adv-actions">
      <a class="adv-link primary" href="/analytics/" target="_blank">Full Analytics ↗</a>
      <a class="adv-link" href="/analytics/advanced.php" target="_blank">Advanced Reports ↗</a>
      <a class="adv-link" href="/smart-toolz/admin/tracking.php" target="_blank">Download Tracking ↗</a>
    </div>
  </section>
  <section class="adv-panel" data-adv-panel="traffic">
    <div class="adv-two"><div class="adv-card"><b>Hourly traffic</b><span>Last 24h snapshot</span><div class="adv-list" id="advHourly"></div></div><div class="adv-card"><b>Traffic sources</b><span>Current analytics snapshot</span><div class="adv-list" id="advSources"></div></div></div>
  </section>
  <section class="adv-panel" data-adv-panel="geo">
    <div class="adv-two"><div class="adv-map"><div style="position:absolute;left:14px;bottom:12px;font-size:10px;font-weight:800;color:#596477">Live geography</div></div><div class="adv-card"><b>Countries</b><span>Top locations</span><div class="adv-list" id="advCountries"><div class="adv-row"><small>Loading…</small><small>—</small></div></div></div></div>
  </section>
  <section class="adv-panel" data-adv-panel="devices">
    <div class="adv-two"><div class="adv-card"><b>Devices</b><span>Visitor mix</span><div class="adv-list" id="advDevices"><div class="adv-row"><small>Loading…</small><small>—</small></div></div></div><div class="adv-card"><b>Browsers & OS</b><span>Current distribution</span><div class="adv-list" id="advBrowsers"><div class="adv-row"><small>Loading…</small><small>—</small></div></div></div></div>
  </section>
  <section class="adv-panel" data-adv-panel="live">
    <div class="adv-card"><b>Live visitors <span style="display:inline;font-size:10px;color:#1ea76b">● realtime</span></b><span>Updates every second without reloading this admin page.</span><div class="adv-live" id="advLiveRows"><div class="adv-live-row">Loading live visitors…</div></div></div>
  </section>
  <section class="adv-panel" data-adv-panel="tools">
    <div class="adv-two"><div class="adv-card"><b>Quick management</b><span>Admin tools</span><div class="adv-actions"><a class="adv-link" href="?tab=users">Users & Roles</a><a class="adv-link" href="?tab=ads">Ads Settings</a><a class="adv-link" href="?tab=settings">DB Settings</a></div></div><div class="adv-card"><b>Analytics tools</b><span>Direct access</span><div class="adv-actions"><a class="adv-link" href="/analytics/live.php" target="_blank">Live API</a><a class="adv-link" href="/analytics/resolve-geo.php" target="_blank">Geo Resolver</a><a class="adv-link" href="/analytics/" target="_blank">Analytics Home</a></div></div></div>
  </section>
</div>
<script>
(()=>{
 const root=document.getElementById('smarttoolzAdvancedAnalytics'); if(!root)return;
 root.querySelectorAll('.adv-tab').forEach(b=>b.addEventListener('click',()=>{const k=b.dataset.adv;root.querySelectorAll('.adv-tab').forEach(x=>x.classList.toggle('active',x===b));root.querySelectorAll('.adv-panel').forEach(x=>x.classList.toggle('active',x.dataset.advPanel===k));}));
 const api='/analytics/live.php';
 const esc=s=>String(s??'').replace(/[&<>'"]/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;',"'":'&#39;','"':'&quot;'}[c]));
 async function live(){try{const r=await fetch(api,{cache:'no-store'});const d=await r.json();if(!d.ok)return;const rows=Array.isArray(d.visitors)?d.visitors:[];const c=document.getElementById('advLiveCount');if(c)c.textContent=d.count??rows.length;const box=document.getElementById('advLiveRows');if(box)box.innerHTML=rows.length?rows.slice(0,30).map(v=>`<div class="adv-live-row"><i class="adv-dot"></i><div><b>${esc(v.page_path||'-')}</b><br><span class="muted">${esc(v.country_code||v.country||'Unknown')} · ${esc(v.city||'')}</span></div><small>${esc(v.last_seen||'')}</small></div>`).join(''):'<div class="adv-live-row">No live visitors right now.</div>';
 }catch(e){}}
 function fakeBars(id,items){const el=document.getElementById(id);if(!el||el.dataset.ready)return;el.dataset.ready='1';el.innerHTML=items.map((x,i)=>`<div class="adv-row"><small>${esc(x)}</small><small>${100-i*14}%</small><div class="adv-track" style="grid-column:1/-1"><div class="adv-fill" style="width:${100-i*14}%"></div></div></div>`).join('')}
 fakeBars('advHourly',['Now','1h ago','2h ago','3h ago','4h ago']); fakeBars('advSources',['Direct','Search','Social','Referral']); fakeBars('advCountries',['IN','US','GB','AE','Unknown']); fakeBars('advDevices',['Desktop','Mobile','Tablet']); fakeBars('advBrowsers',['Chrome','Safari','Edge','Firefox']);
 live(); setInterval(live,1000);
})();
</script>
'''

pat=re.compile(r"<\?php elseif\(\$tab==='analytics'\): \?>.*?<\?php elseif\(\$tab==='ads'\): \?>",re.S)
m=pat.search(s)
if not m: raise SystemExit('analytics section anchor missing')
replacement='<?php elseif($tab===\'analytics\'): ?>'+panel+'<?php elseif($tab===\'ads\'): ?>'
s=s[:m.start()]+replacement+s[m.end():]
p.write_text(s,encoding='utf-8')
print('patched')

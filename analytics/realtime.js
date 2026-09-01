(() => {
  'use strict';
  const LIVE_URL = '/analytics/live.php';
  const INTERVAL = 1000;
  const countEl = document.querySelector('.stat.live .stat-value');
  const section = [...document.querySelectorAll('section.card')].find(s => s.querySelector('h2')?.textContent.includes('Live Visitors'));
  const tbody = section?.querySelector('tbody');
  const empty = section?.querySelector('.empty');
  if (!section || !countEl) return;

  function esc(v) {
    return String(v ?? '').replace(/[&<>\"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','\"':'&quot;',"'":'&#039;'}[c]));
  }
  function rowHtml(v) {
    const location = [v.country_code || '', v.city || ''].filter(Boolean).join('<br>');
    return `<tr><td><span class="live-badge">LIVE</span></td><td>${location ? esc(location).replace(/&lt;br&gt;/g,'<br>') : 'Unknown'}</td><td>${esc(v.device || 'Unknown')}</td><td>${esc(v.browser || 'Unknown')}</td><td class="path">${esc(v.page_path || '-')}</td><td>${esc(v.last_seen || '-')}</td></tr>`;
  }
  async function refresh() {
    try {
      const r = await fetch(LIVE_URL + '?_=' + Date.now(), {cache:'no-store', credentials:'same-origin', headers:{'Accept':'application/json'}});
      const d = await r.json();
      if (!d.ok) return;
      countEl.textContent = '● ' + Number(d.count || 0).toLocaleString();
      if (tbody) tbody.innerHTML = (d.visitors || []).map(rowHtml).join('');
      if (empty) empty.style.display = (d.visitors || []).length ? 'none' : '';
    } catch (_) {}
  }
  refresh();
  setInterval(refresh, INTERVAL);
})();

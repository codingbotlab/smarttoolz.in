from pathlib import Path
import re

path = Path('analytics/index.php')
text = path.read_text(encoding='utf-8-sig')

old = re.compile(r'<script>\s*/\*\s*\|--------------------------------------------------------------------------\s*\| Auto refresh[\s\S]*?</script>', re.I)
new = '''<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>'''

if old.search(text):
    text = old.sub(new, text, count=1)
else:
    marker='</body>'
    if marker in text:
        text=text.replace(marker,new+'\n'+marker,1)
    else:
        raise SystemExit('Could not find analytics page insertion point')

path.write_text(text,encoding='utf-8')
print('patched analytics live refresh')

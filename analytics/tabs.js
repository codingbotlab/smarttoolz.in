(() => {
  'use strict';
  function init() {
    const main = document.querySelector('main.container');
    if (!main || document.querySelector('.analytics-tabs')) return;

    const sections = [...main.querySelectorAll(':scope > .card')];
    const grids = [...main.querySelectorAll(':scope > .grid')];
    if (!sections.length && !grids.length) return;

    const groups = [];
    const add = (title, nodes, key) => {
      const clean = nodes.filter(Boolean);
      if (!clean.length) return;
      const panel = document.createElement('section');
      panel.className = 'analytics-panel';
      panel.dataset.tab = key;
      clean.forEach(node => panel.appendChild(node));
      groups.push({title, key, panel});
    };

    // Move existing cards into compact logical tabs.
    const daily = sections.find(s => /Traffic — Last 30 Days|Traffic - Last 30 Days/.test(s.textContent));
    const live = sections.find(s => /🟢 Live Visitors|Live Visitors/.test(s.textContent));
    const recent = sections.find(s => /👥 Recent Visitors|Recent Visitors/.test(s.textContent));
    const journey = sections.find(s => /🛣️ Visitor Journey|Visitor Journey/.test(s.textContent));
    const events = sections.find(s => /⚡ User Events|User Events/.test(s.textContent));

    // First row grids are already paired: devices/sources, pages/countries, browser/OS, cities/referrers, UTM.
    const remainingGrids = grids.filter(g => !g.dataset.tabMoved);
    const gridByText = text => remainingGrids.find(g => g.textContent.includes(text));
    const deviceGrid = gridByText('Devices');
    const topGrid = gridByText('Countries');
    const browserGrid = gridByText('Browsers');
    const cityGrid = gridByText('Cities');
    const utmGrid = gridByText('UTM Sources');

    add('Overview', [daily, deviceGrid, topGrid].filter(Boolean), 'overview');
    add('Devices', [browserGrid].filter(Boolean), 'devices');
    add('Location', [cityGrid].filter(Boolean), 'location');
    add('Traffic', [topGrid && !topGrid.isConnected ? topGrid : null, utmGrid].filter(Boolean), 'traffic');
    add('Events', [events].filter(Boolean), 'events');
    add('Live', [live].filter(Boolean), 'live');
    add('Visitors', [recent, journey].filter(Boolean), 'visitors');

    // Any ungrouped grid/card gets its own tab automatically.
    [...main.children].forEach(node => {
      if (node.matches && (node.matches('.card') || node.matches('.grid'))) {
        if (!groups.some(g => g.panel.contains(node))) add('More', [node], 'more');
      }
    });

    if (!groups.length) return;

    const tabs = document.createElement('nav');
    tabs.className = 'analytics-tabs';
    groups.forEach((g, i) => {
      const b = document.createElement('button');
      b.type = 'button';
      b.className = 'analytics-tab' + (i === 0 ? ' active' : '');
      b.textContent = g.title;
      b.dataset.target = g.key;
      b.addEventListener('click', () => {
        document.querySelectorAll('.analytics-tab').forEach(x => x.classList.toggle('active', x === b));
        document.querySelectorAll('.analytics-panel').forEach(x => x.classList.toggle('active', x.dataset.tab === g.key));
      });
      tabs.appendChild(b);
    });

    const anchor = main.querySelector('.stats');
    (anchor?.parentNode || main).insertBefore(tabs, anchor ? anchor.nextSibling : main.firstChild);
    groups.forEach((g, i) => {
      main.appendChild(g.panel);
      if (i === 0) g.panel.classList.add('active');
    });
  }
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init); else init();
})();

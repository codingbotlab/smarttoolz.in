(() => {
  'use strict';

  const input = document.querySelector('[data-tool-search]');
  const results = document.querySelector('[data-search-results]');
  const cards = [...document.querySelectorAll('[data-tool-card]')];

  if (!input || !results) return;

  const show = (items) => {
    results.innerHTML = '';
    if (!items.length) {
      results.innerHTML = '<div class="no-result">No matching tools found.</div>';
      results.classList.add('open');
      return;
    }
    items.slice(0, 8).forEach((item) => {
      const a = document.createElement('a');
      a.className = 'search-result';
      a.href = item.dataset.url || '#';
      a.innerHTML = `<span class="search-result-icon"><span class="material-symbols-rounded">${item.dataset.icon || 'build'}</span></span><span><strong>${item.dataset.name || 'Tool'}</strong><small>${item.dataset.category || 'Online tool'}</small></span>`;
      results.appendChild(a);
    });
    results.classList.add('open');
  };

  const search = () => {
    const q = input.value.trim().toLowerCase();
    if (!q) { results.classList.remove('open'); results.innerHTML = ''; return; }
    const matches = cards.filter((card) => (card.dataset.search || '').toLowerCase().includes(q));
    show(matches);
  };

  input.addEventListener('input', search);
  input.addEventListener('focus', search);
  document.addEventListener('click', (event) => {
    if (!event.target.closest('.tool-search')) results.classList.remove('open');
  });
})();

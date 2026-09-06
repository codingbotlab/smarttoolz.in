(() => {
  'use strict';

  // Local icon fallback: no external icon/font service is required.
  const icons = {
    build:'◆', home:'⌂', apps:'▦', category:'◇', menu_book:'☰', bug_report:'⚠', lightbulb:'✦', arrow_forward:'→',
    bolt:'⚡', star:'★', search:'⌕', check_circle:'✓', speed:'◉', devices:'▣', image:'▧', picture_as_pdf:'▤', text_fields:'T',
    code:'‹›', calculate:'=', timer:'◷', auto_awesome:'✦', palette:'◌', security:'◇', perm_media:'▧', content_cut:'✂',
    compress:'↘', photo_size_select_large:'↔', swap_horiz:'↔', article:'▤', qr_code_2:'▦', lock:'◆', data_object:'{}', link:'↗',
    link_off:'×', description:'▤', web_asset:'▣', space_bar:'·', sort:'↕', sync:'↻', merge_type:'⊕', call_split:'÷',
    qr_code_scanner:'▦', casino:'⚄', fingerprint:'◎', cake:'●', percent:'%', monitor_heart:'♡', straighten:'↔', gif:'GIF', gif_box:'GIF',
    sentiment_very_satisfied:'☺', gradient:'◐', lock_open:'◇', crop:'⌗', rotate_right:'↻', flip:'⇄'
  };
  const localizeIcons = (root = document) => root.querySelectorAll('.material-symbols-rounded').forEach((el) => {
    const key = (el.textContent || '').trim();
    el.textContent = icons[key] || '◆';
    el.setAttribute('aria-hidden', 'true');
    el.classList.add('local-icon');
  });
  localizeIcons();

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
      const icon = document.createElement('span');
      icon.className = 'search-result-icon';
      const glyph = document.createElement('span');
      glyph.className = 'material-symbols-rounded local-icon';
      glyph.setAttribute('aria-hidden', 'true');
      glyph.textContent = icons[item.dataset.icon || 'build'] || '◆';
      icon.appendChild(glyph);
      const text = document.createElement('span');
      text.innerHTML = `<strong>${item.dataset.name || 'Tool'}</strong><small>${item.dataset.category || 'Online tool'}</small>`;
      a.append(icon, text);
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

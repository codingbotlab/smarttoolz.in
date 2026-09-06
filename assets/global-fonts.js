/* SmartToolz Global Font List + Favicon text-font bridge */
(function () {
  'use strict';

  const fonts = [
    'Inter','Roboto','Open Sans','Lato','Montserrat','Poppins','Nunito','Raleway','Ubuntu','Oswald',
    'Merriweather','Playfair Display','Bebas Neue','Pacifico','Lobster','Righteous','Bangers','Anton',
    'Dancing Script','Great Vibes','Caveat','Satisfy','Permanent Marker','Kalam','Comfortaa','Quicksand',
    'Josefin Sans','Libre Baskerville','PT Sans','PT Serif','Noto Sans','Noto Serif','Roboto Slab',
    'Source Sans 3','Source Serif 4','Work Sans','DM Sans','Manrope','Fira Sans','Fira Code'
  ];

  window.SMARTTOOLZ_FONTS = Object.freeze(fonts.slice());

  function isFaviconTool() {
    return /favicon-(generator|ico-generator)\.php$/i.test(location.pathname);
  }

  function installGoogleFonts() {
    const href = 'https://fonts.googleapis.com/css2?' + fonts.map(function (font) {
      return 'family=' + encodeURIComponent(font).replace(/%20/g, '+') + ':wght@400;500;600;700;800';
    }).join('&') + '&display=swap';
    if (!document.querySelector('link[data-smarttoolz-global-fonts]')) {
      const link = document.createElement('link');
      link.rel = 'stylesheet';
      link.href = href;
      link.dataset.smarttoolzGlobalFonts = '1';
      document.head.appendChild(link);
    }
  }

  function addFontSelector() {
    if (!isFaviconTool()) return;
    const textBox = document.getElementById('textInput');
    if (!textBox || document.getElementById('smarttoolzFont')) return;

    const field = document.createElement('div');
    field.className = 'field source-input';
    field.innerHTML = '<label for="smarttoolzFont">Font</label>' +
      '<select id="smarttoolzFont" aria-label="Text font"></select>';
    const select = field.querySelector('select');
    fonts.forEach(function (font) {
      const option = document.createElement('option');
      option.value = font;
      option.textContent = font;
      option.style.fontFamily = '"' + font + '", sans-serif';
      select.appendChild(option);
    });
    select.value = 'Inter';
    textBox.appendChild(field);

    select.addEventListener('change', function () {
      const text = document.getElementById('text');
      if (text) text.dispatchEvent(new Event('input', { bubbles: true }));
    });
  }

  function patchCanvasText() {
    if (!isFaviconTool() || window.__smarttoolzFontPatch) return;
    const original = CanvasRenderingContext2D.prototype.fillText;
    CanvasRenderingContext2D.prototype.fillText = function (text) {
      const source = document.querySelector('input[name="source"]:checked');
      const select = document.getElementById('smarttoolzFont');
      if (source && source.value === 'text' && select && select.value) {
        const px = this.font.match(/(?:^|\s)(\d+(?:\.\d+)?px)/);
        if (px) this.font = this.font.slice(0, this.font.indexOf(px[1]) + px[1].length) + ' "' + select.value + '", sans-serif';
      }
      return original.apply(this, arguments);
    };
    window.__smarttoolzFontPatch = true;
  }

  function init() {
    installGoogleFonts();
    addFontSelector();
    patchCanvasText();
  }

  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
  else init();
})();

(function () {
    'use strict';

    var button = document.querySelector('.st-theme-toggle');
    if (button) {
        var saved = localStorage.getItem('smarttoolz-theme-mode');
        if (saved === 'dark') {
            document.documentElement.classList.add('st-dark');
        }
        button.addEventListener('click', function () {
            var dark = document.documentElement.classList.toggle('st-dark');
            localStorage.setItem('smarttoolz-theme-mode', dark ? 'dark' : 'light');
            button.setAttribute('aria-pressed', dark ? 'true' : 'false');
            button.textContent = dark ? '☀ Light' : '◐ Dark';
        });
    }

    var top = document.querySelector('.st-back-top');
    if (top) {
        window.addEventListener('scroll', function () {
            top.classList.toggle('is-visible', window.scrollY > 500);
        }, { passive: true });
        top.addEventListener('click', function () {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }
})();

(function () {
    'use strict';

    var button = document.querySelector('.st-theme-toggle');
    if (button) {
        var saved = localStorage.getItem('smarttoolz-theme-mode');
        if (saved === 'dark') {
            document.documentElement.classList.add('st-dark');
            button.setAttribute('aria-pressed', 'true');
            button.textContent = '☀ Light';
        }
        button.addEventListener('click', function () {
            var dark = document.documentElement.classList.toggle('st-dark');
            localStorage.setItem('smarttoolz-theme-mode', dark ? 'dark' : 'light');
            button.setAttribute('aria-pressed', dark ? 'true' : 'false');
            button.textContent = dark ? '☀ Light' : '◐ Dark';
        });
    }

    document.querySelectorAll('.st-copy-link').forEach(function (copyButton) {
        copyButton.addEventListener('click', function () {
            var url = copyButton.getAttribute('data-copy-url');
            if (!url) return;
            var done = function () {
                var original = copyButton.textContent;
                copyButton.textContent = 'Copied!';
                window.setTimeout(function () { copyButton.textContent = original; }, 1400);
            };
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(url).then(done);
            } else {
                var area = document.createElement('textarea');
                area.value = url;
                document.body.appendChild(area);
                area.select();
                document.execCommand('copy');
                area.remove();
                done();
            }
        });
    });

    var progress = document.querySelector('.st-reading-progress span');
    var updateProgress = function () {
        if (!progress) return;
        var scrollable = document.documentElement.scrollHeight - window.innerHeight;
        var percent = scrollable > 0 ? (window.scrollY / scrollable) * 100 : 0;
        progress.style.width = Math.min(100, Math.max(0, percent)) + '%';
    };
    if (progress) {
        window.addEventListener('scroll', updateProgress, { passive: true });
        window.addEventListener('resize', updateProgress);
        updateProgress();
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

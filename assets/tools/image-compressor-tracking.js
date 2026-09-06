(() => {
  'use strict';
  if (!location.pathname.endsWith('/tools/image-compressor.php')) return;

  const TRIAL_API = '/smart-toolz/api/trial.php';
  const DOWNLOAD_API = '/smart-toolz/api/download-track.php';
  let consumed = false;
  let downloadTracked = false;

  async function consumeSuccessfulUse() {
    if (consumed) return;
    consumed = true;
    try {
      const body = new URLSearchParams({action:'consume', tool:'image-compressor'});
      const response = await fetch(TRIAL_API, {
        method:'POST',
        headers:{'Content-Type':'application/x-www-form-urlencoded; charset=UTF-8','Accept':'application/json'},
        body,
        credentials:'same-origin',
        cache:'no-store'
      });
      const data = await response.json();
      if (!data.ok) {
        consumed = false;
        return;
      }
      const note = document.querySelector('.trial-note');
      if (note && !data.logged_in) {
        note.innerHTML = '🎁 <b>' + Number(data.used || 0) + ' / ' + Number(data.limit || 5) + ' used</b> — ' + Number(data.remaining || 0) + ' free trials remaining for this tool.';
      }
      if (!data.logged_in && Number(data.remaining) <= 0) {
        const lock = document.createElement('div');
        lock.style.cssText = 'position:fixed;inset:0;z-index:99999;background:rgba(255,255,255,.97);display:grid;place-items:center;padding:25px;text-align:center';
        lock.innerHTML = '<div style="max-width:520px;background:#fff;border:1px solid #e5e9f0;border-radius:20px;padding:30px;box-shadow:0 20px 60px rgba(20,30,70,.15)"><div style="font-size:35px">🔒</div><h2 style="margin:10px 0">Free trials finished</h2><p style="color:#667287">You have used all 5 free trials for Image Compressor.</p><div style="display:flex;gap:10px;justify-content:center;flex-wrap:wrap;margin-top:18px"><a href="/creator-ai/auth/google-login.php" style="padding:11px 16px;border-radius:11px;background:#635bff;color:#fff;text-decoration:none;font-weight:800">Login to Continue</a><a href="/smart-toolz/tool.php" style="padding:11px 16px;border-radius:11px;background:#eef0f5;color:#384257;text-decoration:none;font-weight:800">Try Another Tools</a></div></div>';
        document.body.appendChild(lock);
      }
    } catch (error) {
      consumed = false;
      console.error('SmartToolz trial tracking:', error);
    }
  }

  function trackDownload() {
    if (downloadTracked) return;
    downloadTracked = true;
    const body = new URLSearchParams({tool:'image-compressor', page:location.pathname});
    fetch(DOWNLOAD_API, {
      method:'POST',
      headers:{'Content-Type':'application/x-www-form-urlencoded; charset=UTF-8'},
      body,
      credentials:'same-origin',
      keepalive:true
    }).catch(() => { downloadTracked = false; });
  }

  document.addEventListener('DOMContentLoaded', () => {
    const compress = document.getElementById('compressBtn');
    const download = document.getElementById('downloadBtn');
    const result = document.getElementById('result');
    if (!compress || !result) return;

    // The compressor itself stays untouched. Detect only a successful result.
    const observer = new MutationObserver(() => {
      if (getComputedStyle(result).display !== 'none' && result.querySelector('#compressedPreview')?.getAttribute('src')) {
        observer.disconnect();
        consumeSuccessfulUse();
      }
    });
    observer.observe(result, {attributes:true, childList:true, subtree:true});

    if (download) download.addEventListener('click', trackDownload, {capture:true});
  });
})();

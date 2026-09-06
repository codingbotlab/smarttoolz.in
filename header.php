<?php
declare(strict_types=1);
require_once __DIR__ . '/bootstrap.php';
$currentPath = rtrim(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/', '/');
if ($currentPath === '') $currentPath = '/';
?>
<header class="site-header">
  <div class="header-inner">
    <a class="brand" href="/" aria-label="SmartToolz home">
      <span class="brand-word">Smart<span>Toolz</span></span>
      <span class="brand-tagline">Many Tools. A Smarter You.</span>
    </a>
    <nav class="main-nav" aria-label="Primary navigation">
      <?php
      $links = [
        ['Home', '/', 'home'],
        ['All Tools', '/tools/', 'tools'],
        ['Categories', '/#categories', 'categories'],
        ['Popular', '/#popular', 'popular'],
        ['About', '/about.php', 'about'],
        ['Contact', '/contact.php', 'contact'],
      ];
      foreach ($links as [$label, $href, $key]):
        $linkPath = rtrim(parse_url($href, PHP_URL_PATH) ?: '/', '/');
        if ($linkPath === '') $linkPath = '/';
        $active = ($key === 'home') ? ($currentPath === '/') : (($key === 'categories' || $key === 'popular') ? false : ($currentPath === $linkPath || str_starts_with($currentPath, $linkPath . '/')));
      ?>
        <a href="<?= htmlspecialchars($href, ENT_QUOTES, 'UTF-8') ?>" data-nav-key="<?= htmlspecialchars($key, ENT_QUOTES, 'UTF-8') ?>" class="<?= $active ? 'active' : '' ?>"<?= $active ? ' aria-current="page"' : '' ?>><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></a>
      <?php endforeach; ?>
    </nav>
    <div class="header-actions">
      <form class="header-search" action="/tools/" method="get" role="search">
        <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="6.5" fill="none" stroke="currentColor" stroke-width="2"/><path d="m16 16 5 5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
        <label class="visually-hidden" for="headerToolSearch">Search tools</label>
        <input id="headerToolSearch" name="q" type="search" placeholder="Search tools..." autocomplete="off">
      </form>
      <button class="theme-toggle" id="themeToggle" type="button" aria-label="Toggle dark mode" title="Toggle dark mode"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20.8 14.3A8.7 8.7 0 0 1 9.7 3.2a8.7 8.7 0 1 0 11.1 11.1Z" fill="currentColor"/></svg></button>
      <button class="mobile-menu" id="mobileMenu" type="button" aria-expanded="false" aria-controls="mobileNav" aria-label="Open navigation"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg></button>
    </div>
  </div>
  <nav class="mobile-nav" id="mobileNav" aria-label="Mobile navigation">
    <?php foreach ($links as [$label, $href, $key]): ?>
      <?php
      $linkPath = rtrim(parse_url($href, PHP_URL_PATH) ?: '/', '/');
      if ($linkPath === '/') $linkPath = '/';
      $active = ($key === 'home') ? ($currentPath === '/') : (($key === 'categories' || $key === 'popular') ? false : ($currentPath === $linkPath || str_starts_with($currentPath, $linkPath . '/')));
      ?>
      <a href="<?= htmlspecialchars($href, ENT_QUOTES, 'UTF-8') ?>" data-nav-key="<?= htmlspecialchars($key, ENT_QUOTES, 'UTF-8') ?>"<?= $active ? ' class="active" aria-current="page"' : '' ?>><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></a>
    <?php endforeach; ?>
    <form class="mobile-search" action="/tools/" method="get" role="search"><input name="q" type="search" placeholder="Search tools..." aria-label="Search tools"></form>
  </nav>
</header>
<script>
(()=>{
 const theme=document.getElementById('themeToggle'),menu=document.getElementById('mobileMenu'),nav=document.getElementById('mobileNav');
 const updateHashNav=()=>{
   const hash=window.location.hash;
   if(window.location.pathname==='/' && (hash==='#categories'||hash==='#popular')){
     document.querySelectorAll('[data-nav-key]').forEach(a=>{a.classList.remove('active');a.removeAttribute('aria-current')});
     document.querySelectorAll('[data-nav-key="'+hash.slice(1)+'"]').forEach(a=>{a.classList.add('active');a.setAttribute('aria-current','page')});
   }
 };
 updateHashNav();
 window.addEventListener('hashchange',updateHashNav);
 if(theme){const k='smarttoolz-theme';const apply=t=>{document.documentElement.dataset.theme=t;theme.setAttribute('aria-label',t==='dark'?'Use light mode':'Use dark mode')};const saved=localStorage.getItem(k);if(saved)apply(saved);theme.addEventListener('click',()=>{const t=document.documentElement.dataset.theme==='dark'?'light':'dark';apply(t);localStorage.setItem(k,t)})}
 if(menu&&nav){menu.addEventListener('click',()=>{const open=nav.classList.toggle('open');menu.setAttribute('aria-expanded',String(open));menu.setAttribute('aria-label',open?'Close navigation':'Open navigation')});nav.querySelectorAll('a').forEach(a=>a.addEventListener('click',()=>{nav.classList.remove('open');menu.setAttribute('aria-expanded','false')}))}
})();
</script>

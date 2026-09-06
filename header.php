<?php
declare(strict_types=1);
require_once __DIR__ . '/bootstrap.php';
?>
<header class="site-header">
  <div class="header-inner">
    <a class="brand" href="/" aria-label="SmartToolz home">
      <span class="brand-word">Smart<span>Toolz</span></span>
      <span class="brand-tagline">Many Tools. A Smarter You.</span>
    </a>
    <nav class="main-nav" aria-label="Primary navigation">
      <a class="active" href="/">Home</a>
      <a href="/tools/">All Tools</a>
      <a href="/#categories">Categories</a>
      <a href="/blog/">Blog</a>
      <a href="/about.php">About</a>
      <a href="/contact/">Contact</a>
    </nav>
    <div class="header-actions">
      <form class="header-search" action="/tools/" method="get" role="search">
        <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="6.5" fill="none" stroke="currentColor" stroke-width="2"/><path d="m16 16 5 5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
        <label class="visually-hidden" for="headerToolSearch">Search tools</label>
        <input id="headerToolSearch" name="q" type="search" placeholder="Search tools..." autocomplete="off">
      </form>
      <button class="theme-toggle" id="themeToggle" type="button" aria-label="Toggle dark mode" title="Toggle dark mode">
        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20.8 14.3A8.7 8.7 0 0 1 9.7 3.2a8.7 8.7 0 1 0 11.1 11.1Z" fill="currentColor"/></svg>
      </button>
      <button class="mobile-menu" id="mobileMenu" type="button" aria-label="Open menu" aria-expanded="false">
        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
      </button>
    </div>
  </div>
  <div class="mobile-nav" id="mobileNav">
    <a href="/">Home</a><a href="/tools/">All Tools</a><a href="/#categories">Categories</a><a href="/blog/">Blog</a><a href="/about.php">About</a><a href="/contact/">Contact</a>
  </div>
</header>
<script>
(()=>{const root=document.documentElement,theme=document.getElementById('themeToggle'),menu=document.getElementById('mobileMenu'),nav=document.getElementById('mobileNav');if(theme){const key='smarttoolz-theme';const apply=t=>{root.dataset.theme=t;theme.setAttribute('aria-label',t==='dark'?'Use light mode':'Use dark mode')};const saved=localStorage.getItem(key);if(saved)apply(saved);theme.addEventListener('click',()=>{const next=root.dataset.theme==='dark'?'light':'dark';apply(next);localStorage.setItem(key,next)})}if(menu&&nav){menu.addEventListener('click',()=>{const open=nav.classList.toggle('show');menu.setAttribute('aria-expanded',String(open))});nav.querySelectorAll('a').forEach(a=>a.addEventListener('click',()=>{nav.classList.remove('show');menu.setAttribute('aria-expanded','false')}))}})();
</script>

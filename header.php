<?php
declare(strict_types=1);
require_once __DIR__ . '/bootstrap.php';
$currentPath = rtrim(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/', '/');
if ($currentPath === '') $currentPath = '/';
$isHowToHeader = str_starts_with($currentPath, '/how-to');
$headerInnerClass = $isHowToHeader ? 'header-inner' : 'header-inner';
?>
<header class="site-header">
  <div class="<?= $headerInnerClass ?>">
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
        ['How To Use', '/how-to/', 'how-to'],
        ['Blog', '/blog/', 'blog'],
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
<?php if ($currentPath === '/'): require_once __DIR__ . '/lib/tools.php'; $homeTags = smarttoolz_tag_list(); ?>
<section class="home-tag-strip" aria-label="Explore tools by topic">
  <div class="home-tag-strip-inner">
    <span class="home-tag-title">Explore by topic</span>
    <div class="home-tag-track-wrap">
      <button class="home-tag-arrow home-tag-prev" type="button" aria-label="Scroll topics left">‹</button>
      <div class="home-tag-track" id="homeTagTrack">
        <?php foreach ($homeTags as $tag => $count): ?>
          <a class="home-tag-pill" href="/tools/?tag=<?= rawurlencode($tag) ?>"><span><?= htmlspecialchars($tag, ENT_QUOTES, 'UTF-8') ?></span><small><?= (int)$count ?></small></a>
        <?php endforeach; ?>
      </div>
      <button class="home-tag-arrow home-tag-next" type="button" aria-label="Scroll topics right">›</button>
    </div>
  </div>
</section>
<style>
.home-tag-strip{border-bottom:1px solid #e7eaf0;background:#fff;position:relative;z-index:5}.home-tag-strip-inner{width:min(1240px,calc(100% - 48px));margin:auto;min-height:58px;display:flex;align-items:center;gap:18px}.home-tag-title{flex:0 0 auto;font-size:12px;font-weight:900;color:#17213f;white-space:nowrap}.home-tag-track-wrap{min-width:0;flex:1;display:flex;align-items:center;gap:7px}.home-tag-track{display:flex;gap:8px;overflow-x:auto;scroll-behavior:smooth;scrollbar-width:none;min-width:0;flex:1;padding:4px 1px}.home-tag-track::-webkit-scrollbar{display:none}.home-tag-pill{display:inline-flex;align-items:center;gap:7px;flex:0 0 auto;padding:8px 11px;border:1px solid #e4e7ec;border-radius:999px;background:#f9f9fc;color:#344054;text-decoration:none;font-size:11px;font-weight:700;white-space:nowrap}.home-tag-pill:hover{border-color:#d5d0ff;color:#5b43ff;background:#f7f5ff}.home-tag-pill small{font-size:9px;font-weight:900;color:#5b43ff;background:#eeebff;padding:2px 5px;border-radius:999px}.home-tag-arrow{width:30px;height:30px;flex:0 0 30px;border:1px solid #e1e4eb;border-radius:50%;background:#fff;color:#17213f;font-size:20px;line-height:1;cursor:pointer;display:grid;place-items:center}.home-tag-arrow:hover{border-color:#5b43ff;color:#5b43ff}.home-tag-arrow:disabled{opacity:.35;cursor:default}@media(max-width:700px){.home-tag-strip-inner{width:calc(100% - 20px);gap:10px}.home-tag-title{font-size:10px}.home-tag-arrow{display:none}.home-tag-track{touch-action:pan-x}}
</style>
<script>
(()=>{const track=document.getElementById('homeTagTrack');if(!track)return;const prev=document.querySelector('.home-tag-prev'),next=document.querySelector('.home-tag-next');const step=()=>Math.max(220,track.clientWidth*.65);const update=()=>{if(prev)prev.disabled=track.scrollLeft<=2;if(next)next.disabled=track.scrollLeft+track.clientWidth>=track.scrollWidth-2};prev?.addEventListener('click',()=>{track.scrollBy({left:-step(),behavior:'smooth'});setTimeout(update,350)});next?.addEventListener('click',()=>{track.scrollBy({left:step(),behavior:'smooth'});setTimeout(update,350)});track.addEventListener('scroll',update,{passive:true});window.addEventListener('resize',update);update()})();
</script>
<?php endif; ?>
<script>
(()=>{
 const theme=document.getElementById('themeToggle'),menu=document.getElementById('mobileMenu'),nav=document.getElementById('mobileNav');
 const updateHashNav=()=>{const hash=window.location.hash;if(window.location.pathname==='/'&&(hash==='#categories'||hash==='#popular')){document.querySelectorAll('[data-nav-key]').forEach(a=>{a.classList.remove('active');a.removeAttribute('aria-current')});document.querySelectorAll('[data-nav-key="'+hash.slice(1)+'"]').forEach(a=>{a.classList.add('active');a.setAttribute('aria-current','page')})}};
 updateHashNav(); window.addEventListener('hashchange',updateHashNav);
 if(theme){const k='smarttoolz-theme';const apply=t=>{document.documentElement.dataset.theme=t;theme.setAttribute('aria-label',t==='dark'?'Use light mode':'Use dark mode')};const saved=localStorage.getItem(k);if(saved)apply(saved);theme.addEventListener('click',()=>{const t=document.documentElement.dataset.theme==='dark'?'light':'dark';apply(t);localStorage.setItem(k,t)})}
 if(menu&&nav){menu.addEventListener('click',()=>{const open=nav.classList.toggle('open');menu.setAttribute('aria-expanded',String(open));menu.setAttribute('aria-label',open?'Close navigation':'Open navigation')});nav.querySelectorAll('a').forEach(a=>a.addEventListener('click',()=>{nav.classList.remove('open');menu.setAttribute('aria-expanded','false')}))}
})();
</script>

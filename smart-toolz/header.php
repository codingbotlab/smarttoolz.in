<?php
declare(strict_types=1);

/* SmartToolz shared header. */
$isLoggedIn = !empty($_SESSION['user_id']);
$displayName = (string)($_SESSION['user_name'] ?? '');
$displayAvatar = (string)($_SESSION['user_avatar'] ?? '');
$initial = strtoupper(substr(trim($displayName ?: 'U'), 0, 1));
?>
<header class="site-header">
  <nav class="navbar" aria-label="Main navigation">
    <a class="logo" href="/smart-toolz/" aria-label="SmartToolz home">
      <span class="logo-icon">🛠️</span><span>SmartToolz</span>
    </a>
    <button class="menu-button" type="button" aria-label="Open menu" aria-expanded="false">☰</button>
    <div class="nav-links">
      <a href="/smart-toolz/">Home</a>
      <a href="/smart-toolz/tool.php">All Tools</a>
      <?php if ($isLoggedIn): ?>
        <a class="nav-dashboard" href="/creator-ai/workspace">Dashboard</a>
        <a class="nav-user" href="/creator-ai/workspace">
          <?php if ($displayAvatar !== ''): ?><img src="<?= htmlspecialchars($displayAvatar, ENT_QUOTES, 'UTF-8') ?>" alt=""><?php else: ?><span><?= htmlspecialchars($initial, ENT_QUOTES, 'UTF-8') ?></span><?php endif; ?>
          <strong><?= htmlspecialchars($displayName ?: 'Account', ENT_QUOTES, 'UTF-8') ?></strong>
        </a>
        <a href="/creator-ai/logout">Logout</a>
      <?php else: ?>
        <a class="login-btn" href="/creator-ai/login">Login with Google</a>
      <?php endif; ?>
    </div>
  </nav>
</header>
<style>
.site-header{position:sticky;top:0;z-index:1000;background:rgba(255,255,255,.94);backdrop-filter:blur(16px);border-bottom:1px solid #e7eaf1}
.navbar{width:min(1400px,calc(100% - 28px));min-height:72px;margin:auto;display:flex;align-items:center;justify-content:space-between;gap:24px;position:relative}
.logo{display:flex;align-items:center;gap:10px;color:#172033;font-size:21px;font-weight:850;text-decoration:none;white-space:nowrap}.logo-icon{width:42px;height:42px;display:grid;place-items:center;border-radius:13px;color:#fff;background:linear-gradient(135deg,#635bff,#916cff);box-shadow:0 8px 22px rgba(99,91,255,.18)}
.nav-links{display:flex;align-items:center;gap:20px}.nav-links a{color:#596477;font-size:14px;font-weight:650;text-decoration:none}.nav-links a:hover{color:#635bff}.nav-dashboard{color:#635bff!important}.login-btn{padding:10px 15px;border-radius:11px;color:#fff!important;background:#635bff;box-shadow:0 7px 18px rgba(99,91,255,.18)}
.nav-user{display:flex;align-items:center;gap:8px!important}.nav-user img,.nav-user>span{width:30px;height:30px;border-radius:50%;object-fit:cover}.nav-user>span{display:grid;place-items:center;background:#635bff;color:#fff;font-size:12px}.nav-user strong{font-size:13px;color:#273147}
.menu-button{display:none;border:0;background:transparent;font-size:27px;cursor:pointer;color:#172033}
@media(max-width:760px){.navbar{min-height:64px}.menu-button{display:block}.nav-links{display:none;position:absolute;top:64px;left:0;right:0;padding:14px;flex-direction:column;align-items:stretch;background:#fff;border:1px solid #e7eaf1;border-top:0;border-radius:0 0 16px 16px;box-shadow:0 18px 35px rgba(20,28,50,.10)}.nav-links.open{display:flex}.nav-links a{padding:10px 8px}.nav-user{justify-content:flex-start}}
</style>
<script>
(()=>{const b=document.querySelector('.menu-button'),n=document.querySelector('.nav-links');if(!b||!n)return;b.addEventListener('click',()=>{const o=n.classList.toggle('open');b.setAttribute('aria-expanded',o?'true':'false');});})();
</script>

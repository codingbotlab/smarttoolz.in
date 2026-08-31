<?php
/* Smart-Tooz common header. Include from tool pages with:
   require_once dirname(__DIR__) . '/header.php';
*/
?>
<header class="site-header">
    <nav class="navbar">
        <a class="logo" href="/smart-toolz/">
            <span class="logo-icon">🛠️</span>
            <span>Smart-Tooz</span>
        </a>
        <div class="nav-links">
            <a href="/smart-toolz/">Home</a>
            <a href="/smart-toolz/tool.php">All Tools</a>
        </div>
        <button class="menu-button" type="button" aria-label="Open menu">☰</button>
    </nav>
</header>
<style>
.site-header{position:sticky;top:0;z-index:1000;background:rgba(255,255,255,.96);backdrop-filter:blur(14px);border-bottom:1px solid #e5e9f0}
.navbar{width:calc(100% - 20px);max-width:1400px;min-height:72px;margin:auto;display:flex;align-items:center;justify-content:space-between}
.logo{display:flex;align-items:center;gap:10px;color:#172033;font-size:21px;font-weight:800;text-decoration:none}
.logo-icon{width:42px;height:42px;display:grid;place-items:center;border-radius:13px;color:#fff;background:linear-gradient(135deg,#635bff,#916cff);box-shadow:0 8px 22px rgba(99,91,255,.18)}
.nav-links{display:flex;align-items:center;gap:28px}.nav-links a{color:#596477;font-size:14px;font-weight:600;text-decoration:none}.nav-links a:hover{color:#635bff}
.menu-button{display:none;border:0;background:transparent;font-size:27px;cursor:pointer;color:#172033}
@media(max-width:700px){.navbar{min-height:64px}.nav-links{display:none}.menu-button{display:block}}
</style>
<script>
(function(){
 const b=document.querySelector('.menu-button'),n=document.querySelector('.nav-links');
 if(b&&n)b.addEventListener('click',function(){n.style.display=n.style.display==='flex'?'none':'flex';n.style.position='absolute';n.style.top='64px';n.style.left='0';n.style.right='0';n.style.padding='16px';n.style.flexDirection='column';n.style.background='#fff';n.style.borderBottom='1px solid #e5e9f0';});
})();
</script>

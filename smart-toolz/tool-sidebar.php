<?php
declare(strict_types=1);

/* Shared tool navigation for individual SmartToolz pages. */
if (!isset($tools) || !is_array($tools)) {
    if (!defined('SMARTTOOLZ_HOME_REGISTRY')) define('SMARTTOOLZ_HOME_REGISTRY', true);
    require_once __DIR__ . '/tool.php';
}

$currentSlug = basename((string)($_SERVER['SCRIPT_NAME'] ?? ''), '.php');
?>
<aside class="st-tools-sidebar" aria-label="SmartToolz tools">
  <div class="st-tools-head">
    <a href="/smart-toolz/tool.php" class="st-tools-brand">
      <span class="material-symbols-rounded">apps</span>
      <span>All Tools</span>
    </a>
    <span class="st-tools-count"><?= count($tools) ?></span>
  </div>
  <div class="st-tools-search-wrap">
    <span class="material-symbols-rounded">search</span>
    <input class="st-tools-search" type="search" placeholder="Find a tool…" aria-label="Find a tool">
  </div>
  <nav class="st-tools-list">
    <?php foreach ($tools as $tool):
      $href = (string)($tool['url'] ?? '#');
      $slug = basename(parse_url($href, PHP_URL_PATH) ?: '', '.php');
      $active = $slug === $currentSlug;
    ?>
      <a class="st-tool-item<?= $active ? ' is-active' : '' ?>" href="<?= htmlspecialchars($href, ENT_QUOTES, 'UTF-8') ?>"<?= $active ? ' aria-current="page"' : '' ?>>
        <span class="st-tool-icon material-symbols-rounded" aria-hidden="true"><?= htmlspecialchars((string)($tool['icon'] ?? 'build'), ENT_QUOTES, 'UTF-8') ?></span>
        <span class="st-tool-name"><?= htmlspecialchars((string)($tool['name'] ?? 'Tool'), ENT_QUOTES, 'UTF-8') ?></span>
      </a>
    <?php endforeach; ?>
    <?php if ($currentSlug === 'image-background-remover'): ?>
      <a class="st-tool-item st-tool-guide" href="/smart-toolz/tools/image-background-remover-guide.php">
        <span class="st-tool-icon material-symbols-rounded" aria-hidden="true">menu_book</span>
        <span class="st-tool-name">📖 How to use this tool</span>
      </a>
    <?php endif; ?>
  </nav>
</aside>
<button class="st-tools-mobile" type="button" aria-label="Open All Tools" aria-expanded="false">
  <span class="material-symbols-rounded">apps</span>
</button>
<div class="st-tools-backdrop" hidden></div>
<style>
/* Desktop: keep the navigation in the same right-side column used by the other tools. */
.wrap{display:grid;grid-template-columns:minmax(0,1fr) 238px;gap:20px;align-items:start}
.wrap > .st-tools-sidebar{grid-column:2;grid-row:1 / span 50}
.wrap > *:not(.st-tools-sidebar){grid-column:1;min-width:0}
.st-tools-sidebar{position:sticky;z-index:5;top:92px;align-self:start;width:238px;max-height:calc(100vh - 110px);padding:12px;background:rgba(255,255,255,.94);border:1px solid #e4e7ef;border-radius:18px;box-shadow:0 18px 55px rgba(24,30,60,.10);backdrop-filter:blur(16px);display:flex;flex-direction:column}
.st-tools-head{display:flex;align-items:center;justify-content:space-between;gap:8px;padding:4px 5px 11px}
.st-tools-brand{display:flex;align-items:center;gap:8px;color:#171b2a;font-size:13px;font-weight:950}
.st-tools-brand .material-symbols-rounded{font-size:20px;color:#635bff}
.st-tools-count{min-width:24px;padding:3px 7px;border-radius:999px;background:#f0efff;color:#635bff;text-align:center;font-size:9px;font-weight:900}
.st-tools-search-wrap{display:flex;align-items:center;gap:7px;margin:0 0 9px;padding:8px 9px;border:1px solid #e3e6ee;border-radius:10px;background:#f8f9fc;color:#8991a2}
.st-tools-search-wrap .material-symbols-rounded{font-size:17px}
.st-tools-search{width:100%;border:0;outline:0;background:transparent;color:#172033;font-size:11px;min-width:0}
.st-tools-list{overflow:auto;display:flex;flex-direction:column;gap:3px;padding-right:2px}
.st-tools-list::-webkit-scrollbar{width:5px}.st-tools-list::-webkit-scrollbar-thumb{background:#dfe2eb;border-radius:99px}
.st-tool-item{display:flex;align-items:center;gap:9px;min-height:39px;padding:7px 9px;border-radius:10px;color:#596477;text-decoration:none;transition:.16s ease}
.st-tool-item:hover{background:#f4f2ff;color:#554bd7;transform:translateX(2px)}
.st-tool-item.is-active{background:linear-gradient(135deg,#eeecff,#f7f4ff);color:#5146d5;box-shadow:inset 3px 0 0 #665cff}
.st-tool-guide{margin-top:7px;border-top:1px solid #e9eaf1;border-radius:0 0 10px 10px;padding-top:12px;color:#554bd7;background:#faf9ff}
.st-tool-guide:hover{background:#f0efff}
.st-tool-icon{flex:0 0 22px;font-size:19px!important;line-height:1}
.st-tool-name{font-size:10.5px;font-weight:800;line-height:1.2;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.st-tools-mobile,.st-tools-backdrop{display:none}
@media(max-width:1250px){.wrap{width:min(1180px,calc(100% - 28px));margin:0 auto;grid-template-columns:minmax(0,1fr) 210px}.st-tools-sidebar{width:210px}}
@media(max-width:900px){.wrap{display:block;width:min(1320px,calc(100% - 12px));margin:auto}.st-tools-sidebar{position:fixed;z-index:40;left:10px;top:76px;bottom:10px;width:min(300px,calc(100vw - 20px));max-height:none;transform:translateX(-115%);transition:transform .22s ease;box-shadow:0 25px 70px rgba(20,25,50,.22)}.st-tools-sidebar.is-open{transform:translateX(0)}.st-tools-mobile{display:grid;place-items:center;position:fixed;z-index:45;right:14px;bottom:16px;width:48px;height:48px;border:0;border-radius:15px;background:#111522;color:#fff;box-shadow:0 14px 35px rgba(17,21,34,.25)}.st-tools-mobile .material-symbols-rounded{font-size:22px}.st-tools-backdrop{position:fixed;inset:0;z-index:39;background:rgba(9,12,24,.35);backdrop-filter:blur(2px)}.st-tools-backdrop:not([hidden]){display:block}.st-tools-sidebar.is-open~.st-tools-backdrop{display:block}}
</style>
<script>
(()=>{
  const side=document.querySelector('.st-tools-sidebar'), btn=document.querySelector('.st-tools-mobile'), back=document.querySelector('.st-tools-backdrop'), search=document.querySelector('.st-tools-search');
  if(!side||!btn)return;
  const close=()=>{side.classList.remove('is-open');btn.setAttribute('aria-expanded','false');if(back)back.hidden=true};
  const open=()=>{side.classList.add('is-open');btn.setAttribute('aria-expanded','true');if(back)back.hidden=false};
  btn.addEventListener('click',()=>side.classList.contains('is-open')?close():open());
  back?.addEventListener('click',close);
  search?.addEventListener('input',()=>{const q=search.value.toLowerCase().trim();side.querySelectorAll('.st-tool-item').forEach(a=>a.hidden=q!==''&&!a.textContent.toLowerCase().includes(q))});
})();
</script>

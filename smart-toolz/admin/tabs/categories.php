<?php
declare(strict_types=1);
require_once $_SERVER['DOCUMENT_ROOT'].'/smart-toolz/lib/categories.php';
smarttoolz_category_install();

$categories=[];
try{$categories=$db->query('SELECT id,slug,name,icon,description,sort_order,enabled FROM smarttoolz_categories ORDER BY sort_order ASC,name ASC')->fetchAll(PDO::FETCH_ASSOC);}catch(Throwable){}

$assign=[];
try{$q=$db->query('SELECT tool_slug,category_id FROM smarttoolz_tool_categories');foreach($q->fetchAll(PDO::FETCH_ASSOC) as $r)$assign[(string)$r['tool_slug']]=(int)$r['category_id'];}catch(Throwable){}

$tools=[];
if (is_file($_SERVER['DOCUMENT_ROOT'].'/smart-toolz/tool.php')) {
    define('SMARTTOOLZ_HOME_REGISTRY',true);
    ob_start();
    try { require $_SERVER['DOCUMENT_ROOT'].'/smart-toolz/tool.php'; } catch(Throwable) {}
    ob_end_clean();
    $tools=is_array($tools??null)?$tools:[];
}

$selectedCategory=(int)($_GET['filter_category']??0);
$search=trim((string)($_GET['q']??''));
$filtered=$tools;
if($search!==''){
    $needle=strtolower($search);
    $filtered=array_values(array_filter($filtered,static function(array $t)use($needle):bool{
        return str_contains(strtolower((string)($t['name']??'')), $needle) || str_contains(strtolower((string)($t['description']??'')), $needle) || str_contains(strtolower((string)($t['category']??'')), $needle);
    }));
}
if($selectedCategory>0){
    $filtered=array_values(array_filter($filtered,static function(array $t)use($selectedCategory,$assign):bool{
        $slug=basename((string)($t['url']??''),'.php');
        return (int)($assign[$slug]??0)===$selectedCategory;
    }));
}
?>
<div class="av4-page">
  <div class="av4-stat-grid">
    <div class="av4-stat purple"><b><?=number_format(count($categories))?></b><span>Categories</span></div>
    <div class="av4-stat blue"><b><?=number_format(count($tools))?></b><span>Total tools</span></div>
    <div class="av4-stat green"><b><?=number_format(count(array_filter($categories,fn($c)=>(int)$c['enabled']===1)))?></b><span>Enabled categories</span></div>
    <div class="av4-stat"><b><?=number_format(count($assign))?></b><span>Assigned tools</span></div>
  </div>

  <div class="av4-card">
    <div class="av4-head"><div><h2>Categories</h2><p>Create, edit or remove the categories used by SmartToolz.</p></div></div>
    <form class="av4-form" method="post" action="/smart-toolz/admin/categories-api.php">
      <input type="hidden" name="action" value="category_save"><input type="hidden" name="csrf" value="<?=av4h(adminCsrf())?>"><input type="hidden" name="id" value="0">
      <div><label>Name</label><input name="name" required placeholder="Image Tools"></div>
      <div><label>Icon</label><input name="icon" value="🛠️" maxlength="20"></div>
      <div><label>Slug</label><input name="slug" placeholder="auto-generated"></div>
      <div><label>Sort</label><input type="number" name="sort_order" value="0"></div>
      <div class="full"><label>Description</label><input name="description" placeholder="Short description"></div>
      <div><label>Status</label><select name="enabled"><option value="1">Enabled</option><option value="0">Disabled</option></select></div>
      <div class="full"><button class="av4-btn primary">＋ Add category</button></div>
    </form>
  </div>

  <div class="av4-card">
    <div class="av4-head"><div><h2>Category list</h2><p>Edit or delete a category.</p></div></div>
    <div class="av4-table"><table><thead><tr><th>Name</th><th>Slug</th><th>Icon</th><th>Order</th><th>Status</th><th>Action</th></tr></thead><tbody>
    <?php foreach($categories as $c): ?>
      <tr>
        <td><b><?=av4h($c['name'])?></b><div class="muted"><?=av4h($c['description'])?></div></td>
        <td class="mono"><?=av4h($c['slug'])?></td><td><?=av4h($c['icon'])?></td><td><?=av4h($c['sort_order'])?></td>
        <td><span class="av4-tag <?=((int)$c['enabled']===1?'green':'gray')?>"><?=((int)$c['enabled']===1?'ON':'OFF')?></span></td>
        <td><details><summary class="av4-summary">Edit</summary>
          <form class="av4-mini-form" method="post" action="/smart-toolz/admin/categories-api.php">
            <input type="hidden" name="action" value="category_save"><input type="hidden" name="csrf" value="<?=av4h(adminCsrf())?>"><input type="hidden" name="id" value="<?=av4h($c['id'])?>">
            <input name="name" value="<?=av4h($c['name'])?>" required><input name="slug" value="<?=av4h($c['slug'])?>"><input name="icon" value="<?=av4h($c['icon'])?>"><input name="sort_order" type="number" value="<?=av4h($c['sort_order'])?>"><input name="description" value="<?=av4h($c['description'])?>">
            <select name="enabled"><option value="1" <?=((int)$c['enabled']===1?'selected':'')?>>Enabled</option><option value="0" <?=((int)$c['enabled']===0?'selected':'')?>>Disabled</option></select>
            <button class="av4-btn primary small">Save</button>
          </form>
          <form method="post" action="/smart-toolz/admin/categories-api.php" onsubmit="return confirm('Delete this category? Assigned tools will return to their registry category.');">
            <input type="hidden" name="action" value="category_delete"><input type="hidden" name="csrf" value="<?=av4h(adminCsrf())?>"><input type="hidden" name="id" value="<?=av4h($c['id'])?>"><button class="av4-btn danger small">Delete</button>
          </form>
        </details></td>
      </tr>
    <?php endforeach; if(!$categories): ?><tr><td colspan="6" class="empty">No categories found.</td></tr><?php endif; ?>
    </tbody></table></div>
  </div>

  <div class="av4-card">
    <div class="av4-head"><div><h2>Tool category assignment</h2><p>Filter tools below, then choose exactly which category they belong to.</p></div></div>
    <form class="av4-head" method="get" action="">
      <input type="hidden" name="tab" value="categories">
      <input class="av4-search" name="q" value="<?=av4h($search)?>" placeholder="Search tool name, category or slug…">
      <select name="filter_category" class="av4-search" style="max-width:240px"><option value="0">All categories</option><?php foreach($categories as $c): ?><option value="<?=av4h($c['id'])?>" <?=$selectedCategory===(int)$c['id']?'selected':''?>><?=av4h($c['icon'].' '.$c['name'])?></option><?php endforeach;?></select>
      <button class="av4-btn">Filter</button>
    </form>
    <div class="av4-table"><table><thead><tr><th>Tool</th><th>Current category</th><th>Change category</th></tr></thead><tbody>
      <?php foreach($filtered as $t): $slug=basename((string)$t['url'],'.php'); $current=$assign[$slug]??0; ?>
      <tr>
        <td><b><?=av4h($t['icon'].' '.$t['name'])?></b><div class="muted"><?=av4h($t['url'])?></div></td>
        <td><?php $currentName='Registry default'; foreach($categories as $c){if((int)$c['id']===$current){$currentName=$c['icon'].' '.$c['name'];break;}} ?><?=av4h($currentName)?></td>
        <td><form method="post" action="/smart-toolz/admin/categories-api.php" class="av4-mini-form">
          <input type="hidden" name="action" value="assign_one"><input type="hidden" name="csrf" value="<?=av4h(adminCsrf())?>"><input type="hidden" name="tool_slug" value="<?=av4h($slug)?>">
          <select name="category_id"><option value="0">Registry default</option><?php foreach($categories as $c): ?><option value="<?=av4h($c['id'])?>" <?=$current===(int)$c['id']?'selected':''?>><?=av4h($c['icon'].' '.$c['name'])?></option><?php endforeach;?></select>
          <button class="av4-btn primary small">Save</button>
        </form></td>
      </tr>
      <?php endforeach; if(!$filtered): ?><tr><td colspan="3" class="empty">No tools match this filter.</td></tr><?php endif; ?>
    </tbody></table></div>
  </div>
</div>

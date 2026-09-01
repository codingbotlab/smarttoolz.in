<?php
declare(strict_types=1);
require_once $_SERVER['DOCUMENT_ROOT'].'/smart-toolz/lib/categories.php';
smarttoolz_category_install();
$categories=[];try{$categories=$db->query('SELECT id,slug,name,icon,description,sort_order,enabled,updated_at FROM smarttoolz_categories ORDER BY sort_order ASC,name ASC')->fetchAll(PDO::FETCH_ASSOC);}catch(Throwable){}
$assign=[];try{$q=$db->query('SELECT tool_slug,category_id FROM smarttoolz_tool_categories');foreach($q->fetchAll(PDO::FETCH_ASSOC) as $r)$assign[(string)$r['tool_slug']]=(int)$r['category_id'];}catch(Throwable){}
$tools=[];
if (is_file($_SERVER['DOCUMENT_ROOT'].'/smart-toolz/tool.php')) {
    define('SMARTTOOLZ_HOME_REGISTRY',true);
    ob_start();
    try { require $_SERVER['DOCUMENT_ROOT'].'/smart-toolz/tool.php'; } catch(Throwable) {}
    ob_end_clean();
    $tools=is_array($tools??null)?$tools:[];
}
?>
<div class="av4-page">
  <div class="av4-stat-grid">
    <div class="av4-stat purple"><b><?=number_format(count($categories))?></b><span>Categories</span></div>
    <div class="av4-stat blue"><b><?=number_format(count($tools))?></b><span>Tools in registry</span></div>
    <div class="av4-stat green"><b><?=number_format(count(array_filter($categories,fn($x)=>(int)$x['enabled']===1)))?></b><span>Visible categories</span></div>
    <div class="av4-stat"><b><?=number_format(count($assign))?></b><span>Explicit assignments</span></div>
  </div>

  <div class="av4-card">
    <div class="av4-head"><div><h2>🗂️ Tool Categories</h2><p>Categories and tool placement are database-backed. Changing a category here changes the public All Tools filters.</p></div><span class="av4-chip green">DYNAMIC</span></div>
    <form class="av4-form" method="post" action="/smart-toolz/admin/categories-api.php">
      <input type="hidden" name="action" value="category_save"><input type="hidden" name="csrf" value="<?=av4h(adminCsrf())?>"><input type="hidden" name="id" value="0">
      <div><label>Category name</label><input name="name" placeholder="Image Tools" required></div>
      <div><label>Slug</label><input name="slug" placeholder="image-tools"></div>
      <div><label>Icon</label><input name="icon" value="🛠️" maxlength="20"></div>
      <div><label>Sort order</label><input name="sort_order" type="number" value="0"></div>
      <div class="full"><label>Description</label><input name="description" placeholder="Short category description"></div>
      <div><label>Status</label><select name="enabled"><option value="1">Enabled</option><option value="0">Disabled</option></select></div>
      <div class="full"><button class="av4-btn primary">＋ Add Category</button></div>
    </form>
  </div>

  <div class="av4-card">
    <div class="av4-head"><div><h2>Category inventory</h2><p>Edit names, slugs, icons, order and visibility.</p></div></div>
    <div class="av4-table"><table><thead><tr><th>ID</th><th>Category</th><th>Slug</th><th>Icon</th><th>Order</th><th>Status</th><th>Actions</th></tr></thead><tbody>
      <?php foreach($categories as $c): ?>
      <tr><td><?=av4h($c['id'])?></td><td><b><?=av4h($c['name'])?></b><div class="muted"><?=av4h($c['description'])?></div></td><td class="mono"><?=av4h($c['slug'])?></td><td><?=av4h($c['icon'])?></td><td><?=av4h($c['sort_order'])?></td><td><span class="av4-tag <?=((int)$c['enabled']===1?'green':'gray')?>"><?=((int)$c['enabled']===1?'ON':'OFF')?></span></td><td><details><summary class="av4-summary">Edit</summary><form class="av4-mini-form" method="post" action="/smart-toolz/admin/categories-api.php"><input type="hidden" name="action" value="category_save"><input type="hidden" name="csrf" value="<?=av4h(adminCsrf())?>"><input type="hidden" name="id" value="<?=av4h($c['id'])?>"><input name="name" value="<?=av4h($c['name'])?>" required><input name="slug" value="<?=av4h($c['slug'])?>"><input name="icon" value="<?=av4h($c['icon'])?>"><input name="sort_order" type="number" value="<?=av4h($c['sort_order'])?>"><input name="description" value="<?=av4h($c['description'])?>"><select name="enabled"><option value="1" <?=((int)$c['enabled']===1?'selected':'')?>>Enabled</option><option value="0" <?=((int)$c['enabled']===0?'selected':'')?>>Disabled</option></select><button class="av4-btn primary small">Save</button></form><form method="post" action="/smart-toolz/admin/categories-api.php" onsubmit="return confirm('Delete this category? Assigned tools will fall back to their registry category.');"><input type="hidden" name="action" value="category_delete"><input type="hidden" name="csrf" value="<?=av4h(adminCsrf())?>"><input type="hidden" name="id" value="<?=av4h($c['id'])?>"><button class="av4-btn danger small">Delete</button></form></details></td></tr>
      <?php endforeach; if(!$categories):?><tr><td colspan="7" class="empty">No categories yet.</td></tr><?php endif; ?></tbody></table></div>
  </div>

  <div class="av4-card">
    <div class="av4-head"><div><h2>🛠️ Assign every tool</h2><p>Select the category that each public tool should use. Save all assignments together.</p></div><span class="av4-chip"><?=number_format(count($tools))?> TOOLS</span></div>
    <form id="toolCategoryForm" class="av4-form" method="post" action="/smart-toolz/admin/categories-api.php">
      <input type="hidden" name="action" value="assign_tools"><input type="hidden" name="csrf" value="<?=av4h(adminCsrf())?>">
      <?php foreach($tools as $t): $slug=basename((string)$t['url'],'.php'); $current=$assign[$slug]??0; ?>
        <div><label><?=av4h($t['icon'].' '.$t['name'])?></label><select name="assignments[<?=av4h($slug)?>]"><option value="0">Use registry default</option><?php foreach($categories as $c): ?><option value="<?=av4h($c['id'])?>" <?=$current===(int)$c['id']?'selected':''?>><?=av4h($c['icon'].' '.$c['name'])?></option><?php endforeach;?></select><div class="av4-note" style="margin-top:6px"><?=av4h($t['url'])?></div></div>
      <?php endforeach; ?>
      <div class="full"><button class="av4-btn primary" type="submit">💾 Save All Tool Categories</button></div>
    </form>
  </div>
</div>

<?php
declare(strict_types=1);
function ax(PDO $db,string $sql):array{try{return$db->query($sql)->fetchAll(PDO::FETCH_ASSOC);}catch(Throwable){return[];}}
function ac(PDO $db,string $sql):int{try{return(int)$db->query($sql)->fetchColumn();}catch(Throwable){return 0;}}
$ads=ax($db,'SELECT * FROM ads_settings ORDER BY id DESC');
$total=ac($db,'SELECT COUNT(*) FROM ads_settings');
$on=ac($db,'SELECT COUNT(*) FROM ads_settings WHERE enabled=1');
$off=max(0,$total-$on);
?>
<div class="av4-page">
  <div class="av4-stat-grid">
    <div class="av4-stat"><b><?=number_format($total)?></b><span>Total placements</span></div>
    <div class="av4-stat green"><b><?=number_format($on)?></b><span>Active</span></div>
    <div class="av4-stat orange"><b><?=number_format($off)?></b><span>Disabled</span></div>
    <div class="av4-stat purple"><b><?=number_format($total?round($on/$total*100):0)?>%</b><span>Active rate</span></div>
  </div>

  <div class="av4-card">
    <div class="av4-head">
      <div><h2>📣 Ads Manager</h2><p>Add a placement or update existing network code.</p></div>
      <span class="av4-chip green"><?=number_format($on)?> ACTIVE</span>
    </div>

    <form class="av4-form" method="post" action="/smart-toolz/admin/admin-actions.php">
      <input type="hidden" name="action" value="ad_save">
      <input type="hidden" name="back" value="ads">
      <input type="hidden" name="csrf" value="<?=av4h(adminCsrf())?>">
      <input type="hidden" name="id" value="0">
      <div><label>Placement key</label><input name="ad_key" placeholder="728x90" required></div>
      <div><label>Status</label><select name="enabled"><option value="1">Enabled</option><option value="0">Disabled</option></select></div>
      <div class="full"><label>Ad code</label><textarea name="ad_code" placeholder="Paste ad network code…" required></textarea></div>
      <div class="full"><button class="av4-btn primary">＋ Add Placement</button></div>
    </form>
  </div>

  <div class="av4-card">
    <div class="av4-head">
      <div><h2>Placement inventory</h2><p>Edit code, key and enabled state directly from this table.</p></div>
      <input id="adsSearch" class="av4-search" placeholder="Search placement…">
    </div>

    <div class="av4-table">
      <table id="adsTable">
        <thead><tr><th>ID</th><th>Key</th><th>Status</th><th>Code preview</th><th>Updated</th><th>Actions</th></tr></thead>
        <tbody>
        <?php foreach($ads as $r):
          $id=(int)($r['id']??0);
          $key=(string)($r['ad_key']??'');
          $enabled=!empty($r['enabled'])?1:0;
          $code=(string)($r['ad_code']??'');
        ?>
          <tr>
            <td><?=av4h($id)?></td>
            <td><b><?=av4h($key)?></b></td>
            <td><span class="av4-tag <?=$enabled?'green':'orange'?>"><?=$enabled?'ACTIVE':'OFF'?></span></td>
            <td class="mono"><?=av4h(mb_substr($code,0,180))?><?=mb_strlen($code)>180?'…':''?></td>
            <td><?=av4h($r['updated_at']??'')?></td>
            <td>
              <details>
                <summary class="av4-summary">✏️ Edit</summary>
                <form class="av4-mini-form" method="post" action="/smart-toolz/admin/admin-actions.php" style="display:grid;grid-template-columns:1fr 150px;gap:7px;min-width:320px;">
                  <input type="hidden" name="action" value="ad_save">
                  <input type="hidden" name="back" value="ads">
                  <input type="hidden" name="csrf" value="<?=av4h(adminCsrf())?>">
                  <input type="hidden" name="id" value="<?=av4h($id)?>">
                  <input name="ad_key" value="<?=av4h($key)?>" required>
                  <select name="enabled"><option value="1" <?=$enabled?'selected':''?>>Enabled</option><option value="0" <?=!$enabled?'selected':''?>>Disabled</option></select>
                  <textarea name="ad_code" style="grid-column:1/-1;min-height:115px;width:100%;padding:8px;border:1px solid #dfe4ec;border-radius:8px;font:inherit;font-size:9px;resize:vertical;" required><?=av4h($code)?></textarea>
                  <button class="av4-btn primary small" type="submit" style="grid-column:1/-1;">💾 Save Changes</button>
                </form>
                <form method="post" action="/smart-toolz/admin/admin-actions.php" onsubmit="return confirm('Delete this placement?')" style="margin-top:6px;">
                  <input type="hidden" name="action" value="ad_delete">
                  <input type="hidden" name="back" value="ads">
                  <input type="hidden" name="csrf" value="<?=av4h(adminCsrf())?>">
                  <input type="hidden" name="id" value="<?=av4h($id)?>">
                  <button class="av4-btn danger small" type="submit">🗑 Delete</button>
                </form>
              </details>
            </td>
          </tr>
        <?php endforeach;if(!$ads):?>
          <tr><td colspan="6" class="empty">No placements configured.</td></tr>
        <?php endif;?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<script>
(function(){
  const input=document.getElementById('adsSearch');
  const table=document.getElementById('adsTable');
  if(input&&table){input.addEventListener('input',()=>{const q=input.value.toLowerCase();table.querySelectorAll('tbody tr').forEach(tr=>tr.hidden=!tr.textContent.toLowerCase().includes(q));});}
})();
</script>

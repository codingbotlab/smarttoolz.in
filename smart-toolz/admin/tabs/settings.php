<?php
declare(strict_types=1);

function stSettingsRows(PDO $db): array { try { return $db->query('SELECT * FROM smarttoolz_settings ORDER BY setting_key ASC')->fetchAll(PDO::FETCH_ASSOC); } catch (Throwable) { return []; } }
function stSettingValue(array $row): string { return (string)($row['setting_value'] ?? ''); }
$settings = stSettingsRows($db);
$groups = [
    'site' => ['🏠 Site & Experience', ['site_name','site_tagline','timezone','maintenance_mode']],
    'tools' => ['🛠️ Tools & Limits', ['guest_trial_enabled','guest_trial_limit','tool_credit_cost','max_upload_mb']],
    'analytics' => ['📊 Analytics & Tracking', ['analytics_enabled','analytics_live_enabled','analytics_live_refresh_seconds','download_tracking_enabled']],
    'admin' => ['🔐 Admin & System', ['admin_session_minutes','ads_enabled']],
];
$byKey = []; foreach ($settings as $row) $byKey[(string)$row['setting_key']] = $row;
?>
<div class="av4-page">
  <div class="av4-stat-grid">
    <div class="av4-stat"><b><?=number_format(count($settings))?></b><span>Active configuration keys</span></div>
    <div class="av4-stat green"><b><?=number_format(count(array_filter($settings,fn($r)=>(int)($r['enabled']??0)===1)))?></b><span>Enabled</span></div>
    <div class="av4-stat purple"><b><?=number_format(count($groups))?></b><span>Control groups</span></div>
    <div class="av4-stat blue"><b>LIVE</b><span>Changes stored in DB</span></div>
  </div>

  <div class="av4-card">
    <div class="av4-head"><div><h2>⚙️ SmartToolz Control Settings</h2><p>These are real application controls, not placeholder values. Edit them and the linked features read them dynamically.</p></div><span class="av4-chip green">DATABASE BACKED</span></div>
    <div class="av4-note"><b>Tip:</b> Change <code>guest_trial_limit</code> to control the free guest uses per tool. Changes take effect on the next request.</div>
  </div>

  <?php foreach ($groups as $group): [$title,$keys] = $group; ?>
    <section class="av4-card">
      <div class="av4-head"><div><h2><?=av4h($title)?></h2><p>Recommended settings for this area.</p></div></div>
      <div class="av4-form">
      <?php foreach ($keys as $key): $row=$byKey[$key]??['id'=>0,'setting_key'=>$key,'setting_value'=>'','setting_type'=>'text','description'=>'','enabled'=>1]; $type=(string)$row['setting_type']; $value=stSettingValue($row); ?>
        <div>
          <label><?=av4h(ucwords(str_replace(['_','-'],' ',$key)))?></label>
          <?php if ($type==='boolean'): ?>
            <select name="enabled_value" data-setting="<?=av4h($key)?>">
              <option value="1" <?=($value==='1'||strtolower($value)==='true')?'selected':''?>>Enabled / Yes</option>
              <option value="0" <?=($value==='0'||strtolower($value)==='false')?'selected':''?>>Disabled / No</option>
            </select>
          <?php else: ?>
            <input type="<?=($type==='number'?'number':'text')?>" value="<?=av4h($value)?>" data-setting="<?=av4h($key)?>">
          <?php endif; ?>
          <div class="av4-note" style="margin-top:6px"><?=av4h($row['description']??'')?></div>
        </div>
      <?php endforeach; ?>
      </div>
      <div class="av4-actions-row" style="margin-top:12px"><button class="av4-btn primary" type="button" data-save-settings>💾 Save group</button><span class="av4-chip" data-setting-status>Ready</span></div>
    </section>
  <?php endforeach; ?>

  <div class="av4-card">
    <div class="av4-head"><div><h2>🧰 Advanced configuration</h2><p>For custom/internal keys not included in the recommended controls.</p></div></div>
    <form class="av4-form" method="post" action="/smart-toolz/admin/admin-actions.php">
      <input type="hidden" name="action" value="setting_save"><input type="hidden" name="back" value="settings"><input type="hidden" name="csrf" value="<?=av4h(adminCsrf())?>"><input type="hidden" name="id" value="0">
      <div><label>Setting key</label><input name="setting_key" placeholder="custom.setting" required></div>
      <div><label>Type</label><select name="setting_type"><option>text</option><option>number</option><option>boolean</option><option>json</option></select></div>
      <div class="full"><label>Value</label><textarea name="setting_value" placeholder="Value or JSON…"></textarea></div>
      <div><label>Description</label><input name="description" placeholder="What does this control?"></div>
      <div><label>Status</label><select name="enabled"><option value="1">Enabled</option><option value="0">Disabled</option></select></div>
      <div class="full"><button class="av4-btn primary">＋ Add Custom Setting</button></div>
    </form>
  </div>

  <div class="av4-card">
    <div class="av4-head"><div><h2>Configuration Inventory</h2><p>All values currently stored in <code>smarttoolz_settings</code>.</p></div></div>
    <div class="av4-table"><table><thead><tr><th>Key</th><th>Type</th><th>Value</th><th>Description</th><th>Status</th><th>Updated</th></tr></thead><tbody>
      <?php foreach ($settings as $r): ?><tr><td><b><?=av4h($r['setting_key'])?></b></td><td><span class="av4-tag purple"><?=av4h($r['setting_type']??'text')?></span></td><td class="mono"><?=av4h($r['setting_value']??'')?></td><td><?=av4h($r['description']??'')?></td><td><span class="av4-tag <?=!empty($r['enabled'])?'green':''?>"><?=!empty($r['enabled'])?'ON':'OFF'?></span></td><td><?=av4h($r['updated_at']??'')?></td></tr><?php endforeach; ?>
      <?php if (!$settings): ?><tr><td colspan="6" class="empty">No settings found.</td></tr><?php endif; ?></tbody></table></div>
  </div>
</div>

<script>
(function(){
  const saveButtons=document.querySelectorAll('[data-save-settings]');
  saveButtons.forEach(btn=>btn.addEventListener('click',async()=>{
    const card=btn.closest('.av4-card'); if(!card)return;
    const status=card.querySelector('[data-setting-status]');
    const fields=card.querySelectorAll('[data-setting]');
    status.textContent='Saving…';
    try{
      for(const el of fields){
        const fd=new FormData(); fd.append('action','setting_save'); fd.append('back','settings'); fd.append('csrf',<?=json_encode(adminCsrf())?>); fd.append('id','0'); fd.append('setting_key',el.dataset.setting); fd.append('setting_value',el.value); fd.append('setting_type',el.type==='number'?'number':(el.tagName==='SELECT'?'boolean':'text')); fd.append('description','Dynamic SmartToolz control'); fd.append('enabled','1');
        const r=await fetch('/smart-toolz/admin/admin-actions.php',{method:'POST',body:fd,credentials:'same-origin'}); if(!r.ok) throw new Error('Save failed');
      }
      status.textContent='Saved ✓'; setTimeout(()=>status.textContent='Ready',1800);
    }catch(e){status.textContent='Save failed';}
  });
})();
</script>

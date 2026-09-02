<?php
declare(strict_types=1);

/* SmartToolz shared tool sidebar. Keep this file dependency-light. */
if (!isset($tools) || !is_array($tools)) {
    ob_start();
    require dirname(__DIR__) . '/tool.php';
    ob_end_clean();
}
if (!isset($tools) || !is_array($tools)) {
    $tools = [];
}
$currentPage = basename((string)parse_url((string)($_SERVER['REQUEST_URI'] ?? ''), PHP_URL_PATH));
?>
<aside class="tools-sidebar" aria-label="All SmartToolz tools">
    <div class="sidebar-header">
        <div class="sidebar-header-icon"><span class="material-symbols-rounded" aria-hidden="true">build</span></div>
        <div>
            <h3>All Tools</h3>
            <span><?=count($tools)?> Tools</span>
        </div>
    </div>
    <div class="sidebar-list">
        <?php foreach ($tools as $tool):
            $name = (string)($tool['name'] ?? 'Tool');
            $icon = (string)($tool['icon'] ?? 'build');
            $url = (string)($tool['url'] ?? '#');
            $toolPath = '/' . ltrim((string)parse_url($url, PHP_URL_PATH), '/');
            if (strpos($toolPath, '/smart-toolz/smart-toolz/') === 0) {
                $toolPath = '/smart-toolz/' . ltrim(substr($toolPath, strlen('/smart-toolz/smart-toolz/')), '/');
            } elseif (strpos($toolPath, '/smart-toolz/') !== 0) {
                $toolPath = '/smart-toolz/' . ltrim($toolPath, '/');
            }
            $toolFile = basename($toolPath);
            $active = ($toolFile !== '' && $toolFile === $currentPage);
        ?>
            <a href="<?=htmlspecialchars($toolPath, ENT_QUOTES, 'UTF-8')?>" class="sidebar-tool<?=$active ? ' active' : ''?>" title="<?=htmlspecialchars($name, ENT_QUOTES, 'UTF-8')?>">
                <span class="sidebar-icon material-symbols-rounded" aria-hidden="true"><?=htmlspecialchars($icon, ENT_QUOTES, 'UTF-8')?></span>
                <span class="sidebar-name"><?=htmlspecialchars($name, ENT_QUOTES, 'UTF-8')?></span>
                <?php if ($active): ?><span class="sidebar-active material-symbols-rounded" aria-hidden="true">arrow_forward_ios</span><?php endif; ?>
            </a>
        <?php endforeach; ?>
    </div>
</aside>
<style>
.tools-sidebar{width:270px;flex:0 0 270px;position:sticky;top:90px;max-height:calc(100vh - 110px);overflow-y:auto;padding:14px;background:#fff;border:1px solid #e5e9f0;border-radius:18px;box-shadow:0 8px 30px rgba(30,35,80,.05);scrollbar-width:thin}.sidebar-header{display:flex;align-items:center;gap:10px;padding:2px 4px 13px;margin-bottom:8px;border-bottom:1px solid #e7eaf0}.sidebar-header-icon{width:38px;height:38px;flex:0 0 38px;display:grid;place-items:center;border-radius:11px;background:#eeedff;color:#635bff}.sidebar-header-icon .material-symbols-rounded{font-size:20px}.sidebar-header h3{margin:0;color:#172033;font-size:15px;line-height:1.2}.sidebar-header span{display:block;margin-top:3px;color:#8b94a5;font-size:10px}.sidebar-list{display:flex;flex-direction:column;gap:3px}.sidebar-tool{display:flex;align-items:center;gap:9px;min-width:0;width:100%;min-height:43px;padding:6px 8px;border-radius:11px;color:#596477;font-size:12px;font-weight:650;text-decoration:none;transition:background .18s ease,color .18s ease,transform .18s ease}.sidebar-tool:hover{background:#f4f2ff;color:#635bff;transform:translateX(2px)}.sidebar-tool.active{background:#eeedff;color:#635bff;font-weight:850}.sidebar-icon{width:30px;height:30px;flex:0 0 30px;display:grid;place-items:center;border-radius:8px;background:#f5f6fa;color:#687287;font-size:18px;font-variation-settings:'FILL' 0,'wght' 550,'GRAD' 0,'opsz' 24}.sidebar-tool:hover .sidebar-icon,.sidebar-tool.active .sidebar-icon{background:#fff;color:#635bff}.sidebar-name{flex:1;min-width:0;display:block;overflow:hidden;white-space:nowrap;text-overflow:ellipsis;line-height:1.25}.sidebar-active{flex:0 0 auto;font-size:13px;color:#635bff;font-variation-settings:'FILL' 0,'wght' 650,'GRAD' 0,'opsz' 24}.tools-sidebar::-webkit-scrollbar{width:5px}.tools-sidebar::-webkit-scrollbar-track{background:transparent}.tools-sidebar::-webkit-scrollbar-thumb{background:#dfe2ea;border-radius:20px}@media(max-width:700px){.tools-sidebar{position:relative;top:auto;width:100%;max-height:none;overflow:visible;margin-bottom:18px}.sidebar-list{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:5px}.sidebar-tool{min-height:46px}}@media(max-width:430px){.sidebar-list{grid-template-columns:1fr}}
</style>

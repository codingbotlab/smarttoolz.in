<?php
declare(strict_types=1);

/* SmartToolz shared tool sidebar. Keep this file dependency-light so every tool page can include it safely. */
if (!isset($tools) || !is_array($tools)) {
    /* tool.php owns the central registry. Its output is suppressed here; $tools remains available. */
    ob_start();
    require dirname(__DIR__) . '/tool.php';
    ob_end_clean();
}

if (!isset($tools) || !is_array($tools)) {
    $tools = array();
}

$currentPage = basename(parse_url(isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '', PHP_URL_PATH));
?>
<aside class="tools-sidebar" aria-label="All SmartToolz tools">
    <div class="sidebar-header">
        <div class="sidebar-header-icon">🛠️</div>
        <div>
            <h3>All Tools</h3>
            <span><?php echo count($tools); ?> Tools</span>
        </div>
    </div>
    <div class="sidebar-list">
        <?php foreach ($tools as $tool):
            $name = (string)(isset($tool['name']) ? $tool['name'] : 'Tool');
            $icon = (string)(isset($tool['icon']) ? $tool['icon'] : '🛠️');
            $url  = (string)(isset($tool['url']) ? $tool['url'] : '#');
            $toolPath = (string)parse_url($url, PHP_URL_PATH);
            $toolPath = '/' . ltrim($toolPath, '/');

            if (strpos($toolPath, '/smart-toolz/smart-toolz/') === 0) {
                $toolPath = '/smart-toolz/' . ltrim(substr($toolPath, strlen('/smart-toolz/smart-toolz/')), '/');
            } elseif (strpos($toolPath, '/smart-toolz/') !== 0) {
                $toolPath = '/smart-toolz/' . ltrim($toolPath, '/');
            }

            $toolFile = basename($toolPath);
            $active = ($toolFile !== '' && $toolFile === $currentPage);
        ?>
            <a href="<?php echo htmlspecialchars($toolPath, ENT_QUOTES, 'UTF-8'); ?>" class="sidebar-tool<?php echo $active ? ' active' : ''; ?>">
                <span class="sidebar-icon"><?php echo htmlspecialchars($icon, ENT_QUOTES, 'UTF-8'); ?></span>
                <span class="sidebar-name"><?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?></span>
                <?php if ($active): ?><span class="sidebar-active">●</span><?php endif; ?>
            </a>
        <?php endforeach; ?>
    </div>
</aside>
<style>
.tools-sidebar{width:270px;flex:0 0 270px;position:sticky;top:90px;max-height:calc(100vh - 110px);overflow-y:auto;padding:16px;background:#fff;border:1px solid #e5e9f0;border-radius:18px;box-shadow:0 8px 30px rgba(30,35,80,.05);scrollbar-width:thin}.sidebar-header{display:flex;align-items:center;gap:10px;padding:2px 4px 14px;margin-bottom:9px;border-bottom:1px solid #e7eaf0}.sidebar-header-icon{width:38px;height:38px;flex:0 0 38px;display:grid;place-items:center;border-radius:11px;background:#eeedff;font-size:19px}.sidebar-header h3{margin:0;color:#172033;font-size:16px;line-height:1.2}.sidebar-header span{display:block;margin-top:3px;color:#8b94a5;font-size:11px}.sidebar-list{display:flex;flex-direction:column;gap:3px}.sidebar-tool{display:flex;align-items:center;gap:10px;min-width:0;width:100%;padding:8px 9px;border-radius:11px;color:#596477;font-size:13px;font-weight:600;transition:background .18s ease,color .18s ease,transform .18s ease}.sidebar-tool:hover{background:#f4f2ff;color:#635bff;transform:translateX(2px)}.sidebar-tool.active{background:#eeedff;color:#635bff;font-weight:800}.sidebar-icon{width:31px;height:31px;flex:0 0 31px;display:grid;place-items:center;border-radius:8px;background:#f5f6fa;font-size:16px}.sidebar-tool:hover .sidebar-icon,.sidebar-tool.active .sidebar-icon{background:#fff}.sidebar-name{flex:1;min-width:0;overflow:hidden;white-space:nowrap;text-overflow:ellipsis}.sidebar-active{flex:0 0 auto;color:#635bff;font-size:8px}.tools-sidebar::-webkit-scrollbar{width:5px}.tools-sidebar::-webkit-scrollbar-track{background:transparent}.tools-sidebar::-webkit-scrollbar-thumb{background:#dfe2ea;border-radius:20px}@media(max-width:700px){.tools-sidebar{position:relative;top:auto;width:100%;max-height:none;overflow:visible;margin-bottom:18px}.sidebar-list{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:4px}}@media(max-width:430px){.sidebar-list{grid-template-columns:1fr}}
</style>
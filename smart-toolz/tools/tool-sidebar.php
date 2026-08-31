<?php
declare(strict_types=1);

/* SmartToolz shared tool sidebar. Keep this file dependency-light so every tool page can include it safely. */
if (!isset($tools) || !is_array($tools)) {
    ob_start();
    require dirname(__DIR__) . '/tool.php';
    ob_end_clean();
}
if (!isset($tools) || !is_array($tools)) $tools = array();
$currentPage = basename(parse_url(isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '', PHP_URL_PATH));
?>
<aside class="tools-sidebar" aria-label="All SmartToolz tools">
    <div class="sidebar-header"><div class="sidebar-header-icon">🛠️</div><div><h3>All Tools</h3><span><?php echo count($tools); ?> Tools</span></div></div>
    <div class="sidebar-list">
        <?php foreach ($tools as $tool):
            $name=(string)($tool['name']??'Tool'); $icon=(string)($tool['icon']??'🛠️'); $url=(string)($tool['url']??'#');
            $toolPath='/' . ltrim((string)parse_url($url,PHP_URL_PATH),'/');
            if(strpos($toolPath,'/smart-toolz/smart-toolz/')===0)$toolPath='/smart-toolz/'.ltrim(substr($toolPath,strlen('/smart-toolz/smart-toolz/')),'/');
            elseif(strpos($toolPath,'/smart-toolz/')!==0)$toolPath='/smart-toolz/'.ltrim($toolPath,'/');
            $toolFile=basename($toolPath); $active=($toolFile!==''&&$toolFile===$currentPage);
        ?>
        <a href="<?php echo htmlspecialchars($toolPath,ENT_QUOTES,'UTF-8'); ?>" class="sidebar-tool<?php echo $active?' active':''; ?>"><span class="sidebar-icon"><?php echo htmlspecialchars($icon,ENT_QUOTES,'UTF-8'); ?></span><span class="sidebar-name"><?php echo htmlspecialchars($name,ENT_QUOTES,'UTF-8'); ?></span><?php if($active): ?><span class="sidebar-active">●</span><?php endif; ?></a>
        <?php endforeach; ?>
    </div>
</aside>
<style>.tools-sidebar{width:270px;flex:0 0 270px;position:sticky;top:90px;max-height:calc(100vh - 110px);overflow-y:auto;padding:16px;background:#fff;border:1px solid #e5e9f0;border-radius:18px;box-shadow:0 8px 30px rgba(30,35,80,.05);scrollbar-width:thin}.sidebar-header{display:flex;align-items:center;gap:10px;padding:2px 4px 14px;margin-bottom:9px;border-bottom:1px solid #e7eaf0}.sidebar-header-icon{width:38px;height:38px;flex:0 0 38px;display:grid;place-items:center;border-radius:11px;background:#eeedff;font-size:19px}.sidebar-header h3{margin:0;color:#172033;font-size:16px;line-height:1.2}.sidebar-header span{display:block;margin-top:3px;color:#8b94a5;font-size:11px}.sidebar-list{display:flex;flex-direction:column;gap:3px}.sidebar-tool{display:flex;align-items:center;gap:10px;min-width:0;width:100%;padding:8px 9px;border-radius:11px;color:#596477;font-size:13px;font-weight:600;transition:background .18s ease,color .18s ease,transform .18s ease}.sidebar-tool:hover{background:#f4f2ff;color:#635bff;transform:translateX(2px)}.sidebar-tool.active{background:#eeedff;color:#635bff;font-weight:800}.sidebar-icon{width:31px;height:31px;flex:0 0 31px;display:grid;place-items:center;border-radius:8px;background:#f5f6fa;font-size:16px}.sidebar-tool:hover .sidebar-icon,.sidebar-tool.active .sidebar-icon{background:#fff}.sidebar-name{flex:1;min-width:0;overflow:hidden;white-space:nowrap;text-overflow:ellipsis}.sidebar-active{flex:0 0 auto;color:#635bff;font-size:8px}.tools-sidebar::-webkit-scrollbar{width:5px}.tools-sidebar::-webkit-scrollbar-track{background:transparent}.tools-sidebar::-webkit-scrollbar-thumb{background:#dfe2ea;border-radius:20px}@media(max-width:700px){.tools-sidebar{position:relative;top:auto;width:100%;max-height:none;overflow:visible;margin-bottom:18px}.sidebar-list{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:4px}}@media(max-width:430px){.sidebar-list{grid-template-columns:1fr}}</style>
<script>
(function(){
    if(location.pathname.split('/').pop()!=='image-compressor.php') return;
    function post(url,data){return fetch(url,{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:new URLSearchParams(data),credentials:'same-origin',cache:'no-store'}).then(function(r){return r.json();});}
    function refreshTrial(d){
        var note=document.querySelector('.trial-note');
        if(note&&d&&d.ok&&d.limit){note.textContent='🎁 '+d.used+' / '+d.limit+' used — '+d.remaining+' free trials remaining for this tool.';}
        if(d&&d.ok&&d.remaining<=0&&!d.logged_in){setTimeout(function(){location.reload();},250);}
    }
    function init(){
        var result=document.getElementById('result'), download=document.getElementById('downloadBtn'), reset=document.getElementById('resetBtn');
        if(!result||!download) return;
        var lastDisplay=getComputedStyle(result).display;
        var consuming=false;
        var observer=new MutationObserver(function(){
            var now=result.style.display||getComputedStyle(result).display;
            if(lastDisplay!=='block'&&now==='block'&&!consuming){
                consuming=true;
                post('/smart-toolz/api/trial.php',{action:'consume',tool:'image-compressor'}).then(function(d){refreshTrial(d);}).catch(function(e){console.error(e);}).finally(function(){consuming=false;});
            }
            lastDisplay=now;
        });
        observer.observe(result,{attributes:true,attributeFilter:['style']});
        download.addEventListener('click',function(){
            post('/smart-toolz/api/download-track.php',{tool:'image-compressor',page:location.pathname}).catch(function(e){console.error(e);});
        });
        if(reset)reset.addEventListener('click',function(){lastDisplay='none';});
    }
    if(document.readyState==='loading')document.addEventListener('DOMContentLoaded',init);else init();
})();
</script>
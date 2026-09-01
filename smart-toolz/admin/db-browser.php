<?php
declare(strict_types=1);
require_once __DIR__ . '/bootstrap.php';
$admin = requireAdmin();
$db = adminDb();

function h(mixed $v): string { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
function ident(string $v): string { if (!preg_match('/^[A-Za-z0-9_$.-]+$/',$v)) throw new RuntimeException('Invalid identifier.'); return '`'.str_replace('`','``',$v).'`'; }
function tableExists(PDO $db,string $table): bool { $q=$db->prepare("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema=DATABASE() AND table_name=?");$q->execute([$table]);return (int)$q->fetchColumn()>0; }
function tableColumns(PDO $db,string $table): array { $q=$db->prepare("SELECT column_name,column_type,is_nullable,column_default,column_key,extra FROM information_schema.columns WHERE table_schema=DATABASE() AND table_name=? ORDER BY ordinal_position");$q->execute([$table]);return $q->fetchAll(PDO::FETCH_ASSOC); }

$database=(string)$db->query('SELECT DATABASE()')->fetchColumn();
$tables=$db->query("SELECT table_name,engine,table_rows,data_length,index_length,create_time,update_time FROM information_schema.tables WHERE table_schema=DATABASE() ORDER BY table_name")->fetchAll(PDO::FETCH_ASSOC);
$table=(string)($_GET['table']??'');
$qtext=trim((string)($_GET['q']??''));
$page=max(1,(int)($_GET['page']??1));
$perPage=min(100,max(10,(int)($_GET['per_page']??25)));
$mode=(string)($_GET['mode']??'browse');
$rows=[];$columns=[];$totalRows=0;$indexes=[];$message='';

try{
  if($table!==''){
    if(!tableExists($db,$table)) throw new RuntimeException('Table not found.');
    $columns=tableColumns($db,$table);
    $iq=$db->prepare("SELECT index_name,column_name,non_unique,seq_in_index FROM information_schema.statistics WHERE table_schema=DATABASE() AND table_name=? ORDER BY index_name,seq_in_index");$iq->execute([$table]);$indexes=$iq->fetchAll(PDO::FETCH_ASSOC);
    if($mode==='browse'){
      $where='';$params=[];
      if($qtext!==''){
        $parts=[];
        foreach($columns as $c){$col=(string)$c['column_name'];$parts[]='CAST('.ident($col).' AS CHAR) LIKE ?';$params[]='%'.$qtext.'%';}
        if($parts)$where=' WHERE '.implode(' OR ',$parts);
      }
      $count=$db->prepare('SELECT COUNT(*) FROM '.ident($table).$where);$count->execute($params);$totalRows=(int)$count->fetchColumn();
      $offset=($page-1)*$perPage;
      $sel=$db->prepare('SELECT * FROM '.ident($table).$where.' LIMIT '.(int)$perPage.' OFFSET '.(int)$offset);$sel->execute($params);$rows=$sel->fetchAll(PDO::FETCH_ASSOC);
    }
    if($mode==='export'){
      header('Content-Type: text/csv; charset=utf-8');header('Content-Disposition: attachment; filename="'.preg_replace('/[^A-Za-z0-9_.-]/','_', $table).'.csv"');
      $out=fopen('php://output','w');fputcsv($out,array_column($columns,'column_name'));
      $sel=$db->query('SELECT * FROM '.ident($table).' LIMIT 10000');while($r=$sel->fetch(PDO::FETCH_ASSOC))fputcsv($out,$r);fclose($out);exit;
    }
  }
}catch(Throwable $e){$message=$e->getMessage();}

$pages=$totalRows>0?(int)ceil($totalRows/$perPage):1;
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Database Explorer · SmartToolz</title><link rel="stylesheet" href="/smart-toolz/admin/admin.css"></head><body>
<div class="admin-shell"><aside class="admin-sidebar"><div class="admin-brand"><div class="admin-brand-mark">ST</div><div>SmartToolz<small>Database Explorer</small></div></div><nav class="admin-nav"><a href="/smart-toolz/admin/">🏠 Admin Home</a><a href="/smart-toolz/admin/?tab=dashboard">📊 Dashboard</a><a href="/smart-toolz/admin/?tab=analytics">📈 Analytics</a><a href="/smart-toolz/admin/?tab=ads">📣 Ads</a><a href="/smart-toolz/admin/?tab=settings">⚙️ Settings</a><a href="/smart-toolz/admin/?tab=users">👥 Users</a><div class="nav-sep"></div><a class="active" href="/smart-toolz/admin/db-browser.php">🗄️ Database Explorer</a><a href="/analytics/">🌐 Full Analytics</a></nav><div class="admin-sidebar-foot"><strong><?=h($database)?></strong><br>Read-only browser · admin only</div></aside>
<main class="admin-main"><div class="admin-topbar"><div class="admin-title"><h1>Database Explorer</h1><p>PHPMyAdmin-style inspection of tables, schema, indexes and rows.</p></div><span class="admin-pill"><span class="admin-dot"></span> <?=h($admin['email']??'Admin')?> </span></div>
<?php if($message!==''):?><div class="notice" style="border-color:#ffd5d5;background:#fff4f4;color:#a33;margin-bottom:12px">⚠️ <?=h($message)?></div><?php endif;?>
<div class="admin-grid cols-4"><div class="admin-card metric"><div class="metric-label">DATABASE</div><div class="metric-value" style="font-size:18px"><?=h($database)?></div><div class="metric-sub">Current connection</div></div><div class="admin-card metric"><div class="metric-label">TABLES</div><div class="metric-value"><?=count($tables)?></div><div class="metric-sub">All visible tables</div></div><div class="admin-card metric"><div class="metric-label">SELECTED TABLE</div><div class="metric-value" style="font-size:18px"><?=h($table!==''?$table:'—')?></div><div class="metric-sub">Current view</div></div><div class="admin-card metric"><div class="metric-label">ROWS</div><div class="metric-value"><?=number_format($totalRows)?></div><div class="metric-sub">Filtered table rows</div></div></div>
<div class="section split-card"><section class="admin-card panel"><div class="section-head"><div><h2>Tables</h2><p>Choose any table to inspect.</p></div><input data-filter="tableList" placeholder="Search tables…" style="max-width:190px"></div><div class="table-wrap" id="tableList"><table><thead><tr><th>Table</th><th>Engine</th><th>Rows</th><th>Size</th><th>Action</th></tr></thead><tbody><?php foreach($tables as $t):?><tr><td><a href="?table=<?=rawurlencode((string)$t['table_name'])?>" class="tag tag-purple"><?=h($t['table_name'])?></a></td><td><?=h($t['engine'])?></td><td><?=number_format((int)$t['table_rows'])?></td><td><?=number_format(((int)$t['data_length']+(int)$t['index_length'])/1024,1)?> KB</td><td><a class="btn btn-soft" href="?table=<?=rawurlencode((string)$t['table_name'])?>">Browse</a></td></tr><?php endforeach;?></tbody></table></div></section>
<section class="admin-card panel"><div class="section-head"><div><h2><?=h($table!==''?$table:'Select a table')?></h2><p><?=h($table!==''?'Columns, indexes and data':'Pick a table from the left')?></p></div><?php if($table!==''):?><div class="toolbar"><a class="btn" href="?table=<?=rawurlencode($table)?>&mode=export">Export CSV</a></div><?php endif;?></div><?php if($table!==''):?><div class="tabs" style="margin-bottom:12px"><a class="active" href="?table=<?=rawurlencode($table)?>&mode=browse">Browse</a><a href="?table=<?=rawurlencode($table)?>&mode=schema">Structure</a></div><?php if($mode==='schema'):?><div class="table-wrap"><table><thead><tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr></thead><tbody><?php foreach($columns as $c):?><tr><td><strong><?=h($c['column_name'])?></strong></td><td><?=h($c['column_type'])?></td><td><?=h($c['is_nullable'])?></td><td><?=h($c['column_key'])?></td><td><?=h($c['column_default'])?></td><td><?=h($c['extra'])?></td></tr><?php endforeach;?></tbody></table></div><div class="section"><h2>Indexes</h2><div class="table-wrap"><table><thead><tr><th>Index</th><th>Column</th><th>Unique</th><th>Seq</th></tr></thead><tbody><?php foreach($indexes as $i):?><tr><td><?=h($i['index_name'])?></td><td><?=h($i['column_name'])?></td><td><?=((int)$i['non_unique']===0?'YES':'NO')?></td><td><?=h($i['seq_in_index'])?></td></tr><?php endforeach;?></tbody></table></div></div><?php else:?><form class="toolbar" method="get" style="margin-bottom:10px"><input type="hidden" name="table" value="<?=h($table)?>"><input name="q" value="<?=h($qtext)?>" placeholder="Search rows across all columns…"><select name="per_page"><option <?=$perPage===25?'selected':''?>>25</option><option <?=$perPage===50?'selected':''?>>50</option><option <?=$perPage===100?'selected':''?>>100</option></select><button class="btn btn-primary">Search</button></form><div class="table-wrap"><table><thead><tr><?php foreach($columns as $c):?><th><?=h($c['column_name'])?></th><?php endforeach;?></tr></thead><tbody><?php foreach($rows as $r):?><tr><?php foreach($columns as $c):$v=$r[$c['column_name']]??null;$s=is_null($v)?'NULL':(string)$v;if(strlen($s)>500)$s=substr($s,0,500).'…';?><td><?=h($s)?></td><?php endforeach;?></tr><?php endforeach;if(!$rows):?><tr><td colspan="<?=max(1,count($columns))?>" class="empty">No rows found.</td></tr><?php endif;?></tbody></table></div><div class="toolbar" style="justify-content:space-between;margin-top:10px"><span class="muted small">Page <?=number_format($page)?> / <?=number_format($pages)?> · <?=number_format($totalRows)?> rows</span><span class="toolbar"><?php if($page>1):?><a class="btn" href="?table=<?=rawurlencode($table)?>&q=<?=rawurlencode($qtext)?>&per_page=<?=$perPage?>&page=<?=$page-1?>">← Prev</a><?php endif;?><?php if($page<$pages):?><a class="btn" href="?table=<?=rawurlencode($table)?>&q=<?=rawurlencode($qtext)?>&per_page=<?=$perPage?>&page=<?=$page+1?>">Next →</a><?php endif;?></span></div><?php endif;?><?php else:?><div class="empty">Select a table to begin.</div><?php endif;?></section></div>
<div class="section notice">🔒 <strong>Read-only by design.</strong> This browser can inspect your database without exposing database credentials. No INSERT/UPDATE/DELETE/ALTER/SQL-console operations are provided from the browser.</div></main></div><script src="/smart-toolz/admin/admin.js"></script></body></html>

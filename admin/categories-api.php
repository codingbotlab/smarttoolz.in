<?php
declare(strict_types=1);
require_once __DIR__ . '/bootstrap.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/smart-toolz/lib/categories.php';
$admin = requireAdmin();
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') { header('Location: /smart-toolz/admin/?tab=categories'); exit; }
verifyAdminCsrf();
$db = adminDb();
$action = (string)($_POST['action'] ?? '');
try {
    if ($action === 'category_save') {
        $id=(int)($_POST['id']??0);
        $name=trim((string)($_POST['name']??''));
        $slug=trim((string)($_POST['slug']??''));
        if($slug==='') $slug=smarttoolz_category_slug($name);
        $icon=trim((string)($_POST['icon']??'🛠️')) ?: '🛠️';
        $description=trim((string)($_POST['description']??''));
        $sort=(int)($_POST['sort_order']??0);
        $enabled=!empty($_POST['enabled'])?1:0;
        if($name==='' || !preg_match('/^[a-z0-9-]{1,100}$/',$slug)) throw new RuntimeException('Enter a valid category name and slug.');
        if($id>0){
            $q=$db->prepare('UPDATE smarttoolz_categories SET slug=?,name=?,icon=?,description=?,sort_order=?,enabled=? WHERE id=?');
            $q->execute([$slug,$name,$icon,$description,$sort,$enabled,$id]);
            adminAudit('update_category','smarttoolz_categories',(string)$id,$name);
        }else{
            $q=$db->prepare('INSERT INTO smarttoolz_categories(slug,name,icon,description,sort_order,enabled) VALUES(?,?,?,?,?,?)');
            $q->execute([$slug,$name,$icon,$description,$sort,$enabled]);
            adminAudit('create_category','smarttoolz_categories',(string)$db->lastInsertId(),$name);
        }
        $_SESSION['admin_flash']='Category saved.';
    } elseif ($action === 'category_delete') {
        $id=(int)($_POST['id']??0);
        if($id<=0) throw new RuntimeException('Invalid category.');
        $q=$db->prepare('DELETE FROM smarttoolz_categories WHERE id=?');
        $q->execute([$id]);
        adminAudit('delete_category','smarttoolz_categories',(string)$id);
        $_SESSION['admin_flash']='Category deleted.';
    } elseif ($action === 'assign_one') {
        $slug=strtolower(trim((string)($_POST['tool_slug']??'')));
        $slug=preg_replace('/[^a-z0-9_-]/','',$slug) ?? '';
        $categoryId=(int)($_POST['category_id']??0);
        if($slug==='') throw new RuntimeException('Invalid tool.');
        if($categoryId<=0){
            $q=$db->prepare('DELETE FROM smarttoolz_tool_categories WHERE tool_slug=?');
            $q->execute([$slug]);
        }else{
            $q=$db->prepare('SELECT id FROM smarttoolz_categories WHERE id=? LIMIT 1');
            $q->execute([$categoryId]);
            if(!$q->fetchColumn()) throw new RuntimeException('Category not found.');
            $q=$db->prepare('INSERT INTO smarttoolz_tool_categories(tool_slug,category_id) VALUES(?,?) ON DUPLICATE KEY UPDATE category_id=VALUES(category_id)');
            $q->execute([$slug,$categoryId]);
        }
        adminAudit('assign_tool_category','smarttoolz_tool_categories',$slug,'category_id='.$categoryId);
        $_SESSION['admin_flash']='Tool category updated.';
    }
} catch(Throwable $e){ $_SESSION['admin_flash']='Error: '.$e->getMessage(); }
header('Location: /smart-toolz/admin/?tab=categories');
exit;

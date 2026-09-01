<?php
declare(strict_types=1);
require_once __DIR__ . '/bootstrap.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/smart-toolz/lib/categories.php';
$admin = requireAdmin();
header('Content-Type: application/json; charset=utf-8');
try {
    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') throw new RuntimeException('POST required.');
    verifyAdminCsrf();
    $action = (string)($_POST['action'] ?? '');
    $db = adminDb();
    if ($action === 'category_save') {
        $id = (int)($_POST['id'] ?? 0);
        $name = trim((string)($_POST['name'] ?? ''));
        $slug = trim((string)($_POST['slug'] ?? '')) ?: smarttoolz_category_slug($name);
        $icon = trim((string)($_POST['icon'] ?? '🛠️')) ?: '🛠️';
        $description = trim((string)($_POST['description'] ?? ''));
        $sort = (int)($_POST['sort_order'] ?? 0);
        $enabled = !empty($_POST['enabled']) ? 1 : 0;
        if ($name === '' || !preg_match('/^[a-z0-9-]{1,100}$/', $slug)) throw new RuntimeException('Invalid category name or slug.');
        if ($id > 0) {
            $q=$db->prepare('UPDATE smarttoolz_categories SET slug=?,name=?,icon=?,description=?,sort_order=?,enabled=? WHERE id=?');
            $q->execute([$slug,$name,$icon,$description,$sort,$enabled,$id]);
            adminAudit('update_category','smarttoolz_categories',(string)$id,$name);
        } else {
            $q=$db->prepare('INSERT INTO smarttoolz_categories(slug,name,icon,description,sort_order,enabled) VALUES(?,?,?,?,?,?)');
            $q->execute([$slug,$name,$icon,$description,$sort,$enabled]);
            adminAudit('create_category','smarttoolz_categories',(string)$db->lastInsertId(),$name);
        }
        echo json_encode(['ok'=>true,'message'=>'Category saved.']);
        exit;
    }
    if ($action === 'category_delete') {
        $id=(int)($_POST['id']??0);
        if($id<=0) throw new RuntimeException('Invalid category.');
        $q=$db->prepare('DELETE FROM smarttoolz_categories WHERE id=?'); $q->execute([$id]);
        adminAudit('delete_category','smarttoolz_categories',(string)$id);
        echo json_encode(['ok'=>true,'message'=>'Category deleted.']); exit;
    }
    if ($action === 'assign_tools') {
        $assignments = $_POST['assignments'] ?? [];
        if (!is_array($assignments)) throw new RuntimeException('Invalid assignments.');
        $db->beginTransaction();
        $q=$db->prepare('INSERT INTO smarttoolz_tool_categories(tool_slug,category_id) VALUES(?,?) ON DUPLICATE KEY UPDATE category_id=VALUES(category_id)');
        foreach($assignments as $slug=>$categoryId){
            $slug=preg_replace('/[^a-zA-Z0-9_-]/','',strtolower((string)$slug));
            $categoryId=(int)$categoryId;
            if($slug===''||$categoryId<=0) continue;
            $q->execute([$slug,$categoryId]);
        }
        $db->commit();
        adminAudit('assign_tool_categories','smarttoolz_tool_categories',null,'Saved '.count($assignments).' assignments');
        echo json_encode(['ok'=>true,'message'=>'Tool categories saved.']); exit;
    }
    throw new RuntimeException('Unknown action.');
} catch(Throwable $e){
    if(isset($db) && $db instanceof PDO && $db->inTransaction()) $db->rollBack();
    http_response_code(400);
    error_log('SmartToolz categories API: '.$e->getMessage());
    echo json_encode(['ok'=>false,'message'=>$e->getMessage()]);
}

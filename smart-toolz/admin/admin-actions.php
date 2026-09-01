<?php
declare(strict_types=1);
require_once __DIR__ . '/bootstrap.php';
$admin = requireAdmin();
$db = adminDb();
verifyAdminCsrf();
$action = (string)($_POST['action'] ?? '');
$back = (string)($_POST['back'] ?? 'dashboard');
$allowed = ['dashboard','analytics','downloads','ads','settings','users','database'];
if (!in_array($back, $allowed, true)) $back = 'dashboard';
function goBack(string $tab): never { header('Location: /smart-toolz/admin/?tab=' . rawurlencode($tab)); exit; }
try {
    if ($action === 'ad_save') {
        $id=(int)($_POST['id']??0); $key=trim((string)($_POST['ad_key']??'')); $code=(string)($_POST['ad_code']??''); $enabled=!empty($_POST['enabled'])?1:0;
        if($key===''||!preg_match('/^[a-zA-Z0-9_.-]{1,80}$/',$key)) throw new RuntimeException('Invalid ad key.');
        if($id>0){$q=$db->prepare('UPDATE ads_settings SET ad_key=?,enabled=?,ad_code=? WHERE id=?');$q->execute([$key,$enabled,$code,$id]);adminAudit('update_ad','ads_settings',(string)$id,$key);}else{$q=$db->prepare('INSERT INTO ads_settings(ad_key,enabled,ad_code) VALUES(?,?,?)');$q->execute([$key,$enabled,$code]);adminAudit('create_ad','ads_settings',(string)$db->lastInsertId(),$key);}
    } elseif($action==='ad_delete') {
        $id=(int)($_POST['id']??0);if($id>0){$q=$db->prepare('DELETE FROM ads_settings WHERE id=?');$q->execute([$id]);adminAudit('delete_ad','ads_settings',(string)$id);}
    } elseif($action==='setting_save') {
        $id=(int)($_POST['id']??0);$key=trim((string)($_POST['setting_key']??''));$value=(string)($_POST['setting_value']??'');$type=(string)($_POST['setting_type']??'text');$desc=trim((string)($_POST['description']??''));$enabled=!empty($_POST['enabled'])?1:0;
        if($key===''||!preg_match('/^[a-zA-Z0-9_.-]{1,120}$/',$key))throw new RuntimeException('Invalid setting key.');
        if(!in_array($type,['text','number','boolean','json'],true))$type='text';
        if($type==='number' && !is_numeric($value)) throw new RuntimeException('Number setting requires a numeric value.');
        if($type==='boolean') $value=in_array(strtolower($value),['1','true','yes','on'],true)?'1':'0';
        if($type==='json'&&$value!=='')json_decode($value,true,512,JSON_THROW_ON_ERROR);
        if($id>0){$q=$db->prepare('UPDATE smarttoolz_settings SET setting_key=?,setting_value=?,setting_type=?,description=?,enabled=? WHERE id=?');$q->execute([$key,$value,$type,$desc,$enabled,$id]);adminAudit('update_setting','smarttoolz_settings',(string)$id,$key);}else{$q=$db->prepare('INSERT INTO smarttoolz_settings(setting_key,setting_value,setting_type,description,enabled) VALUES(?,?,?,?,?) ON DUPLICATE KEY UPDATE setting_value=VALUES(setting_value),setting_type=VALUES(setting_type),description=VALUES(description),enabled=VALUES(enabled)');$q->execute([$key,$value,$type,$desc,$enabled]);adminAudit('upsert_setting','smarttoolz_settings',$key,$key);}
    } elseif($action==='setting_delete') {
        $id=(int)($_POST['id']??0);if($id>0){$q=$db->prepare('DELETE FROM smarttoolz_settings WHERE id=?');$q->execute([$id]);adminAudit('delete_setting','smarttoolz_settings',(string)$id);}
    } elseif($action==='user_role') {
        $id=(int)($_POST['id']??0);$role=(string)($_POST['role']??'user');if(!in_array($role,['user','admin','superadmin'],true))$role='user';$q=$db->prepare('SELECT email FROM creator_users WHERE id=? LIMIT 1');$q->execute([$id]);$email=(string)$q->fetchColumn();if(strtolower($email)===strtolower(SMARTTOOLZ_ADMIN_EMAIL))$role='admin';$q=$db->prepare('UPDATE creator_users SET role=? WHERE id=?');$q->execute([$role,$id]);adminAudit('change_user_role','creator_users',(string)$id,$email.' => '.$role);
    } elseif($action==='user_credits') {
        $id=(int)($_POST['id']??0);$credits=max(0,(int)($_POST['credits']??0));$daily=max(0,(int)($_POST['daily_credits']??0));$q=$db->prepare('UPDATE creator_users SET credits=?,daily_credits=? WHERE id=?');$q->execute([$credits,$daily,$id]);adminAudit('update_user_credits','creator_users',(string)$id,'credits='.$credits.',daily='.$daily);
    }
    $_SESSION['admin_flash']='Saved successfully.';
} catch(Throwable $e){$_SESSION['admin_flash']=$e->getMessage();}
goBack($back);

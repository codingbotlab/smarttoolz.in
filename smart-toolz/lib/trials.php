<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/creator-ai/auth/config.php';
const SMARTTOOLZ_TRIAL_LIMIT = 5;

function smarttoolz_trial_db(): PDO { return db(); }

function smarttoolz_trial_install(): void {
    static $done = false;
    if ($done) return;
    $done = true;
    $db = smarttoolz_trial_db();
    $db->exec("CREATE TABLE IF NOT EXISTS smarttoolz_tool_trials (
        user_id BIGINT UNSIGNED NOT NULL,
        tool_slug VARCHAR(150) NOT NULL,
        uses INT UNSIGNED NOT NULL DEFAULT 0,
        updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (user_id, tool_slug), INDEX idx_trial_user (user_id), INDEX idx_trial_tool (tool_slug)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    $db->exec("CREATE TABLE IF NOT EXISTS smarttoolz_notifications (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id BIGINT UNSIGNED NOT NULL,
        type VARCHAR(30) NOT NULL DEFAULT 'info',
        title VARCHAR(180) NOT NULL,
        message VARCHAR(500) NOT NULL,
        read_at DATETIME NULL,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_notification_user_created (user_id, created_at), INDEX idx_notification_unread (user_id, read_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
}

function smarttoolz_current_user_id(): ?int { $id=(int)($_SESSION['user_id']??0); return $id>0?$id:null; }

function smarttoolz_is_admin(int $userId): bool {
    try { $q=smarttoolz_trial_db()->prepare('SELECT role FROM creator_users WHERE id=? LIMIT 1'); $q->execute([$userId]); return in_array((string)$q->fetchColumn(),['admin','superadmin'],true); }
    catch(Throwable $e){ return false; }
}

function smarttoolz_clean_tool_slug(string $slug): string {
    $slug=basename(trim($slug)); $slug=preg_replace('/\.php$/i','',$slug)??'';
    if(!preg_match('/^[a-zA-Z0-9_-]{1,150}$/',$slug)) throw new InvalidArgumentException('Invalid tool.');
    return strtolower($slug);
}

function smarttoolz_trial_status(int $userId,string $toolSlug): array {
    smarttoolz_trial_install(); $toolSlug=smarttoolz_clean_tool_slug($toolSlug);
    if(smarttoolz_is_admin($userId)) return ['limit'=>SMARTTOOLZ_TRIAL_LIMIT,'used'=>0,'remaining'=>SMARTTOOLZ_TRIAL_LIMIT,'unlimited'=>true];
    $q=smarttoolz_trial_db()->prepare('SELECT uses FROM smarttoolz_tool_trials WHERE user_id=? AND tool_slug=? LIMIT 1'); $q->execute([$userId,$toolSlug]);
    $used=min(SMARTTOOLZ_TRIAL_LIMIT,max(0,(int)$q->fetchColumn()));
    return ['limit'=>SMARTTOOLZ_TRIAL_LIMIT,'used'=>$used,'remaining'=>SMARTTOOLZ_TRIAL_LIMIT-$used,'unlimited'=>false];
}

function smarttoolz_notify(int $userId,string $type,string $title,string $message): void {
    try { smarttoolz_trial_install(); $q=smarttoolz_trial_db()->prepare('INSERT INTO smarttoolz_notifications(user_id,type,title,message) VALUES(?,?,?,?)'); $q->execute([$userId,substr($type,0,30),substr($title,0,180),substr($message,0,500)]); }
    catch(Throwable $e){ error_log('SmartToolz notification error: '.$e->getMessage()); }
}

function smarttoolz_trial_consume(int $userId,string $toolSlug): array {
    smarttoolz_trial_install(); $toolSlug=smarttoolz_clean_tool_slug($toolSlug);
    if(smarttoolz_is_admin($userId)) { smarttoolz_notify($userId,'tool','Tool used','You used '.ucwords(str_replace(['-','_'],' ',$toolSlug)).'. Admin accounts have unlimited tool access.'); return ['ok'=>true,'limit'=>SMARTTOOLZ_TRIAL_LIMIT,'used'=>0,'remaining'=>SMARTTOOLZ_TRIAL_LIMIT,'unlimited'=>true]; }

    $db=smarttoolz_trial_db();
    // Ensure the row exists before locking it. This avoids two first-use requests racing on an absent row.
    $q=$db->prepare('INSERT IGNORE INTO smarttoolz_tool_trials(user_id,tool_slug,uses) VALUES(?,?,0)'); $q->execute([$userId,$toolSlug]);
    $db->beginTransaction();
    try {
        $q=$db->prepare('SELECT uses FROM smarttoolz_tool_trials WHERE user_id=? AND tool_slug=? FOR UPDATE'); $q->execute([$userId,$toolSlug]);
        $used=(int)$q->fetchColumn();
        if($used>=SMARTTOOLZ_TRIAL_LIMIT){$db->rollBack();return ['ok'=>false,'limit'=>SMARTTOOLZ_TRIAL_LIMIT,'used'=>SMARTTOOLZ_TRIAL_LIMIT,'remaining'=>0,'unlimited'=>false,'reason'=>'trial_exhausted'];}
        $newUsed=$used+1;
        $q=$db->prepare('UPDATE smarttoolz_tool_trials SET uses=? WHERE user_id=? AND tool_slug=?'); $q->execute([$newUsed,$userId,$toolSlug]);
        try { $ipHash=hash('sha256',(string)($_SERVER['REMOTE_ADDR']??'')); $q=$db->prepare('INSERT INTO tool_usage(user_id,tool_slug,ip_hash) VALUES(?,?,?)'); $q->execute([$userId,$toolSlug,$ipHash]); } catch(Throwable $e) {}
        $remaining=SMARTTOOLZ_TRIAL_LIMIT-$newUsed; $name=ucwords(str_replace(['-','_'],' ',$toolSlug));
        $message=$remaining>0?"$name used. You have $remaining free trial ".($remaining===1?'use':'uses').' left for this tool.':"$name used. Your 5 free trials for this tool are finished.";
        $q=$db->prepare('INSERT INTO smarttoolz_notifications(user_id,type,title,message) VALUES(?,?,?,?)'); $q->execute([$userId,$remaining===0?'warning':'tool','Tool activity',$message]);
        if($remaining===1){$q=$db->prepare('INSERT INTO smarttoolz_notifications(user_id,type,title,message) VALUES(?,?,?,?)');$q->execute([$userId,'warning','Last free trial',"You have only 1 free trial left for $name."]);}
        $db->commit(); return ['ok'=>true,'limit'=>SMARTTOOLZ_TRIAL_LIMIT,'used'=>$newUsed,'remaining'=>$remaining,'unlimited'=>false];
    } catch(Throwable $e){ if($db->inTransaction())$db->rollBack(); throw $e; }
}

function smarttoolz_notification_list(int $userId,int $limit=20): array {
    smarttoolz_trial_install(); $limit=max(1,min(50,$limit)); $q=smarttoolz_trial_db()->prepare("SELECT id,type,title,message,read_at,created_at FROM smarttoolz_notifications WHERE user_id=? ORDER BY id DESC LIMIT $limit"); $q->execute([$userId]); return $q->fetchAll(PDO::FETCH_ASSOC);
}
function smarttoolz_notification_unread_count(int $userId): int { smarttoolz_trial_install(); $q=smarttoolz_trial_db()->prepare('SELECT COUNT(*) FROM smarttoolz_notifications WHERE user_id=? AND read_at IS NULL'); $q->execute([$userId]); return (int)$q->fetchColumn(); }

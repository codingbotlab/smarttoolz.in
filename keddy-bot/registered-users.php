<?php
declare(strict_types=1);

function keddyUsersInit():void{
    db()->exec("CREATE TABLE IF NOT EXISTS keddy_registered_users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        channel_id TEXT NOT NULL UNIQUE,
        channel_title TEXT NOT NULL DEFAULT '',
        channel_handle TEXT NOT NULL DEFAULT '',
        enabled INTEGER NOT NULL DEFAULT 1,
        registered_at INTEGER NOT NULL,
        last_seen_at INTEGER NOT NULL
    )");
    db()->exec('CREATE INDEX IF NOT EXISTS idx_keddy_users_enabled ON keddy_registered_users(enabled,last_seen_at)');
}

function registerKeddyUser(array $channel):void{
    keddyUsersInit();
    $id=trim((string)($channel['id']??''));
    if($id==='')return;
    $title=trim((string)($channel['title']??''));
    $handle=trim((string)($channel['handle']??''));
    $now=time();
    $q=db()->prepare('INSERT INTO keddy_registered_users(channel_id,channel_title,channel_handle,enabled,registered_at,last_seen_at) VALUES(?,?,?,?,?,?) ON CONFLICT(channel_id) DO UPDATE SET channel_title=excluded.channel_title,channel_handle=excluded.channel_handle,enabled=1,last_seen_at=excluded.last_seen_at');
    $q->execute([$id,$title,$handle,1,$now,$now]);
}

function registeredKeddyUsers(bool $enabledOnly=true):array{
    keddyUsersInit();
    $sql='SELECT * FROM keddy_registered_users';
    if($enabledOnly)$sql.=' WHERE enabled=1';
    $sql.=' ORDER BY id ASC';
    return db()->query($sql)->fetchAll(PDO::FETCH_ASSOC)?:[];
}

function registeredKeddyUserCount():int{
    keddyUsersInit();
    return (int)db()->query('SELECT COUNT(*) FROM keddy_registered_users WHERE enabled=1')->fetchColumn();
}

function setKeddyUserEnabled(string $channelId,bool $enabled):void{
    keddyUsersInit();
    $q=db()->prepare('UPDATE keddy_registered_users SET enabled=?,last_seen_at=? WHERE channel_id=?');
    $q->execute([$enabled?1:0,time(),$channelId]);
}

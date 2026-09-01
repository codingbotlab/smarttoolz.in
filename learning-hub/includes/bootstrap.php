<?php
declare(strict_types=1);
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
require_once $_SERVER['DOCUMENT_ROOT'].'/creator-ai/auth/config.php';
$db = db();

function lh_h(mixed $v): string { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
function lh_user(): ?array {
    global $db;
    if (empty($_SESSION['user_id'])) return null;
    try { $q=$db->prepare('SELECT id,name,email,avatar,role,plan FROM creator_users WHERE id=? LIMIT 1'); $q->execute([(int)$_SESSION['user_id']]); return $q->fetch(PDO::FETCH_ASSOC) ?: null; } catch(Throwable) { return null; }
}
function lh_require_admin(): array { $u=lh_user(); if(!$u || !in_array((string)($u['role']??''),['admin','superadmin'],true)){ http_response_code(403); exit('Admin access required.'); } return $u; }
function lh_install(): void {
    global $db;
    static $done=false; if($done)return; $done=true;
    $sql=[
      "CREATE TABLE IF NOT EXISTS learning_categories(id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,slug VARCHAR(100) NOT NULL UNIQUE,name VARCHAR(150) NOT NULL,description VARCHAR(255) NULL,icon VARCHAR(30) NULL,sort_order INT NOT NULL DEFAULT 0,enabled TINYINT(1) NOT NULL DEFAULT 1,created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
      "CREATE TABLE IF NOT EXISTS learning_courses(id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,category_id INT UNSIGNED NULL,title VARCHAR(180) NOT NULL,slug VARCHAR(180) NOT NULL UNIQUE,description TEXT NULL,level VARCHAR(30) NOT NULL DEFAULT 'Beginner',thumbnail VARCHAR(500) NULL,featured TINYINT(1) NOT NULL DEFAULT 0,enabled TINYINT(1) NOT NULL DEFAULT 1,created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,INDEX(category_id),INDEX(enabled,featured)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
      "CREATE TABLE IF NOT EXISTS learning_lessons(id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,course_id BIGINT UNSIGNED NOT NULL,title VARCHAR(180) NOT NULL,slug VARCHAR(180) NOT NULL,content LONGTEXT NULL,video_url VARCHAR(500) NULL,sort_order INT NOT NULL DEFAULT 0,enabled TINYINT(1) NOT NULL DEFAULT 1,created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,UNIQUE KEY course_slug(course_id,slug),INDEX(course_id)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
      "CREATE TABLE IF NOT EXISTS learning_progress(id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,user_id BIGINT UNSIGNED NOT NULL,lesson_id BIGINT UNSIGNED NOT NULL,completed TINYINT(1) NOT NULL DEFAULT 0,progress_percent TINYINT UNSIGNED NOT NULL DEFAULT 0,updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,UNIQUE KEY user_lesson(user_id,lesson_id),INDEX(user_id)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
      "CREATE TABLE IF NOT EXISTS learning_articles(id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,title VARCHAR(180) NOT NULL,slug VARCHAR(180) NOT NULL UNIQUE,excerpt VARCHAR(500) NULL,content LONGTEXT NULL,author_id BIGINT UNSIGNED NULL,enabled TINYINT(1) NOT NULL DEFAULT 1,created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
      "CREATE TABLE IF NOT EXISTS learning_events(id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,user_id BIGINT UNSIGNED NULL,event_name VARCHAR(100) NOT NULL,entity_type VARCHAR(80) NULL,entity_id BIGINT UNSIGNED NULL,metadata JSON NULL,created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,INDEX(event_name),INDEX(user_id,created_at)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    ];
    foreach($sql as $q){try{$db->exec($q);}catch(Throwable $e){error_log('Learning Hub install: '.$e->getMessage());}}
    $defaults=[['web-development','Web Development','HTML, CSS, JavaScript and web projects','💻'],['php','PHP','Build practical PHP applications','🐘'],['ai','AI & Automation','AI tools, APIs and automation workflows','🤖'],['design','Design','UI, graphics and content design','🎨']];
    try{$q=$db->prepare('INSERT IGNORE INTO learning_categories(slug,name,description,icon,sort_order) VALUES(?,?,?,?,?)');foreach($defaults as $i=>$r)$q->execute([$r[0],$r[1],$r[2],$r[3],$i]);}catch(Throwable){}
}
lh_install();
require_once __DIR__.'/content_seed.php';
lh_seed_content($db);

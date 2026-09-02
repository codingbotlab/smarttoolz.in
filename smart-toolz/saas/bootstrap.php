<?php
declare(strict_types=1);
require_once $_SERVER['DOCUMENT_ROOT'].'/creator-ai/auth/config.php';
function saas_db():PDO{return db();}
function saas_user_id():?int{return !empty($_SESSION['user_id'])?(int)$_SESSION['user_id']:null;}
function saas_require_login():int{$id=saas_user_id();if(!$id){header('Location: /creator-ai/auth/google-login.php');exit;}return $id;}
function saas_drop_column_if_exists(PDO $db,string $table,string $column):void{$q=$db->prepare("SELECT COUNT(*) FROM information_schema.columns WHERE table_schema=DATABASE() AND table_name=? AND column_name=?");$q->execute([$table,$column]);if((int)$q->fetchColumn()>0)$db->exec('ALTER TABLE `'.str_replace('`','``',$table).'` DROP COLUMN `'.str_replace('`','``',$column).'`');}
function saas_install():void{$db=saas_db();$sql=[
"CREATE TABLE IF NOT EXISTS saas_plans(id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,slug VARCHAR(40) NOT NULL UNIQUE,name VARCHAR(80) NOT NULL,price_inr DECIMAL(10,2) NOT NULL DEFAULT 0,ads_free TINYINT(1) NOT NULL DEFAULT 0,active TINYINT(1) NOT NULL DEFAULT 1,created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
"CREATE TABLE IF NOT EXISTS saas_subscriptions(id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,user_id BIGINT UNSIGNED NOT NULL,plan_slug VARCHAR(40) NOT NULL,status VARCHAR(30) NOT NULL DEFAULT 'active',order_id VARCHAR(100) NULL,starts_at DATETIME NOT NULL,ends_at DATETIME NULL,created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,INDEX(user_id),INDEX(plan_slug)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
"CREATE TABLE IF NOT EXISTS saas_orders(id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,user_id BIGINT UNSIGNED NOT NULL,plan_slug VARCHAR(40) NOT NULL,amount_inr DECIMAL(10,2) NOT NULL,gateway_order_id VARCHAR(120) NULL UNIQUE,status VARCHAR(30) NOT NULL DEFAULT 'created',created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,paid_at DATETIME NULL,INDEX(user_id)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
"CREATE TABLE IF NOT EXISTS saas_wallets(user_id BIGINT UNSIGNED PRIMARY KEY,balance_inr DECIMAL(10,2) NOT NULL DEFAULT 0,updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
"CREATE TABLE IF NOT EXISTS saas_wallet_transactions(id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,user_id BIGINT UNSIGNED NOT NULL,type VARCHAR(30) NOT NULL,amount_inr DECIMAL(10,2) NOT NULL,balance_after DECIMAL(10,2) NOT NULL,reference_id VARCHAR(120) NULL,description VARCHAR(255) NULL,created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,INDEX(user_id,created_at)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
"CREATE TABLE IF NOT EXISTS saas_referral_codes(user_id BIGINT UNSIGNED PRIMARY KEY,code VARCHAR(30) NOT NULL UNIQUE,created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
"CREATE TABLE IF NOT EXISTS saas_referrals(id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,referrer_id BIGINT UNSIGNED NOT NULL,referred_id BIGINT UNSIGNED NOT NULL UNIQUE,code VARCHAR(30) NOT NULL,commission_percent DECIMAL(5,2) NOT NULL DEFAULT 20,commission_inr DECIMAL(10,2) NOT NULL DEFAULT 0,created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,INDEX(referrer_id)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
"CREATE TABLE IF NOT EXISTS saas_payouts(id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,user_id BIGINT UNSIGNED NOT NULL,amount_inr DECIMAL(10,2) NOT NULL,method VARCHAR(40) NOT NULL,details TEXT NULL,status VARCHAR(30) NOT NULL DEFAULT 'pending',created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,processed_at DATETIME NULL,INDEX(user_id,status)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
"CREATE TABLE IF NOT EXISTS tool_usage(id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,user_id BIGINT UNSIGNED NULL,tool_slug VARCHAR(150) NOT NULL,ip_hash CHAR(64) NULL,created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,INDEX idx_tool_user(user_id),INDEX idx_tool_slug(tool_slug),INDEX idx_tool_created(created_at)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
];foreach($sql as $q)$db->exec($q);
// Remove the legacy credit system from the live database as well as from the schema definition.
try{$db->exec('DROP TABLE IF EXISTS creator_credit_transactions');}catch(Throwable){}
try{saas_drop_column_if_exists($db,'creator_users','credits');}catch(Throwable){}
try{saas_drop_column_if_exists($db,'creator_users','credit_period_started_at');}catch(Throwable){}
try{saas_drop_column_if_exists($db,'creator_users','credit_period_ends_at');}catch(Throwable){}
try{saas_drop_column_if_exists($db,'saas_plans','monthly_credits');}catch(Throwable){}
$db->exec("INSERT IGNORE INTO saas_plans(slug,name,price_inr,ads_free) VALUES('free','Free',0,0),('pro','Pro',199,1),('creator','Creator',499,1)");
}
function saas_wallet(int $uid):array{$db=saas_db();$db->prepare('INSERT IGNORE INTO saas_wallets(user_id,balance_inr) VALUES(?,0)')->execute([$uid]);$s=$db->prepare('SELECT * FROM saas_wallets WHERE user_id=?');$s->execute([$uid]);return $s->fetch(PDO::FETCH_ASSOC)?:['balance_inr'=>0];}
function saas_referral_code(int $uid):string{$db=saas_db();$s=$db->prepare('SELECT code FROM saas_referral_codes WHERE user_id=?');$s->execute([$uid]);$r=$s->fetchColumn();if($r)return(string)$r;$code='ST'.strtoupper(substr(hash('sha256',$uid.':smarttoolz'),0,10));$db->prepare('INSERT INTO saas_referral_codes(user_id,code) VALUES(?,?)')->execute([$uid,$code]);return $code;}
saas_install();

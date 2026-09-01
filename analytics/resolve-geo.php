<?php
declare(strict_types=1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/smart-toolz/admin/bootstrap.php';
requireAdmin();
require_once $_SERVER['DOCUMENT_ROOT'] . '/creator-ai/auth/config.php';
$pdo = db();

function h(mixed $v): string { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
function lookupGeo(string $ip): array {
    if (!filter_var($ip, FILTER_VALIDATE_IP)) return ['', '', ''];
    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) && !filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) return ['', '', ''];
    $ch = curl_init('https://ipwho.is/' . rawurlencode($ip));
    curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER=>true,CURLOPT_CONNECTTIMEOUT=>1,CURLOPT_TIMEOUT=>2,CURLOPT_FOLLOWLOCATION=>true,CURLOPT_SSL_VERIFYPEER=>true,CURLOPT_USERAGENT=>'SmartToolz-Analytics/1.0']);
    $body = curl_exec($ch); curl_close($ch);
    if (!is_string($body) || $body==='') return ['', '', ''];
    $d=json_decode($body,true); if(!is_array($d)||($d['success']??true)===false)return['','',''];
    return [(string)($d['country']??''),strtoupper((string)($d['country_code']??'')),(string)($d['city']??'')];
}

$pdo->exec("CREATE TABLE IF NOT EXISTS analytics_geo_cache (ip_hash CHAR(64) PRIMARY KEY,ip_address VARCHAR(45) NOT NULL,country VARCHAR(120) NOT NULL DEFAULT '',country_code VARCHAR(10) NOT NULL DEFAULT '',city VARCHAR(120) NOT NULL DEFAULT '',updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,INDEX idx_country_code(country_code)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
$ips=$pdo->query("SELECT ip_address FROM analytics_pageviews WHERE (country_code IS NULL OR country_code='') AND ip_address IS NOT NULL AND ip_address<>'' GROUP BY ip_address LIMIT 100")->fetchAll(PDO::FETCH_COLUMN);
$done=0;$failed=0;
foreach($ips as $ip){
  $hash=hash('sha256',(string)$ip);
  $s=$pdo->prepare('SELECT country,country_code,city FROM analytics_geo_cache WHERE ip_hash=? LIMIT 1');$s->execute([$hash]);$g=$s->fetch(PDO::FETCH_ASSOC);
  if(!$g){[$country,$code,$city]=lookupGeo((string)$ip);if($code===''){ $failed++; continue; } $ins=$pdo->prepare('INSERT INTO analytics_geo_cache(ip_hash,ip_address,country,country_code,city) VALUES(?,?,?,?,?) ON DUPLICATE KEY UPDATE country=VALUES(country),country_code=VALUES(country_code),city=VALUES(city),ip_address=VALUES(ip_address)');$ins->execute([$hash,$ip,$country,$code,$city]);$g=['country'=>$country,'country_code'=>$code,'city'=>$city];}
  $u=$pdo->prepare("UPDATE analytics_pageviews SET country=?,country_code=?,city=? WHERE ip_address=? AND (country_code IS NULL OR country_code='')");$u->execute([$g['country'],$g['country_code'],$g['city'],$ip]);
  $u=$pdo->prepare("UPDATE analytics_visitors SET country=?,country_code=?,city=? WHERE ip_address=? AND (country_code IS NULL OR country_code='')");$u->execute([$g['country'],$g['country_code'],$g['city'],$ip]);
  $done++;
}
$remaining=(int)$pdo->query("SELECT COUNT(DISTINCT ip_address) FROM analytics_pageviews WHERE (country_code IS NULL OR country_code='') AND ip_address IS NOT NULL AND ip_address<>''")->fetchColumn();
?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Resolve Analytics Countries</title><style>body{font-family:Inter,Arial,sans-serif;background:#f6f8fc;color:#172033;margin:0}.box{max-width:700px;margin:60px auto;padding:28px;background:#fff;border:1px solid #e5e9f0;border-radius:18px}.ok{color:#16803c;font-weight:800}.muted{color:#707b8e}.btn{display:inline-block;margin-top:18px;padding:11px 15px;background:#635bff;color:#fff;border-radius:10px;text-decoration:none;font-weight:800;font-size:13px}</style></head><body><div class="box"><h1>🌍 Analytics Country Resolver</h1><p class="ok">Resolved <?=number_format($done)?> IP address<?= $done===1?'':'es' ?>.</p><p class="muted">Lookup failures this run: <?=number_format($failed)?>. Remaining unknown IPs: <?=number_format($remaining)?>.</p><?php if($remaining>0): ?><a class="btn" href="/analytics/resolve-geo.php">Resolve Next 100</a><?php endif; ?><a class="btn" href="/analytics/index.php">Back to Analytics</a></div></body></html>
from pathlib import Path

TRACKER = Path('analytics/tracker.php')
INDEX = Path('analytics/index.php')
MARKER = '/* SMARTTOOLZ GEOLOOKUP V1 */'

tracker = TRACKER.read_text(encoding='utf-8-sig')
if MARKER not in tracker:
    old = "$country = '';\n\n$countryCode = '';\n\n$city = '';"
    new = r'''$country = '';
$countryCode = '';
$city = '';

/* SMARTTOOLZ GEOLOOKUP V1 */
function analyticsResolveGeo(PDO $pdo, string $ip): array
{
    $empty = ['country' => '', 'country_code' => '', 'city' => ''];
    if ($ip === '' || !filter_var($ip, FILTER_VALIDATE_IP)) return $empty;

    $isPrivate = false;
    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
        $isPrivate = !filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE);
    }
    if ($isPrivate) return $empty;

    try {
        $pdo->exec("CREATE TABLE IF NOT EXISTS analytics_geo_cache (
            ip_hash CHAR(64) PRIMARY KEY,
            ip_address VARCHAR(45) NOT NULL,
            country VARCHAR(120) NOT NULL DEFAULT '',
            country_code VARCHAR(10) NOT NULL DEFAULT '',
            city VARCHAR(120) NOT NULL DEFAULT '',
            updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_country_code (country_code)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        $hash = hash('sha256', $ip);
        $stmt = $pdo->prepare('SELECT country,country_code,city FROM analytics_geo_cache WHERE ip_hash=? LIMIT 1');
        $stmt->execute([$hash]);
        $cached = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($cached) return [
            'country' => (string)($cached['country'] ?? ''),
            'country_code' => strtoupper((string)($cached['country_code'] ?? '')),
            'city' => (string)($cached['city'] ?? '')
        ];

        $ch = curl_init('https://ipwho.is/' . rawurlencode($ip));
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 1,
            CURLOPT_TIMEOUT => 2,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_USERAGENT => 'SmartToolz-Analytics/1.0'
        ]);
        $body = curl_exec($ch);
        curl_close($ch);
        if (!is_string($body) || $body === '') return $empty;

        $data = json_decode($body, true);
        if (!is_array($data) || ($data['success'] ?? true) === false) return $empty;

        $result = [
            'country' => analyticsClean((string)($data['country'] ?? ''), 120),
            'country_code' => strtoupper(analyticsClean((string)($data['country_code'] ?? ''), 10)),
            'city' => analyticsClean((string)($data['city'] ?? ''), 120)
        ];

        $ins = $pdo->prepare('INSERT INTO analytics_geo_cache (ip_hash,ip_address,country,country_code,city) VALUES (?,?,?,?,?) ON DUPLICATE KEY UPDATE country=VALUES(country),country_code=VALUES(country_code),city=VALUES(city),ip_address=VALUES(ip_address)');
        $ins->execute([$hash, $ip, $result['country'], $result['country_code'], $result['city']]);
        return $result;
    } catch (Throwable $e) {
        return $empty;
    }
}

$geo = analyticsResolveGeo($pdo, $ipAddress);
if ($country === '' && $geo['country'] !== '') $country = $geo['country'];
if ($countryCode === '' && $geo['country_code'] !== '') $countryCode = $geo['country_code'];
if ($city === '' && $geo['city'] !== '') $city = $geo['city'];'''
    if old not in tracker:
        raise SystemExit('Tracker country block not found')
    tracker = tracker.replace(old, new, 1)
    TRACKER.write_text(tracker, encoding='utf-8')

idx = INDEX.read_text(encoding='utf-8-sig')
old_block = '''            SELECT
                COALESCE(
                    NULLIF(country_code, ''),
                    'Unknown'
                ) AS label,
                COUNT(*) AS total
            FROM analytics_pageviews
            {$dateCondition}
            GROUP BY label
            ORDER BY total DESC
            LIMIT 15'''
new_block = '''            SELECT
                COALESCE(
                    NULLIF(ap.country_code, ''),
                    NULLIF(av.country_code, ''),
                    'Unknown'
                ) AS label,
                COUNT(*) AS total
            FROM analytics_pageviews ap
            LEFT JOIN analytics_visitors av ON av.visitor_id = ap.visitor_id
            {$dateCondition}
            GROUP BY label
            ORDER BY total DESC
            LIMIT 15'''
if old_block in idx:
    idx = idx.replace(old_block, new_block, 1)

INDEX.write_text(idx, encoding='utf-8')
print('analytics geo patch applied')

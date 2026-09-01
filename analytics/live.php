<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

require_once $_SERVER['DOCUMENT_ROOT'] . '/creator-ai/auth/config.php';

try {
    $pdo = db();
    if (!$pdo instanceof PDO) {
        throw new RuntimeException('Database connection failed.');
    }

    $minutes = 5;
    $rows = [];

    $stmt = $pdo->prepare("SELECT visitor_id, session_id, page_path, page_url, device, os, browser, country, country_code, city, referrer, traffic_source, last_seen FROM analytics_live WHERE last_seen >= DATE_SUB(NOW(), INTERVAL ? MINUTE) ORDER BY last_seen DESC LIMIT 100");
    $stmt->execute([$minutes]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'ok' => true,
        'server_time' => date('Y-m-d H:i:s'),
        'active_window_minutes' => $minutes,
        'count' => count($rows),
        'visitors' => $rows,
    ], JSON_UNESCAPED_SLASHES);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'ok' => false,
        'message' => 'Live analytics unavailable.'
    ]);
}

<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

// Keep provider credentials in environment variables or a server-side config.
// Example: DOMAIN_API_TOKEN=...
$action = $_GET['action'] ?? '';
$domain = trim(strtolower($_GET['domain'] ?? ''));

if ($action === 'check') {
    if ($domain === '' || !preg_match('/^(?=.{1,253}$)([a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?\.)+[a-z]{2,63}$/', $domain)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid domain']);
        exit;
    }

    // TODO: connect this server endpoint to Domain Name API (or another registrar).
    // Do not call the registrar directly from browser JavaScript.
    echo json_encode([
        'domain' => $domain,
        'available' => null,
        'price' => '—',
        'connected' => false
    ]);
    exit;
}

http_response_code(404);
echo json_encode(['error' => 'Unknown action']);

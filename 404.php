<?php
declare(strict_types=1);
require_once __DIR__ . '/lib/content-page.php';
http_response_code(404);
smarttoolz_render_content_page(
    'Page Not Found',
    'The page you requested does not exist or has moved.',
    '<h2>Nothing is broken here</h2><p>The URL may be outdated or the tool may have moved to a cleaner address.</p><p><a href="/">← Back to SmartToolz home</a></p><p><a href="/tool.php">Browse all tools →</a></p>'
);

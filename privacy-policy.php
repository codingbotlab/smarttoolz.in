<?php
declare(strict_types=1);
require_once __DIR__ . '/lib/content-page.php';
smarttoolz_render_content_page(
    'Privacy Policy',
    'How SmartToolz handles information when you use the site.',
    '<h2>Information we collect</h2><p>SmartToolz is designed so that the core site can be used without creating an account. The core tools do not intentionally require a name, email address or profile to perform normal tasks.</p><h2>Tool processing</h2><p>Many tools process data directly in your browser. When a tool sends data to an external service or uses a browser-hosted library, the relevant tool page should make that dependency clear.</p><h2>Cookies</h2><p>SmartToolz may use essential browser storage or cookies required for normal site operation. Optional third-party services, when added, may have their own privacy practices.</p><h2>Third-party services</h2><p>Some tools may use external libraries or services for functionality. Their providers may process technical information according to their own policies.</p><h2>Updates</h2><p>This policy may be updated when the site architecture or features change.</p>'
);

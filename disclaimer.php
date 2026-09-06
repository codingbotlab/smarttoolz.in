<?php
declare(strict_types=1);
require_once __DIR__ . '/lib/content-page.php';
smarttoolz_render_content_page(
    'Disclaimer',
    'Important information about SmartToolz tools, outputs and availability.',
    '<h2>General information</h2><p>SmartToolz provides general-purpose online utilities. Tool outputs are provided for convenience and should be checked before being used for important, legal, financial, medical or business decisions.</p><h2>No guarantee of accuracy</h2><p>Although tools are built to be useful and predictable, no output is guaranteed to be error-free or suitable for every purpose.</p><h2>Third-party dependencies</h2><p>Certain tools may use external browser libraries or services. Availability and behavior of those dependencies can change independently of SmartToolz.</p><h2>Availability</h2><p>The site may be updated, interrupted or changed without notice.</p>'
);

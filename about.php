<?php
declare(strict_types=1);
require_once __DIR__ . '/lib/content-page.php';
smarttoolz_render_content_page(
    'About SmartToolz',
    'A simple collection of practical browser tools for everyday work.',
    '<h2>What is SmartToolz?</h2><p>SmartToolz is a collection of focused online utilities for common tasks such as image editing, PDF work, text cleanup, developer utilities, calculators and generators.</p><h2>Built around one clear idea</h2><p>Each tool should solve one job quickly, with a clean interface and as little friction as possible. The core site does not require user accounts or a database for normal tool use.</p><h2>Privacy-first approach</h2><p>Where a tool can work locally in the browser, SmartToolz is designed to keep processing there. Some tools may use browser libraries or external services where their functionality requires it; those dependencies are disclosed on the relevant tool page.</p><h2>Our goal</h2><p>Keep useful tools accessible, fast and easy to understand.</p>'
);

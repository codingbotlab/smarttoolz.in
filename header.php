<?php
declare(strict_types=1);
require_once __DIR__ . '/bootstrap.php';

function smarttoolz_render_header(): void
{
    $currentPath = parse_url((string)($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH) ?: '/';
    $active = rtrim($currentPath, '/') === '' ? 'home' : ($currentPath === '/tool.php' || str_starts_with($currentPath, '/category/') ? 'all-tools' : (str_starts_with($currentPath, '/tools/') ? 'tools' : ''));
    $is = static fn(string $key): string => $active === $key ? ' aria-current="page"' : '';
    ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="/assets/css/smarttoolz.css">
    <header class="site-header">
      <div class="header-inner">
        <a class="brand" href="/" aria-label="SmartToolz home"><span class="brand-mark" aria-hidden="true">✦</span><span>SmartToolz</span></a>
        <nav class="main-nav" aria-label="Primary navigation">
          <a href="/"<?= $is('home') ?>>Home</a>
          <a href="/tool.php"<?= $is('all-tools') ?>>All Tools</a>
          <a href="/tool.php#categories">Categories</a>
        </nav>
      </div>
    </header>
    <?php
}

smarttoolz_render_header();

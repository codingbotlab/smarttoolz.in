<?php
declare(strict_types=1);
require_once __DIR__ . '/bootstrap.php';

function smarttoolz_render_header(): void
{
    $currentPath = parse_url((string)($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH) ?: '/';
    $active = rtrim($currentPath, '/') === ''
        ? 'home'
        : (($currentPath === '/tool.php' || str_starts_with($currentPath, '/category/'))
            ? 'all-tools'
            : (str_starts_with($currentPath, '/tools/') ? 'tools' : ''));
    $is = static fn(string $key): string => $active === $key ? ' aria-current="page"' : '';
    ?>
<header class="site-header">
  <div class="header-inner">
    <a class="brand" href="/" aria-label="SmartToolz home">
      <span class="brand-mark" aria-hidden="true">✦</span>
      <span>SmartToolz</span>
    </a>
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

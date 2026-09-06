<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

$currentPath = parse_url((string)($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH) ?: '/';
$active = rtrim($currentPath, '/') === '' ? 'home' : (str_starts_with($currentPath, '/tools/') ? 'tools' : ($currentPath === '/tool.php' ? 'all-tools' : ''));
$nav = static function (string $key) use ($active): string {
    return $active === $key ? ' aria-current="page"' : '';
};
?>
<header class="site-header">
  <div class="header-inner">
    <a class="brand" href="/" aria-label="SmartToolz home">
      <span class="brand-mark" aria-hidden="true">✦</span>
      <span>SmartToolz</span>
    </a>
    <nav class="main-nav" aria-label="Primary navigation">
      <a href="/"<?= $nav('home') ?>>Home</a>
      <a href="/tool.php"<?= $nav('all-tools') ?>>All Tools</a>
      <a href="/tool.php#categories">Categories</a>
    </nav>
  </div>
</header>

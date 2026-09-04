<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

$appName = $appName ?? 'SmartToolz';
$appHome = $appHome ?? '/';
$userName = $_SESSION['user_name'] ?? $_SESSION['name'] ?? '';
?>
<header class="st-app-header">
  <a class="st-brand" href="<?= htmlspecialchars($appHome, ENT_QUOTES, 'UTF-8') ?>" aria-label="<?= htmlspecialchars($appName, ENT_QUOTES, 'UTF-8') ?> home">
    <span class="st-brand-mark">S</span><span><?= htmlspecialchars($appName, ENT_QUOTES, 'UTF-8') ?></span>
  </a>
  <div class="st-header-actions">
    <?php if ($userName !== ''): ?>
      <span class="st-user" title="Signed in"><?= htmlspecialchars($userName, ENT_QUOTES, 'UTF-8') ?></span>
      <a class="st-icon-btn" href="/auth/logout.php" aria-label="Sign out">↪</a>
    <?php else: ?>
      <a class="st-login-btn" href="/auth/login.php">Sign in</a>
    <?php endif; ?>
  </div>
</header>

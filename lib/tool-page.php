<?php
declare(strict_types=1);

require_once __DIR__ . '/../bootstrap.php';

function smarttoolz_tool_page_start(array $tool): void
{
    $title = htmlspecialchars((string)($tool['title'] ?? 'Online Tool') . ' | SmartToolz', ENT_QUOTES, 'UTF-8');
    $description = htmlspecialchars((string)($tool['description'] ?? 'Free online tool from SmartToolz.'), ENT_QUOTES, 'UTF-8');
    $canonical = htmlspecialchars((string)($tool['url'] ?? '/'), ENT_QUOTES, 'UTF-8');
    ?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= $title ?></title>
<meta name="description" content="<?= $description ?>">
<meta name="robots" content="index,follow,max-image-preview:large">
<link rel="canonical" href="<?= $canonical ?>">
<meta property="og:type" content="website">
<meta property="og:site_name" content="SmartToolz">
<meta property="og:title" content="<?= $title ?>">
<meta property="og:description" content="<?= $description ?>">
<meta property="og:url" content="<?= $canonical ?>">
<?php require __DIR__ . '/../head.php'; ?>
</head>
<body>
<?php require __DIR__ . '/../header.php'; ?>
<?php
}

function smarttoolz_tool_page_end(): void
{
    require __DIR__ . '/../footer.php';
    ?>
</body>
</html>
<?php
}

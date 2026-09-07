<?php
declare(strict_types=1);

require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/tools.php';

function smarttoolz_tool_page_start(array $tool): void
{
    $rawTitle = (string)($tool['title'] ?? 'Online Tool');
    $rawDescription = (string)($tool['description'] ?? 'Free online tool from SmartToolz.');
    $rawCanonical = (string)($tool['url'] ?? '/');
    $title = htmlspecialchars($rawTitle . ' | SmartToolz', ENT_QUOTES, 'UTF-8');
    $description = htmlspecialchars($rawDescription, ENT_QUOTES, 'UTF-8');
    $canonical = htmlspecialchars($rawCanonical, ENT_QUOTES, 'UTF-8');
    ?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= $title ?></title>
<meta name="description" content="<?= $description ?>">
<meta name="robots" content="index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1">
<link rel="canonical" href="<?= $canonical ?>">
<meta property="og:type" content="website">
<meta property="og:site_name" content="SmartToolz">
<meta property="og:title" content="<?= $title ?>">
<meta property="og:description" content="<?= $description ?>">
<meta property="og:url" content="<?= $canonical ?>">
<script type="application/ld+json">
<?= json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'WebApplication',
    'name' => $rawTitle,
    'url' => $rawCanonical,
    'description' => $rawDescription,
    'applicationCategory' => 'UtilitiesApplication',
    'operatingSystem' => 'Any',
    'browserRequirements' => 'Requires a modern web browser.',
    'isAccessibleForFree' => true,
    'publisher' => [
        '@type' => 'Organization',
        'name' => 'SmartToolz',
        'url' => 'https://smarttoolz.in/'
    ]
], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>
</script>
<?php require __DIR__ . '/../head.php'; ?>
</head>
<body>
<?php require __DIR__ . '/../header.php'; ?>
<?php
}

function smarttoolz_tool_page_end(): void
{
    $requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: '';
    $parts = array_values(array_filter(explode('/', trim($requestPath, '/'))));
    $slug = ($parts[0] ?? '') === 'tools' ? ($parts[1] ?? '') : '';

    if ($slug !== '' && preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $slug)) {
        $meta = smarttoolz_inferred_metadata($slug);
        foreach (smarttoolz_tool_metadata() as $knownSlug => $knownMeta) {
            if ($knownSlug === $slug) { $meta = $knownMeta; break; }
        }
        $tags = smarttoolz_tags_for($slug, (string)$meta['category']);
        ?>
        <section class="tool-tags-section" aria-labelledby="tool-tags-title">
          <div class="tool-tags-inner">
            <span class="tool-tags-label">RELATED TOPICS</span>
            <h2 id="tool-tags-title">Tags for this tool</h2>
            <div class="tool-tags-list">
              <?php foreach ($tags as $tag): ?>
                <a href="/tools/?q=<?= rawurlencode($tag) ?>" class="tool-tag-link"><?= htmlspecialchars($tag, ENT_QUOTES, 'UTF-8') ?></a>
              <?php endforeach; ?>
            </div>
          </div>
        </section>
        <style>
        .tool-tags-section{width:min(920px,calc(100% - 32px));margin:0 auto 28px}.tool-tags-inner{padding:20px 22px;border:1px solid #e7eaf0;border-radius:18px;background:#fff}.tool-tags-label{font-size:8px;letter-spacing:1.3px;font-weight:900;color:#5b43f5}.tool-tags-inner h2{margin:4px 0 12px;font-size:17px;color:#111936}.tool-tags-list{display:flex;flex-wrap:wrap;gap:7px}.tool-tag-link{display:inline-flex;padding:6px 10px;border:1px solid #e3e6ee;border-radius:999px;background:#f8f9fc;color:#596579;text-decoration:none;font-size:10px;font-weight:750}.tool-tag-link:hover{border-color:#cfc9ff;color:#4b3cff}@media(max-width:600px){.tool-tags-section{width:calc(100% - 20px)}}
        </style>
        <section class="tool-howto-cta" aria-labelledby="tool-howto-title">
          <div class="tool-howto-inner">
            <div class="tool-howto-icon" aria-hidden="true">?</div>
            <div class="tool-howto-copy">
              <span class="tool-howto-label">NEED HELP?</span>
              <h2 id="tool-howto-title">How to use this tool</h2>
              <p>Follow our simple step-by-step guide to get the best results from this tool.</p>
            </div>
            <a class="tool-howto-button" href="/how-to/<?= htmlspecialchars($slug, ENT_QUOTES, 'UTF-8') ?>/">Read How To Use →</a>
          </div>
        </section>
        <style>
        .tool-howto-cta{width:min(920px,calc(100% - 32px));margin:0 auto 28px}.tool-howto-inner{display:flex;align-items:center;gap:18px;padding:20px 22px;border:1px solid #ddd9ff;border-radius:18px;background:linear-gradient(135deg,#f5f2ff,#f5f9ff);box-shadow:0 10px 28px rgba(30,40,90,.05)}.tool-howto-icon{flex:0 0 42px;width:42px;height:42px;display:grid;place-items:center;border-radius:12px;background:#e7e2ff;color:#5842f4;font-size:20px;font-weight:900}.tool-howto-copy{min-width:0;flex:1}.tool-howto-label{font-size:8px;letter-spacing:1.3px;font-weight:900;color:#5b43f5}.tool-howto-copy h2{margin:3px 0 2px;font-size:18px;letter-spacing:-.4px;color:#111936}.tool-howto-copy p{margin:0;color:#69748b;font-size:11px;line-height:1.5}.tool-howto-button{flex:0 0 auto;padding:12px 16px;border-radius:11px;background:#5842f4;color:#fff;text-decoration:none;font-size:11px;font-weight:800;box-shadow:0 8px 18px rgba(88,66,244,.2)}.tool-howto-button:hover{color:#fff;opacity:.92}@media(max-width:600px){.tool-howto-cta{width:calc(100% - 20px)}.tool-howto-inner{align-items:flex-start;flex-wrap:wrap}.tool-howto-copy{flex-basis:calc(100% - 60px)}.tool-howto-button{width:100%;text-align:center}}
        </style>
        <?php
    }
    require __DIR__ . '/../footer.php';
    ?>
</body>
</html>
<?php
}

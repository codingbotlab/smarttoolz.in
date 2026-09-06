<?php
declare(strict_types=1);

require_once __DIR__ . '/tools.php';

function smarttoolz_render_tool_seo(): void
{
    $path = parse_url((string)($_SERVER['REQUEST_URI'] ?? ''), PHP_URL_PATH) ?: '';
    if (!preg_match('#^/tools/([a-z0-9-]+)/?$#i', $path, $m)) {
        return;
    }

    $slug = strtolower($m[1]);
    $tool = smarttoolz_tool_by_slug($slug);
    if (!$tool) {
        return;
    }

    $name = $tool['name'];
    $category = $tool['category'];
    $description = $tool['description'];
    $url = smarttoolz_escape($tool['url']);
    $safeName = smarttoolz_escape($name);
    $safeCategory = smarttoolz_escape($category);

    $categoryGuides = [
        'Image Tools' => [
            'intro' => "This browser-based image utility is designed for quick everyday image work without installing desktop software. Use it when you need a focused result such as changing image format, dimensions, orientation, compression, or basic editing.",
            'best' => 'designers, creators, students, marketers, and anyone preparing images for websites or social platforms',
            'tips' => ['Use a copy of the original when you need to preserve the source file.', 'Choose the output format and dimensions that match where the image will be used.', 'Check the final preview before downloading so important details are not lost.'],
        ],
        'PDF Tools' => [
            'intro' => "This PDF utility handles a focused document task directly in your browser. It is useful for preparing files for sharing, publishing, archiving, email attachments, and routine office workflows.",
            'best' => 'students, office teams, freelancers, researchers, and anyone who works with PDF documents',
            'tips' => ['Keep an original copy before applying a transformation.', 'Use clear file names when downloading converted or combined documents.', 'Review page order and output quality before sharing the finished PDF.'],
        ],
        'Text Tools' => [
            'intro' => "This text utility helps turn repetitive text cleanup and formatting into a quick browser task. It is built for practical writing, editing, data cleanup, content preparation, and everyday productivity.",
            'best' => 'writers, editors, students, developers, researchers, and content teams',
            'tips' => ['Paste clean source text when possible to avoid carrying unwanted formatting.', 'Review the result when punctuation, capitalization, or line order matters.', 'Use the tool as a fast preprocessing step before publishing or analysis.'],
        ],
        'Developer Tools' => [
            'intro' => "This developer utility solves a focused formatting, encoding, conversion, or data-preparation task in the browser. It is useful for debugging, API work, frontend development, documentation, and quick checks without adding another dependency to a project.",
            'best' => 'developers, QA testers, API users, students, and technical writers',
            'tips' => ['Validate the result in the system where it will be used.', 'Do not paste secrets, private keys, or credentials into a public website.', 'Keep structured input readable so errors are easier to spot.'],
        ],
        'Calculators' => [
            'intro' => "This calculator provides a quick way to work through a common everyday calculation. It is intended for practical estimation and understanding rather than replacing professional advice in situations that require it.",
            'best' => 'students, households, professionals, and anyone who needs a quick calculation',
            'tips' => ['Check the units before entering values.', 'Compare the result with your expected range to catch input mistakes.', 'For important decisions, verify calculations independently.'],
        ],
        'Generators' => [
            'intro' => "This generator creates useful output for a focused task directly in your browser. It can save time when you need repeatable text, codes, identifiers, samples, or other practical data.",
            'best' => 'developers, creators, students, marketers, and everyday users',
            'tips' => ['Choose options that match the purpose of the generated result.', 'Review generated output before publishing or sharing it.', 'Generate a fresh value when a unique result is required.'],
        ],
        'Design Tools' => [
            'intro' => "This design utility turns a common visual task into a fast browser workflow. It is useful when preparing colors, assets, and design values for websites, apps, presentations, and creative projects.",
            'best' => 'designers, frontend developers, marketers, creators, and students',
            'tips' => ['Record the exact output value when consistency across a project matters.', 'Preview colors and assets in the context where they will be used.', 'Keep accessible contrast and readability in mind when choosing visual values.'],
        ],
        'Security' => [
            'intro' => "This security-focused utility helps with a narrowly defined browser task. Use it as a practical helper, while following the security requirements of the service, application, or system where the output will be used.",
            'best' => 'developers, students, administrators, and everyday users',
            'tips' => ['Never reuse sensitive values when uniqueness is required.', 'Avoid entering confidential information into tools that do not need it.', 'Follow the security policy of the system receiving the result.'],
        ],
        'Utilities' => [
            'intro' => "This lightweight utility is built for a simple everyday workflow that is easy to complete in the browser. It is designed to reduce small tasks and keep the interaction focused.",
            'best' => 'students, professionals, creators, and everyday users',
            'tips' => ['Use the controls provided to keep the workflow simple.', 'Reset and start again when you need a clean result.', 'For important records, save the final result where you can find it later.'],
        ],
    ];
    $guide = $categoryGuides[$category] ?? $categoryGuides['Utilities'];

    $slugWords = trim(str_replace('-', ' ', $slug));
    $searchPhrase = strtolower($name . ' online');
    $how = "Open {$name}, provide the information or file requested by the tool, review the result, and use the download or copy action when you are satisfied.";

    $faqs = [
        ["What is {$name}?", "{$name} is a free SmartToolz browser utility for {$slugWords}. It is designed around one focused task so you can get a result without installing a separate desktop application."],
        ["How do I use {$name}?", $how],
        ["Is {$name} free to use?", "Yes. {$name} is available as a free SmartToolz tool, with the main workflow designed to work directly in a modern web browser."],
    ];

    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'WebApplication',
        'name' => $name,
        'url' => 'https://smarttoolz.in' . $tool['url'],
        'applicationCategory' => 'UtilitiesApplication',
        'operatingSystem' => 'Web',
        'description' => $description,
        'publisher' => ['@type'=>'Organization','name'=>'SmartToolz','url'=>'https://smarttoolz.in'],
        'isAccessibleForFree' => true,
    ];
    ?>
<section class="tool-seo-intro" aria-labelledby="tool-guide-title">
  <div class="tool-seo-copy">
    <div class="tool-seo-kicker"><?= $safeCategory ?> • SmartToolz</div>
    <h2 id="tool-guide-title">About <?= $safeName ?> Online</h2>
    <p><?= smarttoolz_escape($guide['intro']) ?></p>
    <p><strong><?= $safeName ?></strong> — <?= smarttoolz_escape($description) ?> It is especially useful for <?= smarttoolz_escape($guide['best']) ?>. The workflow stays focused so you can complete <?= smarttoolz_escape($slugWords) ?> quickly and move on to the next task.</p>
    <div class="tool-seo-actions">
      <a class="seo-chip" href="<?= $url ?>">Use <?= $safeName ?></a>
      <a class="seo-chip seo-chip-muted" href="/tool.php">Explore all tools</a>
    </div>
  </div>
  <figure class="tool-seo-visual">
    <img src="/assets/home/hero-tools.svg" width="800" height="520" loading="lazy" decoding="async" alt="SmartToolz <?= $safeName ?> online tool illustration">
    <figcaption>SmartToolz browser tools are designed for focused, practical workflows.</figcaption>
  </figure>
</section>
<section class="tool-seo-content">
  <article class="tool-seo-card">
    <h2>How to use <?= $safeName ?></h2>
    <ol class="tool-seo-steps">
      <li>Open the <strong><?= $safeName ?></strong> tool above.</li>
      <li>Enter your input or select the file and options requested by the tool.</li>
      <li>Review the result and make any final adjustments.</li>
      <li>Copy, download, or use the finished result in your next task.</li>
    </ol>
  </article>
  <article class="tool-seo-card">
    <h2>Tips for better results</h2>
    <ul class="tool-seo-list">
      <?php foreach ($guide['tips'] as $tip): ?><li><?= smarttoolz_escape($tip) ?></li><?php endforeach; ?>
    </ul>
  </article>
</section>
<section class="tool-faq" aria-labelledby="tool-faq-title">
  <div class="tool-faq-head"><div><div class="tool-seo-kicker">HELP &amp; FAQ</div><h2 id="tool-faq-title"><?= $safeName ?> FAQ</h2><p>Common questions about using this SmartToolz utility.</p></div></div>
  <div class="tool-faq-grid">
    <?php foreach ($faqs as $faq): ?><details class="tool-faq-item"><summary><?= smarttoolz_escape($faq[0]) ?></summary><p><?= smarttoolz_escape($faq[1]) ?></p></details><?php endforeach; ?>
  </div>
</section>
<script type="application/ld+json"><?= json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?></script>
<?php
}

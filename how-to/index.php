<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/bootstrap.php';
require_once dirname(__DIR__) . '/lib/tools.php';

$slug = strtolower(trim((string)($_GET['slug'] ?? '')));
$tool = null;
foreach (smarttoolz_tools() as $item) {
    if (smarttoolz_slug((string)$item['name']) === $slug || trim((string)$item['url'], '/') === 'tools/'.$slug) {
        $tool = $item;
        break;
    }
}
if (!$tool) {
    http_response_code(404);
    require dirname(__DIR__) . '/error.php';
    exit;
}

$name = (string)$tool['name'];
$category = (string)$tool['category'];
$description = (string)$tool['description'];
$canonical = 'https://smarttoolz.in/how-to/' . rawurlencode($slug) . '/';

$categoryAdvice = [
    'Image Tools' => 'Keep the original file available, check the output dimensions and format, and preview the result before using it elsewhere.',
    'PDF Tools' => 'Keep a backup of the original PDF and verify every page or output file after processing, especially for documents used for work or sharing.',
    'Text Tools' => 'Paste only the text you intend to process, review the transformed output, and copy the final version when it looks correct.',
    'Developer Tools' => 'Use representative test data, check the generated output carefully, and validate it in your development workflow before deploying it.',
    'Calculators' => 'Enter the values using the units shown by the tool, then review the result and assumptions before using it for an important decision.',
    'Generators' => 'Enter the content or options you need, generate the result, and test the generated output before publishing or sharing it.',
    'Security' => 'Avoid entering real secrets into tools unless the page explicitly states how they are handled. Prefer test credentials and review generated results before use.',
    'Design Tools' => 'Choose your source values carefully, adjust the available options, and copy or export the final value after checking the preview.',
    'Other Tools' => 'Enter the information requested by the tool, review the result, and use the final output in your workflow.',
];
$advice = $categoryAdvice[$category] ?? $categoryAdvice['Other Tools'];

$steps = [
    ['Open the tool', 'Open the SmartToolz tool page from the button below. The tool runs in a simple browser-based workflow.'],
    ['Enter your input', 'Provide the text, file, values or other information requested by the tool. Use the labels and examples on the tool page as a guide.'],
    ['Choose options', 'If the tool provides settings, select the options that match the output you need. For tools without options, move to the next step.'],
    ['Run the tool', 'Click the main action button to process the input. Wait for the result area or generated output to appear.'],
    ['Review and use the result', 'Check the output for accuracy, then copy, download or otherwise use it as provided by the tool.'],
];

$faqs = [
    ['Is this tool free to use?', 'Yes. SmartToolz provides free browser-based utilities for everyday digital tasks.'],
    ['Do I need to create an account?', 'No account is required for the normal SmartToolz tool workflow.'],
    ['What should I check before using the result?', 'Review the output against your original input and the purpose of the task. For files, also confirm that the downloaded or generated file opens correctly.'],
];
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>How to Use <?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?> | SmartToolz</title>
<meta name="description" content="Learn how to use <?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?> step by step. Simple instructions, practical tips and common questions for this free SmartToolz <?= htmlspecialchars(strtolower($category), ENT_QUOTES, 'UTF-8') ?> tool.">
<meta name="robots" content="index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1">
<link rel="canonical" href="<?= htmlspecialchars($canonical, ENT_QUOTES, 'UTF-8') ?>">
<meta property="og:type" content="article">
<meta property="og:site_name" content="SmartToolz">
<meta property="og:title" content="How to Use <?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>">
<meta property="og:description" content="Step-by-step instructions for using <?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?> on SmartToolz.">
<meta property="og:url" content="<?= htmlspecialchars($canonical, ENT_QUOTES, 'UTF-8') ?>">
<?php require dirname(__DIR__) . '/head.php'; ?>
<style>
.how-page{width:min(1050px,calc(100% - 32px));margin:0 auto 80px}.how-hero{text-align:center;padding:68px 0 44px}.how-hero .eyebrow{color:#5b43ff;font-size:10px;font-weight:900;letter-spacing:1.8px}.how-hero h1{margin:14px auto 14px;max-width:850px;font-size:clamp(38px,6vw,62px);line-height:1.02;letter-spacing:-3px;color:#111936}.how-hero p{max-width:720px;margin:auto;color:#667085;font-size:15px;line-height:1.8}.how-hero-actions{display:flex;justify-content:center;gap:12px;margin-top:26px;flex-wrap:wrap}.how-btn{display:inline-flex;align-items:center;justify-content:center;padding:12px 18px;border-radius:11px;text-decoration:none;font-size:13px;font-weight:800;background:#5542ff;color:#fff}.how-btn.secondary{background:#f1efff;color:#5542ff}.how-layout{display:grid;grid-template-columns:minmax(0,1fr) 300px;gap:22px;align-items:start}.how-card{background:#fff;border:1px solid #e7eaf0;border-radius:22px;padding:30px;box-shadow:0 14px 38px rgba(16,24,40,.055)}.how-card h2{margin:0 0 12px;color:#111936;font-size:27px;letter-spacing:-1px}.how-card>p{color:#475467;font-size:14px;line-height:1.8}.how-step{display:grid;grid-template-columns:42px 1fr;gap:15px;padding:20px 0;border-top:1px solid #edf0f4}.how-step:first-of-type{margin-top:20px}.how-number{display:grid;place-items:center;width:42px;height:42px;border-radius:13px;background:#f0edff;color:#5542ff;font-size:13px;font-weight:900}.how-step h3{margin:1px 0 6px;font-size:16px;color:#17213f}.how-step p{margin:0;color:#667085;font-size:13px;line-height:1.7}.how-side{display:grid;gap:16px}.how-side-card{padding:22px;border:1px solid #e7eaf0;border-radius:18px;background:#fff;box-shadow:0 10px 28px rgba(16,24,40,.04)}.how-side-card h2{font-size:16px;margin:0 0 10px;color:#111936}.how-side-card p{margin:0;color:#667085;font-size:13px;line-height:1.75}.how-side-card a{display:inline-block;margin-top:13px;color:#5542ff;font-size:12px;font-weight:800;text-decoration:none}.how-list{margin:10px 0 0;padding-left:18px;color:#667085;font-size:13px;line-height:1.8}.how-faq{margin-top:22px}.how-faq details{border-top:1px solid #edf0f4;padding:16px 0}.how-faq summary{cursor:pointer;font-weight:800;font-size:14px;color:#17213f}.how-faq p{margin:8px 0 0;color:#667085;font-size:13px;line-height:1.7}.how-bottom{margin-top:22px;text-align:center;padding:28px;border-radius:20px;background:#111936;color:#fff}.how-bottom h2{margin:0 0 8px;font-size:23px}.how-bottom p{margin:0 0 18px;color:#c9d0e1;font-size:13px}.how-bottom .how-btn{background:#fff;color:#5542ff}@media(max-width:800px){.how-layout{grid-template-columns:1fr}.how-side{grid-template-columns:1fr 1fr}}@media(max-width:560px){.how-page{width:calc(100% - 20px)}.how-hero{padding:48px 0 32px}.how-hero h1{letter-spacing:-2px}.how-card{padding:22px}.how-side{grid-template-columns:1fr}.how-step{grid-template-columns:36px 1fr}.how-number{width:36px;height:36px}}
</style>
<script type="application/ld+json">
<?= json_encode(['@context'=>'https://schema.org','@type'=>'HowTo','name'=>'How to Use '.$name,'description'=>'Step-by-step instructions for using '.$name.' on SmartToolz.','url'=>$canonical,'step'=>array_map(static fn($s,$i)=>['@type'=>'HowToStep','position'=>$i+1,'name'=>$s[0],'text'=>$s[1]],$steps,array_keys($steps)),'mainEntity'=>array_map(static fn($f)=>['@type'=>'Question','name'=>$f[0],'acceptedAnswer'=>['@type'=>'Answer','text'=>$f[1]]],$faqs)], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) ?>
</script>
</head>
<body>
<?php require dirname(__DIR__) . '/header.php'; ?>
<main class="how-page">
  <section class="how-hero">
    <span class="eyebrow">HOW TO USE • <?= htmlspecialchars(strtoupper($category), ENT_QUOTES, 'UTF-8') ?></span>
    <h1>How to use <?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?></h1>
    <p><?= htmlspecialchars($description, ENT_QUOTES, 'UTF-8') ?> Follow the simple workflow below to get the result you need.</p>
    <div class="how-hero-actions"><a class="how-btn" href="<?= htmlspecialchars($tool['url'], ENT_QUOTES, 'UTF-8') ?>">Open <?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?> →</a><a class="how-btn secondary" href="/tools/">Browse all tools</a></div>
  </section>
  <div class="how-layout">
    <article class="how-card">
      <h2>Step-by-step guide</h2>
      <p>You can complete the task directly in your browser. The exact controls vary by tool, but this workflow explains what to look for and how to verify the result.</p>
      <?php foreach ($steps as $i => [$title,$text]): ?>
        <div class="how-step"><span class="how-number"><?= $i + 1 ?></span><div><h3><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></h3><p><?= htmlspecialchars($text, ENT_QUOTES, 'UTF-8') ?></p></div></div>
      <?php endforeach; ?>
      <div class="how-faq"><h2>Frequently asked questions</h2><?php foreach ($faqs as [$q,$a]): ?><details><summary><?= htmlspecialchars($q, ENT_QUOTES, 'UTF-8') ?></summary><p><?= htmlspecialchars($a, ENT_QUOTES, 'UTF-8') ?></p></details><?php endforeach; ?></div>
    </article>
    <aside class="how-side">
      <div class="how-side-card"><h2>About this guide</h2><p>This guide is specifically for <strong><?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?></strong> and is kept separate from the tool itself so you can return to the instructions whenever you need them.</p><a href="<?= htmlspecialchars($tool['url'], ENT_QUOTES, 'UTF-8') ?>">Open the tool →</a></div>
      <div class="how-side-card"><h2>Helpful tip</h2><p><?= htmlspecialchars($advice, ENT_QUOTES, 'UTF-8') ?></p></div>
      <div class="how-side-card"><h2>Related</h2><p>Explore more free tools in <strong><?= htmlspecialchars($category, ENT_QUOTES, 'UTF-8') ?></strong>.</p><a href="/tools/?category=<?= rawurlencode(smarttoolz_slug($category)) ?>">Browse <?= htmlspecialchars($category, ENT_QUOTES, 'UTF-8') ?> →</a></div>
    </aside>
  </div>
  <section class="how-bottom"><h2>Ready to try it?</h2><p>Open the tool and follow the guide alongside your workflow.</p><a class="how-btn" href="<?= htmlspecialchars($tool['url'], ENT_QUOTES, 'UTF-8') ?>">Use <?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?> →</a></section>
</main>
<?php require dirname(__DIR__) . '/footer.php'; ?>
</body>
</html>

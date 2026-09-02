<?php
declare(strict_types=1);

require_once __DIR__ . '/../smart-toolz/tool.php';

$slug = trim((string)($_GET['tool'] ?? ''));
$tool = null;
foreach ($tools as $item) {
    $path = trim((string)parse_url($item['url'], PHP_URL_PATH), '/');
    if ($slug === basename($path, '.php')) { $tool = $item; break; }
}
if (!$tool) { http_response_code(404); exit('Article not found.'); }

$name = $tool['name'];
$category = $tool['category'];
$description = $tool['description'];
$url = $tool['url'];
$h = static fn(string $v): string => htmlspecialchars($v, ENT_QUOTES, 'UTF-8');

$steps = [
    ['title'=>'Open the tool','body'=>'Open <a href="'.$h($url).'">'.$h($name).'</a> from SmartToolz. The tool is designed to complete this task directly in your browser.'],
    ['title'=>'Add your input','body'=>match ($category) {
        'Image Tools' => 'Upload or select the image you want to work with. Check the selected file before continuing.',
        'PDF Tools' => 'Choose the PDF file(s) or source content required by the tool. If multiple files are supported, add them in the order you want processed.',
        'Text Tools' => 'Paste or type the text you want to process into the main input area.',
        'Developer Tools' => 'Paste the code, data, URL, or value you want to process into the input field.',
        'Calculators' => 'Enter the values requested by the calculator, using the units shown beside each field.',
        'Generators' => 'Enter the content or options you want to use, then review the settings before generating the result.',
        'Security' => 'Enter the requested options or source value. Avoid entering passwords or other secrets that you do not want exposed on screen.',
        'Design Tools' => 'Enter or select the color/value you want to convert or inspect.',
        'Utilities' => 'Set the requested value, duration, or options shown by the tool.',
        default => 'Enter the information requested by the tool.',
    }],
    ['title'=>'Run the tool','body'=>'Use the main action button to process your input. Wait for the result area to finish updating before moving on.'],
    ['title'=>'Review the result','body'=>'Check the generated result and make sure it matches what you expected. If needed, adjust the input and run the tool again.'],
    ['title'=>'Save or use the result','body'=>'Use the available download, copy, export, or next-step control when you are happy with the result.'],
];

$faqs = [
    ['q'=>'Is this tool free?','a'=>'Yes. SmartToolz provides this tool as part of its free online tool collection.'],
    ['q'=>'Do I need to install software?','a'=>'No installation is required for normal browser use. Open the tool page and follow the controls shown there.'],
    ['q'=>'Can I run it again?','a'=>'Yes. You can change the input and process it again whenever the tool provides the relevant controls.'],
];
?><!doctype html>
<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>How to Use <?= $h($name) ?> — SmartToolz</title><meta name="description" content="Step-by-step guide for using <?= $h($name) ?> on SmartToolz."><style>body{margin:0;background:#f7f8fc;color:#172033;font-family:Inter,system-ui,-apple-system,Segoe UI,sans-serif}.wrap{max-width:900px;margin:auto;padding:38px 18px 70px}.crumb{font-size:13px;margin-bottom:20px}.crumb a{color:#635bff;text-decoration:none}.hero,.step,.faq{background:#fff;border:1px solid #e4e8f0;border-radius:18px}.hero{padding:30px;margin-bottom:18px}.tag{font-size:11px;font-weight:900;letter-spacing:1px;color:#635bff}.hero h1{font-size:clamp(30px,5vw,46px);letter-spacing:-1.5px;margin:8px 0}.hero p{color:#687387;line-height:1.7}.cta{display:inline-block;background:#635bff;color:#fff;padding:11px 16px;border-radius:10px;text-decoration:none;font-weight:800}.step{padding:22px;margin:12px 0}.num{display:inline-grid;place-items:center;width:30px;height:30px;border-radius:50%;background:#eeedff;color:#635bff;font-weight:900;margin-right:8px}.step h2{display:inline;font-size:18px}.step p{color:#5f6b7d;line-height:1.7;margin:13px 0 0}.faq{padding:20px;margin-top:12px}.faq h3{font-size:16px;margin:0 0 7px}.faq p{color:#667184;line-height:1.6;margin:0 0 18px}.faq p:last-child{margin-bottom:0}.note{padding:15px 17px;background:#f0f5ff;border-radius:12px;margin-top:20px;color:#43536c;line-height:1.6}</style></head><body><main class="wrap"><div class="crumb"><a href="/knowledge-base/">Knowledge Base</a> / <?= $h($name) ?></div><section class="hero"><div class="tag"><?= $h($category) ?></div><h1>How to Use <?= $h($name) ?></h1><p><?= $h($description) ?> This guide is matched specifically to this SmartToolz tool.</p><a class="cta" href="<?= $h($url) ?>">Open <?= $h($name) ?> →</a></section><?php foreach($steps as $i=>$step): ?><section class="step"><span class="num"><?= $i+1 ?></span><h2><?= $h($step['title']) ?></h2><p><?= $step['body'] ?></p></section><?php endforeach; ?><div class="note"><strong>Tip:</strong> For the best result, use a supported input format and review the result before downloading or sharing it.</div><section class="faq"><h2>Common questions</h2><?php foreach($faqs as $faq): ?><h3><?= $h($faq['q']) ?></h3><p><?= $h($faq['a']) ?></p><?php endforeach; ?></section></main></body></html>

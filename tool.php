<?php
declare(strict_types=1);

/* SmartToolz — central registry + All Tools page. */
$iconsFile = __DIR__ . '/lib/icons.php';
if (is_file($iconsFile)) {
    require_once $iconsFile;
}
if (!function_exists('st_icon')) {
    function st_icon(string $name, string $class = 'st-icon', string $label = ''): string
    {
        $safe = preg_replace('/[^a-z0-9_-]/i', '', $name) ?: 'build';
        $safeClass = preg_replace('/[^a-z0-9_-]/i', ' ', $class) ?: 'st-icon';
        $aria = $label === '' ? ' aria-hidden="true"' : ' role="img" aria-label="' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '"';
        return '<svg class="' . htmlspecialchars($safeClass, ENT_QUOTES, 'UTF-8') . '" viewBox="0 0 24 24" focusable="false"' . $aria . '><use href="/assets/icons/smarttoolz-icons.svg#' . htmlspecialchars($safe, ENT_QUOTES, 'UTF-8') . '"/></svg>';
    }
}

$tools = [
    ['name'=>'Image Background Remover','icon'=>'content_cut','category'=>'Image Tools','description'=>'Remove image backgrounds automatically with AI.','url'=>'/tools/image-background-remover/'],
    ['name'=>'Image Compressor','icon'=>'compress','category'=>'Image Tools','description'=>'Compress JPG, PNG and WebP images online.','url'=>'/tools/image-compressor/'],
    ['name'=>'Image Resizer','icon'=>'photo_size_select_large','category'=>'Image Tools','description'=>'Resize images to any width and height.','url'=>'/tools/image-resizer/'],
    ['name'=>'JPG to PNG','icon'=>'swap_horiz','category'=>'Image Tools','description'=>'Convert JPG images into PNG format.','url'=>'/tools/jpg-to-png/'],
    ['name'=>'PNG to JPG','icon'=>'swap_horiz','category'=>'Image Tools','description'=>'Convert PNG images into JPG format.','url'=>'/tools/png-to-jpg/'],
    ['name'=>'Word Counter','icon'=>'article','category'=>'Text Tools','description'=>'Count words, characters, sentences and lines.','url'=>'/tools/word-counter/'],
    ['name'=>'Case Converter','icon'=>'text_fields','category'=>'Text Tools','description'=>'Convert text to uppercase, lowercase and title case.','url'=>'/tools/case-converter/'],
    ['name'=>'QR Code Generator','icon'=>'qr_code_2','category'=>'Generators','description'=>'Create QR codes for URLs and text.','url'=>'/tools/qr-generator/'],
    ['name'=>'Password Generator','icon'=>'lock','category'=>'Security','description'=>'Generate strong and secure passwords.','url'=>'/tools/password-generator/'],
    ['name'=>'JSON Formatter','icon'=>'data_object','category'=>'Developer Tools','description'=>'Format, beautify and validate JSON.','url'=>'/tools/json-formatter/'],
    ['name'=>'URL Encoder','icon'=>'link','category'=>'Developer Tools','description'=>'Encode URLs safely and easily.','url'=>'/tools/url-encoder/'],
    ['name'=>'PDF to JPG','icon'=>'picture_as_pdf','category'=>'PDF Tools','description'=>'Convert PDF pages into JPG images.','url'=>'/tools/pdf-to-jpg/'],
    ['name'=>'Duplicate Line Remover','icon'=>'content_copy','category'=>'Text Tools','description'=>'Remove duplicate lines from text.','url'=>'/tools/remove-duplicate-lines/'],
    ['name'=>'Image Cropper','icon'=>'crop','category'=>'Image Tools','description'=>'Crop images to the exact size you need.','url'=>'/tools/image-cropper/'],
    ['name'=>'Image Rotator','icon'=>'rotate_right','category'=>'Image Tools','description'=>'Rotate images clockwise or counterclockwise.','url'=>'/tools/image-rotator/'],
    ['name'=>'Image Flipper','icon'=>'flip','category'=>'Image Tools','description'=>'Flip images horizontally or vertically.','url'=>'/tools/image-flipper/'],
    ['name'=>'WebP to JPG','icon'=>'image','category'=>'Image Tools','description'=>'Convert WebP images into JPG format.','url'=>'/tools/webp-to-jpg/'],
    ['name'=>'JPG to WebP','icon'=>'swap_horiz','category'=>'Image Tools','description'=>'Convert JPG images to WebP format.','url'=>'/tools/jpg-to-webp/'],
    ['name'=>'PNG to WebP','icon'=>'swap_horiz','category'=>'Image Tools','description'=>'Convert PNG images to WebP format.','url'=>'/tools/png-to-webp/'],
    ['name'=>'GIF to JPG','icon'=>'gif','category'=>'Image Tools','description'=>'Convert GIF images into JPG format.','url'=>'/tools/gif-to-jpg/'],
    ['name'=>'GIF Maker','icon'=>'gif_box','category'=>'Image Tools','description'=>'Create animated GIF files online.','url'=>'/tools/gif-maker/'],
    ['name'=>'Meme Generator','icon'=>'sentiment_very_satisfied','category'=>'Generators','description'=>'Create custom memes with your own image and text.','url'=>'/tools/meme-generator/'],
    ['name'=>'Color Picker','icon'=>'palette','category'=>'Design Tools','description'=>'Pick a color and get HEX and RGB values.','url'=>'/tools/color-picker/'],
    ['name'=>'Color Converter','icon'=>'gradient','category'=>'Design Tools','description'=>'Convert HEX, RGB and HSL color values.','url'=>'/tools/color-converter/'],
    ['name'=>'Base64 Encoder','icon'=>'lock','category'=>'Developer Tools','description'=>'Encode text and data into Base64.','url'=>'/tools/base64-encoder/'],
    ['name'=>'Base64 Decoder','icon'=>'lock_open','category'=>'Developer Tools','description'=>'Decode Base64 encoded text and data.','url'=>'/tools/base64-decoder/'],
    ['name'=>'URL Decoder','icon'=>'link_off','category'=>'Developer Tools','description'=>'Decode URL encoded text.','url'=>'/tools/url-decoder/'],
    ['name'=>'HTML Encoder','icon'=>'code','category'=>'Developer Tools','description'=>'Encode HTML special characters.','url'=>'/tools/html-encoder/'],
    ['name'=>'HTML Decoder','icon'=>'code','category'=>'Developer Tools','description'=>'Decode HTML entities.','url'=>'/tools/html-decoder/'],
    ['name'=>'Markdown to HTML','icon'=>'description','category'=>'Developer Tools','description'=>'Convert Markdown into HTML.','url'=>'/tools/markdown-to-html/'],
    ['name'=>'Favicon Generator','icon'=>'web_asset','category'=>'Developer Tools','description'=>'Create website favicons from images, emoji or text.','url'=>'/tools/favicon-generator/'],
    ['name'=>'Favicon ICO Generator','icon'=>'web_asset','category'=>'Developer Tools','description'=>'Create favicon.ico files from images or text.','url'=>'/tools/favicon-ico-generator/'],
    ['name'=>'Text to Slug','icon'=>'link','category'=>'Text Tools','description'=>'Convert text into URL-friendly slugs.','url'=>'/tools/text-to-slug/'],
    ['name'=>'Remove Extra Spaces','icon'=>'space_bar','category'=>'Text Tools','description'=>'Remove unnecessary spaces from text.','url'=>'/tools/remove-extra-spaces/'],
    ['name'=>'Sort Lines','icon'=>'sort','category'=>'Text Tools','description'=>'Sort lines alphabetically or numerically.','url'=>'/tools/sort-lines/'],
    ['name'=>'Reverse Text','icon'=>'sync','category'=>'Text Tools','description'=>'Reverse text characters instantly.','url'=>'/tools/reverse-text/'],
    ['name'=>'PDF Merger','icon'=>'merge_type','category'=>'PDF Tools','description'=>'Merge multiple PDF files into one.','url'=>'/tools/pdf-merger/'],
    ['name'=>'PDF Splitter','icon'=>'call_split','category'=>'PDF Tools','description'=>'Split PDFs into separate pages.','url'=>'/tools/pdf-splitter/'],
    ['name'=>'PDF Compressor','icon'=>'compress','category'=>'PDF Tools','description'=>'Reduce PDF file size.','url'=>'/tools/pdf-compressor/'],
    ['name'=>'PDF to PNG','icon'=>'picture_as_pdf','category'=>'PDF Tools','description'=>'Convert PDF pages into PNG images.','url'=>'/tools/pdf-to-png/'],
    ['name'=>'PNG to PDF','icon'=>'picture_as_pdf','category'=>'PDF Tools','description'=>'Convert images into PDF documents.','url'=>'/tools/png-to-pdf/'],
    ['name'=>'Text to PDF','icon'=>'picture_as_pdf','category'=>'PDF Tools','description'=>'Convert text content into a PDF.','url'=>'/tools/text-to-pdf/'],
    ['name'=>'QR Code Reader','icon'=>'qr_code_scanner','category'=>'Generators','description'=>'Read QR codes from images.','url'=>'/tools/qr-reader/'],
    ['name'=>'Random Number Generator','icon'=>'casino','category'=>'Generators','description'=>'Generate random numbers in any range.','url'=>'/tools/random-number-generator/'],
    ['name'=>'UUID Generator','icon'=>'fingerprint','category'=>'Developer Tools','description'=>'Generate unique UUID identifiers.','url'=>'/tools/uuid-generator/'],
    ['name'=>'Timestamp Converter','icon'=>'schedule','category'=>'Developer Tools','description'=>'Convert Unix timestamps into readable dates.','url'=>'/tools/timestamp-converter/'],
    ['name'=>'Unix Timestamp','icon'=>'timer','category'=>'Developer Tools','description'=>'Work with Unix timestamps.','url'=>'/tools/unix-timestamp/'],
    ['name'=>'Lorem Ipsum Generator','icon'=>'article','category'=>'Generators','description'=>'Generate placeholder Lorem Ipsum text.','url'=>'/tools/lorem-ipsum-generator/'],
    ['name'=>'Age Calculator','icon'=>'cake','category'=>'Calculators','description'=>'Calculate age from date of birth.','url'=>'/tools/age-calculator/'],
    ['name'=>'Percentage Calculator','icon'=>'percent','category'=>'Calculators','description'=>'Calculate percentages quickly.','url'=>'/tools/percentage-calculator/'],
    ['name'=>'BMI Calculator','icon'=>'monitor_heart','category'=>'Calculators','description'=>'Calculate Body Mass Index.','url'=>'/tools/bmi-calculator/'],
    ['name'=>'Unit Converter','icon'=>'straighten','category'=>'Calculators','description'=>'Convert common units quickly.','url'=>'/tools/unit-converter/'],
    ['name'=>'Stopwatch / Timer','icon'=>'timer','category'=>'Utilities','description'=>'Use an online stopwatch and timer.','url'=>'/tools/stopwatch-timer/']
];

$categoriesFile = __DIR__ . '/lib/categories.php';
if (is_file($categoriesFile)) {
    require_once $categoriesFile;
}
if (function_exists('smarttoolz_apply_tool_categories')) {
    smarttoolz_apply_tool_categories($tools);
}

/* home.php uses this file only as the central tool registry. */
if (defined('SMARTTOOLZ_HOME_REGISTRY')) {
    return;
}

$requestedCategory = trim((string)($_GET['category'] ?? ''));
$categorySlug = static function (string $value): string {
    $slug = preg_replace('/[^a-z0-9]+/i', '-', $value);
    return trim(strtolower((string)$slug), '-');
};

$categories = [];
foreach ($tools as $tool) {
    $category = trim((string)($tool['category'] ?? 'Other'));
    if ($category === '') {
        $category = 'Other';
    }
    $categories[$category] = ($categories[$category] ?? 0) + 1;
}
ksort($categories, SORT_NATURAL | SORT_FLAG_CASE);

$visibleTools = $tools;
if ($requestedCategory !== '') {
    $visibleTools = [];
    foreach ($tools as $tool) {
        if ($categorySlug((string)($tool['category'] ?? 'Other')) === $requestedCategory) {
            $visibleTools[] = $tool;
        }
    }
}

require_once __DIR__ . '/header.php';
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>All Tools — SmartToolz</title>
<meta name="description" content="Browse all SmartToolz free online tools.">
<link rel="canonical" href="https://smarttoolz.in/tool.php">
</head>
<body>
<main class="tools-page">
<section class="tools-hero">
<span class="tag">SMARTTOOLZ COLLECTION</span>
<h1>All Tools</h1>
<p><?=count($tools)?> free online tools — find exactly what you need.</p>
</section>
<div class="layout">
<aside class="filters" aria-label="Tool categories">
<h3>Categories</h3>
<a class="filter <?=$requestedCategory===''?'active':''?>" href="/tool.php">All Tools <span class="count"><?=count($tools)?></span></a>
<?php foreach ($categories as $cat=>$num): $cs=$categorySlug((string)$cat); ?>
<a class="filter <?=$requestedCategory===$cs?'active':''?>" href="/category/<?=htmlspecialchars($cs,ENT_QUOTES,'UTF-8')?>/"><?=htmlspecialchars((string)$cat,ENT_QUOTES,'UTF-8')?> <span class="count"><?=$num?></span></a>
<?php endforeach; ?>
</aside>
<section class="content">
<input class="search" id="toolSearch" type="search" placeholder="Search tools by name or description…" autocomplete="off" aria-label="Search tools">
<div class="grid" id="toolGrid">
<?php foreach ($visibleTools as $tool):
    $name=(string)($tool['name']??'Tool');
    $description=(string)($tool['description']??'');
    $category=(string)($tool['category']??'Other');
    $icon=(string)($tool['icon']??'build');
    $url=(string)($tool['url']??'#');
?>
<a class="card" data-search="<?=htmlspecialchars(strtolower($name.' '.$description.' '.$category),ENT_QUOTES,'UTF-8')?>" href="<?=htmlspecialchars($url,ENT_QUOTES,'UTF-8')?>">
<span class="icon"><?=st_icon($icon)?></span>
<h2><?=htmlspecialchars($name,ENT_QUOTES,'UTF-8')?></h2>
<p><?=htmlspecialchars($description,ENT_QUOTES,'UTF-8')?></p>
<span class="use">Open Tool <?=st_icon('arrow_forward')?></span>
</a>
<?php endforeach; ?>
</div>
<div class="empty" id="empty" hidden>No tools match your search.</div>
</section>
</div>
</main>
<?php require_once __DIR__ . '/footer.php'; ?>
<script>
(() => {
    const input=document.getElementById('toolSearch');
    const cards=[...document.querySelectorAll('#toolGrid .card')];
    const empty=document.getElementById('empty');
    if(!input)return;
    input.addEventListener('input',()=>{
        const value=input.value.toLowerCase().trim();
        let visible=0;
        cards.forEach(card=>{
            const match=!value||(card.dataset.search||'').includes(value);
            card.hidden=!match;
            if(match)visible++;
        });
        if(empty)empty.hidden=visible!==0;
    });
})();
</script>
</body>
</html>

<?php
/* SmartToolz — All Tools page and central registry. */
$iconsFile = __DIR__ . '/lib/icons.php';
if (is_file($iconsFile)) {
    require_once $iconsFile;
}
if (!function_exists('st_icon')) {
    function st_icon($name, $class = 'st-icon', $label = '') {
        $safe = preg_replace('/[^a-z0-9_-]/i', '', (string)$name);
        if (!$safe) $safe = 'build';
        $safeClass = preg_replace('/[^a-z0-9_-]/i', ' ', (string)$class);
        if (!$safeClass) $safeClass = 'st-icon';
        $aria = $label === '' ? ' aria-hidden="true"' : ' role="img" aria-label="' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '"';
        return '<svg class="' . htmlspecialchars($safeClass, ENT_QUOTES, 'UTF-8') . '" viewBox="0 0 24 24" focusable="false"' . $aria . '><use href="/assets/icons/smarttoolz-icons.svg#' . htmlspecialchars($safe, ENT_QUOTES, 'UTF-8') . '"/></svg>';
    }
}

$tools = array(
    array('name'=>'Image Background Remover','icon'=>'content_cut','category'=>'Image Tools','description'=>'Remove image backgrounds automatically with AI.','url'=>'/tools/image-background-remover/'),
    array('name'=>'Image Compressor','icon'=>'compress','category'=>'Image Tools','description'=>'Compress JPG, PNG and WebP images online.','url'=>'/tools/image-compressor/'),
    array('name'=>'Image Resizer','icon'=>'photo_size_select_large','category'=>'Image Tools','description'=>'Resize images to any width and height.','url'=>'/tools/image-resizer/'),
    array('name'=>'JPG to PNG','icon'=>'swap_horiz','category'=>'Image Tools','description'=>'Convert JPG images into PNG format.','url'=>'/tools/jpg-to-png/'),
    array('name'=>'PNG to JPG','icon'=>'swap_horiz','category'=>'Image Tools','description'=>'Convert PNG images into JPG format.','url'=>'/tools/png-to-jpg/'),
    array('name'=>'Word Counter','icon'=>'article','category'=>'Text Tools','description'=>'Count words, characters, sentences and lines.','url'=>'/tools/word-counter/'),
    array('name'=>'Case Converter','icon'=>'text_fields','category'=>'Text Tools','description'=>'Convert text to uppercase, lowercase and title case.','url'=>'/tools/case-converter/'),
    array('name'=>'QR Code Generator','icon'=>'qr_code_2','category'=>'Generators','description'=>'Create QR codes for URLs and text.','url'=>'/tools/qr-generator/'),
    array('name'=>'Password Generator','icon'=>'lock','category'=>'Security','description'=>'Generate strong and secure passwords.','url'=>'/tools/password-generator/'),
    array('name'=>'JSON Formatter','icon'=>'data_object','category'=>'Developer Tools','description'=>'Format, beautify and validate JSON.','url'=>'/tools/json-formatter/'),
    array('name'=>'URL Encoder','icon'=>'link','category'=>'Developer Tools','description'=>'Encode URLs safely and easily.','url'=>'/tools/url-encoder/'),
    array('name'=>'PDF to JPG','icon'=>'picture_as_pdf','category'=>'PDF Tools','description'=>'Convert PDF pages into JPG images.','url'=>'/tools/pdf-to-jpg/'),
    array('name'=>'Duplicate Line Remover','icon'=>'content_copy','category'=>'Text Tools','description'=>'Remove duplicate lines from text.','url'=>'/tools/remove-duplicate-lines/'),
    array('name'=>'Image Cropper','icon'=>'crop','category'=>'Image Tools','description'=>'Crop images to the exact size you need.','url'=>'/tools/image-cropper/'),
    array('name'=>'Image Rotator','icon'=>'rotate_right','category'=>'Image Tools','description'=>'Rotate images clockwise or counterclockwise.','url'=>'/tools/image-rotator/'),
    array('name'=>'Image Flipper','icon'=>'flip','category'=>'Image Tools','description'=>'Flip images horizontally or vertically.','url'=>'/tools/image-flipper/'),
    array('name'=>'WebP to JPG','icon'=>'image','category'=>'Image Tools','description'=>'Convert WebP images into JPG format.','url'=>'/tools/webp-to-jpg/'),
    array('name'=>'JPG to WebP','icon'=>'swap_horiz','category'=>'Image Tools','description'=>'Convert JPG images to WebP format.','url'=>'/tools/jpg-to-webp/'),
    array('name'=>'PNG to WebP','icon'=>'swap_horiz','category'=>'Image Tools','description'=>'Convert PNG images to WebP format.','url'=>'/tools/png-to-webp/'),
    array('name'=>'GIF to JPG','icon'=>'gif','category'=>'Image Tools','description'=>'Convert GIF images into JPG format.','url'=>'/tools/gif-to-jpg/'),
    array('name'=>'GIF Maker','icon'=>'gif_box','category'=>'Image Tools','description'=>'Create animated GIF files online.','url'=>'/tools/gif-maker/'),
    array('name'=>'Meme Generator','icon'=>'sentiment_very_satisfied','category'=>'Generators','description'=>'Create custom memes with your own image and text.','url'=>'/tools/meme-generator/'),
    array('name'=>'Color Picker','icon'=>'palette','category'=>'Design Tools','description'=>'Pick a color and get HEX and RGB values.','url'=>'/tools/color-picker/'),
    array('name'=>'Color Converter','icon'=>'gradient','category'=>'Design Tools','description'=>'Convert HEX, RGB and HSL color values.','url'=>'/tools/color-converter/'),
    array('name'=>'Base64 Encoder','icon'=>'lock','category'=>'Developer Tools','description'=>'Encode text and data into Base64.','url'=>'/tools/base64-encoder/'),
    array('name'=>'Base64 Decoder','icon'=>'lock_open','category'=>'Developer Tools','description'=>'Decode Base64 encoded text and data.','url'=>'/tools/base64-decoder/'),
    array('name'=>'URL Decoder','icon'=>'link_off','category'=>'Developer Tools','description'=>'Decode URL encoded text.','url'=>'/tools/url-decoder/'),
    array('name'=>'HTML Encoder','icon'=>'code','category'=>'Developer Tools','description'=>'Encode HTML special characters.','url'=>'/tools/html-encoder/'),
    array('name'=>'HTML Decoder','icon'=>'code','category'=>'Developer Tools','description'=>'Decode HTML entities.','url'=>'/tools/html-decoder/'),
    array('name'=>'Markdown to HTML','icon'=>'description','category'=>'Developer Tools','description'=>'Convert Markdown into HTML.','url'=>'/tools/markdown-to-html/'),
    array('name'=>'Favicon Generator','icon'=>'web_asset','category'=>'Developer Tools','description'=>'Create website favicons from images, emoji or text.','url'=>'/tools/favicon-generator/'),
    array('name'=>'Favicon ICO Generator','icon'=>'web_asset','category'=>'Developer Tools','description'=>'Create favicon.ico files from images or text.','url'=>'/tools/favicon-ico-generator/'),
    array('name'=>'Text to Slug','icon'=>'link','category'=>'Text Tools','description'=>'Convert text into URL-friendly slugs.','url'=>'/tools/text-to-slug/'),
    array('name'=>'Remove Extra Spaces','icon'=>'space_bar','category'=>'Text Tools','description'=>'Remove unnecessary spaces from text.','url'=>'/tools/remove-extra-spaces/'),
    array('name'=>'Sort Lines','icon'=>'sort','category'=>'Text Tools','description'=>'Sort lines alphabetically or numerically.','url'=>'/tools/sort-lines/'),
    array('name'=>'Reverse Text','icon'=>'sync','category'=>'Text Tools','description'=>'Reverse text characters instantly.','url'=>'/tools/reverse-text/'),
    array('name'=>'PDF Merger','icon'=>'merge_type','category'=>'PDF Tools','description'=>'Merge multiple PDF files into one.','url'=>'/tools/pdf-merger/'),
    array('name'=>'PDF Splitter','icon'=>'call_split','category'=>'PDF Tools','description'=>'Split PDFs into separate pages.','url'=>'/tools/pdf-splitter/'),
    array('name'=>'PDF Compressor','icon'=>'compress','category'=>'PDF Tools','description'=>'Reduce PDF file size.','url'=>'/tools/pdf-compressor/'),
    array('name'=>'PDF to PNG','icon'=>'picture_as_pdf','category'=>'PDF Tools','description'=>'Convert PDF pages into PNG images.','url'=>'/tools/pdf-to-png/'),
    array('name'=>'PNG to PDF','icon'=>'picture_as_pdf','category'=>'PDF Tools','description'=>'Convert images into PDF documents.','url'=>'/tools/png-to-pdf/'),
    array('name'=>'Text to PDF','icon'=>'picture_as_pdf','category'=>'PDF Tools','description'=>'Convert text content into a PDF.','url'=>'/tools/text-to-pdf/'),
    array('name'=>'QR Code Reader','icon'=>'qr_code_scanner','category'=>'Generators','description'=>'Read QR codes from images.','url'=>'/tools/qr-reader/'),
    array('name'=>'Random Number Generator','icon'=>'casino','category'=>'Generators','description'=>'Generate random numbers in any range.','url'=>'/tools/random-number-generator/'),
    array('name'=>'UUID Generator','icon'=>'fingerprint','category'=>'Developer Tools','description'=>'Generate unique UUID identifiers.','url'=>'/tools/uuid-generator/'),
    array('name'=>'Timestamp Converter','icon'=>'schedule','category'=>'Developer Tools','description'=>'Convert Unix timestamps into readable dates.','url'=>'/tools/timestamp-converter/'),
    array('name'=>'Unix Timestamp','icon'=>'timer','category'=>'Developer Tools','description'=>'Work with Unix timestamps.','url'=>'/tools/unix-timestamp/'),
    array('name'=>'Lorem Ipsum Generator','icon'=>'article','category'=>'Generators','description'=>'Generate placeholder Lorem Ipsum text.','url'=>'/tools/lorem-ipsum-generator/'),
    array('name'=>'Age Calculator','icon'=>'cake','category'=>'Calculators','description'=>'Calculate age from date of birth.','url'=>'/tools/age-calculator/'),
    array('name'=>'Percentage Calculator','icon'=>'percent','category'=>'Calculators','description'=>'Calculate percentages quickly.','url'=>'/tools/percentage-calculator/'),
    array('name'=>'BMI Calculator','icon'=>'monitor_heart','category'=>'Calculators','description'=>'Calculate Body Mass Index.','url'=>'/tools/bmi-calculator/'),
    array('name'=>'Unit Converter','icon'=>'straighten','category'=>'Calculators','description'=>'Convert common units quickly.','url'=>'/tools/unit-converter/'),
    array('name'=>'Stopwatch / Timer','icon'=>'timer','category'=>'Utilities','description'=>'Use an online stopwatch and timer.','url'=>'/tools/stopwatch-timer/')
);

/* When included by home.php, expose only the registry. */
if (defined('SMARTTOOLZ_HOME_REGISTRY')) {
    return;
}

$requestedCategory = isset($_GET['category']) ? trim((string)$_GET['category']) : '';
function smarttoolz_slug($value) {
    $slug = preg_replace('/[^a-z0-9]+/i', '-', (string)$value);
    return trim(strtolower((string)$slug), '-');
}

$categories = array();
foreach ($tools as $tool) {
    $category = trim((string)($tool['category'] ?? 'Other'));
    if ($category === '') $category = 'Other';
    if (!isset($categories[$category])) $categories[$category] = 0;
    $categories[$category]++;
}
ksort($categories, SORT_NATURAL | SORT_FLAG_CASE);

$visibleTools = $tools;
if ($requestedCategory !== '') {
    $visibleTools = array();
    foreach ($tools as $tool) {
        if (smarttoolz_slug($tool['category'] ?? 'Other') === $requestedCategory) {
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
<meta name="description" content="Browse all SmartToolz free online tools for images, PDF, text, developer tasks, calculators and everyday work.">
<meta name="robots" content="index,follow">
<link rel="canonical" href="https://smarttoolz.in/tool.php">
</head>
<body>
<main class="tools-page">
<section class="tools-hero">
<span class="tag">SMARTTOOLZ COLLECTION</span>
<h1>All Tools</h1>
<p><?php echo count($tools); ?> free online tools — find exactly what you need.</p>
</section>
<div class="layout">
<aside class="filters" aria-label="Tool categories">
<h3>Categories</h3>
<a class="filter <?php echo $requestedCategory === '' ? 'active' : ''; ?>" href="/tool.php">All Tools <span class="count"><?php echo count($tools); ?></span></a>
<?php foreach ($categories as $cat => $num): $cs = smarttoolz_slug($cat); ?>
<a class="filter <?php echo $requestedCategory === $cs ? 'active' : ''; ?>" href="/category/<?php echo htmlspecialchars($cs, ENT_QUOTES, 'UTF-8'); ?>/"><?php echo htmlspecialchars($cat, ENT_QUOTES, 'UTF-8'); ?> <span class="count"><?php echo $num; ?></span></a>
<?php endforeach; ?>
</aside>
<section class="content">
<label class="sr-only" for="toolSearch">Search tools</label>
<input class="search" id="toolSearch" type="search" placeholder="Search tools by name or description…" autocomplete="off" aria-label="Search tools">
<div class="grid" id="toolGrid">
<?php foreach ($visibleTools as $tool):
    $name = (string)($tool['name'] ?? 'Tool');
    $description = (string)($tool['description'] ?? '');
    $category = (string)($tool['category'] ?? 'Other');
    $icon = (string)($tool['icon'] ?? 'build');
    $url = (string)($tool['url'] ?? '#');
?>
<a class="card" data-search="<?php echo htmlspecialchars(strtolower($name . ' ' . $description . ' ' . $category), ENT_QUOTES, 'UTF-8'); ?>" href="<?php echo htmlspecialchars($url, ENT_QUOTES, 'UTF-8'); ?>">
<span class="icon"><?php echo st_icon($icon); ?></span>
<h2><?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?></h2>
<p><?php echo htmlspecialchars($description, ENT_QUOTES, 'UTF-8'); ?></p>
<span class="use">Open Tool <?php echo st_icon('arrow_forward'); ?></span>
</a>
<?php endforeach; ?>
</div>
<div class="empty" id="empty" hidden>No tools match your search.</div>
</section>
</div>
</main>
<?php require_once __DIR__ . '/footer.php'; ?>
<script>
(function () {
    var input = document.getElementById('toolSearch');
    var cards = Array.prototype.slice.call(document.querySelectorAll('#toolGrid .card'));
    var empty = document.getElementById('empty');
    if (!input) return;
    input.addEventListener('input', function () {
        var value = input.value.toLowerCase().replace(/^\s+|\s+$/g, '');
        var visible = 0;
        cards.forEach(function (card) {
            var match = !value || (card.getAttribute('data-search') || '').indexOf(value) !== -1;
            card.hidden = !match;
            if (match) visible++;
        });
        if (empty) empty.hidden = visible !== 0;
    });
}());
</script>
</body>
</html>

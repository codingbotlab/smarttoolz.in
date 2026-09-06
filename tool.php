<?php
declare(strict_types=1);

require_once __DIR__ . '/lib/icons.php';
require_once __DIR__ . '/header.php';

function smarttoolz_icon(string $name): string {
    if (function_exists('st_icon')) {
        return st_icon($name);
    }
    $safe = preg_replace('/[^a-z0-9_-]/i', '', $name) ?: 'build';
    return '<svg class="st-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><use href="/assets/icons/smarttoolz-icons.svg#' . htmlspecialchars($safe, ENT_QUOTES, 'UTF-8') . '"></use></svg>';
}

$tools = [
    ['Image Background Remover','content_cut','Image Tools','Remove image backgrounds automatically with AI.','/tools/image-background-remover/'],
    ['Image Compressor','compress','Image Tools','Compress JPG, PNG and WebP images online.','/tools/image-compressor/'],
    ['Image Resizer','photo_size_select_large','Image Tools','Resize images to any width and height.','/tools/image-resizer/'],
    ['JPG to PNG','swap_horiz','Image Tools','Convert JPG images into PNG format.','/tools/jpg-to-png/'],
    ['PNG to JPG','swap_horiz','Image Tools','Convert PNG images into JPG format.','/tools/png-to-jpg/'],
    ['Word Counter','article','Text Tools','Count words, characters, sentences and paragraphs.','/tools/word-counter/'],
    ['Case Converter','text_fields','Text Tools','Convert text to uppercase, lowercase and title case.','/tools/case-converter/'],
    ['QR Code Generator','qr_code_2','Generators','Create QR codes for URLs and text.','/tools/qr-generator/'],
    ['Password Generator','lock','Security','Generate strong and secure passwords.','/tools/password-generator/'],
    ['JSON Formatter','data_object','Developer Tools','Format, beautify and validate JSON.','/tools/json-formatter/'],
    ['URL Encoder','link','Developer Tools','Encode URLs safely and easily.','/tools/url-encoder/'],
    ['PDF to JPG','picture_as_pdf','PDF Tools','Convert PDF pages into JPG images.','/tools/pdf-to-jpg/'],
    ['Duplicate Line Remover','content_copy','Text Tools','Remove duplicate lines from text.','/tools/remove-duplicate-lines/'],
    ['Image Cropper','crop','Image Tools','Crop images to the exact size you need.','/tools/image-cropper/'],
    ['Image Rotator','rotate_right','Image Tools','Rotate images clockwise or counterclockwise.','/tools/image-rotator/'],
    ['Image Flipper','flip','Image Tools','Flip images horizontally or vertically.','/tools/image-flipper/'],
    ['WebP to JPG','image','Image Tools','Convert WebP images into JPG format.','/tools/webp-to-jpg/'],
    ['JPG to WebP','swap_horiz','Image Tools','Convert JPG images to WebP format.','/tools/jpg-to-webp/'],
    ['PNG to WebP','swap_horiz','Image Tools','Convert PNG images to WebP format.','/tools/png-to-webp/'],
    ['GIF to JPG','gif','Image Tools','Convert GIF images into JPG format.','/tools/gif-to-jpg/'],
    ['GIF Maker','gif_box','Image Tools','Create animated GIF files online.','/tools/gif-maker/'],
    ['Meme Generator','sentiment_very_satisfied','Generators','Create custom memes with your own image and text.','/tools/meme-generator/'],
    ['Color Picker','palette','Design Tools','Pick a color and get HEX and RGB values.','/tools/color-picker/'],
    ['Color Converter','gradient','Design Tools','Convert HEX, RGB and HSL color values.','/tools/color-converter/'],
    ['Base64 Encoder','lock','Developer Tools','Encode text and data into Base64.','/tools/base64-encoder/'],
    ['Base64 Decoder','lock_open','Developer Tools','Decode Base64 encoded text and data.','/tools/base64-decoder/'],
    ['URL Decoder','link_off','Developer Tools','Decode URL encoded text.','/tools/url-decoder/'],
    ['HTML Encoder','code','Developer Tools','Encode HTML special characters.','/tools/html-encoder/'],
    ['HTML Decoder','code','Developer Tools','Decode HTML entities.','/tools/html-decoder/'],
    ['Markdown to HTML','description','Developer Tools','Convert Markdown into HTML.','/tools/markdown-to-html/'],
    ['Favicon Generator','web_asset','Developer Tools','Create website favicons from images, emoji or text.','/tools/favicon-generator/'],
    ['Favicon ICO Generator','web_asset','Developer Tools','Create favicon.ico files from images or text.','/tools/favicon-ico-generator/'],
    ['Text to Slug','link','Text Tools','Convert text into URL-friendly slugs.','/tools/text-to-slug/'],
    ['Remove Extra Spaces','space_bar','Text Tools','Remove unnecessary spaces from text.','/tools/remove-extra-spaces/'],
    ['Sort Lines','sort','Text Tools','Sort lines alphabetically or numerically.','/tools/sort-lines/'],
    ['Reverse Text','sync','Text Tools','Reverse text characters instantly.','/tools/reverse-text/'],
    ['PDF Merger','merge_type','PDF Tools','Merge multiple PDF files into one.','/tools/pdf-merger/'],
    ['PDF Splitter','call_split','PDF Tools','Split PDFs into separate pages.','/tools/pdf-splitter/'],
    ['PDF Compressor','compress','PDF Tools','Reduce PDF file size.','/tools/pdf-compressor/'],
    ['PDF to PNG','picture_as_pdf','PDF Tools','Convert PDF pages into PNG images.','/tools/pdf-to-png/'],
    ['PNG to PDF','picture_as_pdf','PDF Tools','Convert images into PDF documents.','/tools/png-to-pdf/'],
    ['Text to PDF','picture_as_pdf','PDF Tools','Convert text content into a PDF.','/tools/text-to-pdf/'],
    ['QR Code Reader','qr_code_scanner','Generators','Read QR codes from images.','/tools/qr-reader/'],
    ['Random Number Generator','casino','Generators','Generate random numbers in any range.','/tools/random-number-generator/'],
    ['UUID Generator','fingerprint','Developer Tools','Generate unique UUID identifiers.','/tools/uuid-generator/'],
    ['Timestamp Converter','schedule','Developer Tools','Convert Unix timestamps into readable dates.','/tools/timestamp-converter/'],
    ['Unix Timestamp','timer','Developer Tools','Work with Unix timestamps.','/tools/unix-timestamp/'],
    ['Lorem Ipsum Generator','article','Generators','Generate placeholder Lorem Ipsum text.','/tools/lorem-ipsum-generator/'],
    ['Age Calculator','cake','Calculators','Calculate age from date of birth.','/tools/age-calculator/'],
    ['Percentage Calculator','percent','Calculators','Calculate percentages quickly.','/tools/percentage-calculator/'],
    ['BMI Calculator','monitor_heart','Calculators','Calculate Body Mass Index.','/tools/bmi-calculator/'],
    ['Unit Converter','straighten','Calculators','Convert common units quickly.','/tools/unit-converter/'],
    ['Stopwatch / Timer','timer','Utilities','Use an online stopwatch and timer.','/tools/stopwatch-timer/'],
];

$requestedCategory = isset($_GET['category']) ? trim((string)$_GET['category']) : '';
$slugify = static function (string $value): string {
    $slug = preg_replace('/[^a-z0-9]+/i', '-', $value);
    return trim(strtolower((string)$slug), '-');
};

$categories = [];
foreach ($tools as $tool) {
    $categories[$tool[2]] = ($categories[$tool[2]] ?? 0) + 1;
}
uksort($categories, 'strnatcasecmp');

$visibleTools = $tools;
if ($requestedCategory !== '') {
    $visibleTools = array_values(array_filter($tools, static fn(array $tool): bool => $slugify($tool[2]) === $requestedCategory));
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>All Free Online Tools | SmartToolz</title>
<meta name="description" content="Browse all SmartToolz free online tools for images, PDF, text, developer tasks, calculators and everyday work.">
<meta name="robots" content="index,follow,max-image-preview:large">
<link rel="canonical" href="https://smarttoolz.in/tool.php">
<style>
:root{--brand:#635bff;--brand-dark:#5148e8;--ink:#101828;--muted:#667085;--line:#e6e8ef;--bg:#f6f8fc}
*{box-sizing:border-box}html{scroll-behavior:smooth}body{margin:0;background:var(--bg);color:var(--ink);font-family:Inter,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif}.tools-page{width:min(1240px,calc(100% - 28px));margin:0 auto 56px}.tools-hero{text-align:center;padding:46px 0 28px}.tools-hero .tag{display:inline-flex;padding:7px 11px;border:1px solid #dedbff;border-radius:999px;background:#efedff;color:var(--brand);font-size:10px;font-weight:900;letter-spacing:1px}.tools-hero h1{margin:13px 0 7px;font-size:clamp(34px,5vw,52px);line-height:1.08;letter-spacing:-2px}.tools-hero p{margin:0;color:var(--muted);font-size:14px}.layout{display:grid;grid-template-columns:230px minmax(0,1fr);gap:22px;align-items:start}.filters{position:sticky;top:90px;background:#fff;border:1px solid var(--line);border-radius:18px;padding:15px;box-shadow:0 10px 30px rgba(16,24,40,.04)}.filters h3{margin:3px 8px 10px;font-size:14px}.filter{display:flex;justify-content:space-between;align-items:center;gap:8px;padding:10px;border-radius:10px;color:#475467;text-decoration:none;font-size:12px;font-weight:750}.filter:hover,.filter.active{background:#efedff;color:var(--brand)}.count{min-width:25px;padding:3px 7px;border-radius:999px;background:#f2f4f7;color:#667085;text-align:center;font-size:10px}.filter.active .count{background:#ddd9ff;color:#5148e8}.content{min-width:0}.search{width:100%;height:48px;padding:0 15px;border:1px solid var(--line);border-radius:13px;background:#fff;outline:0;color:var(--ink);font:inherit;box-shadow:0 8px 25px rgba(16,24,40,.03)}.search:focus{border-color:#bcb7ff;box-shadow:0 0 0 3px rgba(99,91,255,.08)}.grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:13px;margin-top:15px}.card{display:flex;min-height:190px;flex-direction:column;padding:18px;background:#fff;border:1px solid var(--line);border-radius:17px;text-decoration:none;color:var(--ink);box-shadow:0 10px 30px rgba(16,24,40,.035);transition:.2s}.card:hover{transform:translateY(-3px);border-color:#d8d4ff;box-shadow:0 18px 40px rgba(16,24,40,.08)}.icon{width:42px;height:42px;display:grid;place-items:center;margin-bottom:13px;border-radius:12px;background:#efedff;color:var(--brand)}.st-icon{width:20px;height:20px;fill:currentColor}.card h2{margin:0 0 6px;font-size:14px;line-height:1.3}.card p{margin:0;color:var(--muted);font-size:11px;line-height:1.55}.use{display:flex;align-items:center;gap:5px;margin-top:auto;padding-top:15px;color:var(--brand);font-size:10px;font-weight:850}.empty{padding:30px 10px;text-align:center;color:var(--muted);font-size:13px}@media(max-width:900px){.layout{grid-template-columns:1fr}.filters{position:static}.grid{grid-template-columns:repeat(2,minmax(0,1fr))}}@media(max-width:560px){.tools-page{width:calc(100% - 18px)}.tools-hero{padding:30px 0 20px}.grid{grid-template-columns:1fr}.card{min-height:165px}}
</style>
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
<?php foreach ($categories as $category => $count): $slug = $slugify($category); ?>
<a class="filter <?php echo $requestedCategory === $slug ? 'active' : ''; ?>" href="/category/<?php echo htmlspecialchars($slug, ENT_QUOTES, 'UTF-8'); ?>/"><?php echo htmlspecialchars($category, ENT_QUOTES, 'UTF-8'); ?> <span class="count"><?php echo $count; ?></span></a>
<?php endforeach; ?>
</aside>
<section class="content">
<label for="toolSearch" class="sr-only">Search tools</label>
<input class="search" id="toolSearch" type="search" placeholder="Search tools by name or description…" autocomplete="off">
<div class="grid" id="toolGrid">
<?php foreach ($visibleTools as $tool): ?>
<a class="card" href="<?php echo htmlspecialchars($tool[4], ENT_QUOTES, 'UTF-8'); ?>" data-search="<?php echo htmlspecialchars(strtolower($tool[0] . ' ' . $tool[2] . ' ' . $tool[3]), ENT_QUOTES, 'UTF-8'); ?>">
<span class="icon"><?php echo smarttoolz_icon($tool[1]); ?></span>
<h2><?php echo htmlspecialchars($tool[0], ENT_QUOTES, 'UTF-8'); ?></h2>
<p><?php echo htmlspecialchars($tool[3], ENT_QUOTES, 'UTF-8'); ?></p>
<span class="use">Open Tool <?php echo smarttoolz_icon('arrow_forward'); ?></span>
</a>
<?php endforeach; ?>
</div>
<div class="empty" id="empty" hidden>No tools match your search.</div>
</section>
</div>
</main>
<?php require_once __DIR__ . '/footer.php'; ?>
<script>
(function(){
 var input=document.getElementById('toolSearch');
 var cards=[].slice.call(document.querySelectorAll('#toolGrid .card'));
 var empty=document.getElementById('empty');
 if(!input)return;
 input.addEventListener('input',function(){
   var q=input.value.toLowerCase().trim();
   var shown=0;
   cards.forEach(function(card){
     var match=!q || (card.getAttribute('data-search')||'').indexOf(q)!==-1;
     card.hidden=!match;
     if(match)shown++;
   });
   if(empty)empty.hidden=shown!==0;
 });
}());
</script>
</body>
</html>

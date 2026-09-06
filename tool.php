<?php
/* SmartToolz — standalone All Tools page. */

$tools = array(
    array('Image Background Remover','✂','Image Tools','Remove image backgrounds automatically with AI.','/tools/image-background-remover/'),
    array('Image Compressor','▣','Image Tools','Compress JPG, PNG and WebP images online.','/tools/image-compressor/'),
    array('Image Resizer','↔','Image Tools','Resize images to any width and height.','/tools/image-resizer/'),
    array('JPG to PNG','↔','Image Tools','Convert JPG images into PNG format.','/tools/jpg-to-png/'),
    array('PNG to JPG','↔','Image Tools','Convert PNG images into JPG format.','/tools/png-to-jpg/'),
    array('Word Counter','Aa','Text Tools','Count words, characters, sentences and paragraphs.','/tools/word-counter/'),
    array('Case Converter','Aa','Text Tools','Convert text to uppercase, lowercase and title case.','/tools/case-converter/'),
    array('QR Code Generator','▦','Generators','Create QR codes for URLs and text.','/tools/qr-generator/'),
    array('Password Generator','🔒','Security','Generate strong and secure passwords.','/tools/password-generator/'),
    array('JSON Formatter','{}','Developer Tools','Format, beautify and validate JSON.','/tools/json-formatter/'),
    array('URL Encoder','↗','Developer Tools','Encode URLs safely and easily.','/tools/url-encoder/'),
    array('PDF to JPG','PDF','PDF Tools','Convert PDF pages into JPG images.','/tools/pdf-to-jpg/'),
    array('Duplicate Line Remover','≡','Text Tools','Remove duplicate lines from text.','/tools/remove-duplicate-lines/'),
    array('Image Cropper','⌗','Image Tools','Crop images to the exact size you need.','/tools/image-cropper/'),
    array('Image Rotator','↻','Image Tools','Rotate images clockwise or counterclockwise.','/tools/image-rotator/'),
    array('Image Flipper','⇆','Image Tools','Flip images horizontally or vertically.','/tools/image-flipper/'),
    array('WebP to JPG','W','Image Tools','Convert WebP images into JPG format.','/tools/webp-to-jpg/'),
    array('JPG to WebP','W','Image Tools','Convert JPG images to WebP format.','/tools/jpg-to-webp/'),
    array('PNG to WebP','W','Image Tools','Convert PNG images to WebP format.','/tools/png-to-webp/'),
    array('GIF to JPG','GIF','Image Tools','Convert GIF images into JPG format.','/tools/gif-to-jpg/'),
    array('GIF Maker','GIF','Image Tools','Create animated GIF files online.','/tools/gif-maker/'),
    array('Meme Generator','M','Generators','Create custom memes with your own image and text.','/tools/meme-generator/'),
    array('Color Picker','◉','Design Tools','Pick a color and get HEX and RGB values.','/tools/color-picker/'),
    array('Color Converter','◈','Design Tools','Convert HEX, RGB and HSL color values.','/tools/color-converter/'),
    array('Base64 Encoder','B64','Developer Tools','Encode text and data into Base64.','/tools/base64-encoder/'),
    array('Base64 Decoder','B64','Developer Tools','Decode Base64 encoded text and data.','/tools/base64-decoder/'),
    array('URL Decoder','↙','Developer Tools','Decode URL encoded text.','/tools/url-decoder/'),
    array('HTML Encoder','</>','Developer Tools','Encode HTML special characters.','/tools/html-encoder/'),
    array('HTML Decoder','</>','Developer Tools','Decode HTML entities.','/tools/html-decoder/'),
    array('Markdown to HTML','MD','Developer Tools','Convert Markdown into HTML.','/tools/markdown-to-html/'),
    array('Favicon Generator','★','Developer Tools','Create website favicons from images, emoji or text.','/tools/favicon-generator/'),
    array('Favicon ICO Generator','ICO','Developer Tools','Create favicon.ico files from images or text.','/tools/favicon-ico-generator/'),
    array('Text to Slug','↗','Text Tools','Convert text into URL-friendly slugs.','/tools/text-to-slug/'),
    array('Remove Extra Spaces','⌫','Text Tools','Remove unnecessary spaces from text.','/tools/remove-extra-spaces/'),
    array('Sort Lines','↕','Text Tools','Sort lines alphabetically or numerically.','/tools/sort-lines/'),
    array('Reverse Text','↔','Text Tools','Reverse text characters instantly.','/tools/reverse-text/'),
    array('PDF Merger','⊕','PDF Tools','Merge multiple PDF files into one.','/tools/pdf-merger/'),
    array('PDF Splitter','÷','PDF Tools','Split PDFs into separate pages.','/tools/pdf-splitter/'),
    array('PDF Compressor','▣','PDF Tools','Reduce PDF file size.','/tools/pdf-compressor/'),
    array('PDF to PNG','PDF','PDF Tools','Convert PDF pages into PNG images.','/tools/pdf-to-png/'),
    array('PNG to PDF','PDF','PDF Tools','Convert images into PDF documents.','/tools/png-to-pdf/'),
    array('Text to PDF','PDF','PDF Tools','Convert text content into a PDF.','/tools/text-to-pdf/'),
    array('QR Code Reader','▦','Generators','Read QR codes from images.','/tools/qr-reader/'),
    array('Random Number Generator','123','Generators','Generate random numbers in any range.','/tools/random-number-generator/'),
    array('UUID Generator','ID','Developer Tools','Generate unique UUID identifiers.','/tools/uuid-generator/'),
    array('Timestamp Converter','T','Developer Tools','Convert Unix timestamps into readable dates.','/tools/timestamp-converter/'),
    array('Unix Timestamp','T','Developer Tools','Work with Unix timestamps.','/tools/unix-timestamp/'),
    array('Lorem Ipsum Generator','TXT','Generators','Generate placeholder Lorem Ipsum text.','/tools/lorem-ipsum-generator/'),
    array('Age Calculator','AGE','Calculators','Calculate age from date of birth.','/tools/age-calculator/'),
    array('Percentage Calculator','%','Calculators','Calculate percentages quickly.','/tools/percentage-calculator/'),
    array('BMI Calculator','BMI','Calculators','Calculate Body Mass Index.','/tools/bmi-calculator/'),
    array('Unit Converter','↔','Calculators','Convert common units quickly.','/tools/unit-converter/'),
    array('Stopwatch / Timer','⏱','Utilities','Use an online stopwatch and timer.','/tools/stopwatch-timer/')
);

$requestedCategory = isset($_GET['category']) ? trim((string)$_GET['category']) : '';

function smarttoolz_slugify($value) {
    $slug = preg_replace('/[^a-z0-9]+/i', '-', (string)$value);
    return trim(strtolower((string)$slug), '-');
}

$categories = array();
foreach ($tools as $tool) {
    $cat = $tool[2];
    if (!isset($categories[$cat])) {
        $categories[$cat] = 0;
    }
    $categories[$cat]++;
}

uksort($categories, function ($a, $b) {
    return strcasecmp($a, $b);
});

$visibleTools = array();
foreach ($tools as $tool) {
    if ($requestedCategory === '' || smarttoolz_slugify($tool[2]) === $requestedCategory) {
        $visibleTools[] = $tool;
    }
}

$totalTools = count($tools);
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
<meta property="og:type" content="website">
<meta property="og:site_name" content="SmartToolz">
<meta property="og:title" content="All Free Online Tools | SmartToolz">
<meta property="og:description" content="Browse free SmartToolz online tools for images, PDFs, text, developers, calculators and everyday tasks.">
<meta property="og:url" content="https://smarttoolz.in/tool.php">
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="All Free Online Tools | SmartToolz">
<meta name="twitter:description" content="Browse all SmartToolz free online tools.">
<meta name="theme-color" content="#635bff">
<style>
*{box-sizing:border-box}html{scroll-behavior:smooth}body{margin:0;background:#f6f8fc;color:#101828;font-family:Inter,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif;line-height:1.6}
a{text-decoration:none;color:inherit}.site-header{position:sticky;top:0;z-index:1000;background:rgba(255,255,255,.97);backdrop-filter:blur(14px);border-bottom:1px solid #e5e9f0}.navbar{width:min(1240px,calc(100% - 28px));min-height:72px;margin:auto;display:flex;align-items:center;justify-content:space-between}.logo{display:flex;align-items:center;gap:10px;font-size:21px;font-weight:850}.logo-icon{width:42px;height:42px;border-radius:13px;display:grid;place-items:center;background:linear-gradient(135deg,#635bff,#916cff);color:#fff}.nav-links{display:flex;gap:28px}.nav-links a{font-size:13px;font-weight:700;color:#596477}.nav-links a:hover{color:#635bff}.page{width:min(1240px,calc(100% - 28px));margin:0 auto 60px}.hero{text-align:center;padding:48px 0 30px}.tag{display:inline-flex;padding:7px 11px;border:1px solid #dedbff;border-radius:999px;background:#efedff;color:#635bff;font-size:10px;font-weight:900;letter-spacing:1px}.hero h1{margin:14px 0 7px;font-size:clamp(34px,5vw,52px);line-height:1.08;letter-spacing:-2px}.hero p{margin:0;color:#667085;font-size:14px}.layout{display:grid;grid-template-columns:230px minmax(0,1fr);gap:22px;align-items:start}.filters{position:sticky;top:90px;background:#fff;border:1px solid #e6e8ef;border-radius:18px;padding:15px;box-shadow:0 10px 30px rgba(16,24,40,.04)}.filters h2{margin:3px 8px 10px;font-size:14px}.filter{display:flex;align-items:center;justify-content:space-between;gap:8px;padding:10px;border-radius:10px;color:#475467;font-size:12px;font-weight:750}.filter:hover,.filter.active{background:#efedff;color:#635bff}.count{min-width:25px;padding:3px 7px;border-radius:999px;background:#f2f4f7;color:#667085;text-align:center;font-size:10px}.filter.active .count{background:#ddd9ff;color:#5148e8}.search{width:100%;height:48px;padding:0 15px;border:1px solid #e6e8ef;border-radius:13px;background:#fff;outline:0;font:inherit;color:#101828;box-shadow:0 8px 25px rgba(16,24,40,.03)}.search:focus{border-color:#bcb7ff;box-shadow:0 0 0 3px rgba(99,91,255,.08)}.grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:13px;margin-top:15px}.card{display:flex;min-height:190px;flex-direction:column;padding:18px;background:#fff;border:1px solid #e6e8ef;border-radius:17px;box-shadow:0 10px 30px rgba(16,24,40,.035);transition:.2s}.card:hover{transform:translateY(-3px);border-color:#d8d4ff;box-shadow:0 18px 40px rgba(16,24,40,.08)}.icon{width:42px;height:42px;display:grid;place-items:center;margin-bottom:13px;border-radius:12px;background:#efedff;color:#635bff;font-size:13px;font-weight:900}.card h3{margin:0 0 6px;font-size:14px;line-height:1.3}.card p{margin:0;color:#667085;font-size:11px;line-height:1.55}.use{margin-top:auto;padding-top:15px;color:#635bff;font-size:10px;font-weight:850}.empty{margin-top:15px;padding:30px 10px;text-align:center;color:#667085;background:#fff;border:1px solid #e6e8ef;border-radius:15px}.footer{padding:34px 16px;background:#fff;border-top:1px solid #e6e8ef}.footer-inner{width:min(1240px,calc(100% - 28px));margin:auto;display:flex;align-items:center;justify-content:space-between;gap:20px;color:#667085;font-size:11px}.footer-brand{font-weight:850;color:#101828}.footer-links{display:flex;flex-wrap:wrap;gap:15px}.footer-links a:hover{color:#635bff;text-decoration:underline}@media(max-width:900px){.layout{grid-template-columns:1fr}.filters{position:static}.grid{grid-template-columns:repeat(2,minmax(0,1fr))}}@media(max-width:560px){.navbar{min-height:64px}.nav-links{gap:14px}.nav-links a{font-size:12px}.page{width:calc(100% - 18px)}.hero{padding:30px 0 20px}.grid{grid-template-columns:1fr}.card{min-height:165px}.footer-inner{flex-direction:column;text-align:center}}
</style>
</head>
<body>
<header class="site-header"><nav class="navbar" aria-label="Primary navigation"><a class="logo" href="/"><span class="logo-icon" aria-hidden="true">✦</span><span>SmartToolz</span></a><div class="nav-links"><a href="/">Home</a><a href="/tool.php" aria-current="page">All Tools</a><a href="/tool.php#categories">Categories</a></div></nav></header>
<main class="page">
<section class="hero"><span class="tag">SMARTTOOLZ COLLECTION</span><h1>All Tools</h1><p><?php echo $totalTools; ?> free online tools — find exactly what you need.</p></section>
<div class="layout" id="categories">
<aside class="filters" aria-label="Tool categories"><h2>Categories</h2>
<a class="filter <?php echo $requestedCategory === '' ? 'active' : ''; ?>" href="/tool.php">All Tools <span class="count"><?php echo $totalTools; ?></span></a>
<?php foreach ($categories as $category => $count): $slug = smarttoolz_slugify($category); ?>
<a class="filter <?php echo $requestedCategory === $slug ? 'active' : ''; ?>" href="/category/<?php echo htmlspecialchars($slug, ENT_QUOTES, 'UTF-8'); ?>/"><?php echo htmlspecialchars($category, ENT_QUOTES, 'UTF-8'); ?><span class="count"><?php echo $count; ?></span></a>
<?php endforeach; ?>
</aside>
<section>
<label for="toolSearch" style="position:absolute;left:-9999px">Search tools</label>
<input class="search" id="toolSearch" type="search" placeholder="Search tools by name or description…" autocomplete="off">
<div class="grid" id="toolGrid">
<?php foreach ($visibleTools as $tool): ?>
<a class="card" href="<?php echo htmlspecialchars($tool[4], ENT_QUOTES, 'UTF-8'); ?>" data-search="<?php echo htmlspecialchars(strtolower($tool[0] . ' ' . $tool[2] . ' ' . $tool[3]), ENT_QUOTES, 'UTF-8'); ?>">
<span class="icon" aria-hidden="true"><?php echo htmlspecialchars($tool[1], ENT_QUOTES, 'UTF-8'); ?></span>
<h3><?php echo htmlspecialchars($tool[0], ENT_QUOTES, 'UTF-8'); ?></h3>
<p><?php echo htmlspecialchars($tool[3], ENT_QUOTES, 'UTF-8'); ?></p>
<span class="use">Open Tool →</span>
</a>
<?php endforeach; ?>
</div>
<div class="empty" id="empty" hidden>No tools match your search.</div>
</section>
</div>
</main>
<footer class="footer"><div class="footer-inner"><div><span class="footer-brand">SmartToolz</span> — free tools for everyday work.</div><nav class="footer-links" aria-label="Footer navigation"><a href="/">Home</a><a href="/tool.php">All Tools</a><a href="/about.php">About</a><a href="/contact.php">Contact</a><a href="/privacy-policy.php">Privacy</a><a href="/cookie-policy.php">Cookies</a><a href="/terms.php">Terms</a><a href="/disclaimer.php">Disclaimer</a></nav></div></footer>
<script>
(function(){
 var input=document.getElementById('toolSearch');
 var cards=[].slice.call(document.querySelectorAll('#toolGrid .card'));
 var empty=document.getElementById('empty');
 if(!input)return;
 input.addEventListener('input',function(){
   var q=input.value.toLowerCase().replace(/^\s+|\s+$/g,'');
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

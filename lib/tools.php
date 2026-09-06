<?php
declare(strict_types=1);

/**
 * SmartToolz public tool registry.
 * Keep this file as the single source of truth for names, categories,
 * descriptions and canonical URLs used by the homepage, All Tools page,
 * navigation and sitemap automation.
 */
function smarttoolz_tools(): array
{
    static $tools = null;
    if ($tools !== null) {
        return $tools;
    }

    $tools = [
        ['name'=>'Image Background Remover','category'=>'Image Tools','description'=>'Remove image backgrounds automatically in your browser.','url'=>'/tools/image-background-remover/','icon'=>'✂'],
        ['name'=>'Image Compressor','category'=>'Image Tools','description'=>'Compress JPG, PNG and WebP images online.','url'=>'/tools/image-compressor/','icon'=>'▣'],
        ['name'=>'Image Resizer','category'=>'Image Tools','description'=>'Resize images to exact width and height.','url'=>'/tools/image-resizer/','icon'=>'↔'],
        ['name'=>'JPG to PNG','category'=>'Image Tools','description'=>'Convert JPG images into PNG format.','url'=>'/tools/jpg-to-png/','icon'=>'↔'],
        ['name'=>'PNG to JPG','category'=>'Image Tools','description'=>'Convert PNG images into JPG format.','url'=>'/tools/png-to-jpg/','icon'=>'↔'],
        ['name'=>'Word Counter','category'=>'Text Tools','description'=>'Count words, characters, sentences and paragraphs.','url'=>'/tools/word-counter/','icon'=>'Aa'],
        ['name'=>'Case Converter','category'=>'Text Tools','description'=>'Convert text to uppercase, lowercase and title case.','url'=>'/tools/case-converter/','icon'=>'Aa'],
        ['name'=>'QR Code Generator','category'=>'Generators','description'=>'Create QR codes for URLs and text.','url'=>'/tools/qr-generator/','icon'=>'▦'],
        ['name'=>'Password Generator','category'=>'Security','description'=>'Generate strong random passwords.','url'=>'/tools/password-generator/','icon'=>'••'],
        ['name'=>'JSON Formatter','category'=>'Developer Tools','description'=>'Format, beautify and validate JSON.','url'=>'/tools/json-formatter/','icon'=>'{}'],
        ['name'=>'URL Encoder','category'=>'Developer Tools','description'=>'Encode URLs safely and easily.','url'=>'/tools/url-encoder/','icon'=>'↗'],
        ['name'=>'PDF to JPG','category'=>'PDF Tools','description'=>'Convert PDF pages into JPG images.','url'=>'/tools/pdf-to-jpg/','icon'=>'PDF'],
        ['name'=>'Duplicate Line Remover','category'=>'Text Tools','description'=>'Remove duplicate lines from text.','url'=>'/tools/remove-duplicate-lines/','icon'=>'≡'],
        ['name'=>'Image Cropper','category'=>'Image Tools','description'=>'Crop images to the exact area you need.','url'=>'/tools/image-cropper/','icon'=>'⌗'],
        ['name'=>'Image Rotator','category'=>'Image Tools','description'=>'Rotate images clockwise or counterclockwise.','url'=>'/tools/image-rotator/','icon'=>'↻'],
        ['name'=>'Image Flipper','category'=>'Image Tools','description'=>'Flip images horizontally or vertically.','url'=>'/tools/image-flipper/','icon'=>'⇆'],
        ['name'=>'WebP to JPG','category'=>'Image Tools','description'=>'Convert WebP images into JPG format.','url'=>'/tools/webp-to-jpg/','icon'=>'W'],
        ['name'=>'JPG to WebP','category'=>'Image Tools','description'=>'Convert JPG images to WebP format.','url'=>'/tools/jpg-to-webp/','icon'=>'W'],
        ['name'=>'PNG to WebP','category'=>'Image Tools','description'=>'Convert PNG images to WebP format.','url'=>'/tools/png-to-webp/','icon'=>'W'],
        ['name'=>'GIF to JPG','category'=>'Image Tools','description'=>'Convert GIF images into JPG format.','url'=>'/tools/gif-to-jpg/','icon'=>'GIF'],
        ['name'=>'GIF Maker','category'=>'Image Tools','description'=>'Create animated GIF files online.','url'=>'/tools/gif-maker/','icon'=>'GIF'],
        ['name'=>'Meme Generator','category'=>'Generators','description'=>'Create custom memes with your own image and text.','url'=>'/tools/meme-generator/','icon'=>'M'],
        ['name'=>'Color Picker','category'=>'Design Tools','description'=>'Pick a color and get HEX, RGB and HSL values.','url'=>'/tools/color-picker/','icon'=>'◉'],
        ['name'=>'Color Converter','category'=>'Design Tools','description'=>'Convert HEX, RGB and HSL color values.','url'=>'/tools/color-converter/','icon'=>'◈'],
        ['name'=>'Base64 Encoder','category'=>'Developer Tools','description'=>'Encode text and data into Base64.','url'=>'/tools/base64-encoder/','icon'=>'B64'],
        ['name'=>'Base64 Decoder','category'=>'Developer Tools','description'=>'Decode Base64 encoded text and data.','url'=>'/tools/base64-decoder/','icon'=>'B64'],
        ['name'=>'URL Decoder','category'=>'Developer Tools','description'=>'Decode URL encoded text.','url'=>'/tools/url-decoder/','icon'=>'↙'],
        ['name'=>'HTML Encoder','category'=>'Developer Tools','description'=>'Encode HTML special characters.','url'=>'/tools/html-encoder/','icon'=>'&lt;&gt;'],
        ['name'=>'HTML Decoder','category'=>'Developer Tools','description'=>'Decode HTML entities.','url'=>'/tools/html-decoder/','icon'=>'&lt;&gt;'],
        ['name'=>'Markdown to HTML','category'=>'Developer Tools','description'=>'Convert Markdown into HTML.','url'=>'/tools/markdown-to-html/','icon'=>'MD'],
        ['name'=>'Favicon Generator','category'=>'Developer Tools','description'=>'Create website favicons from images, emoji or text.','url'=>'/tools/favicon-generator/','icon'=>'★'],
        ['name'=>'Favicon ICO Generator','category'=>'Developer Tools','description'=>'Create favicon.ico files from images or text.','url'=>'/tools/favicon-ico-generator/','icon'=>'ICO'],
        ['name'=>'Text to Slug','category'=>'Text Tools','description'=>'Convert text into URL-friendly slugs.','url'=>'/tools/text-to-slug/','icon'=>'↗'],
        ['name'=>'Remove Extra Spaces','category'=>'Text Tools','description'=>'Remove unnecessary spaces from text.','url'=>'/tools/remove-extra-spaces/','icon'=>'⌫'],
        ['name'=>'Sort Lines','category'=>'Text Tools','description'=>'Sort lines alphabetically or numerically.','url'=>'/tools/sort-lines/','icon'=>'↕'],
        ['name'=>'Reverse Text','category'=>'Text Tools','description'=>'Reverse text characters instantly.','url'=>'/tools/reverse-text/','icon'=>'↔'],
        ['name'=>'PDF Merger','category'=>'PDF Tools','description'=>'Merge multiple PDF files into one.','url'=>'/tools/pdf-merger/','icon'=>'⊕'],
        ['name'=>'PDF Splitter','category'=>'PDF Tools','description'=>'Split PDFs into separate pages.','url'=>'/tools/pdf-splitter/','icon'=>'÷'],
        ['name'=>'PDF Compressor','category'=>'PDF Tools','description'=>'Reduce PDF file size.','url'=>'/tools/pdf-compressor/','icon'=>'▣'],
        ['name'=>'PDF to PNG','category'=>'PDF Tools','description'=>'Convert PDF pages into PNG images.','url'=>'/tools/pdf-to-png/','icon'=>'PDF'],
        ['name'=>'PNG to PDF','category'=>'PDF Tools','description'=>'Convert images into PDF documents.','url'=>'/tools/png-to-pdf/','icon'=>'PDF'],
        ['name'=>'Text to PDF','category'=>'PDF Tools','description'=>'Convert text content into a PDF.','url'=>'/tools/text-to-pdf/','icon'=>'PDF'],
        ['name'=>'QR Code Reader','category'=>'Generators','description'=>'Read QR codes from images.','url'=>'/tools/qr-reader/','icon'=>'▦'],
        ['name'=>'Random Number Generator','category'=>'Generators','description'=>'Generate random numbers in any range.','url'=>'/tools/random-number-generator/','icon'=>'123'],
        ['name'=>'UUID Generator','category'=>'Developer Tools','description'=>'Generate unique UUID identifiers.','url'=>'/tools/uuid-generator/','icon'=>'ID'],
        ['name'=>'Timestamp Converter','category'=>'Developer Tools','description'=>'Convert Unix timestamps into readable dates.','url'=>'/tools/timestamp-converter/','icon'=>'T'],
        ['name'=>'Unix Timestamp','category'=>'Developer Tools','description'=>'Work with Unix timestamps.','url'=>'/tools/unix-timestamp/','icon'=>'T'],
        ['name'=>'Lorem Ipsum Generator','category'=>'Generators','description'=>'Generate placeholder Lorem Ipsum text.','url'=>'/tools/lorem-ipsum-generator/','icon'=>'TXT'],
        ['name'=>'Age Calculator','category'=>'Calculators','description'=>'Calculate age from date of birth.','url'=>'/tools/age-calculator/','icon'=>'AGE'],
        ['name'=>'Percentage Calculator','category'=>'Calculators','description'=>'Calculate percentages quickly.','url'=>'/tools/percentage-calculator/','icon'=>'%'],
        ['name'=>'BMI Calculator','category'=>'Calculators','description'=>'Calculate Body Mass Index.','url'=>'/tools/bmi-calculator/','icon'=>'BMI'],
        ['name'=>'Unit Converter','category'=>'Calculators','description'=>'Convert common units quickly.','url'=>'/tools/unit-converter/','icon'=>'↔'],
        ['name'=>'Stopwatch / Timer','category'=>'Utilities','description'=>'Use an online stopwatch and timer.','url'=>'/tools/stopwatch-timer/','icon'=>'⏱'],
    ];

    return $tools;
}

function smarttoolz_slug(string $value): string
{
    $slug = preg_replace('/[^a-z0-9]+/i', '-', $value) ?? '';
    return trim(strtolower($slug), '-');
}

function smarttoolz_categories(): array
{
    $counts = [];
    foreach (smarttoolz_tools() as $tool) {
        $category = $tool['category'];
        $counts[$category] = ($counts[$category] ?? 0) + 1;
    }
    uksort($counts, static fn(string $a, string $b): int => strcasecmp($a, $b));
    return $counts;
}

function smarttoolz_tool_by_slug(string $slug): ?array
{
    foreach (smarttoolz_tools() as $tool) {
        if (smarttoolz_slug($tool['name']) === $slug || trim($tool['url'], '/') === 'tools/' . $slug) {
            return $tool;
        }
    }
    return null;
}

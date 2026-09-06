<?php
declare(strict_types=1);

function smarttoolz_slug(string $value): string
{
    $value = trim($value);
    $value = strtolower($value);
    $value = preg_replace('/[^a-z0-9]+/i', '-', $value) ?? '';
    return trim($value, '-');
}

function smarttoolz_tools(): array
{
    return [
        ['name'=>'Image Compressor','category'=>'Image Tools','description'=>'Compress JPG, PNG and WebP images in your browser without uploading them to a server.','url'=>'/tools/image-compressor/','icon'=>'IMG'],
        ['name'=>'Image Resizer','category'=>'Image Tools','description'=>'Resize images to exact dimensions for websites, social media and documents.','url'=>'/tools/image-resizer/','icon'=>'↔'],
        ['name'=>'JPG to PNG','category'=>'Image Tools','description'=>'Convert JPG images to PNG format quickly in your browser.','url'=>'/tools/jpg-to-png/','icon'=>'J→P'],
        ['name'=>'PNG to JPG','category'=>'Image Tools','description'=>'Convert PNG images to JPG with a simple browser-based workflow.','url'=>'/tools/png-to-jpg/','icon'=>'P→J'],
        ['name'=>'Word Counter','category'=>'Text Tools','description'=>'Count words, characters, sentences and paragraphs instantly.','url'=>'/tools/word-counter/','icon'=>'ABC'],
        ['name'=>'Case Converter','category'=>'Text Tools','description'=>'Convert text between uppercase, lowercase, sentence and title case.','url'=>'/tools/case-converter/','icon'=>'Aa'],
        ['name'=>'JSON Formatter','category'=>'Developer Tools','description'=>'Format, validate and beautify JSON for easier debugging and development.','url'=>'/tools/json-formatter/','icon'=>'{}'],
        ['name'=>'QR Code Generator','category'=>'Generators','description'=>'Create QR codes from URLs and text with no signup required.','url'=>'/tools/qr-generator/','icon'=>'QR'],
        ['name'=>'Password Generator','category'=>'Security','description'=>'Generate strong random passwords for accounts and development work.','url'=>'/tools/password-generator/','icon'=>'KEY'],
        ['name'=>'Age Calculator','category'=>'Calculators','description'=>'Calculate age in years, months and days from a date of birth.','url'=>'/tools/age-calculator/','icon'=>'AGE'],
        ['name'=>'Color Picker','category'=>'Design Tools','description'=>'Pick a color and copy HEX, RGB, HSL and CSS values.','url'=>'/tools/color-picker/','icon'=>'CLR'],
        ['name'=>'PDF to JPG','category'=>'PDF Tools','description'=>'Convert PDF pages into JPG images directly in your browser.','url'=>'/tools/pdf-to-jpg/','icon'=>'PDF'],
    ];
}

function smarttoolz_categories(): array
{
    $out=[];
    foreach(smarttoolz_tools() as $tool){
        $out[$tool['category']]=($out[$tool['category']]??0)+1;
    }
    ksort($out,SORT_NATURAL|SORT_FLAG_CASE);
    return $out;
}

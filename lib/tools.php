<?php
declare(strict_types=1);

function smarttoolz_slug(string $value): string
{
    $value = trim($value);
    $value = strtolower($value);
    $value = preg_replace('/[^a-z0-9]+/i', '-', $value) ?? '';
    return trim($value, '-');
}

function smarttoolz_tags_for(string $slug, string $category): array
{
    $map = [
        'image-compressor'=>['image compression','compress images','JPG','PNG','WebP','image optimization'],
        'image-resizer'=>['image resize','resize images','dimensions','social media','web images'],
        'jpg-to-png'=>['JPG','PNG','image conversion','format conversion'],
        'png-to-jpg'=>['PNG','JPG','image conversion','format conversion'],
        'word-counter'=>['word count','character count','text analysis','writing','content'],
        'case-converter'=>['case converter','uppercase','lowercase','text formatting'],
        'json-formatter'=>['JSON','JSON formatter','JSON validator','developer tools','debugging'],
        'qr-generator'=>['QR code','QR generator','URL QR','sharing','generators'],
        'password-generator'=>['password generator','strong passwords','random password','security'],
        'password-entropy-calculator'=>['password entropy','password security','entropy','security'],
        'password-generator-advanced'=>['password generator','custom passwords','strong passwords','security'],
        'password-hash-generator'=>['password hash','hash generator','SHA','password security'],
        'password-pattern-checker'=>['password patterns','password security','pattern detection','security'],
        'password-policy-checker'=>['password policy','password security','security rules','compliance'],
        'password-rule-audit'=>['password rules','password audit','password security','security'],
        'password-strength-checker'=>['password strength','password security','strong passwords','security'],
        'password-token-generator'=>['secure token','random token','token generator','security'],
        'age-calculator'=>['age calculator','date of birth','date calculation','calculator'],
        'color-picker'=>['color picker','HEX','RGB','HSL','CSS colors','design'],
        'pdf-to-jpg'=>['PDF','PDF to JPG','JPG','image conversion','PDF tools'],
    ];
    if (isset($map[$slug])) return $map[$slug];

    $tags = [];
    $keywords = [
        'base64'=>['Base64','encoding','decoding','developer tools'],
        'basic-auth'=>['Basic Auth','HTTP authentication','API','developer tools'],
        'auth'=>['authentication','security','API'],
        'password'=>['password','password security','security'],
        'jwt'=>['JWT','JSON Web Token','developer tools','security'],
        'hmac'=>['HMAC','hashing','cryptography','security'],
        'hash'=>['hashing','cryptography','security'],
        'url'=>['URL','encoding','decoding','developer tools'],
        'html'=>['HTML','web development','developer tools'],
        'css'=>['CSS','web development','developer tools'],
        'code'=>['code','developer tools','formatting'],
        'json'=>['JSON','developer tools'],
        'image'=>['image','image tools'],
        'jpg'=>['JPG','image conversion'],
        'png'=>['PNG','image conversion'],
        'webp'=>['WebP','image tools'],
        'pdf'=>['PDF','PDF tools'],
        'qr'=>['QR code','generators'],
        'color'=>['color','design tools'],
        'calculator'=>['calculator','calculations'],
        'converter'=>['converter','format conversion'],
        'generator'=>['generator','generators'],
        'checker'=>['checker','validation'],
        'validator'=>['validator','validation'],
        'counter'=>['counter','text analysis'],
        'minifier'=>['minification','performance','developer tools'],
    ];
    foreach ($keywords as $keyword => $values) {
        if (str_contains($slug, $keyword)) $tags = array_merge($tags, $values);
    }
    $tags[] = $category;
    $tags = array_values(array_unique(array_filter($tags)));
    return array_slice($tags, 0, 6);
}

/**
 * Tool metadata for polished cards. The filesystem is the source of truth:
 * every folder under /tools containing <folder>/<folder>.php is discovered
 * automatically. Add a new tool folder and it will appear on Home and All Tools.
 */
function smarttoolz_tool_metadata(): array
{
    return [
        'image-compressor'=>['name'=>'Image Compressor','category'=>'Image Tools','description'=>'Compress JPG, PNG and WebP images in your browser without uploading them to a server.','icon'=>'IMG'],
        'image-resizer'=>['name'=>'Image Resizer','category'=>'Image Tools','description'=>'Resize images to exact dimensions for websites, social media and documents.','icon'=>'↔'],
        'jpg-to-png'=>['name'=>'JPG to PNG','category'=>'Image Tools','description'=>'Convert JPG images to PNG format quickly in your browser.','icon'=>'J→P'],
        'png-to-jpg'=>['name'=>'PNG to JPG','category'=>'Image Tools','description'=>'Convert PNG images to JPG with a simple browser-based workflow.','icon'=>'P→J'],
        'word-counter'=>['name'=>'Word Counter','category'=>'Text Tools','description'=>'Count words, characters, sentences and paragraphs instantly.','icon'=>'ABC'],
        'case-converter'=>['name'=>'Case Converter','category'=>'Text Tools','description'=>'Convert text between uppercase, lowercase, sentence and title case.','icon'=>'Aa'],
        'json-formatter'=>['name'=>'JSON Formatter','category'=>'Developer Tools','description'=>'Format, validate and beautify JSON for easier debugging and development.','icon'=>'{}'],
        'qr-generator'=>['name'=>'QR Code Generator','category'=>'Generators','description'=>'Create QR codes from URLs and text with no signup required.','icon'=>'QR'],
        'password-generator'=>['name'=>'Password Generator','category'=>'Security','description'=>'Generate strong random passwords for accounts and development work.','icon'=>'KEY'],
        'password-entropy-calculator'=>['name'=>'Password Entropy Calculator','category'=>'Security','description'=>'Estimate password entropy from length and character variety.','icon'=>'KEY'],
        'password-generator-advanced'=>['name'=>'Password Generator Advanced','category'=>'Security','description'=>'Generate customizable strong passwords with advanced options.','icon'=>'KEY'],
        'password-hash-generator'=>['name'=>'Password Hash Generator','category'=>'Security','description'=>'Generate secure password hashes locally in your browser.','icon'=>'HASH'],
        'password-pattern-checker'=>['name'=>'Password Pattern Checker','category'=>'Security','description'=>'Check passwords for common patterns and predictable sequences.','icon'=>'CHK'],
        'password-policy-checker'=>['name'=>'Password Policy Checker','category'=>'Security','description'=>'Check a password against common strength and policy requirements.','icon'=>'POL'],
        'password-rule-audit'=>['name'=>'Password Rule Audit','category'=>'Security','description'=>'Audit password rules such as length, case, numbers and symbols.','icon'=>'AUD'],
        'password-strength-checker'=>['name'=>'Password Strength Checker','category'=>'Security','description'=>'Check password strength using common character and length rules.','icon'=>'KEY'],
        'password-token-generator'=>['name'=>'Password Token Generator','category'=>'Security','description'=>'Generate secure random tokens locally in your browser.','icon'=>'TOK'],
        'age-calculator'=>['name'=>'Age Calculator','category'=>'Calculators','description'=>'Calculate age in years, months and days from a date of birth.','icon'=>'AGE'],
        'color-picker'=>['name'=>'Color Picker','category'=>'Design Tools','description'=>'Pick a color and copy HEX, RGB, HSL and CSS values.','icon'=>'CLR'],
        'pdf-to-jpg'=>['name'=>'PDF to JPG','category'=>'PDF Tools','description'=>'Convert PDF pages into JPG images directly in your browser.','icon'=>'PDF'],
    ];
}

function smarttoolz_inferred_metadata(string $slug): array
{
    $label = ucwords(str_replace('-', ' ', $slug));
    $rules = [
        'image'=>['Image Tools','Use this free image utility directly in your browser.','IMG'],
        'jpg'=>['Image Tools','Convert and work with JPG images directly in your browser.','JPG'],
        'png'=>['Image Tools','Convert and work with PNG images directly in your browser.','PNG'],
        'webp'=>['Image Tools','Work with WebP images directly in your browser.','WEB'],
        'pdf'=>['PDF Tools','Work with PDF files using a simple browser-based workflow.','PDF'],
        'word'=>['Text Tools','Work with text quickly using this free browser-based utility.','TXT'],
        'text'=>['Text Tools','Transform and process text instantly in your browser.','TXT'],
        'case'=>['Text Tools','Transform text instantly with a simple browser-based utility.','Aa'],
        'json'=>['Developer Tools','Format, validate and work with structured JSON data.','{}'],
        'code'=>['Developer Tools','Use this developer utility directly in your browser.','</>'],
        'url'=>['Developer Tools','Process URLs quickly with this free browser-based utility.','URL'],
        'base64'=>['Developer Tools','Encode or decode data instantly in your browser.','64'],
        'password'=>['Security','Generate and work with secure data in your browser.','KEY'],
        'qr'=>['Generators','Generate useful output instantly with this free browser tool.','QR'],
        'generator'=>['Generators','Generate useful output instantly with this free browser tool.','GEN'],
        'calculator'=>['Calculators','Calculate everyday values instantly in your browser.','CAL'],
        'color'=>['Design Tools','Work with colors and design values directly in your browser.','CLR'],
    ];
    foreach ($rules as $keyword=>$meta) {
        if (str_contains($slug, $keyword)) {
            return ['name'=>$label,'category'=>$meta[0],'description'=>$meta[1],'icon'=>$meta[2]];
        }
    }
    return ['name'=>$label,'category'=>'Other Tools','description'=>'A free, simple browser-based utility for everyday digital tasks.','icon'=>'TOOL'];
}

function smarttoolz_tools(): array
{
    $metadata = smarttoolz_tool_metadata();
    $toolsDir = dirname(__DIR__) . '/tools';
    $tools = [];

    if (is_dir($toolsDir)) {
        $folders = scandir($toolsDir) ?: [];
        foreach ($folders as $folder) {
            if ($folder === '.' || $folder === '..' || !preg_match('/^[a-z0-9-]+$/', $folder)) continue;
            $file = $toolsDir . '/' . $folder . '/' . $folder . '.php';
            if (!is_file($file)) continue;
            $meta = $metadata[$folder] ?? smarttoolz_inferred_metadata($folder);
            $meta['tags'] = smarttoolz_tags_for($folder, (string)$meta['category']);
            $tools[] = $meta + ['url'=>'/tools/' . $folder . '/','slug'=>$folder];
        }
    }

    usort($tools, static fn(array $a, array $b): int => strnatcasecmp($a['name'], $b['name']));
    return $tools;
}

function smarttoolz_categories(): array
{
    $out=[];
    foreach(smarttoolz_tools() as $tool){ $out[$tool['category']]=($out[$tool['category']]??0)+1; }
    ksort($out,SORT_NATURAL|SORT_FLAG_CASE);
    return $out;
}

function smarttoolz_tag_list(): array
{
    $out=[];
    foreach (smarttoolz_tools() as $tool) {
        foreach (($tool['tags'] ?? []) as $tag) $out[$tag]=($out[$tag]??0)+1;
    }
    uksort($out, static fn(string $a,string $b): int => strnatcasecmp($a,$b));
    return $out;
}

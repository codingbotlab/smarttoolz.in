<?php
declare(strict_types=1);
define('SMARTTOOLZ_HOME_REGISTRY', true);
require_once __DIR__ . '/../smart-toolz/tool.php';
$slug=trim((string)($_GET['tool']??''));$tool=null;foreach($tools as $t){$p=trim((string)parse_url($t['url'],PHP_URL_PATH),'/');if($slug!==''&&$slug===basename($p,'.php')){$tool=$t;break;}}
if(!$tool){http_response_code(404);exit;}
$name=(string)$tool['name'];$h=static fn(string $v):string=>htmlspecialchars($v,ENT_QUOTES,'UTF-8');
$visuals=[
'image-compressor'=>['Drop your image here','Compression Quality 80%','Compress Image','Original Image  →  Compressed Image','Download Compressed Image'],
'image-background-remover'=>['Upload image','Processing / remove background','Check subject edges','Transparent / background option','Download result'],
'image-resizer'=>['Upload image','Width × Height','Keep aspect ratio','Resize image','Download resized image'],
'jpg-to-png'=>['Choose JPG image','Source preview','Convert JPG → PNG','Check PNG result','Download PNG'],
'png-to-jpg'=>['Choose PNG image','Output options','Convert PNG → JPG','Check JPG result','Download JPG'],
'word-counter'=>['Paste your text','Text editor','Live counters','Words / Characters / Lines','Copy text'],
'case-converter'=>['Paste text','Choose case','Convert text','Review output','Copy result'],
'qr-generator'=>['Enter URL or text','QR preview','Choose size/style','Generate QR','Download QR'],
'password-generator'=>['Password length','Character options','Generate password','Review strength/length','Copy password'],
'json-formatter'=>['Paste JSON','Format / Beautify','Validate errors','Review formatted JSON','Copy JSON'],
'url-encoder'=>['Enter URL component','URL Encode','Encoded characters','Review result','Copy encoded value'],
'pdf-to-jpg'=>['Upload PDF','Page/output options','Convert PDF → JPG','Preview pages','Download JPG'],
'remove-duplicate-lines'=>['Paste multiline text','Remove duplicates','Review unique lines','Compare with source','Copy cleaned text'],
'image-cropper'=>['Upload image','Select crop area','Adjust boundaries','Apply crop','Download cropped image'],
'image-rotator'=>['Upload image','Choose rotation','Apply rotation','Check orientation','Download result'],
'image-flipper'=>['Upload image','Horizontal / Vertical','Apply flip','Check mirrored content','Download result'],
'webp-to-jpg'=>['Upload WebP','Load preview','Convert WebP → JPG','Check result','Download JPG'],
'jpg-to-webp'=>['Upload JPG','WebP quality','Convert JPG → WebP','Compare result','Download WebP'],
'png-to-webp'=>['Upload PNG','WebP quality','Convert PNG → WebP','Check transparency','Download WebP'],
'gif-to-jpg'=>['Upload GIF','Convert to static frame','Check selected frame','Inspect JPG','Download JPG'],
'gif-maker'=>['Add image frames','Arrange order','Frame timing','Generate / preview GIF','Download GIF'],
'meme-generator'=>['Upload image','Enter meme text','Position / style','Preview meme','Export / download'],
'color-picker'=>['Choose a color','Color preview','HEX value','RGB value','Copy color value'],
'color-converter'=>['Enter color value','Select HEX / RGB / HSL','Convert','Check equivalent values','Copy format'],
'base64-encoder'=>['Paste text/data','Encode','Base64 output','Review string','Copy Base64'],
'base64-decoder'=>['Paste Base64','Decode','Decoded output','Check text/data','Copy result'],
'url-decoder'=>['Paste encoded URL','URL Decode','Decoded characters','Review result','Copy decoded value'],
'html-encoder'=>['Paste text','HTML Encode','Entity output','Review escaped text','Copy result'],
'html-decoder'=>['Paste entities','HTML Decode','Readable output','Review result','Copy text'],
'markdown-to-html'=>['Paste Markdown','Convert','HTML output','Review headings/links','Copy HTML'],
'text-to-slug'=>['Enter title','Generate slug','Check separators','Review URL text','Copy slug'],
'remove-extra-spaces'=>['Paste text','Remove extra spaces','Review line breaks','Check formatting','Copy cleaned text'],
'sort-lines'=>['Paste one item per line','Choose sort mode','Sort lines','Check first/last items','Copy sorted lines'],
'reverse-text'=>['Paste text','Reverse Text','Inspect output','Confirm character order','Copy result'],
'pdf-merger'=>['Select PDFs','Arrange order','Merge PDFs','Check page order','Download merged PDF'],
'pdf-splitter'=>['Upload PDF','Select pages/ranges','Split PDF','Check generated files','Download pages'],
'pdf-compressor'=>['Upload PDF','Choose compression','Compress PDF','Check size/readability','Download PDF'],
'pdf-to-png'=>['Upload PDF','Choose pages/options','Convert PDF → PNG','Preview pages','Download PNG'],
'png-to-pdf'=>['Select PNG images','Arrange order','Page/orientation options','Create PDF','Download PDF'],
'text-to-pdf'=>['Enter text','Page/PDF options','Generate PDF','Check page breaks','Download PDF'],
'qr-reader'=>['Upload QR image','Scan / decode','Decoded content','Check URL/domain','Copy result'],
'random-number-generator'=>['Minimum','Maximum','Count/options','Generate','Review random numbers'],
'uuid-generator'=>['UUID options','Generate','New UUID','Review identifier','Copy UUID'],
'timestamp-converter'=>['Enter timestamp','Seconds / milliseconds','Convert','Check timezone','Copy date/time'],
'unix-timestamp'=>['Enter date/time or timestamp','Choose direction','Convert','Check unit/timezone','Copy result'],
'lorem-ipsum-generator'=>['Choose amount','Words / paragraphs','Generate','Review length','Copy placeholder text'],
'age-calculator'=>['Date of birth','Comparison date','Calculate','Years / months / days','Review age'],
'percentage-calculator'=>['Read input labels','Enter values','Calculate','Check what % represents','Copy result'],
'bmi-calculator'=>['Enter weight','Enter height + units','Calculate BMI','Review BMI/category','Record result'],
'unit-converter'=>['Enter value','From unit','To unit','Convert','Check result + unit'],
'stopwatch-timer'=>['Stopwatch / Timer','Set duration','Start','Pause / Reset','Read elapsed/countdown time']
];
$labels=$visuals[$slug]??[$name,'Enter required input','Run main action','Review result','Download / copy output'];
header('Content-Type: image/svg+xml; charset=UTF-8');
$W=1200;$H=640;
?><svg xmlns="http://www.w3.org/2000/svg" width="<?=$W?>" height="<?=$H?>" viewBox="0 0 <?=$W?> <?=$H?>"><rect width="1200" height="640" rx="28" fill="#f6f8fc"/><rect x="40" y="35" width="1120" height="570" rx="24" fill="#fff" stroke="#e1e6ef"/><rect x="40" y="35" width="1120" height="78" rx="24" fill="#172033"/><circle cx="78" cy="74" r="10" fill="#635bff"/><text x="102" y="82" font-family="Arial,sans-serif" font-size="25" font-weight="700" fill="#fff">SmartToolz · <?=$h($name)?></text><text x="1020" y="82" font-family="Arial,sans-serif" font-size="14" fill="#c0c8d6">HOW-TO VISUAL</text><?php for($i=0;$i<5;$i++):$x=70+$i*215;$y=145;$num=$i+1;$txt=$labels[$i];$words=preg_split('/\s+/',$txt);$line1='';$line2='';foreach($words as $w){if(strlen($line1)+strlen($w)+1<23)$line1.=($line1?' ':'').$w;else $line2.=($line2?' ':'').$w;}?><rect x="<?=$x?>" y="<?=$y?>" width="190" height="350" rx="18" fill="#fafbff" stroke="#e1e6ef"/><circle cx="<?=$x+30?>" cy="<?=$y+30?>" r="19" fill="#eeedff"/><text x="<?=$x+30?>" y="<?=$y+37?>" text-anchor="middle" font-family="Arial,sans-serif" font-size="15" font-weight="700" fill="#635bff"><?=$num?></text><text x="<?=$x+20?>" y="<?=$y+90?>" font-family="Arial,sans-serif" font-size="16" font-weight="700" fill="#172033"><?=$h($line1)?></text><?php if($line2!==''):?><text x="<?=$x+20?>" y="<?=$y+115?>" font-family="Arial,sans-serif" font-size="16" font-weight="700" fill="#172033"><?=$h($line2)?></text><?php endif;?><rect x="<?=$x+20?>" y="<?=$y+145?>" width="150" height="100" rx="12" fill="#f0efff"/><circle cx="<?=$x+95?>" cy="<?=$y+177?>" r="18" fill="#635bff" opacity=".16"/><rect x="<?=$x+50?>" y="<?=$y+210?>" width="90" height="9" rx="5" fill="#d7dbea"/><rect x="<?=$x+40?>" y="<?=$y+270?>" width="110" height="38" rx="10" fill="#635bff"/><text x="<?=$x+95?>" y="<?=$y+294?>" text-anchor="middle" font-family="Arial,sans-serif" font-size="11" font-weight="700" fill="#fff"><?=['INPUT','OPTION','ACTION','CHECK','SAVE'][$i]?></text><text x="<?=$x+95?>" y="<?=$y+330?>" text-anchor="middle" font-family="Arial,sans-serif" font-size="10" fill="#7a8494">SmartToolz guide</text><?php endfor;?><text x="70" y="560" font-family="Arial,sans-serif" font-size="13" fill="#697487">Visual walkthrough: follow the real task flow from input → settings → action → result → output.</text></svg>
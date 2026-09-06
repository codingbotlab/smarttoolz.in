<?php
declare(strict_types=1);
ini_set('display_errors','0');
error_reporting(0);

/* Keep this tool independent of Creator AI/database configuration. */
$tracker = $_SERVER['DOCUMENT_ROOT'].'/analytics/tracker.php';
if (is_file($tracker)) { require_once $tracker; }

function colorPickerAd(string $key): void {
    try {
        if (function_exists('db')) {
            $pdo = db();
            $stmt = $pdo->prepare('SELECT enabled, ad_code FROM ads_settings WHERE ad_key = ? LIMIT 1');
            $stmt->execute([$key]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row && (int)$row['enabled'] === 1) {
                $code = trim((string)($row['ad_code'] ?? ''));
                if ($code !== '') echo $code;
            }
        }
    } catch (Throwable $e) {}
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Color Picker Online - Pick HEX, RGB & HSL Colors | Smart-Tooz</title>
<meta name="description" content="Free online color picker. Pick a color and instantly get HEX, RGB, HSL and HSV values.">
<meta name="robots" content="index,follow">
<style>
*{box-sizing:border-box;margin:0;padding:0}body{font-family:Inter,-apple-system,BlinkMacSystemFont,"Segoe UI",Arial,sans-serif;background:#f6f8fc;color:#172033;line-height:1.5}a{text-decoration:none;color:inherit}button,input{font:inherit}.site-header{position:sticky;top:0;z-index:1000;background:rgba(255,255,255,.96);backdrop-filter:blur(14px);border-bottom:1px solid #e5e9f0}.navbar{width:calc(100% - 20px);max-width:1400px;min-height:72px;margin:auto;display:flex;align-items:center;justify-content:space-between}.logo{display:flex;align-items:center;gap:10px;font-size:21px;font-weight:800}.logo-icon{width:42px;height:42px;display:grid;place-items:center;border-radius:13px;color:#fff;background:linear-gradient(135deg,#635bff,#916cff)}.nav-links{display:flex;gap:28px}.nav-links a{color:#596477;font-size:14px;font-weight:600}.page-layout{width:min(1400px,calc(100% - 30px));margin:28px auto 60px;display:flex;align-items:flex-start;gap:24px}.tool-content{min-width:0;flex:1}.ad-slot{width:100%;min-height:10px;margin:0 auto 24px;padding:5px;display:flex;align-items:center;justify-content:center;text-align:center;overflow:hidden}.desktop-ad{display:flex}.mobile-ad{display:none}.tool-header{margin-bottom:22px;padding:34px 25px;background:#fff;border:1px solid #e5e9f0;border-radius:22px;text-align:center;box-shadow:0 8px 30px rgba(30,35,80,.035)}.badge{display:inline-block;margin-bottom:12px;padding:7px 13px;border-radius:50px;background:#eeedff;color:#635bff;font-size:12px;font-weight:800}.tool-header h1{font-size:clamp(32px,5vw,48px);line-height:1.08;letter-spacing:-1.8px}.tool-header h1 span{color:#635bff}.tool-header p{max-width:680px;margin:13px auto 0;color:#707b8e;font-size:14px}.picker-card{padding:28px;background:#fff;border:1px solid #e5e9f0;border-radius:22px;box-shadow:0 15px 45px rgba(30,35,80,.06)}.picker-grid{display:grid;grid-template-columns:300px 1fr;gap:28px;align-items:center}.color-preview{width:100%;height:300px;border-radius:20px;border:1px solid #e1e5ed;background:#635bff}.native-picker{margin-top:15px}.native-picker input{width:100%;height:48px;padding:3px;border:1px solid #d7dbea;border-radius:12px;background:#fff;cursor:pointer}.values{display:grid;grid-template-columns:repeat(2,1fr);gap:12px}.value-box{padding:16px;background:#fafbff;border:1px solid #e5e9f0;border-radius:14px}.value-box label{display:block;margin-bottom:7px;color:#707b8e;font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.5px}.value-row{display:flex;align-items:center;gap:8px}.value-row input{width:100%;height:40px;padding:8px 10px;border:1px solid #d7dbea;border-radius:9px;background:#fff;color:#172033;font-weight:700;outline:none}.copy{height:40px;padding:0 12px;border:0;border-radius:9px;background:#eef0f5;color:#4d5768;font-size:11px;font-weight:800;cursor:pointer}.copy:hover{background:#e5e7ed;color:#635bff}.hex-large{grid-column:1/-1}.hex-large input{font-size:18px;color:#635bff}.actions{display:flex;justify-content:center;gap:10px;flex-wrap:wrap;margin-top:20px}.btn{min-height:44px;padding:11px 18px;border:0;border-radius:12px;cursor:pointer;font-size:13px;font-weight:800}.primary{background:#635bff;color:#fff}.secondary{background:#eef0f5;color:#3f4858}.info{margin-top:24px;padding:25px;background:#fff;border:1px solid #e5e9f0;border-radius:19px}.info h2{font-size:21px;margin-bottom:9px}.info h3{font-size:16px;margin:18px 0 7px}.info p,.info li{color:#707b8e;font-size:13px;line-height:1.7}.info ul{padding-left:20px}footer{padding:35px 20px;background:#151827;color:#fff;text-align:center}footer p{margin-top:6px;color:#aeb5c5;font-size:12px}@media(max-width:850px){.picker-grid{grid-template-columns:1fr}.color-preview{max-width:360px;height:260px;margin:auto}.native-picker{max-width:360px;margin:15px auto 0}}@media(max-width:700px){.navbar{min-height:64px}.nav-links{display:none}.page-layout{width:calc(100% - 16px);margin-top:16px;flex-direction:column;gap:18px}.tool-content,.tools-sidebar{width:100%}.desktop-ad{display:none}.mobile-ad{display:flex}.tool-header{padding:27px 17px}.picker-card{padding:15px}.values{grid-template-columns:1fr}.hex-large{grid-column:auto}}
</style></head>
<body>
<?php require_once dirname(__DIR__) . '/header.php'; ?>

<div class="page-layout"><main class="tool-content">
<div class="desktop-ad ad-slot"><?php colorPickerAd('top_desktop'); ?></div><div class="mobile-ad ad-slot"><?php colorPickerAd('top_mobile'); ?></div>
<section class="tool-header"><span class="badge">DESIGN TOOL</span><h1>Color <span>Picker</span></h1><p>Pick any color and instantly get HEX, RGB, HSL and HSV values.</p></section>
<section class="picker-card"><div class="picker-grid"><div><div class="color-preview" id="preview"></div><div class="native-picker"><input id="picker" type="color" value="#635BFF" aria-label="Choose a color"></div></div><div class="values"><div class="value-box hex-large"><label>HEX</label><div class="value-row"><input id="hex" readonly><button class="copy" data-copy="hex">COPY</button></div></div><div class="value-box"><label>RGB</label><div class="value-row"><input id="rgb" readonly><button class="copy" data-copy="rgb">COPY</button></div></div><div class="value-box"><label>HSL</label><div class="value-row"><input id="hsl" readonly><button class="copy" data-copy="hsl">COPY</button></div></div><div class="value-box"><label>HSV</label><div class="value-row"><input id="hsv" readonly><button class="copy" data-copy="hsv">COPY</button></div></div><div class="value-box"><label>CSS</label><div class="value-row"><input id="css" readonly><button class="copy" data-copy="css">COPY</button></div></div></div></div><div class="actions"><button class="btn primary" id="random" type="button">🎲 Random Color</button><button class="btn secondary" id="reset" type="button">Reset</button></div></section>
<section class="info"><h2>Free Online Color Picker</h2><p>Choose a color and instantly convert it into common web and design formats. Everything happens in your browser.</p><h3>How to use</h3><ul><li>Choose a color using the color selector.</li><li>Copy the HEX, RGB, HSL or HSV value.</li><li>Use Random Color to generate a new color.</li></ul></section>
<div class="desktop-ad ad-slot"><?php colorPickerAd('bottom_desktop'); ?></div><div class="mobile-ad ad-slot"><?php colorPickerAd('bottom_mobile'); ?></div>
</main><?php require_once __DIR__.'/tool-sidebar.php'; ?></div>
<footer><strong>Smart-Tooz</strong><p>Free online tools for everyday tasks.</p></footer>
<script>
const picker=document.getElementById('picker'),preview=document.getElementById('preview');
function hexRgb(h){h=h.replace('#','');if(h.length===3)h=h.split('').map(x=>x+x).join('');return[parseInt(h.slice(0,2),16),parseInt(h.slice(2,4),16),parseInt(h.slice(4,6),16)]}
function hsl(r,g,b){r/=255;g/=255;b/=255;const mx=Math.max(r,g,b),mn=Math.min(r,g,b),d=mx-mn;let h=0,s=0,l=(mx+mn)/2;if(d){s=d/(1-Math.abs(2*l-1));if(mx===r)h=((g-b)/d)%6;else if(mx===g)h=(b-r)/d+2;else h=(r-g)/d+4;h*=60;if(h<0)h+=360}return[Math.round(h),Math.round(s*100),Math.round(l*100)]}
function hsv(r,g,b){r/=255;g/=255;b/=255;const mx=Math.max(r,g,b),mn=Math.min(r,g,b),d=mx-mn;let h=0;if(d){if(mx===r)h=((g-b)/d)%6;else if(mx===g)h=(b-r)/d+2;else h=(r-g)/d+4;h*=60;if(h<0)h+=360}return[Math.round(h),Math.round(mx?d/mx*100:0),Math.round(mx*100)]}
function update(hex){const[r,g,b]=hexRgb(hex),a=hsl(r,g,b),v=hsv(r,g,b),up=hex.toUpperCase();preview.style.background=up;picker.value=up;document.getElementById('hex').value=up;document.getElementById('rgb').value=`rgb(${r}, ${g}, ${b})`;document.getElementById('hsl').value=`hsl(${a[0]}, ${a[1]}%, ${a[2]}%)`;document.getElementById('hsv').value=`hsv(${v[0]}, ${v[1]}%, ${v[2]}%)`;document.getElementById('css').value=`color: ${up};`}
picker.addEventListener('input',e=>update(e.target.value));document.getElementById('random').onclick=()=>update('#'+Math.floor(Math.random()*16777216).toString(16).padStart(6,'0'));document.getElementById('reset').onclick=()=>update('#635BFF');document.querySelectorAll('.copy').forEach(b=>b.onclick=async()=>{const el=document.getElementById(b.dataset.copy);try{await navigator.clipboard.writeText(el.value)}catch(e){el.select();document.execCommand('copy')}b.textContent='COPIED';setTimeout(()=>b.textContent='COPY',900)});update('#635BFF');
</script>

<?php require_once dirname(__DIR__) . '/footer.php'; ?>
</body></html>
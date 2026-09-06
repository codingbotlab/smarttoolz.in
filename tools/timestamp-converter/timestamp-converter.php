<?php
declare(strict_types=1);
if(session_status()!==PHP_SESSION_ACTIVE)session_start();
?><!doctype html><html lang="en"><head>
<?php require_once dirname(__DIR__) . '/head.php'; ?>



<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Timestamp Converter — SmartToolz</title><style>*{box-sizing:border-box}body{margin:0;background:#f6f8fc;color:#172033;font-family:Inter,system-ui,Arial,sans-serif}.page{width:min(1200px,calc(100% - 28px));margin:32px auto 70px}.hero,.tool,.tools{background:#fff;border:1px solid #e5e9f0;border-radius:20px;box-shadow:0 14px 40px rgba(20,30,70,.05)}.hero{padding:28px;margin-bottom:18px}.hero h1{font-size:clamp(30px,5vw,44px);margin:0 0 8px}.muted{color:#707b8e;line-height:1.6}.layout{display:grid;grid-template-columns:minmax(0,1fr) 290px;gap:20px;align-items:start}.tool{padding:25px}.tools{position:sticky;top:88px;max-height:calc(100vh - 108px);overflow:auto;padding:16px}.tools h3{margin:2px 8px 12px}.tools a{display:block;padding:9px 10px;margin:3px 0;border-radius:9px;text-decoration:none;color:#536075;font-size:13px;font-weight:700}.tools a:hover,.tools a.active{background:#f0efff;color:#635bff}.input{width:100%;padding:13px;border:1px solid #dfe4ec;border-radius:11px;font:inherit;margin:6px 0 12px}.btn{border:0;border-radius:11px;padding:12px 18px;background:#635bff;color:#fff;font-weight:800;cursor:pointer}.out{margin-top:15px;padding:16px;border:1px solid #e5e9f0;border-radius:12px;background:#f7f8fb;word-break:break-word}.row{display:grid;grid-template-columns:1fr 1fr;gap:16px}@media(max-width:800px){.layout,.row{grid-template-columns:1fr}.tools{position:static;max-height:330px}.hero,.tool{padding:21px}}</style></head><body>
<?php require_once dirname(__DIR__) . '/header.php'; ?>






<main class="page"><section class="hero"><h1>Timestamp Converter</h1><p class="muted">Convert Unix timestamps and dates in both directions.</p></section><div class="layout"><section class="tool"><div class="row"><div><h3>Unix Timestamp → Date</h3><input class="input" id="unix" type="number" placeholder="e.g. 1756656000"><button class="btn" onclick="unixToDate()">Convert</button><div class="out" id="uout"></div></div><div><h3>Date → Unix Timestamp</h3><input class="input" id="date" type="datetime-local"><button class="btn" onclick="dateToUnix()">Convert</button><div class="out" id="dout"></div></div></div><hr><h3>Current Timestamp</h3><div class="out" id="current"></div></section><aside class="tools"><h3>🛠️ All Tools</h3><?php $items=['image-compressor.php'=>'🖼️ Image Compressor','png-to-jpg.php'=>'↩️ PNG to JPG','jpg-to-webp.php'=>'🖼️ JPG to WebP','png-to-webp.php'=>'🖼️ PNG to WebP','gif-maker.php'=>'🎞️ GIF Maker','pdf-to-jpg.php'=>'📄 PDF to JPG','pdf-to-png.php'=>'📄 PDF to PNG','pdf-merger.php'=>'📑 PDF Merger','pdf-splitter.php'=>'📄 PDF Splitter','pdf-compressor.php'=>'📦 PDF Compressor','qr-generator.php'=>'🔐 QR Code Generator','age-calculator.php'=>'🎂 Age Calculator','percentage-calculator.php'=>'% Percentage Calculator','bmi-calculator.php'=>'⚖️ BMI Calculator','unit-converter.php'=>'📏 Unit Converter','stopwatch-timer.php'=>'⏱️ Stopwatch Timer','lorem-ipsum-generator.php'=>'📄 Lorem Ipsum','unix-timestamp.php'=>'🕐 Unix Timestamp','timestamp-converter.php'=>'🕐 Timestamp Converter','json-formatter.php'=>'{} JSON Formatter','word-counter.php'=>'📝 Word Counter','case-converter.php'=>'🔤 Case Converter','url-encoder.php'=>'🔗 URL Encoder'];foreach($items as $f=>$n):?><a class="<?=basename($_SERVER['SCRIPT_NAME'])===$f?'active':''?>" href="/tools/<?=htmlspecialchars($f,ENT_QUOTES,'UTF-8')?>"><?=htmlspecialchars($n)?></a><?php endforeach;?><a href="/tool.php">View All Tools →</a></aside></div></main>
<script>function unixToDate(){const n=Number(document.getElementById('unix').value),o=document.getElementById('uout');o.textContent=Number.isFinite(n)?new Date(n*1000).toLocaleString():'Enter a valid Unix timestamp.'}function dateToUnix(){const v=document.getElementById('date').value,o=document.getElementById('dout');o.textContent=v?Math.floor(new Date(v).getTime()/1000):'Choose a date and time.'}function tick(){document.getElementById('current').textContent=Math.floor(Date.now()/1000)+' ('+new Date().toLocaleString()+')'}tick();setInterval(tick,1000)</script>



<?php require_once dirname(__DIR__) . '/footer.php'; ?>
</body></html>
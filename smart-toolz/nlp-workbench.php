<?php
// SmartToolz NLP Workbench backend + UI
// This file intentionally contains the first real local executor: image batch processing.
declare(strict_types=1);

$base = __DIR__ . '/nlp-workbench-data';
if (!is_dir($base)) @mkdir($base, 0755, true);

function json_out(array $x): never { header('Content-Type: application/json; charset=utf-8'); echo json_encode($x, JSON_UNESCAPED_UNICODE); exit; }
function clean_name(string $name): string { $name = pathinfo($name, PATHINFO_FILENAME); $name = preg_replace('/[^a-z0-9]+/i','-', strtolower($name)); return trim($name,'-') ?: 'file'; }
function parse_request(string $s): array {
  $s0=$s; $s=strtolower($s); $ops=[];
  $add=function(string $id,string $label,array $p=[])use(&$ops){if(!isset($ops[$id]))$ops[$id]=['id'=>$id,'label'=>$label,'params'=>$p];else $ops[$id]['params']=array_merge($ops[$id]['params'],$p);};
  if(preg_match('/(?:resize|width|dimension).{0,30}?(\d{2,5})\s*(?:px|pixel)/',$s,$m))$add('resize','Resize images',['width'=>(int)$m[1]]);
  elseif(preg_match('/(?:to|at)\s*(\d{2,5})\s*(?:px|pixel)/',$s,$m))$add('resize','Resize images',['width'=>(int)$m[1]]);
  if(str_contains($s,'compress')||str_contains($s,'smaller')||str_contains($s,'optimize'))$add('compress','Compress images',['quality'=>82]);
  if(str_contains($s,'webp'))$add('webp','Convert to WebP');
  elseif(str_contains($s,'jpg')||str_contains($s,'jpeg'))$add('jpg','Convert to JPG');
  if(str_contains($s,'rename')||str_contains($s,'seo'))$add('rename','SEO-friendly rename');
  if(str_contains($s,'zip')||str_contains($s,'archive'))$add('zip','Create ZIP');
  if(!$ops)$add('auto','Analyze and process');
  return ['text'=>$s0,'operations'=>array_values($ops),'confidence'=>min(99,78+count($ops)*5)];
}
function img_load(string $path,string $mime){ return match($mime){'image/jpeg'=>@imagecreatefromjpeg($path),'image/png'=>@imagecreatefrompng($path),'image/gif'=>@imagecreatefromgif($path),'image/webp'=>function_exists('imagecreatefromwebp')?@imagecreatefromwebp($path):false,default=>false}; }
function img_save($im,string $path,string $ext,int $q=82): bool { return match($ext){'webp'=>function_exists('imagewebp')?imagewebp($im,$path,$q):false,'jpg'=>imagejpeg($im,$path,$q),'png'=>imagepng($im,$path,6),default=>false}; }

if($_SERVER['REQUEST_METHOD']==='POST' && isset($_FILES['files'])){
  $request=(string)($_POST['request']??''); $plan=parse_request($request); $job=bin2hex(random_bytes(8)); $dir=$base.'/'.$job; @mkdir($dir,0755,true); $outDir=$dir.'/out'; @mkdir($outDir,0755,true); $done=[]; $errors=[];
  $names=$_FILES['files']['name']??[]; $tmp=$_FILES['files']['tmp_name']??[]; $types=$_FILES['files']['type']??[];
  foreach($tmp as $i=>$source){
    if(!is_uploaded_file($source))continue; $orig=(string)($names[$i]??('file-'.$i)); $mime=(string)($types[$i]??'');
    if(!str_starts_with($mime,'image/')){$errors[]=$orig.': only image files are enabled in this first executor.';continue;}
    $im=img_load($source,$mime); if(!$im){$errors[]=$orig.': unsupported image or missing PHP GD support.';continue;}
    $w=imagesx($im);$h=imagesy($im);$ext='jpg';$quality=82;
    foreach($plan['operations'] as $op){
      if($op['id']==='resize' && !empty($op['params']['width']) && $w>(int)$op['params']['width']){$nw=(int)$op['params']['width'];$nh=max(1,(int)round($h*$nw/$w));$n=imagecreatetruecolor($nw,$nh);imagealphablending($n,false);imagesavealpha($n,true);imagecopyresampled($n,$im,0,0,0,0,$nw,$nh,$w,$h);imagedestroy($im);$im=$n;$w=$nw;$h=$nh;}
      if($op['id']==='compress')$quality=(int)($op['params']['quality']??82);
      if($op['id']==='webp')$ext='webp'; if($op['id']==='jpg')$ext='jpg';
    }
    $stem=clean_name($orig); if(in_array('rename',array_column($plan['operations'],'id'),true))$stem=$stem.'-smarttoolz';
    $target=$outDir.'/'.$stem.'.'.$ext; $n=2; while(file_exists($target)){$target=$outDir.'/'.$stem.'-'.$n.'.'.$ext;$n++;}
    if(img_save($im,$target,$ext,$quality))$done[]=['name'=>basename($target),'url'=>'nlp-workbench-data/'.$job.'/out/'.rawurlencode(basename($target)),'bytes'=>filesize($target)]; else $errors[]=$orig.': conversion failed.'; imagedestroy($im);
  }
  $zipUrl=null;
  foreach($plan['operations'] as $op)if($op['id']==='zip'&&$done){$zipPath=$dir.'/SmartToolz-output-'.$job.'.zip';$z=new ZipArchive();if($z->open($zipPath,ZipArchive::CREATE|ZipArchive::OVERWRITE)===true){foreach($done as $f)$z->addFile($outDir.'/'.$f['name'],$f['name']);$z->close();$zipUrl='nlp-workbench-data/'.$job.'/SmartToolz-output-'.$job.'.zip';}}
  json_out(['ok'=>true,'job'=>$job,'plan'=>$plan,'files'=>$done,'zip'=>$zipUrl,'errors'=>$errors]);
}
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>SmartToolz — Real NLP Workbench</title><style>*{box-sizing:border-box}body{margin:0;font-family:Inter,system-ui,Arial;background:#f6f7fb;color:#111827}.wrap{max-width:1120px;margin:32px auto 70px;padding:0 16px}.hero{text-align:center;padding:28px 10px}.badge{display:inline-block;padding:8px 12px;border-radius:99px;background:#eceaff;color:#5b4de2;font-size:11px;font-weight:900}.hero h1{font-size:clamp(35px,6vw,64px);letter-spacing:-3px;margin:15px 0}.hero h1 span{color:#6d5dfc}.hero p{color:#687386;max-width:760px;margin:auto;line-height:1.7}.card{background:#fff;border:1px solid #e3e6ef;border-radius:24px;padding:22px;box-shadow:0 20px 60px rgba(30,35,70,.08)}textarea{width:100%;min-height:125px;padding:16px;border:1px solid #dfe3ec;border-radius:15px;font:15px/1.6 inherit;resize:vertical}input[type=file]{width:100%;padding:16px;border:2px dashed #d9dce7;border-radius:15px;background:#fafbff;margin:12px 0}.btn{width:100%;border:0;border-radius:14px;padding:15px;background:linear-gradient(135deg,#6556ef,#927cff);color:white;font-weight:900;cursor:pointer}.grid{display:grid;grid-template-columns:.8fr 1.2fr;gap:14px;margin-top:18px}.panel{background:#fafbff;border:1px solid #e5e7ef;border-radius:17px;padding:17px}.pill{display:inline-block;padding:7px 10px;border-radius:99px;background:#eceaff;color:#5d4fe2;font-size:11px;font-weight:900}.step{display:flex;gap:10px;align-items:center;background:#fff;border:1px solid #e6e8ef;border-radius:12px;padding:11px;margin-top:8px}.num{width:28px;height:28px;border-radius:9px;background:#eeecff;color:#5d4fe2;display:grid;place-items:center;font-weight:900;font-size:11px}.files{margin-top:18px}.file{padding:10px;border:1px solid #e5e7ef;border-radius:10px;margin-top:7px;background:white;font-size:12px}.status{margin-top:14px;padding:12px;border-radius:12px;background:#f3f1ff;color:#5548ca;font-size:12px;line-height:1.6}@media(max-width:800px){.grid{grid-template-columns:1fr}}</style></head><body><main class="wrap"><section class="hero"><span class="badge">🧠 REAL NLP WORKBENCH</span><h1>Give SmartToolz your <span>files + instruction.</span></h1><p>Describe the result in normal language. The workbench builds a plan and actually executes the enabled operations on uploaded images.</p></section><section class="card"><textarea id="req" placeholder="Example: Resize these 50 images to 1200px, compress them, convert to WebP, rename for SEO and ZIP everything."></textarea><input id="files" type="file" multiple accept="image/*"><button class="btn" id="run">🚀 Understand + Execute Workflow</button><div id="result" style="display:none"><div class="grid"><div class="panel"><h3>🧠 NLP PLAN</h3><span class="pill" id="intent">—</span><p id="confidence"></p><div id="plan"></div><div class="status" id="status"></div></div><div class="panel"><h3>📦 REAL OUTPUT</h3><div id="outputs"></div></div></div></div></section></main><script>
const $=id=>document.getElementById(id);$('run').onclick=async()=>{const req=$('req').value.trim(),files=$('files').files;if(!req||!files.length){alert('Instruction aur kam se kam 1 image file select karo.');return}const fd=new FormData();fd.append('request',req);[...files].forEach(f=>fd.append('files[]',f));$('run').disabled=true;$('run').textContent='⏳ NLP planning + processing...';try{const res=await fetch(location.href,{method:'POST',body:fd});const d=await res.json();$('result').style.display='block';$('intent').textContent=(d.plan.operations[0]?.label||'Workflow')+' • '+d.plan.operations.length+' operations';$('confidence').textContent='Intent confidence: '+d.plan.confidence+'%';$('plan').innerHTML=d.plan.operations.map((x,i)=>`<div class="step"><span class="num">${i+1}</span><div><b>${x.label}</b><div style="font-size:10px;color:#7b8495">${x.id==='resize'?'Parameters: '+(x.params.width||'auto')+'px':x.id==='compress'?'Quality optimized':x.id==='zip'?'Package final outputs':'SmartToolz executor'}</div></div></div>`).join('');$('outputs').innerHTML=d.files.map(f=>`<div class="file">✅ ${f.name} <small>(${Math.round(f.bytes/1024)} KB)</small><br><a href="${f.url}" target="_blank">Open output</a></div>`).join('')+(d.zip?`<div class="status"><a href="${d.zip}" download>📦 Download complete ZIP</a></div>`:'')+(d.errors.length?`<div class="status">⚠️ ${d.errors.join('<br>')}</div>`:'');$('status').textContent='Completed '+d.files.length+' file(s). NLP plan was converted into real image-processing operations on the server.'}catch(e){$('result').style.display='block';$('status').textContent='Execution error: '+e.message}finally{$('run').disabled=false;$('run').textContent='🚀 Understand + Execute Workflow'}};
</script></body></html>
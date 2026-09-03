<?php
declare(strict_types=1);
session_start();
const CLIENT_ID='523738407628-o1c4t43j4vjriajnvojpt4cio4mktr01.apps.googleusercontent.com';
const REDIRECT_URI='https://smarttoolz.in/keddy-bot/oauth-callback.php';
const SETUP_KEY='keddy-setup-2026';
$ok=$err='';
$stored=false;
if($_SERVER['REQUEST_METHOD']==='POST'){
 try{
  if(!hash_equals(SETUP_KEY,(string)($_POST['setup_key']??''))) throw new RuntimeException('Invalid setup key.');
  $secret=trim((string)($_POST['client_secret']??''));
  if($secret==='') throw new RuntimeException('Client Secret required.');
  $dir=__DIR__.'/data';
  if(!is_dir($dir) && !@mkdir($dir,0700,true) && !is_dir($dir)) throw new RuntimeException('Cannot create data directory. Server permission denied: '.htmlspecialchars($dir));
  if(!is_writable($dir)) throw new RuntimeException('Keddy data directory is not writable: '.htmlspecialchars($dir));
  $file=$dir.'/credentials.php';
  $tmp=$file.'.tmp-'.bin2hex(random_bytes(6));
  $content="<?php\ndeclare(strict_types=1);\nreturn ".var_export(['google_client_id'=>CLIENT_ID,'google_client_secret'=>$secret,'redirect_uri'=>REDIRECT_URI],true).";\n";
  if(file_put_contents($tmp,$content,LOCK_EX)===false) throw new RuntimeException('Cannot write temporary credentials file.');
  @chmod($tmp,0600);
  if(!@rename($tmp,$file)) { @unlink($tmp); throw new RuntimeException('Cannot replace credentials.php. Check server write permission.'); }
  @chmod($file,0600);
  if(!is_file($file) || filesize($file)<80) throw new RuntimeException('credentials.php was not created correctly.');
  $stored=true;
  $ok='Keddy OAuth credentials saved successfully on the server.';
 }catch(Throwable $e){$err=$e->getMessage();}
}
?><!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Keddy Setup</title><style>body{margin:0;background:#0b0d12;color:#fff;font:16px system-ui}main{max-width:680px;margin:8vh auto;padding:24px}.card{background:#121620;border:1px solid #252b38;border-radius:18px;padding:24px}label{display:block;margin:14px 0 7px;font-weight:700}input{width:100%;box-sizing:border-box;background:#0b0e14;border:1px solid #303746;color:#fff;border-radius:10px;padding:12px;font:inherit}.btn{margin-top:18px;border:0;border-radius:10px;padding:12px 18px;background:#7c3aed;color:#fff;font-weight:700;cursor:pointer}.ok,.err{padding:12px;border-radius:10px;margin-bottom:14px}.ok{background:#123b2a}.err{background:#4a1721}.muted{color:#9aa3b5}.code{font-family:monospace;word-break:break-all;margin:8px 0}.path{font-family:monospace;font-size:13px;color:#81899a;margin-top:12px}</style></head><body><main><div class="card"><h1>🤖 Keddy Setup</h1><p class="muted">Configure Keddy's Google OAuth Web Client. The Client Secret is saved only on the server.</p><?php if($ok):?><div class="ok"><?=htmlspecialchars($ok)?></div><?php endif;?><?php if($err):?><div class="err">⚠️ <?=htmlspecialchars($err)?></div><?php endif;?><div class="code">Client ID: <?=CLIENT_ID?></div><div class="code">Callback: <?=REDIRECT_URI?></div><?php if($stored):?><div class="path">✓ credentials.php verified: server-side storage is working.</div><?php endif;?><form method="post"><label>Setup Key</label><input name="setup_key" type="password" autocomplete="off" required><label>Google Client Secret</label><input name="client_secret" type="password" autocomplete="off" required><button class="btn">Save & Configure Keddy</button></form><p class="muted">After the success message appears, open <a href="index.php" style="color:#a78bfa">Keddy Bot</a> and click Connect YouTube.</p></div></main></body></html>
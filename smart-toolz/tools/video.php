<?php
declare(strict_types=1);

/*
 * SmartToolz — Video
 *
 * The hosting server never renders video and never needs FFmpeg.
 * Rendering is delegated to the repository's GitHub Actions Remotion worker.
 * Set SMARTTOOLZ_GITHUB_TOKEN on hosting for one-click dispatch from this page.
 */

$repo = 'codingbotlab/smarttoolz.in';
$workflow = 'video-remotion.yml';
$token = (string)(getenv('SMARTTOOLZ_GITHUB_TOKEN') ?: '');
$message = '';
$runUrl = '';

function videoDispatch(string $repo, string $workflow, string $token, array $inputs): array {
    if ($token === '') {
        return [false, 'SMARTTOOLZ_GITHUB_TOKEN is not configured on the hosting server. The tool UI is ready, but one-click GitHub rendering needs a GitHub token with Actions write permission.'];
    }

    $url = 'https://api.github.com/repos/' . $repo . '/actions/workflows/' . rawurlencode($workflow) . '/dispatches';
    $payload = json_encode(['ref' => 'main', 'inputs' => $inputs], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $payload,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Accept: application/vnd.github+json',
            'Authorization: Bearer ' . $token,
            'X-GitHub-Api-Version: 2022-11-28',
            'Content-Type: application/json',
            'User-Agent: SmartToolz-Video'
        ],
        CURLOPT_TIMEOUT => 20,
    ]);
    $body = curl_exec($ch);
    $code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err = curl_error($ch);
    curl_close($ch);

    if ($err !== '') return [false, 'GitHub connection failed: ' . $err];
    if ($code < 200 || $code >= 300) return [false, 'GitHub rejected the render request (HTTP ' . $code . '). ' . trim((string)$body)];
    return [true, 'Render started successfully.'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim((string)($_POST['title'] ?? ''));
    $script = trim((string)($_POST['script'] ?? ''));
    $voice = trim((string)($_POST['voice'] ?? 'hi-IN-MadhurNeural'));
    $format = trim((string)($_POST['format'] ?? 'landscape'));

    if ($title === '') $message = 'Video title is required.';
    elseif ($script === '') $message = 'Tutorial script is required.';
    elseif (mb_strlen($title) > 120) $message = 'Video title is too long (maximum 120 characters).';
    elseif (mb_strlen($script) > 60000) $message = 'Script is too long (maximum 60,000 characters).';
    elseif (!in_array($voice, ['hi-IN-MadhurNeural','en-IN-PrabhatNeural','en-US-GuyNeural'], true)) $message = 'Invalid voice selected.';
    elseif (!in_array($format, ['landscape','portrait'], true)) $message = 'Invalid video format.';
    else {
        [$ok, $message] = videoDispatch($repo, $workflow, $token, [
            'title' => $title,
            'script' => $script,
            'voice' => $voice,
            'format' => $format,
        ]);
        if ($ok) $runUrl = 'https://github.com/' . $repo . '/actions/workflows/' . $workflow;
    }
}

?><!doctype html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Video — SmartToolz</title>
<meta name="description" content="Create narrated tutorial videos with Remotion, realistic male AI voice and audio-synced captions.">
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,500,0,0&display=swap" rel="stylesheet">
<style>
:root{--p:#635bff;--p2:#8b7cff;--ink:#172033;--muted:#707b8e;--b:#e5e8f0;--bg:#f7f9fd;--card:#fff}*{box-sizing:border-box}body{margin:0;background:var(--bg);color:var(--ink);font-family:Inter,Arial,sans-serif}.wrap{width:min(1040px,calc(100% - 28px));margin:0 auto;padding:28px 0 55px}.hero{padding:28px;border:1px solid var(--b);border-radius:24px;background:linear-gradient(135deg,#fff,#f0efff);box-shadow:0 18px 50px rgba(30,35,80,.07)}.tag{display:inline-flex;align-items:center;gap:7px;color:var(--p);font-size:11px;font-weight:900;letter-spacing:1px}.hero h1{margin:10px 0 8px;font-size:clamp(34px,6vw,54px);letter-spacing:-2.2px}.hero p{margin:0;color:var(--muted);line-height:1.7;max-width:760px;font-size:14px}.grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-top:16px}.card{background:#fff;border:1px solid var(--b);border-radius:20px;padding:22px;box-shadow:0 12px 35px rgba(30,35,80,.05)}label{display:block;font-size:12px;font-weight:800;margin:0 0 7px}input,textarea,select{width:100%;border:1px solid #dfe3eb;border-radius:12px;background:#fff;color:var(--ink);padding:12px 13px;font:inherit;font-size:13px;outline:none}textarea{min-height:300px;resize:vertical;line-height:1.6}input:focus,textarea:focus,select:focus{border-color:#aaa4ff;box-shadow:0 0 0 4px rgba(99,91,255,.08)}.field{margin-bottom:15px}.hint{color:var(--muted);font-size:10.5px;line-height:1.5;margin-top:5px}.row{display:grid;grid-template-columns:1fr 1fr;gap:10px}.btn{width:100%;border:0;border-radius:12px;padding:13px 16px;font-weight:900;font-size:13px;background:linear-gradient(135deg,var(--p),var(--p2));color:#fff;cursor:pointer}.notice{margin-top:16px;padding:13px 15px;border-radius:13px;border:1px solid #ddd9ff;background:#f5f3ff;color:#4338ca;font-size:12px;line-height:1.55}.steps{display:grid;gap:9px;margin-top:16px}.step{display:flex;gap:10px;align-items:flex-start;padding:12px;border:1px solid var(--b);border-radius:13px;background:#fff}.num{width:25px;height:25px;flex:0 0 25px;border-radius:50%;display:grid;place-items:center;background:#eeedff;color:var(--p);font-size:11px;font-weight:900}.step strong{font-size:12px}.step span{display:block;color:var(--muted);font-size:10.5px;margin-top:2px;line-height:1.45}@media(max-width:760px){.grid{grid-template-columns:1fr}.row{grid-template-columns:1fr}.wrap{padding-top:15px}.hero{padding:21px}}
</style>
</head>
<body>
<main class="wrap">
<section class="hero">
<span class="tag"><span class="material-symbols-rounded">movie</span> SMARTTOOLZ VIDEO</span>
<h1>Video</h1>
<p>Turn a tutorial script into a polished narrated video. Rendering happens in GitHub Actions with Remotion, so your hosting server does <b>not</b> need FFmpeg. The narration uses a male neural voice and the video timeline follows the generated audio.</p>
</section>

<?php if ($message !== ''): ?><div class="notice"><?=htmlspecialchars($message,ENT_QUOTES,'UTF-8')?><?php if ($runUrl !== ''): ?> <a href="<?=htmlspecialchars($runUrl,ENT_QUOTES,'UTF-8')?>" target="_blank" rel="noopener noreferrer">Open GitHub render workflow →</a><?php endif; ?></div><?php endif; ?>

<form method="post" class="grid" id="videoForm">
<section class="card">
<div class="field"><label for="title">Video title</label><input id="title" name="title" maxlength="120" placeholder="How to use SmartToolz Video" required></div>
<div class="field"><label for="script">Tutorial script</label><textarea id="script" name="script" maxlength="60000" placeholder="Write the exact narration here. Use a blank line between major tutorial sections.\n\nExample:\nWelcome to SmartToolz. In this tutorial, we will learn how to..." required></textarea><div class="hint">Blank lines create natural scene breaks. Keep each section focused on one tutorial step.</div></div>
</section>
<section class="card">
<div class="row">
<div class="field"><label for="voice">Male realistic voice</label><select id="voice" name="voice"><option value="hi-IN-MadhurNeural">Hindi — Madhur (Male)</option><option value="en-IN-PrabhatNeural">Indian English — Prabhat (Male)</option><option value="en-US-GuyNeural">English — Guy (Male)</option></select></div>
<div class="field"><label for="format">Video format</label><select id="format" name="format"><option value="landscape">16:9 Landscape — YouTube</option><option value="portrait">9:16 Portrait — Shorts/Reels</option></select></div>
</div>
<div class="steps">
<div class="step"><div class="num">1</div><div><strong>Write the tutorial</strong><span>Put the complete narration in the script box.</span></div></div>
<div class="step"><div class="num">2</div><div><strong>Generate male narration</strong><span>GitHub generates the voice audio and timing data.</span></div></div>
<div class="step"><div class="num">3</div><div><strong>Remotion renders</strong><span>Scenes, captions and narration are locked to the same timeline.</span></div></div>
<div class="step"><div class="num">4</div><div><strong>Get the MP4</strong><span>The finished video is uploaded to the GitHub Actions run as an artifact.</span></div></div>
</div>
<button class="btn" type="submit" style="margin-top:18px"><span class="material-symbols-rounded" style="vertical-align:-5px">movie</span> Generate Video in GitHub</button>
</section>
</form>
</main>
<script>
document.getElementById('videoForm')?.addEventListener('submit',function(){const b=this.querySelector('.btn');if(b){b.disabled=true;b.textContent='Starting GitHub render…'}});
</script>
</body></html>

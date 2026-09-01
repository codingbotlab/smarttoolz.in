<?php
declare(strict_types=1);

require_once __DIR__ . '/Reddott-films/youtube/lib.php';

$connection = null;
$error = '';
$channel = null;
$videos = [];

try {
    $connection = reddottYoutubeConnection();
    if ($connection) {
        $token = reddottYoutubeAccessToken();
        $r = reddottYoutubeHttpGet('https://www.googleapis.com/youtube/v3/channels?' . http_build_query([
            'part' => 'snippet,statistics,contentDetails',
            'id' => $connection['channel_id'],
        ], '', '&', PHP_QUERY_RFC3986), $token);
        $d = json_decode($r, true);
        $channel = $d['items'][0] ?? null;

        $uploads = (string)($channel['contentDetails']['relatedPlaylists']['uploads'] ?? '');
        if ($uploads !== '') {
            $r = reddottYoutubeHttpGet('https://www.googleapis.com/youtube/v3/playlistItems?' . http_build_query([
                'part' => 'snippet,contentDetails',
                'playlistId' => $uploads,
                'maxResults' => 8,
            ], '', '&', PHP_QUERY_RFC3986), $token);
            $d = json_decode($r, true);
            $videos = $d['items'] ?? [];
        }
    }
} catch (Throwable $e) {
    error_log('Reddott Films dashboard: ' . $e->getMessage());
    $error = 'YouTube connection needs attention. Please reconnect if the problem continues.';
}

function rfEsc(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

$videoCount = count($videos);
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Reddott Films — Dashboard</title>
<meta name="description" content="Reddott Films content, YouTube and video blog workspace by SmartToolz.">
<style>
*{box-sizing:border-box}
body{margin:0;background:#0b0d12;color:#f5f7fb;font-family:Arial,Helvetica,sans-serif}
a{color:inherit}
.shell{width:min(1180px,calc(100% - 28px));margin:28px auto 50px}
.top{display:flex;align-items:center;justify-content:space-between;gap:18px;margin-bottom:20px}
.brand{display:flex;align-items:center;gap:12px;text-decoration:none}
.logo{width:42px;height:42px;border-radius:12px;background:#e11d48;display:grid;place-items:center;font-weight:900}
.brand strong{font-size:18px}.brand span{display:block;color:#aeb7c6;font-size:12px;margin-top:3px}
.nav{display:flex;gap:8px;flex-wrap:wrap;justify-content:flex-end}.nav a{padding:9px 12px;border:1px solid #2a3140;border-radius:10px;text-decoration:none;color:#dfe5ef;font-size:13px}.nav a:hover{border-color:#e11d48}
.hero{background:linear-gradient(135deg,#171922,#11141c);border:1px solid #2a3140;border-radius:20px;padding:28px;margin-bottom:16px}
.eyebrow{color:#ff7b9b;text-transform:uppercase;letter-spacing:.12em;font-size:12px;font-weight:800}
h1{font-size:38px;margin:8px 0 8px}.muted{color:#aeb7c6;line-height:1.6}.hero p{max-width:760px;margin:0}
.actions{display:flex;gap:10px;flex-wrap:wrap;margin-top:20px}.btn{display:inline-block;padding:11px 15px;border-radius:10px;background:#e11d48;text-decoration:none;font-weight:700}.btn.alt{background:#252b38}
.grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:12px;margin-bottom:16px}.card{background:#151922;border:1px solid #2a3140;border-radius:16px;padding:20px}.stat{font-size:26px;font-weight:800;margin-top:8px}.label{color:#aeb7c6;font-size:13px}
.modules{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:12px;margin-bottom:16px}.module{text-decoration:none;transition:.15s}.module:hover{transform:translateY(-2px);border-color:#e11d48}.module h2{font-size:18px;margin:0 0 8px}.module p{margin:0;color:#aeb7c6;font-size:14px;line-height:1.55}.icon{font-size:24px;margin-bottom:12px}
.section-head{display:flex;justify-content:space-between;gap:12px;align-items:center}.section-head h2{margin:0;font-size:20px}.section-head a{font-size:13px;color:#ff8eaa;text-decoration:none}
.video{padding:14px 0;border-top:1px solid #272e3b}.video:first-of-type{border-top:0}.video strong{display:block;margin-bottom:6px}.video small{color:#aeb7c6}
.error{border-color:#8b1e3f;color:#ffd5df;margin-bottom:16px}
.footer{display:flex;justify-content:space-between;gap:15px;flex-wrap:wrap;margin-top:20px;color:#7f8999;font-size:13px}.footer a{color:#aeb7c6;text-decoration:none}
@media(max-width:850px){.grid{grid-template-columns:repeat(2,1fr)}.modules{grid-template-columns:1fr 1fr}.top{align-items:flex-start;flex-direction:column}.nav{justify-content:flex-start}}
@media(max-width:560px){.grid,.modules{grid-template-columns:1fr}h1{font-size:30px}.shell{width:min(100% - 20px,1180px)}.hero{padding:22px}}
</style>
</head>
<body>
<div class="shell">
<header class="top">
<a class="brand" href="/reddott-films.php"><div class="logo">RF</div><div><strong>Reddott Films</strong><span>SmartToolz video workspace</span></div></a>
<nav class="nav">
<a href="/Reddott-films/youtube/dashboard.php">YouTube</a>
<a href="/Reddott-films/youtube/videos.php">Videos</a>
<a href="/Reddott-films/youtube/manager.php">Manage</a>
<a href="/Reddott-films/youtube/seo.php">SEO</a>
<a href="/Reddott-films/blogs/">Blogs</a>
</nav>
</header>

<?php if ($error): ?><div class="card error"><?=rfEsc($error)?></div><?php endif; ?>

<section class="hero">
<div class="eyebrow">Reddott Films</div>
<h1>Video Content Dashboard</h1>
<p class="muted">One workspace for YouTube publishing, video management, SEO and video-based blogs.</p>
<div class="actions">
<?php if ($connection): ?>
<a class="btn" href="/Reddott-films/youtube/manager.php">Manage Videos</a>
<a class="btn alt" href="/Reddott-films/youtube/seo.php">Open SEO</a>
<?php else: ?>
<a class="btn" href="/Reddott-films/youtube/connect.php">Connect YouTube</a>
<?php endif; ?>
<a class="btn alt" href="/Reddott-films/blogs/">Open Video Blog</a>
</div>
</section>

<div class="grid">
<div class="card"><div class="label">YouTube status</div><div class="stat"><?= $connection ? 'Connected' : 'Not connected' ?></div></div>
<div class="card"><div class="label">Subscribers</div><div class="stat"><?=rfEsc((string)($channel['statistics']['subscriberCount'] ?? '0'))?></div></div>
<div class="card"><div class="label">Channel views</div><div class="stat"><?=rfEsc((string)($channel['statistics']['viewCount'] ?? '0'))?></div></div>
<div class="card"><div class="label">Videos</div><div class="stat"><?=rfEsc((string)($channel['statistics']['videoCount'] ?? '0'))?></div></div>
</div>

<section class="modules">
<a class="card module" href="/Reddott-films/youtube/dashboard.php"><div class="icon">▶️</div><h2>YouTube</h2><p>Channel connection, statistics and recent uploads.</p></a>
<a class="card module" href="/Reddott-films/youtube/videos.php"><div class="icon">🎬</div><h2>Videos</h2><p>Browse the channel's existing video library.</p></a>
<a class="card module" href="/Reddott-films/youtube/manager.php"><div class="icon">⚙️</div><h2>Manage</h2><p>Upload, edit, publish and manage video settings.</p></a>
<a class="card module" href="/Reddott-films/youtube/seo.php"><div class="icon">🔎</div><h2>SEO</h2><p>Review and improve titles, descriptions and tags.</p></a>
<a class="card module" href="/Reddott-films/blogs/"><div class="icon">📝</div><h2>Video Blogs</h2><p>Turn video sources into structured, source-grounded articles.</p></a>
<a class="card module" href="/smart-toolz/"><div class="icon">🛠️</div><h2>SmartToolz</h2><p>Return to the main SmartToolz tools platform.</p></a>
</section>

<?php if ($channel): ?>
<section class="card">
<div class="section-head"><h2>Recent YouTube videos</h2><a href="/Reddott-films/youtube/videos.php">View all</a></div>
<?php if (!$videos): ?><p class="muted">No videos returned.</p><?php endif; ?>
<?php foreach ($videos as $video): ?>
<div class="video"><strong><?=rfEsc((string)($video['snippet']['title'] ?? 'Untitled'))?></strong><small>Published: <?=rfEsc((string)($video['snippet']['publishedAt'] ?? ''))?></small></div>
<?php endforeach; ?>
</section>
<?php else: ?>
<section class="card"><h2>Get started</h2><p class="muted">Connect the dedicated Reddott Films YouTube account to unlock channel stats, video management, SEO and publishing tools.</p><a class="btn" href="/Reddott-films/youtube/connect.php">Connect YouTube</a></section>
<?php endif; ?>

<footer class="footer"><span>© <?=date('Y')?> SmartToolz / Reddott Films</span><span><a href="/privacy-policy.php">Privacy</a> · <a href="/terms.php">Terms</a> · <a href="/">SmartToolz</a></span></footer>
</div>
</body>
</html>

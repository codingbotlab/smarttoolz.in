<?php
declare(strict_types=1);

require_once __DIR__ . '/reddott-films-youtube-lib.php';

$connection = null;
$error = '';
$channel = null;
$videos = [];

try {
    $connection = reddottYoutubeConnection();
    if ($connection) {
        $accessToken = reddottYoutubeAccessToken();

        $channelResponse = reddottYoutubeHttpGet(
            'https://www.googleapis.com/youtube/v3/channels?' . http_build_query([
                'part' => 'snippet,statistics,contentDetails',
                'id' => $connection['channel_id'],
            ], '', '&', PHP_QUERY_RFC3986),
            $accessToken
        );
        $channelData = json_decode($channelResponse, true);
        $channel = $channelData['items'][0] ?? null;

        $uploadsPlaylistId = (string)($channel['contentDetails']['relatedPlaylists']['uploads'] ?? '');
        if ($uploadsPlaylistId !== '') {
            $playlistResponse = reddottYoutubeHttpGet(
                'https://www.googleapis.com/youtube/v3/playlistItems?' . http_build_query([
                    'part' => 'snippet,contentDetails',
                    'playlistId' => $uploadsPlaylistId,
                    'maxResults' => 10,
                ], '', '&', PHP_QUERY_RFC3986),
                $accessToken
            );
            $playlistData = json_decode($playlistResponse, true);
            $videos = $playlistData['items'] ?? [];
        }
    }
} catch (Throwable $e) {
    error_log('Reddott Films YouTube dashboard error: ' . $e->getMessage());
    $error = 'YouTube connection needs attention. Please reconnect if the problem continues.';
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Reddott Films — YouTube Dashboard</title>
<style>
body{margin:0;background:#0b0d12;color:#f5f7fb;font-family:Arial,sans-serif}
.wrap{width:min(1050px,calc(100% - 32px));margin:40px auto}
.card{background:#151922;border:1px solid #2a3140;border-radius:18px;padding:24px;margin-bottom:18px}
h1{margin:0 0 8px}.muted{color:#aeb7c6}.ok{font-weight:700}.stat{font-size:28px;font-weight:700;margin-top:8px}
a{color:#fff;text-decoration:none}.btn{display:inline-block;background:#e11d48;padding:11px 16px;border-radius:10px;margin-top:14px}
.grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:14px}
.video{padding:14px;border:1px solid #2a3140;border-radius:12px;margin-top:10px}.video strong{display:block;margin-bottom:6px}.error{border-color:#8b1e3f;color:#ffd5df}
</style>
</head>
<body>
<div class="wrap">
<div class="card">
<h1>Reddott Films — YouTube</h1>
<p class="muted">Secure YouTube connection and channel management.</p>
<?php if ($connection): ?>
<p class="ok">🟢 Connected: <?= htmlspecialchars((string)$connection['channel_title'], ENT_QUOTES, 'UTF-8') ?></p>
<p class="muted">Channel ID: <?= htmlspecialchars((string)$connection['channel_id'], ENT_QUOTES, 'UTF-8') ?></p>
<?php else: ?>
<p class="muted">YouTube is not connected yet.</p>
<a class="btn" href="/reddott-films-youtube-connect.php">Connect YouTube</a>
<?php endif; ?>
<?php if ($error): ?><div class="card error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
</div>
<?php if ($channel): ?>
<div class="grid">
<div class="card"><div class="muted">Subscribers</div><div class="stat"><?= htmlspecialchars((string)($channel['statistics']['subscriberCount'] ?? '0'), ENT_QUOTES, 'UTF-8') ?></div></div>
<div class="card"><div class="muted">Views</div><div class="stat"><?= htmlspecialchars((string)($channel['statistics']['viewCount'] ?? '0'), ENT_QUOTES, 'UTF-8') ?></div></div>
<div class="card"><div class="muted">Videos</div><div class="stat"><?= htmlspecialchars((string)($channel['statistics']['videoCount'] ?? '0'), ENT_QUOTES, 'UTF-8') ?></div></div>
</div>
<div class="card">
<h2>Recent videos</h2>
<?php if (!$videos): ?><p class="muted">No videos returned.</p><?php endif; ?>
<?php foreach ($videos as $video): ?>
<div class="video">
<strong><?= htmlspecialchars((string)($video['snippet']['title'] ?? 'Untitled'), ENT_QUOTES, 'UTF-8') ?></strong>
<span class="muted">Published: <?= htmlspecialchars((string)($video['snippet']['publishedAt'] ?? ''), ENT_QUOTES, 'UTF-8') ?></span>
</div>
<?php endforeach; ?>
</div>
<?php endif; ?>
<div class="card"><a href="/reddott-films.php">← Back to Reddott Films</a></div>
</div>
</body>
</html>

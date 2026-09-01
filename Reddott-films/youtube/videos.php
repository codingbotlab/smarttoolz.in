<?php
declare(strict_types=1);
require_once __DIR__ . '/lib.php';

$videos = [];
$error = '';
$nextPage = '';

try {
    $connection = reddottYoutubeConnection();
    if (!$connection) {
        header('Location: /Reddott-films/youtube/dashboard.php');
        exit;
    }

    $token = reddottYoutubeAccessToken();
    $channelUrl = 'https://www.googleapis.com/youtube/v3/channels?' . http_build_query([
        'part' => 'contentDetails',
        'id' => $connection['channel_id'],
    ], '', '&', PHP_QUERY_RFC3986);
    $channel = json_decode(reddottYoutubeHttpGet($channelUrl, $token), true);
    $uploads = (string)($channel['items'][0]['contentDetails']['relatedPlaylists']['uploads'] ?? '');

    if ($uploads !== '') {
        $url = 'https://www.googleapis.com/youtube/v3/playlistItems?' . http_build_query([
            'part' => 'snippet,contentDetails,status',
            'playlistId' => $uploads,
            'maxResults' => 50,
        ], '', '&', PHP_QUERY_RFC3986);
        $data = json_decode(reddottYoutubeHttpGet($url, $token), true);
        $videos = is_array($data['items'] ?? null) ? $data['items'] : [];
        $nextPage = (string)($data['nextPageToken'] ?? '');
    }
} catch (Throwable $e) {
    error_log('Reddott Films YouTube videos: ' . $e->getMessage());
    $error = 'Unable to load YouTube videos. Please reconnect YouTube if the problem continues.';
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Reddott Films — YouTube Videos</title>
<style>
body{margin:0;background:#0b0d12;color:#f5f7fb;font-family:Arial,sans-serif}.wrap{width:min(1100px,calc(100% - 32px));margin:40px auto}.card{background:#151922;border:1px solid #2a3140;border-radius:18px;padding:24px;margin-bottom:18px}.muted{color:#aeb7c6}a{color:#fff}.video{display:grid;grid-template-columns:180px 1fr;gap:18px;padding:16px 0;border-top:1px solid #2a3140}.video:first-child{border-top:0}.thumb{width:180px;height:101px;object-fit:cover;border-radius:10px;background:#222}.title{font-size:18px;font-weight:700;margin-bottom:8px}.meta{font-size:13px;color:#aeb7c6;line-height:1.6}.btn{display:inline-block;background:#e11d48;color:#fff;text-decoration:none;padding:10px 14px;border-radius:9px;margin-top:12px}.error{border-color:#8b1e3f;color:#ffd5df}@media(max-width:650px){.video{grid-template-columns:1fr}.thumb{width:100%;height:auto;max-height:220px}}
</style>
</head>
<body><div class="wrap">
<div class="card"><h1>Reddott Films — YouTube Videos</h1><p class="muted">Existing videos from the connected Reddott Films channel.</p><a href="/Reddott-films/youtube/dashboard.php">← Dashboard</a></div>
<?php if ($error): ?><div class="card error"><?=htmlspecialchars($error, ENT_QUOTES, 'UTF-8')?></div><?php endif; ?>
<div class="card">
<?php if (!$videos): ?><p class="muted">No videos returned.</p><?php endif; ?>
<?php foreach ($videos as $video):
    $snippet = $video['snippet'] ?? [];
    $videoId = (string)($video['contentDetails']['videoId'] ?? '');
    $title = (string)($snippet['title'] ?? 'Untitled');
    $published = (string)($snippet['publishedAt'] ?? '');
    $thumb = (string)($snippet['thumbnails']['medium']['url'] ?? $snippet['thumbnails']['default']['url'] ?? '');
?>
<div class="video">
<?php if ($thumb !== ''): ?><img class="thumb" src="<?=htmlspecialchars($thumb, ENT_QUOTES, 'UTF-8')?>" alt=""><?php else: ?><div class="thumb"></div><?php endif; ?>
<div><div class="title"><?=htmlspecialchars($title, ENT_QUOTES, 'UTF-8')?></div><div class="meta">Published: <?=htmlspecialchars($published, ENT_QUOTES, 'UTF-8')?><br>Video ID: <?=htmlspecialchars($videoId, ENT_QUOTES, 'UTF-8')?></div><?php if ($videoId !== ''): ?><a class="btn" target="_blank" rel="noopener" href="https://www.youtube.com/watch?v=<?=rawurlencode($videoId)?>">Open on YouTube</a><?php endif; ?></div>
</div>
<?php endforeach; ?>
<?php if ($nextPage !== ''): ?><p class="muted">More videos are available on YouTube; pagination will be added to the manager next.</p><?php endif; ?>
</div></div></body></html>

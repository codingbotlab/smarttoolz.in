<?php
declare(strict_types=1);

require_once __DIR__ . '/lib.php';

if (session_status() !== PHP_SESSION_ACTIVE) session_start();
if (empty($_SESSION['reddott_youtube_manager_csrf'])) {
    $_SESSION['reddott_youtube_manager_csrf'] = bin2hex(random_bytes(32));
}
$csrf = (string)$_SESSION['reddott_youtube_manager_csrf'];
$error = '';
$notice = '';
$videos = [];

function h(string $v): string
{
    return htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
}

function ytApiRequest(string $method, string $url, string $token, ?string $body = null, array $headers = []): array
{
    $ch = curl_init($url);
    if ($ch === false) throw new RuntimeException('Unable to initialize cURL.');

    $headers[] = 'Authorization: Bearer ' . $token;
    if ($body !== null && !preg_grep('/^Content-Type:/i', $headers)) {
        $headers[] = 'Content-Type: application/json; charset=UTF-8';
    }

    curl_setopt_array($ch, [
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_TIMEOUT => 180,
        CURLOPT_CONNECTTIMEOUT => 20,
        CURLOPT_POSTFIELDS => $body,
    ]);

    $response = curl_exec($ch);
    if ($response === false) {
        $e = curl_error($ch);
        curl_close($ch);
        throw new RuntimeException($e);
    }
    $status = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $data = json_decode((string)$response, true);
    if (!is_array($data)) $data = [];
    if ($status < 200 || $status >= 300) {
        throw new RuntimeException((string)($data['error']['message'] ?? ('YouTube API returned HTTP ' . $status . '.')));
    }
    return $data;
}

function uploadThumbnail(string $videoId, string $path, string $mime, string $token): void
{
    $file = file_get_contents($path);
    if ($file === false) throw new RuntimeException('Unable to read thumbnail.');

    $ch = curl_init('https://www.googleapis.com/upload/youtube/v3/thumbnails/set?videoId=' . rawurlencode($videoId));
    if ($ch === false) throw new RuntimeException('Unable to initialize cURL.');

    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $token, 'Content-Type: ' . $mime],
        CURLOPT_POSTFIELDS => $file,
        CURLOPT_TIMEOUT => 120,
    ]);
    $response = curl_exec($ch);
    if ($response === false) {
        $e = curl_error($ch);
        curl_close($ch);
        throw new RuntimeException($e);
    }
    $status = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    $data = json_decode((string)$response, true);
    if ($status < 200 || $status >= 300) {
        throw new RuntimeException((string)($data['error']['message'] ?? ('Thumbnail API returned HTTP ' . $status . '.')));
    }
}

function validVideoId(string $id): bool
{
    return (bool)preg_match('/^[A-Za-z0-9_-]{6,}$/', $id);
}

try {
    $connection = reddottYoutubeConnection();
    if (!$connection) {
        header('Location: /Reddott-films/youtube/dashboard.php');
        exit;
    }
    $token = reddottYoutubeAccessToken();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!hash_equals($csrf, (string)($_POST['csrf'] ?? ''))) {
            throw new RuntimeException('Invalid form token. Refresh the page and try again.');
        }

        $action = (string)($_POST['action'] ?? '');
        $videoId = trim((string)($_POST['video_id'] ?? ''));

        if ($action === 'update') {
            if (!validVideoId($videoId)) throw new RuntimeException('Invalid video ID.');
            $title = trim((string)($_POST['title'] ?? ''));
            $description = (string)($_POST['description'] ?? '');
            $privacy = (string)($_POST['privacyStatus'] ?? 'private');
            if ($title === '' || mb_strlen($title) > 100) throw new RuntimeException('Title is required and must be 100 characters or fewer.');
            if (!in_array($privacy, ['private', 'public', 'unlisted'], true)) $privacy = 'private';

            ytApiRequest('PUT', 'https://www.googleapis.com/youtube/v3/videos?part=snippet,status', $token,
                json_encode([
                    'id' => $videoId,
                    'snippet' => [
                        'title' => $title,
                        'description' => $description,
                        'categoryId' => '28',
                    ],
                    'status' => ['privacyStatus' => $privacy],
                ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
            );

            if (isset($_FILES['thumbnail']) && is_array($_FILES['thumbnail']) && (int)$_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
                $thumbTmp = (string)$_FILES['thumbnail']['tmp_name'];
                $thumbSize = (int)$_FILES['thumbnail']['size'];
                $thumbMime = (string)($_FILES['thumbnail']['type'] ?? '');
                if ($thumbSize <= 0 || $thumbSize > 2 * 1024 * 1024) throw new RuntimeException('Thumbnail must be 2 MB or smaller.');
                if (!in_array($thumbMime, ['image/jpeg', 'image/png'], true)) throw new RuntimeException('Thumbnail must be JPG or PNG.');
                uploadThumbnail($videoId, $thumbTmp, $thumbMime, $token);
            }
            $notice = 'Video details updated successfully.';
        } elseif ($action === 'delete') {
            if (!validVideoId($videoId)) throw new RuntimeException('Invalid video ID.');
            ytApiRequest('DELETE', 'https://www.googleapis.com/youtube/v3/videos?id=' . rawurlencode($videoId), $token);
            $notice = 'Video deleted successfully.';
        } elseif ($action === 'upload') {
            if (!isset($_FILES['video']) || !is_array($_FILES['video']) || (int)$_FILES['video']['error'] !== UPLOAD_ERR_OK) {
                throw new RuntimeException('Please choose a video file.');
            }
            $tmp = (string)$_FILES['video']['tmp_name'];
            if (!is_uploaded_file($tmp)) throw new RuntimeException('Invalid uploaded file.');
            $size = (int)$_FILES['video']['size'];
            if ($size <= 0 || $size > 512 * 1024 * 1024) throw new RuntimeException('Video must be between 1 byte and 512 MB.');

            $mime = (string)($_FILES['video']['type'] ?? 'video/mp4');
            if (strpos($mime, 'video/') !== 0) $mime = 'video/mp4';
            $title = trim((string)($_POST['upload_title'] ?? ''));
            $description = (string)($_POST['upload_description'] ?? '');
            $privacy = (string)($_POST['upload_privacy'] ?? 'private');
            if ($title === '' || mb_strlen($title) > 100) throw new RuntimeException('Upload title is required and must be 100 characters or fewer.');
            if (!in_array($privacy, ['private', 'public', 'unlisted'], true)) $privacy = 'private';

            $meta = json_encode([
                'snippet' => ['title' => $title, 'description' => $description, 'categoryId' => '28'],
                'status' => ['privacyStatus' => $privacy],
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            $file = file_get_contents($tmp);
            if ($file === false) throw new RuntimeException('Unable to read uploaded video.');

            $boundary = '----ReddottFilms' . bin2hex(random_bytes(12));
            $body = '--' . $boundary . "\r\n" .
                "Content-Type: application/json; charset=UTF-8\r\n\r\n" . $meta . "\r\n" .
                '--' . $boundary . "\r\n" .
                'Content-Type: ' . $mime . "\r\n\r\n" . $file . "\r\n" .
                '--' . $boundary . "--\r\n";

            $data = ytApiRequest(
                'POST',
                'https://www.googleapis.com/upload/youtube/v3/videos?uploadType=multipart&part=snippet,status',
                $token,
                $body,
                ['Content-Type: multipart/related; boundary=' . $boundary]
            );
            $newId = (string)($data['id'] ?? '');

            if ($newId !== '' && isset($_FILES['upload_thumbnail']) && is_array($_FILES['upload_thumbnail']) && (int)$_FILES['upload_thumbnail']['error'] === UPLOAD_ERR_OK) {
                $thumbTmp = (string)$_FILES['upload_thumbnail']['tmp_name'];
                $thumbSize = (int)$_FILES['upload_thumbnail']['size'];
                $thumbMime = (string)($_FILES['upload_thumbnail']['type'] ?? '');
                if ($thumbSize > 2 * 1024 * 1024) throw new RuntimeException('Video uploaded, but thumbnail is larger than 2 MB.');
                if (!in_array($thumbMime, ['image/jpeg', 'image/png'], true)) throw new RuntimeException('Video uploaded, but thumbnail must be JPG or PNG.');
                uploadThumbnail($newId, $thumbTmp, $thumbMime, $token);
            }
            $notice = $newId !== '' ? 'Video uploaded successfully. Video ID: ' . $newId : 'Video uploaded successfully.';
        }
    }

    // Get the user's videos, then fetch their current status so editing never
    // accidentally changes a public/unlisted video to private.
    $listUrl = 'https://www.googleapis.com/youtube/v3/search?' . http_build_query([
        'part' => 'snippet', 'forMine' => 'true', 'type' => 'video', 'maxResults' => 50,
    ], '', '&', PHP_QUERY_RFC3986);
    $searchData = ytApiRequest('GET', $listUrl, $token);
    $items = is_array($searchData['items'] ?? null) ? $searchData['items'] : [];
    $ids = [];
    foreach ($items as $item) {
        $id = (string)($item['id']['videoId'] ?? '');
        if ($id !== '') $ids[] = $id;
    }

    $detailById = [];
    if ($ids) {
        $detailUrl = 'https://www.googleapis.com/youtube/v3/videos?' . http_build_query([
            'part' => 'snippet,status,contentDetails', 'id' => implode(',', $ids), 'maxResults' => 50,
        ], '', '&', PHP_QUERY_RFC3986);
        $detailData = ytApiRequest('GET', $detailUrl, $token);
        foreach (($detailData['items'] ?? []) as $item) {
            if (isset($item['id'])) $detailById[(string)$item['id']] = $item;
        }
    }

    foreach ($items as $item) {
        $id = (string)($item['id']['videoId'] ?? '');
        if ($id === '') continue;
        $detail = $detailById[$id] ?? $item;
        $s = is_array($detail['snippet'] ?? null) ? $detail['snippet'] : [];
        $st = is_array($detail['status'] ?? null) ? $detail['status'] : [];
        $videos[] = [
            'id' => $id,
            'title' => (string)($s['title'] ?? 'Untitled'),
            'description' => (string)($s['description'] ?? ''),
            'privacy' => (string)($st['privacyStatus'] ?? 'private'),
            'publishedAt' => (string)($s['publishedAt'] ?? ''),
            'thumb' => (string)($s['thumbnails']['medium']['url'] ?? $s['thumbnails']['default']['url'] ?? ''),
        ];
    }
} catch (Throwable $e) {
    error_log('Reddott Films YouTube manager: ' . $e->getMessage());
    $error = $e->getMessage();
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Reddott Films — YouTube Manager</title>
<style>
body{margin:0;background:#0b0d12;color:#f5f7fb;font-family:Arial,sans-serif}.wrap{width:min(1180px,calc(100% - 32px));margin:32px auto}.card{background:#151922;border:1px solid #2a3140;border-radius:18px;padding:22px;margin-bottom:18px}.grid{display:grid;grid-template-columns:1fr 1fr;gap:18px}.field{margin:10px 0}.field label{display:block;font-size:13px;color:#aeb7c6;margin-bottom:6px}.field input,.field textarea,.field select{width:100%;box-sizing:border-box;background:#0e1118;border:1px solid #303848;color:#fff;border-radius:10px;padding:11px}.field textarea{min-height:100px;resize:vertical}.btn{border:0;background:#e11d48;color:#fff;padding:10px 15px;border-radius:10px;cursor:pointer}.danger{background:#7f1d1d}.muted,.small{color:#aeb7c6}.small{font-size:12px}.notice{border-color:#245c3b;color:#c8f7d7}.error{border-color:#8b1e3f;color:#ffd5df}.video{border-top:1px solid #2a3140;padding:18px 0;overflow:auto}.video:first-child{border-top:0}.thumb{width:180px;height:101px;object-fit:cover;border-radius:10px;float:left;margin-right:16px}.actions{padding-top:12px}.inline{display:flex;gap:10px;align-items:center;flex-wrap:wrap}a{color:#fff}@media(max-width:750px){.grid{grid-template-columns:1fr}.thumb{float:none;width:100%;height:auto;margin:0 0 12px}}
</style>
</head>
<body><div class="wrap">
<div class="card"><h1>Reddott Films — YouTube Manager</h1><p class="muted">Upload, edit, thumbnail, privacy and delete controls for the connected Reddott Films channel.</p><a href="/Reddott-films/youtube/dashboard.php">← Dashboard</a></div>
<?php if($notice):?><div class="card notice"><?=h($notice)?></div><?php endif;?>
<?php if($error):?><div class="card error"><?=h($error)?></div><?php endif;?>
<div class="grid">
<div class="card"><h2>Upload video</h2><form method="post" enctype="multipart/form-data"><input type="hidden" name="csrf" value="<?=h($csrf)?>"><input type="hidden" name="action" value="upload">
<div class="field"><label>Video file</label><input type="file" name="video" accept="video/*" required></div>
<div class="field"><label>Title</label><input name="upload_title" maxlength="100" required></div>
<div class="field"><label>Description</label><textarea name="upload_description"></textarea></div>
<div class="field"><label>Thumbnail (optional, JPG/PNG, max 2 MB)</label><input type="file" name="upload_thumbnail" accept="image/jpeg,image/png"></div>
<div class="field"><label>Privacy</label><select name="upload_privacy"><option value="private">Private</option><option value="unlisted">Unlisted</option><option value="public">Public</option></select></div>
<button class="btn">Upload to YouTube</button></form><p class="small">Large uploads also depend on Hostinger PHP upload limits.</p></div>
<div class="card"><h2>Reddott controls</h2><p class="muted">Connected YouTube channel is managed through the separate Reddott Films OAuth connection. Access and refresh tokens remain server-side.</p><p class="muted">Existing video privacy is loaded from YouTube before editing, so saving a title does not silently force a video private.</p></div>
</div>
<div class="card"><h2>Existing videos</h2>
<?php if(!$videos):?><p class="muted">No videos returned.</p><?php endif;?>
<?php foreach($videos as $v):?><div class="video">
<?php if($v['thumb']!==''):?><img class="thumb" src="<?=h($v['thumb'])?>" alt=""><?php endif;?>
<form method="post" enctype="multipart/form-data"><input type="hidden" name="csrf" value="<?=h($csrf)?>"><input type="hidden" name="action" value="update"><input type="hidden" name="video_id" value="<?=h($v['id'])?>">
<div class="field"><label>Title</label><input name="title" maxlength="100" value="<?=h($v['title'])?>" required></div>
<div class="field"><label>Description</label><textarea name="description"><?=h($v['description'])?></textarea></div>
<div class="field"><label>Privacy</label><select name="privacyStatus"><?php foreach(['private','unlisted','public'] as $p):?><option value="<?=$p?>" <?=$v['privacy']===$p?'selected':''?>><?=ucfirst($p)?></option><?php endforeach;?></select></div>
<div class="field"><label>Replace thumbnail (optional, JPG/PNG, max 2 MB)</label><input type="file" name="thumbnail" accept="image/jpeg,image/png"></div>
<div class="inline"><button class="btn">Save changes</button><a href="https://www.youtube.com/watch?v=<?=rawurlencode($v['id'])?>" target="_blank" rel="noopener">Open on YouTube</a></div>
</form>
<div class="actions"><form method="post" onsubmit="return confirm('Delete this YouTube video permanently?');"><input type="hidden" name="csrf" value="<?=h($csrf)?>"><input type="hidden" name="action" value="delete"><input type="hidden" name="video_id" value="<?=h($v['id'])?>"><button class="btn danger">Delete permanently</button></form></div>
<div class="small">Video ID: <?=h($v['id'])?><?php if($v['publishedAt']!==''):?> · <?=h($v['publishedAt'])?><?php endif;?></div>
</div><?php endforeach;?></div>
</div></body></html>

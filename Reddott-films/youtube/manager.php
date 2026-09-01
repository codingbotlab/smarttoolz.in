<?php
declare(strict_types=1);

require_once __DIR__ . '/lib.php';

if (session_status() !== PHP_SESSION_ACTIVE) session_start();
if (empty($_SESSION['reddott_youtube_manager_csrf'])) $_SESSION['reddott_youtube_manager_csrf'] = bin2hex(random_bytes(32));
$csrf = (string)$_SESSION['reddott_youtube_manager_csrf'];
$error = '';
$notice = '';
$videos = [];

function ytJson(array $data): array { return is_array($data) ? $data : []; }
function ytRequest(string $method, string $url, string $token, ?string $body = null, array $headers = []): array {
    $ch = curl_init($url);
    if ($ch === false) throw new RuntimeException('Unable to initialize cURL.');
    $headers[] = 'Authorization: Bearer ' . $token;
    if ($body !== null) $headers[] = 'Content-Type: application/json; charset=UTF-8';
    curl_setopt_array($ch, [CURLOPT_CUSTOMREQUEST=>$method,CURLOPT_RETURNTRANSFER=>true,CURLOPT_HTTPHEADER=>$headers,CURLOPT_TIMEOUT=>120,CURLOPT_POSTFIELDS=>$body]);
    $response = curl_exec($ch);
    if ($response === false) { $e=curl_error($ch); curl_close($ch); throw new RuntimeException($e); }
    $status=(int)curl_getinfo($ch,CURLINFO_HTTP_CODE); curl_close($ch);
    $data=ytJson(json_decode((string)$response,true));
    if ($status < 200 || $status >= 300) throw new RuntimeException((string)($data['error']['message'] ?? ('YouTube API returned HTTP '.$status.'.')));
    return $data;
}

try {
    $connection = reddottYoutubeConnection();
    if (!$connection) { header('Location: /Reddott-films/youtube/dashboard.php'); exit; }
    $token = reddottYoutubeAccessToken();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!hash_equals($csrf, (string)($_POST['csrf'] ?? ''))) throw new RuntimeException('Invalid form token. Refresh the page and try again.');
        $action=(string)($_POST['action'] ?? '');
        $videoId=trim((string)($_POST['video_id'] ?? ''));
        if ($action === 'update') {
            if (!preg_match('/^[A-Za-z0-9_-]{6,}$/',$videoId)) throw new RuntimeException('Invalid video ID.');
            $title=trim((string)($_POST['title'] ?? ''));
            $description=(string)($_POST['description'] ?? '');
            $privacy=(string)($_POST['privacyStatus'] ?? 'private');
            if ($title === '' || mb_strlen($title) > 100) throw new RuntimeException('Title is required and must be 100 characters or fewer.');
            if (!in_array($privacy,['private','public','unlisted'],true)) $privacy='private';
            ytRequest('PUT','https://www.googleapis.com/youtube/v3/videos?part=snippet,status',$token,json_encode(['id'=>$videoId,'snippet'=>['title'=>$title,'description'=>$description,'categoryId'=>'28'],'status'=>['privacyStatus'=>$privacy]],JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES));
            $notice='Video details updated successfully.';
        } elseif ($action === 'delete') {
            if (!preg_match('/^[A-Za-z0-9_-]{6,}$/',$videoId)) throw new RuntimeException('Invalid video ID.');
            ytRequest('DELETE','https://www.googleapis.com/youtube/v3/videos?id='.rawurlencode($videoId),$token);
            $notice='Video deleted successfully.';
        } elseif ($action === 'upload') {
            if (!isset($_FILES['video']) || !is_array($_FILES['video']) || (int)$_FILES['video']['error'] !== UPLOAD_ERR_OK) throw new RuntimeException('Please choose a video file.');
            $tmp=(string)$_FILES['video']['tmp_name'];
            if (!is_uploaded_file($tmp)) throw new RuntimeException('Invalid uploaded file.');
            $size=(int)$_FILES['video']['size'];
            if ($size <= 0 || $size > 512*1024*1024) throw new RuntimeException('Video must be between 1 byte and 512 MB.');
            $mime=(string)($_FILES['video']['type'] ?? 'video/mp4');
            if (strpos($mime,'video/') !== 0) $mime='video/mp4';
            $title=trim((string)($_POST['upload_title'] ?? ''));
            $description=(string)($_POST['upload_description'] ?? '');
            $privacy=(string)($_POST['upload_privacy'] ?? 'private');
            if ($title === '' || mb_strlen($title) > 100) throw new RuntimeException('Upload title is required and must be 100 characters or fewer.');
            if (!in_array($privacy,['private','public','unlisted'],true)) $privacy='private';
            $meta=json_encode(['snippet'=>['title'=>$title,'description'=>$description,'categoryId'=>'28'],'status'=>['privacyStatus'=>$privacy]],JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
            $file=file_get_contents($tmp);
            if ($file === false) throw new RuntimeException('Unable to read uploaded video.');
            $boundary='----ReddottFilms'.bin2hex(random_bytes(12));
            $body='--'.$boundary."\r\nContent-Type: application/json; charset=UTF-8\r\n\r\n".$meta."\r\n--".$boundary."\r\nContent-Type: ".$mime."\r\n\r\n".$file."\r\n--".$boundary."--\r\n";
            $data=ytRequest('POST','https://www.googleapis.com/upload/youtube/v3/videos?uploadType=multipart&part=snippet,status',$token,$body,['Content-Type: multipart/related; boundary='.$boundary]);
            $newId=(string)($data['id'] ?? '');
            $notice=$newId !== '' ? 'Video uploaded successfully. Video ID: '.$newId : 'Video uploaded successfully.';
        }
    }

    $listUrl='https://www.googleapis.com/youtube/v3/search?'.http_build_query(['part'=>'snippet','forMine'=>'true','type'=>'video','maxResults'=>50],'','&',PHP_QUERY_RFC3986);
    $data=ytRequest('GET',$listUrl,$token);
    $videos=is_array($data['items'] ?? null) ? $data['items'] : [];
} catch (Throwable $e) {
    error_log('Reddott Films YouTube manager: '.$e->getMessage());
    $error=$e->getMessage();
}
function h(string $v): string { return htmlspecialchars($v,ENT_QUOTES,'UTF-8'); }
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Reddott Films — YouTube Manager</title><style>
body{margin:0;background:#0b0d12;color:#f5f7fb;font-family:Arial,sans-serif}.wrap{width:min(1150px,calc(100% - 32px));margin:32px auto}.card{background:#151922;border:1px solid #2a3140;border-radius:18px;padding:22px;margin-bottom:18px}.grid{display:grid;grid-template-columns:1fr 1fr;gap:18px}.field{margin:10px 0}.field label{display:block;font-size:13px;color:#aeb7c6;margin-bottom:6px}.field input,.field textarea,.field select{width:100%;box-sizing:border-box;background:#0e1118;border:1px solid #303848;color:#fff;border-radius:10px;padding:11px}.field textarea{min-height:100px;resize:vertical}.btn{border:0;background:#e11d48;color:#fff;padding:10px 15px;border-radius:10px;cursor:pointer}.danger{background:#7f1d1d}.muted{color:#aeb7c6}.notice{border-color:#245c3b;color:#c8f7d7}.error{border-color:#8b1e3f;color:#ffd5df}.video{border-top:1px solid #2a3140;padding:18px 0}.video:first-child{border-top:0}.thumb{width:180px;height:101px;object-fit:cover;border-radius:10px;float:left;margin-right:16px}.actions{clear:both;padding-top:12px}.small{font-size:12px;color:#8f99aa}@media(max-width:750px){.grid{grid-template-columns:1fr}.thumb{float:none;width:100%;height:auto;margin:0 0 12px}}
</style></head><body><div class="wrap">
<div class="card"><h1>Reddott Films — YouTube Manager</h1><p class="muted">Upload new videos and manage existing videos from the connected channel.</p><a href="/Reddott-films/youtube/dashboard.php" style="color:#fff">← Dashboard</a></div>
<?php if($notice):?><div class="card notice"><?=h($notice)?></div><?php endif;?><?php if($error):?><div class="card error"><?=h($error)?></div><?php endif;?>
<div class="grid"><div class="card"><h2>Upload video</h2><form method="post" enctype="multipart/form-data"><input type="hidden" name="csrf" value="<?=h($csrf)?>"><input type="hidden" name="action" value="upload"><div class="field"><label>Video file</label><input type="file" name="video" accept="video/*" required></div><div class="field"><label>Title</label><input name="upload_title" maxlength="100" required></div><div class="field"><label>Description</label><textarea name="upload_description"></textarea></div><div class="field"><label>Privacy</label><select name="upload_privacy"><option value="private">Private</option><option value="unlisted">Unlisted</option><option value="public">Public</option></select></div><button class="btn">Upload to YouTube</button></form><p class="small">The hosting server must allow uploads of the selected file size.</p></div>
<div class="card"><h2>What this manager controls</h2><p class="muted">Existing videos can be edited or deleted. New videos can be uploaded with title, description and privacy status.</p><p class="muted">Tokens remain server-side; the OAuth client secret is not stored in this file.</p></div></div>
<div class="card"><h2>Existing videos</h2><?php if(!$videos):?><p class="muted">No videos returned.</p><?php endif;?><?php foreach($videos as $v): $id=(string)($v['id']['videoId']??'');$s=$v['snippet']??[];$title=(string)($s['title']??'Untitled');$desc=(string)($s['description']??'');$thumb=(string)($s['thumbnails']['medium']['url']??$s['thumbnails']['default']['url']??'');?><div class="video"><?php if($thumb!==''):?><img class="thumb" src="<?=h($thumb)?>" alt=""><?php endif;?><form method="post"><input type="hidden" name="csrf" value="<?=h($csrf)?>"><input type="hidden" name="action" value="update"><input type="hidden" name="video_id" value="<?=h($id)?>"><div class="field"><label>Title</label><input name="title" maxlength="100" value="<?=h($title)?>" required></div><div class="field"><label>Description</label><textarea name="description"><?=h($desc)?></textarea></div><div class="field"><label>Privacy</label><select name="privacyStatus"><option value="private">Private</option><option value="unlisted">Unlisted</option><option value="public">Public</option></select></div><button class="btn">Save changes</button></form><div class="actions"><form method="post" onsubmit="return confirm('Delete this YouTube video permanently?');"><input type="hidden" name="csrf" value="<?=h($csrf)?>"><input type="hidden" name="action" value="delete"><input type="hidden" name="video_id" value="<?=h($id)?>"><button class="btn danger">Delete</button><?php if($id!==''):?><a href="https://www.youtube.com/watch?v=<?=rawurlencode($id)?>" target="_blank" rel="noopener" style="color:#fff;margin-left:10px">Open on YouTube</a><?php endif;?></form></div><div class="small">Video ID: <?=h($id)?></div></div><?php endforeach;?></div>
</div></body></html>

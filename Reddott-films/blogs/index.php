<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/youtube/lib.php';

function h(string $v): string { return htmlspecialchars($v, ENT_QUOTES, 'UTF-8'); }
function yt(string $url, string $token): array {
    $raw = reddottYoutubeHttpGet($url, $token);
    $data = json_decode($raw, true);
    if (!is_array($data)) throw new RuntimeException('Invalid YouTube API response.');
    return $data;
}
function slug(string $title): string {
    $title = strtolower(trim($title));
    $title = preg_replace('/[^a-z0-9]+/', '-', $title) ?? '';
    return trim($title, '-') ?: 'video';
}
function videos(): array {
    $connection = reddottYoutubeConnection();
    if (!$connection) throw new RuntimeException('YouTube is not connected.');
    $token = reddottYoutubeAccessToken();
    $channel = yt('https://www.googleapis.com/youtube/v3/channels?' . http_build_query([
        'part'=>'contentDetails','id'=>$connection['channel_id']
    ], '', '&', PHP_QUERY_RFC3986), $token);
    $uploads = (string)($channel['items'][0]['contentDetails']['relatedPlaylists']['uploads'] ?? '');
    if ($uploads === '') return [];
    $all=[]; $page='';
    do {
        $params=['part'=>'snippet,contentDetails','playlistId'=>$uploads,'maxResults'=>50];
        if ($page!=='') $params['pageToken']=$page;
        $data=yt('https://www.googleapis.com/youtube/v3/playlistItems?'.http_build_query($params,'','&',PHP_QUERY_RFC3986),$token);
        foreach (($data['items'] ?? []) as $item) {
            $id=(string)($item['contentDetails']['videoId'] ?? '');
            if ($id==='') continue;
            $s=$item['snippet'] ?? [];
            $all[]=[
                'id'=>$id,
                'title'=>(string)($s['title'] ?? 'Untitled video'),
                'description'=>(string)($s['description'] ?? ''),
                'published'=>(string)($s['publishedAt'] ?? ''),
                'thumb'=>(string)($s['thumbnails']['high']['url'] ?? $s['thumbnails']['medium']['url'] ?? ''),
            ];
        }
        $page=(string)($data['nextPageToken'] ?? '');
    } while ($page!=='' && count($all)<500);
    return $all;
}
$error=''; $items=[];
try { $items=videos(); } catch(Throwable $e) { error_log('Reddott Films blogs index: '.$e->getMessage()); $error=$e->getMessage(); }
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Reddott Films Blog — Video Stories & Insights</title><meta name="description" content="Reddott Films video companion articles, behind-the-video notes and useful context from the official channel."><style>body{margin:0;background:#0b0d12;color:#f5f7fb;font-family:Arial,sans-serif}.wrap{width:min(1100px,calc(100% - 32px));margin:36px auto}.hero,.card{background:#151922;border:1px solid #2a3140;border-radius:18px;padding:24px;margin-bottom:18px}.muted{color:#aeb7c6;line-height:1.65}.grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:16px}.post{background:#11151d;border:1px solid #2a3140;border-radius:16px;overflow:hidden}.post img{width:100%;aspect-ratio:16/9;object-fit:cover}.post .body{padding:18px}.post h2{font-size:20px;line-height:1.35;margin:0 0 10px}.post a{color:#fff;text-decoration:none}.meta{font-size:12px;color:#8994a7;margin-bottom:12px}.btn{display:inline-block;background:#e11d48;color:#fff;padding:10px 14px;border-radius:9px;margin-top:8px}.error{border-color:#8b1e3f;color:#ffd5df}footer{color:#8994a7;font-size:13px;padding:20px 0}footer a{color:#fff;margin-right:14px}</style></head><body><main class="wrap"><section class="hero"><h1>Reddott Films Blog</h1><p class="muted">A companion space for the official Reddott Films YouTube channel. Each article is tied to a real video and adds context rather than simply reproducing the video page.</p><a class="btn" href="/reddott-films.php">Reddott Films</a></section><?php if($error):?><div class="card error">Unable to load the current YouTube library. Please try again later.</div><?php endif;?><section class="grid"><?php foreach($items as $v): $desc=trim($v['description']); $quality=strlen($desc)>=240; if(!$quality) continue; $url='/Reddott-films/blogs/'.rawurlencode(slug($v['title'])).'/'.rawurlencode($v['id']).'/';?><article class="post"><?php if($v['thumb']!==''):?><img src="<?=h($v['thumb'])?>" alt="<?=h($v['title'])?>" loading="lazy"><?php endif;?><div class="body"><div class="meta">Reddott Films · <?=h(substr($v['published'],0,10))?></div><h2><a href="<?=h($url)?>"><?=h($v['title'])?></a></h2><p class="muted"><?=h(mb_substr(preg_replace('/\s+/',' ',$desc) ?? '',0,180))?><?=mb_strlen($desc)>180?'…':''?></p><a class="btn" href="<?=h($url)?>">Read the article</a></div></article><?php endforeach;?></section><footer><a href="/privacy-policy.php">Privacy</a><a href="/terms.php">Terms</a><a href="/reddott-films.php">Reddott Films</a><p>Low-information video records are intentionally not listed as articles until they have enough original editorial context.</p></footer></main></body></html>

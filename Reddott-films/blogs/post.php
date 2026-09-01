<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/youtube/lib.php';

function h(string $v): string { return htmlspecialchars($v, ENT_QUOTES, 'UTF-8'); }
function yt(string $url, string $token): array {
    $d = json_decode(reddottYoutubeHttpGet($url, $token), true);
    if (!is_array($d)) throw new RuntimeException('Invalid YouTube API response.');
    return $d;
}
function clean(string $v): string { return trim(preg_replace('/\s+/',' ',$v) ?? ''); }
function slug(string $title): string {
    $s = strtolower(trim($title));
    $s = preg_replace('/[^a-z0-9]+/', '-', $s) ?? '';
    return trim($s, '-') ?: 'video';
}
function sentences(string $text): array {
    $parts = preg_split('/(?<=[.!?])\s+/', trim($text)) ?: [];
    return array_values(array_filter(array_map('clean', $parts), static fn($v) => $v !== ''));
}
function topicFor(string $title, string $description): string {
    $lower = strtolower($title.' '.$description);
    if (str_contains($lower,'animation') || str_contains($lower,'animated') || str_contains($lower,'fantasy')) return 'animation and visual storytelling';
    if (str_contains($lower,'tutorial') || str_contains($lower,'how to') || str_contains($lower,'guide') || str_contains($lower,'tool')) return 'practical learning and how-to content';
    if (str_contains($lower,'music') || str_contains($lower,'song') || str_contains($lower,'audio')) return 'music and performance';
    if (str_contains($lower,'story') || str_contains($lower,'film') || str_contains($lower,'short film') || str_contains($lower,'movie')) return 'storytelling and filmmaking';
    return 'video storytelling and creator content';
}
function keywordList(string $title, string $description): array {
    $text = strtolower($title.' '.$description);
    $words = preg_split('/[^a-z0-9]+/', $text) ?: [];
    $stop = array_flip(['about','after','again','also','and','are','because','been','being','before','between','could','from','have','into','more','most','only','other','over','that','their','there','these','this','through','title','video','videos','watch','what','when','where','which','while','with','would','your','you','the','for','has','how','its','our','was','will','not','but','can','who','why','than','then','they','them','his','her','she','him','out','use','using','get','just','new']);
    $freq = [];
    foreach ($words as $word) {
        if (strlen($word) < 4 || isset($stop[$word])) continue;
        $freq[$word] = ($freq[$word] ?? 0) + 1;
    }
    arsort($freq);
    return array_slice(array_keys($freq), 0, 8);
}

$id = trim((string)($_GET['id'] ?? ''));
$video = null;
$error = '';
try {
    if (!preg_match('/^[A-Za-z0-9_-]{6,}$/', $id)) throw new RuntimeException('Invalid video.');
    $connection = reddottYoutubeConnection();
    if (!$connection) throw new RuntimeException('YouTube is not connected.');
    $token = reddottYoutubeAccessToken();
    $data = yt('https://www.googleapis.com/youtube/v3/videos?'.http_build_query([
        'part' => 'snippet,status', 'id' => $id
    ], '', '&', PHP_QUERY_RFC3986), $token);
    $video = $data['items'][0] ?? null;
    if (!$video) throw new RuntimeException('Video not found.');
} catch (Throwable $e) {
    error_log('Reddott Films blog post: '.$e->getMessage());
    $error = 'This article is temporarily unavailable.';
}

if (!$video) {
    http_response_code(404);
    ?><!doctype html><html><head><meta charset="utf-8"><title>Article unavailable — Reddott Films</title></head><body><p><?=h($error)?></p><p><a href="/Reddott-films/blogs/">Back to Reddott Films Blog</a></p></body></html><?php
    exit;
}

$s = $video['snippet'] ?? [];
$title = clean((string)($s['title'] ?? 'Reddott Films video'));
$description = clean((string)($s['description'] ?? ''));
published = (string)($s['publishedAt'] ?? '');
$thumb = (string)($s['thumbnails']['maxres']['url'] ?? $s['thumbnails']['high']['url'] ?? $s['thumbnails']['medium']['url'] ?? '');
$indexable = strlen($description) >= 240;
$canonical = 'https://smarttoolz.in/Reddott-films/blogs/'.rawurlencode(slug($title)).'/'.rawurlencode($id).'/';
$topic = topicFor($title, $description);
$points = array_slice(sentences($description), 0, 6);
$keywords = keywordList($title, $description);
$summary = $points ? implode(' ', array_slice($points, 0, 2)) : 'A companion guide to the official Reddott Films video, with useful context and a clear path to the original source.';
$metaDescription = mb_substr(clean($summary), 0, 155);
if ($metaDescription === '') $metaDescription = 'Read the Reddott Films companion article for this video, with useful context, key points and the original YouTube source.';
$publishedDate = substr($published, 0, 10);

$schema = [
    '@context' => 'https://schema.org',
    '@type' => 'Article',
    'headline' => $title,
    'description' => $metaDescription,
    'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => $canonical],
    'datePublished' => $published,
    'dateModified' => $published,
    'author' => ['@type' => 'Organization', 'name' => 'Reddott Films', 'url' => 'https://smarttoolz.in/reddott-films.php'],
    'publisher' => ['@type' => 'Organization', 'name' => 'Reddott Films', 'url' => 'https://smarttoolz.in/reddott-films.php'],
];
$videoSchema = [
    '@context' => 'https://schema.org', '@type' => 'VideoObject', 'name' => $title,
    'description' => $description !== '' ? $description : $metaDescription,
    'thumbnailUrl' => $thumb !== '' ? [$thumb] : [], 'uploadDate' => $published,
    'embedUrl' => 'https://www.youtube.com/embed/'.$id, 'contentUrl' => 'https://www.youtube.com/watch?v='.$id,
];
$breadcrumb = [
    '@context' => 'https://schema.org', '@type' => 'BreadcrumbList',
    'itemListElement' => [
        ['@type'=>'ListItem','position'=>1,'name'=>'Reddott Films','item'=>'https://smarttoolz.in/reddott-films.php'],
        ['@type'=>'ListItem','position'=>2,'name'=>'Video Blog','item'=>'https://smarttoolz.in/Reddott-films/blogs/'],
        ['@type'=>'ListItem','position'=>3,'name'=>$title,'item'=>$canonical],
    ]
];
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?=h($title)?> — Explained & Key Points | Reddott Films</title>
<meta name="description" content="<?=h($metaDescription)?>">
<?php if (!$indexable): ?><meta name="robots" content="noindex,follow">
<?php else: ?><meta name="robots" content="index,follow,max-image-preview:large">
<?php endif; ?>
<link rel="canonical" href="<?=h($canonical)?>">
<meta property="og:type" content="article"><meta property="og:title" content="<?=h($title)?> — Reddott Films">
<meta property="og:description" content="<?=h($metaDescription)?>"><meta property="og:url" content="<?=h($canonical)?>">
<?php if ($thumb !== ''): ?><meta property="og:image" content="<?=h($thumb)?>"><?php endif; ?>
<script type="application/ld+json"><?=json_encode($schema, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT)?></script>
<script type="application/ld+json"><?=json_encode($videoSchema, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT)?></script>
<script type="application/ld+json"><?=json_encode($breadcrumb, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT)?></script>
<style>
body{margin:0;background:#0b0d12;color:#f5f7fb;font-family:Arial,sans-serif}.wrap{width:min(900px,calc(100% - 32px));margin:32px auto}.card{background:#151922;border:1px solid #2a3140;border-radius:18px;padding:24px;margin-bottom:18px}.muted{color:#aeb7c6;line-height:1.8}.eyebrow{font-size:13px;color:#e11d48;font-weight:700;text-transform:uppercase;letter-spacing:.08em}.article p,.article li{font-size:17px;line-height:1.85;color:#d9deea}.article h2{font-size:25px;margin-top:34px}.article h3{font-size:19px;margin-top:26px}.article ul{padding-left:24px}.video{position:relative;padding-top:56.25%;overflow:hidden;border-radius:14px;background:#000;margin:20px 0}.video iframe{position:absolute;inset:0;width:100%;height:100%;border:0}.btn{display:inline-block;background:#e11d48;color:#fff;text-decoration:none;padding:11px 16px;border-radius:10px}.tag{display:inline-block;border:1px solid #343d4e;border-radius:999px;padding:7px 10px;margin:4px 5px 0 0;color:#cbd3e0;font-size:13px}.callout{border-left:4px solid #e11d48;padding:14px 18px;background:#11151d;border-radius:8px}.source{font-size:13px;color:#8f99aa}.source a{color:#fff}.meta{font-size:13px;color:#8994a7}
</style>
</head>
<body>
<main class="wrap">
<section class="card">
<div class="eyebrow">Reddott Films · <?=h($topic)?></div>
<h1><?=h($title)?></h1>
<p class="muted">Published <?=h($publishedDate)?> · A researched companion page for the official Reddott Films YouTube video.</p>
<?php if ($keywords): ?><div><?php foreach ($keywords as $keyword): ?><span class="tag"><?=h($keyword)?></span><?php endforeach; ?></div><?php endif; ?>
</section>

<article class="card article">
<?php if ($thumb !== ''): ?><img src="<?=h($thumb)?>" alt="<?=h($title)?> — Reddott Films" style="width:100%;border-radius:14px;display:block;margin-bottom:20px" loading="eager"><?php endif; ?>
<div class="video"><iframe src="https://www.youtube.com/embed/<?=h($id)?>" title="<?=h($title)?>" loading="lazy" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe></div>

<h2><?=h($title)?> — overview</h2>
<p><?=h($summary)?></p>
<p>This page is built to answer the questions a viewer is likely to have before watching: what the video focuses on, what information is available from the creator, what to pay attention to, and where to find the original video. It adds readable editorial context instead of treating a YouTube embed as the entire article.</p>

<h2>What you can learn from this video</h2>
<p>The most reliable starting point is the creator-published information below. The points are presented as a reading guide, so readers can quickly understand the stated focus and then verify the visual, narrative or practical details by watching the complete video.</p>
<?php if ($points): ?><ul><?php foreach ($points as $p): ?><li><?=h($p)?></li><?php endforeach; ?></ul>
<?php else: ?><div class="callout"><strong>Watch for context:</strong> The public description contains limited detail, so this page does not invent claims that are not supported by the source.</div><?php endif; ?>

<?php if ($description !== ''): ?>
<h2>Creator-published description</h2>
<p><?=nl2br(h($description))?></p>
<?php endif; ?>

<h2>How to get more value from the video</h2>
<ol>
<li><strong>Start with the stated focus.</strong> Use the title and creator description as your viewing checklist rather than judging the video from its thumbnail alone.</li>
<li><strong>Watch the complete piece.</strong> Important context can depend on the sequence of scenes, demonstrations, dialogue, music or visual details.</li>
<li><strong>Compare the details with the published information.</strong> This helps separate what the creator actually presents from assumptions made from the title.</li>
<li><strong>Use the original YouTube page for the full experience.</strong> YouTube remains the primary source for the video, comments and channel-level information.</li>
</ol>

<h2>Who may find this useful?</h2>
<p>This companion page is most useful for readers interested in <?=h($topic)?> who want a quick text reference before or after watching. It can also help search visitors understand the video's stated subject without replacing the original creator's work.</p>

<h2>Key takeaways</h2>
<?php if ($points): ?>
<ul><?php foreach (array_slice($points,0,4) as $p): ?><li><?=h($p)?></li><?php endforeach; ?></ul>
<?php else: ?><p>The available source metadata is not detailed enough to publish specific takeaways responsibly. The original video is the best source for the missing context.</p><?php endif; ?>

<h2>Frequently asked questions</h2>
<h3>What is this Reddott Films page?</h3>
<p>It is an editorial companion to a real Reddott Films YouTube video. It organizes the published information and adds viewing context in a text-friendly format.</p>
<h3>Is the video available on YouTube?</h3>
<p>Yes. The original video is linked below, and the embedded player above loads the same YouTube video.</p>
<h3>Does this article replace the original video?</h3>
<p>No. The article is a companion reference. Visual, audio and narrative information should be verified by watching the original video.</p>

<p><a class="btn" href="https://www.youtube.com/watch?v=<?=rawurlencode($id)?>" target="_blank" rel="noopener">Watch the original on YouTube</a></p>
</article>

<div class="card source">
<strong>Editorial standard:</strong> This page separates creator-published information from site-added guidance and does not invent facts that are absent from the source. Short or low-information records remain out of search indexing until there is enough material for a genuinely useful article. Review content before placing advertising on it.
<br><br><a href="/Reddott-films/blogs/">← Back to Reddott Films Blog</a> · <a href="/privacy-policy.php">Privacy</a> · <a href="/terms.php">Terms</a>
</div>
</main>
</body>
</html>

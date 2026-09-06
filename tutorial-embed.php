<?php
// Render a tool-specific tutorial when its generated MP4 exists.
$path = (string)($_SERVER['REQUEST_URI'] ?? '');
$slug = basename(parse_url($path, PHP_URL_PATH) ?: '', '.php');
$videoMap = [
    'image-background-remover' => '/video-library/image-background-remover.mp4',
];
if (!isset($videoMap[$slug])) return;
$videoUrl = $videoMap[$slug];
?>
<section class="st-tutorial" aria-labelledby="st-tutorial-title">
  <div class="st-tutorial-inner">
    <div class="st-tutorial-head">
      <div>
        <span class="st-tutorial-kicker">VIDEO TUTORIAL</span>
        <h2 id="st-tutorial-title">How to use this tool</h2>
        <p>Watch the complete SmartToolz walkthrough with real screen actions and synced narration.</p>
      </div>
    </div>
    <video class="st-tutorial-video" controls preload="metadata" playsinline poster="/smart-toolz/assets/home/hero-tools.svg">
      <source src="<?=htmlspecialchars($videoUrl, ENT_QUOTES, 'UTF-8')?>" type="video/mp4">
      Your browser does not support HTML5 video.
    </video>
  </div>
</section>
<style>
.st-tutorial{width:min(1200px,calc(100% - 28px));margin:24px auto 34px}.st-tutorial-inner{background:#fff;border:1px solid #e6e8ee;border-radius:20px;padding:22px;box-shadow:0 14px 40px rgba(20,30,70,.05)}.st-tutorial-kicker{font-size:10px;font-weight:900;letter-spacing:1.4px;color:#635bff}.st-tutorial h2{margin:5px 0;font-size:24px}.st-tutorial p{margin:0;color:#7a8191;font-size:12px;line-height:1.55}.st-tutorial-video{display:block;width:100%;margin-top:18px;border-radius:14px;background:#0b1020;aspect-ratio:16/9}.st-tutorial-inner{overflow:hidden}@media(max-width:600px){.st-tutorial{width:calc(100% - 16px)}.st-tutorial-inner{padding:16px}.st-tutorial h2{font-size:20px}}
</style>

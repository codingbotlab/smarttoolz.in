<?php
/* Smart-Tooz Analytics navigation component */
$analyticsLinks = [
    ['href'=>'/analytics/','title'=>'Analytics Home','desc'=>'Main analytics dashboard and overview.','icon'=>'📊'],
    ['href'=>'/analytics/advanced.php','title'=>'Advanced Analytics','desc'=>'Deeper traffic, visitor, location and behaviour analytics.','icon'=>'🚀'],
    ['href'=>'/analytics/events.php','title'=>'Event Analytics','desc'=>'Tool opens, tool uses, downloads, page views and live interaction history.','icon'=>'⚡'],
    ['href'=>'/analytics/live.php','title'=>'Live API','desc'=>'JSON endpoint for current active visitors and event ingestion.','icon'=>'🟢'],
    ['href'=>'/analytics/realtime.js','title'=>'Realtime JS','desc'=>'Client-side realtime analytics refresh script.','icon'=>'⚡'],
    ['href'=>'/analytics/resolve-geo.php','title'=>'Resolve Geo','desc'=>'Resolve and backfill country/city from visitor IPs.','icon'=>'🌍'],
    ['href'=>'/analytics/tabs.css','title'=>'Tabs CSS','desc'=>'Analytics tabs styling.','icon'=>'🎨'],
    ['href'=>'/analytics/tabs.js','title'=>'Tabs JS','desc'=>'Analytics tab interaction logic.','icon'=>'🧩'],
    ['href'=>'/analytics/tracker.php','title'=>'Tracker','desc'=>'Server-side visitor and pageview tracking engine.','icon'=>'📡'],
];
?>
<nav class="analytics-links" aria-label="Analytics tools">
  <?php foreach ($analyticsLinks as $link): ?>
    <a class="analytics-link-card" href="<?= htmlspecialchars($link['href'], ENT_QUOTES, 'UTF-8') ?>">
      <span class="analytics-link-icon"><?= htmlspecialchars($link['icon'], ENT_QUOTES, 'UTF-8') ?></span>
      <span class="analytics-link-body"><strong><?= htmlspecialchars($link['title'], ENT_QUOTES, 'UTF-8') ?></strong><small><?= htmlspecialchars($link['desc'], ENT_QUOTES, 'UTF-8') ?></small></span>
      <span class="analytics-link-arrow">→</span>
    </a>
  <?php endforeach; ?>
</nav>
<style>.analytics-links{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px}.analytics-link-card{display:flex;align-items:center;gap:12px;padding:15px;background:#fff;border:1px solid #e2e6ed;border-radius:14px;color:#172033;text-decoration:none;transition:.18s ease}.analytics-link-card:hover{transform:translateY(-2px);border-color:#cfcaff;box-shadow:0 10px 25px rgba(30,35,80,.07)}.analytics-link-icon{width:42px;height:42px;flex:0 0 42px;display:grid;place-items:center;border-radius:11px;background:#f0efff;font-size:20px}.analytics-link-body{display:flex;flex-direction:column;gap:3px;min-width:0;flex:1}.analytics-link-body strong{font-size:13px}.analytics-link-body small{font-size:10px;line-height:1.45;color:#7b8493}.analytics-link-arrow{font-size:16px;color:#635bff;font-weight:900}@media(max-width:650px){.analytics-links{grid-template-columns:1fr}}</style>

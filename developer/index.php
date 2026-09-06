<?php
declare(strict_types=1);
?><!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="theme-color" content="#635bff">
<title>SmartToolz Developer Hub</title>
<link rel="stylesheet" href="/assets/css/smarttoolz.css">
<style>
.dev{width:min(1200px,calc(100% - 32px));margin:0 auto;padding:56px 0 80px}.dev-hero{padding:34px;border:1px solid #e5e8f0;border-radius:24px;background:#fff;box-shadow:0 18px 50px rgba(30,35,80,.08);margin-bottom:22px}.eyebrow{font-size:10px;font-weight:900;letter-spacing:1.4px;color:#635bff}.dev h1{font-size:clamp(34px,5vw,56px);letter-spacing:-2.5px;margin:8px 0}.muted{color:#707b8e;line-height:1.7}.dev-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:15px}.dev-card{display:block;padding:22px;border:1px solid #e5e8f0;border-radius:18px;background:#fff;color:#172033;transition:.2s}.dev-card:hover{transform:translateY(-4px);border-color:#cfcaff;box-shadow:0 18px 45px rgba(30,35,80,.09)}.dev-card code{display:inline-block;padding:6px 8px;border-radius:8px;background:#f2f1ff;color:#635bff;font:700 11px ui-monospace,monospace}.dev-card h2{font-size:17px;margin:14px 0 7px}.dev-card p{font-size:12px;margin:0 0 16px;color:#707b8e;line-height:1.6}.dev-card span{font-size:11px;font-weight:850;color:#635bff}.flow{margin-top:22px;padding:24px;border-radius:18px;background:#172033;color:#fff}.flow h2{margin:0 0 12px}.flow code{color:#c9c5ff;font:600 12px ui-monospace,monospace}.flow p{color:#c2c9d8;font-size:12px;line-height:1.7}@media(max-width:800px){.dev-grid{grid-template-columns:1fr 1fr}}@media(max-width:520px){.dev{width:min(100% - 24px,1200px);padding-top:32px}.dev-grid{grid-template-columns:1fr}}
</style>
</head>
<body>
<header class="site-header">
<a class="brand" href="/" aria-label="SmartToolz home"><span class="brand-mark">S</span><span>SmartToolz</span></a>
<nav class="top-nav" aria-label="Primary navigation"><a href="/smart-toolz/">Tools</a><a href="/knowledge-base/">Knowledge</a><a href="/learning-hub/">Learning</a><a href="/ai-social-media/">AI Social</a><a href="/analytics/">Analytics</a></nav>
<a class="nav-cta" href="/smart-toolz/tool.php">Open Tools <span>→</span></a>
</header>
<main class="dev">
<section class="dev-hero"><span class="eyebrow">SMARTTOOLZ / DEVELOPERS</span><h1>One clean map for the active codebase.</h1><p class="muted">This is the developer starting point. Each active module owns its application code, assets and APIs. Shared infrastructure stays predictable, and retired products are removed rather than left as dead routes.</p></section>
<section class="dev-grid">
<a class="dev-card" href="/smart-toolz/"><code>/smart-toolz/</code><h2>Core Tools</h2><p>Tool pages, registry, router, shared UI, APIs, admin and SaaS.</p><span>Open module →</span></a>
<a class="dev-card" href="/learning-hub/"><code>/learning-hub/</code><h2>Learning Hub</h2><p>Courses, lessons, practice, dashboard, APIs, components and curriculum.</p><span>Open module →</span></a>
<a class="dev-card" href="/knowledge-base/"><code>/knowledge-base/</code><h2>Knowledge Base</h2><p>Articles, guides, visuals and the knowledge presentation layer.</p><span>Open module →</span></a>
<a class="dev-card" href="/analytics/"><code>/analytics/</code><h2>Analytics</h2><p>Tracking, events, realtime views, geo resolution and analytics UI.</p><span>Open module →</span></a>
<a class="dev-card" href="/ai-social-media/"><code>/ai-social-media/</code><h2>AI Social World</h2><p>Experimental social conversations, bots and shared-world experiences.</p><span>Open module →</span></a>
<a class="dev-card" href="/keddy-bot/"><code>/keddy-bot/</code><h2>Keddy Bot</h2><p>Bot application and its supporting runtime code.</p><span>Open module →</span></a>
<a class="dev-card" href="/smart-toolz/admin/"><code>/smart-toolz/admin/</code><h2>Admin & SaaS</h2><p>Operational dashboards, accounts, usage, plans and platform controls.</p><span>Open admin →</span></a>
</section>
<section class="flow"><h2>Request flow</h2><p><code>User → Root Entry → Router → Active Module → API / Service → UI → Response</code></p><p>Global browser assets live in <code>/assets/</code>. Module-specific APIs live under the owning module's <code>api/</code>. Repository-wide maintenance belongs in <code>/scripts/</code>.</p></section>
</main>
<footer class="site-footer"><div><strong>SmartToolz</strong><span>Developer Hub</span></div><nav><a href="/">Home</a><a href="/smart-toolz/">Tools</a><a href="/knowledge-base/">Knowledge</a><a href="/learning-hub/">Learning</a><a href="/privacy-policy.php">Privacy</a></nav></footer>
<script src="/assets/js/smarttoolz.js" defer></script>
</body>
</html>

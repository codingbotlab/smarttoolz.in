<?php
// Shared SmartToolz footer.
$isSmartToolzMain = basename((string)($_SERVER['SCRIPT_NAME'] ?? '')) === 'index.php'
    && str_contains((string)($_SERVER['REQUEST_URI'] ?? ''), '/smart-toolz/');
?>
<link rel="stylesheet" href="/core/ui/app.css">
<script defer src="/core/ui/app.js"></script>
<nav class="st-app-footer" aria-label="SmartToolz navigation">
  <a href="/smart-toolz/" data-st-nav="home"><span>⌂</span><small>Home</small></a>
  <a href="/smart-toolz/tool.php" data-st-nav="tools"><span>▦</span><small>Tools</small></a>
  <a href="/creator-ai/" data-st-nav="creator"><span>✦</span><small>Creator AI</small></a>
  <a href="/learning-hub/" data-st-nav="learn"><span>◈</span><small>Learn</small></a>
  <a href="/knowledge-base/" data-st-nav="kb"><span>▤</span><small>Knowledge</small></a>
</nav>
<?php if ($isSmartToolzMain): ?>
<style>
@media (max-width: 820px) {
  .site-header { display: none !important; }
  .st-app-footer { display: none !important; }
  body { padding-bottom: 0 !important; }
}
</style>
<?php endif; ?>

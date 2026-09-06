<?php
declare(strict_types=1);
require __DIR__ . '/bootstrap.php';
?><!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Page Not Found | SmartToolz</title>
<meta name="description" content="The page you requested could not be found. Explore SmartToolz free online tools instead.">
<meta name="robots" content="noindex,follow">
<?php require __DIR__ . '/head.php'; ?>
</head>
<body>
<?php require __DIR__ . '/header.php'; ?>
<main class="error-page">
  <section class="error-card">
    <div class="error-code">404</div>
    <span class="eyebrow">PAGE NOT FOUND</span>
    <h1>Oops! This page took a wrong turn.</h1>
    <p>The page you're looking for doesn't exist, may have moved, or the link may be incorrect.</p>
    <div class="error-actions">
      <a class="primary" href="/">Go Home <span>→</span></a>
      <a class="secondary" href="/tools/">Browse All Tools</a>
    </div>
  </section>
  <section class="error-help">
    <div><span class="mini-icon">⌕</span><div><strong>Looking for a tool?</strong><p>Use the search bar above or browse the complete toolbox.</p></div></div>
    <a href="/tools/">Explore tools →</a>
  </section>
</main>
<style>
.error-page{width:min(920px,calc(100% - 32px));margin:0 auto 80px}.error-card{text-align:center;padding:92px 20px 70px}.error-code{font-size:clamp(90px,18vw,170px);font-weight:900;line-height:.8;letter-spacing:-10px;background:linear-gradient(90deg,#5d46ff,#3e7dff);-webkit-background-clip:text;background-clip:text;color:transparent;margin-bottom:30px}.eyebrow{display:inline-block;color:#5b43ff;font-size:10px;font-weight:900;letter-spacing:1.8px}.error-card h1{max-width:680px;margin:15px auto 10px;font-size:clamp(32px,5vw,48px);line-height:1.05;letter-spacing:-2px}.error-card p{max-width:580px;margin:0 auto;color:#667085;font-size:14px;line-height:1.75}.error-actions{display:flex;justify-content:center;gap:10px;margin-top:25px}.error-actions a{padding:12px 17px;border-radius:11px;text-decoration:none;font-size:11px;font-weight:900}.error-actions .primary{background:linear-gradient(90deg,#5d46ff,#3e7dff);color:#fff}.error-actions .primary span{margin-left:6px}.error-actions .secondary{border:1px solid #dfe3eb;background:#fff;color:#344054}.error-actions .secondary:hover{border-color:#a9a0ff;color:#5541ff;background:#f7f6ff}.error-help{display:flex;align-items:center;justify-content:space-between;gap:20px;padding:22px 25px;border:1px solid #e7eaf0;border-radius:18px;background:#fff;box-shadow:0 12px 35px rgba(16,24,40,.05)}.error-help>div{display:flex;align-items:center;gap:13px}.mini-icon{display:grid;place-items:center;width:40px;height:40px;border-radius:11px;background:#eeeaff;color:#5b43ff;font-size:20px}.error-help strong{font-size:13px}.error-help p{margin:4px 0 0;color:#667085;font-size:11px}.error-help>a{color:#5541ff;text-decoration:none;font-size:11px;font-weight:900;white-space:nowrap}@media(max-width:600px){.error-page{width:calc(100% - 20px)}.error-card{padding:65px 8px 48px}.error-code{letter-spacing:-6px}.error-actions{flex-direction:column}.error-actions a{width:100%}.error-help{align-items:flex-start;flex-direction:column}.error-help>a{padding-left:53px}}
</style>
<?php require __DIR__ . '/footer.php'; ?>
</body>
</html>

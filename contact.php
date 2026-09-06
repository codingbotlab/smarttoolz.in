<?php
declare(strict_types=1);
require __DIR__ . '/bootstrap.php';
require __DIR__ . '/head.php';
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Contact SmartToolz | Get in Touch</title>
<meta name="description" content="Contact SmartToolz for feedback, suggestions, tool issues or general questions. We would love to hear from you.">
<meta name="robots" content="index,follow,max-image-preview:large">
<link rel="canonical" href="https://smarttoolz.in/contact.php">
</head>
<body>
<?php require __DIR__ . '/header.php'; ?>

<main class="contact-page">
  <section class="contact-hero">
    <span class="eyebrow">CONTACT SMARTTOOLZ</span>
    <h1>Let's talk.</h1>
    <p>Found a problem, have an idea for a new tool, or simply want to say hello? Send us a message.</p>
  </section>

  <section class="contact-layout">
    <aside class="contact-info">
      <div class="info-card featured">
        <span class="card-icon">✦</span>
        <h2>We'd love to hear from you</h2>
        <p>SmartToolz is built to make everyday digital tasks simpler. Your feedback helps us improve existing tools and decide what to build next.</p>
      </div>
      <div class="info-card">
        <span class="card-icon">💡</span>
        <h3>Suggest a tool</h3>
        <p>Tell us what small task you would like to solve online.</p>
      </div>
      <div class="info-card">
        <span class="card-icon">🐞</span>
        <h3>Report a problem</h3>
        <p>Include the tool name and what went wrong so we can investigate.</p>
      </div>
    </aside>

    <section class="contact-form-card">
      <form id="contactForm" action="" method="post" novalidate>
        <div class="field-row">
          <label>Name <input id="name" name="name" type="text" autocomplete="name" placeholder="Your name"></label>
          <label>Email <input id="email" name="email" type="email" autocomplete="email" placeholder="you@example.com"></label>
        </div>
        <label>Subject
          <select id="subject" name="subject">
            <option value="General question">General question</option>
            <option value="Tool suggestion">Tool suggestion</option>
            <option value="Report a problem">Report a problem</option>
            <option value="Feedback">Feedback</option>
          </select>
        </label>
        <label>Message
          <textarea id="message" name="message" rows="8" placeholder="How can we help?"></textarea>
        </label>
        <button class="send-button" type="submit">Send Message <span>→</span></button>
        <p class="form-status" id="formStatus" aria-live="polite"></p>
      </form>
    </section>
  </section>

  <section class="contact-note">
    <span class="eyebrow">BEFORE YOU MESSAGE</span>
    <h2>Need help with a tool?</h2>
    <p>Please mention the exact tool URL or tool name and describe the issue briefly. This makes it much easier to understand your request.</p>
  </section>
</main>

<style>
.contact-page{width:min(1040px,calc(100% - 32px));margin:0 auto 80px}.contact-hero{text-align:center;padding:68px 0 42px}.eyebrow{display:inline-block;color:#5b43ff;font-size:10px;font-weight:900;letter-spacing:1.8px}.contact-hero h1{margin:16px auto 12px;font-size:clamp(44px,7vw,68px);line-height:.98;letter-spacing:-3.5px}.contact-hero p{max-width:690px;margin:0 auto;color:#667085;font-size:16px;line-height:1.7}.contact-layout{display:grid;grid-template-columns:1fr 1.45fr;gap:16px;align-items:start}.contact-info{display:grid;gap:16px}.info-card,.contact-form-card,.contact-note{border:1px solid #e7eaf0;border-radius:20px;background:#fff;box-shadow:0 12px 35px rgba(16,24,40,.05)}.info-card{padding:24px}.info-card.featured{background:linear-gradient(145deg,#f7f5ff,#fff)}.card-icon{display:grid;place-items:center;width:42px;height:42px;margin-bottom:18px;border-radius:12px;background:#eeeaff;color:#5b43ff;font-size:17px}.info-card h2{margin:0 0 9px;font-size:21px;letter-spacing:-.6px}.info-card h3{margin:0 0 6px;font-size:16px}.info-card p{margin:0;color:#667085;font-size:12px;line-height:1.75}.contact-form-card{padding:28px}.contact-form-card form{display:grid;gap:16px}.field-row{display:grid;grid-template-columns:1fr 1fr;gap:13px}.contact-form-card label{display:grid;gap:7px;color:#344054;font-size:11px;font-weight:800}.contact-form-card input,.contact-form-card select,.contact-form-card textarea{width:100%;border:1px solid #dfe3eb;border-radius:11px;outline:0;background:#fcfdff;color:#172033;padding:12px 13px;font:13px Arial,sans-serif}.contact-form-card textarea{resize:vertical;line-height:1.6}.contact-form-card input:focus,.contact-form-card select:focus,.contact-form-card textarea:focus{border-color:#8d80ff;box-shadow:0 0 0 3px rgba(92,70,255,.08)}.send-button{height:48px;border:0;border-radius:12px;background:linear-gradient(90deg,#5d46ff,#3e7dff);color:#fff;font-size:12px;font-weight:900;cursor:pointer}.send-button span{margin-left:7px}.form-status{min-height:15px;margin:0;text-align:center;color:#5d46ff;font-size:10px}.contact-note{display:grid;grid-template-columns:1fr 1fr;gap:30px;margin-top:16px;padding:26px 28px}.contact-note h2{margin:9px 0 0;font-size:24px;letter-spacing:-.8px}.contact-note p{margin:0;color:#667085;font-size:12px;line-height:1.8}@media(max-width:780px){.contact-layout{grid-template-columns:1fr}.contact-note{grid-template-columns:1fr;gap:12px}}@media(max-width:600px){.contact-page{width:calc(100% - 20px)}.contact-hero{padding:48px 0 30px}.contact-hero h1{letter-spacing:-2.3px}.field-row{grid-template-columns:1fr}.contact-form-card{padding:19px}.contact-note{padding:23px}.contact-info{gap:12px}}
</style>

<script>
(()=>{const form=document.getElementById('contactForm'),status=document.getElementById('formStatus');form.addEventListener('submit',e=>{e.preventDefault();const name=document.getElementById('name').value.trim(),email=document.getElementById('email').value.trim(),message=document.getElementById('message').value.trim();if(!name||!email||!message){status.textContent='Please fill in your name, email and message.';return}if(!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)){status.textContent='Please enter a valid email address.';return}status.textContent='Thanks! Your message is ready to be sent. Please connect this form to your preferred email endpoint to receive submissions.'});})();
</script>
<?php require __DIR__ . '/footer.php'; ?>
</body>
</html>

<?php
declare(strict_types=1);
if(session_status()!==PHP_SESSION_ACTIVE)session_start();
$isLoggedIn=!empty($_SESSION['user_id']);
$displayName=(string)($_SESSION['user_name']??'');
$displayAvatar=(string)($_SESSION['user_avatar']??'');
$initial=strtoupper(substr(trim($displayName?:'U'),0,1));
$headerCredits=null;
$isAdmin=false;
$trialRemaining=null;
$trialUsed=0;
$trialLimit=5;

$script=basename((string)($_SERVER['SCRIPT_NAME']??''));
$isHome=in_array($script,['index.php','home.php'],true);
$isAccount=$script==='account.php';
$isAllTools=$script==='tool.php';
$isToolPage=!$isHome&&!$isAccount&&!$isAllTools&&str_contains((string)($_SERVER['SCRIPT_NAME']??''),'/tools/');
$slug=basename($script,'.php');
$toolTitle=ucwords(str_replace(['-','_'],' ',strtolower($slug?:'Online Tool')));

if($isLoggedIn){
    try{
        require_once $_SERVER['DOCUMENT_ROOT'].'/smart-toolz/saas/bootstrap.php';
        $hs=saas_db()->prepare('SELECT credits,role FROM creator_users WHERE id=? LIMIT 1');
        $hs->execute([(int)$_SESSION['user_id']]);
        if($hr=$hs->fetch(PDO::FETCH_ASSOC)){
            $headerCredits=(int)$hr['credits'];
            $isAdmin=in_array((string)($hr['role']??'user'),['admin','superadmin'],true);
        }
    }catch(Throwable $e){$headerCredits=null;}

    if($isToolPage){
        try{
            require_once $_SERVER['DOCUMENT_ROOT'].'/smart-toolz/lib/trials.php';
            $ts=smarttoolz_trial_status((int)$_SESSION['user_id'],$slug);
            $trialRemaining=(int)$ts['remaining'];
            $trialUsed=(int)$ts['used'];
            $trialLimit=(int)$ts['limit'];
        }catch(Throwable $e){$trialRemaining=5;}
    }
}
?>
<header class="site-header"><nav class="navbar"><a class="logo" href="/smart-toolz/"><span class="logo-icon">🛠️</span><span>SmartToolz</span></a><button class="menu-button" type="button" aria-label="Menu" aria-expanded="false">☰</button><div class="nav-links"><a href="/smart-toolz/">Home</a><a href="/smart-toolz/tool.php">All Tools</a><?php if($isLoggedIn): ?><?php if($isAdmin): ?><a class="admin-link" href="/smart-toolz/admin/">Admin</a><?php endif; ?><?php if($isToolPage): ?><span class="trial-pill" id="trialPill" title="5 free uses for this tool">🎁 <b id="trialRemaining"><?= $trialRemaining===null?'5':$trialRemaining ?></b>/<?= $trialLimit ?> free trials</span><?php endif; ?><button class="notify-btn" id="notifyBtn" type="button" aria-label="Notifications">🔔<b id="notifyCount" hidden>0</b></button><span class="credit-pill" title="1 generation/use = 1 credit for Creator AI">⚡ <b id="headerCredits"><?= $headerCredits===null?'—':number_format($headerCredits) ?></b> credits</span><a class="nav-user" href="/smart-toolz/account.php"><?php if($displayAvatar!==''): ?><img src="<?=htmlspecialchars($displayAvatar,ENT_QUOTES,'UTF-8')?>" alt=""><?php else: ?><span><?=htmlspecialchars($initial,ENT_QUOTES,'UTF-8')?></span><?php endif; ?><strong><?=htmlspecialchars($displayName?:'Account',ENT_QUOTES,'UTF-8')?></strong></a><a href="/creator-ai/auth/logout.php">Logout</a><?php else: ?><a class="login-btn" href="/creator-ai/auth/google-login.php">Login with Google</a><?php endif; ?></div></nav><?php if($isLoggedIn): ?><div class="notification-panel" id="notificationPanel"><div class="notification-head"><strong>Activity</strong><button id="markAllRead" type="button">Mark all read</button></div><div id="notificationList"><div class="notification-empty">Loading…</div></div></div><?php endif; ?></header>
<?php if(!$isHome && !$isAccount && !$isAllTools): ?><section class="tool-seo-intro"><div><span class="tool-free-badge">FREE ONLINE TOOL</span><h2>Free Online <?=htmlspecialchars($toolTitle,ENT_QUOTES,'UTF-8')?></h2><p>SmartToolz <?=htmlspecialchars($toolTitle,ENT_QUOTES,'UTF-8')?> helps you complete this task quickly and easily. Use the tool online without unnecessary setup.</p></div><div class="how-use"><h3>How to Use</h3><ol><li>Enter, upload or select your data.</li><li>Choose your options and click the main action button.</li><li>Review the result and download or copy it.</li></ol><?php if($isToolPage&&$isLoggedIn): ?><div class="trial-note">🎁 <b id="seoTrialRemaining"><?= $trialRemaining===null?'5':$trialRemaining ?></b> of <?= $trialLimit ?> free trials left for this tool.</div><?php elseif($isToolPage): ?><div class="trial-note">🔐 Login to unlock <b>5 free trials</b> for this tool.</div><?php endif; ?></div></section><?php endif; ?>
<style>.site-header{position:sticky;top:0;z-index:1000;background:rgba(255,255,255,.96);backdrop-filter:blur(16px);border-bottom:1px solid #e7eaf1}.navbar{width:min(1400px,calc(100% - 28px));min-height:72px;margin:auto;display:flex;align-items:center;justify-content:space-between;gap:22px;position:relative}.logo{display:flex;align-items:center;gap:10px;color:#172033;font-size:21px;font-weight:850;text-decoration:none;white-space:nowrap}.logo-icon{width:42px;height:42px;display:grid;place-items:center;border-radius:13px;color:#fff;background:linear-gradient(135deg,#635bff,#916cff)}.nav-links{display:flex;align-items:center;gap:10px}.nav-links a{color:#596477;font-size:13px;font-weight:700;text-decoration:none}.nav-links a:hover{color:#635bff}.admin-link{color:#635bff!important}.login-btn{padding:10px 15px;border-radius:11px;color:#fff!important;background:#635bff}.credit-pill,.trial-pill{display:inline-flex;align-items:center;gap:5px;padding:8px 11px;border-radius:999px;font-size:12px;font-weight:800;white-space:nowrap}.credit-pill{border:1px solid #ddd9ff;background:#f5f3ff;color:#635bff}.trial-pill{border:1px solid #bfead3;background:#effcf5;color:#137a4b}.trial-pill.empty{border-color:#ffd0cc;background:#fff2f0;color:#c43b30}.notify-btn{position:relative;border:1px solid #e0e4ec;background:#fff;border-radius:11px;width:38px;height:38px;cursor:pointer;font-size:17px}.notify-btn b{position:absolute;right:-5px;top:-6px;min-width:18px;height:18px;padding:0 4px;display:grid;place-items:center;border-radius:999px;background:#e5484d;color:#fff;font-size:9px}.notification-panel{display:none;position:absolute;right:18px;top:67px;width:min(390px,calc(100% - 20px));background:#fff;border:1px solid #e3e7ef;border-radius:16px;box-shadow:0 18px 50px rgba(20,30,70,.18);overflow:hidden}.notification-panel.open{display:block}.notification-head{display:flex;align-items:center;justify-content:space-between;padding:14px 15px;border-bottom:1px solid #edf0f4}.notification-head button{border:0;background:transparent;color:#635bff;font-size:11px;font-weight:800;cursor:pointer}.notification-item{padding:12px 15px;border-bottom:1px solid #f0f2f5;cursor:pointer}.notification-item.unread{background:#f7f6ff}.notification-item strong{display:block;font-size:12px}.notification-item p{margin:4px 0;color:#697489;font-size:11px;line-height:1.5}.notification-item small{color:#98a0ae;font-size:10px}.notification-empty{padding:25px;text-align:center;color:#8791a2;font-size:12px}.nav-user{display:flex;align-items:center;gap:7px!important}.nav-user img,.nav-user>span{width:30px;height:30px;border-radius:50%;object-fit:cover}.nav-user>span{display:grid;place-items:center;background:#635bff;color:#fff;font-size:12px}.menu-button{display:none;border:0;background:transparent;font-size:27px;cursor:pointer}.tool-seo-intro{width:min(1400px,calc(100% - 30px));margin:24px auto 0;padding:24px 26px;display:grid;grid-template-columns:minmax(0,1.5fr) minmax(280px,1fr);gap:22px;background:#fff;border:1px solid #e5e9f0;border-radius:20px;box-shadow:0 10px 35px rgba(20,30,70,.045)}.tool-free-badge{display:inline-block;padding:6px 10px;border-radius:999px;background:#eeedff;color:#635bff;font-size:11px;font-weight:900;letter-spacing:.4px}.tool-seo-intro h2{margin:9px 0 7px;font-size:26px;line-height:1.15}.tool-seo-intro p{margin:0;color:#707b8e;font-size:13px;line-height:1.7}.how-use{padding:18px;background:#f7f8fc;border:1px solid #e8eaf1;border-radius:15px}.how-use h3{margin:0 0 8px;font-size:16px}.how-use ol{margin:0 0 10px;padding-left:20px;color:#596477;font-size:12px;line-height:1.8}.how-use small{color:#635bff;font-weight:800}.trial-note{padding:9px 11px;margin-top:10px;border-radius:10px;background:#fff;border:1px solid #dfe5ee;color:#5f6b7e;font-size:11px;font-weight:700}@media(max-width:900px){.tool-seo-intro{grid-template-columns:1fr}}@media(max-width:820px){.menu-button{display:block}.nav-links{display:none;position:absolute;top:64px;left:0;right:0;padding:12px;flex-direction:column;align-items:stretch;background:#fff;border:1px solid #e7eaf1;border-top:0;border-radius:0 0 16px 16px;box-shadow:0 18px 35px #141c321a}.nav-links.open{display:flex}.nav-links a,.credit-pill,.trial-pill,.notify-btn{margin:2px 0;padding:10px}.nav-user{justify-content:flex-start}.tool-seo-intro{width:calc(100% - 20px);padding:20px}.notification-panel{right:10px;top:64px}}</style>
<script>(()=>{const b=document.querySelector('.menu-button'),n=document.querySelector('.nav-links');if(b&&n)b.onclick=()=>{const o=n.classList.toggle('open');b.setAttribute('aria-expanded',o?'true':'false')};
const loggedIn=<?= $isLoggedIn?'true':'false' ?>,toolPage=<?= $isToolPage?'true':'false' ?>,toolSlug=<?=json_encode($slug)?>;
if(!loggedIn)return;
const notifyBtn=document.getElementById('notifyBtn'),notifyPanel=document.getElementById('notificationPanel'),notifyList=document.getElementById('notificationList'),notifyCount=document.getElementById('notifyCount');
async function loadNotifications(){try{const r=await fetch('/smart-toolz/api/notifications.php?action=list',{cache:'no-store'}),d=await r.json();if(!d.ok)return;if(notifyCount){notifyCount.textContent=d.unread||0;notifyCount.hidden=!(d.unread>0)}if(notifyList){if(!d.notifications?.length){notifyList.innerHTML='<div class="notification-empty">No activity yet.</div>'}else{notifyList.innerHTML=d.notifications.map(x=>`<div class="notification-item ${x.read_at?'':'unread'}" data-id="${x.id}"><strong>${esc(x.title)}</strong><p>${esc(x.message)}</p><small>${esc(x.created_at)}</small></div>`).join('');}}}catch(e){}}
function esc(s){return String(s??'').replace(/[&<>'"]/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;',"'":'&#39;','\"':'&quot;'}[c]))}
notifyBtn?.addEventListener('click',()=>{notifyPanel?.classList.toggle('open');loadNotifications()});
document.getElementById('markAllRead')?.addEventListener('click',async()=>{await fetch('/smart-toolz/api/notifications.php?action=read_all',{method:'POST'});loadNotifications()});
notifyList?.addEventListener('click',async e=>{const item=e.target.closest('.notification-item');if(!item)return;await fetch('/smart-toolz/api/notifications.php?action=read',{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:'id='+encodeURIComponent(item.dataset.id)});item.classList.remove('unread');loadNotifications()});
loadNotifications();setInterval(loadNotifications,15000);
if(toolPage){
 const remainingEl=document.getElementById('trialRemaining'),seoEl=document.getElementById('seoTrialRemaining'),pill=document.getElementById('trialPill');
 const setRemaining=v=>{if(remainingEl)remainingEl.textContent=v;if(seoEl)seoEl.textContent=v;if(pill)pill.classList.toggle('empty',Number(v)<=0)};
 async function consume(){const r=await fetch('/smart-toolz/api/trial.php?action=consume&tool='+encodeURIComponent(toolSlug),{method:'POST',cache:'no-store'});let d={};try{d=await r.json()}catch(e){}if(d.ok){setRemaining(d.remaining);loadNotifications();return d}if(d.reason==='login_required'){window.location.href='/creator-ai/auth/google-login.php?return='+encodeURIComponent(location.href);return null}if(d.reason==='trial_exhausted'||r.status===429||d.remaining===0){setRemaining(0);alert(d.message||'Your 5 free trials for this tool are finished.');return null}alert(d.message||'Unable to start this tool.');return null}
 let approved=false,busy=false;
 document.addEventListener('submit',async e=>{const form=e.target;if(!(form instanceof HTMLFormElement)||!form.closest('main'))return;if(form.dataset.trialApproved==='1'){form.dataset.trialApproved='';return}e.preventDefault();if(busy)return;busy=true;const d=await consume();if(d){form.dataset.trialApproved='1';form.requestSubmit()}busy=false},{capture:true});
 document.addEventListener('click',async e=>{const el=e.target.closest('main button, main input[type="submit"]');if(!el||el.closest('form')||el.dataset.trialApproved==='1')return;if(el.disabled)return;e.preventDefault();if(busy)return;busy=true;const d=await consume();if(d){el.dataset.trialApproved='1';el.click();el.dataset.trialApproved=''}busy=false},{capture:true});
}
})();</script>

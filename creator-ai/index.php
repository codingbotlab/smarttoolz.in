<?php
declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Creator AI - Learning Hub
|--------------------------------------------------------------------------
| Login/authentication remains compatible with the existing system.
| Put this file at:
| /creator-ai/index.php
|--------------------------------------------------------------------------
*/

require_once __DIR__ . '/auth/config.php';
requireLogin();

$userId = (int)($_SESSION['user_id'] ?? 0);

if ($userId <= 0) {
    header('Location: /creator-ai/login');
    exit;
}

$user = getCreatorUser($userId);

if (!$user) {
    session_unset();
    session_destroy();
    header('Location: /creator-ai/login');
    exit;
}

$name = (string)($user['name'] ?? $_SESSION['user_name'] ?? 'Creator');

$initial = strtoupper(substr(trim($name), 0, 1));
if ($initial === '') {
    $initial = 'C';
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Creator AI — Learning Hub</title>
<meta name="description" content="Creator AI Learning Hub — learn programming, practice, test and build projects.">

<link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Ctext y='.9em' font-size='90'%3E%F0%9F%8E%93%3C/text%3E%3C/svg%3E">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

<style>
:root{
    --bg:#07080d;
    --bg2:#0b0e16;
    --panel:#10141e;
    --panel2:#141a27;
    --panel3:#181f2e;
    --line:rgba(255,255,255,.075);
    --line2:rgba(255,255,255,.13);
    --text:#f5f7fb;
    --muted:#8f98aa;
    --muted2:#626b7d;
    --purple:#8b5cf6;
    --purple2:#a78bfa;
    --cyan:#22d3ee;
    --green:#34d399;
    --orange:#fb923c;
    --pink:#f472b6;
    --blue:#60a5fa;
    --yellow:#facc15;
    --shadow:0 25px 80px rgba(0,0,0,.42);
}

*{box-sizing:border-box}
html{scroll-behavior:smooth}
body{
    margin:0;
    min-height:100vh;
    color:var(--text);
    font-family:Inter,Arial,sans-serif;
    background:
        radial-gradient(circle at 70% -10%,rgba(139,92,246,.18),transparent 30%),
        radial-gradient(circle at 100% 35%,rgba(34,211,238,.07),transparent 25%),
        var(--bg);
}
button,input{font:inherit}
button{cursor:pointer}
a{color:inherit;text-decoration:none}

.app{min-height:100vh;display:flex}

.sidebar{
    width:260px;
    position:fixed;
    inset:0 auto 0 0;
    z-index:100;
    display:flex;
    flex-direction:column;
    padding:16px 12px;
    background:rgba(8,10,16,.96);
    border-right:1px solid var(--line);
    backdrop-filter:blur(25px);
}

.brand{
    display:flex;
    align-items:center;
    gap:10px;
    padding:7px 9px 22px;
    font-weight:900;
    font-size:19px;
    letter-spacing:-.8px;
}
.brand-icon{
    width:32px;height:32px;
    display:grid;place-items:center;
    border-radius:10px;
    background:linear-gradient(135deg,var(--purple),var(--cyan));
    box-shadow:0 10px 30px rgba(139,92,246,.25);
}
.brand span{color:var(--purple2)}

.side-section{
    color:var(--muted2);
    font-size:9px;
    font-weight:900;
    letter-spacing:1.4px;
    padding:16px 10px 7px;
}

.nav-item{
    display:flex;
    align-items:center;
    gap:11px;
    width:100%;
    padding:10px 11px;
    margin:2px 0;
    border:1px solid transparent;
    border-radius:10px;
    color:#9ea7b9;
    font-size:11px;
    font-weight:700;
    transition:.2s;
}
.nav-item:hover{background:#ffffff06;color:#fff}
.nav-item.active{
    color:#fff;
    border-color:rgba(139,92,246,.2);
    background:linear-gradient(90deg,rgba(139,92,246,.16),rgba(139,92,246,.035));
}
.nav-item i{font-size:14px;width:18px;text-align:center}

.side-bottom{
    margin-top:auto;
    padding-top:12px;
    border-top:1px solid var(--line);
}
.user{
    display:flex;align-items:center;gap:9px;padding:8px;
}
.avatar{
    width:32px;height:32px;flex:none;
    display:grid;place-items:center;
    border-radius:50%;
    color:#fff;font-size:10px;font-weight:900;
    background:linear-gradient(135deg,var(--purple),var(--cyan));
}
.user-info{min-width:0;flex:1}
.user-name{
    overflow:hidden;text-overflow:ellipsis;white-space:nowrap;
    font-size:10px;font-weight:800;
}
.user-label{margin-top:2px;color:var(--muted2);font-size:8px}
.logout{color:#727c90;font-size:16px}
.logout:hover{color:#fff}

.main{
    width:calc(100% - 260px);
    margin-left:260px;
    min-height:100vh;
}
.topbar{
    height:64px;
    position:sticky;
    top:0;
    z-index:80;
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding:0 26px;
    border-bottom:1px solid var(--line);
    background:rgba(7,8,13,.76);
    backdrop-filter:blur(22px);
}
.top-left{display:flex;align-items:center;gap:12px}
.mobile-menu{
    display:none;
    width:34px;height:34px;
    border:1px solid var(--line);
    border-radius:9px;
    color:#aeb5c6;background:#ffffff03;
}
.page-label{font-size:11px;font-weight:800;color:#aeb5c6}
.top-right{display:flex;align-items:center;gap:8px}
.top-btn{
    width:34px;height:34px;
    display:grid;place-items:center;
    border:1px solid var(--line);
    border-radius:9px;
    color:#9ba4b6;
    background:#ffffff03;
}
.top-btn:hover{color:#fff;background:#ffffff08}

.content{
    width:min(1320px,100%);
    margin:auto;
    padding:34px 30px 70px;
}

.hero{
    position:relative;
    overflow:hidden;
    padding:34px;
    border:1px solid var(--line2);
    border-radius:22px;
    background:
        radial-gradient(circle at 85% 10%,rgba(34,211,238,.10),transparent 30%),
        radial-gradient(circle at 65% 0%,rgba(139,92,246,.16),transparent 40%),
        linear-gradient(135deg,#111625,#0d111b);
    box-shadow:var(--shadow);
}
.hero:after{
    content:"";
    position:absolute;
    width:220px;height:220px;
    right:-70px;bottom:-120px;
    border-radius:50%;
    border:1px solid rgba(167,139,250,.15);
    box-shadow:0 0 0 35px rgba(167,139,250,.025),0 0 0 70px rgba(167,139,250,.018);
}
.eyebrow{
    display:inline-flex;align-items:center;gap:7px;
    color:#c4b5fd;
    font-size:9px;font-weight:900;
    letter-spacing:1.5px;
    text-transform:uppercase;
}
.hero h1{
    max-width:700px;
    margin:11px 0 8px;
    font-size:36px;
    line-height:1.12;
    letter-spacing:-1.8px;
}
.hero p{
    max-width:650px;
    margin:0;
    color:var(--muted);
    font-size:12px;
    line-height:1.8;
}
.hero-actions{display:flex;gap:8px;margin-top:22px;flex-wrap:wrap}
.primary{
    border:0;
    padding:11px 15px;
    border-radius:10px;
    color:#fff;
    font-size:10px;
    font-weight:850;
    background:linear-gradient(135deg,var(--purple),var(--cyan));
    box-shadow:0 12px 35px rgba(139,92,246,.18);
}
.secondary{
    border:1px solid var(--line2);
    padding:10px 14px;
    border-radius:10px;
    color:#c0c7d6;
    font-size:10px;
    font-weight:800;
    background:#ffffff04;
}
.secondary:hover{background:#ffffff08;color:#fff}

.section{margin-top:32px}
.section-head{
    display:flex;
    align-items:end;
    justify-content:space-between;
    gap:15px;
    margin-bottom:13px;
}
.section-title{font-size:17px;font-weight:900;letter-spacing:-.5px}
.section-sub{margin-top:4px;color:var(--muted2);font-size:9px}
.view-all{
    border:0;background:none;color:#a78bfa;
    font-size:9px;font-weight:800;
}
.view-all:hover{color:#fff}

.continue{
    display:grid;
    grid-template-columns:minmax(0,1fr) 250px;
    gap:14px;
}
.progress-card,.stats-card{
    border:1px solid var(--line);
    border-radius:16px;
    background:linear-gradient(135deg,#101520,#0d1119);
}
.progress-card{padding:20px}
.progress-top{display:flex;justify-content:space-between;gap:15px}
.course-mini{display:flex;gap:13px;align-items:center}
.course-icon{
    width:48px;height:48px;
    display:grid;place-items:center;
    border-radius:13px;
    font-size:22px;
    background:rgba(247,223,30,.1);
    border:1px solid rgba(247,223,30,.15);
}
.course-mini h3{margin:0;font-size:13px}
.course-mini p{margin:4px 0 0;color:var(--muted);font-size:9px}
.percent{font-size:17px;font-weight:900;color:#ddd6fe}
.progress{
    height:7px;
    margin-top:20px;
    overflow:hidden;
    border-radius:10px;
    background:#202637;
}
.progress > span{
    display:block;height:100%;
    width:64%;
    border-radius:inherit;
    background:linear-gradient(90deg,var(--purple),var(--cyan));
}
.progress-bottom{
    display:flex;justify-content:space-between;
    margin-top:9px;color:var(--muted2);font-size:8px;
}
.stats-card{display:grid;grid-template-columns:1fr 1fr}
.stat{
    padding:18px;
    border-right:1px solid var(--line);
}
.stat:nth-child(2){border-right:0}
.stat:nth-child(3),.stat:nth-child(4){border-top:1px solid var(--line)}
.stat:nth-child(4){border-right:0}
.stat-number{font-size:18px;font-weight:900}
.stat-label{margin-top:4px;color:var(--muted2);font-size:8px}

.languages{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:12px;
}
.lang{
    position:relative;
    overflow:hidden;
    min-height:155px;
    padding:18px;
    border:1px solid var(--line);
    border-radius:15px;
    background:linear-gradient(145deg,#111622,#0d1119);
    transition:.2s;
}
.lang:hover{
    transform:translateY(-2px);
    border-color:rgba(167,139,250,.26);
    background:#141a26;
}
.lang-icon{
    width:42px;height:42px;
    display:grid;place-items:center;
    border-radius:12px;
    font-size:20px;
    margin-bottom:14px;
}
.lang h3{margin:0;font-size:12px}
.lang p{margin:5px 0 0;color:var(--muted);font-size:8px;line-height:1.5}
.lang-meta{
    display:flex;justify-content:space-between;
    margin-top:15px;color:var(--muted2);font-size:8px;
}
.lang-progress{height:4px;margin-top:7px;background:#202637;border-radius:10px;overflow:hidden}
.lang-progress span{display:block;height:100%;width:0;background:linear-gradient(90deg,var(--purple),var(--cyan));border-radius:inherit}
.lang-badge{
    position:absolute;top:13px;right:13px;
    padding:4px 6px;border-radius:6px;
    color:#aeb5c7;background:#ffffff05;border:1px solid var(--line);
    font-size:7px;font-weight:800;
}

.tools{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:12px;
}
.tool{
    display:flex;align-items:center;gap:12px;
    padding:15px;
    border:1px solid var(--line);
    border-radius:14px;
    background:#ffffff02;
    transition:.2s;
}
.tool:hover{background:#ffffff06;border-color:rgba(167,139,250,.2)}
.tool-icon{
    width:40px;height:40px;flex:none;
    display:grid;place-items:center;
    border-radius:11px;
    font-size:17px;
    background:rgba(139,92,246,.09);
}
.tool h3{margin:0;font-size:10px}
.tool p{margin:4px 0 0;color:var(--muted2);font-size:8px}

.activity{
    display:grid;
    grid-template-columns:1.2fr .8fr;
    gap:14px;
}
.panel{
    border:1px solid var(--line);
    border-radius:16px;
    background:#ffffff02;
    overflow:hidden;
}
.panel-head{
    padding:16px 18px;
    border-bottom:1px solid var(--line);
    display:flex;justify-content:space-between;align-items:center;
}
.panel-head strong{font-size:11px}
.activity-row{
    display:flex;align-items:center;gap:11px;
    padding:13px 18px;
    border-bottom:1px solid var(--line);
}
.activity-row:last-child{border-bottom:0}
.activity-icon{
    width:31px;height:31px;
    display:grid;place-items:center;
    border-radius:9px;
    background:#ffffff05;color:#a78bfa;
}
.activity-info{flex:1;min-width:0}
.activity-info strong{display:block;font-size:9px}
.activity-info span{display:block;margin-top:3px;color:var(--muted2);font-size:8px}
.check{color:var(--green);font-size:14px}

.quick-grid{padding:14px;display:grid;grid-template-columns:1fr 1fr;gap:8px}
.quick{
    padding:13px;
    border:1px solid var(--line);
    border-radius:11px;
    background:#ffffff02;
}
.quick i{color:#a78bfa;font-size:14px}
.quick strong{display:block;margin-top:8px;font-size:9px}
.quick span{display:block;margin-top:3px;color:var(--muted2);font-size:7px;line-height:1.5}

.footer{
    padding:30px 0 5px;
    text-align:center;
    color:#4f586b;
    font-size:8px;
}

.search-overlay{
    display:none;
    position:fixed;inset:0;
    z-index:200;
    background:rgba(0,0,0,.65);
    backdrop-filter:blur(8px);
    align-items:flex-start;justify-content:center;
    padding-top:90px;
}
.search-overlay.show{display:flex}
.search-box{
    width:min(650px,calc(100% - 30px));
    padding:15px;
    border:1px solid var(--line2);
    border-radius:16px;
    background:#111621;
    box-shadow:var(--shadow);
}
.search-box input{
    width:100%;height:45px;
    border:1px solid var(--line);
    border-radius:10px;
    outline:0;
    padding:0 13px;
    color:#fff;background:#ffffff04;
    font-size:12px;
}
.search-results{margin-top:10px}
.search-result{
    display:flex;align-items:center;gap:10px;
    padding:11px;
    border-radius:9px;
    color:#c5ccda;
    font-size:10px;
}
.search-result:hover{background:#ffffff07;color:#fff}
.close-search{
    margin-top:10px;
    width:100%;
    border:1px solid var(--line);
    border-radius:9px;
    padding:9px;
    color:#929bad;background:#ffffff03;
    font-size:9px;
}

@media(max-width:1100px){
    .languages,.tools{grid-template-columns:repeat(3,1fr)}
    .continue{grid-template-columns:1fr}
}
@media(max-width:800px){
    .sidebar{
        transform:translateX(-100%);
        transition:.25s;
        box-shadow:25px 0 80px rgba(0,0,0,.5);
    }
    .sidebar.open{transform:none}
    .main{width:100%;margin-left:0}
    .mobile-menu{display:grid;place-items:center}
    .topbar{padding:0 14px}
    .content{padding:20px 14px 50px}
    .hero{padding:24px 20px}
    .hero h1{font-size:28px}
    .languages,.tools{grid-template-columns:repeat(2,1fr)}
    .activity{grid-template-columns:1fr}
}
@media(max-width:500px){
    .languages,.tools{grid-template-columns:1fr}
    .hero h1{font-size:24px}
    .hero p{font-size:10px}
    .stats-card{grid-template-columns:1fr 1fr}
    .top-label{display:none}
}
</style>
</head>

<body>

<div class="app">

<aside class="sidebar" id="sidebar">
    <a class="brand" href="/creator-ai/">
        <div class="brand-icon"><i class="bi bi-stars"></i></div>
        <div><span>Creator</span> AI</div>
    </a>

    <div class="side-section">LEARNING</div>

    <a class="nav-item active" href="/creator-ai/">
        <i class="bi bi-mortarboard-fill"></i>
        Learning Hub
    </a>

    <a class="nav-item" href="#languages">
        <i class="bi bi-code-slash"></i>
        Programming
    </a>

    <a class="nav-item" href="#courses">
        <i class="bi bi-journal-bookmark-fill"></i>
        My Courses
    </a>

    <a class="nav-item" href="#activity">
        <i class="bi bi-bar-chart-fill"></i>
        My Progress
    </a>

    <div class="side-section">PRACTICE</div>

    <a class="nav-item" href="#tools">
        <i class="bi bi-lightning-charge-fill"></i>
        Practice Tests
    </a>

    <a class="nav-item" href="#tools">
        <i class="bi bi-patch-question-fill"></i>
        Quizzes
    </a>

    <a class="nav-item" href="#tools">
        <i class="bi bi-trophy-fill"></i>
        Mock Tests
    </a>

    <div class="side-section">ACCOUNT</div>

    <a class="nav-item" href="#activity">
        <i class="bi bi-bookmark-fill"></i>
        Bookmarks
    </a>

    <a class="nav-item" href="#activity">
        <i class="bi bi-heart-fill"></i>
        Liked Lessons
    </a>

    <div class="side-bottom">
        <div class="user">
            <div class="avatar"><?= htmlspecialchars($initial, ENT_QUOTES, 'UTF-8') ?></div>
            <div class="user-info">
                <div class="user-name"><?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?></div>
                <div class="user-label">Creator account</div>
            </div>
            <a class="logout" href="/creator-ai/logout" title="Logout">
                <i class="bi bi-box-arrow-right"></i>
            </a>
        </div>
    </div>
</aside>

<main class="main">

<header class="topbar">
    <div class="top-left">
        <button class="mobile-menu" id="mobileMenu" type="button">
            <i class="bi bi-list"></i>
        </button>
        <div class="page-label">Learning Hub</div>
    </div>

    <div class="top-right">
        <button class="top-btn" id="searchBtn" title="Search">
            <i class="bi bi-search"></i>
        </button>
        <a class="top-btn" href="#activity" title="Progress">
            <i class="bi bi-graph-up-arrow"></i>
        </a>
        <a class="top-btn" href="/creator-ai/logout" title="Logout">
            <i class="bi bi-person"></i>
        </a>
    </div>
</header>

<div class="content">

<section class="hero">
    <div class="eyebrow">
        <i class="bi bi-stars"></i>
        CREATOR AI LEARNING HUB
    </div>

    <h1>Learn programming. Practice. Test. Build.</h1>

    <p>
        Master programming languages from beginner to advanced with structured
        tutorials, courses, quizzes, mock tests, practice and real projects —
        all in one place.
    </p>

    <div class="hero-actions">
        <a class="primary" href="#languages">
            <i class="bi bi-play-fill"></i>
            Start Learning
        </a>
        <a class="secondary" href="#courses">
            <i class="bi bi-journal-text"></i>
            Explore Courses
        </a>
    </div>
</section>

<section class="section" id="courses">
    <div class="section-head">
        <div>
            <div class="section-title">Continue Learning</div>
            <div class="section-sub">Pick up where you left off</div>
        </div>
        <button class="view-all" type="button" onclick="toast('Course library will be available here.')">View all</button>
    </div>

    <div class="continue">
        <div class="progress-card">
            <div class="progress-top">
                <div class="course-mini">
                    <div class="course-icon">🌐</div>
                    <div>
                        <h3>HTML Fundamentals</h3>
                        <p>Chapter 4 · Semantic HTML</p>
                    </div>
                </div>
                <div class="percent">64%</div>
            </div>

            <div class="progress"><span></span></div>

            <div class="progress-bottom">
                <span>16 of 25 lessons completed</span>
                <span>Next: Forms & Validation</span>
            </div>
        </div>

        <div class="stats-card">
            <div class="stat">
                <div class="stat-number">16</div>
                <div class="stat-label">Lessons done</div>
            </div>
            <div class="stat">
                <div class="stat-number">8</div>
                <div class="stat-label">Quiz scores</div>
            </div>
            <div class="stat">
                <div class="stat-number">420</div>
                <div class="stat-label">XP earned</div>
            </div>
            <div class="stat">
                <div class="stat-number">3</div>
                <div class="stat-label">Courses</div>
            </div>
        </div>
    </div>
</section>

<section class="section" id="languages">
    <div class="section-head">
        <div>
            <div class="section-title">Programming Languages</div>
            <div class="section-sub">Choose a language and start your journey</div>
        </div>
        <button class="view-all" type="button" onclick="toast('More languages are coming soon.')">View all languages</button>
    </div>

    <div class="languages">

        <a class="lang" href="/creator-ai/learning/html/">
            <div class="lang-icon" style="background:rgba(249,115,22,.12);color:#fb923c">🌐</div>
            <span class="lang-badge">Beginner</span>
            <h3>HTML</h3>
            <p>Build the structure of modern web pages.</p>
            <div class="lang-meta"><span>25 lessons</span><span>64%</span></div>
            <div class="lang-progress"><span style="width:64%"></span></div>
        </a>

        <a class="lang" href="/creator-ai/learning/css/">
            <div class="lang-icon" style="background:rgba(96,165,250,.12);color:#60a5fa">🎨</div>
            <span class="lang-badge">Beginner</span>
            <h3>CSS</h3>
            <p>Learn layouts, responsive design and animations.</p>
            <div class="lang-meta"><span>30 lessons</span><span>22%</span></div>
            <div class="lang-progress"><span style="width:22%"></span></div>
        </a>

        <a class="lang" href="/creator-ai/learning/javascript/">
            <div class="lang-icon" style="background:rgba(250,204,21,.11);color:#facc15">JS</div>
            <span class="lang-badge">Popular</span>
            <h3>JavaScript</h3>
            <p>Make websites interactive and build web apps.</p>
            <div class="lang-meta"><span>48 lessons</span><span>0%</span></div>
            <div class="lang-progress"><span style="width:0"></span></div>
        </a>

        <a class="lang" href="/creator-ai/learning/python/">
            <div class="lang-icon" style="background:rgba(96,165,250,.11);color:#60a5fa">🐍</div>
            <span class="lang-badge">Popular</span>
            <h3>Python</h3>
            <p>Start programming with one of the easiest languages.</p>
            <div class="lang-meta"><span>55 lessons</span><span>0%</span></div>
            <div class="lang-progress"><span style="width:0"></span></div>
        </a>

        <a class="lang" href="/creator-ai/learning/php/">
            <div class="lang-icon" style="background:rgba(139,92,246,.12);color:#a78bfa">PHP</div>
            <span class="lang-badge">Backend</span>
            <h3>PHP</h3>
            <p>Build dynamic websites and server-side applications.</p>
            <div class="lang-meta"><span>44 lessons</span><span>0%</span></div>
            <div class="lang-progress"><span style="width:0"></span></div>
        </a>

        <a class="lang" href="/creator-ai/learning/c/">
            <div class="lang-icon" style="background:rgba(34,211,238,.10);color:#22d3ee">C</div>
            <span class="lang-badge">Core</span>
            <h3>C</h3>
            <p>Understand programming fundamentals and memory.</p>
            <div class="lang-meta"><span>42 lessons</span><span>0%</span></div>
            <div class="lang-progress"><span style="width:0"></span></div>
        </a>

        <a class="lang" href="/creator-ai/learning/cpp/">
            <div class="lang-icon" style="background:rgba(34,211,238,.10);color:#67e8f9">C++</div>
            <span class="lang-badge">Advanced</span>
            <h3>C++</h3>
            <p>Learn OOP, STL, memory and modern C++.</p>
            <div class="lang-meta"><span>52 lessons</span><span>0%</span></div>
            <div class="lang-progress"><span style="width:0"></span></div>
        </a>

        <a class="lang" href="/creator-ai/learning/java/">
            <div class="lang-icon" style="background:rgba(251,146,60,.11);color:#fb923c">☕</div>
            <span class="lang-badge">Popular</span>
            <h3>Java</h3>
            <p>Build object-oriented applications and backend systems.</p>
            <div class="lang-meta"><span>50 lessons</span><span>0%</span></div>
            <div class="lang-progress"><span style="width:0"></span></div>
        </a>

        <a class="lang" href="/creator-ai/learning/csharp/">
            <div class="lang-icon" style="background:rgba(168,85,247,.12);color:#c084fc">C#</div>
            <span class="lang-badge">Modern</span>
            <h3>C#</h3>
            <p>Learn .NET programming and application development.</p>
            <div class="lang-meta"><span>46 lessons</span><span>0%</span></div>
            <div class="lang-progress"><span style="width:0"></span></div>
        </a>

        <a class="lang" href="/creator-ai/learning/typescript/">
            <div class="lang-icon" style="background:rgba(59,130,246,.12);color:#60a5fa">TS</div>
            <span class="lang-badge">Modern</span>
            <h3>TypeScript</h3>
            <p>Write safer and scalable JavaScript applications.</p>
            <div class="lang-meta"><span>35 lessons</span><span>0%</span></div>
            <div class="lang-progress"><span style="width:0"></span></div>
        </a>

        <a class="lang" href="/creator-ai/learning/sql/">
            <div class="lang-icon" style="background:rgba(52,211,153,.10);color:#34d399">SQL</div>
            <span class="lang-badge">Database</span>
            <h3>SQL</h3>
            <p>Query, manage and understand relational databases.</p>
            <div class="lang-meta"><span>32 lessons</span><span>0%</span></div>
            <div class="lang-progress"><span style="width:0"></span></div>
        </a>

        <a class="lang" href="/creator-ai/learning/go/">
            <div class="lang-icon" style="background:rgba(34,211,238,.10);color:#67e8f9">GO</div>
            <span class="lang-badge">Modern</span>
            <h3>Go</h3>
            <p>Learn simple, fast and scalable backend programming.</p>
            <div class="lang-meta"><span>38 lessons</span><span>0%</span></div>
            <div class="lang-progress"><span style="width:0"></span></div>
        </a>

    </div>
</section>

<section class="section" id="tools">
    <div class="section-head">
        <div>
            <div class="section-title">Practice & Assessment</div>
            <div class="section-sub">Turn lessons into real skills</div>
        </div>
    </div>

    <div class="tools">
        <button class="tool" onclick="toast('Tutorial library is being connected.')">
            <div class="tool-icon">📚</div>
            <div><h3>Tutorials</h3><p>Step-by-step lessons</p></div>
        </button>

        <button class="tool" onclick="toast('Course system is being connected.')">
            <div class="tool-icon">🎓</div>
            <div><h3>Courses</h3><p>Complete learning paths</p></div>
        </button>

        <button class="tool" onclick="toast('Quiz system is being connected.')">
            <div class="tool-icon">🧠</div>
            <div><h3>Quizzes</h3><p>Topic-based practice</p></div>
        </button>

        <button class="tool" onclick="toast('Mock tests are being connected.')">
            <div class="tool-icon">🏆</div>
            <div><h3>Mock Tests</h3><p>Exam-style tests</p></div>
        </button>

        <button class="tool" onclick="toast('Coding practice is being connected.')">
            <div class="tool-icon">💻</div>
            <div><h3>Code Practice</h3><p>Write and test code</p></div>
        </button>

        <button class="tool" onclick="toast('Progress tracking is being connected.')">
            <div class="tool-icon">📈</div>
            <div><h3>Progress</h3><p>Track your learning</p></div>
        </button>

        <button class="tool" onclick="toast('Certificates are being connected.')">
            <div class="tool-icon">📜</div>
            <div><h3>Certificates</h3><p>Complete & earn</p></div>
        </button>

        <button class="tool" onclick="toast('Bookmarks are being connected.')">
            <div class="tool-icon">🔖</div>
            <div><h3>Bookmarks</h3><p>Save important lessons</p></div>
        </button>
    </div>
</section>

<section class="section" id="activity">
    <div class="section-head">
        <div>
            <div class="section-title">Your Activity</div>
            <div class="section-sub">Your latest learning progress</div>
        </div>
    </div>

    <div class="activity">

        <div class="panel">
            <div class="panel-head">
                <strong>Recent Activity</strong>
                <i class="bi bi-clock-history" style="color:#737d90"></i>
            </div>

            <div class="activity-row">
                <div class="activity-icon"><i class="bi bi-check2"></i></div>
                <div class="activity-info">
                    <strong>HTML — Semantic HTML</strong>
                    <span>Lesson completed · Today</span>
                </div>
                <i class="bi bi-check-circle-fill check"></i>
            </div>

            <div class="activity-row">
                <div class="activity-icon"><i class="bi bi-patch-question"></i></div>
                <div class="activity-info">
                    <strong>HTML Basics Quiz</strong>
                    <span>Score: 8 / 10 · Yesterday</span>
                </div>
                <i class="bi bi-check-circle-fill check"></i>
            </div>

            <div class="activity-row">
                <div class="activity-icon"><i class="bi bi-bookmark"></i></div>
                <div class="activity-info">
                    <strong>CSS Flexbox</strong>
                    <span>Added to bookmarks · 2 days ago</span>
                </div>
                <i class="bi bi-bookmark-fill" style="color:#a78bfa"></i>
            </div>
        </div>

        <div class="panel">
            <div class="panel-head">
                <strong>Quick Access</strong>
                <i class="bi bi-lightning-charge" style="color:#737d90"></i>
            </div>

            <div class="quick-grid">
                <button class="quick" onclick="toast('Saved lessons will appear here.')">
                    <i class="bi bi-bookmark-fill"></i>
                    <strong>Bookmarks</strong>
                    <span>Continue saved lessons</span>
                </button>

                <button class="quick" onclick="toast('Liked lessons will appear here.')">
                    <i class="bi bi-heart-fill"></i>
                    <strong>Liked</strong>
                    <span>Your favorite lessons</span>
                </button>

                <button class="quick" onclick="toast('Test history will appear here.')">
                    <i class="bi bi-bar-chart-fill"></i>
                    <strong>Scores</strong>
                    <span>View your test history</span>
                </button>

                <button class="quick" onclick="toast('Certificates will appear here.')">
                    <i class="bi bi-award-fill"></i>
                    <strong>Certificates</strong>
                    <span>Your achievements</span>
                </button>
            </div>
        </div>

    </div>
</section>

<div class="footer">
    Creator AI Learning Hub · Learn • Practice • Test • Build
</div>

</div>
</main>
</div>

<div class="search-overlay" id="searchOverlay">
    <div class="search-box">
        <input id="searchInput" type="search" placeholder="Search programming languages, courses or topics..." autocomplete="off">
        <div class="search-results" id="searchResults"></div>
        <button class="close-search" id="closeSearch" type="button">Close</button>
    </div>
</div>

<script>
const sidebar = document.getElementById('sidebar');
const mobileMenu = document.getElementById('mobileMenu');

mobileMenu?.addEventListener('click', () => {
    sidebar.classList.toggle('open');
});

document.querySelectorAll('.nav-item').forEach(item => {
    item.addEventListener('click', () => sidebar.classList.remove('open'));
});

function toast(message){
    let old = document.getElementById('creatorToast');
    if(old) old.remove();

    const el = document.createElement('div');
    el.id = 'creatorToast';
    el.textContent = message;

    Object.assign(el.style,{
        position:'fixed',
        right:'20px',
        bottom:'24px',
        zIndex:'500',
        padding:'12px 15px',
        border:'1px solid rgba(255,255,255,.12)',
        borderRadius:'11px',
        background:'#121722',
        color:'#e7eaf1',
        fontSize:'10px',
        fontWeight:'700',
        boxShadow:'0 20px 60px rgba(0,0,0,.45)',
        opacity:'0',
        transform:'translateY(10px)',
        transition:'.2s'
    });

    document.body.appendChild(el);

    requestAnimationFrame(() => {
        el.style.opacity = '1';
        el.style.transform = 'none';
    });

    setTimeout(() => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(10px)';
        setTimeout(() => el.remove(),220);
    },2600);
}

const searchOverlay = document.getElementById('searchOverlay');
const searchInput = document.getElementById('searchInput');
const searchResults = document.getElementById('searchResults');

const languages = [
    ['HTML','/creator-ai/learning/html/','Build web page structure'],
    ['CSS','/creator-ai/learning/css/','Design responsive websites'],
    ['JavaScript','/creator-ai/learning/javascript/','Build interactive web apps'],
    ['Python','/creator-ai/learning/python/','Learn programming fundamentals'],
    ['PHP','/creator-ai/learning/php/','Build server-side applications'],
    ['C','/creator-ai/learning/c/','Learn core programming'],
    ['C++','/creator-ai/learning/cpp/','OOP, STL and modern C++'],
    ['Java','/creator-ai/learning/java/','Object-oriented programming'],
    ['C#','/creator-ai/learning/csharp/','.NET application development'],
    ['TypeScript','/creator-ai/learning/typescript/','Typed JavaScript'],
    ['SQL','/creator-ai/learning/sql/','Databases and queries'],
    ['Go','/creator-ai/learning/go/','Fast backend programming']
];

function renderSearch(value=''){
    const q = value.trim().toLowerCase();

    const found = languages.filter(item =>
        !q ||
        item[0].toLowerCase().includes(q) ||
        item[2].toLowerCase().includes(q)
    );

    if(!found.length){
        searchResults.innerHTML =
            '<div style="padding:14px;color:#727b8e;font-size:10px">No results found.</div>';
        return;
    }

    searchResults.innerHTML = found.map(item => `
        <a class="search-result" href="${item[1]}">
            <i class="bi bi-code-square"></i>
            <span>
                <strong style="color:#fff">${item[0]}</strong>
                <span style="display:block;margin-top:3px;color:#6f788a">${item[2]}</span>
            </span>
        </a>
    `).join('');
}

document.getElementById('searchBtn').addEventListener('click',()=>{
    searchOverlay.classList.add('show');
    searchInput.value='';
    renderSearch();
    setTimeout(()=>searchInput.focus(),50);
});

document.getElementById('closeSearch').addEventListener('click',()=>{
    searchOverlay.classList.remove('show');
});

searchOverlay.addEventListener('click',e=>{
    if(e.target === searchOverlay) searchOverlay.classList.remove('show');
});

searchInput.addEventListener('input',()=>renderSearch(searchInput.value));

document.addEventListener('keydown',e=>{
    if(e.key === 'Escape'){
        searchOverlay.classList.remove('show');
        sidebar.classList.remove('open');
    }

    if((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k'){
        e.preventDefault();
        searchOverlay.classList.add('show');
        searchInput.focus();
    }
});
</script>

</body>
</html>

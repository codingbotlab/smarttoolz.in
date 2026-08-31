<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

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
$email = (string)($user['email'] ?? $_SESSION['user_email'] ?? '');
$avatar = (string)($user['avatar'] ?? $_SESSION['user_avatar'] ?? '');

$dailyCredits = max(0, (int)($user['daily_credits'] ?? 0));
$purchasedCredits = max(0, (int)($user['credits'] ?? 0));
$totalCredits = $dailyCredits + $purchasedCredits;

$initial = strtoupper(substr(trim($name ?: 'C'), 0, 1));

function h(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Creator AI — Creative Studio</title>
<meta name="description" content="Creator AI creative studio for text, image and video generation.">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
:root{
    --bg:#05060a;
    --bg2:#090b12;
    --sidebar:#090a10;
    --panel:#11141d;
    --panel2:#0d1018;
    --input:#080a10;

    --white:#ffffff;
    --text:#f7f8fc;
    --text2:#e4e7f0;
    --muted:#a5aec2;
    --muted2:#7d879d;
    --dim:#5d667c;

    --line:rgba(255,255,255,.12);
    --line2:rgba(255,255,255,.18);

    --purple:#9b6cff;
    --purple2:#7c4dff;
    --cyan:#35d8ee;
    --green:#46df9b;
    --red:#ff7188;
}

*{
    box-sizing:border-box;
}

html,
body{
    width:100%;
    height:100%;
    margin:0;
}

body{
    color:var(--text);
    background:
        radial-gradient(
            circle at 48% -15%,
            rgba(124,77,255,.20),
            transparent 32%
        ),
        radial-gradient(
            circle at 100% 100%,
            rgba(53,216,238,.09),
            transparent 30%
        ),
        linear-gradient(
            135deg,
            #04050a 0%,
            #080a10 50%,
            #05060a 100%
        );

    font-family:Inter,Arial,sans-serif;
    overflow:hidden;
}

body:before{
    content:"";
    position:fixed;
    inset:0;
    pointer-events:none;
    opacity:.45;
    background:
        linear-gradient(
            rgba(255,255,255,.018) 1px,
            transparent 1px
        ),
        linear-gradient(
            90deg,
            rgba(255,255,255,.018) 1px,
            transparent 1px
        );
    background-size:60px 60px;
}

button,
input,
textarea{
    font-family:inherit;
}

button{
    cursor:pointer;
}

a{
    color:inherit;
    text-decoration:none;
}

/* =========================================================
   APP
   ========================================================= */

.app{
    width:100%;
    height:100vh;
    display:flex;
}

/* =========================================================
   SIDEBAR
   ========================================================= */

.sidebar{
    width:260px;
    flex:0 0 260px;
    height:100vh;

    display:flex;
    flex-direction:column;

    padding:15px 12px;

    background:
        linear-gradient(
            180deg,
            rgba(13,15,23,.98),
            rgba(7,8,13,.98)
        );

    border-right:1px solid rgba(255,255,255,.11);

    position:relative;
    z-index:100;
}

.brand-row{
    height:44px;
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding:0 8px 8px;
}

.brand{
    color:#fff;
    font-size:21px;
    font-weight:900;
    letter-spacing:-1.2px;
}

.brand span{
    color:#a879ff;
}

.close-side{
    display:none;
    border:0;
    background:transparent;
    color:#b9c0d0;
    font-size:18px;
}

.create-btn{
    width:100%;

    display:flex;
    align-items:center;
    gap:9px;

    padding:12px 13px;

    border:1px solid rgba(167,139,250,.30);
    border-radius:11px;

    color:#fff;

    background:
        linear-gradient(
            135deg,
            rgba(139,92,246,.25),
            rgba(34,211,238,.09)
        );

    font-size:10px;
    font-weight:900;

    box-shadow:
        0 10px 35px rgba(0,0,0,.20);

    transition:.2s;
}

.create-btn:hover{
    border-color:rgba(167,139,250,.55);
    transform:translateY(-1px);
}

.create-btn i{
    color:#d7caff;
    font-size:14px;
}

.nav{
    display:grid;
    gap:3px;
    margin-top:12px;
}

.nav a{
    display:flex;
    align-items:center;
    gap:10px;

    padding:10px 11px;

    border-radius:9px;

    color:#aab2c5;

    font-size:9px;
    font-weight:800;

    transition:.18s;
}

.nav a:hover,
.nav a.active{
    color:#fff;
    background:rgba(255,255,255,.075);
}

.nav a.active{
    box-shadow:
        inset 2px 0 0 #9b6cff;
}

.nav a i{
    width:18px;
    text-align:center;
    color:#b6bfd2;
    font-size:13px;
}

.nav-divider{
    height:1px;
    background:var(--line);
    margin:10px 4px;
}

.side-title{
    padding:8px 9px 7px;

    color:#6e7890;

    font-size:7px;
    font-weight:900;
    letter-spacing:1.7px;

    text-transform:uppercase;
}

.history{
    flex:1;
    overflow:auto;
    padding-right:2px;
}

.history::-webkit-scrollbar{
    width:4px;
}

.history::-webkit-scrollbar-thumb{
    background:rgba(255,255,255,.10);
    border-radius:20px;
}

.history-item{
    width:100%;

    display:block;

    border:0;
    background:transparent;

    color:#a0a9bd;

    text-align:left;

    padding:9px 10px;

    border-radius:8px;

    font-size:8px;
    font-weight:650;

    white-space:nowrap;
    overflow:hidden;
    text-overflow:ellipsis;

    transition:.15s;
}

.history-item:hover{
    color:#fff;
    background:rgba(255,255,255,.065);
}

.history-empty{
    padding:9px;

    color:#69738a;

    font-size:8px;
    line-height:1.6;
}

.side-bottom{
    padding-top:10px;
    border-top:1px solid var(--line);
}

.credit-card{
    display:flex;
    align-items:center;
    justify-content:space-between;

    padding:11px;

    margin-bottom:9px;

    border:1px solid rgba(255,255,255,.12);
    border-radius:11px;

    background:rgba(255,255,255,.035);
}

.credit-label{
    color:#8993a9;

    font-size:7px;
    font-weight:850;

    text-transform:uppercase;
    letter-spacing:1px;
}

.credit-number{
    margin-top:3px;

    color:#e5dcff;

    font-size:15px;
    font-weight:900;
}

.credit-buy{
    color:#c5b5ff;

    font-size:7px;
    font-weight:900;
}

.profile{
    display:flex;
    align-items:center;
    gap:9px;

    padding:4px 5px;
}

.avatar{
    width:32px;
    height:32px;
    flex:0 0 32px;

    display:grid;
    place-items:center;

    border-radius:50%;

    overflow:hidden;

    color:#fff;

    background:
        linear-gradient(
            135deg,
            #8b5cf6,
            #22d3ee
        );

    font-size:10px;
    font-weight:900;
}

.avatar img{
    width:100%;
    height:100%;
    object-fit:cover;
}

.profile-info{
    min-width:0;
    flex:1;
}

.profile-name,
.profile-email{
    white-space:nowrap;
    overflow:hidden;
    text-overflow:ellipsis;
}

.profile-name{
    color:#eef1f7;
    font-size:8px;
    font-weight:850;
}

.profile-email{
    color:#7e879b;
    margin-top:2px;
    font-size:7px;
}

.logout{
    color:#929bb0;
    font-size:15px;
}

.logout:hover{
    color:#fff;
}

/* =========================================================
   MAIN
   ========================================================= */

.main{
    min-width:0;
    flex:1;
    height:100vh;

    display:flex;
    flex-direction:column;

    position:relative;
}

/* TOP BAR */

.topbar{
    height:61px;
    flex:0 0 61px;

    display:flex;
    align-items:center;
    justify-content:space-between;

    padding:0 25px;

    background:rgba(7,8,13,.78);

    border-bottom:1px solid rgba(255,255,255,.10);

    backdrop-filter:blur(22px);

    z-index:20;
}

.top-title{
    color:#e9ebf3;
    font-size:10px;
    font-weight:900;
}

.top-title span{
    color:#818ba1;
    margin-left:6px;
    font-weight:650;
}

.top-actions{
    display:flex;
    align-items:center;
    gap:8px;
}

.credit-pill{
    display:flex;
    align-items:center;
    gap:6px;

    padding:8px 11px;

    color:#ddd5ff;

    border:1px solid rgba(167,139,250,.25);
    border-radius:9px;

    background:rgba(139,92,246,.08);

    font-size:8px;
    font-weight:900;
}

.credit-pill i{
    color:#bca7ff;
}

.top-icon{
    width:31px;
    height:31px;

    border:1px solid rgba(255,255,255,.12);
    border-radius:8px;

    background:rgba(255,255,255,.035);

    color:#aeb7ca;
}

/* MOBILE BAR */

.mobile-bar{
    display:none;
}

/* =========================================================
   CANVAS
   ========================================================= */

.canvas{
    flex:1;
    overflow-y:auto;

    position:relative;
}

.canvas::-webkit-scrollbar{
    width:5px;
}

.canvas::-webkit-scrollbar-thumb{
    background:rgba(255,255,255,.09);
    border-radius:20px;
}

/* =========================================================
   HERO
   ========================================================= */

.hero{
    min-height:calc(100vh - 61px);

    display:flex;
    align-items:center;
    justify-content:center;

    padding:50px 22px 150px;
}

.hero-inner{
    width:min(900px,100%);
    text-align:center;
}

.ai-orb{
    width:76px;
    height:76px;

    display:grid;
    place-items:center;

    margin:0 auto 25px;

    border-radius:25px;

    color:#fff;

    background:
        linear-gradient(
            135deg,
            #8b5cf6,
            #22d3ee
        );

    font-size:30px;

    box-shadow:
        0 0 0 8px rgba(139,92,246,.045),
        0 20px 80px rgba(139,92,246,.30);
}

.eyebrow{
    color:#b39aff;

    font-size:8px;
    font-weight:900;

    letter-spacing:3px;
}

.hero h1{
    margin:11px 0 12px;

    color:#fff;

    font-size:50px;
    line-height:1.04;

    letter-spacing:-3.2px;

    font-weight:900;
}

.gradient{
    background:
        linear-gradient(
            100deg,
            #ffffff 10%,
            #cbbdff 52%,
            #67e8f9
        );

    -webkit-background-clip:text;
    background-clip:text;

    -webkit-text-fill-color:transparent;
}

.hero-sub{
    max-width:590px;

    margin:0 auto;

    color:#9da6ba;

    font-size:11px;
    line-height:1.85;
}

/* =========================================================
   MODES
   ========================================================= */

.modes{
    display:flex;
    justify-content:center;
    flex-wrap:wrap;
    gap:5px;

    margin-top:30px;
}

.mode{
    border:1px solid rgba(255,255,255,.12);
    border-radius:9px;

    padding:8px 13px;

    color:#a0a9bd;

    background:rgba(255,255,255,.035);

    font-size:8px;
    font-weight:850;

    transition:.2s;
}

.mode i{
    margin-right:5px;
}

.mode:hover{
    color:#fff;
    border-color:rgba(167,139,250,.32);
    background:rgba(255,255,255,.07);
}

.mode.active{
    color:#fff;

    border-color:rgba(167,139,250,.42);

    background:
        linear-gradient(
            135deg,
            rgba(139,92,246,.18),
            rgba(34,211,238,.07)
        );

    box-shadow:
        0 8px 30px rgba(139,92,246,.08);
}

/* =========================================================
   PROMPT
   ========================================================= */

.prompt-shell{
    width:min(800px,100%);

    margin:18px auto 0;

    border:1px solid rgba(255,255,255,.18);
    border-radius:18px;

    background:
        linear-gradient(
            145deg,
            rgba(20,23,34,.97),
            rgba(12,14,21,.97)
        );

    box-shadow:
        0 30px 100px rgba(0,0,0,.55);

    overflow:hidden;

    transition:.2s;
}

.prompt-shell:focus-within{
    border-color:rgba(167,139,250,.45);

    box-shadow:
        0 30px 100px rgba(0,0,0,.58),
        0 0 0 3px rgba(139,92,246,.06);
}

.prompt-top{
    display:flex;
    align-items:center;
    gap:5px;

    padding:9px 11px 0;
}

.prompt-chip{
    border:1px solid transparent;

    border-radius:7px;

    padding:6px 8px;

    color:#7f899e;

    background:transparent;

    font-size:7px;
    font-weight:850;
}

.prompt-chip:hover{
    color:#dfe2ed;
    background:rgba(255,255,255,.05);
}

.prompt-chip.active{
    color:#dcd2ff;

    border-color:rgba(167,139,250,.20);

    background:rgba(139,92,246,.10);
}

.prompt-text{
    display:block;

    width:100%;

    min-height:78px;
    max-height:190px;

    resize:none;

    outline:0;
    border:0;

    padding:12px 15px 7px;

    color:#fff;

    background:transparent;

    font-size:11px;
    line-height:1.7;
}

.prompt-text::placeholder{
    color:#788197;
}

.prompt-text:focus{
    color:#fff;
}

.prompt-bottom{
    display:flex;
    align-items:center;
    justify-content:space-between;

    padding:5px 9px 9px 10px;
}

.prompt-tools{
    display:flex;
    align-items:center;
    gap:2px;
}

.tool{
    border:0;

    padding:7px 8px;

    border-radius:8px;

    color:#8993aa;

    background:transparent;

    font-size:7px;
    font-weight:850;
}

.tool:hover{
    color:#e3e6ef;
    background:rgba(255,255,255,.06);
}

.tool i{
    margin-right:4px;
    font-size:12px;
    vertical-align:-1px;
}

.send{
    width:34px;
    height:34px;

    display:grid;
    place-items:center;

    border:0;
    border-radius:10px;

    color:#fff;

    background:
        linear-gradient(
            135deg,
            #8b5cf6,
            #22d3ee
        );

    box-shadow:
        0 8px 25px rgba(139,92,246,.30);

    transition:.2s;
}

.send:hover{
    transform:translateY(-2px);
}

.send:disabled{
    opacity:.35;
    cursor:not-allowed;
    transform:none;
}

.prompt-note{
    margin-top:8px;

    color:#657087;

    font-size:6px;
}

/* =========================================================
   FEATURE CARDS
   ========================================================= */

.cards{
    width:min(800px,100%);

    display:grid;
    grid-template-columns:repeat(3,1fr);

    gap:10px;

    margin:26px auto 0;
}

.feature{
    min-height:112px;

    padding:16px;

    border:1px solid rgba(255,255,255,.12);
    border-radius:15px;

    text-align:left;

    color:#fff;

    background:
        linear-gradient(
            145deg,
            rgba(255,255,255,.055),
            rgba(255,255,255,.018)
        );

    position:relative;
    overflow:hidden;

    transition:.22s;
}

.feature:hover{
    transform:translateY(-4px);

    border-color:rgba(167,139,250,.32);

    background:
        linear-gradient(
            145deg,
            rgba(139,92,246,.09),
            rgba(255,255,255,.025)
        );

    box-shadow:
        0 20px 60px rgba(0,0,0,.28);
}

.feature:after{
    content:"";

    position:absolute;

    width:100px;
    height:100px;

    right:-55px;
    bottom:-55px;

    border-radius:50%;

    background:rgba(139,92,246,.13);

    filter:blur(15px);
}

.feature-icon{
    width:32px;
    height:32px;

    display:grid;
    place-items:center;

    margin-bottom:12px;

    border:1px solid rgba(255,255,255,.10);
    border-radius:9px;

    color:#c9bbff;

    background:rgba(255,255,255,.055);

    font-size:14px;
}

.feature h3{
    margin:0 0 5px;

    color:#f2f4fa;

    font-size:9px;
    font-weight:900;
}

.feature p{
    margin:0;

    color:#8791a7;

    font-size:7px;
    line-height:1.55;
}

/* =========================================================
   RESULT
   ========================================================= */

.result-area{
    display:none;

    width:min(850px,calc(100% - 30px));

    margin:0 auto;

    padding:35px 0 150px;
}

.result-area.show{
    display:block;
}

.result-card{
    overflow:hidden;

    border:1px solid rgba(255,255,255,.13);
    border-radius:18px;

    background:rgba(16,19,29,.90);

    box-shadow:
        0 25px 90px rgba(0,0,0,.42);
}

.result-head{
    display:flex;
    align-items:center;
    justify-content:space-between;

    padding:12px 14px;

    border-bottom:1px solid rgba(255,255,255,.08);
}

.result-label{
    color:#c9c0e9;

    font-size:8px;
    font-weight:900;
}

.result-actions{
    display:flex;
    gap:5px;
}

.small-action{
    border:1px solid rgba(255,255,255,.12);
    border-radius:7px;

    padding:6px 9px;

    color:#aeb6c8;

    background:rgba(255,255,255,.04);

    font-size:7px;
    font-weight:850;
}

.small-action:hover{
    color:#fff;
    background:rgba(255,255,255,.08);
}

.result-body{
    min-height:230px;

    padding:20px;

    color:#e8ebf4;

    background:#090b11;

    white-space:pre-wrap;

    font-size:11px;
    line-height:1.85;
}

.generating{
    display:flex;
    align-items:center;
    gap:9px;

    color:#aab2c5;
}

.loader{
    width:7px;
    height:7px;

    border-radius:50%;

    background:#b19aff;

    animation:pulse 1s infinite alternate;
}

@keyframes pulse{
    to{
        opacity:.25;
        transform:scale(.7);
    }
}

/* =========================================================
   MOBILE
   ========================================================= */

@media(max-width:780px){

    .sidebar{
        position:fixed;

        left:0;
        top:0;

        width:280px;

        transform:translateX(-100%);

        transition:.22s;

        box-shadow:25px 0 80px #000b;
    }

    .sidebar.open{
        transform:translateX(0);
    }

    .close-side{
        display:block;
    }

    .mobile-bar{
        height:55px;
        flex:0 0 55px;

        display:flex;
        align-items:center;
        justify-content:space-between;

        padding:0 12px;

        background:rgba(7,8,13,.95);

        border-bottom:1px solid rgba(255,255,255,.10);

        backdrop-filter:blur(20px);
    }

    .mobile-menu{
        width:32px;
        height:32px;

        border:1px solid rgba(255,255,255,.12);
        border-radius:8px;

        color:#e0e4ed;

        background:rgba(255,255,255,.045);
    }

    .mobile-brand{
        color:#fff;
        font-size:15px;
        font-weight:900;
    }

    .mobile-brand span{
        color:#a879ff;
    }

    .mobile-credit{
        color:#d1c5ff;
        font-size:8px;
        font-weight:900;
    }

    .topbar{
        display:none;
    }

    .hero{
        min-height:calc(100vh - 55px);
        padding:35px 12px 100px;
    }

    .hero h1{
        font-size:36px;
        letter-spacing:-2.2px;
    }

    .hero-sub{
        font-size:10px;
    }

    .modes{
        margin-top:22px;
    }

    .mode{
        padding:7px 9px;
    }

    .cards{
        grid-template-columns:1fr;
        margin-top:18px;
    }

    .feature{
        min-height:92px;
    }

    .result-area{
        width:calc(100% - 22px);
        padding-top:20px;
    }
}

@media(max-width:430px){

    .hero h1{
        font-size:31px;
    }

    .prompt-text{
        min-height:85px;
    }

    .tool span{
        display:none;
    }

    .tool{
        padding:7px;
    }
}
</style>
</head>

<body>

<div class="app">

<!-- =========================================================
     SIDEBAR
     ========================================================= -->

<aside class="sidebar" id="sidebar">

    <div class="brand-row">

        <a class="brand" href="/creator-ai/">
            <span>Creator</span> AI
        </a>

        <button
            class="close-side"
            id="closeSide"
            type="button"
        >
            <i class="bi bi-x-lg"></i>
        </button>

    </div>


    <button
        class="create-btn"
        id="newCreation"
        type="button"
    >
        <i class="bi bi-plus-lg"></i>
        New Creation
    </button>


    <nav class="nav">

        <a
            class="active"
            href="/creator-ai/"
        >
            <i class="bi bi-stars"></i>
            Create
        </a>

        <a
            href="/creator-ai/workspace"
        >
            <i class="bi bi-grid-1x2"></i>
            Workspace
        </a>

        <a
            href="/creator-ai/credits"
        >
            <i class="bi bi-lightning-charge"></i>
            Credits
        </a>

        <a href="#">
            <i class="bi bi-compass"></i>
            Explore
        </a>

        <a href="#">
            <i class="bi bi-heart"></i>
            Favorites
        </a>

    </nav>


    <div class="nav-divider"></div>


    <div class="side-title">
        Recent creations
    </div>


    <div
        class="history"
        id="history"
    >

        <div class="history-empty">
            Your recent creations will appear here.
        </div>

    </div>


    <div class="side-bottom">

        <div class="credit-card">

            <div>

                <div class="credit-label">
                    Available credits
                </div>

                <div
                    class="credit-number"
                    id="sideCredits"
                >
                    <?= number_format($totalCredits) ?>
                </div>

            </div>

            <a
                class="credit-buy"
                href="/creator-ai/credits"
            >
                ADD CREDITS
            </a>

        </div>


        <div class="profile">

            <div class="avatar">

                <?php if ($avatar): ?>

                    <img
                        src="<?= h($avatar) ?>"
                        alt=""
                    >

                <?php else: ?>

                    <?= h($initial) ?>

                <?php endif; ?>

            </div>


            <div class="profile-info">

                <div class="profile-name">
                    <?= h($name) ?>
                </div>

                <div class="profile-email">
                    <?= h($email) ?>
                </div>

            </div>


            <a
                class="logout"
                href="/creator-ai/logout"
                title="Logout"
            >
                <i class="bi bi-box-arrow-right"></i>
            </a>

        </div>

    </div>

</aside>


<!-- =========================================================
     MAIN
     ========================================================= -->

<main class="main">


    <div class="mobile-bar">

        <button
            class="mobile-menu"
            id="openSide"
            type="button"
        >
            <i class="bi bi-list"></i>
        </button>


        <div class="mobile-brand">
            <span>Creator</span> AI
        </div>


        <a
            class="mobile-credit"
            href="/creator-ai/credits"
        >
            <i class="bi bi-stars"></i>
            <span id="mobileCredits">
                <?= number_format($totalCredits) ?>
            </span>
        </a>

    </div>


    <header class="topbar">

        <div class="top-title">
            Creator AI
            <span>Creative Studio</span>
        </div>


        <div class="top-actions">

            <a
                class="credit-pill"
                href="/creator-ai/credits"
            >
                <i class="bi bi-stars"></i>

                <span id="topCredits">
                    <?= number_format($totalCredits) ?>
                </span>

                Credits
            </a>


            <button
                class="top-icon"
                type="button"
                title="Settings"
            >
                <i class="bi bi-sliders2"></i>
            </button>

        </div>

    </header>


    <div class="canvas">


        <!-- =================================================
             HERO
             ================================================= -->

        <section
            class="hero"
            id="hero"
        >

            <div class="hero-inner">


                <div class="ai-orb">
                    <i class="bi bi-stars"></i>
                </div>


                <div class="eyebrow">
                    CREATOR AI STUDIO
                </div>


                <h1>
                    What do you want to<br>
                    <span class="gradient">
                        create today?
                    </span>
                </h1>


                <p class="hero-sub">
                    Turn your ideas into beautiful
                    text, images and videos with one
                    powerful creative workspace.
                </p>


                <!-- MODES -->

                <div
                    class="modes"
                    id="modes"
                >

                    <button
                        class="mode active"
                        data-mode="auto"
                        type="button"
                    >
                        <i class="bi bi-stars"></i>
                        Auto
                    </button>

                    <button
                        class="mode"
                        data-mode="text"
                        type="button"
                    >
                        <i class="bi bi-type"></i>
                        Text
                    </button>

                    <button
                        class="mode"
                        data-mode="image"
                        type="button"
                    >
                        <i class="bi bi-image"></i>
                        Image
                    </button>

                    <button
                        class="mode"
                        data-mode="video"
                        type="button"
                    >
                        <i class="bi bi-camera-video"></i>
                        Video
                    </button>

                </div>


                <!-- PROMPT -->

                <div class="prompt-shell">


                    <div class="prompt-top">

                        <button
                            class="prompt-chip active"
                            data-chip="auto"
                            type="button"
                        >
                            ✨ Auto
                        </button>

                        <button
                            class="prompt-chip"
                            data-chip="cinematic"
                            type="button"
                        >
                            Cinematic
                        </button>

                        <button
                            class="prompt-chip"
                            data-chip="creative"
                            type="button"
                        >
                            Creative
                        </button>

                    </div>


                    <textarea
                        id="prompt"
                        class="prompt-text"
                        maxlength="12000"
                        placeholder="Describe anything you want to create..."
                    ></textarea>


                    <div class="prompt-bottom">


                        <div class="prompt-tools">


                            <label
                                class="tool"
                                title="Attach file"
                            >

                                <i class="bi bi-paperclip"></i>
                                <span>Attach</span>

                                <input
                                    id="file"
                                    type="file"
                                    hidden
                                    accept="image/*,.txt,.pdf,.doc,.docx"
                                >

                            </label>


                            <button
                                class="tool"
                                id="enhance"
                                type="button"
                            >
                                <i class="bi bi-magic"></i>
                                <span>Enhance prompt</span>
                            </button>


                        </div>


                        <button
                            class="send"
                            id="send"
                            type="button"
                            disabled
                        >
                            <i class="bi bi-arrow-up"></i>
                        </button>


                    </div>

                </div>


                <div class="prompt-note">
                    One creative request at a time • You control your generations
                </div>


                <!-- FEATURE CARDS -->

                <div class="cards">


                    <button
                        class="feature"
                        data-mode-card="image"
                        data-prompt="Create a cinematic image of a futuristic city at night, ultra detailed, dramatic lighting, premium composition."
                        type="button"
                    >

                        <div class="feature-icon">
                            <i class="bi bi-image"></i>
                        </div>

                        <h3>
                            Generate an Image
                        </h3>

                        <p>
                            Turn a simple idea into a cinematic visual.
                        </p>

                    </button>


                    <button
                        class="feature"
                        data-mode-card="video"
                        data-prompt="Create a cinematic 10 second video of a futuristic city at night with smooth camera movement and dramatic lighting."
                        type="button"
                    >

                        <div class="feature-icon">
                            <i class="bi bi-camera-video"></i>
                        </div>

                        <h3>
                            Create a Video
                        </h3>

                        <p>
                            Bring your ideas to life with motion.
                        </p>

                    </button>


                    <button
                        class="feature"
                        data-mode-card="text"
                        data-prompt="Write a polished professional article about the future of artificial intelligence."
                        type="button"
                    >

                        <div class="feature-icon">
                            <i class="bi bi-pencil-square"></i>
                        </div>

                        <h3>
                            Write Content
                        </h3>

                        <p>
                            Blogs, scripts, ads, captions and more.
                        </p>

                    </button>


                </div>

            </div>

        </section>


        <!-- =================================================
             RESULT
             ================================================= -->

        <section
            class="result-area"
            id="resultArea"
        >

            <div class="result-card">

                <div class="result-head">

                    <div
                        class="result-label"
                        id="resultLabel"
                    >
                        CREATOR AI RESULT
                    </div>


                    <div class="result-actions">

                        <button
                            class="small-action"
                            id="copyBtn"
                            type="button"
                        >
                            <i class="bi bi-copy"></i>
                            Copy
                        </button>


                        <button
                            class="small-action"
                            id="downloadBtn"
                            type="button"
                        >
                            <i class="bi bi-download"></i>
                            Save
                        </button>

                    </div>

                </div>


                <div
                    class="result-body"
                    id="resultBody"
                ></div>

            </div>

        </section>


    </div>

</main>

</div>


<script>
const promptBox =
    document.getElementById('prompt');

const send =
    document.getElementById('send');

const hero =
    document.getElementById('hero');

const resultArea =
    document.getElementById('resultArea');

const resultBody =
    document.getElementById('resultBody');

const resultLabel =
    document.getElementById('resultLabel');

const sideCredits =
    document.getElementById('sideCredits');

const topCredits =
    document.getElementById('topCredits');

const mobileCredits =
    document.getElementById('mobileCredits');

const historyBox =
    document.getElementById('history');

let mode = 'auto';
let lastText = '';


/* =========================================================
   CREDITS
   ========================================================= */

function setCredits(value){

    const amount =
        Math.max(
            0,
            Number(value || 0)
        );

    const formatted =
        amount.toLocaleString();

    sideCredits.textContent =
        formatted;

    topCredits.textContent =
        formatted;

    mobileCredits.textContent =
        formatted;
}


/* =========================================================
   TEXTAREA
   ========================================================= */

function resizePrompt(){

    promptBox.style.height =
        'auto';

    promptBox.style.height =
        Math.min(
            promptBox.scrollHeight,
            190
        ) + 'px';

    send.disabled =
        !promptBox.value.trim();
}

promptBox.addEventListener(
    'input',
    resizePrompt
);


/* =========================================================
   ENTER
   ========================================================= */

promptBox.addEventListener(
    'keydown',
    function(event){

        if(
            event.key === 'Enter' &&
            !event.shiftKey
        ){

            event.preventDefault();

            if(
                promptBox.value.trim()
            ){
                run();
            }
        }
    }
);


/* =========================================================
   MODE
   ========================================================= */

function setMode(value){

    mode = value;

    document
        .querySelectorAll('.mode')
        .forEach(
            button => {

                button.classList.toggle(
                    'active',
                    button.dataset.mode === value
                );

            }
        );
}


document
    .querySelectorAll('.mode')
    .forEach(
        button => {

            button.addEventListener(
                'click',
                function(){

                    setMode(
                        this.dataset.mode
                    );

                }
            );

        }
    );


/* =========================================================
   PROMPT CHIPS
   ========================================================= */

document
    .querySelectorAll('.prompt-chip')
    .forEach(
        button => {

            button.addEventListener(
                'click',
                function(){

                    document
                        .querySelectorAll('.prompt-chip')
                        .forEach(
                            x =>
                                x.classList.remove('active')
                        );

                    this.classList.add(
                        'active'
                    );


                    if(
                        this.dataset.chip ===
                        'cinematic'
                    ){

                        promptBox.value +=
                            (
                                promptBox.value
                                ? ' '
                                : ''
                            ) +
                            'Cinematic, dramatic lighting, premium composition, highly detailed.';

                    }


                    if(
                        this.dataset.chip ===
                        'creative'
                    ){

                        promptBox.value +=
                            (
                                promptBox.value
                                ? ' '
                                : ''
                            ) +
                            'Make it original, imaginative and visually striking.';

                    }


                    resizePrompt();

                    promptBox.focus();

                }
            );

        }
    );


/* =========================================================
   FEATURE CARDS
   ========================================================= */

document
    .querySelectorAll('[data-prompt]')
    .forEach(
        card => {

            card.addEventListener(
                'click',
                function(){

                    promptBox.value =
                        this.dataset.prompt;

                    setMode(
                        this.dataset.modeCard
                    );

                    resizePrompt();

                    promptBox.focus();

                }
            );

        }
    );


/* =========================================================
   ENHANCE
   ========================================================= */

document
    .getElementById('enhance')
    .addEventListener(
        'click',
        function(){

            if(
                !promptBox.value.trim()
            ){
                promptBox.focus();
                return;
            }

            promptBox.value =
                promptBox.value.trim() +
                ' Make the request clear, specific, polished and production-ready while preserving the original intent.';

            resizePrompt();

            promptBox.focus();

        }
    );


/* =========================================================
   FILE
   ========================================================= */

document
    .getElementById('file')
    .addEventListener(
        'change',
        function(){

            if(
                this.files &&
                this.files[0]
            ){

                promptBox.value +=
                    (
                        promptBox.value
                        ? ' '
                        : ''
                    ) +
                    '[Attached file: ' +
                    this.files[0].name +
                    ']';

                resizePrompt();

            }

        }
    );


/* =========================================================
   NEW CREATION
   ========================================================= */

document
    .getElementById('newCreation')
    .addEventListener(
        'click',
        function(){

            promptBox.value = '';

            resultArea.classList.remove(
                'show'
            );

            hero.style.display =
                'flex';

            lastText = '';

            resizePrompt();

            promptBox.focus();

        }
    );


/* =========================================================
   MOBILE SIDEBAR
   ========================================================= */

document
    .getElementById('openSide')
    .addEventListener(
        'click',
        function(){

            document
                .getElementById('sidebar')
                .classList.add('open');

        }
    );


document
    .getElementById('closeSide')
    .addEventListener(
        'click',
        function(){

            document
                .getElementById('sidebar')
                .classList.remove('open');

        }
    );


/* =========================================================
   GENERATE
   ========================================================= */

async function run(){

    const text =
        promptBox.value.trim();

    if(!text){
        return;
    }


    send.disabled = true;

    hero.style.display =
        'none';

    resultArea.classList.add(
        'show'
    );


    resultLabel.textContent =
        (
            mode === 'auto'
            ? 'CREATOR AI'
            : mode.toUpperCase()
        ) +
        ' • GENERATING';


    resultBody.innerHTML =
        '<div class="generating">' +
            '<span class="loader"></span>' +
            '<span>Creating your result…</span>' +
        '</div>';


    try{

        const form =
            new FormData();

        form.append(
            'message',
            text
        );

        form.append(
            'mode',
            mode
        );


        const file =
            document.getElementById('file')
                .files[0];

        if(file){

            form.append(
                'file',
                file
            );

        }


        const response =
            await fetch(
                '/creator-ai/api/chat.php',
                {
                    method:'POST',
                    body:form,
                    headers:{
                        'Accept':
                            'application/json'
                    }
                }
            );


        const raw =
            await response.text();

        let data;

        try{

            data =
                JSON.parse(raw);

        }catch(error){

            throw new Error(
                'Server returned an invalid response.'
            );

        }


        if(
            !response.ok ||
            !data.ok
        ){

            if(
                typeof data.credits !==
                'undefined'
            ){

                setCredits(
                    data.credits
                );

            }

            throw new Error(
                data.error ||
                'Generation failed.'
            );

        }


        lastText =
            String(
                data.text || ''
            );


        resultLabel.textContent =
            (
                data.type ||
                mode ||
                'AI'
            ).toUpperCase() +
            ' • RESULT';


        resultBody.textContent =
            lastText ||
            'Your generation is ready.';


        if(
            typeof data.credits !==
            'undefined'
        ){

            setCredits(
                data.credits
            );

        }


        addHistory(
            data.title ||
            text
        );


    }catch(error){

        resultLabel.textContent =
            'CREATOR AI • ERROR';

        resultBody.textContent =
            error.message;

    }finally{

        send.disabled =
            !promptBox.value.trim();

    }

}


/* =========================================================
   HISTORY UI
   ========================================================= */

function addHistory(title){

    const empty =
        historyBox.querySelector(
            '.history-empty'
        );

    if(empty){
        empty.remove();
    }


    const button =
        document.createElement(
            'button'
        );

    button.type =
        'button';

    button.className =
        'history-item';

    button.textContent =
        title;

    button.title =
        title;


    historyBox.prepend(
        button
    );


    while(
        historyBox.children.length >
        20
    ){

        historyBox.lastElementChild
            .remove();

    }

}


/* =========================================================
   SEND
   ========================================================= */

send.addEventListener(
    'click',
    run
);


/* =========================================================
   COPY
   ========================================================= */

document
    .getElementById('copyBtn')
    .addEventListener(
        'click',
        async function(){

            if(!lastText){
                return;
            }

            try{

                await navigator
                    .clipboard
                    .writeText(
                        lastText
                    );

                const old =
                    this.innerHTML;

                this.innerHTML =
                    '<i class="bi bi-check2"></i> Copied';

                setTimeout(
                    () => {
                        this.innerHTML =
                            old;
                    },
                    1300
                );

            }catch(error){

                /* Clipboard may be blocked by browser. */

            }

        }
    );


/* =========================================================
   DOWNLOAD
   ========================================================= */

document
    .getElementById('downloadBtn')
    .addEventListener(
        'click',
        function(){

            if(!lastText){
                return;
            }


            const blob =
                new Blob(
                    [lastText],
                    {
                        type:
                            'text/plain;charset=utf-8'
                    }
                );


            const url =
                URL.createObjectURL(
                    blob
                );


            const a =
                document.createElement(
                    'a'
                );

            a.href =
                url;

            a.download =
                'creator-ai-result.txt';


            document.body.appendChild(
                a
            );

            a.click();

            a.remove();


            setTimeout(
                () => {
                    URL.revokeObjectURL(
                        url
                    );
                },
                500
            );

        }
    );


/* =========================================================
   INITIAL
   ========================================================= */

setCredits(
    <?= (int)$totalCredits ?>
);

resizePrompt();
</script>

</body>
</html>

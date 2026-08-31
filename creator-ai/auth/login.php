<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';


/*
|--------------------------------------------------------------------------
| Already Logged In
|--------------------------------------------------------------------------
*/

if (!empty($_SESSION['user_id'])) {

    header('Location: /creator-ai/');

    exit;
}


/*
|--------------------------------------------------------------------------
| Google OAuth Ready Check
|--------------------------------------------------------------------------
*/

$googleReady =
    defined('GOOGLE_CLIENT_ID') &&
    defined('GOOGLE_CLIENT_SECRET') &&
    GOOGLE_CLIENT_ID !== '' &&
    GOOGLE_CLIENT_SECRET !== '' &&
    GOOGLE_CLIENT_ID !== 'YOUR_GOOGLE_CLIENT_ID' &&
    GOOGLE_CLIENT_SECRET !== 'YOUR_GOOGLE_CLIENT_SECRET';


/*
|--------------------------------------------------------------------------
| Optional Error
|--------------------------------------------------------------------------
*/

$error = '';

if (
    isset($_GET['error']) &&
    $_GET['error'] !== ''
) {

    $error = trim(
        (string)$_GET['error']
    );
}

?>
<!doctype html>

<html lang="en">

<head>

<meta charset="utf-8">

<meta
    name="viewport"
    content="width=device-width,initial-scale=1"
>

<title>Login — Creator AI</title>

<meta
    name="description"
    content="Sign in to Creator AI powered by SmartToolz."
>


<link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet"
>


<link
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
    rel="stylesheet"
>


<link
    href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"
    rel="stylesheet"
>


<style>

*{
    box-sizing:border-box;
}

html,
body{
    min-height:100%;
}

body{

    margin:0;

    min-height:100vh;

    display:flex;

    align-items:center;

    justify-content:center;

    color:#eef1ff;

    font-family:Inter,Arial,sans-serif;

    background:

        radial-gradient(
            circle at 15% 10%,
            rgba(139,92,246,.22),
            transparent 30%
        ),

        radial-gradient(
            circle at 85% 85%,
            rgba(34,211,238,.15),
            transparent 30%
        ),

        linear-gradient(
            135deg,
            #070812,
            #0a0c18 55%,
            #070812
        );

    overflow:hidden;

}


/* Background grid */

body:before{

    content:"";

    position:fixed;

    inset:0;

    pointer-events:none;

    opacity:.20;

    background-image:

        linear-gradient(
            rgba(255,255,255,.025) 1px,
            transparent 1px
        ),

        linear-gradient(
            90deg,
            rgba(255,255,255,.025) 1px,
            transparent 1px
        );

    background-size:48px 48px;

}


/* Glow */

.glow{

    position:fixed;

    width:420px;

    height:420px;

    border-radius:50%;

    filter:blur(100px);

    opacity:.15;

    pointer-events:none;

}

.glow.one{

    top:-180px;

    left:-150px;

    background:#8b5cf6;

}

.glow.two{

    right:-180px;

    bottom:-180px;

    background:#22d3ee;

}


/* Login card */

.cardx{

    width:min(
        450px,
        calc(100% - 28px)
    );

    padding:38px;

    position:relative;

    z-index:2;

    border:1px solid
        rgba(255,255,255,.09);

    border-radius:30px;

    background:
        rgba(16,19,38,.82);

    backdrop-filter:blur(25px);

    box-shadow:

        0 35px 120px
        rgba(0,0,0,.55),

        0 0 70px
        rgba(139,92,246,.08);

}


/* Brand */

.logo{

    display:inline-block;

    color:#fff;

    text-decoration:none;

    font-size:25px;

    font-weight:900;

    letter-spacing:-1px;

}

.logo span{

    color:#8b5cf6;

}


/* AI icon */

.ai{

    width:70px;

    height:70px;

    display:grid;

    place-items:center;

    margin:28px auto 20px;

    border-radius:22px;

    background:

        linear-gradient(
            135deg,
            #8b5cf6,
            #22d3ee
        );

    font-size:29px;

    color:#fff;

    box-shadow:

        0 0 50px
        rgba(139,92,246,.30);

}


/* Heading */

h1{

    margin:0;

    font-size:30px;

    font-weight:900;

    letter-spacing:-1.2px;

}

.sub{

    margin-top:12px;

    color:#8f97b7;

    font-size:13px;

    line-height:1.75;

}


/* Google */

.google{

    width:100%;

    min-height:52px;

    margin-top:25px;

    padding:14px 18px;

    display:flex;

    align-items:center;

    justify-content:center;

    gap:12px;

    border-radius:14px;

    border:1px solid
        rgba(255,255,255,.12);

    background:#fff;

    color:#171923;

    text-decoration:none;

    font-size:13px;

    font-weight:800;

    transition:.25s;

}

.google:hover{

    background:#f3f3f3;

    color:#111;

    transform:translateY(-2px);

    box-shadow:
        0 15px 35px
        rgba(0,0,0,.25);

}

.google img{

    width:19px;

    height:19px;

}


/* Security */

.security{

    display:flex;

    align-items:center;

    justify-content:center;

    gap:7px;

    margin-top:18px;

    color:#66708d;

    font-size:9px;

}

.security i{

    color:#34d399;

}


/* Error */

.error{

    margin-top:18px;

    padding:12px 14px;

    border-radius:12px;

    border:1px solid
        rgba(251,113,133,.25);

    background:
        rgba(251,113,133,.07);

    color:#fda4af;

    font-size:10px;

    line-height:1.6;

}


/* Config warning */

.warning{

    margin-top:22px;

    padding:14px;

    border-radius:13px;

    border:1px solid
        rgba(251,191,36,.20);

    background:
        rgba(251,191,36,.06);

    color:#d6bd7a;

    font-size:10px;

    line-height:1.6;

}

.warning code{

    color:#fbbf24;

}


/* Footer */

.note{

    margin-top:20px;

    color:#68718e;

    text-align:center;

    font-size:10px;

    line-height:1.7;

}

.back{

    display:inline-flex;

    align-items:center;

    gap:6px;

    margin-top:20px;

    color:#8f97b7;

    text-decoration:none;

    font-size:11px;

    font-weight:700;

    transition:.2s;

}

.back:hover{

    color:#fff;

}


/* Mobile */

@media(max-width:500px){

    .cardx{

        padding:28px 22px;

        border-radius:24px;

    }

    h1{

        font-size:27px;

    }

}

</style>

</head>


<body>


<div class="glow one"></div>

<div class="glow two"></div>


<div class="cardx text-center">


    <!-- Creator AI -->

    <a
        class="logo"
        href="/creator-ai/"
    >
        <span>Creator</span> AI
    </a>


    <!-- AI Icon -->

    <div class="ai">

        <i class="bi bi-stars"></i>

    </div>


    <!-- Heading -->

    <h1>
        Welcome to Creator AI
    </h1>


    <p class="sub">

        Sign in with Google and enter
        your AI-powered SmartToolz workspace.

    </p>


    <!-- Error -->

    <?php if ($error !== ''): ?>

        <div class="error">

            <i class="bi bi-exclamation-circle"></i>

            <?= htmlspecialchars(
                $error,
                ENT_QUOTES,
                'UTF-8'
            ) ?>

        </div>

    <?php endif; ?>


    <?php if ($googleReady): ?>


        <!-- IMPORTANT:
             login.php is already inside /auth/
             therefore use google-login.php,
             NOT auth/google-login.php.
        -->

        <a
            class="google"
            href="auth/google-login"
        >

            <img
                src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg"
                alt=""
            >

            Continue with Google

        </a>


        <div class="security">

            <i class="bi bi-shield-check"></i>

            Secure Google authentication

        </div>


    <?php else: ?>


        <div class="warning">

            <strong>
                Google login isn't configured.
            </strong>

            <br>

            Add your Google Client ID and
            Client Secret to
            <code>auth/config.php</code>.

        </div>


    <?php endif; ?>


    <div class="note">

        By continuing, you agree to use
        Creator AI responsibly.

    </div>


    <!-- Clean URL -->

    <a
        class="back"
        href="/creator-ai/"
    >

        <i class="bi bi-arrow-left"></i>

        Back to Creator AI

    </a>


</div>


</body>

</html>
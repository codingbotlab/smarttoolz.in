<?php
declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);


/* ============================================================
   ANALYTICS TRACKER
============================================================ */

require_once $_SERVER['DOCUMENT_ROOT'] . '/analytics/tracker.php';



/* ============================================================
   DATABASE
============================================================ */

require_once $_SERVER['DOCUMENT_ROOT'] . '/creator-ai/auth/config.php';

$pdo = db();


/* ============================================================
   ADS FROM DATABASE
============================================================ */

$smartToozAds = [];

try {

    $stmt = $pdo->query("
        SELECT ad_key, enabled, ad_code
        FROM ads_settings
    ");

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        $smartToozAds[(string)$row['ad_key']] = [
            'enabled' => (int)($row['enabled'] ?? 0),
            'ad_code' => (string)($row['ad_code'] ?? '')
        ];

    }

} catch (Throwable $e) {

    $smartToozAds = [];

}


/* ============================================================
   AD FUNCTION
============================================================ */

function smartToozAd(string $adKey): void
{
    global $smartToozAds;

    if (!isset($smartToozAds[$adKey])) {
        return;
    }

    if ((int)$smartToozAds[$adKey]['enabled'] !== 1) {
        return;
    }

    $code = trim(
        (string)$smartToozAds[$adKey]['ad_code']
    );

    if ($code === '') {
        return;
    }

    echo $code;
}


/* ============================================================
   GLOBAL ADS
============================================================ */

smartToozAd('popunder');
smartToozAd('socialbar');

?>
<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>Password Generator - Smart-Tooz</title>

<meta
    name="description"
    content="Generate strong and secure random passwords online for free. Customize password length, numbers, symbols and letters."
>

<meta
    name="robots"
    content="index, follow"
>


<style>

/* ============================================================
   RESET
============================================================ */

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

html {
    scroll-behavior: smooth;
}

body {

    font-family:
        Inter,
        -apple-system,
        BlinkMacSystemFont,
        "Segoe UI",
        Arial,
        sans-serif;

    background: #f6f8fc;

    color: #172033;

    line-height: 1.5;
}

a {
    text-decoration: none;
    color: inherit;
}

button,
input {
    font-family: inherit;
}


/* ============================================================
   HEADER
============================================================ */

.site-header {

    position: sticky;

    top: 0;

    z-index: 1000;

    background:
        rgba(255,255,255,.96);

    backdrop-filter:
        blur(14px);

    border-bottom:
        1px solid #e5e9f0;
}

.navbar {

    width:
        calc(100% - 20px);

    max-width: 1400px;

    min-height: 72px;

    margin: auto;

    display: flex;

    align-items: center;

    justify-content: space-between;
}

.logo {

    display: flex;

    align-items: center;

    gap: 10px;

    color: #172033;

    font-size: 21px;

    font-weight: 800;
}

.logo-icon {

    width: 42px;

    height: 42px;

    display: grid;

    place-items: center;

    border-radius: 13px;

    color: white;

    background:
        linear-gradient(
            135deg,
            #635bff,
            #916cff
        );

    box-shadow:
        0 8px 22px
        rgba(99,91,255,.18);
}

.nav-links {

    display: flex;

    align-items: center;

    gap: 28px;
}

.nav-links a {

    color: #596477;

    font-size: 14px;

    font-weight: 600;
}

.nav-links a:hover {
    color: #635bff;
}

.menu-button {

    display: none;

    border: 0;

    background: transparent;

    font-size: 27px;

    cursor: pointer;
}


/* ============================================================
   PAGE LAYOUT
============================================================ */

.page-layout {

    width:
        min(1400px, calc(100% - 30px));

    margin:
        28px auto 60px;

    display: flex;

    flex-direction: row;

    align-items: flex-start;

    gap: 24px;
}

.tool-content {

    min-width: 0;

    flex: 1;

    order: 1;
}

.page-layout > .tools-sidebar {

    order: 2;
}


/* ============================================================
   ADS
============================================================ */

.ad-slot {

    width: 100%;

    min-height: 10px;

    margin:
        0 auto 24px;

    padding: 5px;

    display: flex;

    align-items: center;

    justify-content: center;

    text-align: center;

    overflow: hidden;
}

.ad-slot iframe {

    display: block;

    max-width: 100% !important;

    border: 0;
}

.ad-slot img {

    max-width: 100%;

    height: auto;
}

.desktop-ad {

    display: flex;

    width: 100%;

    justify-content: center;

    align-items: center;
}

.mobile-ad {

    display: none;

    width: 100%;

    justify-content: center;

    align-items: center;
}


/* ============================================================
   TOOL HEADER
============================================================ */

.tool-header {

    margin-bottom: 22px;

    padding:
        34px 25px;

    background: #ffffff;

    border:
        1px solid #e5e9f0;

    border-radius: 22px;

    text-align: center;

    box-shadow:
        0 8px 30px
        rgba(30,35,80,.035);
}

.tool-badge {

    display: inline-block;

    margin-bottom: 12px;

    padding:
        7px 13px;

    border-radius: 50px;

    background: #eeedff;

    color: #635bff;

    font-size: 12px;

    font-weight: 800;
}

.tool-header h1 {

    font-size:
        clamp(32px, 5vw, 48px);

    line-height: 1.08;

    letter-spacing: -1.8px;
}

.tool-header h1 span {
    color: #635bff;
}

.tool-header p {

    max-width: 680px;

    margin:
        13px auto 0;

    color: #707b8e;

    font-size: 14px;
}


/* ============================================================
   GENERATOR CARD
============================================================ */

.generator-card {

    padding: 28px;

    background: #ffffff;

    border:
        1px solid #e5e9f0;

    border-radius: 22px;

    box-shadow:
        0 15px 45px
        rgba(30,35,80,.06);
}


/* ============================================================
   PASSWORD OUTPUT
============================================================ */

.password-output {

    display: flex;

    gap: 10px;

    align-items: stretch;
}

#password {

    width: 100%;

    min-width: 0;

    height: 55px;

    padding:
        0 17px;

    border:
        1px solid #dfe3eb;

    border-radius: 13px;

    outline: none;

    background: #fafbff;

    color: #172033;

    font-family:
        "SFMono-Regular",
        Consolas,
        monospace;

    font-size: 16px;

    font-weight: 700;

    letter-spacing: .3px;
}

#password:focus {

    border-color: #635bff;

    box-shadow:
        0 0 0 3px
        rgba(99,91,255,.08);
}

.copy-password {

    flex: 0 0 auto;

    min-width: 105px;

    border: 0;

    border-radius: 13px;

    background: #635bff;

    color: white;

    cursor: pointer;

    font-size: 13px;

    font-weight: 800;
}

.copy-password:hover {

    background: #5148e8;
}


/* ============================================================
   STRENGTH
============================================================ */

.strength-wrap {

    margin-top: 17px;
}

.strength-top {

    display: flex;

    justify-content: space-between;

    align-items: center;

    margin-bottom: 8px;
}

.strength-top span {

    color: #707b8e;

    font-size: 12px;

    font-weight: 700;
}

#strengthText {

    color: #635bff;

    font-weight: 800;
}

.strength-bar {

    width: 100%;

    height: 8px;

    overflow: hidden;

    border-radius: 20px;

    background: #e9ebf1;
}

#strengthFill {

    width: 0%;

    height: 100%;

    border-radius: 20px;

    background: #635bff;

    transition: width .25s ease;
}


/* ============================================================
   SETTINGS
============================================================ */

.settings {

    margin-top: 25px;

    padding: 22px;

    border:
        1px solid #e5e9f0;

    border-radius: 17px;

    background: #fafbff;
}

.settings-title {

    margin-bottom: 18px;

    font-size: 16px;

    font-weight: 800;
}


/* ============================================================
   LENGTH
============================================================ */

.length-row {

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 15px;

    margin-bottom: 17px;
}

.length-row label {

    color: #4d5768;

    font-size: 13px;

    font-weight: 700;
}

#length {

    width: 100%;

    accent-color: #635bff;
}

#lengthValue {

    flex: 0 0 55px;

    padding: 7px;

    border-radius: 9px;

    background: #eeedff;

    color: #635bff;

    text-align: center;

    font-size: 13px;

    font-weight: 800;
}


/* ============================================================
   OPTIONS
============================================================ */

.options {

    display: grid;

    grid-template-columns:
        repeat(2, 1fr);

    gap: 11px;
}

.option {

    display: flex;

    align-items: center;

    gap: 9px;

    padding: 12px;

    border:
        1px solid #e5e9f0;

    border-radius: 11px;

    background: white;

    cursor: pointer;
}

.option input {

    width: 17px;

    height: 17px;

    accent-color: #635bff;
}

.option span {

    color: #4d5768;

    font-size: 12px;

    font-weight: 700;
}


/* ============================================================
   GENERATE BUTTON
============================================================ */

.generate-area {

    margin-top: 20px;

    display: flex;

    justify-content: center;
}

.generate-btn {

    min-height: 48px;

    padding:
        12px 27px;

    border: 0;

    border-radius: 13px;

    background: #635bff;

    color: white;

    cursor: pointer;

    font-size: 14px;

    font-weight: 800;

    box-shadow:
        0 10px 24px
        rgba(99,91,255,.17);

    transition:
        transform .2s,
        background .2s;
}

.generate-btn:hover {

    background: #5148e8;

    transform:
        translateY(-1px);
}


/* ============================================================
   INFO
============================================================ */

.info-box {

    margin-top: 24px;

    padding: 25px;

    background: #ffffff;

    border:
        1px solid #e5e9f0;

    border-radius: 19px;
}

.info-box h2 {

    margin-bottom: 9px;

    font-size: 21px;
}

.info-box p {

    color: #707b8e;

    font-size: 13px;

    line-height: 1.7;
}


/* ============================================================
   FOOTER
============================================================ */

footer {

    padding:
        35px 20px;

    background: #151827;

    color: white;

    text-align: center;
}

footer p {

    margin-top: 6px;

    color: #aeb5c5;

    font-size: 12px;
}


/* ============================================================
   MOBILE
============================================================ */

@media (max-width: 700px) {

    .navbar {
        min-height: 64px;
    }


    .nav-links {

        display: none;

        position: absolute;

        top: 64px;

        left: 0;

        right: 0;

        padding: 16px;

        flex-direction: column;

        background: white;

        border-bottom:
            1px solid #e5e9f0;
    }


    .nav-links.open {
        display: flex;
    }


    .menu-button {
        display: block;
    }


    .page-layout {

        width:
            calc(100% - 20px);

        margin:
            15px auto 40px;

        display: flex;

        flex-direction: column;

        gap: 18px;
    }


    .tool-content {

        width: 100%;

        order: 1;
    }


    .page-layout > .tools-sidebar {

        width: 100%;

        order: 2;

        position: relative;

        top: auto;

        max-height: none;

        margin: 0;
    }


    .tool-header {

        padding:
            28px 18px;
    }


    .tool-header h1 {
        font-size: 36px;
    }


    .generator-card {

        padding: 17px;

        border-radius: 18px;
    }


    .password-output {

        flex-direction: column;
    }


    #password {

        height: 52px;

        font-size: 14px;
    }


    .copy-password {

        width: 100%;

        height: 45px;
    }


    .options {

        grid-template-columns: 1fr;
    }


    .desktop-ad {

        display: none !important;
    }


    .mobile-ad {

        display: flex !important;
    }


    .generate-btn {

        width: 100%;
    }

}


/* ============================================================
   SMALL MOBILE
============================================================ */

@media (max-width: 430px) {

    .tool-header h1 {
        font-size: 33px;
    }

}

</style>

</head>


<body>
<?php require_once dirname(__DIR__) . '/header.php'; ?>


<!-- ============================================================
     HEADER
============================================================ -->




<!-- ============================================================
     PAGE
============================================================ -->

<div class="page-layout">


    <!-- ========================================================
         MAIN TOOL
    ========================================================= -->

    <main class="tool-content">


        <!-- TOP AD -->

        <div class="ad-slot">

            <div class="desktop-ad">

                <?php
                smartToozAd('728x90');
                ?>

            </div>


            <div class="mobile-ad">

                <?php
                smartToozAd('320x50');
                ?>

            </div>

        </div>


        <!-- ====================================================
             TOOL HEADER
        ==================================================== -->

        <section class="tool-header">


            <div class="tool-badge">
                🔐 Security Tool
            </div>


            <h1>

                Password
                <span>Generator</span>

            </h1>


            <p>

                Create strong random passwords with custom
                length, numbers, symbols and letters.

            </p>


        </section>


        <!-- ====================================================
             GENERATOR
        ==================================================== -->

        <section class="generator-card">


            <!-- PASSWORD -->

            <div class="password-output">


                <input
                    type="text"
                    id="password"
                    value=""
                    readonly
                    aria-label="Generated password"
                >


                <button
                    type="button"
                    class="copy-password"
                    id="copyPassword"
                >
                    📋 Copy
                </button>


            </div>


            <!-- =================================================
                 STRENGTH
            ================================================== -->

            <div class="strength-wrap">


                <div class="strength-top">

                    <span>
                        Password Strength
                    </span>

                    <span id="strengthText">
                        -
                    </span>

                </div>


                <div class="strength-bar">

                    <div
                        id="strengthFill"
                    ></div>

                </div>


            </div>


            <!-- =================================================
                 SETTINGS
            ================================================== -->

            <div class="settings">


                <div class="settings-title">
                    Password Settings
                </div>


                <!-- LENGTH -->

                <div class="length-row">


                    <label for="length">
                        Password Length
                    </label>


                    <input
                        type="range"
                        id="length"
                        min="4"
                        max="64"
                        value="16"
                    >


                    <span id="lengthValue">
                        16
                    </span>


                </div>


                <!-- OPTIONS -->

                <div class="options">


                    <label class="option">

                        <input
                            type="checkbox"
                            id="uppercase"
                            checked
                        >

                        <span>
                            Uppercase (A-Z)
                        </span>

                    </label>


                    <label class="option">

                        <input
                            type="checkbox"
                            id="lowercase"
                            checked
                        >

                        <span>
                            Lowercase (a-z)
                        </span>

                    </label>


                    <label class="option">

                        <input
                            type="checkbox"
                            id="numbers"
                            checked
                        >

                        <span>
                            Numbers (0-9)
                        </span>

                    </label>


                    <label class="option">

                        <input
                            type="checkbox"
                            id="symbols"
                            checked
                        >

                        <span>
                            Symbols (!@#$)
                        </span>

                    </label>


                </div>


                <!-- GENERATE -->

                <div class="generate-area">

                    <button
                        type="button"
                        class="generate-btn"
                        id="generateBtn"
                    >
                        🔄 Generate Password
                    </button>

                </div>


            </div>


        </section>


        <!-- ====================================================
             NATIVE AD
        ==================================================== -->

        <div class="ad-slot">

            <?php
            smartToozAd('native');
            ?>

        </div>


        <!-- ====================================================
             300x250 AD
        ==================================================== -->

        <div class="ad-slot">

            <?php
            smartToozAd('300x250');
            ?>

        </div>


        <!-- ====================================================
             INFO
        ==================================================== -->

        <section class="info-box">


            <h2>
                Free Strong Password Generator
            </h2>


            <p>

                Smart-Tooz Password Generator creates random
                passwords directly in your browser. You can
                choose the password length and decide whether
                to include uppercase letters, lowercase
                letters, numbers and symbols. No generated
                password is sent to a server by this tool.

            </p>


        </section>


        <!-- ====================================================
             BOTTOM AD
        ==================================================== -->

        <div class="ad-slot">


            <div class="desktop-ad">

                <?php
                smartToozAd('468x60');
                ?>

            </div>


            <div class="mobile-ad">

                <?php
                smartToozAd('320x50');
                ?>

            </div>


        </div>


    </main>


    <!-- ========================================================
         SIDEBAR
    ========================================================= -->

    <?php
    include __DIR__ . '/tool-sidebar.php';
    ?>


</div>


<!-- ============================================================
     FOOTER
============================================================ -->

<footer>

    <strong>
        Smart-Tooz
    </strong>


    <p>
        Free, fast and simple online tools.
    </p>

</footer>


<script>

/* ============================================================
   ELEMENTS
============================================================ */

const passwordInput =
    document.getElementById(
        'password'
    );

const copyPassword =
    document.getElementById(
        'copyPassword'
    );

const lengthInput =
    document.getElementById(
        'length'
    );

const lengthValue =
    document.getElementById(
        'lengthValue'
    );

const uppercase =
    document.getElementById(
        'uppercase'
    );

const lowercase =
    document.getElementById(
        'lowercase'
    );

const numbers =
    document.getElementById(
        'numbers'
    );

const symbols =
    document.getElementById(
        'symbols'
    );

const generateBtn =
    document.getElementById(
        'generateBtn'
    );

const strengthText =
    document.getElementById(
        'strengthText'
    );

const strengthFill =
    document.getElementById(
        'strengthFill'
    );


/* ============================================================
   CHARACTER SETS
============================================================ */

const CHARSETS = {

    uppercase:
        'ABCDEFGHIJKLMNOPQRSTUVWXYZ',

    lowercase:
        'abcdefghijklmnopqrstuvwxyz',

    numbers:
        '0123456789',

    symbols:
        '!@#$%^&*()_+-=[]{}|;:,.<>?'

};


/* ============================================================
   SECURE RANDOM NUMBER
============================================================ */

function secureRandom(max) {

    if (
        window.crypto &&
        window.crypto.getRandomValues
    ) {

        const array =
            new Uint32Array(1);

        const maxUint =
            0xFFFFFFFF;

        const limit =
            maxUint -
            (
                maxUint %
                max
            );

        let random;

        do {

            window.crypto.getRandomValues(
                array
            );

            random =
                array[0];

        } while (
            random >= limit
        );


        return random % max;

    }


    return Math.floor(
        Math.random() * max
    );

}


/* ============================================================
   RANDOM CHARACTER
============================================================ */

function randomCharacter(charset) {

    return charset[
        secureRandom(
            charset.length
        )
    ];

}


/* ============================================================
   SHUFFLE
============================================================ */

function shuffle(array) {

    for (
        let i = array.length - 1;
        i > 0;
        i--
    ) {

        const j =
            secureRandom(
                i + 1
            );


        const temp =
            array[i];

        array[i] =
            array[j];

        array[j] =
            temp;

    }


    return array;

}


/* ============================================================
   GENERATE PASSWORD
============================================================ */

function generatePassword() {

    const length =
        parseInt(
            lengthInput.value,
            10
        );


    let selectedSets = [];


    if (uppercase.checked) {

        selectedSets.push(
            CHARSETS.uppercase
        );

    }


    if (lowercase.checked) {

        selectedSets.push(
            CHARSETS.lowercase
        );

    }


    if (numbers.checked) {

        selectedSets.push(
            CHARSETS.numbers
        );

    }


    if (symbols.checked) {

        selectedSets.push(
            CHARSETS.symbols
        );

    }


    /* --------------------------------------------------------
       At least one option
    -------------------------------------------------------- */

    if (
        selectedSets.length === 0
    ) {

        lowercase.checked =
            true;

        selectedSets = [
            CHARSETS.lowercase
        ];

    }


    let allCharacters =
        selectedSets.join('');


    let result = [];


    /*
     * Make sure every selected character group
     * gets represented when password length allows it.
     */

    for (
        let i = 0;
        i < selectedSets.length &&
        i < length;
        i++
    ) {

        result.push(
            randomCharacter(
                selectedSets[i]
            )
        );

    }


    while (
        result.length < length
    ) {

        result.push(
            randomCharacter(
                allCharacters
            )
        );

    }


    result =
        shuffle(result);


    passwordInput.value =
        result.join('');


    updateStrength();

}


/* ============================================================
   LENGTH
============================================================ */

lengthInput.addEventListener(
    'input',
    function() {

        lengthValue.textContent =
            this.value;

        generatePassword();

    }
);


/* ============================================================
   OPTIONS
============================================================ */

[
    uppercase,
    lowercase,
    numbers,
    symbols
].forEach(
    function(element) {

        element.addEventListener(
            'change',
            generatePassword
        );

    }
);


/* ============================================================
   GENERATE BUTTON
============================================================ */

generateBtn.addEventListener(
    'click',
    generatePassword
);


/* ============================================================
   PASSWORD STRENGTH
============================================================ */

function updateStrength() {

    const password =
        passwordInput.value;


    if (
        password.length === 0
    ) {

        strengthText.textContent =
            '-';

        strengthFill.style.width =
            '0%';

        return;

    }


    let score = 0;


    /* Length */

    if (
        password.length >= 8
    ) {
        score++;
    }

    if (
        password.length >= 12
    ) {
        score++;
    }

    if (
        password.length >= 20
    ) {
        score++;
    }


    /* Character types */

    if (
        /[A-Z]/.test(password)
    ) {
        score++;
    }

    if (
        /[a-z]/.test(password)
    ) {
        score++;
    }

    if (
        /[0-9]/.test(password)
    ) {
        score++;
    }

    if (
        /[^A-Za-z0-9]/.test(password)
    ) {
        score++;
    }


    let label =
        'Weak';

    let percent =
        25;


    if (score >= 7) {

        label =
            'Very Strong';

        percent =
            100;

    } else if (score >= 5) {

        label =
            'Strong';

        percent =
            80;

    } else if (score >= 3) {

        label =
            'Medium';

        percent =
            55;

    }


    strengthText.textContent =
        label;

    strengthFill.style.width =
        percent + '%';

}


/* ============================================================
   COPY
============================================================ */

copyPassword.addEventListener(
    'click',
    async function() {

        const password =
            passwordInput.value;


        if (
            password === ''
        ) {

            return;

        }


        try {

            await navigator.clipboard.writeText(
                password
            );


            copyPassword.textContent =
                '✓ Copied!';


        } catch (error) {

            passwordInput.select();

            document.execCommand(
                'copy'
            );


            copyPassword.textContent =
                '✓ Copied!';

        }


        setTimeout(
            function() {

                copyPassword.textContent =
                    '📋 Copy';

            },
            1500
        );

    }
);


/* ============================================================
   MOBILE MENU
============================================================ */

function toggleMenu() {

    document
        .getElementById(
            'navLinks'
        )
        .classList.toggle(
            'open'
        );

}


/* ============================================================
   INITIAL PASSWORD
============================================================ */

generatePassword();

</script>



</body>

</html>
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

<title>URL Encoder & Decoder - Smart-Tooz</title>

<meta
    name="description"
    content="Free online URL encoder and decoder. Encode URLs and text with percent encoding or decode encoded URLs instantly."
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
textarea {
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

    max-width: 700px;

    margin:
        13px auto 0;

    color: #707b8e;

    font-size: 14px;
}


/* ============================================================
   TOOL CARD
============================================================ */

.tool-card {

    padding: 25px;

    background: #ffffff;

    border:
        1px solid #e5e9f0;

    border-radius: 22px;

    box-shadow:
        0 15px 45px
        rgba(30,35,80,.06);
}


/* ============================================================
   EDITOR GRID
============================================================ */

.editor-grid {

    display: grid;

    grid-template-columns:
        repeat(2, 1fr);

    gap: 16px;
}

.editor-box {

    min-width: 0;
}

.editor-label {

    display: flex;

    align-items: center;

    justify-content: space-between;

    margin-bottom: 9px;

    color: #3f4858;

    font-size: 13px;

    font-weight: 800;
}

.editor-label span {

    color: #9aa3b2;

    font-size: 10px;

    font-weight: 600;
}


/* ============================================================
   TEXTAREA
============================================================ */

.editor-box textarea {

    width: 100%;

    min-height: 360px;

    resize: vertical;

    padding: 17px;

    border:
        1px solid #dfe3eb;

    border-radius: 15px;

    outline: none;

    background: #fafbff;

    color: #172033;

    font-family:
        "SFMono-Regular",
        Consolas,
        "Liberation Mono",
        monospace;

    font-size: 13px;

    line-height: 1.65;

    transition:
        border-color .2s,
        box-shadow .2s;
}

.editor-box textarea:focus {

    border-color: #635bff;

    background: #ffffff;

    box-shadow:
        0 0 0 3px
        rgba(99,91,255,.08);
}

.editor-box textarea::placeholder {
    color: #9aa3b2;
}


/* ============================================================
   BUTTONS
============================================================ */

.actions {

    display: flex;

    justify-content: center;

    align-items: center;

    flex-wrap: wrap;

    gap: 10px;

    margin-top: 18px;
}

.btn {

    min-height: 44px;

    padding:
        10px 19px;

    border: 0;

    border-radius: 11px;

    cursor: pointer;

    font-size: 12px;

    font-weight: 800;

    transition:
        background .2s,
        transform .2s;
}

.btn:hover {

    transform:
        translateY(-1px);
}

.btn-primary {

    background: #635bff;

    color: white;
}

.btn-primary:hover {
    background: #5148e8;
}

.btn-secondary {

    background: #eef0f5;

    color: #3f4858;
}

.btn-secondary:hover {
    background: #e4e7ed;
}


/* ============================================================
   MODE
============================================================ */

.mode-info {

    margin-top: 14px;

    text-align: center;

    color: #707b8e;

    font-size: 11px;
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

@media (max-width: 800px) {

    .editor-grid {

        grid-template-columns: 1fr;
    }

}


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


    .tool-card {

        padding: 17px;

        border-radius: 18px;
    }


    .editor-box textarea {

        min-height: 280px;

        font-size: 12px;
    }


    .desktop-ad {
        display: none !important;
    }

    .mobile-ad {
        display: flex !important;
    }


    .actions {

        flex-direction: column;
    }

    .actions .btn {

        width: 100%;
    }

}


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
                🔗 Developer Tool
            </div>


            <h1>

                URL
                <span>Encoder</span>

            </h1>


            <p>

                Encode and decode URLs and text instantly
                using standard percent encoding.

            </p>


        </section>


        <!-- ====================================================
             TOOL CARD
        ==================================================== -->

        <section class="tool-card">


            <div class="editor-grid">


                <!-- =================================================
                     INPUT
                ================================================== -->

                <div class="editor-box">


                    <div class="editor-label">

                        <span
                            style="
                                color:#3f4858;
                                font-size:13px;
                            "
                        >
                            Input
                        </span>


                        <span>
                            URL / Text
                        </span>

                    </div>


                    <textarea
                        id="inputText"
                        placeholder="Enter or paste a URL or text here...

Example:
https://example.com/search?q=hello world"
                        spellcheck="false"
                    ></textarea>


                </div>


                <!-- =================================================
                     OUTPUT
                ================================================== -->

                <div class="editor-box">


                    <div class="editor-label">

                        <span
                            style="
                                color:#3f4858;
                                font-size:13px;
                            "
                        >
                            Output
                        </span>


                        <span>
                            Encoded / Decoded
                        </span>

                    </div>


                    <textarea
                        id="outputText"
                        placeholder="Your result will appear here..."
                        spellcheck="false"
                        readonly
                    ></textarea>


                </div>


            </div>


            <!-- =================================================
                 BUTTONS
            ================================================== -->

            <div class="actions">


                <button
                    type="button"
                    class="btn btn-primary"
                    id="encodeBtn"
                >
                    🔐 Encode URL
                </button>


                <button
                    type="button"
                    class="btn btn-secondary"
                    id="decodeBtn"
                >
                    🔓 Decode URL
                </button>


                <button
                    type="button"
                    class="btn btn-secondary"
                    id="copyBtn"
                >
                    📋 Copy Result
                </button>


                <button
                    type="button"
                    class="btn btn-secondary"
                    id="swapBtn"
                >
                    ⇄ Use Result as Input
                </button>


                <button
                    type="button"
                    class="btn btn-secondary"
                    id="clearBtn"
                >
                    Clear
                </button>


            </div>


            <div
                class="mode-info"
                id="modeInfo"
            >
                Ready — enter text above.

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
                Free URL Encoder & Decoder
            </h2>


            <p>

                Smart-Tooz URL Encoder converts characters
                that cannot safely appear in a URL into
                percent-encoded form. The decoder reverses
                this process. This tool works directly in
                your browser and supports normal Unicode
                text as well as URLs.

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

const inputText =
    document.getElementById(
        'inputText'
    );

const outputText =
    document.getElementById(
        'outputText'
    );

const encodeBtn =
    document.getElementById(
        'encodeBtn'
    );

const decodeBtn =
    document.getElementById(
        'decodeBtn'
    );

const copyBtn =
    document.getElementById(
        'copyBtn'
    );

const swapBtn =
    document.getElementById(
        'swapBtn'
    );

const clearBtn =
    document.getElementById(
        'clearBtn'
    );

const modeInfo =
    document.getElementById(
        'modeInfo'
    );


/* ============================================================
   ENCODE
============================================================ */

function encodeURL() {

    const value =
        inputText.value;


    if (
        value === ''
    ) {

        modeInfo.textContent =
            'Please enter text or a URL first.';

        return;

    }


    try {

        /*
         * encodeURIComponent is suitable for encoding
         * individual URL components and general text.
         */

        outputText.value =
            encodeURIComponent(
                value
            );


        modeInfo.textContent =
            '✓ URL/text encoded successfully.';

    } catch (error) {

        modeInfo.textContent =
            'Unable to encode this text.';

    }

}


/* ============================================================
   DECODE
============================================================ */

function decodeURL() {

    const value =
        inputText.value;


    if (
        value === ''
    ) {

        modeInfo.textContent =
            'Please enter encoded text first.';

        return;

    }


    try {

        outputText.value =
            decodeURIComponent(
                value
            );


        modeInfo.textContent =
            '✓ URL/text decoded successfully.';

    } catch (error) {

        outputText.value =
            '';


        modeInfo.textContent =
            '⚠ Invalid URL encoding. Please check the input.';

    }

}


/* ============================================================
   COPY
============================================================ */

copyBtn.addEventListener(
    'click',
    async function() {

        const value =
            outputText.value;


        if (
            value === ''
        ) {

            modeInfo.textContent =
                'Nothing to copy.';

            return;

        }


        try {

            await navigator.clipboard.writeText(
                value
            );


            copyBtn.textContent =
                '✓ Copied!';


            modeInfo.textContent =
                'Result copied to clipboard.';


        } catch (error) {

            outputText.removeAttribute(
                'readonly'
            );

            outputText.select();


            try {

                document.execCommand(
                    'copy'
                );


                copyBtn.textContent =
                    '✓ Copied!';

            } catch (e) {

                modeInfo.textContent =
                    'Copy failed. Please copy manually.';

            }


            outputText.setAttribute(
                'readonly',
                'readonly'
            );

        }


        setTimeout(
            function() {

                copyBtn.textContent =
                    '📋 Copy Result';

            },
            1500
        );

    }
);


/* ============================================================
   SWAP
============================================================ */

swapBtn.addEventListener(
    'click',
    function() {

        if (
            outputText.value === ''
        ) {

            modeInfo.textContent =
                'There is no result to use as input.';

            return;

        }


        inputText.value =
            outputText.value;


        outputText.value =
            '';


        modeInfo.textContent =
            'Result moved to input.';

        inputText.focus();

    }
);


/* ============================================================
   CLEAR
============================================================ */

clearBtn.addEventListener(
    'click',
    function() {

        inputText.value =
            '';

        outputText.value =
            '';

        modeInfo.textContent =
            'Ready — enter text above.';

        inputText.focus();

    }
);


/* ============================================================
   BUTTONS
============================================================ */

encodeBtn.addEventListener(
    'click',
    encodeURL
);

decodeBtn.addEventListener(
    'click',
    decodeURL
);


/* ============================================================
   CTRL + ENTER
============================================================ */

inputText.addEventListener(
    'keydown',
    function(event) {

        if (
            event.key === 'Enter' &&
            (
                event.ctrlKey ||
                event.metaKey
            )
        ) {

            event.preventDefault();

            encodeURL();

        }

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

</script>




<?php require_once dirname(__DIR__) . '/footer.php'; ?>
</body>

</html>
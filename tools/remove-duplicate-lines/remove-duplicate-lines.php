<?php
declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);


/* ============================================================
   ANALYTICS TRACKER
============================================================ */



/*
|--------------------------------------------------------------------------
| Smart-Tooz - Remove Duplicate Lines
|--------------------------------------------------------------------------
| Ads:
|   Loaded directly from ads_settings
|
| Sidebar:
|   tool-sidebar.php
|--------------------------------------------------------------------------
*/


/* ============================================================
   DATABASE
============================================================ */

require_once $_SERVER['DOCUMENT_ROOT'] . '/creator-ai/auth/config.php';

$pdo = db();


/* ============================================================
   LOAD ADS FROM DATABASE
============================================================ */

$smartToozAds = [];

try {

    $stmt = $pdo->query("
        SELECT
            ad_key,
            enabled,
            ad_code
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

<title>Remove Duplicate Lines - Smart-Tooz</title>

<meta
    name="description"
    content="Remove duplicate lines from text online. Clean lists, remove repeated lines, trim whitespace and sort text with Smart-Tooz."
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
textarea,
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


/* ============================================================
   LOGO
============================================================ */

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


/* ============================================================
   NAVIGATION
============================================================ */

.nav-links {

    display: flex;

    align-items: center;

    gap: 28px;
}

.nav-links a {

    color: #596477;

    font-size: 14px;

    font-weight: 600;

    transition: .2s;
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


/* ============================================================
   MAIN CONTENT
============================================================ */

.tool-content {

    min-width: 0;

    flex: 1;

    order: 1;
}


/* ============================================================
   SIDEBAR
============================================================ */

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


/* ============================================================
   DESKTOP / MOBILE ADS
============================================================ */

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
        repeat(2, minmax(0, 1fr));

    gap: 16px;
}

.editor-box {

    min-width: 0;
}

.editor-label {

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 10px;

    margin-bottom: 9px;

    color: #3f4858;

    font-size: 13px;

    font-weight: 800;
}

.editor-label small {

    color: #9aa3b2;

    font-size: 10px;

    font-weight: 600;
}


/* ============================================================
   TEXTAREA
============================================================ */

.editor-box textarea {

    width: 100%;

    min-height: 380px;

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
   OPTIONS
============================================================ */

.options {

    display: flex;

    justify-content: center;

    align-items: center;

    flex-wrap: wrap;

    gap: 10px;

    margin-top: 18px;

    padding: 15px;

    border:
        1px solid #e5e9f0;

    border-radius: 13px;

    background: #fafbff;
}

.option {

    display: flex;

    align-items: center;

    gap: 7px;

    padding:
        7px 9px;

    color: #596477;

    font-size: 12px;

    font-weight: 700;

    cursor: pointer;
}

.option input {

    width: 16px;

    height: 16px;

    accent-color: #635bff;

    cursor: pointer;
}


/* ============================================================
   ACTION BUTTONS
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
        10px 18px;

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
   RESULT INFO
============================================================ */

.result-info {

    margin-top: 14px;

    text-align: center;

    color: #707b8e;

    font-size: 11px;
}


/* ============================================================
   INFO BOX
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
   TABLET
============================================================ */

@media (max-width: 900px) {

    .editor-grid {

        grid-template-columns: 1fr;
    }

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

        background: #ffffff;

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

        min-height: 290px;

        font-size: 12px;
    }


    .options {

        flex-direction: column;

        align-items: flex-start;
    }


    .actions {

        flex-direction: column;
    }


    .actions .btn {

        width: 100%;
    }


    .desktop-ad {

        display: none !important;
    }


    .mobile-ad {

        display: flex !important;
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
     PAGE LAYOUT
============================================================ -->

<div class="page-layout">


    <!-- ========================================================
         MAIN CONTENT
    ========================================================= -->

    <main class="tool-content">


        <!-- ====================================================
             TOP AD
        ==================================================== -->

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
                🧹 Text Tool
            </div>


            <h1>

                Remove
                <span>Duplicate Lines</span>

            </h1>


            <p>

                Remove repeated lines from your text,
                clean lists and keep only unique lines.

            </p>


        </section>


        <!-- ====================================================
             TOOL
        ==================================================== -->

        <section class="tool-card">


            <div class="editor-grid">


                <!-- =================================================
                     INPUT
                ================================================== -->

                <div class="editor-box">


                    <div class="editor-label">

                        <span>
                            Input
                        </span>

                        <small>
                            Your Text
                        </small>

                    </div>


                    <textarea
                        id="inputText"
                        spellcheck="false"
                        placeholder="Paste your text here...

Example:
Apple
Banana
Apple
Orange
Banana
Mango"
                    ></textarea>


                </div>


                <!-- =================================================
                     OUTPUT
                ================================================== -->

                <div class="editor-box">


                    <div class="editor-label">

                        <span>
                            Output
                        </span>

                        <small>
                            Unique Lines
                        </small>

                    </div>


                    <textarea
                        id="outputText"
                        spellcheck="false"
                        placeholder="Cleaned text will appear here..."
                        readonly
                    ></textarea>


                </div>


            </div>


            <!-- =================================================
                 OPTIONS
            ================================================= -->

            <div class="options">


                <label class="option">

                    <input
                        type="checkbox"
                        id="ignoreCase"
                    >

                    <span>
                        Ignore Case
                    </span>

                </label>


                <label class="option">

                    <input
                        type="checkbox"
                        id="trimLines"
                        checked
                    >

                    <span>
                        Trim Whitespace
                    </span>

                </label>


                <label class="option">

                    <input
                        type="checkbox"
                        id="removeEmpty"
                        checked
                    >

                    <span>
                        Remove Empty Lines
                    </span>

                </label>


                <label class="option">

                    <input
                        type="checkbox"
                        id="sortLines"
                    >

                    <span>
                        Sort A-Z
                    </span>

                </label>


            </div>


            <!-- =================================================
                 ACTIONS
            ================================================== -->

            <div class="actions">


                <button
                    type="button"
                    class="btn btn-primary"
                    id="removeBtn"
                >
                    ✨ Remove Duplicates
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
                class="result-info"
                id="resultInfo"
            >
                Ready — paste your text above.

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
                Free Remove Duplicate Lines Tool
            </h2>


            <p>

                Remove duplicate lines from lists, copied
                text, data and other line-based content.
                The tool keeps the first occurrence of each
                line and removes repeated entries. You can
                optionally ignore letter case, trim whitespace,
                remove empty lines and sort the final result.

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
         TOOL SIDEBAR
    ========================================================= -->

    <?php

    $sidebarFile =
        __DIR__ . '/tool-sidebar.php';

    if (is_file($sidebarFile)) {

        include $sidebarFile;

    }

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

const ignoreCase =
    document.getElementById(
        'ignoreCase'
    );

const trimLines =
    document.getElementById(
        'trimLines'
    );

const removeEmpty =
    document.getElementById(
        'removeEmpty'
    );

const sortLines =
    document.getElementById(
        'sortLines'
    );

const removeBtn =
    document.getElementById(
        'removeBtn'
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

const resultInfo =
    document.getElementById(
        'resultInfo'
    );

const navLinks =
    document.getElementById(
        'navLinks'
    );

const menuButton =
    document.getElementById(
        'menuButton'
    );


/* ============================================================
   REMOVE DUPLICATE LINES
============================================================ */

function removeDuplicateLines() {

    const original =
        inputText.value;


    if (
        original.trim() === ''
    ) {

        outputText.value =
            '';

        resultInfo.textContent =
            'Please enter some text first.';

        return;

    }


    /* --------------------------------------------------------
       Split lines
    -------------------------------------------------------- */

    let lines =
        original.split(/\r?\n/);


    /* --------------------------------------------------------
       Trim whitespace
    -------------------------------------------------------- */

    if (
        trimLines.checked
    ) {

        lines =
            lines.map(
                function(line) {

                    return line.trim();

                }
            );

    }


    /* --------------------------------------------------------
       Remove empty lines
    -------------------------------------------------------- */

    if (
        removeEmpty.checked
    ) {

        lines =
            lines.filter(
                function(line) {

                    return line !== '';

                }
            );

    }


    /* --------------------------------------------------------
       Remove duplicates
    -------------------------------------------------------- */

    const seen =
        new Set();

    const uniqueLines =
        [];


    lines.forEach(
        function(line) {

            const comparison =
                ignoreCase.checked
                    ? line.toLocaleLowerCase()
                    : line;


            if (
                !seen.has(comparison)
            ) {

                seen.add(
                    comparison
                );

                uniqueLines.push(
                    line
                );

            }

        }
    );


    /* --------------------------------------------------------
       Sort A-Z
    -------------------------------------------------------- */

    if (
        sortLines.checked
    ) {

        uniqueLines.sort(
            function(a, b) {

                return a.localeCompare(
                    b,
                    undefined,
                    {
                        sensitivity:
                            ignoreCase.checked
                                ? 'base'
                                : 'variant'
                    }
                );

            }
        );

    }


    /* --------------------------------------------------------
       Output
    -------------------------------------------------------- */

    outputText.value =
        uniqueLines.join('\n');


    const removed =
        lines.length -
        uniqueLines.length;


    resultInfo.textContent =
        uniqueLines.length +
        ' unique line' +
        (
            uniqueLines.length === 1
                ? ''
                : 's'
        ) +
        ' • ' +
        removed +
        ' duplicate' +
        (
            removed === 1
                ? ''
                : 's'
        ) +
        ' removed';

}


/* ============================================================
   COPY RESULT
============================================================ */

async function copyResult() {

    const text =
        outputText.value;


    if (
        text === ''
    ) {

        resultInfo.textContent =
            'Nothing to copy.';

        return;

    }


    try {

        if (
            navigator.clipboard &&
            window.isSecureContext
        ) {

            await navigator.clipboard.writeText(
                text
            );

        } else {

            outputText.removeAttribute(
                'readonly'
            );

            outputText.focus();

            outputText.select();

            document.execCommand(
                'copy'
            );

            outputText.setAttribute(
                'readonly',
                'readonly'
            );

        }


        copyBtn.textContent =
            '✓ Copied!';

        resultInfo.textContent =
            'Result copied to clipboard.';


    } catch (error) {

        resultInfo.textContent =
            'Copy failed. Please copy the result manually.';

    }


    setTimeout(
        function() {

            copyBtn.textContent =
                '📋 Copy Result';

        },
        1500
    );

}


/* ============================================================
   SWAP RESULT
============================================================ */

function swapResult() {

    if (
        outputText.value === ''
    ) {

        resultInfo.textContent =
            'There is no result to use as input.';

        return;

    }


    inputText.value =
        outputText.value;


    outputText.value =
        '';


    resultInfo.textContent =
        'Result moved to input.';


    inputText.focus();

}


/* ============================================================
   CLEAR
============================================================ */

function clearTool() {

    inputText.value =
        '';

    outputText.value =
        '';


    resultInfo.textContent =
        'Ready — paste your text above.';


    inputText.focus();

}


/* ============================================================
   BUTTON EVENTS
============================================================ */

removeBtn.addEventListener(
    'click',
    removeDuplicateLines
);


copyBtn.addEventListener(
    'click',
    copyResult
);


swapBtn.addEventListener(
    'click',
    swapResult
);


clearBtn.addEventListener(
    'click',
    clearTool
);


/* ============================================================
   LIVE OPTION UPDATE
============================================================ */

[
    ignoreCase,
    trimLines,
    removeEmpty,
    sortLines
].forEach(
    function(element) {

        element.addEventListener(
            'change',
            function() {

                if (
                    inputText.value.trim() !== ''
                ) {

                    removeDuplicateLines();

                }

            }
        );

    }
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

            removeDuplicateLines();

        }

    }
);


/* ============================================================
   MOBILE MENU
============================================================ */

if (menuButton) {

    menuButton.addEventListener(
        'click',
        function() {

            navLinks.classList.toggle(
                'open'
            );

        }
    );

}

</script>




<?php require_once dirname(__DIR__) . '/footer.php'; ?>
</body>

</html>
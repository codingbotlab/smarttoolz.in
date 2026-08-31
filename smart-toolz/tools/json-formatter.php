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

<title>JSON Formatter & Validator - Smart-Tooz</title>

<meta
    name="description"
    content="Free online JSON formatter, validator and minifier. Format, validate, beautify and minify JSON instantly."
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
select {
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
   EDITOR HEADER
============================================================ */

.editor-top {

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 15px;

    margin-bottom: 12px;
}

.editor-label {

    font-size: 14px;

    font-weight: 800;
}

.status {

    padding:
        6px 11px;

    border-radius: 30px;

    background: #f0f1f5;

    color: #707b8e;

    font-size: 11px;

    font-weight: 800;
}

.status.valid {

    background: #e9f8ef;

    color: #21864b;
}

.status.invalid {

    background: #fff0f0;

    color: #d33b3b;
}


/* ============================================================
   TEXTAREA
============================================================ */

#jsonInput {

    width: 100%;

    min-height: 430px;

    resize: vertical;

    padding: 18px;

    border:
        1px solid #dfe3eb;

    border-radius: 15px;

    outline: none;

    background: #101522;

    color: #d9e2f2;

    font-family:
        "SFMono-Regular",
        Consolas,
        "Liberation Mono",
        monospace;

    font-size: 14px;

    line-height: 1.65;

    tab-size: 2;

    transition:
        border-color .2s,
        box-shadow .2s;
}

#jsonInput:focus {

    border-color: #635bff;

    box-shadow:
        0 0 0 3px
        rgba(99,91,255,.09);
}

#jsonInput::placeholder {
    color: #758095;
}


/* ============================================================
   ERROR BOX
============================================================ */

.error-box {

    display: none;

    margin-top: 13px;

    padding:
        12px 14px;

    border-radius: 11px;

    background: #fff1f1;

    color: #c33b3b;

    font-family:
        "SFMono-Regular",
        Consolas,
        monospace;

    font-size: 12px;

    line-height: 1.5;
}


/* ============================================================
   BUTTONS
============================================================ */

.actions {

    display: flex;

    justify-content: center;

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
   INDENT
============================================================ */

.indent-control {

    display: flex;

    align-items: center;

    justify-content: center;

    gap: 9px;

    margin-top: 17px;
}

.indent-control label {

    color: #707b8e;

    font-size: 12px;

    font-weight: 700;
}

#indent {

    height: 36px;

    padding:
        0 10px;

    border:
        1px solid #dfe3eb;

    border-radius: 9px;

    background: white;

    color: #172033;

    outline: none;

    cursor: pointer;
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


    .tool-card {

        padding: 17px;

        border-radius: 18px;
    }


    #jsonInput {

        min-height: 330px;

        padding: 14px;

        font-size: 12px;
    }


    .editor-top {

        align-items: flex-start;

        flex-direction: column;
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
             HEADER
        ==================================================== -->

        <section class="tool-header">


            <div class="tool-badge">
                🧩 Developer Tool
            </div>


            <h1>

                JSON
                <span>Formatter</span>

            </h1>


            <p>

                Format, validate, beautify and minify
                JSON instantly in your browser.

            </p>


        </section>


        <!-- ====================================================
             TOOL CARD
        ==================================================== -->

        <section class="tool-card">


            <div class="editor-top">


                <span class="editor-label">
                    JSON Input
                </span>


                <span
                    class="status"
                    id="status"
                >
                    Ready
                </span>


            </div>


            <textarea
                id="jsonInput"
                spellcheck="false"
                placeholder='Paste your JSON here...

Example:
{
  "name": "Smart-Tooz",
  "tools": 50,
  "free": true
}'
            ></textarea>


            <!-- ERROR -->

            <div
                class="error-box"
                id="errorBox"
            ></div>


            <!-- =================================================
                 ACTIONS
            ================================================== -->

            <div class="actions">


                <button
                    type="button"
                    class="btn btn-primary"
                    id="formatBtn"
                >
                    ✨ Format JSON
                </button>


                <button
                    type="button"
                    class="btn btn-secondary"
                    id="minifyBtn"
                >
                    Minify
                </button>


                <button
                    type="button"
                    class="btn btn-secondary"
                    id="validateBtn"
                >
                    ✓ Validate
                </button>


                <button
                    type="button"
                    class="btn btn-secondary"
                    id="copyBtn"
                >
                    📋 Copy
                </button>


                <button
                    type="button"
                    class="btn btn-secondary"
                    id="clearBtn"
                >
                    Clear
                </button>


            </div>


            <!-- =================================================
                 INDENT
            ================================================== -->

            <div class="indent-control">


                <label for="indent">
                    Indentation:
                </label>


                <select id="indent">

                    <option value="2">
                        2 Spaces
                    </option>

                    <option value="4">
                        4 Spaces
                    </option>

                    <option value="1">
                        1 Space
                    </option>

                    <option value="tab">
                        Tab
                    </option>

                </select>


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
                Free Online JSON Formatter
            </h2>


            <p>

                Smart-Tooz JSON Formatter helps developers
                format and validate JSON data quickly. Paste
                your JSON into the editor and use Format JSON
                to make it readable, Validate to check whether
                it is valid JSON, or Minify to remove unnecessary
                whitespace. Processing happens directly in
                your browser.

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

const jsonInput =
    document.getElementById(
        'jsonInput'
    );

const statusEl =
    document.getElementById(
        'status'
    );

const errorBox =
    document.getElementById(
        'errorBox'
    );

const indentSelect =
    document.getElementById(
        'indent'
    );

const formatBtn =
    document.getElementById(
        'formatBtn'
    );

const minifyBtn =
    document.getElementById(
        'minifyBtn'
    );

const validateBtn =
    document.getElementById(
        'validateBtn'
    );

const copyBtn =
    document.getElementById(
        'copyBtn'
    );

const clearBtn =
    document.getElementById(
        'clearBtn'
    );


/* ============================================================
   GET INDENT
============================================================ */

function getIndent() {

    const value =
        indentSelect.value;


    if (value === 'tab') {
        return '\t';
    }


    return Number(value);

}


/* ============================================================
   PARSE JSON
============================================================ */

function parseJSON() {

    const text =
        jsonInput.value.trim();


    if (text === '') {

        throw new Error(
            'Please enter JSON data.'
        );

    }


    try {

        return JSON.parse(
            text
        );

    } catch (error) {

        throw new Error(
            getReadableJSONError(
                error
            )
        );

    }

}


/* ============================================================
   JSON ERROR
============================================================ */

function getReadableJSONError(error) {

    const message =
        error &&
        error.message
            ? error.message
            : 'Invalid JSON.';


    return message;

}


/* ============================================================
   FORMAT
============================================================ */

function formatJSON() {

    hideError();


    try {

        const data =
            parseJSON();


        jsonInput.value =
            JSON.stringify(
                data,
                null,
                getIndent()
            );


        setStatus(
            'Valid JSON',
            'valid'
        );


    } catch (error) {

        showError(
            error.message
        );

    }

}


/* ============================================================
   MINIFY
============================================================ */

function minifyJSON() {

    hideError();


    try {

        const data =
            parseJSON();


        jsonInput.value =
            JSON.stringify(
                data
            );


        setStatus(
            'Valid JSON',
            'valid'
        );


    } catch (error) {

        showError(
            error.message
        );

    }

}


/* ============================================================
   VALIDATE
============================================================ */

function validateJSON() {

    hideError();


    try {

        parseJSON();


        setStatus(
            '✓ Valid JSON',
            'valid'
        );


    } catch (error) {

        setStatus(
            '✕ Invalid JSON',
            'invalid'
        );


        showError(
            error.message
        );

    }

}


/* ============================================================
   STATUS
============================================================ */

function setStatus(
    text,
    type
) {

    statusEl.textContent =
        text;


    statusEl.classList.remove(
        'valid',
        'invalid'
    );


    if (type) {

        statusEl.classList.add(
            type
        );

    }

}


/* ============================================================
   ERROR
============================================================ */

function showError(message) {

    errorBox.textContent =
        '⚠ ' + message;

    errorBox.style.display =
        'block';

}


function hideError() {

    errorBox.textContent =
        '';

    errorBox.style.display =
        'none';

}


/* ============================================================
   LIVE VALIDATION
============================================================ */

jsonInput.addEventListener(
    'input',
    function() {

        hideError();

        setStatus(
            'Ready',
            ''
        );

    }
);


/* ============================================================
   FORMAT BUTTON
============================================================ */

formatBtn.addEventListener(
    'click',
    formatJSON
);


/* ============================================================
   MINIFY BUTTON
============================================================ */

minifyBtn.addEventListener(
    'click',
    minifyJSON
);


/* ============================================================
   VALIDATE BUTTON
============================================================ */

validateBtn.addEventListener(
    'click',
    validateJSON
);


/* ============================================================
   COPY
============================================================ */

copyBtn.addEventListener(
    'click',
    async function() {

        const text =
            jsonInput.value;


        if (
            text.trim() === ''
        ) {

            copyBtn.textContent =
                'Nothing to Copy';


            setTimeout(
                function() {

                    copyBtn.textContent =
                        '📋 Copy';

                },
                1500
            );

            return;

        }


        try {

            await navigator.clipboard.writeText(
                text
            );


            copyBtn.textContent =
                '✓ Copied!';


        } catch (error) {

            jsonInput.focus();

            jsonInput.select();


            try {

                document.execCommand(
                    'copy'
                );


                copyBtn.textContent =
                    '✓ Copied!';

            } catch (e) {

                copyBtn.textContent =
                    'Copy Failed';

            }

        }


        setTimeout(
            function() {

                copyBtn.textContent =
                    '📋 Copy';

            },
            1500
        );

    }
);


/* ============================================================
   CLEAR
============================================================ */

clearBtn.addEventListener(
    'click',
    function() {

        jsonInput.value =
            '';

        hideError();

        setStatus(
            'Ready',
            ''
        );

        jsonInput.focus();

    }
);


/* ============================================================
   TAB SUPPORT
============================================================ */

jsonInput.addEventListener(
    'keydown',
    function(event) {

        if (
            event.key === 'Tab'
        ) {

            event.preventDefault();


            const start =
                this.selectionStart;

            const end =
                this.selectionEnd;


            const indent =
                indentSelect.value === 'tab'
                    ? '\t'
                    : ' '.repeat(
                        Number(
                            indentSelect.value
                        )
                    );


            this.value =
                this.value.substring(
                    0,
                    start
                ) +
                indent +
                this.value.substring(
                    end
                );


            this.selectionStart =
                this.selectionEnd =
                    start +
                    indent.length;

        }


        /* Ctrl/Cmd + Enter = Format */

        if (
            event.key === 'Enter' &&
            (
                event.ctrlKey ||
                event.metaKey
            )
        ) {

            event.preventDefault();

            formatJSON();

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


<style id="smarttoolz-global-sidebar-css">.smarttoolz-global-sidebar{position:fixed;right:18px;top:88px;width:280px;max-height:calc(100vh - 108px);overflow:auto;z-index:9990;background:#fff;border:1px solid #e5e9f0;border-radius:18px;padding:14px;box-shadow:0 18px 50px rgba(20,30,70,.12)}.st-sidebar-head{display:flex;justify-content:space-between;align-items:center;padding:4px 7px 10px}.st-sidebar-head button{border:0;background:transparent;font-size:24px;cursor:pointer;color:#69758a}.st-sidebar-list a{display:block;padding:9px 10px;margin:2px 0;border-radius:9px;color:#536075;text-decoration:none;font:700 13px/1.25 Inter,system-ui,Arial,sans-serif}.st-sidebar-list a:hover,.st-sidebar-list a.active{background:#f0efff;color:#635bff}.st-sidebar-list .st-all{margin-top:9px;border-top:1px solid #e9ecf2;padding-top:13px}.smarttoolz-tools-toggle{display:none;position:fixed;right:14px;bottom:18px;z-index:9991;border:0;border-radius:12px;background:#635bff;color:#fff;padding:11px 14px;font-weight:800;box-shadow:0 10px 30px rgba(30,30,90,.2)}@media(max-width:900px){.smarttoolz-global-sidebar{right:12px;top:76px;width:min(310px,calc(100vw - 24px));max-height:calc(100vh - 94px);display:none}.smarttoolz-global-sidebar.open{display:block}.smarttoolz-tools-toggle{display:block}}body.smarttoolz-sidebar-page{padding-right:315px}@media(max-width:900px){body.smarttoolz-sidebar-page{padding-right:0}}</style><aside id="smarttoolz-global-sidebar" class="smarttoolz-global-sidebar" aria-label="All Tools"><div class="st-sidebar-head"><strong>All Tools</strong><button type="button" aria-label="Close tools">×</button></div><div class="st-sidebar-list"><a href="/smart-toolz/tools/image-compressor.php">Image Compressor</a><a href="/smart-toolz/tools/png-to-jpg.php">PNG to JPG</a><a href="/smart-toolz/tools/jpg-to-webp.php">JPG to WebP</a><a href="/smart-toolz/tools/png-to-webp.php">PNG to WebP</a><a href="/smart-toolz/tools/gif-maker.php">GIF Maker</a><a href="/smart-toolz/tools/gif-to-jpg.php">GIF to JPG</a><a href="/smart-toolz/tools/jpg-to-png.php">JPG to PNG</a><a href="/smart-toolz/tools/pdf-to-jpg.php">PDF to JPG</a><a href="/smart-toolz/tools/pdf-to-png.php">PDF to PNG</a><a href="/smart-toolz/tools/pdf-merger.php">PDF Merger</a><a href="/smart-toolz/tools/pdf-splitter.php">PDF Splitter</a><a href="/smart-toolz/tools/pdf-compressor.php">PDF Compressor</a><a href="/smart-toolz/tools/qr-generator.php">QR Generator</a><a href="/smart-toolz/tools/qr-reader.php">QR Reader</a><a href="/smart-toolz/tools/age-calculator.php">Age Calculator</a><a href="/smart-toolz/tools/percentage-calculator.php">Percentage Calculator</a><a href="/smart-toolz/tools/bmi-calculator.php">BMI Calculator</a><a href="/smart-toolz/tools/json-formatter.php">JSON Formatter</a><a href="/smart-toolz/tools/word-counter.php">Word Counter</a><a href="/smart-toolz/tools/case-converter.php">Case Converter</a><a href="/smart-toolz/tools/base64-encoder.php">Base64 Encoder</a><a href="/smart-toolz/tools/url-encoder.php">URL Encoder</a><a class="st-all" href="/smart-toolz/tool.php">View All Tools →</a></div></aside><button id="smarttoolz-tools-toggle" class="smarttoolz-tools-toggle" type="button">☰ Tools</button><script>(function(){const s=document.getElementById('smarttoolz-global-sidebar');if(!s)return;const current=location.pathname.split('/').pop();s.querySelectorAll('a').forEach(a=>{if(a.pathname.split('/').pop()===current)a.classList.add('active')});const toggle=document.getElementById('smarttoolz-tools-toggle'),close=s.querySelector('button');toggle?.addEventListener('click',()=>s.classList.toggle('open'));close?.addEventListener('click',()=>s.classList.remove('open'));if(window.innerWidth>900)document.body.classList.add('smarttoolz-sidebar-page')})();</script>
</body>

</html>
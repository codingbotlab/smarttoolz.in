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

<title>
QR Code Generator - Smart-Tooz
</title>

<meta
    name="description"
    content="Create QR codes online for free. Generate QR codes for URLs, text, phone numbers, email and more with Smart-Tooz."
>

<meta
    name="robots"
    content="index, follow"
>

<link
    rel="canonical"
    href="https://smarttoolz.in/smart-toolz/tools/qr-generator.php"
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
input,
select,
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


/* ============================================================
   MAIN TOOL
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
   QR CARD
============================================================ */

.qr-card {

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
   INPUT
============================================================ */

.input-label {

    display: block;

    margin-bottom: 8px;

    color: #4d5768;

    font-size: 13px;

    font-weight: 800;
}

#qrText {

    width: 100%;

    min-height: 135px;

    padding: 14px;

    resize: vertical;

    border:
        1px solid #dce1e9;

    border-radius: 13px;

    outline: none;

    background: #ffffff;

    color: #172033;

    font-size: 14px;

    line-height: 1.6;
}

#qrText:focus {

    border-color: #635bff;

    box-shadow:
        0 0 0 3px
        rgba(99,91,255,.09);
}


/* ============================================================
   OPTIONS
============================================================ */

.qr-options {

    display: grid;

    grid-template-columns:
        repeat(3, 1fr);

    gap: 12px;

    margin-top: 18px;
}

.option {

    min-width: 0;
}

.option label {

    display: block;

    margin-bottom: 6px;

    color: #667184;

    font-size: 11px;

    font-weight: 800;
}

.option select {

    width: 100%;

    height: 42px;

    padding:
        0 10px;

    border:
        1px solid #dce1e9;

    border-radius: 10px;

    background: #ffffff;

    outline: none;

    color: #273044;

    font-size: 12px;
}

.option input[type="color"] {

    width: 100%;

    height: 42px;

    padding: 3px;

    border:
        1px solid #dce1e9;

    border-radius: 10px;

    background: #ffffff;

    cursor: pointer;
}


/* ============================================================
   ACTIONS
============================================================ */

.actions {

    display: flex;

    justify-content: center;

    align-items: center;

    flex-wrap: wrap;

    gap: 10px;

    margin-top: 20px;
}

.btn {

    display: inline-flex;

    align-items: center;

    justify-content: center;

    min-height: 45px;

    padding:
        11px 20px;

    border: 0;

    border-radius: 12px;

    cursor: pointer;

    font-size: 13px;

    font-weight: 800;

    transition:
        background .2s ease,
        transform .2s ease,
        opacity .2s ease;
}

.btn-primary {

    background: #635bff;

    color: #ffffff;
}

.btn-primary:hover {

    background: #5148e8;

    transform:
        translateY(-1px);
}

.btn-secondary {

    background: #eef0f5;

    color: #3f4858;
}

.btn-secondary:hover {

    background: #e4e7ed;
}

.btn:disabled {

    opacity: .6;

    cursor: not-allowed;
}


/* ============================================================
   ERROR
============================================================ */

.error-box {

    display: none;

    margin-top: 15px;

    padding:
        12px 14px;

    border:
        1px solid #ffd1d1;

    border-radius: 11px;

    background: #fff1f1;

    color: #c33;

    font-size: 13px;

    text-align: center;
}


/* ============================================================
   RESULT
============================================================ */

.result {

    display: none;

    margin-top: 25px;

    padding: 20px;

    border:
        1px solid #e5e9f0;

    border-radius: 17px;

    background: #fafbff;

    text-align: center;
}

.result h2 {

    margin-bottom: 15px;

    font-size: 17px;
}

.qr-preview {

    width: 100%;

    max-width: 430px;

    margin: auto;

    padding: 18px;

    background: #ffffff;

    border:
        1px solid #e4e7ed;

    border-radius: 15px;
}

#qrCanvas {

    display: block;

    width: 300px;

    height: 300px;

    max-width: 100%;

    margin: auto;
}

.result-info {

    display: grid;

    grid-template-columns:
        repeat(2, 1fr);

    gap: 10px;

    max-width: 430px;

    margin:
        15px auto 0;
}

.result-stat {

    padding: 12px;

    border-radius: 11px;

    background: #f1f3f8;
}

.result-stat strong {

    display: block;

    color: #635bff;

    font-size: 15px;
}

.result-stat span {

    color: #737d8e;

    font-size: 10px;
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

    color: #ffffff;

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

        align-items: stretch;

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

    .qr-card {

        padding: 17px;

        border-radius: 18px;
    }

    .qr-options {

        grid-template-columns: 1fr;

        gap: 10px;
    }

    .actions {

        flex-direction: column;
    }

    .actions .btn {

        width: 100%;
    }

    .result-info {

        grid-template-columns: 1fr;
    }

    .desktop-ad {

        display: none !important;
    }

    .mobile-ad {

        display: flex !important;
    }

    #qrCanvas {

        width: 250px;

        height: 250px;
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
     GLOBAL ADS
============================================================ -->

<?php
smartToozAd('popunder');
smartToozAd('socialbar');
?>


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
                🔳 QR Tool
            </div>

            <h1>
                QR Code
                <span>Generator</span>
            </h1>

            <p>
                Create a QR code for URLs, text, phone numbers,
                email addresses and other information instantly.
            </p>

        </section>


        <!-- ====================================================
             QR GENERATOR
        ==================================================== -->

        <section class="qr-card">


            <label
                class="input-label"
                for="qrText"
            >
                Text, URL or Information
            </label>


            <textarea
                id="qrText"
                placeholder="Enter your URL, text, phone number, email or any information..."
            ></textarea>


            <!-- OPTIONS -->

            <div class="qr-options">


                <div class="option">

                    <label for="qrSize">
                        QR Size
                    </label>

                    <select id="qrSize">

                        <option value="200">
                            200 × 200
                        </option>

                        <option
                            value="300"
                            selected
                        >
                            300 × 300
                        </option>

                        <option value="400">
                            400 × 400
                        </option>

                        <option value="500">
                            500 × 500
                        </option>

                    </select>

                </div>


                <div class="option">

                    <label for="qrColor">
                        QR Color
                    </label>

                    <input
                        type="color"
                        id="qrColor"
                        value="#000000"
                    >

                </div>


                <div class="option">

                    <label for="bgColor">
                        Background
                    </label>

                    <input
                        type="color"
                        id="bgColor"
                        value="#ffffff"
                    >

                </div>


            </div>


            <!-- BUTTONS -->

            <div class="actions">

                <button
                    type="button"
                    class="btn btn-primary"
                    id="generateBtn"
                >
                    Generate QR
                </button>


                <button
                    type="button"
                    class="btn btn-secondary"
                    id="clearBtn"
                >
                    Clear
                </button>

            </div>


            <!-- ERROR -->

            <div
                class="error-box"
                id="errorBox"
            ></div>


            <!-- RESULT -->

            <div
                class="result"
                id="result"
            >

                <h2>
                    Your QR Code
                </h2>


                <div class="qr-preview">

                    <canvas
                        id="qrCanvas"
                        width="300"
                        height="300"
                    ></canvas>

                </div>


                <div class="result-info">


                    <div class="result-stat">

                        <strong id="qrDimensions">
                            300 × 300
                        </strong>

                        <span>
                            QR Size
                        </span>

                    </div>


                    <div class="result-stat">

                        <strong>
                            PNG
                        </strong>

                        <span>
                            Output Format
                        </span>

                    </div>


                </div>


                <div class="actions">


                    <button
                        type="button"
                        class="btn btn-primary"
                        id="downloadBtn"
                    >
                        ⬇ Download PNG
                    </button>


                    <button
                        type="button"
                        class="btn btn-secondary"
                        id="copyBtn"
                    >
                        Copy QR
                    </button>


                </div>


            </div>


        </section>


        <!-- NATIVE AD -->

        <div class="ad-slot">

            <?php
            smartToozAd('native');
            ?>

        </div>


        <!-- 300x250 -->

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
                Free QR Code Generator
            </h2>

            <p>
                Smart-Tooz QR Code Generator lets you create
                QR codes directly in your browser. Enter a
                website URL, text, phone number, email address
                or any other information and generate a
                downloadable PNG QR code. Your entered
                information is processed in your browser.
            </p>

        </section>


        <!-- BOTTOM AD -->

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


<!-- ============================================================
     QR LIBRARY
============================================================ -->

<script
    src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"
></script>


<script>

/* ============================================================
   ELEMENTS
============================================================ */

const qrText =
    document.getElementById('qrText');

const qrSize =
    document.getElementById('qrSize');

const qrColor =
    document.getElementById('qrColor');

const bgColor =
    document.getElementById('bgColor');

const generateBtn =
    document.getElementById('generateBtn');

const clearBtn =
    document.getElementById('clearBtn');

const downloadBtn =
    document.getElementById('downloadBtn');

const copyBtn =
    document.getElementById('copyBtn');

const errorBox =
    document.getElementById('errorBox');

const result =
    document.getElementById('result');

const canvas =
    document.getElementById('qrCanvas');

const qrDimensions =
    document.getElementById('qrDimensions');


/* ============================================================
   STATE
============================================================ */

let generated = false;


/* ============================================================
   ERROR
============================================================ */

function showError(message) {

    errorBox.textContent = message;

    errorBox.style.display = 'block';

}


function clearError() {

    errorBox.textContent = '';

    errorBox.style.display = 'none';

}


/* ============================================================
   GENERATE QR
============================================================ */

function generateQR() {

    clearError();

    const text =
        qrText.value.trim();

    if (!text) {

        showError(
            'Please enter some text or a URL first.'
        );

        qrText.focus();

        return;
    }


    if (
        typeof QRCode === 'undefined'
    ) {

        showError(
            'QR generator is still loading. Please try again.'
        );

        return;
    }


    const size =
        parseInt(
            qrSize.value,
            10
        );


    const color =
        qrColor.value;

    const background =
        bgColor.value;


    /*
     * Temporary QR container.
     */

    const container =
        document.createElement('div');


    container.style.position =
        'fixed';

    container.style.left =
        '-10000px';

    container.style.top =
        '0';

    container.style.width =
        size + 'px';

    container.style.height =
        size + 'px';

    container.style.background =
        background;


    document.body.appendChild(
        container
    );


    try {

        new QRCode(
            container,
            {
                text: text,

                width: size,

                height: size,

                colorDark: color,

                colorLight: background,

                correctLevel:
                    QRCode.CorrectLevel.H
            }
        );


        setTimeout(
            function() {

                const generatedCanvas =
                    container.querySelector('canvas');


                const generatedImage =
                    container.querySelector('img');


                if (
                    !generatedCanvas &&
                    !generatedImage
                ) {

                    container.remove();

                    showError(
                        'Unable to generate the QR code.'
                    );

                    return;
                }


                canvas.width =
                    size;

                canvas.height =
                    size;


                canvas.style.width =
                    size + 'px';

                canvas.style.height =
                    size + 'px';


                const ctx =
                    canvas.getContext('2d');


                if (!ctx) {

                    container.remove();

                    showError(
                        'Your browser does not support QR generation.'
                    );

                    return;
                }


                ctx.clearRect(
                    0,
                    0,
                    size,
                    size
                );


                ctx.fillStyle =
                    background;


                ctx.fillRect(
                    0,
                    0,
                    size,
                    size
                );


                if (generatedCanvas) {

                    ctx.drawImage(
                        generatedCanvas,
                        0,
                        0,
                        size,
                        size
                    );


                    finishGeneration(
                        size
                    );

                    container.remove();

                    return;
                }


                if (generatedImage) {

                    const image =
                        new Image();


                    image.onload =
                        function() {

                            ctx.drawImage(
                                image,
                                0,
                                0,
                                size,
                                size
                            );


                            finishGeneration(
                                size
                            );

                            container.remove();

                        };


                    image.onerror =
                        function() {

                            container.remove();

                            showError(
                                'Unable to load the generated QR code.'
                            );

                        };


                    image.src =
                        generatedImage.src;

                    return;
                }


                container.remove();

                showError(
                    'Unable to generate the QR code.'
                );

            },
            150
        );


    } catch (error) {

        container.remove();

        showError(
            'Unable to generate QR code. Please try again.'
        );

    }

}


/* ============================================================
   FINISH GENERATION
============================================================ */

function finishGeneration(size) {

    generated =
        true;


    qrDimensions.textContent =
        size + ' × ' + size;


    result.style.display =
        'block';


    result.scrollIntoView({
        behavior: 'smooth',
        block: 'start'
    });

}


/* ============================================================
   DOWNLOAD
============================================================ */

function downloadQR() {

    clearError();


    if (!generated) {

        showError(
            'Generate a QR code first.'
        );

        return;
    }


    try {

        canvas.toBlob(
            function(blob) {

                if (!blob) {

                    showError(
                        'Unable to create PNG image.'
                    );

                    return;
                }


                const url =
                    URL.createObjectURL(blob);


                const link =
                    document.createElement('a');


                link.href =
                    url;

                link.download =
                    'smart-tooz-qr-code.png';


                document.body.appendChild(
                    link
                );


                link.click();


                link.remove();


                setTimeout(
                    function() {

                        URL.revokeObjectURL(
                            url
                        );

                    },
                    1000
                );

            },
            'image/png'
        );

    } catch (error) {

        showError(
            'Unable to download QR code.'
        );

    }

}


/* ============================================================
   COPY QR
============================================================ */

async function copyQR() {

    clearError();


    if (!generated) {

        showError(
            'Generate a QR code first.'
        );

        return;
    }


    if (
        !navigator.clipboard ||
        typeof ClipboardItem === 'undefined'
    ) {

        showError(
            'Copy is not supported by this browser. Please use Download PNG.'
        );

        return;
    }


    try {

        const blob =
            await new Promise(
                function(resolve) {

                    canvas.toBlob(
                        resolve,
                        'image/png'
                    );

                }
            );


        if (!blob) {

            throw new Error(
                'Unable to create image.'
            );

        }


        await navigator.clipboard.write([
            new ClipboardItem({
                'image/png': blob
            })
        ]);


        const originalText =
            copyBtn.textContent;


        copyBtn.textContent =
            '✓ Copied';


        setTimeout(
            function() {

                copyBtn.textContent =
                    originalText;

            },
            1800
        );


    } catch (error) {

        showError(
            'Unable to copy QR code. Please use Download PNG.'
        );

    }

}


/* ============================================================
   CLEAR
============================================================ */

function clearQR() {

    qrText.value =
        '';


    result.style.display =
        'none';


    generated =
        false;


    clearError();


    const ctx =
        canvas.getContext('2d');


    if (ctx) {

        ctx.clearRect(
            0,
            0,
            canvas.width,
            canvas.height
        );

    }


    qrText.focus();

}


/* ============================================================
   EVENTS
============================================================ */

generateBtn.addEventListener(
    'click',
    generateQR
);


downloadBtn.addEventListener(
    'click',
    downloadQR
);


copyBtn.addEventListener(
    'click',
    copyQR
);


clearBtn.addEventListener(
    'click',
    clearQR
);


/* ============================================================
   CTRL + ENTER
============================================================ */

qrText.addEventListener(
    'keydown',
    function(event) {

        if (
            event.ctrlKey &&
            event.key === 'Enter'
        ) {

            event.preventDefault();

            generateQR();

        }

    }
);


/* ============================================================
   MOBILE MENU
============================================================ */

function toggleMenu() {

    document
        .getElementById('navLinks')
        .classList.toggle('open');

}

</script>



</body>

</html>
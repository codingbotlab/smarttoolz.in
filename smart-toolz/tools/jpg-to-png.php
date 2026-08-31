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

<title>JPG to PNG Converter - Smart-Tooz</title>

<meta
    name="description"
    content="Convert JPG and JPEG images to PNG online for free with Smart-Tooz."
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
   DESKTOP:
   TOOL LEFT
   SIDEBAR RIGHT
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
   CONVERTER CARD
============================================================ */

.converter-card {

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
   UPLOAD
============================================================ */

.upload-area {

    width: 100%;

    padding:
        55px 20px;

    border:
        2px dashed #d7dbea;

    border-radius: 19px;

    background: #fafbff;

    text-align: center;

    cursor: pointer;

    transition:
        border-color .2s ease,
        background .2s ease,
        transform .2s ease;
}

.upload-area:hover {

    border-color: #635bff;

    background: #f5f3ff;

    transform:
        translateY(-1px);
}

.upload-area.dragover {

    border-color: #635bff;

    background: #f0eeff;
}

.upload-icon {

    width: 66px;

    height: 66px;

    display: grid;

    place-items: center;

    margin:
        0 auto 15px;

    border-radius: 18px;

    background: #eeedff;

    color: #635bff;

    font-size: 30px;
}

.upload-area h2 {

    margin-bottom: 6px;

    font-size: 20px;
}

.upload-area p {

    color: #707b8e;

    font-size: 13px;
}

.file-info {

    margin-top: 10px;

    color: #635bff !important;

    font-weight: 700;
}

#fileInput {

    display: none;
}


/* ============================================================
   SETTINGS
============================================================ */

.settings {

    display: none;

    margin-top: 22px;

    padding: 21px;

    background: #fafbff;

    border:
        1px solid #e5e9f0;

    border-radius: 17px;
}

.settings-title {

    margin-bottom: 17px;

    font-size: 16px;

    font-weight: 800;
}


/* ============================================================
   QUALITY
============================================================ */

.quality-row {

    margin-top: 5px;
}

.quality-top {

    display: flex;

    align-items: center;

    justify-content: space-between;
}

.quality-top label {

    color: #4d5768;

    font-size: 12px;

    font-weight: 700;
}

.quality-value {

    color: #635bff;

    font-size: 15px;

    font-weight: 800;
}

#quality {

    width: 100%;

    margin-top: 12px;

    accent-color: #635bff;
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

    margin-top: 21px;
}

.btn {

    display: inline-flex;

    align-items: center;

    justify-content: center;

    min-height: 45px;

    padding:
        11px 19px;

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

    color: white;
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

.error {

    display: none;

    margin-top: 15px;

    padding:
        12px 14px;

    border-radius: 11px;

    background: #fff0f0;

    color: #c33;

    font-size: 13px;

    text-align: center;
}


/* ============================================================
   RESULT
============================================================ */

.result {

    display: none;

    margin-top: 28px;
}

.preview-box {

    padding: 15px;

    background: #fafbff;

    border:
        1px solid #e5e9f0;

    border-radius: 17px;

    text-align: center;
}

.preview-box h3 {

    margin-bottom: 12px;

    font-size: 14px;
}

.preview-box img {

    display: block;

    width: 100%;

    max-height: 500px;

    object-fit: contain;

    border-radius: 11px;

    background: white;
}


/* ============================================================
   RESULT INFO
============================================================ */

.result-info {

    display: grid;

    grid-template-columns:
        repeat(3, 1fr);

    gap: 12px;

    margin-top: 16px;
}

.result-stat {

    padding: 15px;

    border-radius: 13px;

    background: #f6f7fb;

    text-align: center;
}

.result-stat strong {

    display: block;

    color: #635bff;

    font-size: 18px;
}

.result-stat span {

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


    /* ========================================================
       MOBILE:
       TOOL FIRST
       SIDEBAR BELOW
    ======================================================== */

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


    .converter-card {

        padding: 17px;

        border-radius: 18px;
    }


    .upload-area {

        padding:
            43px 15px;
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


    .actions {

        flex-direction: column;
    }


    .actions .btn {

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
     PAGE LAYOUT
============================================================ -->

<div class="page-layout">


    <!-- ========================================================
         MAIN TOOL
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
                🖼️ Image Converter
            </div>


            <h1>

                JPG to
                <span>PNG</span>

            </h1>


            <p>

                Convert JPG and JPEG images to PNG format
                quickly and easily in your browser.

            </p>


        </section>


        <!-- ====================================================
             CONVERTER
        ==================================================== -->

        <section class="converter-card">


            <!-- UPLOAD -->

            <div
                class="upload-area"
                id="uploadArea"
            >

                <div class="upload-icon">
                    ⇧
                </div>


                <h2>
                    Drop your JPG here
                </h2>


                <p>
                    or click to choose an image
                </p>


                <p
                    style="
                        margin-top:8px;
                        font-size:12px;
                    "
                >
                    JPG / JPEG • Maximum 20 MB
                </p>


                <p
                    class="file-info"
                    id="fileInfo"
                ></p>


            </div>


            <input
                type="file"
                id="fileInput"
                accept="image/jpeg"
            >


            <!-- =================================================
                 SETTINGS
            ================================================== -->

            <div
                class="settings"
                id="settings"
            >


                <div class="settings-title">
                    PNG Settings
                </div>


                <div class="quality-row">


                    <div class="quality-top">

                        <label for="quality">
                            Image Quality
                        </label>


                        <span
                            class="quality-value"
                            id="qualityValue"
                        >
                            100%
                        </span>

                    </div>


                    <input
                        type="range"
                        id="quality"
                        min="10"
                        max="100"
                        value="100"
                    >


                </div>


                <div class="actions">


                    <button
                        type="button"
                        class="btn btn-primary"
                        id="convertBtn"
                    >
                        Convert to PNG
                    </button>


                    <button
                        type="button"
                        class="btn btn-secondary"
                        id="resetBtn"
                    >
                        Reset
                    </button>


                </div>


            </div>


            <!-- ERROR -->

            <div
                class="error"
                id="error"
            ></div>


            <!-- =================================================
                 RESULT
            ================================================== -->

            <div
                class="result"
                id="result"
            >


                <div class="preview-box">


                    <h3>
                        PNG Preview
                    </h3>


                    <img
                        id="preview"
                        alt="Converted PNG preview"
                    >


                </div>


                <!-- RESULT INFO -->

                <div class="result-info">


                    <div class="result-stat">

                        <strong id="dimensions">
                            -
                        </strong>

                        <span>
                            Dimensions
                        </span>

                    </div>


                    <div class="result-stat">

                        <strong id="fileSize">
                            -
                        </strong>

                        <span>
                            PNG Size
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


                <!-- DOWNLOAD -->

                <div class="actions">


                    <a
                        href="#"
                        id="downloadBtn"
                        class="btn btn-primary"
                        download
                    >
                        ⬇ Download PNG
                    </a>


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
             300x250
        ==================================================== -->

        <div class="ad-slot">

            <?php
            smartToozAd('300x250');
            ?>

        </div>


        <!-- ====================================================
             INFORMATION
        ==================================================== -->

        <section class="info-box">

            <h2>
                Free JPG to PNG Converter
            </h2>


            <p>

                Smart-Tooz JPG to PNG Converter lets you
                convert JPG and JPEG images into PNG format
                directly in your browser. Your image is processed
                locally on your device, so no server upload is
                required. Simply choose a JPG image, convert it,
                preview the result and download your PNG file.

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
         DESKTOP RIGHT
         MOBILE BELOW
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

const uploadArea =
    document.getElementById(
        'uploadArea'
    );

const fileInput =
    document.getElementById(
        'fileInput'
    );

const fileInfo =
    document.getElementById(
        'fileInfo'
    );

const settings =
    document.getElementById(
        'settings'
    );

const quality =
    document.getElementById(
        'quality'
    );

const qualityValue =
    document.getElementById(
        'qualityValue'
    );

const convertBtn =
    document.getElementById(
        'convertBtn'
    );

const resetBtn =
    document.getElementById(
        'resetBtn'
    );

const errorBox =
    document.getElementById(
        'error'
    );

const result =
    document.getElementById(
        'result'
    );

const preview =
    document.getElementById(
        'preview'
    );

const dimensions =
    document.getElementById(
        'dimensions'
    );

const fileSize =
    document.getElementById(
        'fileSize'
    );

const downloadBtn =
    document.getElementById(
        'downloadBtn'
    );


/* ============================================================
   STATE
============================================================ */

let selectedFile = null;

let imageObject = null;

let originalUrl = null;

let pngUrl = null;


/* ============================================================
   CLICK UPLOAD
============================================================ */

uploadArea.addEventListener(
    'click',
    function() {

        fileInput.click();

    }
);


/* ============================================================
   FILE SELECT
============================================================ */

fileInput.addEventListener(
    'change',
    function() {

        if (
            this.files &&
            this.files.length > 0
        ) {

            handleFile(
                this.files[0]
            );

        }

    }
);


/* ============================================================
   DRAG OVER
============================================================ */

uploadArea.addEventListener(
    'dragover',
    function(event) {

        event.preventDefault();

        uploadArea.classList.add(
            'dragover'
        );

    }
);


/* ============================================================
   DRAG LEAVE
============================================================ */

uploadArea.addEventListener(
    'dragleave',
    function() {

        uploadArea.classList.remove(
            'dragover'
        );

    }
);


/* ============================================================
   DROP
============================================================ */

uploadArea.addEventListener(
    'drop',
    function(event) {

        event.preventDefault();

        uploadArea.classList.remove(
            'dragover'
        );


        if (
            event.dataTransfer.files &&
            event.dataTransfer.files.length > 0
        ) {

            handleFile(
                event.dataTransfer.files[0]
            );

        }

    }
);


/* ============================================================
   HANDLE FILE
============================================================ */

function handleFile(file) {

    clearError();


    if (
        file.type !==
        'image/jpeg'
    ) {

        showError(
            'Please select a JPG or JPEG image.'
        );

        return;

    }


    if (
        file.size >
        20 * 1024 * 1024
    ) {

        showError(
            'Maximum file size is 20 MB.'
        );

        return;

    }


    selectedFile =
        file;


    fileInfo.textContent =
        file.name +
        ' • ' +
        formatBytes(
            file.size
        );


    if (originalUrl) {

        URL.revokeObjectURL(
            originalUrl
        );

    }


    originalUrl =
        URL.createObjectURL(
            file
        );


    const img =
        new Image();


    img.onload =
        function() {

            imageObject =
                img;

            settings.style.display =
                'block';

        };


    img.onerror =
        function() {

            showError(
                'Unable to read this image.'
            );

        };


    img.src =
        originalUrl;

}


/* ============================================================
   QUALITY
============================================================ */

quality.addEventListener(
    'input',
    function() {

        qualityValue.textContent =
            this.value +
            '%';

    }
);


/* ============================================================
   CONVERT
============================================================ */

convertBtn.addEventListener(
    'click',
    convertToPng
);


async function convertToPng() {

    clearError();


    if (!selectedFile) {

        showError(
            'Please select a JPG image first.'
        );

        return;

    }


    if (!imageObject) {

        showError(
            'Image is still loading. Please try again.'
        );

        return;

    }


    convertBtn.disabled =
        true;

    convertBtn.textContent =
        'Converting...';


    try {

        const canvas =
            document.createElement(
                'canvas'
            );


        canvas.width =
            imageObject.naturalWidth;

        canvas.height =
            imageObject.naturalHeight;


        const context =
            canvas.getContext(
                '2d'
            );


        if (!context) {

            throw new Error(
                'Your browser does not support image conversion.'
            );

        }


        /*
         * Draw the JPG into canvas.
         * PNG output preserves the image pixels.
         */

        context.drawImage(
            imageObject,
            0,
            0
        );


        const blob =
            await canvasToBlob(
                canvas,
                'image/png'
            );


        if (!blob) {

            throw new Error(
                'Unable to create PNG image.'
            );

        }


        if (pngUrl) {

            URL.revokeObjectURL(
                pngUrl
            );

        }


        pngUrl =
            URL.createObjectURL(
                blob
            );


        preview.src =
            pngUrl;


        dimensions.textContent =
            imageObject.naturalWidth +
            ' × ' +
            imageObject.naturalHeight;


        fileSize.textContent =
            formatBytes(
                blob.size
            );


        downloadBtn.href =
            pngUrl;


        downloadBtn.download =
            createFilename(
                selectedFile.name
            );


        result.style.display =
            'block';


        result.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });


    } catch (error) {

        showError(
            error.message ||
            'Conversion failed.'
        );

    }


    convertBtn.disabled =
        false;

    convertBtn.textContent =
        'Convert to PNG';

}


/* ============================================================
   CANVAS TO BLOB
============================================================ */

function canvasToBlob(
    canvas,
    type
) {

    return new Promise(
        function(resolve) {

            canvas.toBlob(
                function(blob) {

                    resolve(blob);

                },
                type
            );

        }
    );

}


/* ============================================================
   BYTES
============================================================ */

function formatBytes(bytes) {

    if (
        !Number.isFinite(bytes) ||
        bytes <= 0
    ) {

        return '0 Bytes';

    }


    const units = [
        'Bytes',
        'KB',
        'MB',
        'GB'
    ];


    const index =
        Math.min(
            Math.floor(
                Math.log(bytes) /
                Math.log(1024)
            ),
            units.length - 1
        );


    const value =
        bytes /
        Math.pow(
            1024,
            index
        );


    return (
        value.toFixed(
            index === 0
                ? 0
                : 2
        ) +
        ' ' +
        units[index]
    );

}


/* ============================================================
   FILENAME
============================================================ */

function createFilename(filename) {

    const base =
        filename
            .replace(
                /\.[^/.]+$/,
                ''
            )
            .replace(
                /[^a-zA-Z0-9_-]/g,
                '-'
            );


    return (
        base +
        '.png'
    );

}


/* ============================================================
   ERROR
============================================================ */

function showError(message) {

    errorBox.textContent =
        message;

    errorBox.style.display =
        'block';

}


function clearError() {

    errorBox.textContent =
        '';

    errorBox.style.display =
        'none';

}


/* ============================================================
   RESET
============================================================ */

resetBtn.addEventListener(
    'click',
    function() {

        selectedFile =
            null;

        imageObject =
            null;


        if (originalUrl) {

            URL.revokeObjectURL(
                originalUrl
            );

            originalUrl =
                null;

        }


        if (pngUrl) {

            URL.revokeObjectURL(
                pngUrl
            );

            pngUrl =
                null;

        }


        fileInput.value =
            '';


        fileInfo.textContent =
            '';


        preview.removeAttribute(
            'src'
        );


        dimensions.textContent =
            '-';


        fileSize.textContent =
            '-';


        downloadBtn.href =
            '#';


        settings.style.display =
            'none';


        result.style.display =
            'none';


        quality.value =
            '100';


        qualityValue.textContent =
            '100%';


        clearError();

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



</body>

</html>
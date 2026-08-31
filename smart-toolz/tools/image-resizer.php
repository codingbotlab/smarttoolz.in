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

<title>Image Resizer - Smart-Tooz</title>

<meta
    name="description"
    content="Resize JPG, PNG and WebP images online for free with Smart-Tooz."
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
input,
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
   MAIN LAYOUT
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
   TOOL LEFT
============================================================ */

.tool-content {

    min-width: 0;

    flex: 1;

    order: 1;
}


/* ============================================================
   SIDEBAR RIGHT
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

    margin: 0;

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
   RESIZER CARD
============================================================ */

.resizer-card {

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

    padding: 22px;

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
   INPUT GRID
============================================================ */

.dimension-grid {

    display: grid;

    grid-template-columns:
        repeat(2, minmax(0, 1fr));

    gap: 14px;
}

.field label {

    display: block;

    margin-bottom: 7px;

    color: #4d5768;

    font-size: 12px;

    font-weight: 700;
}

.field input,
.field select {

    width: 100%;

    height: 45px;

    padding:
        0 12px;

    border:
        1px solid #dfe3eb;

    border-radius: 10px;

    outline: none;

    background: #ffffff;

    color: #172033;

    font-size: 13px;
}

.field input:focus,
.field select:focus {

    border-color: #635bff;

    box-shadow:
        0 0 0 3px
        rgba(99,91,255,.09);
}


/* ============================================================
   LOCK
============================================================ */

.lock-row {

    display: flex;

    align-items: center;

    gap: 9px;

    margin-top: 16px;

    color: #596477;

    font-size: 13px;

    font-weight: 600;
}

.lock-row input {

    width: 17px;

    height: 17px;

    accent-color: #635bff;

    cursor: pointer;
}


/* ============================================================
   QUALITY
============================================================ */

.quality-row {

    margin-top: 20px;
}

.quality-top {

    display: flex;

    justify-content: space-between;

    align-items: center;

    gap: 10px;
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
   FORMAT
============================================================ */

.format-row {

    margin-top: 18px;
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

.result-preview {

    display: grid;

    grid-template-columns:
        repeat(2, minmax(0, 1fr));

    gap: 18px;
}

.preview-box {

    min-width: 0;

    padding: 14px;

    background: #fafbff;

    border:
        1px solid #e5e9f0;

    border-radius: 17px;
}

.preview-box h3 {

    margin-bottom: 10px;

    font-size: 14px;
}

.preview-box img {

    display: block;

    width: 100%;

    max-height: 400px;

    object-fit: contain;

    border-radius: 11px;

    background: #ffffff;
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
       CONTENT FIRST
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


    .resizer-card {

        padding: 17px;

        border-radius: 18px;
    }


    .upload-area {

        padding:
            43px 15px;
    }


    .dimension-grid {

        grid-template-columns: 1fr;
    }


    .result-preview {

        grid-template-columns: 1fr;
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
     PAGE
============================================================ -->

<div class="page-layout">


    <!-- ========================================================
         TOOL CONTENT
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
             HEADER
        ==================================================== -->

        <section class="tool-header">


            <div class="tool-badge">
                📐 Image Tool
            </div>


            <h1>

                Image
                <span>Resizer</span>

            </h1>


            <p>

                Resize JPG, PNG and WebP images to any
                custom width and height directly in your browser.

            </p>


        </section>


        <!-- ====================================================
             RESIZER
        ==================================================== -->

        <section class="resizer-card">


            <!-- UPLOAD -->

            <div
                class="upload-area"
                id="uploadArea"
            >

                <div class="upload-icon">
                    ↔
                </div>


                <h2>
                    Drop your image here
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
                    JPG, JPEG, PNG, WebP • Maximum 20 MB
                </p>


                <p
                    class="file-info"
                    id="fileInfo"
                ></p>


            </div>


            <input
                type="file"
                id="fileInput"
                accept="image/jpeg,image/png,image/webp"
            >


            <!-- =================================================
                 SETTINGS
            ================================================== -->

            <div
                class="settings"
                id="settings"
            >


                <div class="settings-title">
                    Resize Settings
                </div>


                <div class="dimension-grid">


                    <!-- WIDTH -->

                    <div class="field">

                        <label for="width">
                            Width (px)
                        </label>

                        <input
                            type="number"
                            id="width"
                            min="1"
                            max="10000"
                            placeholder="Width"
                        >

                    </div>


                    <!-- HEIGHT -->

                    <div class="field">

                        <label for="height">
                            Height (px)
                        </label>

                        <input
                            type="number"
                            id="height"
                            min="1"
                            max="10000"
                            placeholder="Height"
                        >

                    </div>


                </div>


                <!-- LOCK -->

                <label class="lock-row">

                    <input
                        type="checkbox"
                        id="lockRatio"
                        checked
                    >

                    <span>
                        Lock aspect ratio
                    </span>

                </label>


                <!-- QUALITY -->

                <div class="quality-row">


                    <div class="quality-top">

                        <label for="quality">
                            Image Quality
                        </label>


                        <span
                            class="quality-value"
                            id="qualityValue"
                        >
                            90%
                        </span>

                    </div>


                    <input
                        type="range"
                        id="quality"
                        min="10"
                        max="100"
                        value="90"
                    >


                </div>


                <!-- FORMAT -->

                <div class="format-row field">

                    <label for="format">
                        Output Format
                    </label>


                    <select id="format">

                        <option value="image/jpeg">
                            JPG
                        </option>

                        <option value="image/png">
                            PNG
                        </option>

                        <option value="image/webp">
                            WebP
                        </option>

                    </select>

                </div>


                <!-- ACTIONS -->

                <div class="actions">


                    <button
                        type="button"
                        class="btn btn-primary"
                        id="resizeBtn"
                    >
                        Resize Image
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


                <div class="result-preview">


                    <!-- ORIGINAL -->

                    <div class="preview-box">

                        <h3>
                            Original Image
                        </h3>


                        <img
                            id="originalPreview"
                            alt="Original image"
                        >

                    </div>


                    <!-- RESIZED -->

                    <div class="preview-box">

                        <h3>
                            Resized Image
                        </h3>


                        <img
                            id="resizedPreview"
                            alt="Resized image"
                        >

                    </div>


                </div>


                <!-- =================================================
                     STATS
                ================================================== -->

                <div class="result-info">


                    <div class="result-stat">

                        <strong id="originalDimensions">
                            -
                        </strong>

                        <span>
                            Original Dimensions
                        </span>

                    </div>


                    <div class="result-stat">

                        <strong id="newDimensions">
                            -
                        </strong>

                        <span>
                            New Dimensions
                        </span>

                    </div>


                    <div class="result-stat">

                        <strong id="newSize">
                            -
                        </strong>

                        <span>
                            Output Size
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
                        ⬇ Download Resized Image
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
             INFO
        ==================================================== -->

        <section class="info-box">

            <h2>
                Free Online Image Resizer
            </h2>


            <p>

                Smart-Tooz Image Resizer lets you resize
                images to custom dimensions without installing
                any software. Upload an image, choose your
                desired width and height, keep the aspect ratio
                locked if needed, select the output format and
                download the resized image.

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

const widthInput =
    document.getElementById(
        'width'
    );

const heightInput =
    document.getElementById(
        'height'
    );

const lockRatio =
    document.getElementById(
        'lockRatio'
    );

const quality =
    document.getElementById(
        'quality'
    );

const qualityValue =
    document.getElementById(
        'qualityValue'
    );

const format =
    document.getElementById(
        'format'
    );

const resizeBtn =
    document.getElementById(
        'resizeBtn'
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

const originalPreview =
    document.getElementById(
        'originalPreview'
    );

const resizedPreview =
    document.getElementById(
        'resizedPreview'
    );

const originalDimensions =
    document.getElementById(
        'originalDimensions'
    );

const newDimensions =
    document.getElementById(
        'newDimensions'
    );

const newSize =
    document.getElementById(
        'newSize'
    );

const downloadBtn =
    document.getElementById(
        'downloadBtn'
    );


/* ============================================================
   STATE
============================================================ */

let selectedFile = null;

let originalImage = null;

let originalUrl = null;

let resizedUrl = null;

let aspectRatio = 1;


/* ============================================================
   OPEN FILE
============================================================ */

uploadArea.addEventListener(
    'click',
    function() {

        fileInput.click();

    }
);


/* ============================================================
   FILE INPUT
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


    const allowedTypes = [
        'image/jpeg',
        'image/png',
        'image/webp'
    ];


    if (
        !allowedTypes.includes(
            file.type
        )
    ) {

        showError(
            'Please choose a JPG, JPEG, PNG or WebP image.'
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


    selectedFile = file;


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


    originalPreview.src =
        originalUrl;


    const reader =
        new FileReader();


    reader.onload =
        function(event) {

            const img =
                new Image();


            img.onload =
                function() {

                    originalImage =
                        img;


                    aspectRatio =
                        img.naturalWidth /
                        img.naturalHeight;


                    widthInput.value =
                        img.naturalWidth;


                    heightInput.value =
                        img.naturalHeight;


                    originalDimensions.textContent =
                        img.naturalWidth +
                        ' × ' +
                        img.naturalHeight;

                };


            img.src =
                event.target.result;

        };


    reader.readAsDataURL(
        file
    );


    settings.style.display =
        'block';


    result.style.display =
        'none';

}


/* ============================================================
   WIDTH CHANGE
============================================================ */

widthInput.addEventListener(
    'input',
    function() {

        if (
            lockRatio.checked &&
            aspectRatio > 0
        ) {

            const width =
                parseInt(
                    this.value,
                    10
                );


            if (
                Number.isFinite(width) &&
                width > 0
            ) {

                heightInput.value =
                    Math.round(
                        width /
                        aspectRatio
                    );

            }

        }

    }
);


/* ============================================================
   HEIGHT CHANGE
============================================================ */

heightInput.addEventListener(
    'input',
    function() {

        if (
            lockRatio.checked &&
            aspectRatio > 0
        ) {

            const height =
                parseInt(
                    this.value,
                    10
                );


            if (
                Number.isFinite(height) &&
                height > 0
            ) {

                widthInput.value =
                    Math.round(
                        height *
                        aspectRatio
                    );

            }

        }

    }
);


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
   RESIZE
============================================================ */

resizeBtn.addEventListener(
    'click',
    resizeImage
);


async function resizeImage() {

    clearError();


    if (!selectedFile) {

        showError(
            'Please select an image first.'
        );

        return;

    }


    if (!originalImage) {

        showError(
            'Image is still loading. Please try again.'
        );

        return;

    }


    const width =
        parseInt(
            widthInput.value,
            10
        );


    const height =
        parseInt(
            heightInput.value,
            10
        );


    if (
        !Number.isFinite(width) ||
        !Number.isFinite(height) ||
        width < 1 ||
        height < 1
    ) {

        showError(
            'Please enter valid width and height.'
        );

        return;

    }


    if (
        width > 10000 ||
        height > 10000
    ) {

        showError(
            'Maximum dimensions are 10000 × 10000 pixels.'
        );

        return;

    }


    resizeBtn.disabled =
        true;

    resizeBtn.textContent =
        'Resizing...';


    try {

        const canvas =
            document.createElement(
                'canvas'
            );


        canvas.width =
            width;

        canvas.height =
            height;


        const context =
            canvas.getContext(
                '2d'
            );


        if (!context) {

            throw new Error(
                'Your browser does not support image resizing.'
            );

        }


        /*
         * PNG transparency is preserved.
         * For JPG, white background is used.
         */

        if (
            format.value ===
            'image/jpeg'
        ) {

            context.fillStyle =
                '#ffffff';


            context.fillRect(
                0,
                0,
                width,
                height
            );

        }


        context.imageSmoothingEnabled =
            true;


        context.imageSmoothingQuality =
            'high';


        context.drawImage(
            originalImage,
            0,
            0,
            width,
            height
        );


        const outputQuality =
            parseInt(
                quality.value,
                10
            ) / 100;


        const blob =
            await canvasToBlob(
                canvas,
                format.value,
                outputQuality
            );


        if (!blob) {

            throw new Error(
                'Unable to create resized image.'
            );

        }


        if (resizedUrl) {

            URL.revokeObjectURL(
                resizedUrl
            );

        }


        resizedUrl =
            URL.createObjectURL(
                blob
            );


        resizedPreview.src =
            resizedUrl;


        originalDimensions.textContent =
            originalImage.naturalWidth +
            ' × ' +
            originalImage.naturalHeight;


        newDimensions.textContent =
            width +
            ' × ' +
            height;


        newSize.textContent =
            formatBytes(
                blob.size
            );


        downloadBtn.href =
            resizedUrl;


        downloadBtn.download =
            createFilename(
                selectedFile.name,
                format.value
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
            'Image resizing failed.'
        );

    }


    resizeBtn.disabled =
        false;

    resizeBtn.textContent =
        'Resize Image';

}


/* ============================================================
   CANVAS TO BLOB
============================================================ */

function canvasToBlob(
    canvas,
    type,
    quality
) {

    return new Promise(
        function(resolve) {

            canvas.toBlob(
                function(blob) {

                    resolve(blob);

                },
                type,
                quality
            );

        }
    );

}


/* ============================================================
   FORMAT BYTES
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

function createFilename(
    filename,
    mimeType
) {

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


    let extension =
        'jpg';


    if (
        mimeType ===
        'image/png'
    ) {

        extension =
            'png';

    }


    if (
        mimeType ===
        'image/webp'
    ) {

        extension =
            'webp';

    }


    return (
        base +
        '-resized.' +
        extension
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

        selectedFile = null;

        originalImage = null;


        if (originalUrl) {

            URL.revokeObjectURL(
                originalUrl
            );

            originalUrl = null;

        }


        if (resizedUrl) {

            URL.revokeObjectURL(
                resizedUrl
            );

            resizedUrl = null;

        }


        fileInput.value =
            '';


        fileInfo.textContent =
            '';


        widthInput.value =
            '';

        heightInput.value =
            '';


        originalPreview.removeAttribute(
            'src'
        );

        resizedPreview.removeAttribute(
            'src'
        );


        settings.style.display =
            'none';


        result.style.display =
            'none';


        quality.value =
            '90';


        qualityValue.textContent =
            '90%';


        format.value =
            'image/jpeg';


        lockRatio.checked =
            true;


        originalDimensions.textContent =
            '-';


        newDimensions.textContent =
            '-';


        newSize.textContent =
            '-';


        downloadBtn.href =
            '#';


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


<style id="smarttoolz-global-sidebar-css">.smarttoolz-global-sidebar{position:fixed;right:18px;top:88px;width:280px;max-height:calc(100vh - 108px);overflow:auto;z-index:9990;background:#fff;border:1px solid #e5e9f0;border-radius:18px;padding:14px;box-shadow:0 18px 50px rgba(20,30,70,.12)}.st-sidebar-head{display:flex;justify-content:space-between;align-items:center;padding:4px 7px 10px}.st-sidebar-head button{border:0;background:transparent;font-size:24px;cursor:pointer;color:#69758a}.st-sidebar-list a{display:block;padding:9px 10px;margin:2px 0;border-radius:9px;color:#536075;text-decoration:none;font:700 13px/1.25 Inter,system-ui,Arial,sans-serif}.st-sidebar-list a:hover,.st-sidebar-list a.active{background:#f0efff;color:#635bff}.st-sidebar-list .st-all{margin-top:9px;border-top:1px solid #e9ecf2;padding-top:13px}.smarttoolz-tools-toggle{display:none;position:fixed;right:14px;bottom:18px;z-index:9991;border:0;border-radius:12px;background:#635bff;color:#fff;padding:11px 14px;font-weight:800;box-shadow:0 10px 30px rgba(30,30,90,.2)}@media(max-width:900px){.smarttoolz-global-sidebar{right:12px;top:76px;width:min(310px,calc(100vw - 24px));max-height:calc(100vh - 94px);display:none}.smarttoolz-global-sidebar.open{display:block}.smarttoolz-tools-toggle{display:block}}body.smarttoolz-sidebar-page{padding-right:315px}@media(max-width:900px){body.smarttoolz-sidebar-page{padding-right:0}}</style><aside id="smarttoolz-global-sidebar" class="smarttoolz-global-sidebar" aria-label="All Tools"><div class="st-sidebar-head"><strong>All Tools</strong><button type="button" aria-label="Close tools">×</button></div><div class="st-sidebar-list"><a href="/smart-toolz/tools/image-compressor.php">Image Compressor</a><a href="/smart-toolz/tools/png-to-jpg.php">PNG to JPG</a><a href="/smart-toolz/tools/jpg-to-webp.php">JPG to WebP</a><a href="/smart-toolz/tools/png-to-webp.php">PNG to WebP</a><a href="/smart-toolz/tools/gif-maker.php">GIF Maker</a><a href="/smart-toolz/tools/gif-to-jpg.php">GIF to JPG</a><a href="/smart-toolz/tools/jpg-to-png.php">JPG to PNG</a><a href="/smart-toolz/tools/pdf-to-jpg.php">PDF to JPG</a><a href="/smart-toolz/tools/pdf-to-png.php">PDF to PNG</a><a href="/smart-toolz/tools/pdf-merger.php">PDF Merger</a><a href="/smart-toolz/tools/pdf-splitter.php">PDF Splitter</a><a href="/smart-toolz/tools/pdf-compressor.php">PDF Compressor</a><a href="/smart-toolz/tools/qr-generator.php">QR Generator</a><a href="/smart-toolz/tools/qr-reader.php">QR Reader</a><a href="/smart-toolz/tools/age-calculator.php">Age Calculator</a><a href="/smart-toolz/tools/percentage-calculator.php">Percentage Calculator</a><a href="/smart-toolz/tools/bmi-calculator.php">BMI Calculator</a><a href="/smart-toolz/tools/json-formatter.php">JSON Formatter</a><a href="/smart-toolz/tools/word-counter.php">Word Counter</a><a href="/smart-toolz/tools/case-converter.php">Case Converter</a><a href="/smart-toolz/tools/base64-encoder.php">Base64 Encoder</a><a href="/smart-toolz/tools/url-encoder.php">URL Encoder</a><a class="st-all" href="/smart-toolz/tool.php">View All Tools →</a></div></aside><button id="smarttoolz-tools-toggle" class="smarttoolz-tools-toggle" type="button">☰ Tools</button><script>(function(){const s=document.getElementById('smarttoolz-global-sidebar');if(!s)return;const current=location.pathname.split('/').pop();s.querySelectorAll('a').forEach(a=>{if(a.pathname.split('/').pop()===current)a.classList.add('active')});const toggle=document.getElementById('smarttoolz-tools-toggle'),close=s.querySelector('button');toggle?.addEventListener('click',()=>s.classList.toggle('open'));close?.addEventListener('click',()=>s.classList.remove('open'));if(window.innerWidth>900)document.body.classList.add('smarttoolz-sidebar-page')})();</script>
</body>

</html>
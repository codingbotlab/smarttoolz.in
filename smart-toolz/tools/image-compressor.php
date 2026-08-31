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
   POPUNDER
============================================================ */

smartToozAd('popunder');


/* ============================================================
   SOCIAL BAR
============================================================ */

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

<title>Image Compressor - Smart-Tooz</title>

<meta
    name="description"
    content="Compress JPG, JPEG, PNG and WebP images online for free with Smart-Tooz."
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
   CONTENT LEFT
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
   MAIN CONTENT LEFT
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

    align-items: center;

    justify-content: center;
}

.mobile-ad {

    display: none;

    width: 100%;

    align-items: center;

    justify-content: center;
}


/* ============================================================
   TOOL HEADER
============================================================ */

.tool-header {

    margin-bottom: 22px;

    padding:
        34px 25px;

    background: white;

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

    max-width: 650px;

    margin:
        13px auto 0;

    color: #707b8e;

    font-size: 14px;
}


/* ============================================================
   COMPRESSOR CARD
============================================================ */

.compressor-card {

    background: white;

    border:
        1px solid #e5e9f0;

    border-radius: 22px;

    padding: 28px;

    box-shadow:
        0 15px 45px
        rgba(30,35,80,.06);
}


/* ============================================================
   UPLOAD AREA
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

    padding: 20px;

    background: #fafbff;

    border:
        1px solid #e5e9f0;

    border-radius: 17px;
}

.setting-header {

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 15px;
}

.setting-title {

    font-size: 14px;

    font-weight: 800;
}

.setting-description {

    margin-top: 3px;

    color: #707b8e;

    font-size: 12px;
}

.quality-value {

    color: #635bff;

    font-size: 17px;

    font-weight: 800;
}

#quality {

    width: 100%;

    margin-top: 15px;

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

    margin-top: 20px;
}

.btn {

    display: inline-flex;

    align-items: center;

    justify-content: center;

    min-height: 44px;

    padding:
        11px 18px;

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

.preview-grid {

    display: grid;

    grid-template-columns:
        repeat(2, minmax(0, 1fr));

    gap: 18px;
}

.preview {

    min-width: 0;

    padding: 14px;

    border:
        1px solid #e5e9f0;

    border-radius: 17px;

    background: #fafbff;
}

.preview h3 {

    margin-bottom: 10px;

    font-size: 14px;
}

.preview img {

    display: block;

    width: 100%;

    max-height: 390px;

    object-fit: contain;

    border-radius: 11px;

    background: white;
}


/* ============================================================
   STATS
============================================================ */

.stats {

    display: grid;

    grid-template-columns:
        repeat(3, 1fr);

    gap: 12px;

    margin-top: 16px;
}

.stat {

    padding: 15px;

    text-align: center;

    background: #f6f7fb;

    border-radius: 13px;
}

.stat strong {

    display: block;

    color: #635bff;

    font-size: 19px;
}

.stat span {

    color: #707b8e;

    font-size: 11px;
}


/* ============================================================
   INFO
============================================================ */

.info-box {

    margin-top: 24px;

    padding: 25px;

    background: white;

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

@media (max-width: 950px) {

    .page-layout {

        width:
            calc(100% - 20px);

        gap: 16px;
    }

}


/* ============================================================
   MOBILE
   CONTENT FIRST
   SIDEBAR BELOW
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
       MOBILE PAGE ORDER
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


    /* Main FIRST */

    .tool-content {

        width: 100%;

        order: 1;
    }


    /* Sidebar LAST */

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


    .compressor-card {

        padding: 17px;

        border-radius: 18px;
    }


    .upload-area {

        padding:
            43px 15px;
    }


    .preview-grid {

        grid-template-columns: 1fr;
    }


    .stats {

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
         MAIN CONTENT
         DESKTOP = LEFT
         MOBILE = FIRST
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
                🖼️ Image Tool
            </div>


            <h1>

                Image
                <span>Compressor</span>

            </h1>


            <p>

                Compress JPG, JPEG, PNG and WebP images
                directly in your browser while keeping
                excellent image quality.

            </p>


        </section>


        <!-- ====================================================
             COMPRESSOR
        ==================================================== -->

        <section class="compressor-card">


            <!-- UPLOAD -->

            <div
                class="upload-area"
                id="uploadArea"
            >

                <div class="upload-icon">
                    ⬆
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


                <div class="setting-header">


                    <div>

                        <div class="setting-title">
                            Compression Quality
                        </div>

                        <div class="setting-description">
                            Lower quality creates a smaller file.
                        </div>

                    </div>


                    <div
                        class="quality-value"
                        id="qualityValue"
                    >
                        80%
                    </div>


                </div>


                <input
                    type="range"
                    id="quality"
                    min="10"
                    max="100"
                    value="80"
                >


                <div class="actions">


                    <button
                        type="button"
                        class="btn btn-primary"
                        id="compressBtn"
                    >
                        Compress Image
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


                <div class="preview-grid">


                    <div class="preview">

                        <h3>
                            Original Image
                        </h3>

                        <img
                            id="originalPreview"
                            alt="Original image preview"
                        >

                    </div>


                    <div class="preview">

                        <h3>
                            Compressed Image
                        </h3>

                        <img
                            id="compressedPreview"
                            alt="Compressed image preview"
                        >

                    </div>


                </div>


                <!-- =================================================
                     STATS
                ================================================== -->

                <div class="stats">


                    <div class="stat">

                        <strong id="originalSize">
                            -
                        </strong>

                        <span>
                            Original Size
                        </span>

                    </div>


                    <div class="stat">

                        <strong id="compressedSize">
                            -
                        </strong>

                        <span>
                            Compressed Size
                        </span>

                    </div>


                    <div class="stat">

                        <strong id="savedSize">
                            -
                        </strong>

                        <span>
                            Reduction
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
                        ⬇ Download Compressed Image
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
                Free Online Image Compressor
            </h2>


            <p>

                Smart-Tooz Image Compressor helps reduce
                image file sizes quickly. Images are processed
                directly in your browser, so the selected image
                does not need to be uploaded to the server.
                Choose the compression quality, compress your
                image and download the optimized result.

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
         DESKTOP = RIGHT
         MOBILE = BELOW
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

const compressBtn =
    document.getElementById(
        'compressBtn'
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

const compressedPreview =
    document.getElementById(
        'compressedPreview'
    );

const originalSize =
    document.getElementById(
        'originalSize'
    );

const compressedSize =
    document.getElementById(
        'compressedSize'
    );

const savedSize =
    document.getElementById(
        'savedSize'
    );

const downloadBtn =
    document.getElementById(
        'downloadBtn'
    );


/* ============================================================
   STATE
============================================================ */

let selectedFile = null;

let originalUrl = null;

let compressedUrl = null;


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


    settings.style.display =
        'block';


    result.style.display =
        'none';

}


/* ============================================================
   QUALITY
============================================================ */

quality.addEventListener(
    'input',
    function() {

        qualityValue.textContent =
            this.value + '%';

    }
);


/* ============================================================
   COMPRESS
============================================================ */

compressBtn.addEventListener(
    'click',
    compressImage
);


async function compressImage() {

    if (!selectedFile) {

        showError(
            'Please select an image first.'
        );

        return;

    }


    clearError();


    compressBtn.disabled =
        true;

    compressBtn.textContent =
        'Compressing...';


    try {

        const image =
            await loadImage(
                selectedFile
            );


        const canvas =
            document.createElement(
                'canvas'
            );


        canvas.width =
            image.naturalWidth;

        canvas.height =
            image.naturalHeight;


        const context =
            canvas.getContext(
                '2d'
            );


        if (!context) {

            throw new Error(
                'Your browser does not support image processing.'
            );

        }


        /*
         * White background for transparent PNG files.
         */

        context.fillStyle =
            '#ffffff';


        context.fillRect(
            0,
            0,
            canvas.width,
            canvas.height
        );


        context.drawImage(
            image,
            0,
            0
        );


        const outputType =
            'image/jpeg';


        const compressionQuality =
            parseInt(
                quality.value,
                10
            ) / 100;


        const blob =
            await canvasToBlob(
                canvas,
                outputType,
                compressionQuality
            );


        if (!blob) {

            throw new Error(
                'Could not create compressed image.'
            );

        }


        if (compressedUrl) {

            URL.revokeObjectURL(
                compressedUrl
            );

        }


        compressedUrl =
            URL.createObjectURL(
                blob
            );


        compressedPreview.src =
            compressedUrl;


        originalSize.textContent =
            formatBytes(
                selectedFile.size
            );


        compressedSize.textContent =
            formatBytes(
                blob.size
            );


        const reduction =
            (
                1 -
                (
                    blob.size /
                    selectedFile.size
                )
            ) * 100;


        savedSize.textContent =
            Math.max(
                0,
                reduction
            ).toFixed(1) +
            '%';


        downloadBtn.href =
            compressedUrl;


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
            'Compression failed.'
        );

    }


    compressBtn.disabled =
        false;

    compressBtn.textContent =
        'Compress Image';

}


/* ============================================================
   LOAD IMAGE
============================================================ */

function loadImage(file) {

    return new Promise(
        function(resolve, reject) {

            const img =
                new Image();


            const objectUrl =
                URL.createObjectURL(
                    file
                );


            img.onload =
                function() {

                    URL.revokeObjectURL(
                        objectUrl
                    );

                    resolve(img);

                };


            img.onerror =
                function() {

                    URL.revokeObjectURL(
                        objectUrl
                    );

                    reject(
                        new Error(
                            'Unable to read this image.'
                        )
                    );

                };


            img.src =
                objectUrl;

        }
    );

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

    if (bytes <= 0) {

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
        '-compressed.jpg'
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


        if (originalUrl) {

            URL.revokeObjectURL(
                originalUrl
            );

            originalUrl = null;

        }


        if (compressedUrl) {

            URL.revokeObjectURL(
                compressedUrl
            );

            compressedUrl = null;

        }


        fileInput.value =
            '';


        fileInfo.textContent =
            '';


        originalPreview.removeAttribute(
            'src'
        );


        compressedPreview.removeAttribute(
            'src'
        );


        settings.style.display =
            'none';


        result.style.display =
            'none';


        quality.value =
            '80';


        qualityValue.textContent =
            '80%';


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
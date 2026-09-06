<?php
declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);


/* ============================================================
   ANALYTICS TRACKER
============================================================ */




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

<title>Image Cropper Online - Smart-Tooz</title>

<meta
    name="description"
    content="Free online image cropper. Crop JPG, PNG and WebP images with custom sizes, aspect ratios, zoom and rotation."
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
   NAV
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

    padding: 22px;

    background: #ffffff;

    border:
        1px solid #e5e9f0;

    border-radius: 22px;

    box-shadow:
        0 15px 45px
        rgba(30,35,80,.06);
}


/* ============================================================
   UPLOAD AREA
============================================================ */

.upload-area {

    min-height: 180px;

    padding: 30px 20px;

    display: flex;

    flex-direction: column;

    align-items: center;

    justify-content: center;

    text-align: center;

    border:
        2px dashed #d8dce6;

    border-radius: 18px;

    background: #fafbff;

    cursor: pointer;

    transition:
        border-color .2s,
        background .2s;
}

.upload-area:hover,
.upload-area.dragover {

    border-color: #635bff;

    background: #f5f3ff;
}

.upload-icon {

    width: 58px;

    height: 58px;

    display: grid;

    place-items: center;

    margin-bottom: 12px;

    border-radius: 16px;

    background: #eeedff;

    font-size: 27px;
}

.upload-area h3 {

    margin-bottom: 5px;

    font-size: 17px;
}

.upload-area p {

    color: #8a94a5;

    font-size: 12px;
}

#fileInput {
    display: none;
}


/* ============================================================
   EDITOR
============================================================ */

.editor {

    display: none;

    margin-top: 20px;
}


/* ============================================================
   PREVIEW
============================================================ */

.preview-wrap {

    width: 100%;

    min-height: 420px;

    display: flex;

    align-items: center;

    justify-content: center;

    padding: 18px;

    overflow: hidden;

    border-radius: 17px;

    background: #151827;

    position: relative;
}

#previewImage {

    display: block;

    max-width: 100%;

    max-height: 560px;

    object-fit: contain;

    user-select: none;

    -webkit-user-drag: none;

    transform-origin: center center;

    transition:
        transform .15s ease;
}


/* ============================================================
   CONTROLS
============================================================ */

.controls {

    margin-top: 18px;

    display: grid;

    grid-template-columns:
        repeat(3, 1fr);

    gap: 12px;
}

.control-box {

    padding: 14px;

    border:
        1px solid #e5e9f0;

    border-radius: 13px;

    background: #fafbff;
}

.control-box label {

    display: block;

    margin-bottom: 7px;

    color: #596477;

    font-size: 11px;

    font-weight: 800;
}

.control-box select {

    width: 100%;

    height: 38px;

    padding:
        0 9px;

    border:
        1px solid #dfe3eb;

    border-radius: 9px;

    background: white;

    outline: none;

    color: #172033;

    font-size: 12px;
}

.control-box input[type="range"] {

    width: 100%;

    accent-color: #635bff;
}


/* ============================================================
   CONTROL BUTTONS
============================================================ */

.control-buttons {

    display: flex;

    justify-content: center;

    flex-wrap: wrap;

    gap: 9px;

    margin-top: 15px;
}

.small-btn {

    min-height: 40px;

    padding:
        9px 15px;

    border: 0;

    border-radius: 10px;

    background: #eef0f5;

    color: #3f4858;

    cursor: pointer;

    font-size: 11px;

    font-weight: 800;
}

.small-btn:hover {
    background: #e3e6ed;
}


/* ============================================================
   MAIN ACTIONS
============================================================ */

.actions {

    display: flex;

    justify-content: center;

    flex-wrap: wrap;

    gap: 10px;

    margin-top: 17px;
}

.btn {

    min-height: 45px;

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
   RESULT
============================================================ */

.result-info {

    margin-top: 12px;

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
   TABLET
============================================================ */

@media (max-width: 850px) {

    .controls {

        grid-template-columns:
            repeat(2, 1fr);
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

        padding: 15px;

        border-radius: 18px;
    }


    .preview-wrap {

        min-height: 300px;

        padding: 10px;
    }


    #previewImage {

        max-height: 400px;

        max-width: 100%;
    }


    .controls {

        grid-template-columns: 1fr;
    }


    .control-buttons {

        display: grid;

        grid-template-columns:
            repeat(2, 1fr);
    }


    .control-buttons .small-btn {

        width: 100%;
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
         MAIN
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
                🖼️ Image Tool
            </div>


            <h1>

                Image
                <span>Cropper</span>

            </h1>


            <p>

                Crop your images online with custom
                aspect ratios, zoom and rotation.

            </p>


        </section>


        <!-- ====================================================
             TOOL
        ==================================================== -->

        <section class="tool-card">


            <!-- =================================================
                 UPLOAD
            ================================================== -->

            <label
                class="upload-area"
                id="uploadArea"
                for="fileInput"
            >

                <div class="upload-icon">
                    🖼️
                </div>


                <h3>
                    Choose an image
                </h3>


                <p>
                    JPG, PNG or WebP • Select or drag & drop
                </p>


            </label>


            <input
                type="file"
                id="fileInput"
                accept="image/jpeg,image/png,image/webp"
            >


            <!-- =================================================
                 EDITOR
            ================================================== -->

            <div
                class="editor"
                id="editor"
            >


                <div class="preview-wrap">

                    <img
                        id="previewImage"
                        alt="Image preview"
                    >

                </div>


                <!-- =================================================
                     CONTROLS
                ================================================== -->

                <div class="controls">


                    <!-- ASPECT -->

                    <div class="control-box">

                        <label for="aspectRatio">
                            Aspect Ratio
                        </label>


                        <select id="aspectRatio">

                            <option value="free">
                                Free
                            </option>

                            <option value="1">
                                1 : 1
                            </option>

                            <option value="4/3">
                                4 : 3
                            </option>

                            <option value="3/4">
                                3 : 4
                            </option>

                            <option value="16/9">
                                16 : 9
                            </option>

                            <option value="9/16">
                                9 : 16
                            </option>

                        </select>

                    </div>


                    <!-- ZOOM -->

                    <div class="control-box">

                        <label for="zoom">
                            Zoom
                        </label>


                        <input
                            type="range"
                            id="zoom"
                            min="0.5"
                            max="3"
                            step="0.05"
                            value="1"
                        >

                    </div>


                    <!-- FORMAT -->

                    <div class="control-box">

                        <label for="outputFormat">
                            Output Format
                        </label>


                        <select id="outputFormat">

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


                </div>


                <!-- =================================================
                     TRANSFORM BUTTONS
                ================================================== -->

                <div class="control-buttons">


                    <button
                        type="button"
                        class="small-btn"
                        id="rotateLeft"
                    >
                        ↶ Rotate Left
                    </button>


                    <button
                        type="button"
                        class="small-btn"
                        id="rotateRight"
                    >
                        ↷ Rotate Right
                    </button>


                    <button
                        type="button"
                        class="small-btn"
                        id="zoomOut"
                    >
                        − Zoom Out
                    </button>


                    <button
                        type="button"
                        class="small-btn"
                        id="zoomIn"
                    >
                        + Zoom In
                    </button>


                    <button
                        type="button"
                        class="small-btn"
                        id="resetBtn"
                    >
                        ↺ Reset
                    </button>


                    <button
                        type="button"
                        class="small-btn"
                        id="changeImageBtn"
                    >
                        🔄 Change Image
                    </button>


                </div>


                <!-- =================================================
                     MAIN ACTIONS
                ================================================== -->

                <div class="actions">


                    <button
                        type="button"
                        class="btn btn-primary"
                        id="cropDownloadBtn"
                    >
                        ✂️ Crop & Download
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
                    Adjust the image and click Crop & Download.

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
                Free Online Image Cropper
            </h2>


            <p>

                Crop JPG, PNG and WebP images directly in
                your browser. Choose a common aspect ratio,
                adjust zoom and rotation, then download
                the cropped image in JPG, PNG or WebP format.
                Your image is processed locally in your browser.

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

const fileInput =
    document.getElementById(
        'fileInput'
    );

const uploadArea =
    document.getElementById(
        'uploadArea'
    );

const editor =
    document.getElementById(
        'editor'
    );

const previewImage =
    document.getElementById(
        'previewImage'
    );

const aspectRatio =
    document.getElementById(
        'aspectRatio'
    );

const zoom =
    document.getElementById(
        'zoom'
    );

const outputFormat =
    document.getElementById(
        'outputFormat'
    );

const rotateLeft =
    document.getElementById(
        'rotateLeft'
    );

const rotateRight =
    document.getElementById(
        'rotateRight'
    );

const zoomOut =
    document.getElementById(
        'zoomOut'
    );

const zoomIn =
    document.getElementById(
        'zoomIn'
    );

const resetBtn =
    document.getElementById(
        'resetBtn'
    );

const changeImageBtn =
    document.getElementById(
        'changeImageBtn'
    );

const cropDownloadBtn =
    document.getElementById(
        'cropDownloadBtn'
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
   STATE
============================================================ */

let imageObject = null;

let imageUrl = '';

let rotation = 0;

let zoomValue = 1;


/* ============================================================
   FILE LOAD
============================================================ */

fileInput.addEventListener(
    'change',
    function() {

        if (
            this.files &&
            this.files.length > 0
        ) {

            loadImage(
                this.files[0]
            );

        }

    }
);


/* ============================================================
   LOAD IMAGE
============================================================ */

function loadImage(file) {

    if (
        !file.type.startsWith('image/')
    ) {

        resultInfo.textContent =
            'Please select a valid image file.';

        return;

    }


    if (imageUrl) {

        URL.revokeObjectURL(
            imageUrl
        );

    }


    imageUrl =
        URL.createObjectURL(
            file
        );


    const image =
        new Image();


    image.onload =
        function() {

            imageObject =
                image;


            previewImage.src =
                imageUrl;


            rotation =
                0;

            zoomValue =
                1;

            zoom.value =
                '1';


            editor.style.display =
                'block';

            uploadArea.style.display =
                'none';


            updatePreview();


            resultInfo.textContent =
                'Image loaded successfully.';

        };


    image.onerror =
        function() {

            resultInfo.textContent =
                'Unable to load this image.';

        };


    image.src =
        imageUrl;

}


/* ============================================================
   UPDATE PREVIEW
============================================================ */

function updatePreview() {

    previewImage.style.transform =
        'rotate(' +
        rotation +
        'deg) scale(' +
        zoomValue +
        ')';

}


/* ============================================================
   ZOOM RANGE
============================================================ */

zoom.addEventListener(
    'input',
    function() {

        zoomValue =
            Number(
                this.value
            );


        updatePreview();

    }
);


/* ============================================================
   ROTATE LEFT
============================================================ */

rotateLeft.addEventListener(
    'click',
    function() {

        rotation -= 90;

        updatePreview();

    }
);


/* ============================================================
   ROTATE RIGHT
============================================================ */

rotateRight.addEventListener(
    'click',
    function() {

        rotation += 90;

        updatePreview();

    }
);


/* ============================================================
   ZOOM OUT
============================================================ */

zoomOut.addEventListener(
    'click',
    function() {

        zoomValue =
            Math.max(
                0.5,
                zoomValue - 0.1
            );


        zoom.value =
            String(
                zoomValue
            );


        updatePreview();

    }
);


/* ============================================================
   ZOOM IN
============================================================ */

zoomIn.addEventListener(
    'click',
    function() {

        zoomValue =
            Math.min(
                3,
                zoomValue + 0.1
            );


        zoom.value =
            String(
                zoomValue
            );


        updatePreview();

    }
);


/* ============================================================
   RESET
============================================================ */

resetBtn.addEventListener(
    'click',
    function() {

        rotation =
            0;

        zoomValue =
            1;

        zoom.value =
            '1';

        aspectRatio.value =
            'free';


        updatePreview();


        resultInfo.textContent =
            'Image settings reset.';

    }
);


/* ============================================================
   CHANGE IMAGE
============================================================ */

changeImageBtn.addEventListener(
    'click',
    function() {

        fileInput.value =
            '';

        fileInput.click();

    }
);


/* ============================================================
   CROP + DOWNLOAD
============================================================ */

cropDownloadBtn.addEventListener(
    'click',
    function() {

        if (
            !imageObject
        ) {

            resultInfo.textContent =
                'Please select an image first.';

            return;

        }


        cropImage();

    }
);


/* ============================================================
   CROP IMAGE
============================================================ */

function cropImage() {

    const sourceWidth =
        imageObject.naturalWidth;

    const sourceHeight =
        imageObject.naturalHeight;


    let cropWidth =
        sourceWidth;

    let cropHeight =
        sourceHeight;


    const ratio =
        aspectRatio.value;


    /* --------------------------------------------------------
       Aspect ratio
    -------------------------------------------------------- */

    if (
        ratio !== 'free'
    ) {

        const parts =
            ratio.split('/');


        let targetRatio;


        if (
            parts.length === 1
        ) {

            targetRatio =
                Number(
                    parts[0]
                );

        } else {

            targetRatio =
                Number(parts[0]) /
                Number(parts[1]);

        }


        const sourceRatio =
            sourceWidth /
            sourceHeight;


        if (
            sourceRatio > targetRatio
        ) {

            cropHeight =
                sourceHeight;

            cropWidth =
                Math.round(
                    cropHeight *
                    targetRatio
                );

        } else {

            cropWidth =
                sourceWidth;

            cropHeight =
                Math.round(
                    cropWidth /
                    targetRatio
                );

        }

    }


    /* --------------------------------------------------------
       Create canvas
    -------------------------------------------------------- */

    const canvas =
        document.createElement(
            'canvas'
        );


    canvas.width =
        cropWidth;

    canvas.height =
        cropHeight;


    const ctx =
        canvas.getContext(
            '2d'
        );


    if (!ctx) {

        resultInfo.textContent =
            'Your browser does not support image processing.';

        return;

    }


    ctx.imageSmoothingEnabled =
        true;

    ctx.imageSmoothingQuality =
        'high';


    /* --------------------------------------------------------
       Rotation
    -------------------------------------------------------- */

    const angle =
        (
            rotation % 360 +
            360
        ) % 360;


    if (
        angle === 90 ||
        angle === 270
    ) {

        canvas.width =
            cropHeight;

        canvas.height =
            cropWidth;

    }


    ctx.save();


    ctx.translate(
        canvas.width / 2,
        canvas.height / 2
    );


    ctx.rotate(
        angle *
        Math.PI /
        180
    );


    /*
     * Scale image according to zoom.
     */

    const drawWidth =
        sourceWidth *
        zoomValue;

    const drawHeight =
        sourceHeight *
        zoomValue;


    ctx.drawImage(
        imageObject,
        -drawWidth / 2,
        -drawHeight / 2,
        drawWidth,
        drawHeight
    );


    ctx.restore();


    /* --------------------------------------------------------
       Convert
    -------------------------------------------------------- */

    const mime =
        outputFormat.value;


    const quality =
        mime === 'image/png'
            ? 1
            : 0.92;


    canvas.toBlob(
        function(blob) {

            if (!blob) {

                resultInfo.textContent =
                    'Unable to create the cropped image.';

                return;

            }


            const url =
                URL.createObjectURL(
                    blob
                );


            const extension =
                mime === 'image/png'
                    ? 'png'
                    : (
                        mime === 'image/webp'
                            ? 'webp'
                            : 'jpg'
                    );


            const link =
                document.createElement(
                    'a'
                );


            link.href =
                url;


            link.download =
                'smart-tooz-cropped.' +
                extension;


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


            resultInfo.textContent =
                '✓ Cropped image created successfully.';

        },
        mime,
        quality
    );

}


/* ============================================================
   CLEAR
============================================================ */

clearBtn.addEventListener(
    'click',
    function() {

        if (imageUrl) {

            URL.revokeObjectURL(
                imageUrl
            );

        }


        imageUrl =
            '';

        imageObject =
            null;


        previewImage.removeAttribute(
            'src'
        );


        editor.style.display =
            'none';


        uploadArea.style.display =
            'flex';


        fileInput.value =
            '';


        rotation =
            0;

        zoomValue =
            1;

        zoom.value =
            '1';

        aspectRatio.value =
            'free';


        resultInfo.textContent =
            'Ready — choose an image.';

    }
);


/* ============================================================
   DRAG & DROP
============================================================ */

[
    'dragenter',
    'dragover'
].forEach(
    function(eventName) {

        uploadArea.addEventListener(
            eventName,
            function(event) {

                event.preventDefault();

                event.stopPropagation();

                uploadArea.classList.add(
                    'dragover'
                );

            }
        );

    }
);


[
    'dragleave',
    'drop'
].forEach(
    function(eventName) {

        uploadArea.addEventListener(
            eventName,
            function(event) {

                event.preventDefault();

                event.stopPropagation();

                uploadArea.classList.remove(
                    'dragover'
                );

            }
        );

    }
);


uploadArea.addEventListener(
    'drop',
    function(event) {

        const files =
            event.dataTransfer.files;


        if (
            files &&
            files.length > 0
        ) {

            loadImage(
                files[0]
            );

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
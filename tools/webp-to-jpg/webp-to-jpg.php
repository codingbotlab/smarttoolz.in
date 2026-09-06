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

<title>WEBP to JPG Converter - Smart-Tooz</title>

<meta
    name="description"
    content="Convert WEBP images to JPG online for free. Convert images directly in your browser without uploading them to a server."
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
   UPLOAD
============================================================ */

.upload-area {

    min-height: 210px;

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

    width: 62px;

    height: 62px;

    display: grid;

    place-items: center;

    margin-bottom: 13px;

    border-radius: 17px;

    background: #eeedff;

    color: #635bff;

    font-size: 28px;

    font-weight: 900;
}

.upload-area h3 {

    margin-bottom: 5px;

    font-size: 18px;
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

.preview {

    width: 100%;

    min-height: 390px;

    padding: 20px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 17px;

    background: #151827;

    overflow: hidden;
}

#previewImage {

    display: block;

    max-width: 100%;

    max-height: 520px;

    object-fit: contain;

    border-radius: 5px;
}


/* ============================================================
   FILE INFO
============================================================ */

.file-info {

    display: flex;

    align-items: center;

    justify-content: center;

    flex-wrap: wrap;

    gap: 10px;

    margin-top: 14px;

    color: #707b8e;

    font-size: 12px;

    text-align: center;
}

.file-info strong {
    color: #3f4858;
}


/* ============================================================
   QUALITY
============================================================ */

.quality-box {

    margin-top: 17px;

    padding: 16px;

    border:
        1px solid #e5e9f0;

    border-radius: 14px;

    background: #fafbff;
}

.quality-top {

    display: flex;

    justify-content: space-between;

    align-items: center;

    margin-bottom: 9px;
}

.quality-top label {

    color: #596477;

    font-size: 12px;

    font-weight: 800;
}

#qualityValue {

    color: #635bff;

    font-size: 12px;

    font-weight: 900;
}

#quality {

    width: 100%;

    accent-color: #635bff;
}


/* ============================================================
   ACTIONS
============================================================ */

.actions {

    display: flex;

    justify-content: center;

    flex-wrap: wrap;

    gap: 10px;

    margin-top: 18px;
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


    .preview {

        min-height: 290px;

        padding: 10px;
    }


    #previewImage {

        max-height: 390px;
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
             HEADER
        ==================================================== -->

        <section class="tool-header">


            <div class="tool-badge">
                🖼️ Image Converter
            </div>


            <h1>

                WEBP to
                <span>JPG</span>

            </h1>


            <p>

                Convert WEBP images to JPG format
                quickly and easily in your browser.

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
                    WEBP
                </div>


                <h3>
                    Choose WEBP image
                </h3>


                <p>
                    Select a WEBP file or drag & drop it here
                </p>

            </label>


            <input
                type="file"
                id="fileInput"
                accept=".webp,image/webp"
            >


            <!-- =================================================
                 EDITOR
            ================================================== -->

            <div
                class="editor"
                id="editor"
            >


                <!-- PREVIEW -->

                <div class="preview">

                    <img
                        id="previewImage"
                        alt="WEBP preview"
                    >

                </div>


                <!-- FILE INFO -->

                <div class="file-info">

                    <span>
                        File:
                    </span>

                    <strong id="fileName">
                        —
                    </strong>

                    <span>
                        •
                    </span>

                    <span>
                        Size:
                    </span>

                    <strong id="fileSize">
                        —
                    </strong>

                    <span>
                        •
                    </span>

                    <span>
                        Dimensions:
                    </span>

                    <strong id="dimensions">
                        —
                    </strong>

                </div>


                <!-- =================================================
                     QUALITY
                ================================================== -->

                <div class="quality-box">


                    <div class="quality-top">

                        <label for="quality">
                            JPG Quality
                        </label>


                        <span id="qualityValue">
                            92%
                        </span>

                    </div>


                    <input
                        type="range"
                        id="quality"
                        min="50"
                        max="100"
                        step="1"
                        value="92"
                    >

                </div>


                <!-- =================================================
                     ACTIONS
                ================================================== -->

                <div class="actions">


                    <button
                        type="button"
                        class="btn btn-primary"
                        id="convertBtn"
                    >
                        🔄 Convert to JPG
                    </button>


                    <button
                        type="button"
                        class="btn btn-secondary"
                        id="changeBtn"
                    >
                        🔄 Change Image
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
                    Your image stays in your browser.

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
                Free WEBP to JPG Converter
            </h2>


            <p>

                Convert WEBP images to JPG format directly
                in your browser. Choose your preferred JPG
                quality and download the converted image
                instantly. No software installation is
                required and your image is not uploaded to
                our server.

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

const fileName =
    document.getElementById(
        'fileName'
    );

const fileSize =
    document.getElementById(
        'fileSize'
    );

const dimensions =
    document.getElementById(
        'dimensions'
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

const changeBtn =
    document.getElementById(
        'changeBtn'
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

let selectedFile = null;


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

            loadFile(
                this.files[0]
            );

        }

    }
);


/* ============================================================
   LOAD FILE
============================================================ */

function loadFile(file) {

    if (
        file.type !== 'image/webp' &&
        !file.name.toLowerCase().endsWith('.webp')
    ) {

        resultInfo.textContent =
            'Please select a valid WEBP image.';

        return;

    }


    selectedFile =
        file;


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


            fileName.textContent =
                file.name;


            fileSize.textContent =
                formatBytes(
                    file.size
                );


            dimensions.textContent =
                image.naturalWidth +
                ' × ' +
                image.naturalHeight;


            editor.style.display =
                'block';


            uploadArea.style.display =
                'none';


            resultInfo.textContent =
                'WEBP image loaded successfully.';

        };


    image.onerror =
        function() {

            resultInfo.textContent =
                'This WEBP image could not be opened by your browser.';

        };


    image.src =
        imageUrl;

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
   FORMAT BYTES
============================================================ */

function formatBytes(bytes) {

    if (
        bytes === 0
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
        Math.floor(
            Math.log(bytes) /
            Math.log(1024)
        );


    return (
        bytes /
        Math.pow(
            1024,
            index
        )
    ).toFixed(
        index === 0
            ? 0
            : 2
    ) +
    ' ' +
    units[index];

}


/* ============================================================
   CONVERT
============================================================ */

convertBtn.addEventListener(
    'click',
    function() {

        if (
            !imageObject
        ) {

            resultInfo.textContent =
                'Please choose a WEBP image first.';

            return;

        }


        convertToJpg();

    }
);


/* ============================================================
   WEBP TO JPG
============================================================ */

function convertToJpg() {

    const width =
        imageObject.naturalWidth;

    const height =
        imageObject.naturalHeight;


    const canvas =
        document.createElement(
            'canvas'
        );


    canvas.width =
        width;

    canvas.height =
        height;


    const ctx =
        canvas.getContext(
            '2d'
        );


    if (!ctx) {

        resultInfo.textContent =
            'Your browser does not support image conversion.';

        return;

    }


    ctx.imageSmoothingEnabled =
        true;

    ctx.imageSmoothingQuality =
        'high';


    /*
     * JPG does not support transparency.
     * Use white background.
     */

    ctx.fillStyle =
        '#ffffff';


    ctx.fillRect(
        0,
        0,
        width,
        height
    );


    ctx.drawImage(
        imageObject,
        0,
        0,
        width,
        height
    );


    const jpgQuality =
        Number(
            quality.value
        ) / 100;


    canvas.toBlob(
        function(blob) {

            if (!blob) {

                resultInfo.textContent =
                    'Conversion failed.';

                return;

            }


            const downloadUrl =
                URL.createObjectURL(
                    blob
                );


            const link =
                document.createElement(
                    'a'
                );


            link.href =
                downloadUrl;


            link.download =
                createJpgName(
                    selectedFile
                        ? selectedFile.name
                        : 'image.webp'
                );


            document.body.appendChild(
                link
            );


            link.click();


            link.remove();


            setTimeout(
                function() {

                    URL.revokeObjectURL(
                        downloadUrl
                    );

                },
                1000
            );


            resultInfo.textContent =
                '✓ Image converted to JPG successfully.';

        },
        'image/jpeg',
        jpgQuality
    );

}


/* ============================================================
   JPG FILE NAME
============================================================ */

function createJpgName(name) {

    const base =
        name.replace(
            /\.webp$/i,
            ''
        );


    return (
        base ||
        'smart-tooz-image'
    ) +
    '.jpg';

}


/* ============================================================
   CHANGE IMAGE
============================================================ */

changeBtn.addEventListener(
    'click',
    function() {

        fileInput.value =
            '';

        fileInput.click();

    }
);


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

        selectedFile =
            null;


        previewImage.removeAttribute(
            'src'
        );


        fileInput.value =
            '';


        fileName.textContent =
            '—';

        fileSize.textContent =
            '—';

        dimensions.textContent =
            '—';


        editor.style.display =
            'none';


        uploadArea.style.display =
            'flex';


        resultInfo.textContent =
            'Your image stays in your browser.';

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

            loadFile(
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
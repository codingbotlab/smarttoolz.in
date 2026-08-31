<?php
declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| SMART-TOOLZ ADS MANAGER
|--------------------------------------------------------------------------
| File:
| /smart-toolz/ads.php
|--------------------------------------------------------------------------
*/

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);


/* ============================================================
   DATABASE
============================================================ */

require_once $_SERVER['DOCUMENT_ROOT'] . '/creator-ai/auth/config.php';

$pdo = db();

if (!$pdo instanceof PDO) {
    die('Database connection failed.');
}


/* ============================================================
   AD DEFINITIONS
============================================================ */

$AD_DEFINITIONS = [

    'popunder' => [
        'name' => 'Popunder',
        'description' => 'Popunder advertising',
        'size' => 'Auto',
        'icon' => 'POP'
    ],

    'smartlink' => [
        'name' => 'Smartlink',
        'description' => 'Smartlink monetization',
        'size' => 'Link',
        'icon' => 'LINK'
    ],

    'socialbar' => [
        'name' => 'Social Bar',
        'description' => 'Floating advertisement',
        'size' => 'Auto',
        'icon' => 'BAR'
    ],

    'native' => [
        'name' => 'Native Banner',
        'description' => 'Native advertisement',
        'size' => 'Native',
        'icon' => 'NAT'
    ],

    '468x60' => [
        'name' => 'Banner 468×60',
        'description' => 'Desktop banner',
        'size' => '468 × 60',
        'icon' => '468'
    ],

    '300x250' => [
        'name' => 'Banner 300×250',
        'description' => 'Medium rectangle',
        'size' => '300 × 250',
        'icon' => '300'
    ],

    '160x600' => [
        'name' => 'Banner 160×600',
        'description' => 'Desktop skyscraper',
        'size' => '160 × 600',
        'icon' => '160'
    ],

    '160x300' => [
        'name' => 'Banner 160×300',
        'description' => 'Vertical banner',
        'size' => '160 × 300',
        'icon' => '160'
    ],

    '320x50' => [
        'name' => 'Banner 320×50',
        'description' => 'Mobile banner',
        'size' => '320 × 50',
        'icon' => '320'
    ],

    '728x90' => [
        'name' => 'Banner 728×90',
        'description' => 'Large desktop banner',
        'size' => '728 × 90',
        'icon' => '728'
    ],

];


/* ============================================================
   MESSAGE
============================================================ */

$message = '';
$messageType = '';


/* ============================================================
   SAVE
============================================================ */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $action = $_POST['action'] ?? '';

    if ($action === 'save_ads') {

        try {

            $pdo->beginTransaction();

            $stmt = $pdo->prepare("
                UPDATE ads_settings
                SET
                    enabled = ?,
                    ad_code = ?
                WHERE ad_key = ?
                LIMIT 1
            ");

            foreach ($AD_DEFINITIONS as $key => $definition) {

                $enabled =
                    isset(
                        $_POST['ads'][$key]['enabled']
                    ) ? 1 : 0;

                $adCode =
                    $_POST['ads'][$key]['code'] ?? '';

                if (!is_string($adCode)) {
                    $adCode = '';
                }

                $stmt->execute([
                    $enabled,
                    $adCode,
                    $key
                ]);
            }

            $pdo->commit();

            $message =
                'Ad settings saved successfully.';

            $messageType = 'success';

        } catch (Throwable $e) {

            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            error_log(
                'Smart-Toolz Ads Error: ' .
                $e->getMessage()
            );

            $message =
                'Unable to save settings.';

            $messageType = 'error';
        }
    }
}


/* ============================================================
   LOAD ADS
============================================================ */

$currentAds = [];

try {

    $stmt = $pdo->query("
        SELECT
            id,
            ad_key,
            ad_name,
            ad_type,
            enabled,
            ad_code,
            updated_at
        FROM ads_settings
        ORDER BY id ASC
    ");

    while (
        $row =
        $stmt->fetch(PDO::FETCH_ASSOC)
    ) {

        $currentAds[
            (string)$row['ad_key']
        ] = $row;
    }

} catch (Throwable $e) {

    $message =
        'ads_settings table error: ' .
        $e->getMessage();

    $messageType = 'error';
}


/* ============================================================
   ESCAPE
============================================================ */

function stz_h(mixed $value): string
{
    return htmlspecialchars(
        (string)$value,
        ENT_QUOTES,
        'UTF-8'
    );
}


/* ============================================================
   SHOW AD
============================================================ */

function showAd(string $type): void
{
    global $pdo;

    static $cache = [];

    if (
        array_key_exists(
            $type,
            $cache
        )
    ) {

        $ad = $cache[$type];

    } else {

        try {

            $stmt = $pdo->prepare("
                SELECT
                    enabled,
                    ad_code
                FROM ads_settings
                WHERE ad_key = ?
                LIMIT 1
            ");

            $stmt->execute([
                $type
            ]);

            $ad =
                $stmt->fetch(
                    PDO::FETCH_ASSOC
                );

            if (!$ad) {

                $cache[$type] = false;

                return;
            }

            $cache[$type] = $ad;

        } catch (Throwable $e) {

            return;
        }
    }


    if (
        !$ad ||
        (int)($ad['enabled'] ?? 0) !== 1
    ) {
        return;
    }


    $code =
        (string)(
            $ad['ad_code'] ?? ''
        );

    if (trim($code) === '') {
        return;
    }


    /*
     * Ad code intentionally unescaped.
     */

    echo $code;
}


/* ============================================================
   STATS
============================================================ */

$enabledCount = 0;

foreach (
    $AD_DEFINITIONS
    as $key => $definition
) {

    if (
        isset($currentAds[$key]) &&
        (int)$currentAds[$key]['enabled'] === 1
    ) {

        $enabledCount++;
    }
}

$totalAds =
    count($AD_DEFINITIONS);

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
Smart-Toolz — Ads Manager
</title>


<style>

/* ============================================================
   RESET
============================================================ */

* {
    box-sizing: border-box;
}

html {
    scroll-behavior: smooth;
}

body {

    margin: 0;

    background: #f4f6fa;

    color: #172033;

    font-family:
        Inter,
        -apple-system,
        BlinkMacSystemFont,
        "Segoe UI",
        Arial,
        sans-serif;
}

button,
textarea {
    font-family: inherit;
}


/* ============================================================
   HEADER
============================================================ */

.header {

    position: sticky;

    top: 0;

    z-index: 100;

    background: #fff;

    border-bottom:
        1px solid #e5e8ef;

    box-shadow:
        0 3px 15px
        rgba(20,30,50,.04);
}

.header-inner {

    width:
        min(1250px, calc(100% - 24px));

    min-height: 62px;

    margin: auto;

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 15px;
}


/* ============================================================
   BRAND
============================================================ */

.brand {

    display: flex;

    align-items: center;

    gap: 10px;
}

.brand-icon {

    width: 38px;

    height: 38px;

    display: grid;

    place-items: center;

    border-radius: 11px;

    background:
        linear-gradient(
            135deg,
            #635bff,
            #8b5cf6
        );

    color: white;

    font-size: 16px;

    font-weight: 800;
}

.brand-name {

    font-size: 16px;

    font-weight: 800;
}

.brand-subtitle {

    color: #8992a2;

    font-size: 10px;
}


/* ============================================================
   HEADER LINKS
============================================================ */

.header-links {

    display: flex;

    align-items: center;

    gap: 7px;
}

.header-links a {

    padding:
        8px 11px;

    border-radius: 8px;

    color: #626d80;

    font-size: 12px;

    font-weight: 700;

    transition: .2s;
}

.header-links a:hover {

    background: #f1efff;

    color: #635bff;
}

.header-links .analytics-link {

    background: #635bff;

    color: #fff;
}

.header-links .analytics-link:hover {

    background: #5149e8;

    color: #fff;
}


/* ============================================================
   PAGE
============================================================ */

.container {

    width:
        min(1250px, calc(100% - 24px));

    margin: auto;
}

.page {

    padding:
        22px 0 45px;
}


/* ============================================================
   TITLE ROW
============================================================ */

.title-row {

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 15px;

    margin-bottom: 16px;
}

.page-title h1 {

    margin: 0;

    font-size: 25px;

    letter-spacing: -.6px;
}

.page-title p {

    margin:
        4px 0 0;

    color: #7c8697;

    font-size: 12px;
}


/* ============================================================
   STATS
============================================================ */

.stats {

    display: grid;

    grid-template-columns:
        repeat(2, 180px);

    gap: 10px;

    margin-bottom: 14px;
}

.stat-card {

    padding:
        13px 15px;

    background: #fff;

    border:
        1px solid #e3e7ee;

    border-radius: 13px;
}

.stat-label {

    color: #7d8798;

    font-size: 10px;

    font-weight: 700;

    text-transform: uppercase;
}

.stat-value {

    margin-top: 2px;

    font-size: 23px;

    line-height: 1.1;

    font-weight: 850;
}


/* ============================================================
   MESSAGE
============================================================ */

.message {

    margin-bottom: 12px;

    padding:
        10px 13px;

    border-radius: 9px;

    font-size: 12px;

    font-weight: 700;
}

.message.success {

    background: #eafaf1;

    border: 1px solid #c7ecd7;

    color: #137a45;
}

.message.error {

    background: #fff0f0;

    border: 1px solid #ffd0d0;

    color: #b42323;
}


/* ============================================================
   ADS GRID
============================================================ */

.ads-grid {

    display: grid;

    grid-template-columns:
        repeat(2, minmax(0, 1fr));

    gap: 12px;
}


/* ============================================================
   AD CARD
============================================================ */

.ad-card {

    min-width: 0;

    background: #fff;

    border:
        1px solid #e2e6ed;

    border-radius: 14px;

    overflow: hidden;

    transition:
        box-shadow .2s,
        border-color .2s;
}

.ad-card:hover {

    border-color: #d7d2ff;

    box-shadow:
        0 8px 25px
        rgba(30,35,80,.06);
}


/* ============================================================
   AD TOP
============================================================ */

.ad-top {

    padding:
        12px 13px;

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 10px;

    border-bottom:
        1px solid #edf0f4;
}

.ad-info {

    min-width: 0;

    display: flex;

    align-items: center;

    gap: 9px;
}

.ad-icon {

    width: 37px;

    height: 37px;

    flex: 0 0 37px;

    display: grid;

    place-items: center;

    border-radius: 9px;

    background: #f0efff;

    color: #635bff;

    font-size: 8px;

    font-weight: 850;
}

.ad-name {

    overflow: hidden;

    text-overflow: ellipsis;

    white-space: nowrap;

    font-size: 13px;

    font-weight: 800;
}

.ad-description {

    margin-top: 1px;

    color: #8992a2;

    font-size: 10px;
}

.ad-size {

    display: inline-block;

    margin-top: 3px;

    padding:
        2px 5px;

    border-radius: 4px;

    background: #f1f3f6;

    color: #6e7889;

    font-size: 8px;

    font-weight: 700;
}


/* ============================================================
   CONTROLS
============================================================ */

.controls {

    display: flex;

    align-items: center;

    gap: 7px;
}

.status {

    min-width: 24px;

    font-size: 9px;

    font-weight: 850;

    text-align: right;
}

.status.on {
    color: #159456;
}

.status.off {
    color: #929aa8;
}


/* ============================================================
   SWITCH
============================================================ */

.switch {

    position: relative;

    width: 40px;

    height: 22px;

    display: inline-block;
}

.switch input {

    width: 0;

    height: 0;

    opacity: 0;
}

.slider {

    position: absolute;

    inset: 0;

    cursor: pointer;

    background: #d7dce5;

    border-radius: 30px;

    transition: .2s;
}

.slider:before {

    content: "";

    position: absolute;

    width: 16px;

    height: 16px;

    left: 3px;

    top: 3px;

    background: #fff;

    border-radius: 50%;

    box-shadow:
        0 1px 4px
        rgba(0,0,0,.18);

    transition: .2s;
}

.switch input:checked + .slider {

    background: #635bff;
}

.switch input:checked + .slider:before {

    transform:
        translateX(18px);
}


/* ============================================================
   CODE
============================================================ */

.code-area {

    padding:
        10px 13px 12px;
}

.code-label {

    display: block;

    margin-bottom: 5px;

    color: #596477;

    font-size: 9px;

    font-weight: 800;

    text-transform: uppercase;
}

.code-editor {

    width: 100%;

    height: 76px;

    min-height: 76px;

    resize: vertical;

    display: block;

    padding: 9px;

    border:
        1px solid #dfe3ea;

    border-radius: 8px;

    outline: none;

    background: #111827;

    color: #d9f7e8;

    font-family:
        Consolas,
        Monaco,
        "Courier New",
        monospace;

    font-size: 10px;

    line-height: 1.45;
}

.code-editor:focus {

    border-color: #635bff;

    box-shadow:
        0 0 0 2px
        rgba(99,91,255,.10);
}

.code-help {

    margin-top: 4px;

    color: #98a0ae;

    font-size: 9px;
}


/* ============================================================
   SAVE BAR
============================================================ */

.save-bar {

    position: sticky;

    bottom: 0;

    z-index: 50;

    margin-top: 14px;

    padding:
        10px 13px;

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 12px;

    background:
        rgba(255,255,255,.96);

    backdrop-filter:
        blur(10px);

    border:
        1px solid #e2e6ed;

    border-radius: 12px;

    box-shadow:
        0 -5px 20px
        rgba(20,30,50,.06);
}

.save-info {

    color: #7d8797;

    font-size: 10px;
}

.save-button {

    border: 0;

    padding:
        9px 18px;

    border-radius: 8px;

    background: #635bff;

    color: white;

    font-size: 11px;

    font-weight: 800;

    cursor: pointer;

    transition: .2s;
}

.save-button:hover {

    background: #5149e8;

    transform:
        translateY(-1px);
}


/* ============================================================
   MOBILE
============================================================ */

@media (max-width: 700px) {

    .header-inner {

        min-height: 58px;

        width:
            calc(100% - 14px);
    }

    .brand-subtitle {
        display: none;
    }

    .brand-name {
        font-size: 14px;
    }

    .brand-icon {

        width: 34px;

        height: 34px;
    }

    .header-links {

        gap: 3px;
    }

    .header-links a {

        padding:
            7px 8px;

        font-size: 10px;
    }

    .container {

        width:
            calc(100% - 14px);
    }

    .page {

        padding:
            16px 0 35px;
    }

    .title-row {

        align-items: flex-start;

        flex-direction: column;

        margin-bottom: 12px;
    }

    .page-title h1 {

        font-size: 22px;
    }

    .stats {

        width: 100%;

        grid-template-columns:
            1fr 1fr;

        gap: 7px;
    }

    .stat-card {

        padding:
            11px 12px;
    }

    .stat-value {

        font-size: 21px;
    }

    .ads-grid {

        grid-template-columns: 1fr;

        gap: 9px;
    }

    .ad-top {

        padding:
            10px;
    }

    .code-area {

        padding:
            9px 10px 10px;
    }

    .code-editor {

        height: 70px;

        min-height: 70px;
    }

    .save-bar {

        border-radius: 10px;
    }

    .save-info {

        display: none;
    }

    .save-button {

        width: 100%;
    }

}


/* ============================================================
   VERY SMALL MOBILE
============================================================ */

@media (max-width: 380px) {

    .header-links a {

        padding:
            6px 5px;

        font-size: 9px;
    }

    .brand-name {

        font-size: 13px;
    }

}

</style>

</head>


<body>


<!-- ============================================================
     HEADER
============================================================ -->

<header class="header">

    <div class="header-inner">


        <div class="brand">

            <div class="brand-icon">
                S
            </div>

            <div>

                <div class="brand-name">
                    Smart-Toolz
                </div>

                <div class="brand-subtitle">
                    Advertisement Manager
                </div>

            </div>

        </div>


        <!-- ====================================================
             NAVIGATION
        ===================================================== -->

        <nav class="header-links">

            <a
                href="/"
                title="Open Website"
            >
                🌐 Site
            </a>

            <a
                href="/analytics/"
                class="analytics-link"
                title="Open Analytics Dashboard"
            >
                📊 Analytics
            </a>

        </nav>


    </div>

</header>


<!-- ============================================================
     PAGE
============================================================ -->

<main class="page">

<div class="container">


    <!-- ========================================================
         TITLE
    ========================================================= -->

    <div class="title-row">


        <div class="page-title">

            <h1>
                Ads Management
            </h1>

            <p>
                Manage all advertisement formats from one place.
            </p>

        </div>


        <div class="stats">


            <div class="stat-card">

                <div class="stat-label">
                    Active Ads
                </div>

                <div class="stat-value">
                    <?= $enabledCount ?>
                </div>

            </div>


            <div class="stat-card">

                <div class="stat-label">
                    Total Formats
                </div>

                <div class="stat-value">
                    <?= $totalAds ?>
                </div>

            </div>


        </div>

    </div>


    <!-- ========================================================
         MESSAGE
    ========================================================= -->

    <?php if ($message !== ''): ?>

        <div
            class="message <?= stz_h($messageType) ?>"
        >

            <?= stz_h($message) ?>

        </div>

    <?php endif; ?>


    <!-- ========================================================
         FORM
    ========================================================= -->

    <form
        method="POST"
        autocomplete="off"
        id="adsForm"
    >

        <input
            type="hidden"
            name="action"
            value="save_ads"
        >


        <!-- ====================================================
             ADS GRID
        ===================================================== -->

        <div class="ads-grid">


        <?php foreach (
            $AD_DEFINITIONS
            as $key => $definition
        ): ?>


            <?php

            $row =
                $currentAds[$key] ?? [];

            $enabled =
                (int)(
                    $row['enabled'] ?? 0
                ) === 1;

            $code =
                (string)(
                    $row['ad_code'] ?? ''
                );

            ?>


            <div class="ad-card">


                <!-- ============================================
                     AD TOP
                ============================================= -->

                <div class="ad-top">


                    <div class="ad-info">


                        <div class="ad-icon">

                            <?= stz_h(
                                $definition['icon']
                            ) ?>

                        </div>


                        <div>

                            <div class="ad-name">

                                <?= stz_h(
                                    $definition['name']
                                ) ?>

                            </div>


                            <div class="ad-description">

                                <?= stz_h(
                                    $definition['description']
                                ) ?>

                            </div>


                            <span class="ad-size">

                                <?= stz_h(
                                    $definition['size']
                                ) ?>

                            </span>

                        </div>


                    </div>


                    <!-- ========================================
                         SWITCH
                    ========================================= -->

                    <div class="controls">


                        <span
                            class="status <?= $enabled ? 'on' : 'off' ?>"
                            data-status
                        >
                            <?= $enabled
                                ? 'ON'
                                : 'OFF'
                            ?>
                        </span>


                        <label class="switch">

                            <input
                                type="checkbox"
                                name="ads[<?= stz_h($key) ?>][enabled]"
                                value="1"
                                <?= $enabled
                                    ? 'checked'
                                    : ''
                                ?>
                                onchange="updateStatus(this)"
                            >

                            <span class="slider"></span>

                        </label>


                    </div>


                </div>


                <!-- ============================================
                     CODE
                ============================================= -->

                <div class="code-area">


                    <label class="code-label">

                        Ad Code

                    </label>


                    <textarea
                        class="code-editor"
                        name="ads[<?= stz_h($key) ?>][code]"
                        spellcheck="false"
                        placeholder="Paste ad HTML / JavaScript..."
                    ><?= stz_h($code) ?></textarea>


                    <div class="code-help">

                        Paste complete ad-network code.

                    </div>


                </div>


            </div>


        <?php endforeach; ?>


        </div>


        <!-- ====================================================
             SAVE BAR
        ===================================================== -->

        <div class="save-bar">


            <div class="save-info">

                Changes are saved directly to database.

            </div>


            <button
                type="submit"
                class="save-button"
            >

                💾 Save All Ads

            </button>


        </div>


    </form>


</div>

</main>


<script>

/* ============================================================
   ON / OFF STATUS
============================================================ */

function updateStatus(input) {

    const card =
        input.closest('.ad-card');

    if (!card) {
        return;
    }

    const status =
        card.querySelector(
            '[data-status]'
        );

    if (!status) {
        return;
    }


    if (input.checked) {

        status.textContent = 'ON';

        status.classList.remove(
            'off'
        );

        status.classList.add(
            'on'
        );

    } else {

        status.textContent = 'OFF';

        status.classList.remove(
            'on'
        );

        status.classList.add(
            'off'
        );

    }

}


/* ============================================================
   UNSAVED CHANGES
============================================================ */

let formChanged = false;

const form =
    document.getElementById(
        'adsForm'
    );


if (form) {

    form.addEventListener(
        'input',
        function () {

            formChanged = true;

        }
    );


    form.addEventListener(
        'change',
        function () {

            formChanged = true;

        }
    );


    form.addEventListener(
        'submit',
        function () {

            formChanged = false;

        }
    );

}


window.addEventListener(
    'beforeunload',
    function (event) {

        if (!formChanged) {
            return;
        }

        event.preventDefault();

        event.returnValue = '';

    }
);

</script>


</body>

</html>
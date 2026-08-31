<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

/*
|--------------------------------------------------------------------------
| Smart-Tooz - Homepage
|--------------------------------------------------------------------------
*/

require_once $_SERVER['DOCUMENT_ROOT'] . '/creator-ai/auth/config.php';

$pdo = db();

/* ============================================================
   LOAD ADS DIRECTLY FROM DATABASE
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

        $smartToozAds[$row['ad_key']] = [
            'enabled' => (int)$row['enabled'],
            'ad_code' => (string)($row['ad_code'] ?? '')
        ];
    }

} catch (Throwable $e) {

    /*
     * Ads fail hone par website band nahi hogi.
     */
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

    if ($smartToozAds[$adKey]['enabled'] !== 1) {
        return;
    }

    $code = trim($smartToozAds[$adKey]['ad_code']);

    if ($code === '') {
        return;
    }

    /*
     * Ad code intentionally unescaped.
     * JS/HTML ad scripts need to execute.
     */
    echo $code;
}


/* ============================================================
   50 TOOLS
============================================================ */

require_once __DIR__ . '/tool.php';


/* ============================================================
   SAFETY
============================================================ */

if (!isset($tools) || !is_array($tools)) {
    $tools = [];
}


/* ============================================================
   CATEGORIES
============================================================ */

$categories = [];

foreach ($tools as $tool) {

    if (
        isset($tool['category']) &&
        !in_array($tool['category'], $categories, true)
    ) {
        $categories[] = $tool['category'];
    }
}

sort($categories);

array_unshift($categories, 'All');


/* ============================================================
   HTML
============================================================ */

?>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/analytics/tracker.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>Smart-Tooz - Free Online Tools</title>

<meta
    name="description"
    content="Smart-Tooz offers free online tools for images, text, PDF, developer utilities, calculators and more."
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
   VARIABLES
============================================================ */

:root {

    --primary: #635bff;

    --primary-dark: #5148e8;

    --text: #172033;

    --muted: #707b8e;

    --border: #e5e9f0;

    --card: #ffffff;

    --bg: #f6f8fc;

}


/* ============================================================
   HEADER
============================================================ */

.site-header {

    position: sticky;

    top: 0;

    z-index: 1000;

    background:
        rgba(255,255,255,.95);

    backdrop-filter:
        blur(15px);

    border-bottom:
        1px solid var(--border);
}

.navbar {

    width:
        min(1200px, calc(100% - 32px));

    min-height: 72px;

    margin: auto;

    display: flex;

    align-items: center;

    justify-content: space-between;
}

.logo {

    display: flex;

    align-items: center;

    gap: 11px;

    font-size: 21px;

    font-weight: 800;
}

.logo-icon {

    width: 42px;

    height: 42px;

    border-radius: 13px;

    display: grid;

    place-items: center;

    color: white;

    background:
        linear-gradient(
            135deg,
            #635bff,
            #916cff
        );

    box-shadow:
        0 8px 22px
        rgba(99,91,255,.22);
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

    transition: .2s;
}

.nav-links a:hover {
    color: var(--primary);
}

.menu-button {

    display: none;

    border: 0;

    background: transparent;

    font-size: 26px;

    cursor: pointer;
}


/* ============================================================
   HERO
============================================================ */

.hero {

    padding:
        78px 18px 48px;

    text-align: center;

    background:

        radial-gradient(
            circle at 15% 15%,
            rgba(99,91,255,.11),
            transparent 28%
        ),

        radial-gradient(
            circle at 85% 5%,
            rgba(145,91,255,.10),
            transparent 28%
        );
}

.hero-badge {

    display: inline-block;

    padding:
        7px 14px;

    margin-bottom: 18px;

    border-radius: 50px;

    background: #eeedff;

    color: var(--primary);

    font-size: 13px;

    font-weight: 700;
}

.hero h1 {

    max-width: 900px;

    margin: auto;

    font-size:
        clamp(38px, 6vw, 64px);

    line-height: 1.04;

    letter-spacing: -2.5px;
}

.hero h1 span {
    color: var(--primary);
}

.hero-description {

    max-width: 680px;

    margin:
        20px auto 28px;

    color: var(--muted);

    font-size: 17px;
}


/* ============================================================
   SEARCH
============================================================ */

.search-wrapper {

    width:
        min(680px, 100%);

    margin: auto;

    position: relative;
}

.search-wrapper input {

    width: 100%;

    height: 58px;

    padding:
        0 55px 0 20px;

    border:
        1px solid var(--border);

    border-radius: 16px;

    background: white;

    outline: none;

    font-size: 15px;

    box-shadow:
        0 12px 35px
        rgba(20,30,50,.07);

    transition: .2s;
}

.search-wrapper input:focus {

    border-color:
        var(--primary);

    box-shadow:
        0 12px 35px
        rgba(99,91,255,.12);
}

.search-icon {

    position: absolute;

    right: 19px;

    top: 16px;

    font-size: 23px;

    color: #7d8797;
}


/* ============================================================
   MAIN
============================================================ */

.container {

    width:
        min(1200px, calc(100% - 32px));

    margin: auto;
}


/* ============================================================
   ADS
============================================================ */

.ad-slot {

    width: 100%;

    min-height: 10px;

    margin:
        26px auto;

    padding: 5px;

    display: flex;

    align-items: center;

    justify-content: center;

    text-align: center;

    overflow: hidden;
}

.ad-slot iframe {

    max-width: 100%;
}

.ad-slot img {

    max-width: 100%;

    height: auto;
}

.native-slot {

    width: 100%;

    margin:
        25px auto;

    text-align: center;

    overflow: hidden;
}


/* ============================================================
   SECTION TITLE
============================================================ */

.section-title {

    margin:
        38px 0 18px;
}

.section-title h2 {

    font-size: 26px;

    margin-bottom: 4px;
}

.section-title p {

    color: var(--muted);

    font-size: 14px;
}


/* ============================================================
   CATEGORY BAR
============================================================ */

.category-bar {

    display: flex;

    gap: 9px;

    overflow-x: auto;

    padding-bottom: 9px;

    scrollbar-width: none;
}

.category-bar::-webkit-scrollbar {
    display: none;
}

.category {

    flex: 0 0 auto;

    border:
        1px solid var(--border);

    background: white;

    color: #667084;

    border-radius: 30px;

    padding:
        10px 16px;

    cursor: pointer;

    font-size: 13px;

    font-weight: 700;

    transition: .2s;
}

.category:hover,
.category.active {

    background:
        var(--primary);

    color: white;

    border-color:
        var(--primary);
}


/* ============================================================
   TOOLS GRID
============================================================ */

.tools-grid {

    display: grid;

    grid-template-columns:
        repeat(4, 1fr);

    gap: 18px;
}

.tool-card {

    position: relative;

    background:
        var(--card);

    border:
        1px solid var(--border);

    border-radius: 18px;

    padding: 22px;

    min-height: 205px;

    transition:
        transform .22s,
        box-shadow .22s,
        border-color .22s;

    overflow: hidden;
}

.tool-card:hover {

    transform:
        translateY(-5px);

    border-color:
        #d9d5ff;

    box-shadow:
        0 18px 40px
        rgba(30,35,80,.09);
}

.tool-icon {

    width: 52px;

    height: 52px;

    display: grid;

    place-items: center;

    border-radius: 14px;

    background:
        #f0efff;

    font-size: 25px;

    margin-bottom: 15px;
}

.tool-card h3 {

    font-size: 17px;

    margin-bottom: 7px;
}

.tool-card p {

    color:
        var(--muted);

    font-size: 13px;

    line-height: 1.55;

    min-height: 42px;
}

.tool-link {

    display: inline-block;

    margin-top: 14px;

    color:
        var(--primary);

    font-size: 13px;

    font-weight: 800;
}


/* ============================================================
   AD AFTER TOOLS
============================================================ */

.long-ad {

    margin-top: 32px;

    margin-bottom: 32px;
}


/* ============================================================
   NO RESULTS
============================================================ */

.no-results {

    display: none;

    padding:
        60px 20px;

    text-align: center;

    color: var(--muted);
}

.no-results h3 {

    margin-bottom: 7px;

    color: var(--text);
}


/* ============================================================
   INFO
============================================================ */

.info-section {

    margin:
        70px 0 35px;

    padding: 36px;

    background: white;

    border:
        1px solid var(--border);

    border-radius: 22px;
}

.info-section h2 {

    margin-bottom: 10px;

    font-size: 24px;
}

.info-section p {

    max-width: 900px;

    color: var(--muted);

    font-size: 14px;
}


/* ============================================================
   FOOTER
============================================================ */

footer {

    margin-top: 70px;

    background:
        #151827;

    color: white;
}

.footer-inner {

    width:
        min(1200px, calc(100% - 32px));

    margin: auto;

    padding:
        45px 0;

    display: flex;

    justify-content: space-between;

    gap: 40px;
}

.footer-brand {

    max-width: 430px;
}

.footer-brand h2 {

    margin-bottom: 9px;
}

.footer-brand p {

    color: #aeb5c5;

    font-size: 14px;
}

.footer-links {

    display: flex;

    flex-wrap: wrap;

    align-content: flex-start;

    gap:
        12px 30px;
}

.footer-links a {

    color: #c5cad5;

    font-size: 14px;
}

.footer-links a:hover {
    color: white;
}

.copyright {

    padding:
        17px 20px;

    text-align: center;

    border-top:
        1px solid
        rgba(255,255,255,.08);

    color: #9198aa;

    font-size: 12px;
}


/* ============================================================
   RESPONSIVE
============================================================ */

@media(max-width: 1000px) {

    .tools-grid {

        grid-template-columns:
            repeat(3, 1fr);
    }

}


@media(max-width: 760px) {

    .tools-grid {

        grid-template-columns:
            repeat(2, 1fr);
    }

}


@media(max-width: 600px) {

    .navbar {

        min-height: 65px;
    }

    .nav-links {

        display: none;

        position: absolute;

        left: 0;

        right: 0;

        top: 65px;

        padding: 18px;

        flex-direction: column;

        background: white;

        border-bottom:
            1px solid var(--border);
    }

    .nav-links.open {
        display: flex;
    }

    .menu-button {
        display: block;
    }

    .hero {

        padding:
            55px 12px 35px;
    }

    .hero h1 {

        font-size: 38px;

        letter-spacing: -1.5px;
    }

    .hero-description {

        font-size: 15px;
    }

    .container {

        width:
            min(100% - 20px, 1200px);
    }

    .tools-grid {

        grid-template-columns: 1fr;
    }

    .section-title h2 {

        font-size: 23px;
    }

    .info-section {

        padding: 25px;
    }

    .footer-inner {

        flex-direction: column;
    }

}


/* ============================================================
   MOBILE ADS
============================================================ */

.desktop-ad {
    display: flex;
}

.mobile-ad {
    display: none;
}

@media(max-width: 600px) {

    .desktop-ad {
        display: none;
    }

    .mobile-ad {
        display: flex;
    }

}

</style>

</head>


<body>


<!-- ============================================================
     POPUNDER
     DB: popunder
============================================================ -->

<?php
smartToozAd('popunder');
?>


<!-- ============================================================
     SOCIAL BAR
     DB: socialbar
============================================================ -->

<?php
smartToozAd('socialbar');
?>


<!-- ============================================================
     HEADER
============================================================ -->

<header class="site-header">

<nav class="navbar">


    <a
        href="/"
        class="logo"
    >

        <div class="logo-icon">
            S
        </div>

        <span>
            Smart-Tooz
        </span>

    </a>


    <div
        class="nav-links"
        id="navLinks"
    >

        <a href="/">
            Home
        </a>

        <a href="#tools">
            Tools
        </a>

        <a href="#about">
            About
        </a>

        <a href="contact.php">
            Contact
        </a>

    </div>


    <button
        type="button"
        class="menu-button"
        onclick="toggleMenu()"
        aria-label="Open menu"
    >
        ☰
    </button>


</nav>

</header>


<!-- ============================================================
     HERO
============================================================ -->

<section class="hero">


    <div class="hero-badge">
        ⚡ 50+ Free Online Tools
    </div>


    <h1>

        Simple Tools for

        <span>
            Everyday Tasks
        </span>

    </h1>


    <p class="hero-description">

        Fast, simple and useful online tools for
        images, PDFs, text, developers, calculations
        and everyday digital tasks.

    </p>


    <div class="search-wrapper">

        <input
            type="search"
            id="searchInput"
            placeholder="Search any tool..."
            autocomplete="off"
        >

        <span class="search-icon">
            ⌕
        </span>

    </div>


</section>


<!-- ============================================================
     TOP DESKTOP AD
     DB: 728x90
============================================================ -->

<div class="container">

    <div class="ad-slot desktop-ad">

        <?php
        smartToozAd('728x90');
        ?>

    </div>


    <!-- MOBILE TOP AD
         DB: 320x50 -->

    <div class="ad-slot mobile-ad">

        <?php
        smartToozAd('320x50');
        ?>

    </div>

</div>


<!-- ============================================================
     MAIN
============================================================ -->

<main
    class="container"
    id="tools"
>


    <!-- ========================================================
         TITLE
    ========================================================= -->

    <div class="section-title">

        <h2>
            Explore All Tools
        </h2>

        <p>
            Choose a tool and get started instantly.
        </p>

    </div>


    <!-- ========================================================
         CATEGORIES
    ========================================================= -->

    <div class="category-bar">

        <?php foreach ($categories as $index => $category): ?>

            <button
                type="button"
                class="category <?= $index === 0 ? 'active' : '' ?>"
                data-category="<?= htmlspecialchars(
                    $category,
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>"
                onclick="filterCategory(this)"
            >

                <?= htmlspecialchars(
                    $category,
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>

            </button>

        <?php endforeach; ?>

    </div>


    <!-- ========================================================
         NATIVE AD #1
         DB: native
    ========================================================= -->

    <div class="native-slot">

        <?php
        smartToozAd('native');
        ?>

    </div>


    <!-- ========================================================
         TOOLS
    ========================================================= -->

    <div
        class="tools-grid"
        id="toolsGrid"
    >

        <?php foreach ($tools as $index => $tool): ?>

            <?php

            $toolName =
                (string)($tool['name'] ?? 'Tool');

            $toolIcon =
                (string)($tool['icon'] ?? '🛠️');

            $toolCategory =
                (string)($tool['category'] ?? 'Other');

            $toolDescription =
                (string)($tool['description'] ?? '');

            $toolUrl =
                (string)($tool['url'] ?? '#');

            ?>


            <a
                href="<?= htmlspecialchars(
                    $toolUrl,
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>"
                class="tool-card"
                data-name="<?= htmlspecialchars(
                    strtolower($toolName),
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>"
                data-category="<?= htmlspecialchars(
                    $toolCategory,
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>"
            >

                <div class="tool-icon">

                    <?= $toolIcon ?>

                </div>


                <h3>

                    <?= htmlspecialchars(
                        $toolName,
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>

                </h3>


                <p>

                    <?= htmlspecialchars(
                        $toolDescription,
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>

                </p>


                <span class="tool-link">

                    Use Tool →

                </span>


            </a>


            <?php
            /*
             * Extra ad slots throughout the long page.
             *
             * Every 12 tools:
             * Native ad
             *
             * Every 25 tools:
             * 300x250
             */
            ?>


            <?php if (($index + 1) === 12): ?>

                <div
                    class="native-slot"
                    style="grid-column: 1 / -1;"
                >

                    <?php
                    smartToozAd('native');
                    ?>

                </div>

            <?php endif; ?>


            <?php if (($index + 1) === 25): ?>

                <div
                    class="ad-slot long-ad"
                    style="grid-column: 1 / -1;"
                >

                    <?php
                    smartToozAd('300x250');
                    ?>

                </div>

            <?php endif; ?>


            <?php if (($index + 1) === 38): ?>

                <div
                    class="native-slot"
                    style="grid-column: 1 / -1;"
                >

                    <?php
                    smartToozAd('native');
                    ?>

                </div>

            <?php endif; ?>


        <?php endforeach; ?>

    </div>


    <!-- ========================================================
         NO RESULTS
    ========================================================= -->

    <div
        class="no-results"
        id="noResults"
    >

        <h3>
            No tool found
        </h3>

        <p>
            Try another search or category.
        </p>

    </div>


    <!-- ========================================================
         300x250 AFTER TOOLS
    ========================================================= -->

    <div class="ad-slot">

        <?php
        smartToozAd('300x250');
        ?>

    </div>


    <!-- ========================================================
         ABOUT
    ========================================================= -->

    <section
        class="info-section"
        id="about"
    >

        <h2>
            About Smart-Tooz
        </h2>


        <p>

            Smart-Tooz is a collection of fast and simple
            online utilities designed to make everyday digital
            tasks easier. You can work with images, PDFs,
            text, developer data, calculations and more
            directly from your browser.

        </p>

    </section>


    <!-- ========================================================
         160x600
         
         Hidden from normal flow because this is a sidebar format.
         Can be enabled and used on future desktop sidebar layout.
    ========================================================= -->


    <!-- ========================================================
         BOTTOM DESKTOP AD
         DB: 468x60
    ========================================================= -->

    <div class="ad-slot desktop-ad">

        <?php
        smartToozAd('468x60');
        ?>

    </div>


    <!-- ========================================================
         BOTTOM MOBILE AD
         DB: 320x50
    ========================================================= -->

    <div class="ad-slot mobile-ad">

        <?php
        smartToozAd('320x50');
        ?>

    </div>


</main>


<!-- ============================================================
     FOOTER
============================================================ -->

<footer>


    <div class="footer-inner">


        <div class="footer-brand">

            <h2>
                Smart-Tooz
            </h2>

            <p>
                Free, fast and simple online tools for everyone.
            </p>

        </div>


        <div class="footer-links">

            <a href="/">
                Home
            </a>

            <a href="#tools">
                Tools
            </a>

            <a href="privacy.php">
                Privacy Policy
            </a>

            <a href="terms.php">
                Terms
            </a>

            <a href="contact.php">
                Contact
            </a>

        </div>


    </div>


    <div class="copyright">

        © <?= date('Y') ?>
        Smart-Tooz.
        All rights reserved.

    </div>


</footer>


<script>

/* ============================================================
   SEARCH
============================================================ */

const searchInput =
    document.getElementById('searchInput');

const toolCards =
    document.querySelectorAll('.tool-card');

const noResults =
    document.getElementById('noResults');

let selectedCategory = 'All';


function applyFilters() {

    const query =
        searchInput.value
            .toLowerCase()
            .trim();

    let visibleCount = 0;


    toolCards.forEach(function(card) {

        const name =
            card.dataset.name || '';

        const category =
            card.dataset.category || '';


        const matchesSearch =
            name.includes(query);

        const matchesCategory =
            selectedCategory === 'All' ||
            category === selectedCategory;


        if (
            matchesSearch &&
            matchesCategory
        ) {

            card.style.display = '';

            visibleCount++;

        } else {

            card.style.display = 'none';

        }

    });


    noResults.style.display =
        visibleCount === 0
            ? 'block'
            : 'none';

}


searchInput.addEventListener(
    'input',
    applyFilters
);


/* ============================================================
   CATEGORY
============================================================ */

function filterCategory(button) {

    document
        .querySelectorAll('.category')
        .forEach(function(item) {

            item.classList.remove(
                'active'
            );

        });


    button.classList.add(
        'active'
    );


    selectedCategory =
        button.dataset.category;


    applyFilters();

}


/* ============================================================
   MOBILE MENU
============================================================ */

function toggleMenu() {

    const nav =
        document.getElementById(
            'navLinks'
        );

    nav.classList.toggle(
        'open'
    );

}

</script>


</body>

</html>
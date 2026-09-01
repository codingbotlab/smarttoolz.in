<?php
declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| SMART-TOOLZ COMPACT ANALYTICS
|--------------------------------------------------------------------------
| /analytics/index.php
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
   HELPERS
============================================================ */

function e(mixed $value): string
{
    return htmlspecialchars(
        (string)$value,
        ENT_QUOTES,
        'UTF-8'
    );
}


function tableExists(PDO $pdo, string $table): bool
{
    try {

        $stmt = $pdo->prepare("
            SELECT COUNT(*)
            FROM information_schema.tables
            WHERE table_schema = DATABASE()
            AND table_name = ?
        ");

        $stmt->execute([$table]);

        return (int)$stmt->fetchColumn() > 0;

    } catch (Throwable $e) {

        return false;
    }
}


function columnExists(
    PDO $pdo,
    string $table,
    string $column
): bool {

    try {

        $stmt = $pdo->prepare("
            SELECT COUNT(*)
            FROM information_schema.columns
            WHERE table_schema = DATABASE()
            AND table_name = ?
            AND column_name = ?
        ");

        $stmt->execute([
            $table,
            $column
        ]);

        return (int)$stmt->fetchColumn() > 0;

    } catch (Throwable $e) {

        return false;
    }
}


function safeRows(
    PDO $pdo,
    string $sql,
    array $params = []
): array {

    try {

        $stmt = $pdo->prepare($sql);

        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (Throwable $e) {

        return [];
    }
}


function safeCount(
    PDO $pdo,
    string $sql,
    array $params = []
): int {

    try {

        $stmt = $pdo->prepare($sql);

        $stmt->execute($params);

        return (int)$stmt->fetchColumn();

    } catch (Throwable $e) {

        return 0;
    }
}


/* ============================================================
   TABLE CHECK
============================================================ */

$hasVisitors =
    tableExists(
        $pdo,
        'analytics_visitors'
    );

$hasPageviews =
    tableExists(
        $pdo,
        'analytics_pageviews'
    );

$hasLive =
    tableExists(
        $pdo,
        'analytics_live'
    );

$hasEvents =
    tableExists(
        $pdo,
        'analytics_events'
    );


/* ============================================================
   PERIOD
============================================================ */

$period =
    $_GET['period'] ?? '7';

if (!in_array(
    $period,
    ['today', '7', '30', 'all'],
    true
)) {

    $period = '7';
}


/* ============================================================
   DATE SQL
============================================================ */

$dateCondition = '';

if ($period === 'today') {

    $dateCondition =
        "WHERE viewed_at >= CURDATE()";

} elseif ($period === '7') {

    $dateCondition =
        "WHERE viewed_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)";

} elseif ($period === '30') {

    $dateCondition =
        "WHERE viewed_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
}


/* ============================================================
   TOTAL VISITORS
============================================================ */

$totalVisitors = 0;

if ($hasVisitors) {

    if ($period === 'today') {

        $totalVisitors =
            safeCount(
                $pdo,
                "
                SELECT COUNT(*)
                FROM analytics_visitors
                WHERE first_seen >= CURDATE()
                "
            );

    } elseif ($period === '7') {

        $totalVisitors =
            safeCount(
                $pdo,
                "
                SELECT COUNT(*)
                FROM analytics_visitors
                WHERE first_seen >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                "
            );

    } elseif ($period === '30') {

        $totalVisitors =
            safeCount(
                $pdo,
                "
                SELECT COUNT(*)
                FROM analytics_visitors
                WHERE first_seen >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                "
            );

    } else {

        $totalVisitors =
            safeCount(
                $pdo,
                "
                SELECT COUNT(*)
                FROM analytics_visitors
                "
            );
    }
}


/* ============================================================
   PAGE VIEWS
============================================================ */

$pageViews = 0;

if ($hasPageviews) {

    if ($period === 'all') {

        $pageViews =
            safeCount(
                $pdo,
                "
                SELECT COUNT(*)
                FROM analytics_pageviews
                "
            );

    } else {

        $pageViews =
            safeCount(
                $pdo,
                "
                SELECT COUNT(*)
                FROM analytics_pageviews
                {$dateCondition}
                "
            );
    }
}


/* ============================================================
   SESSIONS
============================================================ */

$totalSessions = 0;

if ($hasPageviews) {

    if (
        columnExists(
            $pdo,
            'analytics_pageviews',
            'session_id'
        )
    ) {

        if ($period === 'all') {

            $totalSessions =
                safeCount(
                    $pdo,
                    "
                    SELECT COUNT(DISTINCT session_id)
                    FROM analytics_pageviews
                    "
                );

        } else {

            $totalSessions =
                safeCount(
                    $pdo,
                    "
                    SELECT COUNT(DISTINCT session_id)
                    FROM analytics_pageviews
                    {$dateCondition}
                    "
                );
        }
    }
}


/* ============================================================
   LIVE
============================================================ */

$liveVisitors = 0;

if ($hasLive) {

    if (
        columnExists(
            $pdo,
            'analytics_live',
            'last_seen'
        )
    ) {

        $liveVisitors =
            safeCount(
                $pdo,
                "
                SELECT COUNT(*)
                FROM analytics_live
                WHERE last_seen >= DATE_SUB(NOW(), INTERVAL 5 MINUTE)
                "
            );
    }
}


/* ============================================================
   TOP PAGES
============================================================ */

$topPages = [];

if ($hasPageviews) {

    if ($period === 'all') {

        $topPages =
            safeRows(
                $pdo,
                "
                SELECT
                    page_path,
                    COUNT(*) AS total
                FROM analytics_pageviews
                GROUP BY page_path
                ORDER BY total DESC
                LIMIT 15
                "
            );

    } else {

        $topPages =
            safeRows(
                $pdo,
                "
                SELECT
                    page_path,
                    COUNT(*) AS total
                FROM analytics_pageviews
                {$dateCondition}
                GROUP BY page_path
                ORDER BY total DESC
                LIMIT 15
                "
            );
    }
}


/* ============================================================
   DEVICES
============================================================ */

$devices = [];

if (
    $hasPageviews &&
    columnExists(
        $pdo,
        'analytics_pageviews',
        'device'
    )
) {

    $devices =
        safeRows(
            $pdo,
            "
            SELECT
                COALESCE(
                    NULLIF(device, ''),
                    'Unknown'
                ) AS label,
                COUNT(*) AS total
            FROM analytics_pageviews
            {$dateCondition}
            GROUP BY label
            ORDER BY total DESC
            LIMIT 10
            "
        );
}


/* ============================================================
   BROWSERS
============================================================ */

$browsers = [];

if (
    $hasPageviews &&
    columnExists(
        $pdo,
        'analytics_pageviews',
        'browser'
    )
) {

    $browsers =
        safeRows(
            $pdo,
            "
            SELECT
                COALESCE(
                    NULLIF(browser, ''),
                    'Unknown'
                ) AS label,
                COUNT(*) AS total
            FROM analytics_pageviews
            {$dateCondition}
            GROUP BY label
            ORDER BY total DESC
            LIMIT 10
            "
        );
}


/* ============================================================
   OS
============================================================ */

$systems = [];

if (
    $hasPageviews &&
    columnExists(
        $pdo,
        'analytics_pageviews',
        'os'
    )
) {

    $systems =
        safeRows(
            $pdo,
            "
            SELECT
                COALESCE(
                    NULLIF(os, ''),
                    'Unknown'
                ) AS label,
                COUNT(*) AS total
            FROM analytics_pageviews
            {$dateCondition}
            GROUP BY label
            ORDER BY total DESC
            LIMIT 10
            "
        );
}


/* ============================================================
   COUNTRIES
============================================================ */

$countries = [];

if (
    $hasPageviews &&
    columnExists(
        $pdo,
        'analytics_pageviews',
        'country_code'
    )
) {

    $countries =
        safeRows(
            $pdo,
            "
            SELECT
                COALESCE(
                    NULLIF(ap.country_code, ''),
                    NULLIF(av.country_code, ''),
                    'Unknown'
                ) AS label,
                COUNT(*) AS total
            FROM analytics_pageviews ap
            LEFT JOIN analytics_visitors av ON av.visitor_id = ap.visitor_id
            {$dateCondition}
            GROUP BY label
            ORDER BY total DESC
            LIMIT 15
            "
        );
}


/* ============================================================
   CITIES
============================================================ */

$cities = [];

if (
    $hasPageviews &&
    columnExists(
        $pdo,
        'analytics_pageviews',
        'city'
    )
) {

    $cities =
        safeRows(
            $pdo,
            "
            SELECT
                COALESCE(
                    NULLIF(city, ''),
                    'Unknown'
                ) AS label,
                COUNT(*) AS total
            FROM analytics_pageviews
            {$dateCondition}
            GROUP BY label
            ORDER BY total DESC
            LIMIT 15
            "
        );
}


/* ============================================================
   REFERRERS
============================================================ */

$referrers = [];

if (
    $hasPageviews &&
    columnExists(
        $pdo,
        'analytics_pageviews',
        'referrer_host'
    )
) {

    $referrers =
        safeRows(
            $pdo,
            "
            SELECT
                COALESCE(
                    NULLIF(referrer_host, ''),
                    'Direct'
                ) AS label,
                COUNT(*) AS total
            FROM analytics_pageviews
            {$dateCondition}
            GROUP BY label
            ORDER BY total DESC
            LIMIT 15
            "
        );
}


/* ============================================================
   TRAFFIC SOURCE
============================================================ */

$sources = [];

if (
    $hasPageviews &&
    columnExists(
        $pdo,
        'analytics_pageviews',
        'traffic_source'
    )
) {

    $sources =
        safeRows(
            $pdo,
            "
            SELECT
                COALESCE(
                    NULLIF(traffic_source, ''),
                    'Direct'
                ) AS label,
                COUNT(*) AS total
            FROM analytics_pageviews
            {$dateCondition}
            GROUP BY label
            ORDER BY total DESC
            LIMIT 15
            "
        );
}


/* ============================================================
   UTM SOURCE
============================================================ */

$utmSources = [];

if (
    $hasPageviews &&
    columnExists(
        $pdo,
        'analytics_pageviews',
        'utm_source'
    )
) {

    $utmSources =
        safeRows(
            $pdo,
            "
            SELECT
                COALESCE(
                    NULLIF(utm_source, ''),
                    'None'
                ) AS label,
                COUNT(*) AS total
            FROM analytics_pageviews
            {$dateCondition}
            GROUP BY label
            ORDER BY total DESC
            LIMIT 10
            "
        );
}


/* ============================================================
   UTM CAMPAIGN
============================================================ */

$utmCampaigns = [];

if (
    $hasPageviews &&
    columnExists(
        $pdo,
        'analytics_pageviews',
        'utm_campaign'
    )
) {

    $utmCampaigns =
        safeRows(
            $pdo,
            "
            SELECT
                COALESCE(
                    NULLIF(utm_campaign, ''),
                    'None'
                ) AS label,
                COUNT(*) AS total
            FROM analytics_pageviews
            {$dateCondition}
            GROUP BY label
            ORDER BY total DESC
            LIMIT 10
            "
        );
}


/* ============================================================
   DAILY TRAFFIC
============================================================ */

$dailyTraffic = [];

if ($hasPageviews) {

    $dailyTraffic =
        safeRows(
            $pdo,
            "
            SELECT
                DATE(viewed_at) AS day,
                COUNT(*) AS views,
                COUNT(
                    DISTINCT visitor_id
                ) AS visitors
            FROM analytics_pageviews
            WHERE viewed_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
            GROUP BY DATE(viewed_at)
            ORDER BY day ASC
            "
        );
}


/* ============================================================
   EVENT COUNTS
============================================================ */

$eventStats = [];

if ($hasEvents) {

    if (
        columnExists(
            $pdo,
            'analytics_events',
            'event_name'
        )
    ) {

        $eventStats =
            safeRows(
                $pdo,
                "
                SELECT
                    event_name AS label,
                    COUNT(*) AS total
                FROM analytics_events
                {$dateCondition}
                GROUP BY event_name
                ORDER BY total DESC
                LIMIT 15
                "
            );
    }
}


/* ============================================================
   LIVE ROWS
============================================================ */

$liveRows = [];

if ($hasLive) {

    $liveRows =
        safeRows(
            $pdo,
            "
            SELECT *
            FROM analytics_live
            WHERE last_seen >= DATE_SUB(NOW(), INTERVAL 5 MINUTE)
            ORDER BY last_seen DESC
            LIMIT 40
            "
        );
}


/* ============================================================
   RECENT VISITORS
============================================================ */

$recentVisitors = [];

if ($hasVisitors) {

    $recentVisitors =
        safeRows(
            $pdo,
            "
            SELECT *
            FROM analytics_visitors
            ORDER BY last_seen DESC
            LIMIT 30
            "
        );
}


/* ============================================================
   VISITOR PATH
============================================================ */

$selectedVisitor =
    trim(
        (string)(
            $_GET['visitor'] ?? ''
        )
    );

$visitorPath = [];

if (
    $selectedVisitor !== '' &&
    $hasPageviews
) {

    $visitorPath =
        safeRows(
            $pdo,
            "
            SELECT *
            FROM analytics_pageviews
            WHERE visitor_id = ?
            ORDER BY viewed_at ASC
            LIMIT 500
            ",
            [
                $selectedVisitor
            ]
        );
}


/* ============================================================
   PAGE HTML
============================================================ */

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
Smart-Tooz Analytics
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

    font-size: 12px;
}


/* ============================================================
   HEADER
============================================================ */

.header {

    position: sticky;

    top: 0;

    z-index: 100;

    background: #151827;

    color: #fff;
}

.header-inner {

    width:
        min(1450px, calc(100% - 20px));

    min-height: 54px;

    margin: auto;

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 10px;
}

.brand {

    display: flex;

    align-items: center;

    gap: 8px;

    font-weight: 800;
}

.brand-icon {

    width: 32px;

    height: 32px;

    display: grid;

    place-items: center;

    border-radius: 9px;

    background:
        linear-gradient(
            135deg,
            #635bff,
            #916cff
        );
}

.brand-title {

    font-size: 14px;
}

.header-links {

    display: flex;

    gap: 5px;
}

.header-links a {

    padding:
        6px 9px;

    border-radius: 7px;

    color: #dce1ec;

    background:
        rgba(255,255,255,.07);

    font-size: 10px;

    font-weight: 700;
}

.header-links a:hover {

    background:
        rgba(255,255,255,.15);
}


/* ============================================================
   MAIN
============================================================ */

.container {

    width:
        min(1450px, calc(100% - 20px));

    margin:
        15px auto 40px;
}


/* ============================================================
   TITLE
============================================================ */

.title-row {

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 12px;

    margin-bottom: 12px;
}

.title h1 {

    margin: 0;

    font-size: 23px;

    letter-spacing: -.7px;
}

.title p {

    margin:
        2px 0 0;

    color: #7e8797;

    font-size: 10px;
}


/* ============================================================
   FILTERS
============================================================ */

.filters {

    display: flex;

    gap: 5px;

    flex-wrap: wrap;
}

.filters a {

    padding:
        6px 9px;

    background: #fff;

    border:
        1px solid #e1e5ec;

    border-radius: 7px;

    color: #657084;

    font-size: 10px;

    font-weight: 700;
}

.filters a.active {

    background: #635bff;

    border-color: #635bff;

    color: #fff;
}


/* ============================================================
   STATS
============================================================ */

.stats {

    display: grid;

    grid-template-columns:
        repeat(4, 1fr);

    gap: 8px;

    margin-bottom: 10px;
}

.stat {

    min-width: 0;

    padding:
        11px 13px;

    background: #fff;

    border:
        1px solid #e2e6ed;

    border-radius: 11px;
}

.stat-label {

    color: #80899a;

    font-size: 9px;

    font-weight: 800;

    text-transform: uppercase;
}

.stat-value {

    margin-top: 2px;

    font-size: 21px;

    line-height: 1.1;

    font-weight: 850;
}

.stat.live .stat-value {

    color: #149657;
}


/* ============================================================
   GRID
============================================================ */

.grid {

    display: grid;

    grid-template-columns:
        repeat(2, minmax(0, 1fr));

    gap: 9px;

    margin-bottom: 9px;
}


/* ============================================================
   CARD
============================================================ */

.card {

    min-width: 0;

    padding: 13px;

    background: #fff;

    border:
        1px solid #e2e6ed;

    border-radius: 11px;

    overflow: hidden;
}

.card h2 {

    margin: 0 0 9px;

    font-size: 13px;
}

.card-note {

    margin:
        -5px 0 8px;

    color: #8992a1;

    font-size: 9px;
}


/* ============================================================
   TABLE
============================================================ */

.table-wrap {

    width: 100%;

    overflow-x: auto;
}

table {

    width: 100%;

    border-collapse: collapse;

    min-width: 420px;
}

th {

    padding:
        6px 7px;

    text-align: left;

    border-bottom:
        1px solid #e9ecf1;

    color: #8992a2;

    font-size: 8px;

    text-transform: uppercase;

    white-space: nowrap;
}

td {

    padding:
        7px;

    border-bottom:
        1px solid #f0f2f5;

    color: #586477;

    font-size: 9px;

    vertical-align: top;
}

tr:last-child td {
    border-bottom: 0;
}

.path {

    color: #635bff;

    font-weight: 700;

    overflow-wrap: anywhere;
}


/* ============================================================
   BAR
============================================================ */

.bar {

    margin-bottom: 8px;
}

.bar:last-child {
    margin-bottom: 0;
}

.bar-top {

    display: flex;

    justify-content: space-between;

    gap: 8px;

    margin-bottom: 3px;

    color: #596477;

    font-size: 9px;

    font-weight: 700;
}

.bar-label {

    min-width: 0;

    overflow: hidden;

    text-overflow: ellipsis;

    white-space: nowrap;
}

.bar-track {

    height: 5px;

    background: #edf0f4;

    border-radius: 20px;

    overflow: hidden;
}

.bar-fill {

    height: 100%;

    background: #635bff;

    border-radius: 20px;
}


/* ============================================================
   TRAFFIC
============================================================ */

.traffic-row {

    display: grid;

    grid-template-columns:
        75px 1fr 90px;

    gap: 7px;

    align-items: center;

    margin-bottom: 6px;
}

.traffic-day {

    color: #697487;

    font-size: 9px;
}

.traffic-value {

    color: #697487;

    font-size: 8px;

    text-align: right;
}


/* ============================================================
   LIVE
============================================================ */

.live-table td {

    padding:
        6px;
}

.live-badge {

    display: inline-block;

    padding:
        2px 5px;

    border-radius: 5px;

    background: #eaf9f0;

    color: #168b51;

    font-size: 8px;

    font-weight: 800;
}


/* ============================================================
   PATH
============================================================ */

.path-list {

    display: flex;

    flex-direction: column;
}

.path-item {

    position: relative;

    padding:
        5px 5px 7px 21px;
}

.path-item::before {

    content: '';

    position: absolute;

    left: 6px;

    top: 12px;

    bottom: -3px;

    width: 1px;

    background: #dfe3ea;
}

.path-item:last-child::before {
    display: none;
}

.path-item::after {

    content: '';

    position: absolute;

    left: 2px;

    top: 7px;

    width: 9px;

    height: 9px;

    border-radius: 50%;

    background: #635bff;
}

.path-page {

    color: #635bff;

    font-size: 10px;

    font-weight: 800;

    overflow-wrap: anywhere;
}

.path-time {

    margin-top: 2px;

    color: #9199a7;

    font-size: 8px;
}


/* ============================================================
   EMPTY
============================================================ */

.empty {

    padding: 16px 5px;

    color: #9098a7;

    text-align: center;

    font-size: 9px;
}


/* ============================================================
   FOOTER
============================================================ */

.footer {

    padding:
        20px;

    text-align: center;

    color: #9098a7;

    font-size: 9px;
}


/* ============================================================
   MOBILE
============================================================ */

@media(max-width: 800px) {

    .stats {

        grid-template-columns:
            repeat(2, 1fr);
    }

    .grid {

        grid-template-columns: 1fr;
    }

    .title-row {

        align-items: flex-start;

        flex-direction: column;
    }

}


@media(max-width: 500px) {

    .container {

        width:
            calc(100% - 12px);

        margin-top: 10px;
    }

    .header-inner {

        width:
            calc(100% - 10px);
    }

    .brand-title {

        font-size: 12px;
    }

    .header-links a {

        padding:
            5px 6px;

        font-size: 8px;
    }

    .stats {

        gap: 6px;
    }

    .stat {

        padding:
            9px 10px;
    }

    .stat-value {

        font-size: 18px;
    }

    .card {

        padding: 10px;
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

        <div class="brand-title">
            Smart-Tooz Analytics
        </div>

    </div>


    <div class="header-links">

        <a href="/">
            🌐 Site
        </a>

        <a href="/smart-toolz/ads.php">
            📢 Ads
        </a>

    </div>


</div>

</header>


<!-- ============================================================
     MAIN
============================================================ -->

<main class="container">


    <!-- TITLE -->

    <div class="title-row">


        <div class="title">

            <h1>
                Analytics
            </h1>

            <p>
                Visitors · Traffic · Devices · Behaviour
            </p>

        </div>


        <div class="filters">

            <a
                href="?period=today"
                class="<?= $period === 'today' ? 'active' : '' ?>"
            >
                Today
            </a>

            <a
                href="?period=7"
                class="<?= $period === '7' ? 'active' : '' ?>"
            >
                7D
            </a>

            <a
                href="?period=30"
                class="<?= $period === '30' ? 'active' : '' ?>"
            >
                30D
            </a>

            <a
                href="?period=all"
                class="<?= $period === 'all' ? 'active' : '' ?>"
            >
                All
            </a>

        </div>


    </div>


    <!-- ========================================================
         STATS
    ========================================================= -->

    <div class="stats">


        <div class="stat">

            <div class="stat-label">
                Visitors
            </div>

            <div class="stat-value">
                <?= number_format($totalVisitors) ?>
            </div>

        </div>


        <div class="stat">

            <div class="stat-label">
                Page Views
            </div>

            <div class="stat-value">
                <?= number_format($pageViews) ?>
            </div>

        </div>


        <div class="stat">

            <div class="stat-label">
                Sessions
            </div>

            <div class="stat-value">
                <?= number_format($totalSessions) ?>
            </div>

        </div>


        <div class="stat live">

            <div class="stat-label">
                Live Now
            </div>

            <div class="stat-value">

                ● <?= number_format($liveVisitors) ?>

            </div>

        </div>


    </div>


    <!-- ========================================================
         DAILY TRAFFIC
    ========================================================= -->

    <section class="card" style="margin-bottom:9px;">

        <h2>
            📈 Traffic — Last 30 Days
        </h2>


        <?php if (!$dailyTraffic): ?>

            <div class="empty">
                No traffic data yet.
            </div>

        <?php else: ?>


            <?php

            $maxViews = 1;

            foreach ($dailyTraffic as $row) {

                $v =
                    (int)$row['views'];

                if ($v > $maxViews) {
                    $maxViews = $v;
                }
            }

            ?>


            <?php foreach ($dailyTraffic as $row): ?>

                <?php

                $views =
                    (int)$row['views'];

                $visitors =
                    (int)$row['visitors'];

                $width =
                    ($views / $maxViews) * 100;

                ?>


                <div class="traffic-row">

                    <div class="traffic-day">

                        <?= e($row['day']) ?>

                    </div>


                    <div class="bar-track">

                        <div
                            class="bar-fill"
                            style="width:<?= $width ?>%;"
                        ></div>

                    </div>


                    <div class="traffic-value">

                        <?= number_format($views) ?>
                        views /
                        <?= number_format($visitors) ?>

                    </div>

                </div>

            <?php endforeach; ?>


        <?php endif; ?>

    </section>


    <!-- ========================================================
         DEVICES + SOURCES
    ========================================================= -->

    <div class="grid">


        <section class="card">

            <h2>
                📱 Devices
            </h2>


            <?php

            $deviceTotal = 0;

            foreach ($devices as $x) {
                $deviceTotal +=
                    (int)$x['total'];
            }

            ?>


            <?php if (!$devices): ?>

                <div class="empty">
                    No device data.
                </div>

            <?php else: ?>

                <?php foreach ($devices as $row): ?>

                    <?php

                    $total =
                        (int)$row['total'];

                    $width =
                        $deviceTotal > 0
                        ? ($total / $deviceTotal * 100)
                        : 0;

                    ?>

                    <div class="bar">

                        <div class="bar-top">

                            <span class="bar-label">
                                <?= e($row['label']) ?>
                            </span>

                            <span>
                                <?= number_format($total) ?>
                            </span>

                        </div>

                        <div class="bar-track">

                            <div
                                class="bar-fill"
                                style="width:<?= $width ?>%;"
                            ></div>

                        </div>

                    </div>

                <?php endforeach; ?>

            <?php endif; ?>


        </section>


        <section class="card">

            <h2>
                🔗 Traffic Sources
            </h2>


            <?php

            $sourceTotal = 0;

            foreach ($sources as $x) {
                $sourceTotal +=
                    (int)$x['total'];
            }

            ?>


            <?php if (!$sources): ?>

                <div class="empty">
                    No source data.
                </div>

            <?php else: ?>

                <?php foreach ($sources as $row): ?>

                    <?php

                    $total =
                        (int)$row['total'];

                    $width =
                        $sourceTotal > 0
                        ? ($total / $sourceTotal * 100)
                        : 0;

                    ?>

                    <div class="bar">

                        <div class="bar-top">

                            <span class="bar-label">
                                <?= e($row['label']) ?>
                            </span>

                            <span>
                                <?= number_format($total) ?>
                            </span>

                        </div>

                        <div class="bar-track">

                            <div
                                class="bar-fill"
                                style="width:<?= $width ?>%;"
                            ></div>

                        </div>

                    </div>

                <?php endforeach; ?>

            <?php endif; ?>

        </section>


    </div>


    <!-- ========================================================
         TOP PAGES + COUNTRIES
    ========================================================= -->

    <div class="grid">


        <section class="card">

            <h2>
                🔥 Top Pages / Tools
            </h2>


            <?php if (!$topPages): ?>

                <div class="empty">
                    No page data.
                </div>

            <?php else: ?>

                <div class="table-wrap">

                    <table>

                        <thead>

                            <tr>

                                <th>
                                    Page
                                </th>

                                <th>
                                    Views
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                        <?php foreach ($topPages as $row): ?>

                            <tr>

                                <td class="path">

                                    <?= e(
                                        $row['page_path']
                                    ) ?>

                                </td>

                                <td>

                                    <?= number_format(
                                        (int)$row['total']
                                    ) ?>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

            <?php endif; ?>


        </section>


        <section class="card">

            <h2>
                🌍 Countries
            </h2>


            <div class="table-wrap">

                <table>

                    <thead>

                        <tr>

                            <th>
                                Country
                            </th>

                            <th>
                                Views
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    <?php foreach ($countries as $row): ?>

                        <tr>

                            <td>
                                <?= e(
                                    $row['label']
                                ) ?>
                            </td>

                            <td>
                                <?= number_format(
                                    (int)$row['total']
                                ) ?>
                            </td>

                        </tr>

                    <?php endforeach; ?>

                    </tbody>

                </table>

            </div>


        </section>


    </div>


    <!-- ========================================================
         BROWSER + OS
    ========================================================= -->

    <div class="grid">


        <section class="card">

            <h2>
                🌐 Browsers
            </h2>


            <?php foreach ($browsers as $row): ?>

                <div class="bar">

                    <div class="bar-top">

                        <span class="bar-label">
                            <?= e(
                                $row['label']
                            ) ?>
                        </span>

                        <span>
                            <?= number_format(
                                (int)$row['total']
                            ) ?>
                        </span>

                    </div>

                    <div class="bar-track">

                        <div
                            class="bar-fill"
                            style="
                                width:
                                <?= $pageViews > 0
                                    ? (
                                        (int)$row['total']
                                        /
                                        $pageViews
                                        *
                                        100
                                    )
                                    : 0
                                ?>%;
                            "
                        ></div>

                    </div>

                </div>

            <?php endforeach; ?>


            <?php if (!$browsers): ?>

                <div class="empty">
                    No browser data.
                </div>

            <?php endif; ?>


        </section>


        <section class="card">

            <h2>
                💻 Operating Systems
            </h2>


            <?php foreach ($systems as $row): ?>

                <div class="bar">

                    <div class="bar-top">

                        <span class="bar-label">
                            <?= e(
                                $row['label']
                            ) ?>
                        </span>

                        <span>
                            <?= number_format(
                                (int)$row['total']
                            ) ?>
                        </span>

                    </div>

                    <div class="bar-track">

                        <div
                            class="bar-fill"
                            style="
                                width:
                                <?= $pageViews > 0
                                    ? (
                                        (int)$row['total']
                                        /
                                        $pageViews
                                        *
                                        100
                                    )
                                    : 0
                                ?>%;
                            "
                        ></div>

                    </div>

                </div>

            <?php endforeach; ?>


            <?php if (!$systems): ?>

                <div class="empty">
                    No OS data.
                </div>

            <?php endif; ?>


        </section>


    </div>


    <!-- ========================================================
         CITIES + REFERRERS
    ========================================================= -->

    <div class="grid">


        <section class="card">

            <h2>
                📍 Cities
            </h2>


            <div class="table-wrap">

                <table>

                    <thead>

                        <tr>

                            <th>
                                City
                            </th>

                            <th>
                                Views
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    <?php foreach ($cities as $row): ?>

                        <tr>

                            <td>
                                <?= e(
                                    $row['label']
                                ) ?>
                            </td>

                            <td>
                                <?= number_format(
                                    (int)$row['total']
                                ) ?>
                            </td>

                        </tr>

                    <?php endforeach; ?>

                    </tbody>

                </table>

            </div>


        </section>


        <section class="card">

            <h2>
                🔗 Referrers
            </h2>


            <div class="table-wrap">

                <table>

                    <thead>

                        <tr>

                            <th>
                                Referrer
                            </th>

                            <th>
                                Views
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    <?php foreach ($referrers as $row): ?>

                        <tr>

                            <td>
                                <?= e(
                                    $row['label']
                                ) ?>
                            </td>

                            <td>
                                <?= number_format(
                                    (int)$row['total']
                                ) ?>
                            </td>

                        </tr>

                    <?php endforeach; ?>

                    </tbody>

                </table>

            </div>


        </section>


    </div>


    <!-- ========================================================
         UTM
    ========================================================= -->

    <div class="grid">


        <section class="card">

            <h2>
                🎯 UTM Sources
            </h2>


            <?php foreach ($utmSources as $row): ?>

                <div class="bar">

                    <div class="bar-top">

                        <span class="bar-label">
                            <?= e(
                                $row['label']
                            ) ?>
                        </span>

                        <span>
                            <?= number_format(
                                (int)$row['total']
                            ) ?>
                        </span>

                    </div>

                    <div class="bar-track">

                        <div
                            class="bar-fill"
                            style="
                                width:
                                <?= $pageViews > 0
                                    ? (
                                        (int)$row['total']
                                        /
                                        $pageViews
                                        *
                                        100
                                    )
                                    : 0
                                ?>%;
                            "
                        ></div>

                    </div>

                </div>

            <?php endforeach; ?>


            <?php if (!$utmSources): ?>

                <div class="empty">
                    No UTM source data.
                </div>

            <?php endif; ?>


        </section>


        <section class="card">

            <h2>
                📣 UTM Campaigns
            </h2>


            <?php foreach ($utmCampaigns as $row): ?>

                <div class="bar">

                    <div class="bar-top">

                        <span class="bar-label">
                            <?= e(
                                $row['label']
                            ) ?>
                        </span>

                        <span>
                            <?= number_format(
                                (int)$row['total']
                            ) ?>
                        </span>

                    </div>

                    <div class="bar-track">

                        <div
                            class="bar-fill"
                            style="
                                width:
                                <?= $pageViews > 0
                                    ? (
                                        (int)$row['total']
                                        /
                                        $pageViews
                                        *
                                        100
                                    )
                                    : 0
                                ?>%;
                            "
                        ></div>

                    </div>

                </div>

            <?php endforeach; ?>


            <?php if (!$utmCampaigns): ?>

                <div class="empty">
                    No campaign data.
                </div>

            <?php endif; ?>


        </section>


    </div>


    <!-- ========================================================
         EVENTS
    ========================================================= -->

    <?php if ($hasEvents): ?>

        <section
            class="card"
            style="margin-bottom:9px;"
        >

            <h2>
                ⚡ User Events
            </h2>

            <div class="grid">

                <?php foreach ($eventStats as $row): ?>

                    <div class="bar">

                        <div class="bar-top">

                            <span class="bar-label">
                                <?= e(
                                    $row['label']
                                ) ?>
                            </span>

                            <span>
                                <?= number_format(
                                    (int)$row['total']
                                ) ?>
                            </span>

                        </div>

                        <div class="bar-track">

                            <div
                                class="bar-fill"
                                style="
                                    width:
                                    <?= $pageViews > 0
                                        ? (
                                            (int)$row['total']
                                            /
                                            $pageViews
                                            *
                                            100
                                        )
                                        : 0
                                    ?>%;
                                "
                            ></div>

                        </div>

                    </div>

                <?php endforeach; ?>

            </div>

        </section>

    <?php endif; ?>


    <!-- ========================================================
         LIVE VISITORS
    ========================================================= -->

    <section
        class="card"
        style="margin-bottom:9px;"
    >

        <h2>
            🟢 Live Visitors
        </h2>

        <div class="card-note">
            Active during the last 5 minutes.
        </div>


        <?php if (!$liveRows): ?>

            <div class="empty">
                No live visitors right now.
            </div>

        <?php else: ?>

            <div class="table-wrap">

                <table class="live-table">

                    <thead>

                        <tr>

                            <th>
                                Status
                            </th>

                            <th>
                                Location
                            </th>

                            <th>
                                Device
                            </th>

                            <th>
                                Browser
                            </th>

                            <th>
                                Current Page
                            </th>

                            <th>
                                Last Seen
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    <?php foreach ($liveRows as $row): ?>

                        <tr>

                            <td>

                                <span class="live-badge">
                                    LIVE
                                </span>

                            </td>


                            <td>

                                <?= e(
                                    $row['country_code']
                                    ?? 'Unknown'
                                ) ?>

                                <?php if (
                                    !empty(
                                        $row['city']
                                        ?? ''
                                    )
                                ): ?>

                                    <br>

                                    <?= e(
                                        $row['city']
                                    ) ?>

                                <?php endif; ?>

                            </td>


                            <td>
                                <?= e(
                                    $row['device']
                                    ?? 'Unknown'
                                ) ?>
                            </td>


                            <td>
                                <?= e(
                                    $row['browser']
                                    ?? 'Unknown'
                                ) ?>
                            </td>


                            <td class="path">
                                <?= e(
                                    $row['page_path']
                                    ?? '-'
                                ) ?>
                            </td>


                            <td>
                                <?= e(
                                    $row['last_seen']
                                    ?? '-'
                                ) ?>
                            </td>

                        </tr>

                    <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        <?php endif; ?>

    </section>


    <!-- ========================================================
         RECENT VISITORS
    ========================================================= -->

    <section
        class="card"
        style="margin-bottom:9px;"
    >

        <h2>
            👥 Recent Visitors
        </h2>

        <div class="card-note">
            Click a visitor to see the complete browsing path.
        </div>


        <?php if (!$recentVisitors): ?>

            <div class="empty">
                No visitor data yet.
            </div>

        <?php else: ?>

            <div class="table-wrap">

                <table>

                    <thead>

                        <tr>

                            <th>
                                Visitor
                            </th>

                            <th>
                                First
                            </th>

                            <th>
                                Last
                            </th>

                            <th>
                                Location
                            </th>

                            <th>
                                Device
                            </th>

                            <th>
                                Browser
                            </th>

                            <th>
                                Entry
                            </th>

                            <th>
                                Last Page
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    <?php foreach (
                        $recentVisitors
                        as $row
                    ): ?>

                        <?php

                        $visitorId =
                            (string)(
                                $row['visitor_id']
                                ?? ''
                            );

                        ?>

                        <tr>

                            <td>

                                <?php if (
                                    $visitorId !== ''
                                ): ?>

                                    <a
                                        href="?period=<?= e($period) ?>&visitor=<?= e($visitorId) ?>"
                                        style="
                                            color:#635bff;
                                            font-weight:800;
                                        "
                                    >

                                        <?= e(
                                            substr(
                                                $visitorId,
                                                0,
                                                8
                                            )
                                        ) ?>...

                                    </a>

                                <?php else: ?>

                                    -

                                <?php endif; ?>

                            </td>


                            <td>
                                <?= e(
                                    $row['first_seen']
                                    ?? '-'
                                ) ?>
                            </td>


                            <td>
                                <?= e(
                                    $row['last_seen']
                                    ?? '-'
                                ) ?>
                            </td>


                            <td>

                                <?= e(
                                    $row['country_code']
                                    ?? ''
                                ) ?>

                                <?php if (
                                    !empty(
                                        $row['city']
                                        ?? ''
                                    )
                                ): ?>

                                    <br>

                                    <?= e(
                                        $row['city']
                                    ) ?>

                                <?php endif; ?>

                            </td>


                            <td>
                                <?= e(
                                    $row['device']
                                    ?? '-'
                                ) ?>
                            </td>


                            <td>
                                <?= e(
                                    $row['browser']
                                    ?? '-'
                                ) ?>
                            </td>


                            <td class="path">
                                <?= e(
                                    $row['first_page']
                                    ?? '-'
                                ) ?>
                            </td>


                            <td class="path">
                                <?= e(
                                    $row['last_page']
                                    ?? '-'
                                ) ?>
                            </td>

                        </tr>

                    <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        <?php endif; ?>


    </section>


    <!-- ========================================================
         VISITOR JOURNEY
    ========================================================= -->

    <?php if (
        $selectedVisitor !== ''
    ): ?>

        <section class="card">

            <h2>
                🛣️ Visitor Journey
            </h2>

            <div class="card-note">

                Visitor:
                <?= e(
                    substr(
                        $selectedVisitor,
                        0,
                        16
                    )
                ) ?>...

            </div>


            <?php if (!$visitorPath): ?>

                <div class="empty">
                    No journey data found.
                </div>

            <?php else: ?>

                <div class="path-list">

                    <?php foreach (
                        $visitorPath
                        as $step
                    ): ?>

                        <div class="path-item">


                            <div class="path-page">

                                <?= e(
                                    $step['page_path']
                                    ?? '-'
                                ) ?>

                            </div>


                            <?php if (
                                !empty(
                                    $step['page_title']
                                    ?? ''
                                )
                            ): ?>

                                <div
                                    style="
                                        color:#667184;
                                        font-size:9px;
                                    "
                                >

                                    <?= e(
                                        $step['page_title']
                                    ) ?>

                                </div>

                            <?php endif; ?>


                            <div class="path-time">

                                <?= e(
                                    $step['viewed_at']
                                    ?? '-'
                                ) ?>

                                <?php if (
                                    !empty(
                                        $step['referrer']
                                        ?? ''
                                    )
                                ): ?>

                                    ·

                                    From:
                                    <?= e(
                                        $step['referrer']
                                    ) ?>

                                <?php endif; ?>

                            </div>


                        </div>

                    <?php endforeach; ?>

                </div>

            <?php endif; ?>


        </section>

    <?php endif; ?>


</main>


<footer class="footer">

    Smart-Tooz Analytics

</footer>


<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>


<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
</body>

</html>
<?php
declare(strict_types=1);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
/*
|--------------------------------------------------------------------------
| Smart-Tooz Analytics Tracker
|--------------------------------------------------------------------------
| File:
| /analytics/tracker.php
|
| Existing DB:
| /creator-ai/auth/config.php
|
| This file tracks:
| - Visitor
| - Session
| - Page view
| - Current URL
| - Referrer
| - Device
| - Browser
| - OS
| - IP
| - User Agent
| - Entry page
| - Last page
| - Country / city when available
|--------------------------------------------------------------------------
*/


/* ============================================================
   LOAD DATABASE
============================================================ */

require_once $_SERVER['DOCUMENT_ROOT'] . '/creator-ai/auth/config.php';

$pdo = db();

if (!$pdo instanceof PDO) {
    return;
}


/* ============================================================
   CONFIG
============================================================ */

/*
 * Active visitor ko kitne minutes tak "online" maana jaye.
 */
const ANALYTICS_ACTIVE_MINUTES = 5;


/* ============================================================
   HELPER
============================================================ */

function analyticsClean(string $value, int $max = 1000): string
{
    $value = trim($value);

    if ($value === '') {
        return '';
    }

    return mb_substr(
        $value,
        0,
        $max
    );
}


/* ============================================================
   IP ADDRESS
============================================================ */

function analyticsGetIP(): string
{
    /*
     * Cloudflare ho to CF-Connecting-IP use karo.
     */
    if (
        !empty($_SERVER['HTTP_CF_CONNECTING_IP']) &&
        filter_var(
            $_SERVER['HTTP_CF_CONNECTING_IP'],
            FILTER_VALIDATE_IP
        )
    ) {
        return $_SERVER['HTTP_CF_CONNECTING_IP'];
    }


    /*
     * Normal connection.
     */
    if (
        !empty($_SERVER['REMOTE_ADDR']) &&
        filter_var(
            $_SERVER['REMOTE_ADDR'],
            FILTER_VALIDATE_IP
        )
    ) {
        return $_SERVER['REMOTE_ADDR'];
    }


    return '';
}


/* ============================================================
   USER AGENT
============================================================ */

$userAgent =
    analyticsClean(
        (string)($_SERVER['HTTP_USER_AGENT'] ?? ''),
        1000
    );


/* ============================================================
   REQUEST URL
============================================================ */

$scheme =
    (
        (!empty($_SERVER['HTTPS']) &&
        $_SERVER['HTTPS'] !== 'off')
        ||
        (
            isset($_SERVER['SERVER_PORT']) &&
            (int)$_SERVER['SERVER_PORT'] === 443
        )
    )
        ? 'https'
        : 'http';


$host =
    analyticsClean(
        (string)($_SERVER['HTTP_HOST'] ?? ''),
        255
    );


$requestUri =
    analyticsClean(
        (string)($_SERVER['REQUEST_URI'] ?? '/'),
        2000
    );


$currentUrl =
    $scheme .
    '://' .
    $host .
    $requestUri;


/* ============================================================
   PAGE PATH
============================================================ */

$pagePath =
    (string)(
        parse_url(
            $requestUri,
            PHP_URL_PATH
        ) ?? '/'
    );

$pagePath =
    analyticsClean(
        $pagePath,
        1000
    );


/* ============================================================
   REFERRER
============================================================ */

$referrer =
    analyticsClean(
        (string)(
            $_SERVER['HTTP_REFERER'] ?? ''
        ),
        2000
    );


/* ============================================================
   PAGE TITLE
============================================================ */

$pageTitle = '';

/*
 * Agar page se HTTP header/meta ke through title available
 * nahi hai to filename se fallback banega.
 */
if ($pagePath !== '') {

    $filename =
        basename(
            $pagePath
        );

    $filename =
        preg_replace(
            '/\.php$/i',
            '',
            $filename
        );

    $filename =
        str_replace(
            ['-', '_'],
            ' ',
            (string)$filename
        );

    $pageTitle =
        ucwords(
            $filename
        );
}

$pageTitle =
    analyticsClean(
        $pageTitle,
        255
    );


/* ============================================================
   DEVICE
============================================================ */

$device = 'Desktop';

if (
    preg_match(
        '/tablet|ipad|playbook|silk/i',
        $userAgent
    )
) {

    $device = 'Tablet';

} elseif (
    preg_match(
        '/mobile|android|iphone|ipod|blackberry|iemobile|opera mini/i',
        $userAgent
    )
) {

    $device = 'Mobile';

}


/* ============================================================
   OPERATING SYSTEM
============================================================ */

$os = 'Other';


if (
    preg_match(
        '/windows nt 10/i',
        $userAgent
    )
) {

    $os = 'Windows 10/11';

} elseif (
    preg_match(
        '/windows nt 6\.3/i',
        $userAgent
    )
) {

    $os = 'Windows 8.1';

} elseif (
    preg_match(
        '/windows nt 6\.2/i',
        $userAgent
    )
) {

    $os = 'Windows 8';

} elseif (
    preg_match(
        '/windows nt 6\.1/i',
        $userAgent
    )
) {

    $os = 'Windows 7';

} elseif (
    preg_match(
        '/windows/i',
        $userAgent
    )
) {

    $os = 'Windows';

} elseif (
    preg_match(
        '/iphone|ipad|ipod/i',
        $userAgent
    )
) {

    $os = 'iOS';

} elseif (
    preg_match(
        '/android/i',
        $userAgent
    )
) {

    $os = 'Android';

} elseif (
    preg_match(
        '/mac os x/i',
        $userAgent
    )
) {

    $os = 'macOS';

} elseif (
    preg_match(
        '/linux/i',
        $userAgent
    )
) {

    $os = 'Linux';

}


/* ============================================================
   BROWSER
============================================================ */

$browser = 'Other';


if (
    preg_match(
        '/edg/i',
        $userAgent
    )
) {

    $browser = 'Edge';

} elseif (
    preg_match(
        '/opr|opera/i',
        $userAgent
    )
) {

    $browser = 'Opera';

} elseif (
    preg_match(
        '/chrome|crios/i',
        $userAgent
    )
) {

    $browser = 'Chrome';

} elseif (
    preg_match(
        '/firefox|fxios/i',
        $userAgent
    )
) {

    $browser = 'Firefox';

} elseif (
    preg_match(
        '/safari/i',
        $userAgent
    )
) {

    $browser = 'Safari';

} elseif (
    preg_match(
        '/msie|trident/i',
        $userAgent
    )
) {

    $browser = 'Internet Explorer';

}


/* ============================================================
   REFERRER HOST
============================================================ */

$referrerHost = '';

if ($referrer !== '') {

    $referrerHost =
        (string)(
            parse_url(
                $referrer,
                PHP_URL_HOST
            ) ?? ''
        );

    $referrerHost =
        analyticsClean(
            $referrerHost,
            255
        );

}


/* ============================================================
   TRAFFIC SOURCE
============================================================ */

$trafficSource = 'Direct';


if ($referrerHost !== '') {

    $hostLower =
        strtolower(
            $referrerHost
        );


    if (
        str_contains(
            $hostLower,
            'google.'
        )
    ) {

        $trafficSource =
            'Google';

    } elseif (
        str_contains(
            $hostLower,
            'bing.'
        )
    ) {

        $trafficSource =
            'Bing';

    } elseif (
        str_contains(
            $hostLower,
            'yahoo.'
        )
    ) {

        $trafficSource =
            'Yahoo';

    } elseif (
        str_contains(
            $hostLower,
            'facebook.'
        )
    ) {

        $trafficSource =
            'Facebook';

    } elseif (
        str_contains(
            $hostLower,
            'instagram.'
        )
    ) {

        $trafficSource =
            'Instagram';

    } elseif (
        str_contains(
            $hostLower,
            'youtube.'
        )
    ) {

        $trafficSource =
            'YouTube';

    } elseif (
        str_contains(
            $hostLower,
            'twitter.'
        ) ||
        str_contains(
            $hostLower,
            'x.com'
        )
    ) {

        $trafficSource =
            'X / Twitter';

    } else {

        $trafficSource =
            'Referral';

    }

}


/* ============================================================
   UNIQUE VISITOR ID
============================================================ */

$visitorCookieName =
    'smarttooz_visitor';


$visitorId =
    $_COOKIE[$visitorCookieName]
    ?? '';


if (
    !preg_match(
        '/^[a-f0-9]{32}$/',
        $visitorId
    )
) {

    $visitorId =
        bin2hex(
            random_bytes(16)
        );


    /*
     * 1 year cookie.
     */
    setcookie(
        $visitorCookieName,
        $visitorId,
        [
            'expires' =>
                time() + 31536000,

            'path' =>
                '/',

            'secure' =>
                (!empty($_SERVER['HTTPS']) &&
                $_SERVER['HTTPS'] !== 'off'),

            'httponly' =>
                true,

            'samesite' =>
                'Lax'
        ]
    );

}


/* ============================================================
   SESSION ID
============================================================ */

if (
    session_status() !==
    PHP_SESSION_ACTIVE
) {

    /*
     * Existing session ho to use karega.
     * Agar session already configured nahi hai to
     * apna analytics session cookie use hoga.
     */

}


$sessionCookieName =
    'smarttooz_session';


$sessionId =
    $_COOKIE[$sessionCookieName]
    ?? '';


if (
    !preg_match(
        '/^[a-f0-9]{32}$/',
        $sessionId
    )
) {

    $sessionId =
        bin2hex(
            random_bytes(16)
        );


    /*
     * Session cookie.
     */
    setcookie(
        $sessionCookieName,
        $sessionId,
        [
            'expires' =>
                0,

            'path' =>
                '/',

            'secure' =>
                (!empty($_SERVER['HTTPS']) &&
                $_SERVER['HTTPS'] !== 'off'),

            'httponly' =>
                true,

            'samesite' =>
                'Lax'
        ]
    );

}


/* ============================================================
   IP
============================================================ */

$ipAddress =
    analyticsGetIP();


/* ============================================================
   PRIVACY-FRIENDLY IP HASH
============================================================ */

/*
 * Dashboard ke liye exact IP ki jagah hash bhi use kar sakte ho.
 * Database table mein ip_address field ho to exact IP save hoga.
 *
 * Agar privacy ke liye hash chahiye to:
 *
 * $ipHash = hash('sha256', $ipAddress);
 */

$ipHash =
    $ipAddress !== ''
        ? hash(
            'sha256',
            $ipAddress
        )
        : '';


/* ============================================================
   COUNTRY / CITY
============================================================ */

/*
 * Default values.
 *
 * Exact geolocation automatically assume nahi kar rahe.
 * Agar server/CDN headers country provide karta hai to use karenge.
 */

$country = '';

$countryCode = '';

$city = '';


/*
 * Cloudflare country header.
 */
if (
    !empty($_SERVER['HTTP_CF_IPCOUNTRY'])
) {

    $countryCode =
        strtoupper(
            analyticsClean(
                (string)$_SERVER['HTTP_CF_IPCOUNTRY'],
                10
            )
        );

}


/*
 * Cloudflare city header agar available ho.
 */
if (
    !empty($_SERVER['HTTP_CF_IPCITY'])
) {

    $city =
        analyticsClean(
            (string)$_SERVER['HTTP_CF_IPCITY'],
            255
        );

}


/*
 * Generic proxy country header.
 */
if (
    $countryCode === '' &&
    !empty($_SERVER['HTTP_X_COUNTRY_CODE'])
) {

    $countryCode =
        strtoupper(
            analyticsClean(
                (string)$_SERVER['HTTP_X_COUNTRY_CODE'],
                10
            )
        );

}


/* ============================================================
   BOT DETECTION
============================================================ */

$isBot = 0;


if (
    preg_match(
        '/bot|crawler|spider|slurp|bingpreview|facebookexternalhit|mediapartners-google/i',
        $userAgent
    )
) {

    $isBot = 1;

}


/*
 * Bots ko analytics mein store na karna ho to uncomment:
 *
 * if ($isBot === 1) {
 *     return;
 * }
 */


/* ============================================================
   CURRENT TIME
============================================================ */

$now =
    date(
        'Y-m-d H:i:s'
    );


/* ============================================================
   FIND EXISTING VISITOR
============================================================ */

$visitorDbId = null;


try {

    $stmt =
        $pdo->prepare("
            SELECT id
            FROM analytics_visitors
            WHERE visitor_id = ?
            LIMIT 1
        ");


    $stmt->execute([
        $visitorId
    ]);


    $existing =
        $stmt->fetch(
            PDO::FETCH_ASSOC
        );


    if ($existing) {

        $visitorDbId =
            (int)$existing['id'];

    }

} catch (Throwable $e) {

    /*
     * Table missing ya DB problem hone par
     * website ko break nahi karna.
     */
}


/* ============================================================
   CREATE VISITOR
============================================================ */

if ($visitorDbId === null) {

    try {

        $stmt =
            $pdo->prepare("
                INSERT INTO analytics_visitors
                (
                    visitor_id,
                    first_seen,
                    last_seen,
                    first_page,
                    last_page,
                    ip_address,
                    ip_hash,
                    user_agent,
                    device,
                    os,
                    browser,
                    country,
                    country_code,
                    city,
                    referrer,
                    traffic_source,
                    is_bot
                )
                VALUES
                (
                    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
                )
            ");


        $stmt->execute([

            $visitorId,

            $now,

            $now,

            $pagePath,

            $pagePath,

            $ipAddress,

            $ipHash,

            $userAgent,

            $device,

            $os,

            $browser,

            $country,

            $countryCode,

            $city,

            $referrer,

            $trafficSource,

            $isBot

        ]);


        $visitorDbId =
            (int)$pdo->lastInsertId();


    } catch (Throwable $e) {

        /*
         * Visitor insert fail hone par page normal chalega.
         */

        $visitorDbId = null;

    }

}


/* ============================================================
   UPDATE VISITOR
============================================================ */

if ($visitorDbId !== null) {

    try {

        $stmt =
            $pdo->prepare("
                UPDATE analytics_visitors

                SET
                    last_seen = ?,
                    last_page = ?,
                    ip_address = ?,
                    ip_hash = ?,
                    user_agent = ?,
                    device = ?,
                    os = ?,
                    browser = ?,
                    country = ?,
                    country_code = ?,
                    city = ?,
                    referrer = ?,
                    traffic_source = ?

                WHERE id = ?
            ");


        $stmt->execute([

            $now,

            $pagePath,

            $ipAddress,

            $ipHash,

            $userAgent,

            $device,

            $os,

            $browser,

            $country,

            $countryCode,

            $city,

            $referrer,

            $trafficSource,

            $visitorDbId

        ]);

    } catch (Throwable $e) {
        /*
         * Ignore analytics errors.
         */
    }

}


/* ============================================================
   PAGE VIEW
============================================================ */

try {

    $stmt =
        $pdo->prepare("
            INSERT INTO analytics_pageviews
            (
                visitor_id,
                session_id,
                page_path,
                page_url,
                page_title,
                referrer,
                referrer_host,
                traffic_source,
                device,
                os,
                browser,
                country,
                country_code,
                city,
                ip_address,
                ip_hash,
                user_agent,
                is_bot,
                viewed_at
            )
            VALUES
            (
                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
            )
        ");


    $stmt->execute([

        $visitorId,

        $sessionId,

        $pagePath,

        $currentUrl,

        $pageTitle,

        $referrer,

        $referrerHost,

        $trafficSource,

        $device,

        $os,

        $browser,

        $country,

        $countryCode,

        $city,

        $ipAddress,

        $ipHash,

        $userAgent,

        $isBot,

        $now

    ]);

} catch (Throwable $e) {

    /*
     * Analytics fail hone par website fail nahi hogi.
     */

}


/* ============================================================
   LIVE VISITOR
============================================================ */

try {

    $stmt =
        $pdo->prepare("
            INSERT INTO analytics_live
            (
                visitor_id,
                session_id,
                page_path,
                page_url,
                page_title,
                device,
                os,
                browser,
                country,
                country_code,
                city,
                referrer,
                traffic_source,
                ip_address,
                ip_hash,
                last_seen
            )
            VALUES
            (
                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
            )

            ON DUPLICATE KEY UPDATE

                page_path = VALUES(page_path),

                page_url = VALUES(page_url),

                page_title = VALUES(page_title),

                device = VALUES(device),

                os = VALUES(os),

                browser = VALUES(browser),

                country = VALUES(country),

                country_code = VALUES(country_code),

                city = VALUES(city),

                referrer = VALUES(referrer),

                traffic_source = VALUES(traffic_source),

                ip_address = VALUES(ip_address),

                ip_hash = VALUES(ip_hash),

                last_seen = VALUES(last_seen)
        ");


    $stmt->execute([

        $visitorId,

        $sessionId,

        $pagePath,

        $currentUrl,

        $pageTitle,

        $device,

        $os,

        $browser,

        $country,

        $countryCode,

        $city,

        $referrer,

        $trafficSource,

        $ipAddress,

        $ipHash,

        $now

    ]);

} catch (Throwable $e) {

    /*
     * Live tracking fail hone par site normal chalegi.
     */

}


/* ============================================================
   OPTIONAL GLOBAL VARIABLES
============================================================ */

/*
 * Agar future dashboard ko current visitor information
 * chahiye to ye variables available rahenge.
 */

$GLOBALS['smartToozAnalytics'] = [

    'visitor_id' =>
        $visitorId,

    'session_id' =>
        $sessionId,

    'page_path' =>
        $pagePath,

    'device' =>
        $device,

    'os' =>
        $os,

    'browser' =>
        $browser,

    'country' =>
        $country,

    'country_code' =>
        $countryCode,

    'city' =>
        $city,

    'referrer' =>
        $referrer,

    'traffic_source' =>
        $trafficSource

];

?>
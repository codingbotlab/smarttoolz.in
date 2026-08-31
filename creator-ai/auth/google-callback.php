<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';

/*
|--------------------------------------------------------------------------
| Google OAuth Callback
|--------------------------------------------------------------------------
| Google login ke baad:
|
| 1. Google profile verify
| 2. Main users table create/update
| 3. Creator AI user create/update
| 4. New Creator user ko 50 daily credits
| 5. Creator AI session set
| 6. Workspace par redirect
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Google Error
|--------------------------------------------------------------------------
*/

if (isset($_GET['error'])) {
    exit(
        'Google sign-in was cancelled or failed: ' .
        htmlspecialchars(
            (string)$_GET['error'],
            ENT_QUOTES,
            'UTF-8'
        )
    );
}

/*
|--------------------------------------------------------------------------
| OAuth State Verification
|--------------------------------------------------------------------------
*/

if (
    empty($_GET['state']) ||
    empty($_SESSION['oauth_state']) ||
    !hash_equals(
        (string)$_SESSION['oauth_state'],
        (string)$_GET['state']
    )
) {
    http_response_code(400);
    exit('Invalid OAuth state.');
}

unset($_SESSION['oauth_state']);

/*
|--------------------------------------------------------------------------
| Authorization Code
|--------------------------------------------------------------------------
*/

if (empty($_GET['code'])) {
    http_response_code(400);
    exit('Missing Google authorization code.');
}

/*
|--------------------------------------------------------------------------
| Exchange Google Code For Access Token
|--------------------------------------------------------------------------
*/

try {

    $tokenResponse = http_post(
        'https://oauth2.googleapis.com/token',
        [
            'code' => (string)$_GET['code'],
            'client_id' => GOOGLE_CLIENT_ID,
            'client_secret' => GOOGLE_CLIENT_SECRET,
            'redirect_uri' => GOOGLE_REDIRECT_URI,
            'grant_type' => 'authorization_code',
        ]
    );

} catch (Throwable $e) {

    error_log(
        'Google Token Error: ' .
        $e->getMessage()
    );

    http_response_code(500);
    exit('Unable to connect to Google.');
}

$token = json_decode(
    $tokenResponse,
    true
);

if (
    !is_array($token) ||
    empty($token['access_token'])
) {
    error_log(
        'Google Token Response: ' .
        $tokenResponse
    );

    http_response_code(400);
    exit('Unable to obtain Google access token.');
}

$accessToken = (string)$token['access_token'];

/*
|--------------------------------------------------------------------------
| Get Google User Profile
|--------------------------------------------------------------------------
*/

try {

    $userResponse = http_get(
        'https://openidconnect.googleapis.com/v1/userinfo',
        [
            'Authorization: Bearer ' . $accessToken
        ]
    );

} catch (Throwable $e) {

    error_log(
        'Google Userinfo Error: ' .
        $e->getMessage()
    );

    http_response_code(500);
    exit('Unable to get Google account information.');
}

$googleUser = json_decode(
    $userResponse,
    true
);

if (
    !is_array($googleUser) ||
    empty($googleUser['sub']) ||
    empty($googleUser['email']) ||
    empty($googleUser['email_verified'])
) {
    http_response_code(400);
    exit(
        'Google account information could not be verified.'
    );
}

/*
|--------------------------------------------------------------------------
| Google Profile Data
|--------------------------------------------------------------------------
*/

$googleId = trim(
    (string)$googleUser['sub']
);

$email = strtolower(
    trim(
        (string)$googleUser['email']
    )
);

$name = trim(
    (string)(
        $googleUser['name']
        ?? 'Creator User'
    )
);

$avatar = trim(
    (string)(
        $googleUser['picture']
        ?? ''
    )
);

if ($name === '') {
    $name = 'Creator User';
}

if ($email === '') {
    http_response_code(400);
    exit('Google email is missing.');
}

/*
|--------------------------------------------------------------------------
| Database
|--------------------------------------------------------------------------
*/

$pdo = db();

/*
|--------------------------------------------------------------------------
| Database Transaction
|--------------------------------------------------------------------------
*/

try {

    $pdo->beginTransaction();

    /*
    |--------------------------------------------------------------------------
    | 1. MAIN USERS TABLE
    |--------------------------------------------------------------------------
    */

    $stmt = $pdo->prepare("
        SELECT *
        FROM users
        WHERE google_id = ?
           OR email = ?
        LIMIT 1
        FOR UPDATE
    ");

    $stmt->execute([
        $googleId,
        $email
    ]);

    $mainUser = $stmt->fetch();

    if ($mainUser) {

        $mainUserId = (int)$mainUser['id'];

        $update = $pdo->prepare("
            UPDATE users
            SET
                google_id = ?,
                name = ?,
                avatar = ?,
                updated_at = CURRENT_TIMESTAMP
            WHERE id = ?
        ");

        $update->execute([
            $googleId,
            $name,
            $avatar !== '' ? $avatar : null,
            $mainUserId
        ]);

    } else {

        $insert = $pdo->prepare("
            INSERT INTO users
            (
                google_id,
                name,
                email,
                avatar
            )
            VALUES (?, ?, ?, ?)
        ");

        $insert->execute([
            $googleId,
            $name,
            $email,
            $avatar !== '' ? $avatar : null
        ]);

        $mainUserId = (int)$pdo->lastInsertId();
    }

    /*
    |--------------------------------------------------------------------------
    | 2. CREATOR AI USER
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    | Creator AI ka separate account creator_users mein maintain hoga.
    |
    */

    $stmt = $pdo->prepare("
        SELECT
            id,
            google_id,
            name,
            email,
            avatar,
            credits,
            daily_credits,
            daily_credits_reset_at
        FROM creator_users
        WHERE google_id = ?
           OR email = ?
        LIMIT 1
        FOR UPDATE
    ");

    $stmt->execute([
        $googleId,
        $email
    ]);

    $creatorUser = $stmt->fetch();

    /*
    |--------------------------------------------------------------------------
    | Existing Creator AI Account
    |--------------------------------------------------------------------------
    */

    if ($creatorUser) {

        $creatorUserId = (int)$creatorUser['id'];

        $updateCreator = $pdo->prepare("
            UPDATE creator_users
            SET
                google_id = ?,
                name = ?,
                email = ?,
                avatar = ?
            WHERE id = ?
        ");

        $updateCreator->execute([
            $googleId,
            $name,
            $email,
            $avatar,
            $creatorUserId
        ]);

        /*
        |--------------------------------------------------------------------------
        | Existing account with no daily credit reset date
        |--------------------------------------------------------------------------
        |
        | Agar purana account hai aur daily system initialize nahi hua,
        | to first login par 50 credits initialize kar do.
        |
        */

        $existingDailyCredits = (int)(
            $creatorUser['daily_credits']
            ?? 0
        );

        $existingResetAt =
            $creatorUser['daily_credits_reset_at']
            ?? null;

        if (
            empty($existingResetAt) &&
            $existingDailyCredits <= 0
        ) {

            $nextReset = (new DateTimeImmutable())
                ->modify('+24 hours')
                ->format('Y-m-d H:i:s');

            $dailyUpdate = $pdo->prepare("
                UPDATE creator_users
                SET
                    daily_credits = 50,
                    daily_credits_reset_at = ?
                WHERE id = ?
            ");

            $dailyUpdate->execute([
                $nextReset,
                $creatorUserId
            ]);

            /*
            |--------------------------------------------------------------------------
            | Daily Credit Transaction
            |--------------------------------------------------------------------------
            */

            try {

                $transaction = $pdo->prepare("
                    INSERT INTO creator_credit_transactions
                    (
                        user_id,
                        type,
                        amount,
                        balance_after,
                        request_id,
                        description
                    )
                    VALUES (?, ?, ?, ?, ?, ?)
                ");

                $transaction->execute([
                    $creatorUserId,
                    'grant',
                    50,
                    50 + (int)(
                        $creatorUser['credits'] ?? 0
                    ),
                    'login_' . bin2hex(random_bytes(8)),
                    'Creator AI welcome daily credits'
                ]);

            } catch (Throwable $e) {

                error_log(
                    'Creator daily transaction error: ' .
                    $e->getMessage()
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Notification
            |--------------------------------------------------------------------------
            */

            try {

                $notification = $pdo->prepare("
                    INSERT INTO creator_notifications
                    (
                        user_id,
                        type,
                        title,
                        message
                    )
                    VALUES (?, ?, ?, ?)
                ");

                $notification->execute([
                    $creatorUserId,
                    'success',
                    'Welcome to Creator AI',
                    'Your 50 daily Creator AI credits are ready to use.'
                ]);

            } catch (Throwable $e) {

                error_log(
                    'Creator welcome notification error: ' .
                    $e->getMessage()
                );
            }
        }

    } else {

        /*
        |--------------------------------------------------------------------------
        | NEW CREATOR AI ACCOUNT
        |--------------------------------------------------------------------------
        |
        | Google login ke time hi account create.
        | Starting balance:
        |
        | Purchased = 0
        | Daily = 50
        |
        */

        $nextReset = (new DateTimeImmutable())
            ->modify('+24 hours')
            ->format('Y-m-d H:i:s');

        $insertCreator = $pdo->prepare("
            INSERT INTO creator_users
            (
                google_id,
                name,
                email,
                avatar,
                credits,
                daily_credits,
                daily_credits_reset_at
            )
            VALUES
            (?, ?, ?, ?, 0, 50, ?)
        ");

        $insertCreator->execute([
            $googleId,
            $name,
            $email,
            $avatar,
            $nextReset
        ]);

        $creatorUserId = (int)$pdo->lastInsertId();

        /*
        |--------------------------------------------------------------------------
        | Welcome Credit Transaction
        |--------------------------------------------------------------------------
        */

        try {

            $transaction = $pdo->prepare("
                INSERT INTO creator_credit_transactions
                (
                    user_id,
                    type,
                    amount,
                    balance_after,
                    request_id,
                    description
                )
                VALUES (?, ?, ?, ?, ?, ?)
            ");

            $transaction->execute([
                $creatorUserId,
                'grant',
                50,
                50,
                'welcome_' . bin2hex(random_bytes(8)),
                'Welcome Creator AI daily credits'
            ]);

        } catch (Throwable $e) {

            error_log(
                'Creator welcome transaction error: ' .
                $e->getMessage()
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Welcome Notification
        |--------------------------------------------------------------------------
        */

        try {

            $notification = $pdo->prepare("
                INSERT INTO creator_notifications
                (
                    user_id,
                    type,
                    title,
                    message
                )
                VALUES (?, ?, ?, ?)
            ");

            $notification->execute([
                $creatorUserId,
                'success',
                'Welcome to Creator AI',
                'Your 50 daily Creator AI credits are ready to use.'
            ]);

        } catch (Throwable $e) {

            error_log(
                'Creator welcome notification error: ' .
                $e->getMessage()
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Commit Database Changes
    |--------------------------------------------------------------------------
    */

    $pdo->commit();

} catch (Throwable $e) {

    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    error_log(
        'Creator Google Login Database Error: ' .
        $e->getMessage()
    );

    http_response_code(500);

    exit(
        'Unable to create Creator AI account. ' .
        htmlspecialchars(
            $e->getMessage(),
            ENT_QUOTES,
            'UTF-8'
        )
    );
}

/*
|--------------------------------------------------------------------------
| Creator AI Session
|--------------------------------------------------------------------------
|
| IMPORTANT:
| user_id ab creator_users.id hoga.
|
*/

session_regenerate_id(true);

$_SESSION['user_id'] = $creatorUserId;

$_SESSION['user_name'] = $name;

$_SESSION['user_email'] = $email;

$_SESSION['user_avatar'] = $avatar;

/*
|--------------------------------------------------------------------------
| Optional Main User ID
|--------------------------------------------------------------------------
|
| Agar future mein main users table ki zarurat pade,
| ye separate session variable available rahega.
|
*/

$_SESSION['main_user_id'] = $mainUserId;

/*
|--------------------------------------------------------------------------
| Login Success
|--------------------------------------------------------------------------
*/

header(
    'Location: /creator-ai/'
);

exit;


/*
|--------------------------------------------------------------------------
| HTTP POST
|--------------------------------------------------------------------------
*/

function http_post(
    string $url,
    array $data
): string {

    $ch = curl_init($url);

    curl_setopt_array($ch, [

        CURLOPT_POST => true,

        CURLOPT_POSTFIELDS =>
            http_build_query($data),

        CURLOPT_RETURNTRANSFER => true,

        CURLOPT_HTTPHEADER => [
            'Content-Type: application/x-www-form-urlencoded'
        ],

        CURLOPT_TIMEOUT => 20,

    ]);

    $result = curl_exec($ch);

    if ($result === false) {

        $error = curl_error($ch);

        curl_close($ch);

        throw new RuntimeException(
            'OAuth request failed: ' . $error
        );
    }

    $httpCode = curl_getinfo(
        $ch,
        CURLINFO_HTTP_CODE
    );

    curl_close($ch);

    if (
        $httpCode < 200 ||
        $httpCode >= 300
    ) {

        throw new RuntimeException(
            'Google token request returned HTTP ' .
            $httpCode
        );
    }

    return $result;
}


/*
|--------------------------------------------------------------------------
| HTTP GET
|--------------------------------------------------------------------------
*/

function http_get(
    string $url,
    array $headers = []
): string {

    $ch = curl_init($url);

    curl_setopt_array($ch, [

        CURLOPT_RETURNTRANSFER => true,

        CURLOPT_HTTPHEADER => $headers,

        CURLOPT_TIMEOUT => 20,

    ]);

    $result = curl_exec($ch);

    if ($result === false) {

        $error = curl_error($ch);

        curl_close($ch);

        throw new RuntimeException(
            'Google userinfo request failed: ' .
            $error
        );
    }

    $httpCode = curl_getinfo(
        $ch,
        CURLINFO_HTTP_CODE
    );

    curl_close($ch);

    if (
        $httpCode < 200 ||
        $httpCode >= 300
    ) {

        throw new RuntimeException(
            'Google userinfo returned HTTP ' .
            $httpCode
        );
    }

    return $result;
}
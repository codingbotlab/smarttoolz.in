<?php
declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Creator AI — Main Configuration
|--------------------------------------------------------------------------
|
| IMPORTANT:
| This file must remain SERVER-SIDE ONLY.
|
| NEVER expose these values to frontend:
|
| - OPENAI_API_KEY
| - GOOGLE_CLIENT_SECRET
| - RAZORPAY_KEY_SECRET
| - RAZORPAY_WEBHOOK_SECRET
|
|--------------------------------------------------------------------------
*/


/* ================================================================
   SESSION
   ================================================================ */

if (session_status() !== PHP_SESSION_ACTIVE) {

    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => true,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);

    session_start();
}


/* ================================================================
   DATABASE
   ================================================================ */

const DB_HOST = 'localhost';

/*
 * Put your existing database values here.
 *
 * IMPORTANT:
 * Your old DB password was exposed in chat.
 * Rotate it and use the NEW password below.
 */

const DB_NAME = 'u969897784_assetsbox';
const DB_USER = 'u969897784_maya';
const DB_PASS = 'Mk@151089sr1964bcpr';
/* ================================================================
   GOOGLE OAUTH
   ================================================================ */


const GOOGLE_CLIENT_ID = '523738407628-o1c4t43j4vjriajnvojpt4cio4mktr01.apps.googleusercontent.com';
const GOOGLE_CLIENT_SECRET = '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';
const GOOGLE_REDIRECT_URI = 'https://smarttoolz.in/creator-ai/auth/google-callback.php';

/* ================================================================
   OPENAI
   ================================================================ */

/*
 * Put your NEW OpenAI API key here.
 *
 * NEVER put this key in:
 *
 * - JavaScript
 * - HTML
 * - AJAX response
 * - Browser localStorage
 * - Cookies
 */
/* ---------------- Gemini API ---------------- */

const GEMINI_API_KEY = 'AQ.Ab8RN6KKLXJNpvubaFJ3MODAOVBfMMfhB69AX5xYmmtGB5J8Uw';

const GEMINI_MODEL = 'Gemini 3.5 Flash Lite';

/* ================================================================
   CREATOR AI CREDIT PRICING
   ================================================================ */

/*
 * These are INTERNAL SmartToolz credits.
 *
 * They are NOT OpenAI tokens.
 *
 * Example:
 *
 * 1 credit = 1K input tokens
 * 6 credits = 1K output tokens
 *
 * You can change these later according to your pricing/margin.
 */

const CREATOR_AI_CREDITS_PER_1K_INPUT = 1;

const CREATOR_AI_CREDITS_PER_1K_OUTPUT = 6;


/* ================================================================
   FREE DAILY CREDITS
   ================================================================ */

const CREATOR_AI_DAILY_FREE_CREDITS = 50;


/* ================================================================
   RAZORPAY
   ================================================================ */

/*
 * Public Razorpay Key ID.
 *
 * This one MAY be sent to Razorpay Checkout in the browser.
 */

const RAZORPAY_KEY_ID =
    'YOUR_RAZORPAY_KEY_ID';


/*
 * PRIVATE Razorpay Secret.
 *
 * NEVER expose this to frontend.
 */

const RAZORPAY_KEY_SECRET =
    'YOUR_RAZORPAY_KEY_SECRET';


/*
 * PRIVATE Razorpay Webhook Secret.
 *
 * Must match the secret configured in Razorpay Dashboard.
 */

const RAZORPAY_WEBHOOK_SECRET =
    'YOUR_RAZORPAY_WEBHOOK_SECRET';


/* ================================================================
   DATABASE CONNECTION
   ================================================================ */

function db(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }


    $pdo = new PDO(

        'mysql:host=' .
        DB_HOST .
        ';dbname=' .
        DB_NAME .
        ';charset=utf8mb4',

        DB_USER,

        DB_PASS,

        [

            PDO::ATTR_ERRMODE =>
                PDO::ERRMODE_EXCEPTION,

            PDO::ATTR_DEFAULT_FETCH_MODE =>
                PDO::FETCH_ASSOC,

            PDO::ATTR_EMULATE_PREPARES =>
                false,

        ]

    );


    return $pdo;
}


/*
 * Alias used by Creator AI tools.
 */

function creatorDb(): PDO
{
    return db();
}


/* ================================================================
   AUTHENTICATION
   ================================================================ */

function requireLogin(): void
{
    if (empty($_SESSION['user_id'])) {

        header(
            'Location: /creator-ai/login'
        );

        exit;
    }
}


/* ================================================================
   CURRENT USER
   ================================================================ */

function getCreatorUser(
    int $userId
): ?array {

    $stmt = db()->prepare(

        'SELECT
            id,
            google_id,
            email,
            name,
            avatar,
            credits,
            daily_credits,
            daily_credits_reset_at,
            plan

         FROM creator_users

         WHERE id = ?

         LIMIT 1'

    );


    $stmt->execute([
        $userId
    ]);


    $user =
        $stmt->fetch();


    return $user ?: null;
}


/* ================================================================
   PURCHASED CREDITS
   ================================================================ */

function getCreatorCredits(
    int $userId
): int {

    $stmt = db()->prepare(

        'SELECT
            credits

         FROM creator_users

         WHERE id = ?

         LIMIT 1'

    );


    $stmt->execute([
        $userId
    ]);


    return (int)(
        $stmt->fetchColumn() ?: 0
    );
}


/* ================================================================
   DAILY CREDITS
   ================================================================ */

function getCreatorDailyCredits(
    int $userId
): int {

    $stmt = db()->prepare(

        'SELECT
            daily_credits

         FROM creator_users

         WHERE id = ?

         LIMIT 1'

    );


    $stmt->execute([
        $userId
    ]);


    return (int)(
        $stmt->fetchColumn() ?: 0
    );
}


/* ================================================================
   TOTAL CREDITS
   ================================================================ */

function getTotalCreatorCredits(
    int $userId
): int {

    $stmt = db()->prepare(

        'SELECT

            COALESCE(credits, 0)
            +
            COALESCE(daily_credits, 0)

         FROM creator_users

         WHERE id = ?

         LIMIT 1'

    );


    $stmt->execute([
        $userId
    ]);


    return (int)(
        $stmt->fetchColumn() ?: 0
    );
}


/* ================================================================
   INITIALIZE / RESET DAILY CREDITS
   ================================================================ */

function ensureDailyCreatorCredits(
    int $userId
): int {

    $pdo = db();

    try {

        $pdo->beginTransaction();


        /*
         * Lock user row.
         */

        $stmt = $pdo->prepare(

            'SELECT
                daily_credits,
                daily_credits_reset_at

             FROM creator_users

             WHERE id = ?

             LIMIT 1

             FOR UPDATE'

        );


        $stmt->execute([
            $userId
        ]);


        $user =
            $stmt->fetch();


        if (!$user) {

            $pdo->rollBack();

            return 0;
        }


        $dailyCredits =
            (int)(
                $user['daily_credits'] ?? 0
            );


        $resetAt =
            $user['daily_credits_reset_at']
            ?? null;


        $now =
            new DateTimeImmutable(
                'now'
            );


        $needsReset = false;


        /*
         * First-time user.
         */

        if (
            empty($resetAt)
        ) {

            $needsReset = true;

        } else {

            try {

                $resetDate =
                    new DateTimeImmutable(
                        (string)$resetAt
                    );


                if (
                    $resetDate <= $now
                ) {

                    $needsReset = true;
                }

            } catch (Throwable $e) {

                $needsReset = true;
            }
        }


        if ($needsReset) {

            $dailyCredits =
                CREATOR_AI_DAILY_FREE_CREDITS;


            $nextReset =
                $now
                ->modify('+24 hours')
                ->format(
                    'Y-m-d H:i:s'
                );


            $update =
                $pdo->prepare(

                    'UPDATE creator_users

                     SET
                        daily_credits = ?,
                        daily_credits_reset_at = ?

                     WHERE id = ?'

                );


            $update->execute([

                $dailyCredits,

                $nextReset,

                $userId

            ]);
        }


        $pdo->commit();


        return $dailyCredits;


    } catch (Throwable $e) {

        if (
            $pdo->inTransaction()
        ) {

            $pdo->rollBack();
        }


        throw $e;
    }
}


/* ================================================================
   CONSUME CREATOR CREDITS
   ================================================================
|
| Priority:
|
| 1. Daily free credits
| 2. Purchased credits
|
| This is the function AI tools should use.
|
================================================================ */

function consumeCreatorCredits(
    int $userId,
    int $credits,
    string $requestId,
    string $description = 'AI usage'
): array|false {

    if ($credits < 1) {
        $credits = 1;
    }


    $pdo = db();


    try {

        $pdo->beginTransaction();


        /*
         * Lock the account.
         */

        $stmt =
            $pdo->prepare(

                'SELECT
                    credits,
                    daily_credits,
                    daily_credits_reset_at

                 FROM creator_users

                 WHERE id = ?

                 LIMIT 1

                 FOR UPDATE'

            );


        $stmt->execute([
            $userId
        ]);


        $user =
            $stmt->fetch();


        if (!$user) {

            $pdo->rollBack();

            return false;
        }


        $purchased =
            max(
                0,
                (int)(
                    $user['credits'] ?? 0
                )
            );


        $daily =
            max(
                0,
                (int)(
                    $user['daily_credits'] ?? 0
                )
            );


        /*
         * Check/reset daily credits.
         */

        $resetAt =
            $user['daily_credits_reset_at']
            ?? null;


        $now =
            new DateTimeImmutable(
                'now'
            );


        $dailyExpired = false;


        if (
            empty($resetAt)
        ) {

            $dailyExpired = true;

        } else {

            try {

                $resetDate =
                    new DateTimeImmutable(
                        (string)$resetAt
                    );


                if (
                    $resetDate <= $now
                ) {

                    $dailyExpired = true;
                }

            } catch (Throwable $e) {

                $dailyExpired = true;
            }
        }


        if ($dailyExpired) {

            $daily =
                CREATOR_AI_DAILY_FREE_CREDITS;


            $resetAt =
                $now
                ->modify('+24 hours')
                ->format(
                    'Y-m-d H:i:s'
                );
        }


        $total =
            $daily +
            $purchased;


        /*
         * Not enough credits.
         */

        if (
            $total < $credits
        ) {

            /*
             * Save daily reset if required,
             * but do not charge anything.
             */

            if ($dailyExpired) {

                $update =
                    $pdo->prepare(

                        'UPDATE creator_users

                         SET
                            daily_credits = ?,
                            daily_credits_reset_at = ?

                         WHERE id = ?'

                    );


                $update->execute([

                    $daily,

                    $resetAt,

                    $userId

                ]);
            }


            $pdo->commit();


            return false;
        }


        /*
         * DAILY CREDITS FIRST
         */

        $dailyUsed =
            min(
                $daily,
                $credits
            );


        $remaining =
            $credits -
            $dailyUsed;


        /*
         * PURCHASED CREDITS SECOND
         */

        $purchasedUsed =
            min(
                $purchased,
                $remaining
            );


        $newDaily =
            $daily -
            $dailyUsed;


        $newPurchased =
            $purchased -
            $purchasedUsed;


        /*
         * Update account.
         */

        $update =
            $pdo->prepare(

                'UPDATE creator_users

                 SET
                    credits = ?,
                    daily_credits = ?,
                    daily_credits_reset_at = ?

                 WHERE id = ?'

            );


        $update->execute([

            $newPurchased,

            $newDaily,

            $resetAt,

            $userId

        ]);


        /*
         * Ledger entry.
         */

        $tx =
            $pdo->prepare(

                'INSERT INTO creator_credit_transactions

                (
                    user_id,
                    type,
                    amount,
                    balance_after,
                    request_id,
                    description
                )

                VALUES
                (
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?
                )'

            );


        $tx->execute([

            $userId,

            'usage',

            -$credits,

            $newDaily +
            $newPurchased,

            $requestId,

            $description

        ]);


        $pdo->commit();


        return [

            'charged' =>
                $credits,

            'daily_used' =>
                $dailyUsed,

            'purchased_used' =>
                $purchasedUsed,

            'daily_remaining' =>
                $newDaily,

            'purchased_remaining' =>
                $newPurchased,

            'total_remaining' =>
                $newDaily +
                $newPurchased

        ];


    } catch (Throwable $e) {

        if (
            $pdo->inTransaction()
        ) {

            $pdo->rollBack();
        }


        throw $e;
    }
}


/* ================================================================
   OLD COMPATIBILITY FUNCTION
   ================================================================
|
| Existing tools calling chargeCreatorCredits()
| will continue to work.
|
================================================================ */

function chargeCreatorCredits(
    int $userId,
    int $credits,
    string $requestId,
    string $description = 'AI usage'
): int|false {

    $result =
        consumeCreatorCredits(
            $userId,
            $credits,
            $requestId,
            $description
        );


    if (
        $result === false
    ) {

        return false;
    }


    return (int)(
        $result['total_remaining']
    );
}


/* ================================================================
   CREDIT NOTIFICATION
   ================================================================ */

function createCreatorNotification(
    int $userId,
    string $type,
    string $title,
    string $message
): bool {

    try {

        $stmt =
            db()->prepare(

                'INSERT INTO creator_notifications

                (
                    user_id,
                    type,
                    title,
                    message
                )

                VALUES
                (
                    ?,
                    ?,
                    ?,
                    ?
                )'

            );


        return $stmt->execute([

            $userId,

            $type,

            $title,

            $message

        ]);

    } catch (Throwable $e) {

        error_log(
            'Creator notification error: ' .
            $e->getMessage()
        );

        return false;
    }
}


/* ================================================================
   CREATE CREDIT TRANSACTION
   ================================================================ */

function createCreatorCreditTransaction(
    int $userId,
    string $type,
    int $amount,
    int $balanceAfter,
    string $requestId,
    string $description
): bool {

    try {

        $stmt =
            db()->prepare(

                'INSERT INTO creator_credit_transactions

                (
                    user_id,
                    type,
                    amount,
                    balance_after,
                    request_id,
                    description
                )

                VALUES
                (
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?
                )'

            );


        return $stmt->execute([

            $userId,

            $type,

            $amount,

            $balanceAfter,

            $requestId,

            $description

        ]);

    } catch (Throwable $e) {

        error_log(
            'Creator credit transaction error: ' .
            $e->getMessage()
        );

        return false;
    }
}


/* ================================================================
   SECURITY HELPER
   ================================================================ */

function creatorH(
    mixed $value
): string {

    return htmlspecialchars(

        (string)$value,

        ENT_QUOTES,

        'UTF-8'

    );
}

/*
|--------------------------------------------------------------------------
| Creator AI Credit Charge
|--------------------------------------------------------------------------
| Priority:
|
| 1. Daily credits
| 2. Purchased credits
|
| One successful AI generation = 1 Creator Credit.
|
| Returns:
|   integer = new total balance
|   false   = insufficient credits
|--------------------------------------------------------------------------
*/

function chargeCreatorAiCredit(
    int $userId,
    string $requestId,
    string $description = 'AI Writer generation'
): int|false {

    $pdo = db();

    try {

        $pdo->beginTransaction();

        /*
        |--------------------------------------------------------------------------
        | Lock user row
        |--------------------------------------------------------------------------
        */

        $stmt = $pdo->prepare("
            SELECT
                credits,
                daily_credits
            FROM creator_users
            WHERE id = ?
            LIMIT 1
            FOR UPDATE
        ");

        $stmt->execute([
            $userId
        ]);

        $user = $stmt->fetch();

        if (!$user) {

            $pdo->rollBack();

            return false;
        }

        $daily = max(
            0,
            (int)($user['daily_credits'] ?? 0)
        );

        $purchased = max(
            0,
            (int)($user['credits'] ?? 0)
        );

        /*
        |--------------------------------------------------------------------------
        | Daily credits first
        |--------------------------------------------------------------------------
        */

        if ($daily > 0) {

            $daily--;

            $update = $pdo->prepare("
                UPDATE creator_users
                SET daily_credits = ?
                WHERE id = ?
            ");

            $update->execute([
                $daily,
                $userId
            ]);

            $newBalance =
                $daily +
                $purchased;

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
                $userId,
                'usage',
                -1,
                $newBalance,
                $requestId,
                $description . ' — daily credit'
            ]);

            $pdo->commit();

            return $newBalance;
        }

        /*
        |--------------------------------------------------------------------------
        | Purchased credits
        |--------------------------------------------------------------------------
        */

        if ($purchased > 0) {

            $purchased--;

            $update = $pdo->prepare("
                UPDATE creator_users
                SET credits = ?
                WHERE id = ?
            ");

            $update->execute([
                $purchased,
                $userId
            ]);

            $newBalance =
                $daily +
                $purchased;

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
                $userId,
                'usage',
                -1,
                $newBalance,
                $requestId,
                $description . ' — purchased credit'
            ]);

            $pdo->commit();

            return $newBalance;
        }

        /*
        |--------------------------------------------------------------------------
        | No credits
        |--------------------------------------------------------------------------
        */

        $pdo->rollBack();

        return false;

    } catch (Throwable $e) {

        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        error_log(
            'Creator AI Credit Charge Error: ' .
            $e->getMessage()
        );

        throw $e;
    }
}
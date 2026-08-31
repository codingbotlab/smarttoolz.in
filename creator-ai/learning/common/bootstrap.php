<?php
declare(strict_types=1);
error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
/*
|--------------------------------------------------------------------------
| SmartToolz Learning Hub - Common Bootstrap
|--------------------------------------------------------------------------
| Uses the existing Creator AI auth/config.php
| Existing lesson relation:
|
| learning_courses
|       ↓
| learning_chapters
|       ↓
| learning_lessons.chapter_id
|--------------------------------------------------------------------------
*/

require_once dirname(__DIR__, 2) . '/auth/config.php';


/*
|--------------------------------------------------------------------------
| Database
|--------------------------------------------------------------------------
*/

function learning_pdo(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $host = defined('DB_HOST')
        ? DB_HOST
        : 'localhost';

    $name = defined('DB_NAME')
        ? DB_NAME
        : (
            defined('DB_DATABASE')
                ? DB_DATABASE
                : ''
        );

    $user = defined('DB_USER')
        ? DB_USER
        : (
            defined('DB_USERNAME')
                ? DB_USERNAME
                : ''
        );

    $pass = defined('DB_PASS')
        ? DB_PASS
        : (
            defined('DB_PASSWORD')
                ? DB_PASSWORD
                : ''
        );


    if ($name === '' || $user === '') {

        throw new RuntimeException(
            'Database configuration is missing in auth/config.php.'
        );
    }


    $dsn =
        "mysql:host={$host};dbname={$name};charset=utf8mb4";


    $pdo = new PDO(
        $dsn,
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );


    return $pdo;
}


/*
|--------------------------------------------------------------------------
| Login
|--------------------------------------------------------------------------
*/

function learning_user_id(): int
{
    return (int)(
        $_SESSION['user_id'] ?? 0
    );
}


function learning_require_login(): void
{
    if (function_exists('requireLogin')) {

        requireLogin();

        return;
    }


    if (learning_user_id() < 1) {

        header(
            'Location: /creator-ai/auth/login'
        );

        exit;
    }
}


/*
|--------------------------------------------------------------------------
| Current User
|--------------------------------------------------------------------------
*/

function learning_current_user(): array
{
    $userId = learning_user_id();


    /*
     * Existing Creator AI helper
     */

    if (function_exists('getCreatorUser')) {

        $user = getCreatorUser($userId);

        if (is_array($user)) {

            return $user;
        }
    }


    /*
     * Fallback
     */

    try {

        $pdo = learning_pdo();


        /*
         * Try users table first
         */

        try {

            $stmt = $pdo->prepare(
                "SELECT *
                 FROM users
                 WHERE id = ?
                 LIMIT 1"
            );

            $stmt->execute([
                $userId
            ]);

            $user = $stmt->fetch();

            if ($user) {

                return $user;
            }

        } catch (Throwable $e) {
            // Ignore and try creator_users.
        }


        /*
         * Try creator_users
         */

        try {

            $stmt = $pdo->prepare(
                "SELECT *
                 FROM creator_users
                 WHERE id = ?
                 LIMIT 1"
            );

            $stmt->execute([
                $userId
            ]);

            $user = $stmt->fetch();

            if ($user) {

                return $user;
            }

        } catch (Throwable $e) {
            // Ignore fallback failure.
        }

    } catch (Throwable $e) {

        error_log(
            'Learning current user error: ' .
            $e->getMessage()
        );
    }


    return [
        'id'     => $userId,
        'name'   => (string)(
            $_SESSION['user_name'] ?? 'Creator'
        ),
        'avatar' => (string)(
            $_SESSION['user_avatar'] ?? ''
        ),
    ];
}


/*
|--------------------------------------------------------------------------
| HTML Escape
|--------------------------------------------------------------------------
*/

function eh(mixed $value): string
{
    return htmlspecialchars(
        (string)$value,
        ENT_QUOTES,
        'UTF-8'
    );
}


/*
|--------------------------------------------------------------------------
| Get Course
|--------------------------------------------------------------------------
*/

function learning_course(
    string $slug
): ?array
{
    $stmt = learning_pdo()->prepare(
        "SELECT *
         FROM learning_courses
         WHERE slug = ?
           AND is_published = 1
         LIMIT 1"
    );


    $stmt->execute([
        $slug
    ]);


    $course = $stmt->fetch();


    return $course ?: null;
}


/*
|--------------------------------------------------------------------------
| Get Chapters
|--------------------------------------------------------------------------
|
| IMPORTANT:
| Existing database uses learning_chapters.
| Do NOT use learning_modules here.
|--------------------------------------------------------------------------
*/

function learning_chapters(
    int $courseId
): array
{
    $stmt = learning_pdo()->prepare(
        "SELECT *
         FROM learning_chapters
         WHERE course_id = ?
         ORDER BY sort_order ASC, id ASC"
    );


    $stmt->execute([
        $courseId
    ]);


    return $stmt->fetchAll();
}


/*
|--------------------------------------------------------------------------
| Course Stats
|--------------------------------------------------------------------------
|
| Correct relation:
|
| courses.id
|    ↓
| chapters.course_id
|    ↓
| lessons.chapter_id
|
|--------------------------------------------------------------------------
*/

function learning_course_stats(
    int $courseId,
    int $userId
): array
{
    $pdo = learning_pdo();


    /*
     * Total lessons
     * Completed lessons
     */

    $stmt = $pdo->prepare(
        "
        SELECT

            COUNT(l.id) AS total_lessons,

            COUNT(
                CASE
                    WHEN lp.completed = 1
                    THEN l.id
                END
            ) AS completed_lessons

        FROM learning_chapters ch

        LEFT JOIN learning_lessons l
            ON l.chapter_id = ch.id

            AND l.is_published = 1

        LEFT JOIN learning_lesson_progress lp
            ON lp.lesson_id = l.id

            AND lp.user_id = ?

        WHERE ch.course_id = ?
        "
    );


    $stmt->execute([
        $userId,
        $courseId
    ]);


    $row = $stmt->fetch();


    $totalLessons = (int)(
        $row['total_lessons'] ?? 0
    );


    $completedLessons = (int)(
        $row['completed_lessons'] ?? 0
    );


    /*
     * Progress %
     */

    $percent = 0;


    if ($totalLessons > 0) {

        $percent = (int)round(
            (
                $completedLessons
                / $totalLessons
            ) * 100
        );
    }


    /*
     * Quiz XP
     *
     * Correct column:
     * xp_earned
     */

    $stmt = $pdo->prepare(
        "
        SELECT
            COALESCE(
                SUM(a.xp_earned),
                0
            )

        FROM learning_quiz_attempts a

        INNER JOIN learning_quizzes q
            ON q.id = a.quiz_id

        WHERE a.user_id = ?

          AND q.course_id = ?
        "
    );


    $stmt->execute([
        $userId,
        $courseId
    ]);


    $quizXp = (int)(
        $stmt->fetchColumn() ?? 0
    );


    /*
     * Return
     */

    return [

        'total' =>
            $totalLessons,

        'done' =>
            $completedLessons,

        'percent' =>
            $percent,

        'xp' =>
            $quizXp,
    ];
}


/*
|--------------------------------------------------------------------------
| All Published Courses + User Progress
|--------------------------------------------------------------------------
*/

function learning_courses_with_progress(
    int $userId
): array
{
    $stmt = learning_pdo()->prepare(
        "
        SELECT

            c.*,

            COUNT(DISTINCT l.id)
                AS lessons,

            COUNT(
                DISTINCT CASE
                    WHEN lp.completed = 1
                    THEN l.id
                END
            ) AS done,

            CASE

                WHEN COUNT(DISTINCT l.id) = 0

                THEN 0

                ELSE ROUND(

                    COUNT(
                        DISTINCT CASE
                            WHEN lp.completed = 1
                            THEN l.id
                        END
                    ) * 100

                    / COUNT(DISTINCT l.id)

                )

            END AS percent

        FROM learning_courses c

        LEFT JOIN learning_chapters ch
            ON ch.course_id = c.id

        LEFT JOIN learning_lessons l
            ON l.chapter_id = ch.id

            AND l.is_published = 1

        LEFT JOIN learning_lesson_progress lp
            ON lp.lesson_id = l.id

            AND lp.user_id = ?

        WHERE c.is_published = 1

        GROUP BY c.id

        ORDER BY
            c.sort_order ASC,
            c.id ASC
        "
    );


    $stmt->execute([
        $userId
    ]);


    return $stmt->fetchAll();
}


/*
|--------------------------------------------------------------------------
| Continue Learning
|--------------------------------------------------------------------------
*/

function learning_continue_lesson(
    int $userId
): ?array
{
    $pdo = learning_pdo();


    /*
     * First priority:
     * user already started a lesson
     */

    $stmt = $pdo->prepare(
        "
        SELECT

            l.id AS lesson_id,

            l.title AS lesson_title,

            ch.title AS chapter_title,

            c.id AS course_id,

            c.slug,

            c.title AS course_title,

            COUNT(all_l.id)
                AS total,

            COUNT(
                CASE
                    WHEN all_lp.completed = 1
                    THEN all_l.id
                END
            ) AS done

        FROM learning_lessons l

        INNER JOIN learning_chapters ch
            ON ch.id = l.chapter_id

        INNER JOIN learning_courses c
            ON c.id = ch.course_id

        LEFT JOIN learning_lessons all_l

            ON all_l.chapter_id IN (

                SELECT id

                FROM learning_chapters

                WHERE course_id = c.id
            )

            AND all_l.is_published = 1

        LEFT JOIN learning_lesson_progress all_lp

            ON all_lp.lesson_id = all_l.id

            AND all_lp.user_id = ?

        INNER JOIN learning_lesson_progress current_lp

            ON current_lp.lesson_id = l.id

            AND current_lp.user_id = ?

        WHERE c.is_published = 1

          AND current_lp.completed = 0

        GROUP BY

            l.id,
            l.title,
            ch.title,
            c.id,
            c.slug,
            c.title

        ORDER BY

            current_lp.last_opened_at DESC,

            l.id DESC

        LIMIT 1
        "
    );


    $stmt->execute([
        $userId,
        $userId
    ]);


    $row = $stmt->fetch();


    if ($row) {

        $row['percent'] =
            ((int)$row['total'] > 0)

            ? (int)round(
                (
                    (int)$row['done']
                    / (int)$row['total']
                ) * 100
            )

            : 0;


        return $row;
    }


    /*
     * No started lesson:
     * return first uncompleted lesson.
     */

    $stmt = $pdo->prepare(
        "
        SELECT

            l.id AS lesson_id,

            l.title AS lesson_title,

            ch.title AS chapter_title,

            c.id AS course_id,

            c.slug,

            c.title AS course_title

        FROM learning_lessons l

        INNER JOIN learning_chapters ch
            ON ch.id = l.chapter_id

        INNER JOIN learning_courses c
            ON c.id = ch.course_id

        WHERE c.is_published = 1

          AND l.is_published = 1

          AND NOT EXISTS (

              SELECT 1

              FROM learning_lesson_progress lp

              WHERE lp.lesson_id = l.id

                AND lp.user_id = ?
          )

        ORDER BY

            c.sort_order ASC,

            ch.sort_order ASC,

            l.sort_order ASC

        LIMIT 1
        "
    );


    $stmt->execute([
        $userId
    ]);


    $row = $stmt->fetch();


    if (!$row) {

        return null;
    }


    $row['total'] = 0;
    $row['done'] = 0;
    $row['percent'] = 0;


    return $row;
}


/*
|--------------------------------------------------------------------------
| Recent Activity
|--------------------------------------------------------------------------
*/

function learning_recent_activity(
    int $userId,
    int $limit = 8
): array
{
    $limit = max(
        1,
        min(20, $limit)
    );


    $pdo = learning_pdo();


    $stmt = $pdo->prepare(
        "
        SELECT

            a.id,

            a.activity_type,

            a.course_id,

            a.lesson_id,

            a.created_at,

            c.title AS course_title,

            CASE

                WHEN TIMESTAMPDIFF(
                    MINUTE,
                    a.created_at,
                    NOW()
                ) < 1

                THEN 'Just now'

                WHEN TIMESTAMPDIFF(
                    MINUTE,
                    a.created_at,
                    NOW()
                ) < 60

                THEN CONCAT(
                    TIMESTAMPDIFF(
                        MINUTE,
                        a.created_at,
                        NOW()
                    ),
                    ' min ago'
                )

                WHEN TIMESTAMPDIFF(
                    HOUR,
                    a.created_at,
                    NOW()
                ) < 24

                THEN CONCAT(
                    TIMESTAMPDIFF(
                        HOUR,
                        a.created_at,
                        NOW()
                    ),
                    ' hr ago'
                )

                WHEN TIMESTAMPDIFF(
                    DAY,
                    a.created_at,
                    NOW()
                ) < 30

                THEN CONCAT(
                    TIMESTAMPDIFF(
                        DAY,
                        a.created_at,
                        NOW()
                    ),
                    ' days ago'
                )

                ELSE DATE_FORMAT(
                    a.created_at,
                    '%d %b %Y'
                )

            END AS time_label

        FROM learning_activity a

        LEFT JOIN learning_courses c
            ON c.id = a.course_id

        WHERE a.user_id = ?

        ORDER BY
            a.created_at DESC

        LIMIT {$limit}
        "
    );


    $stmt->execute([
        $userId
    ]);


    return $stmt->fetchAll();
}


/*
|--------------------------------------------------------------------------
| Log Activity
|--------------------------------------------------------------------------
*/

function learning_log_activity(
    int $userId,
    string $type,
    ?int $courseId = null,
    ?int $lessonId = null,
    ?array $metadata = null
): void
{
    try {

        $stmt = learning_pdo()->prepare(
            "
            INSERT INTO learning_activity
            (
                user_id,
                activity_type,
                course_id,
                lesson_id,
                metadata
            )

            VALUES
            (
                ?,
                ?,
                ?,
                ?,
                ?
            )
            "
        );


        $stmt->execute([
            $userId,
            $type,
            $courseId,
            $lessonId,
            $metadata !== null
                ? json_encode(
                    $metadata,
                    JSON_UNESCAPED_UNICODE |
                    JSON_UNESCAPED_SLASHES
                )
                : null
        ]);

    } catch (Throwable $e) {

        error_log(
            'Learning activity error: ' .
            $e->getMessage()
        );
    }
}


/*
|--------------------------------------------------------------------------
| Get Course Lesson Count
|--------------------------------------------------------------------------
*/

function learning_course_lesson_count(
    int $courseId
): int
{
    $stmt = learning_pdo()->prepare(
        "
        SELECT COUNT(l.id)

        FROM learning_lessons l

        INNER JOIN learning_chapters ch
            ON ch.id = l.chapter_id

        WHERE ch.course_id = ?

          AND l.is_published = 1
        "
    );


    $stmt->execute([
        $courseId
    ]);


    return (int)(
        $stmt->fetchColumn() ?? 0
    );
}


/*
|--------------------------------------------------------------------------
| Get Lesson
|--------------------------------------------------------------------------
*/

function learning_lesson(
    int $lessonId
): ?array
{
    $stmt = learning_pdo()->prepare(
        "
        SELECT

            l.*,

            ch.title AS chapter_title,

            ch.id AS chapter_id,

            ch.course_id,

            c.title AS course_title,

            c.slug AS course_slug

        FROM learning_lessons l

        INNER JOIN learning_chapters ch
            ON ch.id = l.chapter_id

        INNER JOIN learning_courses c
            ON c.id = ch.course_id

        WHERE l.id = ?

          AND l.is_published = 1

        LIMIT 1
        "
    );


    $stmt->execute([
        $lessonId
    ]);


    $row = $stmt->fetch();


    return $row ?: null;
}


/*
|--------------------------------------------------------------------------
| Mark Lesson Opened
|--------------------------------------------------------------------------
*/

function learning_mark_opened(
    int $userId,
    int $lessonId
): void
{
    $stmt = learning_pdo()->prepare(
        "
        INSERT INTO learning_lesson_progress
        (
            user_id,
            lesson_id,
            started,
            last_opened_at,
            first_opened_at
        )

        VALUES
        (
            ?,
            ?,
            1,
            NOW(),
            NOW()
        )

        ON DUPLICATE KEY UPDATE

            started = 1,

            last_opened_at = NOW()
        "
    );


    $stmt->execute([
        $userId,
        $lessonId
    ]);
}


/*
|--------------------------------------------------------------------------
| JSON Response
|--------------------------------------------------------------------------
*/

function learning_json(
    array $data,
    int $status = 200
): never
{
    http_response_code($status);

    header(
        'Content-Type: application/json; charset=utf-8'
    );


    echo json_encode(
        $data,
        JSON_UNESCAPED_UNICODE |
        JSON_UNESCAPED_SLASHES
    );


    exit;
}
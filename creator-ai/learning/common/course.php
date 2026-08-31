<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

learning_require_login();

$userId = learning_user_id();

$slug = defined('LEARNING_LANGUAGE')
    ? LEARNING_LANGUAGE
    : 'html';

$course = learning_course($slug);

if (!$course) {
    http_response_code(404);
    exit('Course not found.');
}

$pdo = learning_pdo();

$user = learning_current_user();

/*
|--------------------------------------------------------------------------
| Chapters
|--------------------------------------------------------------------------
| IMPORTANT:
| Existing DB structure is:
|
| learning_courses
|       ↓
| learning_chapters
|       ↓
| learning_lessons.chapter_id
|--------------------------------------------------------------------------
*/

$chapters = learning_chapters(
    (int)$course['id']
);


/*
|--------------------------------------------------------------------------
| Course Stats
|--------------------------------------------------------------------------
*/

$stats = learning_course_stats(
    (int)$course['id'],
    $userId
);


/*
|--------------------------------------------------------------------------
| Next Lesson
|--------------------------------------------------------------------------
*/

$nextLesson = null;

$stmt = $pdo->prepare(
    "
    SELECT

        l.id,

        l.title,

        l.slug,

        l.duration_minutes,

        ch.id AS chapter_id,

        ch.title AS chapter_title

    FROM learning_lessons l

    INNER JOIN learning_chapters ch
        ON ch.id = l.chapter_id

    LEFT JOIN learning_lesson_progress lp
        ON lp.lesson_id = l.id

        AND lp.user_id = ?

    WHERE ch.course_id = ?

      AND l.is_published = 1

      AND COALESCE(
          lp.completed,
          0
      ) = 0

    ORDER BY

        ch.sort_order ASC,

        l.sort_order ASC,

        l.id ASC

    LIMIT 1
    "
);

$stmt->execute([
    $userId,
    (int)$course['id']
]);

$nextLesson = $stmt->fetch();


/*
|--------------------------------------------------------------------------
| Course Lesson Totals
|--------------------------------------------------------------------------
*/

$totalLessons = learning_course_lesson_count(
    (int)$course['id']
);


/*
|--------------------------------------------------------------------------
| User Name
|--------------------------------------------------------------------------
*/

$name = trim(
    (string)(
        $user['name']
        ?? $_SESSION['user_name']
        ?? 'Creator'
    )
);

if ($name === '') {
    $name = 'Creator';
}


$initial = strtoupper(
    substr($name, 0, 1)
);


/*
|--------------------------------------------------------------------------
| HTML Escape
|--------------------------------------------------------------------------
*/

function course_e(mixed $value): string
{
    return htmlspecialchars(
        (string)$value,
        ENT_QUOTES,
        'UTF-8'
    );
}

?>

<!doctype html>

<html lang="en">

<head>

<meta charset="utf-8">

<meta
    name="viewport"
    content="width=device-width,initial-scale=1"
>

<title>
    <?= course_e($course['title']) ?>
    — SmartToolz
</title>

<meta
    name="description"
    content="<?= course_e($course['description']) ?>"
>


<style>

:root{

    --bg:#07080d;

    --panel:#10151e;

    --panel2:#141b27;

    --line:rgba(255,255,255,.08);

    --text:#f5f7fb;

    --muted:#8e98aa;

    --muted2:#687286;

    --purple:#8b5cf6;

    --cyan:#22d3ee;

    --green:#34d399;

}


*{
    box-sizing:border-box;
}


html{
    scroll-behavior:smooth;
}


body{

    margin:0;

    min-height:100vh;

    background:

        radial-gradient(
            circle at 75% -10%,
            rgba(139,92,246,.18),
            transparent 30%
        ),

        linear-gradient(
            180deg,
            #07080d,
            #090c12
        );

    color:var(--text);

    font-family:
        Inter,
        Arial,
        sans-serif;

}


a{
    color:inherit;
    text-decoration:none;
}


.wrapper{

    width:min(
        1180px,
        calc(100% - 30px)
    );

    margin:auto;

    padding:
        28px
        0
        70px;

}


/*
|--------------------------------------------------------------------------
| Top
|--------------------------------------------------------------------------
*/

.top{

    display:flex;

    align-items:center;

    justify-content:space-between;

    gap:15px;

    margin-bottom:18px;

}


.back{

    color:#a78bfa;

    font-size:9px;

    font-weight:700;

}


.user{

    color:#7d8799;

    font-size:9px;

}


/*
|--------------------------------------------------------------------------
| Hero
|--------------------------------------------------------------------------
*/

.hero{

    position:relative;

    overflow:hidden;

    padding:30px;

    border:
        1px solid var(--line);

    border-radius:20px;

    background:

        radial-gradient(
            circle at 85% 0%,
            rgba(34,211,238,.08),
            transparent 30%
        ),

        radial-gradient(
            circle at 60% 0%,
            rgba(139,92,246,.14),
            transparent 38%
        ),

        linear-gradient(
            135deg,
            #111824,
            #0d121a
        );

    box-shadow:
        0 30px 90px
        rgba(0,0,0,.40);

}


.hero-grid{

    display:grid;

    grid-template-columns:
        minmax(0,1fr)
        285px;

    gap:22px;

    align-items:center;

}


.eyebrow{

    color:#c4b5fd;

    font-size:8px;

    font-weight:900;

    letter-spacing:1.5px;

}


.hero h1{

    margin:
        10px
        0
        8px;

    font-size:32px;

    line-height:1.15;

    letter-spacing:-1.2px;

}


.hero p{

    max-width:700px;

    margin:0;

    color:var(--muted);

    font-size:10px;

    line-height:1.75;

}


.chips{

    display:flex;

    flex-wrap:wrap;

    gap:7px;

    margin-top:16px;

}


.chip{

    padding:
        6px
        8px;

    border:
        1px solid var(--line);

    border-radius:7px;

    color:#aeb7c5;

    background:#ffffff03;

    font-size:7px;

}


.actions{

    display:flex;

    flex-wrap:wrap;

    gap:7px;

    margin-top:18px;

}


.btn{

    display:inline-flex;

    align-items:center;

    gap:7px;

    padding:
        10px
        13px;

    border-radius:9px;

    border:
        1px solid
        rgba(255,255,255,.12);

    color:#c6ceda;

    background:#ffffff04;

    font-size:8px;

    font-weight:850;

}


.btn:hover{

    color:#fff;

    background:#ffffff08;

}


.btn.primary{

    border:0;

    color:#fff;

    background:

        linear-gradient(
            135deg,
            var(--purple),
            var(--cyan)
        );

}


/*
|--------------------------------------------------------------------------
| Progress
|--------------------------------------------------------------------------
*/

.progress-card{

    padding:18px;

    border:
        1px solid var(--line);

    border-radius:14px;

    background:
        rgba(7,9,14,.55);

}


.progress-label{

    color:var(--muted2);

    font-size:7px;

    font-weight:850;

}


.progress-number{

    margin-top:4px;

    font-size:29px;

    font-weight:900;

}


.progress-bar{

    height:7px;

    margin:12px 0 8px;

    overflow:hidden;

    border-radius:10px;

    background:#242c3a;

}


.progress-bar span{

    display:block;

    height:100%;

    width:
        <?= max(
            0,
            min(
                100,
                (int)$stats['percent']
            )
        ) ?>%;

    border-radius:inherit;

    background:

        linear-gradient(
            90deg,
            var(--purple),
            var(--cyan)
        );

}


.progress-meta{

    display:flex;

    justify-content:space-between;

    gap:10px;

    color:var(--muted2);

    font-size:7px;

}


.next{

    margin-top:15px;

    padding-top:13px;

    border-top:
        1px solid var(--line);

}


.next-label{

    display:block;

    color:var(--muted2);

    font-size:7px;

}


.next-title{

    display:block;

    margin-top:4px;

    color:#fff;

    font-size:9px;

    font-weight:850;

}


/*
|--------------------------------------------------------------------------
| Chapters
|--------------------------------------------------------------------------
*/

.section{

    margin-top:28px;

}


.section-head{

    margin-bottom:12px;

}


.section-head h2{

    margin:0;

    font-size:17px;

    font-weight:900;

}


.section-head p{

    margin:4px 0 0;

    color:var(--muted2);

    font-size:8px;

}


.chapter-list{

    display:grid;

    gap:9px;

}


.chapter{

    display:flex;

    align-items:center;

    gap:13px;

    padding:15px;

    border:
        1px solid var(--line);

    border-radius:13px;

    background:
        #ffffff02;

    transition:.2s;

}


.chapter:hover{

    border-color:
        rgba(167,139,250,.22);

    background:
        #ffffff04;

}


.chapter-number{

    width:36px;

    height:36px;

    display:grid;

    place-items:center;

    flex:none;

    border-radius:10px;

    color:#c6ceda;

    background:#ffffff07;

    font-size:9px;

    font-weight:900;

}


.chapter-info{

    flex:1;

    min-width:0;

}


.chapter-title{

    font-size:10px;

    font-weight:900;

}


.chapter-description{

    margin-top:4px;

    color:var(--muted2);

    font-size:7px;

    line-height:1.5;

}


.chapter-open{

    color:#a78bfa;

    font-size:8px;

    font-weight:850;

}


/*
|--------------------------------------------------------------------------
| Feature cards
|--------------------------------------------------------------------------
*/

.feature-grid{

    display:grid;

    grid-template-columns:
        repeat(4,1fr);

    gap:10px;

}


.feature{

    display:block;

    padding:15px;

    border:
        1px solid var(--line);

    border-radius:13px;

    background:#ffffff02;

}


.feature:hover{

    background:#ffffff06;

    border-color:
        rgba(167,139,250,.22);

}


.feature-icon{

    font-size:17px;

}


.feature-title{

    margin-top:9px;

    font-size:9px;

    font-weight:900;

}


.feature-text{

    margin-top:4px;

    color:var(--muted2);

    font-size:7px;

    line-height:1.5;

}


/*
|--------------------------------------------------------------------------
| Mobile
|--------------------------------------------------------------------------
*/

@media(max-width:850px){

    .hero-grid{

        grid-template-columns:1fr;

    }


    .feature-grid{

        grid-template-columns:
            repeat(2,1fr);

    }

}


@media(max-width:520px){

    .wrapper{

        width:
            calc(100% - 22px);

    }


    .hero{

        padding:22px;

    }


    .hero h1{

        font-size:26px;

    }


    .feature-grid{

        grid-template-columns:1fr;

    }

}

</style>

</head>

<body>


<div class="wrapper">


<div class="top">

    <a
        class="back"
        href="/creator-ai/learning/"
    >
        ← Learning Hub
    </a>

    <div class="user">
        <?= course_e($name) ?>
    </div>

</div>


<section class="hero">


<div class="hero-grid">


<div>


<div class="eyebrow">

    <?= course_e(
        strtoupper(
            (string)$course['language_name']
        )
    ) ?>

    · COMPLETE COURSE

</div>


<h1>

    <?= course_e(
        (string)$course['icon']
    ) ?>

    <?= course_e(
        (string)$course['title']
    ) ?>

</h1>


<p>

    <?= course_e(
        (string)$course['description']
    ) ?>

</p>


<div class="chips">

    <span class="chip">
        <?= course_e(
            (string)$course['level']
        ) ?>
    </span>

    <span class="chip">
        <?= count($chapters) ?>
        Chapters
    </span>

    <span class="chip">
        <?= $totalLessons ?>
        Lessons
    </span>

    <span class="chip">
        🧪 Try It Yourself
    </span>

    <span class="chip">
        🧠 Quizzes
    </span>

    <span class="chip">
        🏆 Mock Tests
    </span>

    <span class="chip">
        🚀 Projects
    </span>

</div>


<div class="actions">

<?php if ($nextLesson): ?>

    <a
        class="btn primary"
        href="lesson.php?id=<?= (int)$nextLesson['id'] ?>"
    >
        ▶ Continue Learning
    </a>

<?php else: ?>

    <a
        class="btn primary"
        href="#chapters"
    >
        ✓ Review Course
    </a>

<?php endif; ?>


<a
    class="btn"
    href="practice.php"
>
    💻 Practice
</a>


<a
    class="btn"
    href="quiz.php"
>
    🧠 Quiz
</a>


<a
    class="btn"
    href="mock-test.php"
>
    🏆 Mock Test
</a>


<a
    class="btn"
    href="projects.php"
>
    🚀 Projects
</a>

</div>

</div>


<div class="progress-card">

    <div class="progress-label">
        COURSE PROGRESS
    </div>

    <div class="progress-number">
        <?= (int)$stats['percent'] ?>%
    </div>

    <div class="progress-bar">

        <span></span>

    </div>

    <div class="progress-meta">

        <span>
            <?= (int)$stats['done'] ?>
            /
            <?= (int)$stats['total'] ?>
            lessons
        </span>

        <span>
            ⭐
            <?= (int)$stats['xp'] ?>
            XP
        </span>

    </div>


<?php if ($nextLesson): ?>

    <div class="next">

        <span class="next-label">
            NEXT LESSON
        </span>

        <a
            class="next-title"
            href="lesson.php?id=<?= (int)$nextLesson['id'] ?>"
        >
            <?= course_e(
                (string)$nextLesson['title']
            ) ?>

            →
        </a>

    </div>

<?php endif; ?>

</div>


</div>

</section>


<section
    class="section"
    id="chapters"
>

<div class="section-head">

    <h2>
        Course Curriculum
    </h2>

    <p>
        Chapters and lessons are loaded directly
        from your MySQL database.
    </p>

</div>


<div class="chapter-list">

<?php foreach ($chapters as $chapter): ?>

<a
    class="chapter"
    href="module.php?id=<?= (int)$chapter['id'] ?>"
>

    <div class="chapter-number">

        <?= (int)$chapter['sort_order'] ?>

    </div>


    <div class="chapter-info">

        <div class="chapter-title">

            <?= course_e(
                (string)$chapter['title']
            ) ?>

        </div>

        <div class="chapter-description">

            <?= course_e(
                (string)$chapter['description']
            ) ?>

        </div>

    </div>


    <div class="chapter-open">

        Open →

    </div>

</a>

<?php endforeach; ?>


<?php if (!$chapters): ?>

<div class="chapter">

    <div class="chapter-info">

        <div class="chapter-title">

            No chapters found

        </div>

        <div class="chapter-description">

            Add chapters to
            <strong>
                learning_chapters
            </strong>
            for this course.

        </div>

    </div>

</div>

<?php endif; ?>


</div>

</section>


<section class="section">


<div class="section-head">

    <h2>
        Learn, Practice & Test
    </h2>

    <p>
        Every section has its own database-backed page.
    </p>

</div>


<div class="feature-grid">


<a
    class="feature"
    href="practice.php"
>

    <div class="feature-icon">
        💻
    </div>

    <div class="feature-title">
        Code Practice
    </div>

    <div class="feature-text">
        Exercises and coding challenges.
    </div>

</a>


<a
    class="feature"
    href="quiz.php"
>

    <div class="feature-icon">
        🧠
    </div>

    <div class="feature-title">
        Quizzes
    </div>

    <div class="feature-text">
        Topic and chapter quizzes.
    </div>

</a>


<a
    class="feature"
    href="mock-test.php"
>

    <div class="feature-icon">
        🏆
    </div>

    <div class="feature-title">
        Mock Tests
    </div>

    <div class="feature-text">
        Timed exam-style tests.
    </div>

</a>


<a
    class="feature"
    href="projects.php"
>

    <div class="feature-icon">
        🚀
    </div>

    <div class="feature-title">
        Projects
    </div>

    <div class="feature-text">
        Build practical projects.
    </div>

</a>


<a
    class="feature"
    href="progress.php"
>

    <div class="feature-icon">
        📊
    </div>

    <div class="feature-title">
        Progress
    </div>

    <div class="feature-text">
        Track lessons and performance.
    </div>

</a>


<a
    class="feature"
    href="bookmarks.php"
>

    <div class="feature-icon">
        🔖
    </div>

    <div class="feature-title">
        Bookmarks
    </div>

    <div class="feature-text">
        Open your saved lessons.
    </div>

</a>


<a
    class="feature"
    href="notes.php"
>

    <div class="feature-icon">
        📝
    </div>

    <div class="feature-title">
        Notes
    </div>

    <div class="feature-text">
        Keep personal lesson notes.
    </div>

</a>


<a
    class="feature"
    href="certificate.php"
>

    <div class="feature-icon">
        🎓
    </div>

    <div class="feature-title">
        Certificate
    </div>

    <div class="feature-text">
        Check your course completion.
    </div>

</a>


</div>

</section>


</div>

</body>

</html>
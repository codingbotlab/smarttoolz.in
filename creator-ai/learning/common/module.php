<?php

declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

learning_require_login();

$pdo = learning_pdo();

$uid = learning_user_id();

$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    http_response_code(404);
    exit('Chapter not found.');
}


/*
|--------------------------------------------------------------------------
| Get Chapter
|--------------------------------------------------------------------------
*/

$s = $pdo->prepare("
    SELECT
        ch.id,
        ch.course_id,
        ch.title,
        ch.description,
        ch.sort_order,
        c.slug AS course_slug,
        c.title AS course_title,
        c.language_name
    FROM learning_chapters AS ch
    INNER JOIN learning_courses AS c
        ON c.id = ch.course_id
    WHERE ch.id = ?
      AND c.is_published = 1
    LIMIT 1
");

$s->execute([
    $id
]);

$chapter = $s->fetch();

if (!$chapter) {
    http_response_code(404);
    exit('Chapter not found.');
}


/*
|--------------------------------------------------------------------------
| Get Lessons
|--------------------------------------------------------------------------
|
| IMPORTANT:
| Existing learning_lessons uses:
|
| chapter_id
|
| Existing progress currently only relies on:
|
| user_id
| lesson_id
| completed
|
|--------------------------------------------------------------------------
*/

$l = $pdo->prepare("
    SELECT
        l.id,
        l.title,
        l.slug,
        l.content,
        l.code_example,
        l.language,
        l.difficulty,
        l.duration_minutes,
        l.sort_order,
        l.is_published,

        COALESCE(
            p.completed,
            0
        ) AS completed

    FROM learning_lessons AS l

    LEFT JOIN learning_lesson_progress AS p
        ON p.lesson_id = l.id
       AND p.user_id = ?

    WHERE l.chapter_id = ?
      AND l.is_published = 1

    ORDER BY
        l.sort_order ASC,
        l.id ASC
");

$l->execute([
    $uid,
    $id
]);

$lessons = $l->fetchAll();


/*
|--------------------------------------------------------------------------
| Chapter Progress
|--------------------------------------------------------------------------
*/

$totalLessons = count($lessons);

$completedLessons = 0;

foreach ($lessons as $lesson) {

    if ((int)$lesson['completed'] === 1) {
        $completedLessons++;
    }
}

$chapterProgress = 0;

if ($totalLessons > 0) {

    $chapterProgress = (int)round(
        (
            $completedLessons
            / $totalLessons
        ) * 100
    );
}


/*
|--------------------------------------------------------------------------
| Next Lesson
|--------------------------------------------------------------------------
*/

$nextLesson = null;

foreach ($lessons as $lesson) {

    if ((int)$lesson['completed'] !== 1) {

        $nextLesson = $lesson;

        break;
    }
}


/*
|--------------------------------------------------------------------------
| Escape
|--------------------------------------------------------------------------
*/

function module_e(mixed $value): string
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
    <?= module_e($chapter['title']) ?>
    —
    <?= module_e($chapter['course_title']) ?>
</title>


<style>

:root{

    --bg:#07090e;
    --panel:#10151e;
    --line:rgba(255,255,255,.08);

    --text:#f5f7fb;

    --muted:#8e98aa;
    --muted2:#677287;

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

    color:var(--text);

    font-family:
        Inter,
        Arial,
        sans-serif;

    background:

        radial-gradient(
            circle at 75% -10%,
            rgba(139,92,246,.16),
            transparent 30%
        ),

        linear-gradient(
            180deg,
            #07090e,
            #090c12
        );
}


a{
    color:inherit;
    text-decoration:none;
}


.wrapper{

    width:min(
        950px,
        calc(100% - 28px)
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

    font-weight:800;
}


.language{

    color:var(--muted2);

    font-size:8px;
}


/*
|--------------------------------------------------------------------------
| Header
|--------------------------------------------------------------------------
*/

.header{

    padding:28px;

    border:
        1px solid var(--line);

    border-radius:18px;

    background:

        radial-gradient(
            circle at 85% 0%,
            rgba(34,211,238,.07),
            transparent 28%
        ),

        linear-gradient(
            135deg,
            #111824,
            #0d121a
        );

    box-shadow:
        0 25px 75px
        rgba(0,0,0,.35);
}


.eyebrow{

    color:#c4b5fd;

    font-size:8px;

    font-weight:900;

    letter-spacing:1.4px;

    text-transform:uppercase;
}


h1{

    margin:
        9px
        0
        8px;

    font-size:30px;

    letter-spacing:-1px;
}


.description{

    max-width:720px;

    margin:0;

    color:var(--muted);

    font-size:10px;

    line-height:1.75;
}


/*
|--------------------------------------------------------------------------
| META
|--------------------------------------------------------------------------
*/

.meta{

    display:flex;

    flex-wrap:wrap;

    gap:7px;

    margin-top:16px;
}


.meta span{

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


/*
|--------------------------------------------------------------------------
| Progress
|--------------------------------------------------------------------------
*/

.progress-card{

    margin-top:12px;

    padding:17px;

    border:
        1px solid var(--line);

    border-radius:14px;

    background:#ffffff02;
}


.progress-top{

    display:flex;

    justify-content:space-between;

    align-items:center;
}


.progress-label{

    color:var(--muted2);

    font-size:7px;

    font-weight:850;
}


.progress-number{

    color:#ddd6fe;

    font-size:18px;

    font-weight:900;
}


.progress-bar{

    height:7px;

    margin-top:10px;

    overflow:hidden;

    border-radius:10px;

    background:#252c39;
}


.progress-bar span{

    display:block;

    height:100%;

    width:
        <?= max(
            0,
            min(
                100,
                $chapterProgress
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


.progress-info{

    display:flex;

    justify-content:space-between;

    gap:10px;

    margin-top:8px;

    color:var(--muted2);

    font-size:7px;
}


/*
|--------------------------------------------------------------------------
| Next Lesson
|--------------------------------------------------------------------------
*/

.next{

    margin-top:14px;

    padding-top:12px;

    border-top:
        1px solid var(--line);
}


.next-label{

    display:block;

    color:var(--muted2);

    font-size:7px;
}


.next-link{

    display:inline-block;

    margin-top:4px;

    color:#c4b5fd;

    font-size:9px;

    font-weight:850;
}


/*
|--------------------------------------------------------------------------
| Lessons
|--------------------------------------------------------------------------
*/

.section{

    margin-top:26px;
}


.section-title{

    margin-bottom:11px;

    font-size:16px;

    font-weight:900;
}


.lesson-list{

    display:grid;

    gap:8px;
}


.lesson{

    display:flex;

    align-items:center;

    gap:12px;

    padding:14px;

    border:
        1px solid var(--line);

    border-radius:12px;

    background:#ffffff02;

    transition:.2s;
}


.lesson:hover{

    border-color:
        rgba(167,139,250,.22);

    background:#ffffff05;

    transform:translateY(-1px);
}


.lesson-number{

    width:33px;

    height:33px;

    display:grid;

    place-items:center;

    flex:none;

    border-radius:9px;

    color:#c4ccd9;

    background:#ffffff08;

    font-size:8px;

    font-weight:900;
}


.lesson-number.complete{

    color:#06130e;

    background:var(--green);
}


.lesson-info{

    min-width:0;

    flex:1;
}


.lesson-title{

    font-size:9px;

    font-weight:900;

    line-height:1.4;
}


.lesson-meta{

    display:flex;

    flex-wrap:wrap;

    gap:8px;

    margin-top:5px;

    color:var(--muted2);

    font-size:7px;
}


.lesson-status{

    color:#a78bfa;
}


.lesson-open{

    flex:none;

    color:#a78bfa;

    font-size:8px;

    font-weight:850;
}


.empty{

    padding:25px;

    text-align:center;

    border:
        1px solid var(--line);

    border-radius:12px;

    color:var(--muted2);

    background:#ffffff02;

    font-size:9px;
}


/*
|--------------------------------------------------------------------------
| Mobile
|--------------------------------------------------------------------------
*/

@media(max-width:600px){

    .wrapper{

        width:
            calc(100% - 20px);
    }


    .header{

        padding:22px;
    }


    h1{

        font-size:25px;
    }


    .lesson{

        align-items:flex-start;
    }


    .lesson-open{

        padding-top:8px;
    }

}

</style>

</head>

<body>


<div class="wrapper">


<!-- TOP -->

<div class="top">

    <a
        class="back"
        href="/creator-ai/learning/<?= module_e(
            $chapter['course_slug']
        ) ?>/"
    >

        ←

        <?= module_e(
            $chapter['course_title']
        ) ?>

    </a>


    <div class="language">

        <?= module_e(
            $chapter['language_name']
        ) ?>

    </div>

</div>


<!-- HEADER -->

<section class="header">


<div class="eyebrow">

    Chapter
    <?= (int)$chapter['sort_order'] ?>

</div>


<h1>

    <?= module_e(
        $chapter['title']
    ) ?>

</h1>


<p class="description">

    <?= module_e(
        $chapter['description']
    ) ?>

</p>


<div class="meta">

    <span>
        <?= $totalLessons ?>
        Lessons
    </span>

    <span>
        <?= $completedLessons ?>
        Completed
    </span>

    <span>
        <?= $chapterProgress ?>%
        Complete
    </span>

</div>


<?php if ($nextLesson): ?>

<div style="margin-top:16px">

    <a
        href="lesson.php?id=<?= (int)$nextLesson['id'] ?>"
        style="
            display:inline-flex;
            padding:10px 13px;
            border-radius:9px;
            color:#fff;
            font-size:8px;
            font-weight:850;
            background:
                linear-gradient(
                    135deg,
                    var(--purple),
                    var(--cyan)
                );
        "
    >

        ▶ Continue Learning

    </a>

</div>

<?php endif; ?>


</section>


<!-- PROGRESS -->

<div class="progress-card">


<div class="progress-top">

    <div class="progress-label">

        CHAPTER PROGRESS

    </div>


    <div class="progress-number">

        <?= $chapterProgress ?>%

    </div>

</div>


<div class="progress-bar">

    <span></span>

</div>


<div class="progress-info">

    <span>

        <?= $completedLessons ?>
        /
        <?= $totalLessons ?>

        lessons completed

    </span>


    <span>

        Chapter
        <?= (int)$chapter['sort_order'] ?>

    </span>

</div>


<?php if ($nextLesson): ?>

<div class="next">

    <span class="next-label">

        NEXT LESSON

    </span>


    <a
        class="next-link"
        href="lesson.php?id=<?= (int)$nextLesson['id'] ?>"
    >

        <?= module_e(
            $nextLesson['title']
        ) ?>

        →

    </a>

</div>

<?php endif; ?>


</div>


<!-- LESSONS -->

<section class="section">


<div class="section-title">

    Lessons

</div>


<div class="lesson-list">


<?php if (!$lessons): ?>

<div class="empty">

    इस chapter में अभी कोई lesson नहीं है।

</div>

<?php endif; ?>


<?php foreach (
    $lessons as $index => $lesson
):
?>

<a
    class="lesson"
    href="lesson.php?id=<?= (int)$lesson['id'] ?>"
>


<div
    class="
        lesson-number
        <?= (int)$lesson['completed'] === 1
            ? 'complete'
            : ''
        ?>
    "
>

<?php if (
    (int)$lesson['completed'] === 1
): ?>

    ✓

<?php else: ?>

    <?= $index + 1 ?>

<?php endif; ?>

</div>


<div class="lesson-info">


<div class="lesson-title">

    <?= module_e(
        $lesson['title']
    ) ?>

</div>


<div class="lesson-meta">

    <span>

        <?= module_e(
            $lesson['difficulty']
        ) ?>

    </span>


    <span>

        <?= (int)$lesson['duration_minutes'] ?>

        min

    </span>


<?php if (
    (int)$lesson['completed'] === 1
): ?>

    <span class="lesson-status">

        Completed

    </span>

<?php endif; ?>


</div>


</div>


<div class="lesson-open">

    Open →

</div>


</a>

<?php endforeach; ?>


</div>

</section>


</div>

</body>

</html>
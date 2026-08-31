<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

learning_require_login();

$pdo = learning_pdo();
$uid = learning_user_id();

$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    http_response_code(404);
    exit('Lesson not found.');
}


/*
|--------------------------------------------------------------------------
| Lesson
|--------------------------------------------------------------------------
| Actual structure:
|
| learning_courses
|      ↓
| learning_chapters
|      ↓
| learning_lessons.chapter_id
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("
    SELECT
        l.id,
        l.chapter_id,
        l.title,
        l.slug,
        l.content,
        l.code_example,
        l.language,
        l.difficulty,
        l.duration_minutes,
        l.sort_order,
        l.is_published,

        ch.title AS chapter_title,
        ch.sort_order AS chapter_sort_order,
        ch.course_id,

        c.title AS course_title,
        c.slug AS course_slug,
        c.language_name,
        c.icon AS course_icon

    FROM learning_lessons AS l

    INNER JOIN learning_chapters AS ch
        ON ch.id = l.chapter_id

    INNER JOIN learning_courses AS c
        ON c.id = ch.course_id

    WHERE l.id = ?

      AND l.is_published = 1

      AND c.is_published = 1

    LIMIT 1
");

$stmt->execute([
    $id
]);

$lesson = $stmt->fetch();

if (!$lesson) {
    http_response_code(404);
    exit('Lesson not found.');
}


/*
|--------------------------------------------------------------------------
| Mark lesson as opened
|--------------------------------------------------------------------------
|
| Current progress table is assumed to have:
| user_id
| lesson_id
| completed
|
| We DO NOT use started / progress_percent here.
|--------------------------------------------------------------------------
*/

try {

    $progressStmt = $pdo->prepare("
        INSERT INTO learning_lesson_progress
        (
            user_id,
            lesson_id
        )

        VALUES
        (
            ?,
            ?
        )

        ON DUPLICATE KEY UPDATE
            lesson_id = VALUES(lesson_id)
    ");

    $progressStmt->execute([
        $uid,
        $id
    ]);

} catch (Throwable $e) {

    /*
     * Don't break the lesson if the progress table
     * has a slightly different existing structure.
     */

    error_log(
        'Learning progress open error: ' .
        $e->getMessage()
    );
}


/*
|--------------------------------------------------------------------------
| Current Completion
|--------------------------------------------------------------------------
*/

$completed = 0;

try {

    $progressStmt = $pdo->prepare("
        SELECT completed

        FROM learning_lesson_progress

        WHERE user_id = ?

          AND lesson_id = ?

        LIMIT 1
    ");

    $progressStmt->execute([
        $uid,
        $id
    ]);

    $completed = (int)(
        $progressStmt->fetchColumn() ?? 0
    );

} catch (Throwable $e) {

    $completed = 0;
}


/*
|--------------------------------------------------------------------------
| Try It Yourself
|--------------------------------------------------------------------------
*/

$tryIt = null;

try {

    $stmt = $pdo->prepare("
        SELECT *

        FROM learning_tryit

        WHERE lesson_id = ?

        LIMIT 1
    ");

    $stmt->execute([
        $id
    ]);

    $tryIt = $stmt->fetch();

} catch (Throwable $e) {

    $tryIt = null;
}


/*
|--------------------------------------------------------------------------
| Saved Code
|--------------------------------------------------------------------------
*/

$savedCode = null;

try {

    $stmt = $pdo->prepare("
        SELECT *

        FROM learning_saved_code

        WHERE user_id = ?

          AND lesson_id = ?

        ORDER BY updated_at DESC

        LIMIT 1
    ");

    $stmt->execute([
        $uid,
        $id
    ]);

    $savedCode = $stmt->fetch();

} catch (Throwable $e) {

    $savedCode = null;
}


/*
|--------------------------------------------------------------------------
| Code Defaults
|--------------------------------------------------------------------------
*/

$starterHtml = '';

$starterCss = '';

$starterJs = '';


if ($savedCode) {

    $starterHtml =
        (string)($savedCode['html_code'] ?? '');

    $starterCss =
        (string)($savedCode['css_code'] ?? '');

    $starterJs =
        (string)($savedCode['js_code'] ?? '');

}


if ($starterHtml === '') {

    $starterHtml =
        (string)(
            $tryIt['starter_html']
            ?? $lesson['code_example']
            ?? ''
        );
}


if ($starterCss === '') {

    $starterCss =
        (string)(
            $tryIt['starter_css']
            ?? ''
        );
}


if ($starterJs === '') {

    $starterJs =
        (string)(
            $tryIt['starter_js']
            ?? ''
        );
}


/*
|--------------------------------------------------------------------------
| Previous / Next Lessons
|--------------------------------------------------------------------------
*/

$previousLesson = null;

$nextLesson = null;


$stmt = $pdo->prepare("
    SELECT
        id,
        title,
        slug,
        sort_order

    FROM learning_lessons

    WHERE chapter_id = ?

      AND is_published = 1

    ORDER BY
        sort_order ASC,
        id ASC
");

$stmt->execute([
    $lesson['chapter_id']
]);

$chapterLessons = $stmt->fetchAll();


$currentIndex = null;


foreach (
    $chapterLessons
    as $index => $item
) {

    if ((int)$item['id'] === $id) {

        $currentIndex = $index;

        break;
    }
}


if ($currentIndex !== null) {

    if ($currentIndex > 0) {

        $previousLesson =
            $chapterLessons[
                $currentIndex - 1
            ];
    }


    if (
        $currentIndex
        <
        count($chapterLessons) - 1
    ) {

        $nextLesson =
            $chapterLessons[
                $currentIndex + 1
            ];
    }
}


/*
|--------------------------------------------------------------------------
| Like
|--------------------------------------------------------------------------
*/

$isLiked = false;

try {

    $stmt = $pdo->prepare("
        SELECT id

        FROM learning_likes

        WHERE user_id = ?

          AND lesson_id = ?

        LIMIT 1
    ");

    $stmt->execute([
        $uid,
        $id
    ]);

    $isLiked =
        (bool)$stmt->fetchColumn();

} catch (Throwable $e) {

    $isLiked = false;
}


/*
|--------------------------------------------------------------------------
| Bookmark
|--------------------------------------------------------------------------
*/

$isBookmarked = false;

try {

    $stmt = $pdo->prepare("
        SELECT id

        FROM learning_bookmarks

        WHERE user_id = ?

          AND lesson_id = ?

        LIMIT 1
    ");

    $stmt->execute([
        $uid,
        $id
    ]);

    $isBookmarked =
        (bool)$stmt->fetchColumn();

} catch (Throwable $e) {

    $isBookmarked = false;
}


/*
|--------------------------------------------------------------------------
| Notes
|--------------------------------------------------------------------------
*/

$note = '';

try {

    $stmt = $pdo->prepare("
        SELECT note

        FROM learning_notes

        WHERE user_id = ?

          AND lesson_id = ?

        LIMIT 1
    ");

    $stmt->execute([
        $uid,
        $id
    ]);

    $note = (string)(
        $stmt->fetchColumn() ?? ''
    );

} catch (Throwable $e) {

    $note = '';
}


/*
|--------------------------------------------------------------------------
| Escape Helper
|--------------------------------------------------------------------------
*/

function lesson_e(mixed $value): string
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
    content="width=device-width, initial-scale=1"
>

<title>
    <?= lesson_e($lesson['title']) ?>
    —
    <?= lesson_e($lesson['course_title']) ?>
</title>

<style>

:root{

    --bg:#07090e;

    --panel:#10151e;

    --panel2:#0c1118;

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
            circle at 80% -10%,
            rgba(139,92,246,.16),
            transparent 30%
        ),

        #07090e;

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
        1250px,
        calc(100% - 28px)
    );

    margin:auto;

    padding:
        25px
        0
        70px;
}


/*
|--------------------------------------------------------------------------
| Top navigation
|--------------------------------------------------------------------------
*/

.top{

    display:flex;

    justify-content:space-between;

    align-items:center;

    gap:10px;

    margin-bottom:18px;

}


.back{

    color:#a78bfa;

    font-size:9px;

    font-weight:800;
}


.course-name{

    color:var(--muted2);

    font-size:8px;
}


/*
|--------------------------------------------------------------------------
| Main Grid
|--------------------------------------------------------------------------
*/

.layout{

    display:grid;

    grid-template-columns:
        minmax(0,1fr)
        430px;

    gap:14px;

    align-items:start;
}


/*
|--------------------------------------------------------------------------
| Panel
|--------------------------------------------------------------------------
*/

.panel{

    padding:22px;

    border:
        1px solid var(--line);

    border-radius:16px;

    background:
        linear-gradient(
            145deg,
            #10151e,
            #0d1219
        );
}


/*
|--------------------------------------------------------------------------
| Lesson content
|--------------------------------------------------------------------------
*/

.eyebrow{

    color:#c4b5fd;

    font-size:8px;

    font-weight:900;

    letter-spacing:1.4px;

    text-transform:uppercase;
}


h1{

    margin:
        8px
        0
        7px;

    font-size:30px;

    letter-spacing:-1px;

    line-height:1.18;
}


.meta{

    display:flex;

    flex-wrap:wrap;

    gap:7px;

    color:var(--muted2);

    font-size:7px;
}


.meta span{

    padding:
        5px
        7px;

    border:
        1px solid var(--line);

    border-radius:6px;

}


.content{

    margin-top:22px;

    color:#b5bdcb;

    font-size:11px;

    line-height:1.85;
}


.content h2{

    color:#fff;

    font-size:19px;
}


.content h3{

    color:#fff;

    font-size:14px;
}


.content code{

    padding:
        2px
        5px;

    border-radius:4px;

    background:#ffffff08;

    color:#ddd6fe;
}


.content pre{

    padding:14px;

    overflow:auto;

    border:
        1px solid var(--line);

    border-radius:10px;

    background:#07090d;

    color:#dce3ef;

    font:
        11px/1.6
        monospace;
}


/*
|--------------------------------------------------------------------------
| Example
|--------------------------------------------------------------------------
*/

.example-title{

    margin-top:22px;

    margin-bottom:7px;

    color:#fff;

    font-size:11px;

    font-weight:900;
}


.code-example{

    margin:0;

    padding:15px;

    overflow:auto;

    border:
        1px solid var(--line);

    border-radius:11px;

    background:#07090d;

    color:#dce3ef;

    white-space:pre-wrap;

    font:
        11px/1.65
        ui-monospace,
        SFMono-Regular,
        Menlo,
        monospace;
}


/*
|--------------------------------------------------------------------------
| Try It
|--------------------------------------------------------------------------
*/

.try-head{

    display:flex;

    justify-content:space-between;

    align-items:center;

    gap:10px;

    margin-bottom:10px;
}


.try-title{

    font-size:13px;

    font-weight:900;
}


.try-badge{

    padding:
        5px
        7px;

    border-radius:6px;

    color:#a7f3d0;

    background:#34d39912;

    border:
        1px solid
        #34d39924;

    font-size:7px;

    font-weight:850;
}


.editor-label{

    margin:
        10px
        0
        5px;

    color:var(--muted2);

    font-size:7px;

    font-weight:900;

    letter-spacing:.8px;
}


.editor{

    width:100%;

    min-height:130px;

    padding:12px;

    resize:vertical;

    outline:0;

    border:
        1px solid var(--line);

    border-radius:9px;

    background:#07090d;

    color:#dce3ef;

    font:
        11px/1.55
        ui-monospace,
        SFMono-Regular,
        Menlo,
        monospace;
}


.editor:focus{

    border-color:
        rgba(167,139,250,.35);
}


.preview{

    width:100%;

    height:280px;

    margin-top:7px;

    border:
        1px solid var(--line);

    border-radius:9px;

    background:#fff;
}


/*
|--------------------------------------------------------------------------
| Buttons
|--------------------------------------------------------------------------
*/

.actions{

    display:flex;

    flex-wrap:wrap;

    gap:7px;

    margin-top:9px;
}


.btn{

    display:inline-flex;

    align-items:center;

    justify-content:center;

    gap:6px;

    padding:
        9px
        11px;

    border:
        1px solid var(--line);

    border-radius:8px;

    color:#c0c8d5;

    background:#ffffff04;

    font-size:8px;

    font-weight:850;

    cursor:pointer;
}


.btn:hover{

    background:#ffffff08;

    color:#fff;
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
| Footer lesson navigation
|--------------------------------------------------------------------------
*/

.lesson-nav{

    display:flex;

    justify-content:space-between;

    gap:8px;

    margin-top:20px;

}


.nav-btn{

    flex:1;

    padding:11px;

    border:
        1px solid var(--line);

    border-radius:9px;

    background:#ffffff03;

    color:#aab3c2;

    font-size:8px;

    text-align:center;
}


.nav-btn:hover{

    color:#fff;

    background:#ffffff07;
}


@media(max-width:950px){

    .layout{

        grid-template-columns:1fr;
    }

}


@media(max-width:520px){

    .wrapper{

        width:
            calc(100% - 18px);
    }


    .panel{

        padding:16px;
    }


    h1{

        font-size:25px;
    }


    .lesson-nav{

        flex-direction:column;
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
        href="module.php?id=<?= (int)$lesson['chapter_id'] ?>"
    >

        ←

        <?= lesson_e(
            $lesson['chapter_title']
        ) ?>

    </a>


    <div class="course-name">

        <?= lesson_e(
            $lesson['course_title']
        ) ?>

    </div>

</div>


<div class="layout">


<!-- =========================================================
     LESSON
========================================================= -->

<main class="panel">


<div class="eyebrow">

    <?= lesson_e(
        $lesson['language']
        ?: $lesson['course_title']
    ) ?>

    · LESSON

</div>


<h1>

    <?= lesson_e(
        $lesson['title']
    ) ?>

</h1>


<div class="meta">

    <span>

        <?= lesson_e(
            $lesson['difficulty']
        ) ?>

    </span>


    <span>

        <?= (int)$lesson['duration_minutes'] ?>

        minutes

    </span>


<?php if ($completed): ?>

    <span
        style="color:#86efac"
    >

        ✓ Completed

    </span>

<?php endif; ?>


</div>


<article class="content">

    <?= $lesson['content'] ?>

</article>


<?php if (
    trim(
        (string)$lesson['code_example']
    ) !== ''
): ?>


<div class="example-title">

    Example

</div>


<pre class="code-example"><?= lesson_e(
    $lesson['code_example']
) ?></pre>


<?php endif; ?>


<div class="actions">

    <button
        class="btn primary"
        id="completeBtn"
        onclick="completeLesson()"
    >

        <?= $completed
            ? '✓ Completed'
            : '✓ Mark Complete'
        ?>

    </button>


    <button
        class="btn"
        onclick="toggleLike()"
    >

        <?= $isLiked
            ? '♥ Liked'
            : '♡ Like'
        ?>

    </button>


    <button
        class="btn"
        onclick="toggleBookmark()"
    >

        <?= $isBookmarked
            ? '★ Bookmarked'
            : '☆ Bookmark'
        ?>

    </button>


    <button
        class="btn"
        onclick="saveNote()"
    >

        📝 Save Note

    </button>


    <button
        class="btn"
        onclick="shareLesson()"
    >

        ↗ Share

    </button>

</div>


<div style="margin-top:18px">

    <div
        class="editor-label"
    >
        PERSONAL NOTE
    </div>


    <textarea
        id="note"
        class="editor"
        style="min-height:90px"
        placeholder="Write your notes for this lesson..."
    ><?= lesson_e($note) ?></textarea>

</div>


<div class="lesson-nav">


<?php if ($previousLesson): ?>

<a
    class="nav-btn"
    href="lesson.php?id=<?= (int)$previousLesson['id'] ?>"
>

    ←

    <?= lesson_e(
        $previousLesson['title']
    ) ?>

</a>

<?php else: ?>

<div></div>

<?php endif; ?>


<?php if ($nextLesson): ?>

<a
    class="nav-btn"
    href="lesson.php?id=<?= (int)$nextLesson['id'] ?>"
>

    <?= lesson_e(
        $nextLesson['title']
    ) ?>

    →

</a>

<?php endif; ?>


</div>


</main>


<!-- =========================================================
     TRY IT YOURSELF
========================================================= -->

<aside class="panel">


<div class="try-head">

    <div class="try-title">

        🧪 Try It Yourself

    </div>


    <div class="try-badge">

        Browser Sandbox

    </div>

</div>


<p
    style="
        margin:0 0 12px;
        color:var(--muted);
        font-size:8px;
        line-height:1.6;
    "
>

    Edit the code and click
    <strong>Run</strong>
    to see your result.

    Your saved code belongs to
    your account and this lesson.

</p>


<!-- HTML -->

<div class="editor-label">

    HTML

</div>


<textarea
    id="htmlCode"
    class="editor"
><?= lesson_e($starterHtml) ?></textarea>


<!-- CSS -->

<div class="editor-label">

    CSS

</div>


<textarea
    id="cssCode"
    class="editor"
><?= lesson_e($starterCss) ?></textarea>


<!-- JS -->

<div class="editor-label">

    JAVASCRIPT

</div>


<textarea
    id="jsCode"
    class="editor"
><?= lesson_e($starterJs) ?></textarea>


<iframe
    id="preview"
    class="preview"
    sandbox="allow-scripts"
></iframe>


<div class="actions">


<button
    class="btn primary"
    onclick="runCode()"
>

    ▶ Run

</button>


<button
    class="btn"
    onclick="saveCode()"
>

    💾 Save Code

</button>


<button
    class="btn"
    onclick="resetCode()"
>

    ↻ Reset

</button>


<button
    class="btn"
    onclick="copyHTML()"
>

    📋 Copy HTML

</button>


</div>


</aside>


</div>


</div>


<script>

const STARTER_HTML =
<?= json_encode(
    $tryIt['starter_html']
    ?? $lesson['code_example']
    ?? '',
    JSON_UNESCAPED_UNICODE |
    JSON_UNESCAPED_SLASHES
) ?>;


const STARTER_CSS =
<?= json_encode(
    $tryIt['starter_css']
    ?? '',
    JSON_UNESCAPED_UNICODE |
    JSON_UNESCAPED_SLASHES
) ?>;


const STARTER_JS =
<?= json_encode(
    $tryIt['starter_js']
    ?? '',
    JSON_UNESCAPED_UNICODE |
    JSON_UNESCAPED_SLASHES
) ?>;


const htmlEditor =
    document.getElementById(
        'htmlCode'
    );


const cssEditor =
    document.getElementById(
        'cssCode'
    );


const jsEditor =
    document.getElementById(
        'jsCode'
    );


const preview =
    document.getElementById(
        'preview'
    );


/*
|--------------------------------------------------------------------------
| Run
|--------------------------------------------------------------------------
*/

function runCode()
{

    const html =
        htmlEditor.value;


    const css =
        cssEditor.value;


    const js =
        jsEditor.value;


    const documentContent =

        '<!doctype html>' +

        '<html>' +

        '<head>' +

        '<meta charset="UTF-8">' +

        '<style>' +

        css +

        '</style>' +

        '</head>' +

        '<body>' +

        html +

        '<script>' +

        'try{' +

        js +

        '}catch(error){' +

        'document.body.insertAdjacentHTML(' +

        '"beforeend",' +

        '"<pre style=\\"color:red\\">"' +

        '+error.toString()' +

        '+"</pre>"' +

        ');' +

        '}' +

        '<\/script>' +

        '</body>' +

        '</html>';


    preview.srcdoc =
        documentContent;
}


/*
|--------------------------------------------------------------------------
| Save Code
|--------------------------------------------------------------------------
*/

async function saveCode()
{

    try {

        const response =
            await fetch(
                'api.php',
                {
                    method:'POST',

                    headers:{
                        'Content-Type':
                            'application/json'
                    },

                    body:JSON.stringify({

                        action:
                            'save_code',

                        lesson_id:
                            <?= (int)$id ?>,

                        html_code:
                            htmlEditor.value,

                        css_code:
                            cssEditor.value,

                        js_code:
                            jsEditor.value

                    })
                }
            );


        const data =
            await response.json();


        if (data.ok) {

            alert(
                'Code saved successfully.'
            );

        } else {

            alert(
                data.error
                ||
                'Unable to save code.'
            );
        }

    } catch(error) {

        alert(
            'Request failed.'
        );
    }
}


/*
|--------------------------------------------------------------------------
| Complete Lesson
|--------------------------------------------------------------------------
*/

async function completeLesson()
{

    try {

        const response =
            await fetch(
                'api.php',
                {
                    method:'POST',

                    headers:{
                        'Content-Type':
                            'application/json'
                    },

                    body:JSON.stringify({

                        action:
                            'complete_lesson',

                        lesson_id:
                            <?= (int)$id ?>

                    })
                }
            );


        const data =
            await response.json();


        if (data.ok) {

            const button =
                document.getElementById(
                    'completeBtn'
                );


            button.textContent =
                '✓ Completed';


            alert(
                'Lesson completed!'
            );

        } else {

            alert(
                data.error
                ||
                'Unable to update progress.'
            );
        }

    } catch(error) {

        alert(
            'Request failed.'
        );
    }
}


/*
|--------------------------------------------------------------------------
| Like
|--------------------------------------------------------------------------
*/

async function toggleLike()
{

    const response =
        await fetch(
            'api.php',
            {
                method:'POST',

                headers:{
                    'Content-Type':
                        'application/json'
                },

                body:JSON.stringify({

                    action:'like',

                    lesson_id:
                        <?= (int)$id ?>

                })
            }
        );


    const data =
        await response.json();


    if (data.ok) {

        location.reload();

    } else {

        alert(
            data.error
            ||
            'Unable to update like.'
        );
    }
}


/*
|--------------------------------------------------------------------------
| Bookmark
|--------------------------------------------------------------------------
*/

async function toggleBookmark()
{

    const response =
        await fetch(
            'api.php',
            {
                method:'POST',

                headers:{
                    'Content-Type':
                        'application/json'
                },

                body:JSON.stringify({

                    action:'bookmark',

                    lesson_id:
                        <?= (int)$id ?>

                })
            }
        );


    const data =
        await response.json();


    if (data.ok) {

        location.reload();

    } else {

        alert(
            data.error
            ||
            'Unable to update bookmark.'
        );
    }
}


/*
|--------------------------------------------------------------------------
| Save Note
|--------------------------------------------------------------------------
*/

async function saveNote()
{

    const note =
        document.getElementById(
            'note'
        ).value;


    try {

        const response =
            await fetch(
                'api.php',
                {
                    method:'POST',

                    headers:{
                        'Content-Type':
                            'application/json'
                    },

                    body:JSON.stringify({

                        action:
                            'save_note',

                        lesson_id:
                            <?= (int)$id ?>,

                        note:
                            note

                    })
                }
            );


        const data =
            await response.json();


        alert(
            data.ok
                ? 'Note saved.'
                : (
                    data.error
                    ||
                    'Unable to save note.'
                )
        );

    } catch(error) {

        alert(
            'Request failed.'
        );
    }
}


/*
|--------------------------------------------------------------------------
| Share
|--------------------------------------------------------------------------
*/

function shareLesson()
{

    if (
        navigator.share
    ) {

        navigator.share({

            title:
                document.title,

            url:
                window.location.href

        });

        return;
    }


    if (
        navigator.clipboard
    ) {

        navigator.clipboard
            .writeText(
                window.location.href
            )
            .then(
                function(){

                    alert(
                        'Lesson link copied.'
                    );

                }
            );

    }

}


/*
|--------------------------------------------------------------------------
| Copy HTML
|--------------------------------------------------------------------------
*/

async function copyHTML()
{

    try {

        await navigator.clipboard
            .writeText(
                htmlEditor.value
            );


        alert(
            'HTML copied.'
        );

    } catch(error) {

        alert(
            'Unable to copy.'
        );
    }

}


/*
|--------------------------------------------------------------------------
| Reset
|--------------------------------------------------------------------------
*/

function resetCode()
{

    htmlEditor.value =
        STARTER_HTML;


    cssEditor.value =
        STARTER_CSS;


    jsEditor.value =
        STARTER_JS;


    runCode();
}


/*
|--------------------------------------------------------------------------
| Initial preview
|--------------------------------------------------------------------------
*/

runCode();

</script>


</body>

</html>
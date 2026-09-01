<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';

$admin = lh_require_admin();
$messages = [];
$errors = [];

try {
    $db->beginTransaction();

    $categories = [
        ['computer-basics','Computer Basics','Start using computers confidently.','🖥️',10],
        ['office-productivity','Office & Productivity','Practical office and productivity skills.','📄',20],
        ['digital-skills','Internet & Digital Skills','Useful internet and digital skills.','🌐',30],
        ['design','Graphic Design','Practical visual design skills.','🎨',40],
        ['web-development','Web Development','Build modern websites.','💻',50],
        ['programming','Programming','Programming, PHP, databases and APIs.','⌨️',60],
        ['ai','AI & Generative AI','AI, prompting and responsible AI use.','🤖',70],
        ['digital-marketing','Digital Marketing & SEO','SEO, content and digital marketing.','📈',80],
        ['freelancing','Freelancing & Career','Practical online-work and career skills.','💼',90],
        ['business','Business & Accounting','Useful business computer skills.','🧾',100],
        ['advanced-tech','Advanced Technology','Linux, networking, cloud and security concepts.','🚀',110],
    ];

    $stmt = $db->prepare('INSERT IGNORE INTO learning_categories(slug,name,description,icon,sort_order) VALUES(?,?,?,?,?)');
    foreach ($categories as $row) $stmt->execute($row);

    $courses = [
        ['computer-basics','computer-basics-for-beginners','Computer Basics for Beginners','Learn computer fundamentals, files, Windows and safe digital habits.','Beginner',1],
        ['office-productivity','ms-office-productivity-basics','MS Office & Productivity Basics','Learn practical Word, Excel and PowerPoint skills.','Beginner',1],
        ['digital-skills','internet-and-digital-skills','Internet & Digital Skills','Learn browsers, email, cloud storage and digital safety.','Beginner',1],
        ['design','graphic-design-with-canva','Graphic Design with Canva','Learn practical design principles and Canva workflows.','Beginner',1],
        ['web-development','html-css-for-beginners','HTML & CSS for Beginners','Build responsive web pages with HTML and CSS.','Beginner',1],
        ['web-development','javascript-foundations','JavaScript Foundations','Learn JavaScript fundamentals and browser interaction.','Beginner',0],
        ['programming','php-mysql-for-beginners','PHP & MySQL for Beginners','Learn PHP, forms, databases and CRUD applications.','Intermediate',1],
        ['ai','ai-and-prompt-engineering','AI & Prompt Engineering','Understand generative AI and write useful prompts.','Beginner',1],
        ['digital-marketing','seo-fundamentals','SEO Fundamentals','Learn search intent, on-page SEO and technical basics.','Beginner',1],
        ['freelancing','freelancing-career-foundations','Freelancing & Career Foundations','Build a portfolio and professional freelance workflow.','Beginner',1],
        ['advanced-tech','git-and-github-foundations','Git & GitHub Foundations','Learn repositories, commits, branches and GitHub.','Intermediate',0],
    ];

    $cat = $db->prepare('SELECT id FROM learning_categories WHERE slug=? LIMIT 1');
    $course = $db->prepare('SELECT id FROM learning_courses WHERE slug=? LIMIT 1');
    $insert = $db->prepare('INSERT INTO learning_courses(category_id,title,slug,description,level,featured,enabled) VALUES(?,?,?,?,?,?,1)');

    foreach ($courses as $c) {
        $cat->execute([$c[0]]);
        $categoryId = (int)$cat->fetchColumn();
        if (!$categoryId) throw new RuntimeException('Category missing: '.$c[0]);
        $course->execute([$c[1]]);
        $courseId = (int)$course->fetchColumn();
        if (!$courseId) {
            $insert->execute([$categoryId,$c[2],$c[1],$c[3],$c[4],$c[5]]);
            $courseId = (int)$db->lastInsertId();
        }
        $messages[] = $c[2] . ' — OK';
    }

    $db->commit();
} catch (Throwable $e) {
    if ($db->inTransaction()) $db->rollBack();
    $errors[] = $e->getMessage();
}

$count = 0;
try { $count = (int)$db->query('SELECT COUNT(*) FROM learning_courses WHERE enabled=1')->fetchColumn(); } catch (Throwable $e) { $errors[] = $e->getMessage(); }

?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Learning Hub Repair</title><style>body{font-family:system-ui;background:#f7f8fc;color:#172033;margin:0}.wrap{max-width:800px;margin:50px auto;padding:24px}.box{background:#fff;border:1px solid #e3e7ef;border-radius:18px;padding:25px;margin-top:18px}.ok{color:#16834b}.err{color:#b42318;background:#fff1f0;padding:14px;border-radius:10px}a{color:#635bff;font-weight:800}</style></head><body><main class="wrap"><a href="/learning-hub/admin/">← Admin</a><h1>Learning Hub Repair</h1><div class="box"><h2><?=htmlspecialchars((string)$count)?> published courses</h2><?php if($errors):foreach($errors as $e):?><p class="err"><?=htmlspecialchars($e,ENT_QUOTES,'UTF-8')?></p><?php endforeach;else:?><p class="ok">Course data is ready.</p><?php endif;?></div><div class="box"><h3>Repair result</h3><?php foreach($messages as $m):?><p class="ok">✓ <?=htmlspecialchars($m,ENT_QUOTES,'UTF-8')?></p><?php endforeach;?></div><p><a href="/learning-hub/courses.php">Open Courses →</a></p></main></body></html>

<?php
declare(strict_types=1);

/* Self-healing visibility/seed check for the public course catalogue. */
function lh_ensure_courses(PDO $db): void {
    try {
        $count = (int)$db->query("SELECT COUNT(*) FROM learning_courses WHERE enabled=1")->fetchColumn();
        if ($count > 0) return;

        $db->exec("UPDATE learning_categories SET enabled=1");
        $db->exec("UPDATE learning_courses SET enabled=1");
        $db->exec("UPDATE learning_lessons SET enabled=1");

        $count = (int)$db->query("SELECT COUNT(*) FROM learning_courses WHERE enabled=1")->fetchColumn();
        if ($count > 0) return;

        $categories = [
            ['computer-basics','Computer Basics','🖥️'],
            ['office-productivity','Office & Productivity','📄'],
            ['digital-skills','Internet & Digital Skills','🌐'],
            ['design','Graphic Design','🎨'],
            ['web-development','Web Development','💻'],
            ['programming','Programming','⌨️'],
            ['ai','AI & Generative AI','🤖'],
            ['digital-marketing','Digital Marketing & SEO','📈'],
            ['freelancing','Freelancing & Career','💼'],
            ['business','Business & Accounting','🧾'],
            ['advanced-tech','Advanced Technology','🚀'],
        ];
        $cat = $db->prepare('INSERT IGNORE INTO learning_categories(slug,name,icon,sort_order,enabled) VALUES(?,?,?,?,1)');
        foreach ($categories as $i=>$c) $cat->execute([$c[0],$c[1],$c[2],($i+1)*10]);

        $find = $db->prepare('SELECT id FROM learning_categories WHERE slug=? LIMIT 1');
        $add = $db->prepare('INSERT INTO learning_courses(category_id,title,slug,description,level,featured,enabled) VALUES(?,?,?,?,?,?,1)');
        $courses = [
            ['computer-basics','Computer Basics for Beginners','computer-basics-for-beginners','Learn computer fundamentals, files, Windows and safe digital habits.','Beginner',1],
            ['office-productivity','MS Office & Productivity Basics','ms-office-productivity-basics','Build practical Word, Excel and PowerPoint skills.','Beginner',1],
            ['digital-skills','Internet & Digital Skills','internet-and-digital-skills','Learn browsers, email, cloud storage, online forms and privacy.','Beginner',0],
            ['design','Graphic Design with Canva','graphic-design-with-canva','Learn practical visual design, social graphics and thumbnails.','Beginner',1],
            ['web-development','HTML & CSS for Beginners','html-css-for-beginners','Build responsive web pages with HTML and modern CSS.','Beginner',1],
            ['web-development','JavaScript Foundations','javascript-foundations','Learn JavaScript fundamentals and browser interaction.','Beginner',0],
            ['programming','PHP & MySQL for Beginners','php-mysql-for-beginners','Learn PHP, forms, databases and CRUD applications.','Intermediate',1],
            ['ai','AI & Prompt Engineering','ai-and-prompt-engineering','Understand generative AI and write useful, responsible prompts.','Beginner',1],
            ['digital-marketing','SEO Fundamentals','seo-fundamentals','Learn search intent, on-page SEO and technical foundations.','Beginner',1],
            ['freelancing','Freelancing & Career Foundations','freelancing-career-foundations','Build a portfolio and professional freelance workflow.','Beginner',1],
            ['advanced-tech','Git & GitHub Foundations','git-and-github-foundations','Learn repositories, commits, branches and collaboration.','Intermediate',0],
        ];
        foreach ($courses as $c) {
            $find->execute([$c[0]]); $categoryId=(int)$find->fetchColumn();
            if (!$categoryId) continue;
            $q=$db->prepare('SELECT id FROM learning_courses WHERE slug=? LIMIT 1');
            $q->execute([$c[2]]); $courseId=(int)$q->fetchColumn();
            if ($courseId) {
                $u=$db->prepare('UPDATE learning_courses SET enabled=1,category_id=?,title=?,description=?,level=?,featured=? WHERE id=?');
                $u->execute([$categoryId,$c[1],$c[3],$c[4],$c[5],$courseId]);
            } else {
                $add->execute([$categoryId,$c[1],$c[2],$c[3],$c[4],$c[5]]);
            }
        }
    } catch (Throwable $e) {
        error_log('Learning Hub ensure courses: '.$e->getMessage());
    }
}

lh_ensure_courses($db);

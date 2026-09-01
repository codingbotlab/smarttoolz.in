<?php
declare(strict_types=1);

function lh_column_exists(PDO $db, string $table, string $column): bool {
    $q=$db->prepare('SELECT COUNT(*) FROM information_schema.columns WHERE table_schema=DATABASE() AND table_name=? AND column_name=?');
    $q->execute([$table,$column]);
    return (int)$q->fetchColumn()>0;
}
function lh_add_column(PDO $db,string $table,string $column,string $definition):void{
    if(!lh_column_exists($db,$table,$column)) $db->exec('ALTER TABLE `'.$table.'` ADD COLUMN `'.$column.'` '.$definition);
}
function lh_ensure_courses(PDO $db): void {
    try {
        $schema=[
        "CREATE TABLE IF NOT EXISTS learning_categories(id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,slug VARCHAR(100) NOT NULL UNIQUE,name VARCHAR(150) NOT NULL,description VARCHAR(500) NULL,icon VARCHAR(30) NULL,sort_order INT NOT NULL DEFAULT 0,enabled TINYINT(1) NOT NULL DEFAULT 1,created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
        "CREATE TABLE IF NOT EXISTS learning_courses(id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,category_id INT UNSIGNED NULL,title VARCHAR(180) NOT NULL,slug VARCHAR(180) NOT NULL UNIQUE,description TEXT NULL,level VARCHAR(30) NOT NULL DEFAULT 'Beginner',thumbnail VARCHAR(500) NULL,featured TINYINT(1) NOT NULL DEFAULT 0,enabled TINYINT(1) NOT NULL DEFAULT 1,created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,INDEX(category_id),INDEX(enabled,featured)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
        "CREATE TABLE IF NOT EXISTS learning_lessons(id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,course_id BIGINT UNSIGNED NOT NULL,title VARCHAR(180) NOT NULL,slug VARCHAR(180) NOT NULL,content LONGTEXT NULL,video_url VARCHAR(500) NULL,sort_order INT NOT NULL DEFAULT 0,enabled TINYINT(1) NOT NULL DEFAULT 1,created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,UNIQUE KEY course_slug(course_id,slug),INDEX(course_id)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
        "CREATE TABLE IF NOT EXISTS learning_progress(id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,user_id BIGINT UNSIGNED NOT NULL,lesson_id BIGINT UNSIGNED NOT NULL,completed TINYINT(1) NOT NULL DEFAULT 0,progress_percent TINYINT UNSIGNED NOT NULL DEFAULT 0,updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,UNIQUE KEY user_lesson(user_id,lesson_id),INDEX(user_id)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
        "CREATE TABLE IF NOT EXISTS learning_articles(id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,title VARCHAR(180) NOT NULL,slug VARCHAR(180) NOT NULL UNIQUE,excerpt VARCHAR(500) NULL,content LONGTEXT NULL,author_id BIGINT UNSIGNED NULL,enabled TINYINT(1) NOT NULL DEFAULT 1,created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
        "CREATE TABLE IF NOT EXISTS learning_events(id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,user_id BIGINT UNSIGNED NULL,event_name VARCHAR(100) NOT NULL,entity_type VARCHAR(80) NULL,entity_id BIGINT UNSIGNED NULL,metadata TEXT NULL,created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,INDEX(event_name),INDEX(user_id,created_at)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
        ];
        foreach($schema as $sql)$db->exec($sql);
        lh_add_column($db,'learning_categories','description','VARCHAR(500) NULL');
        lh_add_column($db,'learning_categories','icon','VARCHAR(30) NULL');
        lh_add_column($db,'learning_categories','sort_order','INT NOT NULL DEFAULT 0');
        lh_add_column($db,'learning_categories','enabled','TINYINT(1) NOT NULL DEFAULT 1');
        lh_add_column($db,'learning_courses','category_id','INT UNSIGNED NULL');
        lh_add_column($db,'learning_courses','description','TEXT NULL');
        lh_add_column($db,'learning_courses','level',"VARCHAR(30) NOT NULL DEFAULT 'Beginner'");
        lh_add_column($db,'learning_courses','thumbnail','VARCHAR(500) NULL');
        lh_add_column($db,'learning_courses','featured','TINYINT(1) NOT NULL DEFAULT 0');
        lh_add_column($db,'learning_courses','enabled','TINYINT(1) NOT NULL DEFAULT 1');
        lh_add_column($db,'learning_lessons','course_id','BIGINT UNSIGNED NOT NULL');
        lh_add_column($db,'learning_lessons','content','LONGTEXT NULL');
        lh_add_column($db,'learning_lessons','video_url','VARCHAR(500) NULL');
        lh_add_column($db,'learning_lessons','sort_order','INT NOT NULL DEFAULT 0');
        lh_add_column($db,'learning_lessons','enabled','TINYINT(1) NOT NULL DEFAULT 1');

        $categories=[
        ['computer-basics','Computer Basics','Start using computers confidently.','🖥️',10],['office-productivity','Office & Productivity','Practical Word, Excel and PowerPoint skills.','📄',20],['digital-skills','Internet & Digital Skills','Useful internet and digital skills.','🌐',30],['design','Graphic Design','Practical visual design skills.','🎨',40],['web-development','Web Development','Build modern websites.','💻',50],['programming','Programming','Programming, PHP, databases and APIs.','⌨️',60],['ai','AI & Generative AI','AI, prompting and responsible AI use.','🤖',70],['digital-marketing','Digital Marketing & SEO','SEO and digital marketing fundamentals.','📈',80],['freelancing','Freelancing & Career','Practical career and freelance skills.','💼',90],['business','Business & Accounting','Useful business computer skills.','🧾',100],['advanced-tech','Advanced Technology','Linux, networking, cloud and security concepts.','🚀',110]];
        $cat=$db->prepare('INSERT INTO learning_categories(slug,name,description,icon,sort_order,enabled) VALUES(?,?,?,?,?,1) ON DUPLICATE KEY UPDATE name=VALUES(name),description=VALUES(description),icon=VALUES(icon),sort_order=VALUES(sort_order),enabled=1');
        foreach($categories as $c)$cat->execute($c);
        $courses=[
        ['computer-basics','computer-basics-for-beginners','Computer Basics for Beginners','Learn computer fundamentals, files, Windows and safe digital habits.','Beginner',1],['office-productivity','ms-office-productivity-basics','MS Office & Productivity Basics','Build practical Word, Excel and PowerPoint skills.','Beginner',1],['digital-skills','internet-and-digital-skills','Internet & Digital Skills','Learn browsers, email, cloud storage and digital safety.','Beginner',0],['design','graphic-design-with-canva','Graphic Design with Canva','Learn practical design, social graphics and thumbnails.','Beginner',1],['web-development','html-css-for-beginners','HTML & CSS for Beginners','Build responsive web pages with HTML and CSS.','Beginner',1],['web-development','javascript-foundations','JavaScript Foundations','Learn JavaScript fundamentals and browser interaction.','Beginner',0],['programming','php-mysql-for-beginners','PHP & MySQL for Beginners','Learn PHP, forms, databases and CRUD applications.','Intermediate',1],['ai','ai-and-prompt-engineering','AI & Prompt Engineering','Understand generative AI and write useful, responsible prompts.','Beginner',1],['digital-marketing','seo-fundamentals','SEO Fundamentals','Learn search intent, on-page SEO and technical basics.','Beginner',1],['freelancing','freelancing-career-foundations','Freelancing & Career Foundations','Build a portfolio and professional freelance workflow.','Beginner',1],['advanced-tech','git-and-github-foundations','Git & GitHub Foundations','Learn repositories, commits, branches and collaboration.','Intermediate',0]];
        $findCat=$db->prepare('SELECT id FROM learning_categories WHERE slug=? LIMIT 1');$find=$db->prepare('SELECT id FROM learning_courses WHERE slug=? LIMIT 1');$ins=$db->prepare('INSERT INTO learning_courses(category_id,title,slug,description,level,featured,enabled) VALUES(?,?,?,?,?,?,1)');$upd=$db->prepare('UPDATE learning_courses SET category_id=?,title=?,description=?,level=?,featured=?,enabled=1 WHERE id=?');
        foreach($courses as $c){$findCat->execute([$c[0]]);$cid=(int)$findCat->fetchColumn();if(!$cid)continue;$find->execute([$c[1]]);$id=(int)$find->fetchColumn();if($id)$upd->execute([$cid,$c[2],$c[3],$c[4],$c[5],$id]);else $ins->execute([$cid,$c[2],$c[1],$c[3],$c[4],$c[5]]);}
    }catch(Throwable $e){error_log('Learning Hub catalogue: '.$e->getMessage());}
}
lh_ensure_courses($db);

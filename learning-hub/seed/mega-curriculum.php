<?php
declare(strict_types=1);

function lh_make_5000(PDO $db): array {
    $catalog = [
        ['computer-basics','Computer Basics Mastery'],['office-productivity','MS Office Productivity'],['digital-skills','Digital Skills Essentials'],['graphic-design','Graphic Design Fundamentals'],['web-development','Web Development Foundations'],
        ['programming','Programming Fundamentals'],['ai','AI & Prompt Engineering'],['digital-marketing','SEO & Digital Marketing'],['freelancing','Freelancing & Career Skills'],['business','Business Computer Skills'],
        ['advanced-tech','Advanced Technology'],['web-development','HTML & CSS Complete Guide'],['web-development','JavaScript Complete Guide'],['web-development','Responsive Web Design'],['web-development','Frontend Projects'],
        ['programming','PHP Web Development'],['programming','MySQL Database Development'],['programming','Python Programming'],['programming','Git & GitHub'],['programming','REST API Development'],
        ['ai','Generative AI Fundamentals'],['ai','AI Productivity Workflows'],['ai','AI API Development'],['ai','AI Automation Projects'],['digital-marketing','Content Marketing'],
        ['digital-marketing','Technical SEO'],['digital-marketing','Local SEO'],['digital-marketing','Analytics Fundamentals'],['freelancing','Freelance Web Development'],['freelancing','Freelance Design'],
        ['business','Excel for Business'],['business','Digital Office Management'],['business','Small Business Technology'],['advanced-tech','Linux Fundamentals'],['advanced-tech','Networking Fundamentals'],
        ['advanced-tech','Cloud Computing Basics'],['advanced-tech','Cybersecurity Fundamentals'],['advanced-tech','Data & Databases'],['design','Canva for Beginners'],['design','UI Design Essentials'],
        ['computer-basics','Computer Troubleshooting'],['computer-basics','Internet Safety & Privacy'],['digital-skills','Email & Cloud Productivity'],['digital-skills','Online Work Skills'],['programming','SQL Practice Lab'],
        ['web-development','Web Accessibility'],['web-development','Web Performance'],['ai','Responsible AI'],['freelancing','Portfolio & Personal Branding'],['digital-marketing','SEO Content Writing']
    ];
    $lessonSeeds = [
        'Introduction and Learning Goals','Core Concepts','Important Terms','Getting Started','Tools and Setup','Basic Workflow','Practical Example','Step-by-Step Method','Common Patterns','Best Practices',
        'Working with Real Data','Troubleshooting Basics','Mistakes to Avoid','Quality Checklist','Accessibility and Safety','Performance Tips','Organizing Your Work','Reusable Techniques','Intermediate Concepts','Practical Exercise',
        'Mini Project Part 1','Mini Project Part 2','Mini Project Part 3','Testing Your Work','Debugging Techniques','Improving Results','Real-World Use Cases','Professional Workflow','Documentation Basics','Collaboration Basics',
        'Security Considerations','Privacy Considerations','Efficiency Improvements','Choosing the Right Approach','Comparing Methods','Advanced Tips','Review and Self-Check','Practice Challenge','Project Planning','Project Build',
        'Project Testing','Project Polish','Project Deployment Basics','Portfolio Presentation','Interview Questions','Frequently Asked Questions','Further Practice','Key Takeaways','Final Assessment','Course Capstone'
    ];
    $categories = [];
    $q = $db->query('SELECT id,slug FROM learning_categories');
    foreach($q->fetchAll(PDO::FETCH_ASSOC) as $r) $categories[(string)$r['slug']] = (int)$r['id'];
    $makeCat = $db->prepare('INSERT INTO learning_categories(slug,name,description,icon,sort_order,enabled) VALUES(?,?,?,?,?,1) ON DUPLICATE KEY UPDATE name=VALUES(name),description=VALUES(description),icon=VALUES(icon),enabled=1');
    $icons=['🖥️','📄','🌐','🎨','💻','⌨️','🤖','📈','💼','🧾','🚀'];
    $catNames=['computer-basics'=>'Computer Basics','office-productivity'=>'Office & Productivity','digital-skills'=>'Internet & Digital Skills','graphic-design'=>'Graphic Design','web-development'=>'Web Development','programming'=>'Programming','ai'=>'AI & Generative AI','digital-marketing'=>'Digital Marketing & SEO','freelancing'=>'Freelancing & Career','business'=>'Business & Accounting','advanced-tech'=>'Advanced Technology'];
    foreach($catNames as $slug=>$name){$makeCat->execute([$slug,$name,'Practical lessons and projects from the SmartToolz Learning Hub.',$icons[array_search($slug,array_keys($catNames),true)%count($icons)],array_search($slug,array_keys($catNames),true)+1]);$categories[$slug]=(int)$db->query("SELECT id FROM learning_categories WHERE slug='".str_replace("'","''",$slug)."' LIMIT 1")->fetchColumn();}

    $courseHasChapter = false;
    $cols=[]; foreach($db->query("SELECT column_name,is_nullable,column_default,data_type FROM information_schema.columns WHERE table_schema=DATABASE() AND table_name='learning_chapters'")->fetchAll(PDO::FETCH_ASSOC) as $r)$cols[$r['column_name']]=$r;
    if(!$cols){$db->exec("CREATE TABLE IF NOT EXISTS learning_chapters(id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,course_id BIGINT UNSIGNED NULL,title VARCHAR(180) NOT NULL,slug VARCHAR(180) NOT NULL,description TEXT NULL,sort_order INT NOT NULL DEFAULT 1,enabled TINYINT(1) NOT NULL DEFAULT 1,created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,INDEX(course_id)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");$cols=[];foreach($db->query("SELECT column_name,is_nullable,data_type FROM information_schema.columns WHERE table_schema=DATABASE() AND table_name='learning_chapters'")->fetchAll(PDO::FETCH_ASSOC) as $r)$cols[$r['column_name']]=$r;}
    $courseCols=[];foreach($db->query("SELECT column_name FROM information_schema.columns WHERE table_schema=DATABASE() AND table_name='learning_courses'")->fetchAll(PDO::FETCH_COLUMN) as $c)$courseCols[$c]=true;
    $lessonCols=[];foreach($db->query("SELECT column_name FROM information_schema.columns WHERE table_schema=DATABASE() AND table_name='learning_lessons'")->fetchAll(PDO::FETCH_COLUMN) as $c)$lessonCols[$c]=true;
    $courseField=function(string $c)use(&$courseCols):bool{return isset($courseCols[$c]);};
    $lessonField=function(string $c)use(&$lessonCols):bool{return isset($lessonCols[$c]);};
    $chapterField=function(string $c)use(&$cols):bool{return isset($cols[$c]);};

    $findCourse=$db->prepare('SELECT id FROM learning_courses WHERE slug=? LIMIT 1');
    $courseSql='INSERT INTO learning_courses(category_id,title,slug,description,level,featured,enabled) VALUES(?,?,?,?,?,?,1)';
    $courseInsert=$courseField('category_id')?$db->prepare($courseSql):null;
    $updateCourse=$db->prepare('UPDATE learning_courses SET enabled=1 WHERE id=?');
    $stats=['courses'=>0,'chapters'=>0,'lessons'=>0];

    foreach($catalog as $index=>$c){
        [$catSlug,$title]=$c; $base=$catSlug.'-'.trim(preg_replace('/[^a-z0-9]+/i','-',strtolower($title)),'-');
        $findCourse->execute([$base]); $courseId=(int)$findCourse->fetchColumn();
        if(!$courseId){
            if($courseInsert){$courseInsert->execute([$categories[$catSlug]??null,$title,$base,'A practical SmartToolz course with step-by-step lessons, practice tasks and a capstone project.','Beginner',$index<15?1:0]);$courseId=(int)$db->lastInsertId();}
            else continue;
            $stats['courses']++;
        } else $updateCourse->execute([$courseId]);

        $chapterQ=$db->prepare('SELECT id FROM learning_chapters WHERE course_id=? AND sort_order=1 LIMIT 1');$chapterQ->execute([$courseId]);$chapterId=(int)$chapterQ->fetchColumn();
        if(!$chapterId){
            $fields=['course_id','title','slug','description','sort_order','enabled'];$vals=['?','?','?','?','1','1'];$data=[$courseId,'Complete Course Chapters',$base.'-chapter-1','Organized lessons for '.$title.'.'];
            if(!$chapterField('course_id')){$fields=['title','slug','description','sort_order','enabled'];$vals=['?','?','?','1','1'];$data=array_slice($data,1);} $sql='INSERT INTO learning_chapters('.implode(',',$fields).') VALUES('.implode(',',$vals).')';$st=$db->prepare($sql);$st->execute($data);$chapterId=(int)$db->lastInsertId();$stats['chapters']++;
        }

        $lessonCheck=$lessonField('chapter_id')?$db->prepare('SELECT id FROM learning_lessons WHERE chapter_id=? AND sort_order=? LIMIT 1'):$db->prepare('SELECT id FROM learning_lessons WHERE course_id=? AND sort_order=? LIMIT 1');
        foreach($lessonSeeds as $n=>$seed){$order=$n+1;$lessonCheck->execute([$lessonField('chapter_id')?$chapterId:$courseId,$order]);if($lessonCheck->fetchColumn())continue;$ltitle=$seed.' — '.$title;$slug=$base.'-lesson-'.$order;$safe=htmlspecialchars($ltitle,ENT_QUOTES,'UTF-8');$content='<div class="lh-prose"><p><strong>'.$safe.'</strong> is an original SmartToolz Learning Hub lesson designed for practical learning.</p><h2>Overview</h2><p>This lesson introduces the topic in clear language, explains why it matters, and connects the idea to practical computer or technology work.</p><h2>Learning objectives</h2><ul><li>Understand the main concept and terminology.</li><li>Follow a repeatable workflow.</li><li>Apply the idea to a small real-world task.</li></ul><h2>Step-by-step</h2><ol><li>Identify the goal and required inputs.</li><li>Review the concept before changing anything.</li><li>Follow the workflow one step at a time.</li><li>Test the result and compare it with the expected outcome.</li></ol><h2>Practical example</h2><p>Choose a small example related to this topic. Change one variable at a time, observe the result, and write down what happened.</p><h2>Practice task</h2><p>Create a small exercise for yourself based on this lesson. Complete it without copying the steps, then explain the result in your own words.</p><h2>Common mistakes</h2><p>Skipping prerequisites, changing too many things at once, ignoring errors, or moving on without checking the result can make learning harder.</p><h2>Quality checklist</h2><ul><li>Can you explain the concept?</li><li>Can you demonstrate it?</li><li>Can you recognize a common mistake?</li><li>Can you complete the practice task independently?</li></ul><h2>Quick check</h2><p>Write one sentence explaining the core idea and one practical example you could use in everyday work.</p><h2>Continue</h2><p>Complete this lesson, review your notes, and continue to the next lesson in the course.</p></div>';
            if($lessonField('chapter_id')){$ins=$db->prepare('INSERT INTO learning_lessons(chapter_id,title,slug,content,sort_order,enabled) VALUES(?,?,?,?,?,1)');$ins->execute([$chapterId,$ltitle,$slug,$content,$order]);}
            elseif($lessonField('course_id')){$ins=$db->prepare('INSERT INTO learning_lessons(course_id,title,slug,content,sort_order,enabled) VALUES(?,?,?,?,?,1)');$ins->execute([$courseId,$ltitle,$slug,$content,$order]);}
            else continue; $stats['lessons']++;
        }
    }
    return $stats;
}

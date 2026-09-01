<?php
declare(strict_types=1);

/*
 * SmartToolz Learning Hub: 50 courses x 50 lessons = 2,500 lesson records.
 * Original curriculum generator. Run once from the admin installer; it is
 * versioned so it will not duplicate records on subsequent runs.
 */
function lh_master_seed_2500(PDO $db): array {
    $version='lh-2500-v1';
    $db->exec("CREATE TABLE IF NOT EXISTS learning_seed_meta(id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,seed_key VARCHAR(120) NOT NULL UNIQUE,seeded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    $q=$db->prepare('SELECT id FROM learning_seed_meta WHERE seed_key=? LIMIT 1');$q->execute([$version]);
    if($q->fetchColumn()) return ['status'=>'already_seeded','courses'=>50,'lessons'=>2500];

    $catalog=[
      ['computer-basics','Computer Basics',['Computer Basics Mastery','Windows Productivity Essentials','Computer Hardware Essentials','Digital File Management','Computer Troubleshooting Foundations']],
      ['office-productivity','Office & Productivity',['Microsoft Word Practical Course','Microsoft Excel Practical Course','Microsoft PowerPoint Practical Course','Google Workspace Essentials','Office Automation Foundations']],
      ['digital-skills','Internet & Digital Skills',['Internet Fundamentals','Email Productivity Skills','Cloud Storage Essentials','Digital Safety Fundamentals','Online Work Essentials']],
      ['design','Graphic Design',['Graphic Design Foundations','Canva Design Mastery','Social Media Design','Thumbnail Design','Visual Branding Essentials']],
      ['web-development','Web Development',['HTML Complete Foundations','CSS Complete Foundations','Responsive Web Design','JavaScript Web Essentials','Frontend Development Foundations']],
      ['programming','Programming',['Python Programming Foundations','PHP Programming Foundations','MySQL Database Foundations','Programming Logic & Algorithms','API Development Foundations']],
      ['ai','AI & Generative AI',['Generative AI Foundations','Prompt Engineering Practical Course','AI Productivity Workflows','AI API Foundations','Responsible AI Essentials']],
      ['digital-marketing','Digital Marketing & SEO',['SEO Foundations','Keyword Research Practical Course','Content Marketing Foundations','Social Media Marketing','Web Analytics Foundations']],
      ['freelancing','Freelancing & Career',['Freelancing Foundations','Portfolio Building','Client Communication','Remote Work Skills','Career Development Essentials']],
      ['advanced-tech','Advanced Technology',['Git & GitHub Foundations','Linux Essentials','Networking Foundations','Cloud Computing Foundations','Cybersecurity Fundamentals']],
    ];
    $modules=[
      ['Foundations','Core ideas, vocabulary and the purpose of the topic.'],
      ['Concepts','Important concepts and how they connect.'],
      ['Tools','Common tools, interfaces and workflows.'],
      ['Practical Skills','Step-by-step tasks and real-world application.'],
      ['Best Practices','Quality, accessibility, safety and maintainability.'],
      ['Troubleshooting','Common errors, diagnosis and recovery.'],
      ['Projects','Small projects that combine several concepts.'],
      ['Review','Revision, comparisons and self-check activities.'],
      ['Career Use','How the skill appears in practical work.'],
      ['Final Practice','Capstone tasks and a personal checklist.'],
    ];
    $lessonKinds=['Introduction','Key Terms','How It Works','Core Elements','Common Patterns'];

    $catQ=$db->prepare('INSERT INTO learning_categories(slug,name,description,icon,sort_order,enabled) VALUES(?,?,?,?,?,1) ON DUPLICATE KEY UPDATE name=VALUES(name),description=VALUES(description),icon=VALUES(icon),sort_order=VALUES(sort_order),enabled=1');
    $courseQ=$db->prepare('SELECT id FROM learning_courses WHERE slug=? LIMIT 1');
    $courseIns=$db->prepare('INSERT INTO learning_courses(category_id,title,slug,description,level,featured,enabled) VALUES(?,?,?,?,?,?,1)');
    $courseUp=$db->prepare('UPDATE learning_courses SET category_id=?,title=?,description=?,level=?,featured=?,enabled=1 WHERE id=?');
    $lessonQ=$db->prepare('SELECT id FROM learning_lessons WHERE course_id=? AND slug=? LIMIT 1');
    $lessonIns=$db->prepare('INSERT INTO learning_lessons(course_id,title,slug,content,sort_order,enabled) VALUES(?,?,?,?,?,1)');

    $icons=['🖥️','📄','🌐','🎨','💻','⌨️','🤖','📈','💼','🚀'];
    $courseCount=0;$lessonCount=0;
    $db->beginTransaction();
    try {
      foreach($catalog as $ci=>$group){
        [$catSlug,$catName,$courseNames]=$group;
        $catQ->execute([$catSlug,$catName,$catName.' practical learning paths for SmartToolz users.',$icons[$ci],($ci+1)*10]);
        $catId=(int)$db->query("SELECT id FROM learning_categories WHERE slug=".$db->quote($catSlug)." LIMIT 1")->fetchColumn();
        foreach($courseNames as $courseIndex=>$courseName){
          $courseSlug=trim(preg_replace('/[^a-z0-9]+/','-',strtolower($courseName)),'-');
          $courseQ->execute([$courseSlug]);$courseId=(int)$courseQ->fetchColumn();
          $desc='A practical SmartToolz course covering '.$courseName.' from foundations through hands-on practice.';
          if(!$courseId){$courseIns->execute([$catId,$courseName,$courseSlug,$desc,$courseIndex>2?'Intermediate':'Beginner',$courseIndex===0?1:0]);$courseId=(int)$db->lastInsertId();}
          else {$courseUp->execute([$catId,$courseName,$desc,$courseIndex>2?'Intermediate':'Beginner',$courseIndex===0?1:0,$courseId]);}
          $courseCount++;
          for($m=0;$m<10;$m++){
            [$module,$moduleDesc]=$modules[$m];
            for($k=0;$k<5;$k++){
              $n=$m*5+$k+1;$kind=$lessonKinds[$k];
              $title=$module.' '.$kind.' — '.$courseName;
              $slug=$courseSlug.'-lesson-'.$n;
              $focus=$courseName.' in the '.$module.' stage';
              $content='<article class="lh-prose">'
                .'<p>This lesson is part of <strong>'.htmlspecialchars($courseName,ENT_QUOTES,'UTF-8').'</strong> and focuses on <strong>'.htmlspecialchars($focus,ENT_QUOTES,'UTF-8').'</strong>.</p>'
                .'<h2>Learning goal</h2><p>'.htmlspecialchars($moduleDesc,ENT_QUOTES,'UTF-8').' You will connect the idea to a practical workflow and record what you learned.</p>'
                .'<h2>Concept</h2><p>The most useful way to learn this topic is to understand the purpose first, then identify the inputs, the process, the expected result, and the checks that tell you whether the result is correct. In '.$courseName.', this lesson builds that mental model one step at a time.</p>'
                .'<h2>Step by step</h2><ol><li>Define the outcome you want before starting.</li><li>Identify the tools, settings or concepts needed for the task.</li><li>Complete the smallest useful version of the task.</li><li>Test the result and compare it with your expectation.</li><li>Improve the result using one change at a time.</li></ol>'
                .'<h2>Practical example</h2><p>Choose a small real-world task related to '.$courseName.'. Write down the starting state, perform the task, and save the result. Then repeat the task with one controlled change so you can explain what changed and why.</p>'
                .'<div class="alert alert-primary"><strong>Practice:</strong> Create your own example for this lesson. Explain the goal, the steps you followed, the result you got, and one improvement you would make next.</div>'
                .'<h2>Common mistakes</h2><ul><li>Skipping the basic concept and copying steps without understanding.</li><li>Changing several variables at once, which makes troubleshooting difficult.</li><li>Not checking the final output against the original goal.</li><li>Ignoring accessibility, safety, privacy or maintainability where they apply.</li></ul>'
                .'<h2>Quick self-check</h2><p>Can you explain the main idea in your own words, demonstrate a small example, and describe what you would check when the result is wrong?</p>'
                .'<h2>Useful checklist</h2><ul><li>Goal is clear.</li><li>Inputs are correct.</li><li>Steps are repeatable.</li><li>Result has been tested.</li><li>Notes are saved for later review.</li></ul>'
                .'<h2>Continue learning</h2><p>Complete the practice task, mark this lesson complete, and continue to the next lesson in the course. When a matching SmartToolz utility exists, use it for hands-on practice.</p>'
                .'</article>';
              $lessonQ->execute([$courseId,$slug]);$lessonId=(int)$lessonQ->fetchColumn();
              if($lessonId){$u=$db->prepare('UPDATE learning_lessons SET title=?,content=?,sort_order=?,enabled=1 WHERE id=?');$u->execute([$title,$content,$n,$lessonId]);}
              else {$lessonIns->execute([$courseId,$title,$slug,$content,$n]);}
              $lessonCount++;
            }
          }
        }
      }
      $q=$db->prepare('INSERT INTO learning_seed_meta(seed_key) VALUES(?)');$q->execute([$version]);
      $db->commit();
      return ['status'=>'seeded','courses'=>$courseCount,'lessons'=>$lessonCount];
    } catch(Throwable $e) {
      if($db->inTransaction())$db->rollBack();
      error_log('Learning Hub 2500 seed: '.$e->getMessage());
      return ['status'=>'error','courses'=>$courseCount,'lessons'=>$lessonCount,'error'=>$e->getMessage()];
    }
}

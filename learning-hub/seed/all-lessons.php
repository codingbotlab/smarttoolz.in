<?php
declare(strict_types=1);
function lh_seed_all_lessons(PDO $db): void {
 static $done=false;if($done)return;$done=true;
 $map=[
 'computer-basics-for-beginners'=>['What Is a Computer?','Hardware and Software','Files, Folders and Storage','Using Windows Efficiently','Computer Safety Basics'],
 'ms-office-productivity-basics'=>['Introduction to Word','Excel Fundamentals','Excel for Everyday Work','PowerPoint Basics','Build a Small Office Project'],
 'internet-and-digital-skills'=>['How the Internet Works','Email Essentials','Cloud Storage Basics','Online Forms and Services','Privacy and Digital Safety'],
 'graphic-design-with-canva'=>['Design Principles for Beginners','Getting Started with Canva','Create a Social Media Graphic','Thumbnail Design Basics','Mini Design Project'],
 'html-css-for-beginners'=>['What Is HTML?','HTML Text, Links and Images','CSS Fundamentals','Layout with Flexbox and Grid','Build a Responsive Web Page'],
 'javascript-foundations'=>['JavaScript and the Browser','Variables and Data Types','Conditions and Loops','Functions and Events','Mini Interactive Project'],
 'php-mysql-for-beginners'=>['PHP and Server-Side Programming','Variables, Arrays and Forms','Connecting PHP to MySQL','CRUD Fundamentals','Build a Small PHP App'],
 'ai-and-prompt-engineering'=>['What Is Generative AI?','Prompt Fundamentals','Structured Prompts','Evaluating AI Answers','Responsible AI Use'],
 'seo-fundamentals'=>['How Search Engines Discover Pages','Search Intent and Keywords','On-Page SEO Basics','Technical SEO Essentials','Build a Useful SEO Content Plan'],
 'freelancing-career-foundations'=>['What Is Freelancing?','Choose a Service and Niche','Build a Portfolio','Client Communication','Your First Freelance Workflow'],
 'git-and-github-foundations'=>['Why Version Control Matters','Repositories and Commits','Branches and Merging','GitHub Basics','Practical Project Workflow']
 ];
 $find=$db->prepare('SELECT id FROM learning_courses WHERE slug=? LIMIT 1');
 $check=$db->prepare('SELECT id FROM learning_lessons WHERE course_id=? AND sort_order=? LIMIT 1');
 $add=$db->prepare('INSERT INTO learning_lessons(course_id,title,slug,content,sort_order,enabled) VALUES(?,?,?,?,?,1)');
 foreach($map as $courseSlug=>$titles){$find->execute([$courseSlug]);$cid=(int)$find->fetchColumn();if(!$cid)continue;foreach($titles as $i=>$title){$n=$i+1;$check->execute([$cid,$n]);if($check->fetchColumn())continue;$slug=strtolower(trim(preg_replace('/[^a-z0-9]+/i','-',str_replace(['&','?'], '',$title)),'-'));$safe=htmlspecialchars($title,ENT_QUOTES,'UTF-8');$html='<article class="lh-prose"><h2>'.$safe.'</h2><p>Learn '.$safe.' through a practical SmartToolz lesson with clear explanations and examples.</p><h3>What you will learn</h3><ul><li>Understand the core concept.</li><li>Follow a practical step-by-step workflow.</li><li>Apply the skill to a small real-world task.</li></ul><h3>Practice</h3><p>Try the concept yourself, change one part, and observe the result. Note what you learned.</p><h3>Common mistakes</h3><p>Do not skip the fundamentals or copy steps without understanding why they work. Test your work before continuing.</p><h3>Quick check</h3><p>Explain the main idea in your own words and give one practical example.</p></article>';$add->execute([$cid,$title,$slug,$html,$n]);}}
}

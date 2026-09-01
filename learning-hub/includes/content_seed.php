<?php
declare(strict_types=1);

/* Initial, editable Learning Hub curriculum. Content is inserted only when the course/lesson slug does not exist. */
function lh_seed_content(PDO $db): void {
    static $seeded = false;
    if ($seeded) return;
    $seeded = true;

    $categories = [
        'computer-basics' => ['Computer Basics','Start using computers confidently: hardware, software, files, Windows, internet and safe digital habits.','🖥️',10],
        'office-productivity' => ['Office & Productivity','Practical Word, Excel, PowerPoint and everyday productivity skills.','📄',20],
        'digital-skills' => ['Internet & Digital Skills','Useful internet, email, cloud, privacy and online-work skills.','🌐',30],
        'design' => ['Graphic Design','Learn practical visual design, Canva, thumbnails and basic image workflows.','🎨',40],
        'web-development' => ['Web Development','Build websites from HTML and CSS to JavaScript and responsive layouts.','💻',50],
        'programming' => ['Programming','Programming fundamentals, PHP, databases, Git and APIs.','⌨️',60],
        'ai' => ['AI & Generative AI','Understand modern AI, prompting, AI tools, APIs and responsible use.','🤖',70],
        'digital-marketing' => ['Digital Marketing & SEO','Learn SEO, content, social media and analytics fundamentals.','📈',80],
        'freelancing' => ['Freelancing & Career','Build a portfolio, find work, communicate with clients and work professionally.','💼',90],
        'business' => ['Business & Accounting','Computer skills for small businesses, spreadsheets, invoices and basic accounting workflows.','🧾',100],
        'advanced-tech' => ['Advanced Technology','Explore Linux, networking, cloud, security and modern software concepts.','🚀',110],
    ];
    try {
        $cat = $db->prepare('SELECT id FROM learning_categories WHERE slug=? LIMIT 1');
        foreach ($categories as $slug => $v) {
            $cat->execute([$slug]);
            if (!$cat->fetchColumn()) $db->prepare('INSERT IGNORE INTO learning_categories(slug,name,description,icon,sort_order) VALUES(?,?,?,?,?)')->execute([$slug,$v[0],$v[1],$v[2],$v[3]]);
        }

        $courses = [
          ['computer-basics','computer-basics-for-beginners','Computer Basics for Beginners','Learn the essentials of using a computer, managing files and working safely online.','Beginner',1],
          ['office-productivity','ms-office-productivity-basics','MS Office & Productivity Basics','Build practical skills with Word, Excel, PowerPoint and everyday productivity workflows.','Beginner',1],
          ['digital-skills','internet-and-digital-skills','Internet & Digital Skills','Learn browsers, email, cloud storage, online forms, privacy and safe digital habits.','Beginner',0],
          ['design','graphic-design-with-canva','Graphic Design with Canva','Learn the principles behind clean graphics, social posts and useful thumbnails.','Beginner',1],
          ['web-development','html-css-for-beginners','HTML & CSS for Beginners','Build your first responsive web pages with semantic HTML and modern CSS.','Beginner',1],
          ['web-development','javascript-foundations','JavaScript Foundations','Learn JavaScript fundamentals and add interaction to web pages.','Beginner',0],
          ['programming','php-mysql-for-beginners','PHP & MySQL for Beginners','Understand server-side PHP, forms, databases and practical CRUD applications.','Intermediate',1],
          ['ai','ai-and-prompt-engineering','AI & Prompt Engineering','Understand generative AI and write clearer prompts for useful, responsible results.','Beginner',1],
          ['digital-marketing','seo-fundamentals','SEO Fundamentals','Learn how search engines discover content and how to build useful, search-friendly pages.','Beginner',1],
          ['freelancing','freelancing-career-foundations','Freelancing & Career Foundations','Create a professional profile, portfolio and client workflow for online work.','Beginner',1],
          ['advanced-tech','git-and-github-foundations','Git & GitHub Foundations','Learn version control, repositories, branches, commits and collaborative workflows.','Intermediate',0],
        ];

        $findCat=$db->prepare('SELECT id FROM learning_categories WHERE slug=? LIMIT 1');
        $findCourse=$db->prepare('SELECT id FROM learning_courses WHERE slug=? LIMIT 1');
        $addCourse=$db->prepare('INSERT INTO learning_courses(category_id,title,slug,description,level,featured,enabled) VALUES(?,?,?,?,?,?,1)');
        $findLesson=$db->prepare('SELECT id FROM learning_lessons WHERE course_id=? AND slug=? LIMIT 1');
        $addLesson=$db->prepare('INSERT INTO learning_lessons(course_id,title,slug,content,sort_order,enabled) VALUES(?,?,?,?,?,1)');

        $lessonTemplates = [
          'computer-basics-for-beginners' => [
            ['What Is a Computer?','what-is-a-computer','Understand what a computer does, the difference between input, processing, storage and output, and where computers are used in everyday life.','1'],
            ['Hardware and Software','hardware-and-software','Learn the role of the CPU, RAM, storage, keyboard, mouse and display, then compare hardware with software using simple examples.','2'],
            ['Files, Folders and Storage','files-folders-storage','Learn how files and folders are organized, how extensions work, and how to copy, move, rename and safely delete files.','3'],
            ['Using Windows Efficiently','using-windows-efficiently','Practice common desktop actions, search, settings, screenshots, keyboard shortcuts and basic troubleshooting habits.','4'],
            ['Computer Safety Basics','computer-safety-basics','Learn strong passwords, updates, backups, suspicious links, downloads and other everyday habits that reduce digital risk.','5'],
          ],
          'ms-office-productivity-basics' => [
            ['Introduction to Word','introduction-to-word','Learn the Word interface, documents, formatting, headings, lists and page setup.','1'],
            ['Excel Fundamentals','excel-fundamentals','Understand cells, rows, columns, formulas, basic functions and simple tables in Excel.','2'],
            ['Excel for Everyday Work','excel-for-everyday-work','Use sorting, filtering, simple charts and useful formulas to organize everyday data.','3'],
            ['PowerPoint Basics','powerpoint-basics','Create clear presentations using layouts, text, images, simple charts and consistent formatting.','4'],
            ['Build a Small Office Project','small-office-project','Combine Word, Excel and PowerPoint skills into a practical mini project for a small business or student assignment.','5'],
          ],
          'internet-and-digital-skills' => [
            ['How the Internet Works','how-the-internet-works','Learn the basic ideas behind websites, browsers, servers, URLs, domains and internet connections without unnecessary jargon.','1'],
            ['Email Essentials','email-essentials','Learn professional email basics, attachments, folders, search, signatures and common email safety checks.','2'],
            ['Cloud Storage Basics','cloud-storage-basics','Understand cloud storage and learn practical file organization, sharing and backup habits.','3'],
            ['Online Forms and Services','online-forms-and-services','Learn how to complete online forms carefully, verify information and keep copies of important submissions.','4'],
            ['Privacy and Digital Safety','privacy-and-digital-safety','Understand permissions, phishing, public Wi-Fi, account security and practical privacy habits.','5'],
          ],
          'graphic-design-with-canva' => [
            ['Design Principles for Beginners','design-principles','Learn hierarchy, alignment, contrast, spacing and consistency—the building blocks of clear visual communication.','1'],
            ['Getting Started with Canva','getting-started-with-canva','Understand templates, dimensions, elements, text and exporting in Canva.','2'],
            ['Create a Social Media Graphic','create-social-media-graphic','Build a simple social post using a clear message, readable typography and balanced spacing.','3'],
            ['Thumbnail Design Basics','thumbnail-design-basics','Learn how to create a readable, honest thumbnail with strong hierarchy and a focused visual.','4'],
            ['Mini Design Project','mini-design-project','Create a small three-piece visual set while keeping typography, spacing and branding consistent.','5'],
          ],
          'html-css-for-beginners' => [
            ['What Is HTML?','what-is-html','Learn the purpose of HTML, elements, attributes and the basic structure of a web document.','1'],
            ['HTML Text, Links and Images','html-text-links-images','Build content using headings, paragraphs, lists, links and accessible images.','2'],
            ['CSS Fundamentals','css-fundamentals','Learn selectors, properties, values, the cascade, colors, spacing and typography.','3'],
            ['Layout with Flexbox and Grid','flexbox-and-grid','Understand modern CSS layout with Flexbox and Grid and when each approach is useful.','4'],
            ['Build a Responsive Web Page','responsive-web-page','Combine HTML and CSS into a responsive page that works well on phones and desktops.','5'],
          ],
          'javascript-foundations' => [
            ['JavaScript and the Browser','javascript-and-browser','Understand what JavaScript does in a web page and how scripts interact with browser content.','1'],
            ['Variables and Data Types','variables-and-data-types','Learn variables, strings, numbers, booleans, arrays and basic operators.','2'],
            ['Conditions and Loops','conditions-and-loops','Use if statements and loops to make programs respond to different situations.','3'],
            ['Functions and Events','functions-and-events','Create reusable functions and respond to user actions such as clicks and input changes.','4'],
            ['Mini Interactive Project','mini-interactive-project','Build a small browser interaction that combines variables, functions, events and DOM updates.','5'],
          ],
          'php-mysql-for-beginners' => [
            ['PHP and Server-Side Programming','php-server-side-programming','Understand where PHP runs, how requests work and how dynamic HTML can be generated.','1'],
            ['Variables, Arrays and Forms','php-variables-arrays-forms','Learn PHP variables, arrays, conditionals and safe handling of basic form input.','2'],
            ['Connecting PHP to MySQL','connecting-php-to-mysql','Understand databases, tables, connections, prepared statements and why parameterized queries matter.','3'],
            ['CRUD Fundamentals','crud-fundamentals','Learn the create, read, update and delete pattern used by many practical web applications.','4'],
            ['Build a Small PHP App','small-php-app','Plan and build a simple database-backed application using reusable PHP components and basic validation.','5'],
          ],
          'ai-and-prompt-engineering' => [
            ['What Is Generative AI?','what-is-generative-ai','Learn the basic idea of generative AI, language models, multimodal systems and common practical uses.','1'],
            ['Prompt Fundamentals','prompt-fundamentals','Learn how context, goal, constraints, examples and output format can improve an AI request.','2'],
            ['Structured Prompts','structured-prompts','Create repeatable prompts with roles, inputs, rules, examples and clearly defined outputs.','3'],
            ['Evaluating AI Answers','evaluating-ai-answers','Learn to check factual claims, assumptions, calculations, citations and important missing context.','4'],
            ['Responsible AI Use','responsible-ai-use','Understand privacy, sensitive information, copyright considerations, bias and human review when using AI.','5'],
          ],
          'seo-fundamentals' => [
            ['How Search Engines Discover Pages','how-search-engines-discover-pages','Learn crawling, indexing and ranking at a high level and why useful content matters.','1'],
            ['Search Intent and Keywords','search-intent-and-keywords','Understand search intent and use topic research to create pages that answer real questions.','2'],
            ['On-Page SEO Basics','on-page-seo-basics','Learn titles, headings, URLs, internal links, images and descriptive page content.','3'],
            ['Technical SEO Essentials','technical-seo-essentials','Understand mobile usability, performance, crawlability, sitemaps and basic structured data concepts.','4'],
            ['Build a Useful SEO Content Plan','seo-content-plan','Create a practical content plan focused on user needs, topical coverage and measurable improvement.','5'],
          ],
          'freelancing-career-foundations' => [
            ['What Is Freelancing?','what-is-freelancing','Understand how freelance work operates, common service models and what clients usually expect.','1'],
            ['Choose a Service and Niche','choose-service-and-niche','Turn your existing skills into a focused service offer that is easier for clients to understand.','2'],
            ['Build a Portfolio','build-a-portfolio','Create practical sample work and case-study style portfolio pages that demonstrate your process and results.','3'],
            ['Client Communication','client-communication','Learn how to clarify requirements, write professional messages, document decisions and handle revisions.','4'],
            ['Your First Freelance Workflow','first-freelance-workflow','Build a repeatable workflow from inquiry to proposal, delivery, feedback and final handoff.','5'],
          ],
          'git-and-github-foundations' => [
            ['Why Version Control Matters','why-version-control-matters','Learn why developers track changes, how version control helps recovery and how Git fits into a project.','1'],
            ['Repositories and Commits','repositories-and-commits','Understand repositories, working trees, staging and commits with a simple practical workflow.','2'],
            ['Branches and Merging','branches-and-merging','Learn why branches are used and how changes can be combined safely.','3'],
            ['GitHub Basics','github-basics','Understand remote repositories, pushing, pulling, README files and basic collaboration on GitHub.','4'],
            ['Practical Project Workflow','practical-project-workflow','Use a clean feature-to-commit workflow and write useful commit messages for a small project.','5'],
          ],
        ];

        foreach ($courses as $c) {
            $findCat->execute([$c[0]]); $catId=(int)$findCat->fetchColumn(); if(!$catId) continue;
            $findCourse->execute([$c[2]]); $courseId=(int)$findCourse->fetchColumn();
            if (!$courseId) { $addCourse->execute([$catId,$c[2],$c[1],$c[3],$c[4],$c[5]]); $courseId=(int)$db->lastInsertId(); }
            foreach (($lessonTemplates[$c[1]] ?? []) as $l) {
                $findLesson->execute([$courseId,$l[1]]);
                if (!$findLesson->fetchColumn()) {
                    $content='<h2>'.htmlspecialchars($l[0],ENT_QUOTES,'UTF-8').'</h2><p>'.htmlspecialchars($l[2],ENT_QUOTES,'UTF-8').'</p><h3>What you will learn</h3><ul><li>Understand the core concept in simple language.</li><li>Follow a practical example.</li><li>Apply the idea in a small task.</li></ul><h3>Practice</h3><p>Write down or try one real example based on this lesson. Then review your result and identify one thing you could improve.</p><h3>Quick check</h3><p>Can you explain the main idea in your own words and give one practical example?</p><h3>Related learning</h3><p>Continue to the next lesson in this course, or explore a related SmartToolz tool when one is available.</p>';
                    $addLesson->execute([$courseId,$l[0],$l[1],$content,(int)$l[3]]);
                }
            }
        }
    } catch (Throwable $e) { error_log('Learning Hub content seed: '.$e->getMessage()); }
}

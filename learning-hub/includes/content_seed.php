<?php
declare(strict_types=1);

/* Initial editable Learning Hub curriculum. Safe to run on every request. */
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

    $lessons = [
      'computer-basics-for-beginners'=>[
        ['What Is a Computer?','what-is-a-computer','Learn what a computer is, how input, processing, storage and output work together, and where computers are used in everyday life.'],
        ['Hardware and Software','hardware-and-software','Understand CPU, RAM, storage, keyboard, mouse and display, and learn the difference between hardware and software.'],
        ['Files, Folders and Storage','files-folders-storage','Learn file extensions, folders, copying, moving, renaming, deleting and safe storage habits.'],
        ['Using Windows Efficiently','using-windows-efficiently','Practice search, settings, screenshots, keyboard shortcuts and simple troubleshooting.'],
        ['Computer Safety Basics','computer-safety-basics','Learn strong passwords, updates, backups, suspicious links, safe downloads and everyday security habits.'],
      ],
      'ms-office-productivity-basics'=>[
        ['Introduction to Word','introduction-to-word','Learn the Word interface, documents, formatting, headings, lists and page setup.'],
        ['Excel Fundamentals','excel-fundamentals','Understand cells, rows, columns, formulas, basic functions and simple tables.'],
        ['Excel for Everyday Work','excel-for-everyday-work','Use sorting, filtering, simple charts and useful formulas to organize data.'],
        ['PowerPoint Basics','powerpoint-basics','Create clear presentations using layouts, text, images, charts and consistent formatting.'],
        ['Build a Small Office Project','small-office-project','Combine Word, Excel and PowerPoint skills in a practical student or small-business project.'],
      ],
      'internet-and-digital-skills'=>[
        ['How the Internet Works','how-the-internet-works','Learn websites, browsers, servers, URLs, domains and internet connections in simple language.'],
        ['Email Essentials','email-essentials','Learn professional email, attachments, folders, search, signatures and email safety.'],
        ['Cloud Storage Basics','cloud-storage-basics','Understand cloud storage, file organization, sharing and backup habits.'],
        ['Online Forms and Services','online-forms-and-services','Learn to complete online forms carefully, verify information and keep useful records.'],
        ['Privacy and Digital Safety','privacy-and-digital-safety','Understand permissions, phishing, public Wi-Fi, account security and privacy habits.'],
      ],
      'graphic-design-with-canva'=>[
        ['Design Principles for Beginners','design-principles','Learn hierarchy, alignment, contrast, spacing and consistency—the foundations of clear visual design.'],
        ['Getting Started with Canva','getting-started-with-canva','Understand templates, dimensions, elements, text and exporting in Canva.'],
        ['Create a Social Media Graphic','create-social-media-graphic','Build a simple social post using a clear message, readable typography and balanced spacing.'],
        ['Thumbnail Design Basics','thumbnail-design-basics','Learn how to create a readable, honest thumbnail with strong visual hierarchy.'],
        ['Mini Design Project','mini-design-project','Create a three-piece visual set while keeping typography, spacing and branding consistent.'],
      ],
      'html-css-for-beginners'=>[
        ['What Is HTML?','what-is-html','Learn the purpose of HTML, elements, attributes and the basic structure of a web document.'],
        ['HTML Text, Links and Images','html-text-links-images','Build content using headings, paragraphs, lists, links and accessible images.'],
        ['CSS Fundamentals','css-fundamentals','Learn selectors, properties, values, the cascade, spacing, typography and basic styling.'],
        ['Layout with Flexbox and Grid','flexbox-and-grid','Understand modern CSS layout with Flexbox and Grid and when each is useful.'],
        ['Build a Responsive Web Page','responsive-web-page','Combine HTML and CSS into a responsive page that works on phones and desktops.'],
      ],
      'javascript-foundations'=>[
        ['JavaScript and the Browser','javascript-and-browser','Understand what JavaScript does in a web page and how scripts interact with browser content.'],
        ['Variables and Data Types','variables-and-data-types','Learn variables, strings, numbers, booleans, arrays and basic operators.'],
        ['Conditions and Loops','conditions-and-loops','Use conditions and loops to make programs respond to different situations.'],
        ['Functions and Events','functions-and-events','Create reusable functions and respond to clicks and input changes.'],
        ['Mini Interactive Project','mini-interactive-project','Build a small browser interaction combining variables, functions, events and DOM updates.'],
      ],
      'php-mysql-for-beginners'=>[
        ['PHP and Server-Side Programming','php-server-side-programming','Understand where PHP runs, how requests work and how dynamic HTML is generated.'],
        ['Variables, Arrays and Forms','php-variables-arrays-forms','Learn PHP variables, arrays, conditions and safe handling of basic form input.'],
        ['Connecting PHP to MySQL','connecting-php-to-mysql','Understand databases, connections, prepared statements and parameterized queries.'],
        ['CRUD Fundamentals','crud-fundamentals','Learn create, read, update and delete patterns used in database-backed applications.'],
        ['Build a Small PHP App','small-php-app','Plan and build a simple database-backed application with validation and reusable components.'],
      ],
      'ai-and-prompt-engineering'=>[
        ['What Is Generative AI?','what-is-generative-ai','Learn the basic ideas behind generative AI, language models and multimodal systems.'],
        ['Prompt Fundamentals','prompt-fundamentals','Learn how context, goals, constraints, examples and output formats improve AI requests.'],
        ['Structured Prompts','structured-prompts','Create repeatable prompts with roles, inputs, rules, examples and defined outputs.'],
        ['Evaluating AI Answers','evaluating-ai-answers','Learn to check factual claims, assumptions, calculations and missing context.'],
        ['Responsible AI Use','responsible-ai-use','Understand privacy, sensitive information, copyright considerations, bias and human review.'],
      ],
      'seo-fundamentals'=>[
        ['How Search Engines Discover Pages','how-search-engines-discover-pages','Learn crawling, indexing and ranking at a high level and why useful content matters.'],
        ['Search Intent and Keywords','search-intent-and-keywords','Understand search intent and use topic research to answer real user questions.'],
        ['On-Page SEO Basics','on-page-seo-basics','Learn titles, headings, URLs, internal links, images and descriptive content.'],
        ['Technical SEO Essentials','technical-seo-essentials','Understand mobile usability, performance, crawlability and sitemaps.'],
        ['Build a Useful SEO Content Plan','seo-content-plan','Create a practical content plan focused on user needs and measurable improvement.'],
      ],
      'freelancing-career-foundations'=>[
        ['What Is Freelancing?','what-is-freelancing','Understand freelance work, common service models and client expectations.'],
        ['Choose a Service and Niche','choose-service-and-niche','Turn your skills into a focused service offer that clients can understand.'],
        ['Build a Portfolio','build-a-portfolio','Create practical sample work and case-study style portfolio pages.'],
        ['Client Communication','client-communication','Learn to clarify requirements, write professional messages and handle revisions.'],
        ['Your First Freelance Workflow','first-freelance-workflow','Build a repeatable workflow from inquiry to proposal, delivery and handoff.'],
      ],
      'git-and-github-foundations'=>[
        ['Why Version Control Matters','why-version-control-matters','Learn why developers track changes and how version control helps recovery.'],
        ['Repositories and Commits','repositories-and-commits','Understand repositories, working trees, staging and commits.'],
        ['Branches and Merging','branches-and-merging','Learn why branches are used and how changes can be combined safely.'],
        ['GitHub Basics','github-basics','Understand remote repositories, pushing, pulling, README files and collaboration.'],
        ['Practical Project Workflow','practical-project-workflow','Use a clean feature-to-commit workflow and useful commit messages.'],
      ],
    ];

    try {
        $catInsert=$db->prepare('INSERT IGNORE INTO learning_categories(slug,name,description,icon,sort_order) VALUES(?,?,?,?,?)');
        foreach($categories as $slug=>$v) $catInsert->execute([$slug,$v[0],$v[1],$v[2],$v[3]]);

        $findCat=$db->prepare('SELECT id FROM learning_categories WHERE slug=? LIMIT 1');
        $findCourse=$db->prepare('SELECT id FROM learning_courses WHERE slug=? LIMIT 1');
        $addCourse=$db->prepare('INSERT INTO learning_courses(category_id,title,slug,description,level,featured,enabled) VALUES(?,?,?,?,?,?,1)');
        $findLesson=$db->prepare('SELECT id FROM learning_lessons WHERE course_id=? AND slug=? LIMIT 1');
        $addLesson=$db->prepare('INSERT INTO learning_lessons(course_id,title,slug,content,sort_order,enabled) VALUES(?,?,?,?,?,1)');

        foreach($courses as $c){
            $findCat->execute([$c[0]]); $catId=(int)$findCat->fetchColumn();
            if(!$catId) continue;
            $findCourse->execute([$c[1]]); $courseId=(int)$findCourse->fetchColumn();
            if(!$courseId){
                $addCourse->execute([$catId,$c[2],$c[1],$c[3],$c[4],$c[5]]);
                $courseId=(int)$db->lastInsertId();
            }
            foreach(($lessons[$c[1]]??[]) as $i=>$l){
                $findLesson->execute([$courseId,$l[1]]);
                if($findLesson->fetchColumn()) continue;
                $title=htmlspecialchars($l[0],ENT_QUOTES,'UTF-8');
                $desc=htmlspecialchars($l[2],ENT_QUOTES,'UTF-8');
                $content='<article><p>'.$desc.'</p><h2>What you will learn</h2><ul><li>Understand the core concept in simple language.</li><li>Follow a practical example.</li><li>Apply the idea in a small real-world task.</li></ul><h2>Step by step</h2><p>Start with the concept, try the example, then change one part and observe what happens. Keep notes of anything that is unclear and revisit the previous lesson when needed.</p><h2>Practice task</h2><p>Try one practical example based on this lesson. Explain what you did, what result you expected and what result you actually received.</p><h2>Common mistakes</h2><p>Do not rush to memorize steps. Focus on understanding why each step is needed, check your inputs and review the result before moving on.</p><h2>Quick check</h2><p>Can you explain the main idea in your own words and give one practical example?</p><h2>Continue learning</h2><p>Complete this lesson, then continue to the next lesson in the course. Explore a relevant SmartToolz utility when one is available for hands-on practice.</p></article>';
                $addLesson->execute([$courseId,$l[0],$l[1],$content,$i+1]);
            }
        }
    }catch(Throwable $e){ error_log('Learning Hub content seed: '.$e->getMessage()); }
}

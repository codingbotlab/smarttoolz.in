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
                $desc=htmlspecialchars($l[2],ENT_QUOTES,'UTF-8');
                $content='<article><p>'.$desc.'</p><h2>What you will learn</h2><ul><li>Understand the core concept in simple language.</li><li>Follow a practical example.</li><li>Apply the idea in a small real-world task.</li></ul><h2>Step by step</h2><p>Start with the concept, try the example, then change one part and observe what happens. Keep notes of anything that is unclear and revisit the previous lesson when needed.</p><h2>Practice task</h2><p>Try one practical example based on this lesson. Explain what you did, what result you expected and what result you actually received.</p><h2>Common mistakes</h2><p>Do not rush to memorize steps. Focus on understanding why each step is needed, check your inputs and review the result before moving on.</p><h2>Quick check</h2><p>Can you explain the main idea in your own words and give one practical example?</p><h2>Continue learning</h2><p>Complete this lesson, then continue to the next lesson in the course. Explore a relevant SmartToolz utility when one is available for hands-on practice.</p></article>';
                $addLesson->execute([$courseId,$l[0],$l[1],$content,$i+1]);
            }
        }

        /* Real, topic-specific content for the first Computer Basics lesson. */
        $realLesson = <<<'HTML'
<article class="lh-lesson-article">
  <p class="lead">A computer is an electronic machine that takes input, processes it using instructions, stores information, and produces an output. Almost every app you use follows this same basic cycle.</p>

  <h2>Learning objectives</h2>
  <ul>
    <li>Explain what a computer does using the input-process-storage-output model.</li>
    <li>Recognize the main roles of the CPU, RAM, storage, input devices and output devices.</li>
    <li>Relate everyday tasks such as opening a photo or saving a document to the computer's basic working cycle.</li>
    <li>Use the right terms when describing a computer to another person.</li>
  </ul>

  <h2>What is a computer?</h2>
  <p>A computer is a programmable electronic system that works with data according to instructions. The instruction may come from software, a web application, a document editor, a game, or the operating system.</p>
  <p>Think about writing a document. You press a key, the computer receives that input, software decides what should happen, the processor performs the necessary work, and the result appears on the screen. When you save the document, the information is written to storage so it can be opened later.</p>

  <div class="lh-callout lh-callout-info"><strong>Simple model:</strong> Input → Processing → Storage/Memory → Output. The parts work together; a computer is not just the CPU sitting inside the case.</div>

  <h2>1. Input</h2>
  <p>Input is information sent to the computer. Common input devices include a keyboard, mouse, microphone, camera, scanner and touchscreen. A typed word, mouse click, scanned page or recorded sound can all become digital input.</p>

  <h2>2. Processing</h2>
  <p>Processing is the work performed on the input. The <strong>CPU (Central Processing Unit)</strong> executes instructions and performs calculations and logical operations. Modern computers can perform millions or billions of operations in a short time.</p>

  <h2>3. Memory and storage</h2>
  <p><strong>RAM</strong> is fast working memory used by programs that are currently running. It is temporary: when power is removed, its contents are lost. <strong>Storage</strong>, such as an SSD or hard drive, keeps files and programs for the long term.</p>
  <p>That is why adding more RAM can help when many applications are open, while replacing a slow hard drive with an SSD can improve startup and file-access times. They solve different problems.</p>

  <table class="table table-bordered align-middle">
    <thead><tr><th>Part</th><th>Main role</th><th>Example</th></tr></thead>
    <tbody>
      <tr><td>CPU</td><td>Executes instructions and processes data</td><td>Runs calculations for an application</td></tr>
      <tr><td>RAM</td><td>Holds data and programs currently in use</td><td>Keeps an open browser and document ready</td></tr>
      <tr><td>SSD / HDD</td><td>Stores data long term</td><td>Photos, documents and installed software</td></tr>
      <tr><td>Keyboard / Mouse</td><td>Provides user input</td><td>Typing and clicking</td></tr>
      <tr><td>Monitor / Speakers</td><td>Presents output</td><td>Shows a page or plays audio</td></tr>
    </tbody>
  </table>

  <h2>4. Output</h2>
  <p>Output is the result the computer presents to you. A monitor displays text and images; speakers produce sound; a printer creates a paper copy. Output can also be sent to another application, device or network service.</p>

  <h2>Practical example: opening a photo</h2>
  <ol>
    <li>You double-click the photo. That click is <strong>input</strong>.</li>
    <li>The operating system identifies the image file and starts a suitable viewer. This involves <strong>processing</strong>.</li>
    <li>The file is read from <strong>storage</strong> and working data is placed in <strong>RAM</strong>.</li>
    <li>The CPU and graphics hardware prepare the image for display.</li>
    <li>The monitor shows the photo as the final <strong>output</strong>.</li>
  </ol>

  <h2>Why this model matters</h2>
  <p>This model helps you troubleshoot problems. A computer that cannot receive input may have a disconnected keyboard or faulty touchpad. A computer that freezes while many programs are running may be low on available memory or processor capacity. A computer that can run programs but cannot find a saved file may have a storage, file-path or permissions problem.</p>

  <div class="lh-callout lh-callout-tip"><strong>SmartTip:</strong> When solving a computer problem, ask four questions: What went in? What should the computer have processed? Where should the data be stored? What output did I expect?</div>

  <h2>Practice task</h2>
  <p>Choose one everyday action such as opening YouTube, saving a Word document, connecting a USB drive, or printing a file. Write four short lines describing its input, processing, storage or memory use, and output.</p>

  <h2>Common mistakes</h2>
  <ul>
    <li>Thinking RAM and storage are the same thing.</li>
    <li>Assuming a faster CPU automatically fixes every slow-computer problem.</li>
    <li>Ignoring the role of software and the operating system.</li>
    <li>Calling every component “the processor” even when you are describing RAM, storage or a peripheral.</li>
  </ul>

  <h2>Quick check</h2>
  <ol>
    <li>What is the difference between RAM and storage?</li>
    <li>Which part executes instructions?</li>
    <li>Give one example of input and one example of output.</li>
  </ol>

  <h2>Continue learning</h2>
  <p>Next, study <strong>Hardware and Software</strong>. You will identify the physical components of a computer and understand how software tells those components what to do.</p>
</article>
HTML;

        $findReal=$db->prepare('SELECT l.id FROM learning_lessons l JOIN learning_courses c ON c.id=l.course_id WHERE c.slug=? AND l.slug=? LIMIT 1');
        $findReal->execute(['computer-basics-for-beginners','what-is-a-computer']);
        $realId=(int)$findReal->fetchColumn();
        if($realId){
            $updateReal=$db->prepare('UPDATE learning_lessons SET title=?, content=?, sort_order=1, enabled=1 WHERE id=?');
            $updateReal->execute(['What Is a Computer?',$realLesson,$realId]);
        }
    }catch(Throwable $e){ error_log('Learning Hub content seed: '.$e->getMessage()); }
}

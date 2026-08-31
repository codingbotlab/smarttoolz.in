-- SMARTTOOLZ LEARNING HUB — ALL LANGUAGES
-- Import into the SAME MySQL database used by Creator AI.
-- Existing creator_users/users are NOT replaced.
SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS learning_courses (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 slug VARCHAR(120) NOT NULL UNIQUE,
 language_name VARCHAR(80) NOT NULL,
 title VARCHAR(200) NOT NULL,
 description TEXT NOT NULL,
 level VARCHAR(80) NOT NULL DEFAULT 'Beginner',
 icon VARCHAR(40) NOT NULL DEFAULT '💻',
 color_a VARCHAR(20) NOT NULL DEFAULT '#8b5cf6',
 color_b VARCHAR(20) NOT NULL DEFAULT '#22d3ee',
 is_published TINYINT(1) NOT NULL DEFAULT 1,
 sort_order INT NOT NULL DEFAULT 0,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS learning_modules (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 course_id BIGINT UNSIGNED NOT NULL,
 title VARCHAR(200) NOT NULL,
 description TEXT NOT NULL,
 sort_order INT NOT NULL DEFAULT 0,
 FOREIGN KEY(course_id) REFERENCES learning_courses(id) ON DELETE CASCADE,
 KEY(course_id,sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS learning_lessons (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 module_id BIGINT UNSIGNED NOT NULL,
 title VARCHAR(200) NOT NULL,
 slug VARCHAR(200) NOT NULL,
 objective TEXT NULL,
 content LONGTEXT NOT NULL,
 syntax LONGTEXT NULL,
 example_code LONGTEXT NULL,
 output_html LONGTEXT NULL,
 key_points LONGTEXT NULL,
 difficulty VARCHAR(60) DEFAULT 'Beginner',
 duration_minutes INT DEFAULT 10,
 sort_order INT DEFAULT 0,
 is_published TINYINT(1) DEFAULT 1,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 FOREIGN KEY(module_id) REFERENCES learning_modules(id) ON DELETE CASCADE,
 UNIQUE KEY(module_id,slug),
 KEY(module_id,sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS learning_tryit (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 lesson_id BIGINT UNSIGNED NOT NULL,
 instructions TEXT NULL,
 starter_code LONGTEXT NULL,
 starter_html LONGTEXT NULL,
 starter_css LONGTEXT NULL,
 starter_js LONGTEXT NULL,
 solution_code LONGTEXT NULL,
 validator_type VARCHAR(50) DEFAULT 'preview',
 FOREIGN KEY(lesson_id) REFERENCES learning_lessons(id) ON DELETE CASCADE,
 UNIQUE KEY(lesson_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS learning_practice (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 lesson_id BIGINT UNSIGNED NOT NULL,
 title VARCHAR(200) NOT NULL,
 instructions TEXT NOT NULL,
 starter_code LONGTEXT NULL,
 expected_output LONGTEXT NULL,
 solution_code LONGTEXT NULL,
 difficulty VARCHAR(50) DEFAULT 'Easy',
 xp INT DEFAULT 20,
 sort_order INT DEFAULT 0,
 FOREIGN KEY(lesson_id) REFERENCES learning_lessons(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS learning_quizzes (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 course_id BIGINT UNSIGNED NOT NULL,
 title VARCHAR(200) NOT NULL,
 description TEXT NULL,
 quiz_type ENUM('chapter','practice','final','mock') DEFAULT 'chapter',
 time_limit_minutes INT NULL,
 pass_percent INT DEFAULT 70,
 xp INT DEFAULT 25,
 is_published TINYINT(1) DEFAULT 1,
 FOREIGN KEY(course_id) REFERENCES learning_courses(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS learning_questions (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 quiz_id BIGINT UNSIGNED NOT NULL,
 lesson_id BIGINT UNSIGNED NULL,
 question_type ENUM('mcq','true_false','fill_blank','code','multiple') DEFAULT 'mcq',
 question TEXT NOT NULL,
 explanation TEXT NULL,
 marks INT DEFAULT 1,
 sort_order INT DEFAULT 0,
 FOREIGN KEY(quiz_id) REFERENCES learning_quizzes(id) ON DELETE CASCADE,
 FOREIGN KEY(lesson_id) REFERENCES learning_lessons(id) ON DELETE SET NULL,
 KEY(quiz_id,sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS learning_options (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 question_id BIGINT UNSIGNED NOT NULL,
 option_text TEXT NOT NULL,
 is_correct TINYINT(1) DEFAULT 0,
 sort_order INT DEFAULT 0,
 FOREIGN KEY(question_id) REFERENCES learning_questions(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS learning_mock_tests (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 course_id BIGINT UNSIGNED NOT NULL,
 title VARCHAR(200) NOT NULL,
 description TEXT NULL,
 question_count INT DEFAULT 30,
 time_limit_minutes INT DEFAULT 30,
 pass_percent INT DEFAULT 70,
 xp INT DEFAULT 100,
 is_published TINYINT(1) DEFAULT 1,
 FOREIGN KEY(course_id) REFERENCES learning_courses(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS learning_mock_questions (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 mock_test_id BIGINT UNSIGNED NOT NULL,
 question_id BIGINT UNSIGNED NOT NULL,
 sort_order INT DEFAULT 0,
 FOREIGN KEY(mock_test_id) REFERENCES learning_mock_tests(id) ON DELETE CASCADE,
 FOREIGN KEY(question_id) REFERENCES learning_questions(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS learning_projects (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 course_id BIGINT UNSIGNED NOT NULL,
 title VARCHAR(200) NOT NULL,
 description TEXT NOT NULL,
 requirements LONGTEXT NULL,
 starter_code LONGTEXT NULL,
 solution_code LONGTEXT NULL,
 difficulty VARCHAR(50) DEFAULT 'Intermediate',
 xp INT DEFAULT 100,
 sort_order INT DEFAULT 0,
 is_published TINYINT(1) DEFAULT 1,
 FOREIGN KEY(course_id) REFERENCES learning_courses(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS learning_challenges (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 course_id BIGINT UNSIGNED NOT NULL,
 title VARCHAR(200) NOT NULL,
 description TEXT NOT NULL,
 starter_code LONGTEXT NULL,
 expected_output LONGTEXT NULL,
 solution_code LONGTEXT NULL,
 difficulty VARCHAR(50) DEFAULT 'Easy',
 xp INT DEFAULT 20,
 sort_order INT DEFAULT 0,
 FOREIGN KEY(course_id) REFERENCES learning_courses(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS learning_lesson_progress (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 user_id BIGINT UNSIGNED NOT NULL,
 lesson_id BIGINT UNSIGNED NOT NULL,
 started TINYINT(1) DEFAULT 1,
 completed TINYINT(1) DEFAULT 0,
 progress_percent INT DEFAULT 0,
 last_position INT DEFAULT 0,
 first_opened_at TIMESTAMP NULL,
 last_opened_at TIMESTAMP NULL,
 completed_at TIMESTAMP NULL,
 UNIQUE KEY(user_id,lesson_id),
 KEY(user_id,lesson_id),
 FOREIGN KEY(lesson_id) REFERENCES learning_lessons(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS learning_saved_code (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 user_id BIGINT UNSIGNED NOT NULL,
 lesson_id BIGINT UNSIGNED NULL,
 practice_id BIGINT UNSIGNED NULL,
 project_id BIGINT UNSIGNED NULL,
 html_code LONGTEXT NULL,
 css_code LONGTEXT NULL,
 js_code LONGTEXT NULL,
 code LONGTEXT NULL,
 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 KEY(user_id,lesson_id),
 FOREIGN KEY(lesson_id) REFERENCES learning_lessons(id) ON DELETE CASCADE,
 FOREIGN KEY(practice_id) REFERENCES learning_practice(id) ON DELETE SET NULL,
 FOREIGN KEY(project_id) REFERENCES learning_projects(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS learning_code_attempts (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 user_id BIGINT UNSIGNED NOT NULL,
 lesson_id BIGINT UNSIGNED NULL,
 challenge_id BIGINT UNSIGNED NULL,
 project_id BIGINT UNSIGNED NULL,
 code LONGTEXT NULL,
 html_code LONGTEXT NULL,
 css_code LONGTEXT NULL,
 js_code LONGTEXT NULL,
 result_text TEXT NULL,
 passed TINYINT(1) DEFAULT 0,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY(lesson_id) REFERENCES learning_lessons(id) ON DELETE SET NULL,
 FOREIGN KEY(challenge_id) REFERENCES learning_challenges(id) ON DELETE SET NULL,
 FOREIGN KEY(project_id) REFERENCES learning_projects(id) ON DELETE SET NULL,
 KEY(user_id,created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS learning_bookmarks (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 user_id BIGINT UNSIGNED NOT NULL,
 lesson_id BIGINT UNSIGNED NOT NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 UNIQUE KEY(user_id,lesson_id),
 FOREIGN KEY(lesson_id) REFERENCES learning_lessons(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS learning_likes (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 user_id BIGINT UNSIGNED NOT NULL,
 lesson_id BIGINT UNSIGNED NOT NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 UNIQUE KEY(user_id,lesson_id),
 FOREIGN KEY(lesson_id) REFERENCES learning_lessons(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS learning_notes (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 user_id BIGINT UNSIGNED NOT NULL,
 lesson_id BIGINT UNSIGNED NOT NULL,
 note LONGTEXT NOT NULL,
 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 UNIQUE KEY(user_id,lesson_id),
 FOREIGN KEY(lesson_id) REFERENCES learning_lessons(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS learning_quiz_attempts (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 user_id BIGINT UNSIGNED NOT NULL,
 quiz_id BIGINT UNSIGNED NOT NULL,
 score INT DEFAULT 0,
 total INT DEFAULT 0,
 percent DECIMAL(6,2) DEFAULT 0,
 passed TINYINT(1) DEFAULT 0,
 xp_earned INT DEFAULT 0,
 started_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 finished_at TIMESTAMP NULL,
 FOREIGN KEY(quiz_id) REFERENCES learning_quizzes(id) ON DELETE CASCADE,
 KEY(user_id,quiz_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS learning_quiz_answers (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 attempt_id BIGINT UNSIGNED NOT NULL,
 question_id BIGINT UNSIGNED NOT NULL,
 option_id BIGINT UNSIGNED NULL,
 answer_text TEXT NULL,
 is_correct TINYINT(1) DEFAULT 0,
 FOREIGN KEY(attempt_id) REFERENCES learning_quiz_attempts(id) ON DELETE CASCADE,
 FOREIGN KEY(question_id) REFERENCES learning_questions(id) ON DELETE CASCADE,
 FOREIGN KEY(option_id) REFERENCES learning_options(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS learning_mock_attempts (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 user_id BIGINT UNSIGNED NOT NULL,
 mock_test_id BIGINT UNSIGNED NOT NULL,
 score INT DEFAULT 0,
 total INT DEFAULT 0,
 percent DECIMAL(6,2) DEFAULT 0,
 passed TINYINT(1) DEFAULT 0,
 xp_earned INT DEFAULT 0,
 started_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 finished_at TIMESTAMP NULL,
 FOREIGN KEY(mock_test_id) REFERENCES learning_mock_tests(id) ON DELETE CASCADE,
 KEY(user_id,mock_test_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS learning_practice_attempts (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 user_id BIGINT UNSIGNED NOT NULL,
 practice_id BIGINT UNSIGNED NOT NULL,
 answer LONGTEXT NULL,
 score INT DEFAULT 0,
 passed TINYINT(1) DEFAULT 0,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY(practice_id) REFERENCES learning_practice(id) ON DELETE CASCADE,
 KEY(user_id,practice_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS learning_project_submissions (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 user_id BIGINT UNSIGNED NOT NULL,
 project_id BIGINT UNSIGNED NOT NULL,
 code LONGTEXT NULL,
 html_code LONGTEXT NULL,
 css_code LONGTEXT NULL,
 js_code LONGTEXT NULL,
 status VARCHAR(40) DEFAULT 'draft',
 score INT DEFAULT 0,
 feedback TEXT NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 FOREIGN KEY(project_id) REFERENCES learning_projects(id) ON DELETE CASCADE,
 KEY(user_id,project_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS learning_user_stats (
 user_id BIGINT UNSIGNED PRIMARY KEY,
 xp INT DEFAULT 0,
 streak_days INT DEFAULT 0,
 longest_streak INT DEFAULT 0,
 lessons_completed INT DEFAULT 0,
 quizzes_passed INT DEFAULT 0,
 mock_tests_passed INT DEFAULT 0,
 projects_completed INT DEFAULT 0,
 total_learning_minutes INT DEFAULT 0,
 last_active_date DATE NULL,
 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS learning_activity (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 user_id BIGINT UNSIGNED NOT NULL,
 activity_type VARCHAR(80) NOT NULL,
 course_id BIGINT UNSIGNED NULL,
 lesson_id BIGINT UNSIGNED NULL,
 metadata JSON NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 KEY(user_id,created_at),
 FOREIGN KEY(course_id) REFERENCES learning_courses(id) ON DELETE SET NULL,
 FOREIGN KEY(lesson_id) REFERENCES learning_lessons(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS learning_achievements (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 slug VARCHAR(120) UNIQUE NOT NULL,
 title VARCHAR(180) NOT NULL,
 description TEXT NOT NULL,
 icon VARCHAR(40) DEFAULT '🏆',
 xp INT DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS learning_user_achievements (
 user_id BIGINT UNSIGNED NOT NULL,
 achievement_id BIGINT UNSIGNED NOT NULL,
 earned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 PRIMARY KEY(user_id,achievement_id),
 FOREIGN KEY(achievement_id) REFERENCES learning_achievements(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS learning_certificates (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 user_id BIGINT UNSIGNED NOT NULL,
 course_id BIGINT UNSIGNED NOT NULL,
 certificate_no VARCHAR(120) UNIQUE NOT NULL,
 verification_hash VARCHAR(128) UNIQUE NOT NULL,
 issued_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 UNIQUE KEY(user_id,course_id),
 FOREIGN KEY(course_id) REFERENCES learning_courses(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- All requested language/course records

INSERT INTO learning_courses(slug,language_name,title,description,level,icon,sort_order)
VALUES('html','HTML','HTML Complete Course',"Build modern web pages.",'Beginner','🌐',1)
ON DUPLICATE KEY UPDATE language_name=VALUES(language_name),title=VALUES(title),description=VALUES(description),level=VALUES(level),icon=VALUES(icon),sort_order=VALUES(sort_order);

SET @course_html=(SELECT id FROM learning_courses WHERE slug='html' LIMIT 1);

INSERT INTO learning_modules(course_id,title,description,sort_order)
SELECT @course_html,'HTML Introduction','Start learning HTML from the fundamentals.',1
WHERE NOT EXISTS(SELECT 1 FROM learning_modules WHERE course_id=@course_html AND sort_order=1);

INSERT INTO learning_lessons(module_id,title,slug,objective,content,syntax,example_code,output_html,key_points,language,difficulty,duration_minutes,sort_order)
SELECT id,'Introduction to HTML','introduction','Understand the basics of HTML.',
'<h2>Introduction to HTML</h2><p>This is the starting lesson for the HTML course. The complete curriculum is stored in MySQL and can be expanded through the same learning tables.</p>',
'HTML',
'',
'',
'Begin with the language fundamentals, syntax and practical exercises.',
'HTML','Beginner',10,1
FROM learning_modules
WHERE course_id=@course_html AND sort_order=1
AND NOT EXISTS(SELECT 1 FROM learning_lessons WHERE module_id=learning_modules.id AND sort_order=1);

INSERT INTO learning_courses(slug,language_name,title,description,level,icon,sort_order)
VALUES('css','CSS','CSS Complete Course',"Design responsive interfaces.",'Beginner','🎨',2)
ON DUPLICATE KEY UPDATE language_name=VALUES(language_name),title=VALUES(title),description=VALUES(description),level=VALUES(level),icon=VALUES(icon),sort_order=VALUES(sort_order);

SET @course_css=(SELECT id FROM learning_courses WHERE slug='css' LIMIT 1);

INSERT INTO learning_modules(course_id,title,description,sort_order)
SELECT @course_css,'CSS Introduction','Start learning CSS from the fundamentals.',1
WHERE NOT EXISTS(SELECT 1 FROM learning_modules WHERE course_id=@course_css AND sort_order=1);

INSERT INTO learning_lessons(module_id,title,slug,objective,content,syntax,example_code,output_html,key_points,language,difficulty,duration_minutes,sort_order)
SELECT id,'Introduction to CSS','introduction','Understand the basics of CSS.',
'<h2>Introduction to CSS</h2><p>This is the starting lesson for the CSS course. The complete curriculum is stored in MySQL and can be expanded through the same learning tables.</p>',
'CSS',
'',
'',
'Begin with the language fundamentals, syntax and practical exercises.',
'CSS','Beginner',10,1
FROM learning_modules
WHERE course_id=@course_css AND sort_order=1
AND NOT EXISTS(SELECT 1 FROM learning_lessons WHERE module_id=learning_modules.id AND sort_order=1);

INSERT INTO learning_courses(slug,language_name,title,description,level,icon,sort_order)
VALUES('javascript','JavaScript','JavaScript Complete Course',"Build interactive web applications.",'Beginner → Advanced','JS',3)
ON DUPLICATE KEY UPDATE language_name=VALUES(language_name),title=VALUES(title),description=VALUES(description),level=VALUES(level),icon=VALUES(icon),sort_order=VALUES(sort_order);

SET @course_javascript=(SELECT id FROM learning_courses WHERE slug='javascript' LIMIT 1);

INSERT INTO learning_modules(course_id,title,description,sort_order)
SELECT @course_javascript,'JavaScript Introduction','Start learning JavaScript from the fundamentals.',1
WHERE NOT EXISTS(SELECT 1 FROM learning_modules WHERE course_id=@course_javascript AND sort_order=1);

INSERT INTO learning_lessons(module_id,title,slug,objective,content,syntax,example_code,output_html,key_points,language,difficulty,duration_minutes,sort_order)
SELECT id,'Introduction to JavaScript','introduction','Understand the basics of JavaScript.',
'<h2>Introduction to JavaScript</h2><p>This is the starting lesson for the JavaScript course. The complete curriculum is stored in MySQL and can be expanded through the same learning tables.</p>',
'JavaScript',
'',
'',
'Begin with the language fundamentals, syntax and practical exercises.',
'JavaScript','Beginner',10,1
FROM learning_modules
WHERE course_id=@course_javascript AND sort_order=1
AND NOT EXISTS(SELECT 1 FROM learning_lessons WHERE module_id=learning_modules.id AND sort_order=1);

INSERT INTO learning_courses(slug,language_name,title,description,level,icon,sort_order)
VALUES('python','Python','Python Complete Course',"Learn programming, automation and application development.",'Beginner → Advanced','🐍',4)
ON DUPLICATE KEY UPDATE language_name=VALUES(language_name),title=VALUES(title),description=VALUES(description),level=VALUES(level),icon=VALUES(icon),sort_order=VALUES(sort_order);

SET @course_python=(SELECT id FROM learning_courses WHERE slug='python' LIMIT 1);

INSERT INTO learning_modules(course_id,title,description,sort_order)
SELECT @course_python,'Python Introduction','Start learning Python from the fundamentals.',1
WHERE NOT EXISTS(SELECT 1 FROM learning_modules WHERE course_id=@course_python AND sort_order=1);

INSERT INTO learning_lessons(module_id,title,slug,objective,content,syntax,example_code,output_html,key_points,language,difficulty,duration_minutes,sort_order)
SELECT id,'Introduction to Python','introduction','Understand the basics of Python.',
'<h2>Introduction to Python</h2><p>This is the starting lesson for the Python course. The complete curriculum is stored in MySQL and can be expanded through the same learning tables.</p>',
'Python',
'',
'',
'Begin with the language fundamentals, syntax and practical exercises.',
'Python','Beginner',10,1
FROM learning_modules
WHERE course_id=@course_python AND sort_order=1
AND NOT EXISTS(SELECT 1 FROM learning_lessons WHERE module_id=learning_modules.id AND sort_order=1);

INSERT INTO learning_courses(slug,language_name,title,description,level,icon,sort_order)
VALUES('php','PHP','PHP Complete Course',"Build dynamic server-side web applications.",'Beginner → Advanced','PHP',5)
ON DUPLICATE KEY UPDATE language_name=VALUES(language_name),title=VALUES(title),description=VALUES(description),level=VALUES(level),icon=VALUES(icon),sort_order=VALUES(sort_order);

SET @course_php=(SELECT id FROM learning_courses WHERE slug='php' LIMIT 1);

INSERT INTO learning_modules(course_id,title,description,sort_order)
SELECT @course_php,'PHP Introduction','Start learning PHP from the fundamentals.',1
WHERE NOT EXISTS(SELECT 1 FROM learning_modules WHERE course_id=@course_php AND sort_order=1);

INSERT INTO learning_lessons(module_id,title,slug,objective,content,syntax,example_code,output_html,key_points,language,difficulty,duration_minutes,sort_order)
SELECT id,'Introduction to PHP','introduction','Understand the basics of PHP.',
'<h2>Introduction to PHP</h2><p>This is the starting lesson for the PHP course. The complete curriculum is stored in MySQL and can be expanded through the same learning tables.</p>',
'PHP',
'',
'',
'Begin with the language fundamentals, syntax and practical exercises.',
'PHP','Beginner',10,1
FROM learning_modules
WHERE course_id=@course_php AND sort_order=1
AND NOT EXISTS(SELECT 1 FROM learning_lessons WHERE module_id=learning_modules.id AND sort_order=1);

INSERT INTO learning_courses(slug,language_name,title,description,level,icon,sort_order)
VALUES('c','C','C Complete Course',"Learn core programming and memory.",'Beginner → Advanced','C',6)
ON DUPLICATE KEY UPDATE language_name=VALUES(language_name),title=VALUES(title),description=VALUES(description),level=VALUES(level),icon=VALUES(icon),sort_order=VALUES(sort_order);

SET @course_c=(SELECT id FROM learning_courses WHERE slug='c' LIMIT 1);

INSERT INTO learning_modules(course_id,title,description,sort_order)
SELECT @course_c,'C Introduction','Start learning C from the fundamentals.',1
WHERE NOT EXISTS(SELECT 1 FROM learning_modules WHERE course_id=@course_c AND sort_order=1);

INSERT INTO learning_lessons(module_id,title,slug,objective,content,syntax,example_code,output_html,key_points,language,difficulty,duration_minutes,sort_order)
SELECT id,'Introduction to C','introduction','Understand the basics of C.',
'<h2>Introduction to C</h2><p>This is the starting lesson for the C course. The complete curriculum is stored in MySQL and can be expanded through the same learning tables.</p>',
'C',
'',
'',
'Begin with the language fundamentals, syntax and practical exercises.',
'C','Beginner',10,1
FROM learning_modules
WHERE course_id=@course_c AND sort_order=1
AND NOT EXISTS(SELECT 1 FROM learning_lessons WHERE module_id=learning_modules.id AND sort_order=1);

INSERT INTO learning_courses(slug,language_name,title,description,level,icon,sort_order)
VALUES('cpp','C++','C++ Complete Course',"Master OOP, STL and modern C++.",'Intermediate → Advanced','C++',7)
ON DUPLICATE KEY UPDATE language_name=VALUES(language_name),title=VALUES(title),description=VALUES(description),level=VALUES(level),icon=VALUES(icon),sort_order=VALUES(sort_order);

SET @course_cpp=(SELECT id FROM learning_courses WHERE slug='cpp' LIMIT 1);

INSERT INTO learning_modules(course_id,title,description,sort_order)
SELECT @course_cpp,'C++ Introduction','Start learning C++ from the fundamentals.',1
WHERE NOT EXISTS(SELECT 1 FROM learning_modules WHERE course_id=@course_cpp AND sort_order=1);

INSERT INTO learning_lessons(module_id,title,slug,objective,content,syntax,example_code,output_html,key_points,language,difficulty,duration_minutes,sort_order)
SELECT id,'Introduction to C++','introduction','Understand the basics of C++.',
'<h2>Introduction to C++</h2><p>This is the starting lesson for the C++ course. The complete curriculum is stored in MySQL and can be expanded through the same learning tables.</p>',
'C++',
'',
'',
'Begin with the language fundamentals, syntax and practical exercises.',
'C++','Beginner',10,1
FROM learning_modules
WHERE course_id=@course_cpp AND sort_order=1
AND NOT EXISTS(SELECT 1 FROM learning_lessons WHERE module_id=learning_modules.id AND sort_order=1);

INSERT INTO learning_courses(slug,language_name,title,description,level,icon,sort_order)
VALUES('java','Java','Java Complete Course',"Build object-oriented applications.",'Beginner → Advanced','☕',8)
ON DUPLICATE KEY UPDATE language_name=VALUES(language_name),title=VALUES(title),description=VALUES(description),level=VALUES(level),icon=VALUES(icon),sort_order=VALUES(sort_order);

SET @course_java=(SELECT id FROM learning_courses WHERE slug='java' LIMIT 1);

INSERT INTO learning_modules(course_id,title,description,sort_order)
SELECT @course_java,'Java Introduction','Start learning Java from the fundamentals.',1
WHERE NOT EXISTS(SELECT 1 FROM learning_modules WHERE course_id=@course_java AND sort_order=1);

INSERT INTO learning_lessons(module_id,title,slug,objective,content,syntax,example_code,output_html,key_points,language,difficulty,duration_minutes,sort_order)
SELECT id,'Introduction to Java','introduction','Understand the basics of Java.',
'<h2>Introduction to Java</h2><p>This is the starting lesson for the Java course. The complete curriculum is stored in MySQL and can be expanded through the same learning tables.</p>',
'Java',
'',
'',
'Begin with the language fundamentals, syntax and practical exercises.',
'Java','Beginner',10,1
FROM learning_modules
WHERE course_id=@course_java AND sort_order=1
AND NOT EXISTS(SELECT 1 FROM learning_lessons WHERE module_id=learning_modules.id AND sort_order=1);

INSERT INTO learning_courses(slug,language_name,title,description,level,icon,sort_order)
VALUES('csharp','C#','C# Complete Course',"Build .NET applications.",'Beginner → Advanced','C#',9)
ON DUPLICATE KEY UPDATE language_name=VALUES(language_name),title=VALUES(title),description=VALUES(description),level=VALUES(level),icon=VALUES(icon),sort_order=VALUES(sort_order);

SET @course_csharp=(SELECT id FROM learning_courses WHERE slug='csharp' LIMIT 1);

INSERT INTO learning_modules(course_id,title,description,sort_order)
SELECT @course_csharp,'C# Introduction','Start learning C# from the fundamentals.',1
WHERE NOT EXISTS(SELECT 1 FROM learning_modules WHERE course_id=@course_csharp AND sort_order=1);

INSERT INTO learning_lessons(module_id,title,slug,objective,content,syntax,example_code,output_html,key_points,language,difficulty,duration_minutes,sort_order)
SELECT id,'Introduction to C#','introduction','Understand the basics of C#.',
'<h2>Introduction to C#</h2><p>This is the starting lesson for the C# course. The complete curriculum is stored in MySQL and can be expanded through the same learning tables.</p>',
'C#',
'',
'',
'Begin with the language fundamentals, syntax and practical exercises.',
'C#','Beginner',10,1
FROM learning_modules
WHERE course_id=@course_csharp AND sort_order=1
AND NOT EXISTS(SELECT 1 FROM learning_lessons WHERE module_id=learning_modules.id AND sort_order=1);

INSERT INTO learning_courses(slug,language_name,title,description,level,icon,sort_order)
VALUES('typescript','TypeScript','TypeScript Complete Course',"Build scalable typed JavaScript applications.",'Intermediate','TS',10)
ON DUPLICATE KEY UPDATE language_name=VALUES(language_name),title=VALUES(title),description=VALUES(description),level=VALUES(level),icon=VALUES(icon),sort_order=VALUES(sort_order);

SET @course_typescript=(SELECT id FROM learning_courses WHERE slug='typescript' LIMIT 1);

INSERT INTO learning_modules(course_id,title,description,sort_order)
SELECT @course_typescript,'TypeScript Introduction','Start learning TypeScript from the fundamentals.',1
WHERE NOT EXISTS(SELECT 1 FROM learning_modules WHERE course_id=@course_typescript AND sort_order=1);

INSERT INTO learning_lessons(module_id,title,slug,objective,content,syntax,example_code,output_html,key_points,language,difficulty,duration_minutes,sort_order)
SELECT id,'Introduction to TypeScript','introduction','Understand the basics of TypeScript.',
'<h2>Introduction to TypeScript</h2><p>This is the starting lesson for the TypeScript course. The complete curriculum is stored in MySQL and can be expanded through the same learning tables.</p>',
'TypeScript',
'',
'',
'Begin with the language fundamentals, syntax and practical exercises.',
'TypeScript','Beginner',10,1
FROM learning_modules
WHERE course_id=@course_typescript AND sort_order=1
AND NOT EXISTS(SELECT 1 FROM learning_lessons WHERE module_id=learning_modules.id AND sort_order=1);

INSERT INTO learning_courses(slug,language_name,title,description,level,icon,sort_order)
VALUES('sql','SQL','SQL Complete Course',"Learn databases and SQL queries.",'Beginner → Advanced','SQL',11)
ON DUPLICATE KEY UPDATE language_name=VALUES(language_name),title=VALUES(title),description=VALUES(description),level=VALUES(level),icon=VALUES(icon),sort_order=VALUES(sort_order);

SET @course_sql=(SELECT id FROM learning_courses WHERE slug='sql' LIMIT 1);

INSERT INTO learning_modules(course_id,title,description,sort_order)
SELECT @course_sql,'SQL Introduction','Start learning SQL from the fundamentals.',1
WHERE NOT EXISTS(SELECT 1 FROM learning_modules WHERE course_id=@course_sql AND sort_order=1);

INSERT INTO learning_lessons(module_id,title,slug,objective,content,syntax,example_code,output_html,key_points,language,difficulty,duration_minutes,sort_order)
SELECT id,'Introduction to SQL','introduction','Understand the basics of SQL.',
'<h2>Introduction to SQL</h2><p>This is the starting lesson for the SQL course. The complete curriculum is stored in MySQL and can be expanded through the same learning tables.</p>',
'SQL',
'',
'',
'Begin with the language fundamentals, syntax and practical exercises.',
'SQL','Beginner',10,1
FROM learning_modules
WHERE course_id=@course_sql AND sort_order=1
AND NOT EXISTS(SELECT 1 FROM learning_lessons WHERE module_id=learning_modules.id AND sort_order=1);

INSERT INTO learning_courses(slug,language_name,title,description,level,icon,sort_order)
VALUES('go','Go','Go Complete Course',"Build fast and scalable software.",'Intermediate','GO',12)
ON DUPLICATE KEY UPDATE language_name=VALUES(language_name),title=VALUES(title),description=VALUES(description),level=VALUES(level),icon=VALUES(icon),sort_order=VALUES(sort_order);

SET @course_go=(SELECT id FROM learning_courses WHERE slug='go' LIMIT 1);

INSERT INTO learning_modules(course_id,title,description,sort_order)
SELECT @course_go,'Go Introduction','Start learning Go from the fundamentals.',1
WHERE NOT EXISTS(SELECT 1 FROM learning_modules WHERE course_id=@course_go AND sort_order=1);

INSERT INTO learning_lessons(module_id,title,slug,objective,content,syntax,example_code,output_html,key_points,language,difficulty,duration_minutes,sort_order)
SELECT id,'Introduction to Go','introduction','Understand the basics of Go.',
'<h2>Introduction to Go</h2><p>This is the starting lesson for the Go course. The complete curriculum is stored in MySQL and can be expanded through the same learning tables.</p>',
'Go',
'',
'',
'Begin with the language fundamentals, syntax and practical exercises.',
'Go','Beginner',10,1
FROM learning_modules
WHERE course_id=@course_go AND sort_order=1
AND NOT EXISTS(SELECT 1 FROM learning_lessons WHERE module_id=learning_modules.id AND sort_order=1);

INSERT INTO learning_courses(slug,language_name,title,description,level,icon,sort_order)
VALUES('rust','Rust','Rust Complete Course',"Learn safe systems programming.",'Intermediate → Advanced','RS',13)
ON DUPLICATE KEY UPDATE language_name=VALUES(language_name),title=VALUES(title),description=VALUES(description),level=VALUES(level),icon=VALUES(icon),sort_order=VALUES(sort_order);

SET @course_rust=(SELECT id FROM learning_courses WHERE slug='rust' LIMIT 1);

INSERT INTO learning_modules(course_id,title,description,sort_order)
SELECT @course_rust,'Rust Introduction','Start learning Rust from the fundamentals.',1
WHERE NOT EXISTS(SELECT 1 FROM learning_modules WHERE course_id=@course_rust AND sort_order=1);

INSERT INTO learning_lessons(module_id,title,slug,objective,content,syntax,example_code,output_html,key_points,language,difficulty,duration_minutes,sort_order)
SELECT id,'Introduction to Rust','introduction','Understand the basics of Rust.',
'<h2>Introduction to Rust</h2><p>This is the starting lesson for the Rust course. The complete curriculum is stored in MySQL and can be expanded through the same learning tables.</p>',
'Rust',
'',
'',
'Begin with the language fundamentals, syntax and practical exercises.',
'Rust','Beginner',10,1
FROM learning_modules
WHERE course_id=@course_rust AND sort_order=1
AND NOT EXISTS(SELECT 1 FROM learning_lessons WHERE module_id=learning_modules.id AND sort_order=1);

INSERT INTO learning_courses(slug,language_name,title,description,level,icon,sort_order)
VALUES('kotlin','Kotlin','Kotlin Complete Course',"Build modern JVM and Android applications.",'Beginner → Advanced','KT',14)
ON DUPLICATE KEY UPDATE language_name=VALUES(language_name),title=VALUES(title),description=VALUES(description),level=VALUES(level),icon=VALUES(icon),sort_order=VALUES(sort_order);

SET @course_kotlin=(SELECT id FROM learning_courses WHERE slug='kotlin' LIMIT 1);

INSERT INTO learning_modules(course_id,title,description,sort_order)
SELECT @course_kotlin,'Kotlin Introduction','Start learning Kotlin from the fundamentals.',1
WHERE NOT EXISTS(SELECT 1 FROM learning_modules WHERE course_id=@course_kotlin AND sort_order=1);

INSERT INTO learning_lessons(module_id,title,slug,objective,content,syntax,example_code,output_html,key_points,language,difficulty,duration_minutes,sort_order)
SELECT id,'Introduction to Kotlin','introduction','Understand the basics of Kotlin.',
'<h2>Introduction to Kotlin</h2><p>This is the starting lesson for the Kotlin course. The complete curriculum is stored in MySQL and can be expanded through the same learning tables.</p>',
'Kotlin',
'',
'',
'Begin with the language fundamentals, syntax and practical exercises.',
'Kotlin','Beginner',10,1
FROM learning_modules
WHERE course_id=@course_kotlin AND sort_order=1
AND NOT EXISTS(SELECT 1 FROM learning_lessons WHERE module_id=learning_modules.id AND sort_order=1);

INSERT INTO learning_courses(slug,language_name,title,description,level,icon,sort_order)
VALUES('swift','Swift','Swift Complete Course',"Build apps for Apple platforms.",'Beginner → Advanced','SW',15)
ON DUPLICATE KEY UPDATE language_name=VALUES(language_name),title=VALUES(title),description=VALUES(description),level=VALUES(level),icon=VALUES(icon),sort_order=VALUES(sort_order);

SET @course_swift=(SELECT id FROM learning_courses WHERE slug='swift' LIMIT 1);

INSERT INTO learning_modules(course_id,title,description,sort_order)
SELECT @course_swift,'Swift Introduction','Start learning Swift from the fundamentals.',1
WHERE NOT EXISTS(SELECT 1 FROM learning_modules WHERE course_id=@course_swift AND sort_order=1);

INSERT INTO learning_lessons(module_id,title,slug,objective,content,syntax,example_code,output_html,key_points,language,difficulty,duration_minutes,sort_order)
SELECT id,'Introduction to Swift','introduction','Understand the basics of Swift.',
'<h2>Introduction to Swift</h2><p>This is the starting lesson for the Swift course. The complete curriculum is stored in MySQL and can be expanded through the same learning tables.</p>',
'Swift',
'',
'',
'Begin with the language fundamentals, syntax and practical exercises.',
'Swift','Beginner',10,1
FROM learning_modules
WHERE course_id=@course_swift AND sort_order=1
AND NOT EXISTS(SELECT 1 FROM learning_lessons WHERE module_id=learning_modules.id AND sort_order=1);

INSERT INTO learning_achievements(slug,title,description,icon,xp) VALUES
('first-lesson','First Lesson','Complete your first lesson.','🎯',25),
('first-quiz','First Quiz','Pass your first quiz.','🧠',50),
('perfect-quiz','Perfect Quiz','Score 100% on a quiz.','💯',100),
('course-complete','Course Complete','Complete a course.','🏆',500)
ON DUPLICATE KEY UPDATE title=VALUES(title);

-- Create a small starter quiz for every course.

SET @course_html=(SELECT id FROM learning_courses WHERE slug='html' LIMIT 1);
INSERT INTO learning_quizzes(course_id,title,description,quiz_type,time_limit_minutes,pass_percent,xp)
SELECT @course_html,'HTML Fundamentals Quiz','Starter assessment for the HTML course.','chapter',10,70,25
WHERE NOT EXISTS(SELECT 1 FROM learning_quizzes WHERE course_id=@course_html AND title='HTML Fundamentals Quiz');

INSERT INTO learning_mock_tests(course_id,title,description,question_count,time_limit_minutes,pass_percent,xp)
SELECT @course_html,'HTML Final Mock Test','Final assessment for the HTML course.',30,30,70,100
WHERE NOT EXISTS(SELECT 1 FROM learning_mock_tests WHERE course_id=@course_html AND title='HTML Final Mock Test');

INSERT INTO learning_projects(course_id,title,description,requirements,difficulty,xp,sort_order)
SELECT @course_html,'HTML Starter Project','Build a small practical project using HTML.',
'Create a project that demonstrates the concepts learned in the course. Use clean structure and document your work.',
'Beginner',100,1
WHERE NOT EXISTS(SELECT 1 FROM learning_projects WHERE course_id=@course_html AND sort_order=1);

SET @course_css=(SELECT id FROM learning_courses WHERE slug='css' LIMIT 1);
INSERT INTO learning_quizzes(course_id,title,description,quiz_type,time_limit_minutes,pass_percent,xp)
SELECT @course_css,'CSS Fundamentals Quiz','Starter assessment for the CSS course.','chapter',10,70,25
WHERE NOT EXISTS(SELECT 1 FROM learning_quizzes WHERE course_id=@course_css AND title='CSS Fundamentals Quiz');

INSERT INTO learning_mock_tests(course_id,title,description,question_count,time_limit_minutes,pass_percent,xp)
SELECT @course_css,'CSS Final Mock Test','Final assessment for the CSS course.',30,30,70,100
WHERE NOT EXISTS(SELECT 1 FROM learning_mock_tests WHERE course_id=@course_css AND title='CSS Final Mock Test');

INSERT INTO learning_projects(course_id,title,description,requirements,difficulty,xp,sort_order)
SELECT @course_css,'CSS Starter Project','Build a small practical project using CSS.',
'Create a project that demonstrates the concepts learned in the course. Use clean structure and document your work.',
'Beginner',100,1
WHERE NOT EXISTS(SELECT 1 FROM learning_projects WHERE course_id=@course_css AND sort_order=1);

SET @course_javascript=(SELECT id FROM learning_courses WHERE slug='javascript' LIMIT 1);
INSERT INTO learning_quizzes(course_id,title,description,quiz_type,time_limit_minutes,pass_percent,xp)
SELECT @course_javascript,'JavaScript Fundamentals Quiz','Starter assessment for the JavaScript course.','chapter',10,70,25
WHERE NOT EXISTS(SELECT 1 FROM learning_quizzes WHERE course_id=@course_javascript AND title='JavaScript Fundamentals Quiz');

INSERT INTO learning_mock_tests(course_id,title,description,question_count,time_limit_minutes,pass_percent,xp)
SELECT @course_javascript,'JavaScript Final Mock Test','Final assessment for the JavaScript course.',30,30,70,100
WHERE NOT EXISTS(SELECT 1 FROM learning_mock_tests WHERE course_id=@course_javascript AND title='JavaScript Final Mock Test');

INSERT INTO learning_projects(course_id,title,description,requirements,difficulty,xp,sort_order)
SELECT @course_javascript,'JavaScript Starter Project','Build a small practical project using JavaScript.',
'Create a project that demonstrates the concepts learned in the course. Use clean structure and document your work.',
'Beginner',100,1
WHERE NOT EXISTS(SELECT 1 FROM learning_projects WHERE course_id=@course_javascript AND sort_order=1);

SET @course_python=(SELECT id FROM learning_courses WHERE slug='python' LIMIT 1);
INSERT INTO learning_quizzes(course_id,title,description,quiz_type,time_limit_minutes,pass_percent,xp)
SELECT @course_python,'Python Fundamentals Quiz','Starter assessment for the Python course.','chapter',10,70,25
WHERE NOT EXISTS(SELECT 1 FROM learning_quizzes WHERE course_id=@course_python AND title='Python Fundamentals Quiz');

INSERT INTO learning_mock_tests(course_id,title,description,question_count,time_limit_minutes,pass_percent,xp)
SELECT @course_python,'Python Final Mock Test','Final assessment for the Python course.',30,30,70,100
WHERE NOT EXISTS(SELECT 1 FROM learning_mock_tests WHERE course_id=@course_python AND title='Python Final Mock Test');

INSERT INTO learning_projects(course_id,title,description,requirements,difficulty,xp,sort_order)
SELECT @course_python,'Python Starter Project','Build a small practical project using Python.',
'Create a project that demonstrates the concepts learned in the course. Use clean structure and document your work.',
'Beginner',100,1
WHERE NOT EXISTS(SELECT 1 FROM learning_projects WHERE course_id=@course_python AND sort_order=1);

SET @course_php=(SELECT id FROM learning_courses WHERE slug='php' LIMIT 1);
INSERT INTO learning_quizzes(course_id,title,description,quiz_type,time_limit_minutes,pass_percent,xp)
SELECT @course_php,'PHP Fundamentals Quiz','Starter assessment for the PHP course.','chapter',10,70,25
WHERE NOT EXISTS(SELECT 1 FROM learning_quizzes WHERE course_id=@course_php AND title='PHP Fundamentals Quiz');

INSERT INTO learning_mock_tests(course_id,title,description,question_count,time_limit_minutes,pass_percent,xp)
SELECT @course_php,'PHP Final Mock Test','Final assessment for the PHP course.',30,30,70,100
WHERE NOT EXISTS(SELECT 1 FROM learning_mock_tests WHERE course_id=@course_php AND title='PHP Final Mock Test');

INSERT INTO learning_projects(course_id,title,description,requirements,difficulty,xp,sort_order)
SELECT @course_php,'PHP Starter Project','Build a small practical project using PHP.',
'Create a project that demonstrates the concepts learned in the course. Use clean structure and document your work.',
'Beginner',100,1
WHERE NOT EXISTS(SELECT 1 FROM learning_projects WHERE course_id=@course_php AND sort_order=1);

SET @course_c=(SELECT id FROM learning_courses WHERE slug='c' LIMIT 1);
INSERT INTO learning_quizzes(course_id,title,description,quiz_type,time_limit_minutes,pass_percent,xp)
SELECT @course_c,'C Fundamentals Quiz','Starter assessment for the C course.','chapter',10,70,25
WHERE NOT EXISTS(SELECT 1 FROM learning_quizzes WHERE course_id=@course_c AND title='C Fundamentals Quiz');

INSERT INTO learning_mock_tests(course_id,title,description,question_count,time_limit_minutes,pass_percent,xp)
SELECT @course_c,'C Final Mock Test','Final assessment for the C course.',30,30,70,100
WHERE NOT EXISTS(SELECT 1 FROM learning_mock_tests WHERE course_id=@course_c AND title='C Final Mock Test');

INSERT INTO learning_projects(course_id,title,description,requirements,difficulty,xp,sort_order)
SELECT @course_c,'C Starter Project','Build a small practical project using C.',
'Create a project that demonstrates the concepts learned in the course. Use clean structure and document your work.',
'Beginner',100,1
WHERE NOT EXISTS(SELECT 1 FROM learning_projects WHERE course_id=@course_c AND sort_order=1);

SET @course_cpp=(SELECT id FROM learning_courses WHERE slug='cpp' LIMIT 1);
INSERT INTO learning_quizzes(course_id,title,description,quiz_type,time_limit_minutes,pass_percent,xp)
SELECT @course_cpp,'C++ Fundamentals Quiz','Starter assessment for the C++ course.','chapter',10,70,25
WHERE NOT EXISTS(SELECT 1 FROM learning_quizzes WHERE course_id=@course_cpp AND title='C++ Fundamentals Quiz');

INSERT INTO learning_mock_tests(course_id,title,description,question_count,time_limit_minutes,pass_percent,xp)
SELECT @course_cpp,'C++ Final Mock Test','Final assessment for the C++ course.',30,30,70,100
WHERE NOT EXISTS(SELECT 1 FROM learning_mock_tests WHERE course_id=@course_cpp AND title='C++ Final Mock Test');

INSERT INTO learning_projects(course_id,title,description,requirements,difficulty,xp,sort_order)
SELECT @course_cpp,'C++ Starter Project','Build a small practical project using C++.',
'Create a project that demonstrates the concepts learned in the course. Use clean structure and document your work.',
'Beginner',100,1
WHERE NOT EXISTS(SELECT 1 FROM learning_projects WHERE course_id=@course_cpp AND sort_order=1);

SET @course_java=(SELECT id FROM learning_courses WHERE slug='java' LIMIT 1);
INSERT INTO learning_quizzes(course_id,title,description,quiz_type,time_limit_minutes,pass_percent,xp)
SELECT @course_java,'Java Fundamentals Quiz','Starter assessment for the Java course.','chapter',10,70,25
WHERE NOT EXISTS(SELECT 1 FROM learning_quizzes WHERE course_id=@course_java AND title='Java Fundamentals Quiz');

INSERT INTO learning_mock_tests(course_id,title,description,question_count,time_limit_minutes,pass_percent,xp)
SELECT @course_java,'Java Final Mock Test','Final assessment for the Java course.',30,30,70,100
WHERE NOT EXISTS(SELECT 1 FROM learning_mock_tests WHERE course_id=@course_java AND title='Java Final Mock Test');

INSERT INTO learning_projects(course_id,title,description,requirements,difficulty,xp,sort_order)
SELECT @course_java,'Java Starter Project','Build a small practical project using Java.',
'Create a project that demonstrates the concepts learned in the course. Use clean structure and document your work.',
'Beginner',100,1
WHERE NOT EXISTS(SELECT 1 FROM learning_projects WHERE course_id=@course_java AND sort_order=1);

SET @course_csharp=(SELECT id FROM learning_courses WHERE slug='csharp' LIMIT 1);
INSERT INTO learning_quizzes(course_id,title,description,quiz_type,time_limit_minutes,pass_percent,xp)
SELECT @course_csharp,'C# Fundamentals Quiz','Starter assessment for the C# course.','chapter',10,70,25
WHERE NOT EXISTS(SELECT 1 FROM learning_quizzes WHERE course_id=@course_csharp AND title='C# Fundamentals Quiz');

INSERT INTO learning_mock_tests(course_id,title,description,question_count,time_limit_minutes,pass_percent,xp)
SELECT @course_csharp,'C# Final Mock Test','Final assessment for the C# course.',30,30,70,100
WHERE NOT EXISTS(SELECT 1 FROM learning_mock_tests WHERE course_id=@course_csharp AND title='C# Final Mock Test');

INSERT INTO learning_projects(course_id,title,description,requirements,difficulty,xp,sort_order)
SELECT @course_csharp,'C# Starter Project','Build a small practical project using C#.',
'Create a project that demonstrates the concepts learned in the course. Use clean structure and document your work.',
'Beginner',100,1
WHERE NOT EXISTS(SELECT 1 FROM learning_projects WHERE course_id=@course_csharp AND sort_order=1);

SET @course_typescript=(SELECT id FROM learning_courses WHERE slug='typescript' LIMIT 1);
INSERT INTO learning_quizzes(course_id,title,description,quiz_type,time_limit_minutes,pass_percent,xp)
SELECT @course_typescript,'TypeScript Fundamentals Quiz','Starter assessment for the TypeScript course.','chapter',10,70,25
WHERE NOT EXISTS(SELECT 1 FROM learning_quizzes WHERE course_id=@course_typescript AND title='TypeScript Fundamentals Quiz');

INSERT INTO learning_mock_tests(course_id,title,description,question_count,time_limit_minutes,pass_percent,xp)
SELECT @course_typescript,'TypeScript Final Mock Test','Final assessment for the TypeScript course.',30,30,70,100
WHERE NOT EXISTS(SELECT 1 FROM learning_mock_tests WHERE course_id=@course_typescript AND title='TypeScript Final Mock Test');

INSERT INTO learning_projects(course_id,title,description,requirements,difficulty,xp,sort_order)
SELECT @course_typescript,'TypeScript Starter Project','Build a small practical project using TypeScript.',
'Create a project that demonstrates the concepts learned in the course. Use clean structure and document your work.',
'Beginner',100,1
WHERE NOT EXISTS(SELECT 1 FROM learning_projects WHERE course_id=@course_typescript AND sort_order=1);

SET @course_sql=(SELECT id FROM learning_courses WHERE slug='sql' LIMIT 1);
INSERT INTO learning_quizzes(course_id,title,description,quiz_type,time_limit_minutes,pass_percent,xp)
SELECT @course_sql,'SQL Fundamentals Quiz','Starter assessment for the SQL course.','chapter',10,70,25
WHERE NOT EXISTS(SELECT 1 FROM learning_quizzes WHERE course_id=@course_sql AND title='SQL Fundamentals Quiz');

INSERT INTO learning_mock_tests(course_id,title,description,question_count,time_limit_minutes,pass_percent,xp)
SELECT @course_sql,'SQL Final Mock Test','Final assessment for the SQL course.',30,30,70,100
WHERE NOT EXISTS(SELECT 1 FROM learning_mock_tests WHERE course_id=@course_sql AND title='SQL Final Mock Test');

INSERT INTO learning_projects(course_id,title,description,requirements,difficulty,xp,sort_order)
SELECT @course_sql,'SQL Starter Project','Build a small practical project using SQL.',
'Create a project that demonstrates the concepts learned in the course. Use clean structure and document your work.',
'Beginner',100,1
WHERE NOT EXISTS(SELECT 1 FROM learning_projects WHERE course_id=@course_sql AND sort_order=1);

SET @course_go=(SELECT id FROM learning_courses WHERE slug='go' LIMIT 1);
INSERT INTO learning_quizzes(course_id,title,description,quiz_type,time_limit_minutes,pass_percent,xp)
SELECT @course_go,'Go Fundamentals Quiz','Starter assessment for the Go course.','chapter',10,70,25
WHERE NOT EXISTS(SELECT 1 FROM learning_quizzes WHERE course_id=@course_go AND title='Go Fundamentals Quiz');

INSERT INTO learning_mock_tests(course_id,title,description,question_count,time_limit_minutes,pass_percent,xp)
SELECT @course_go,'Go Final Mock Test','Final assessment for the Go course.',30,30,70,100
WHERE NOT EXISTS(SELECT 1 FROM learning_mock_tests WHERE course_id=@course_go AND title='Go Final Mock Test');

INSERT INTO learning_projects(course_id,title,description,requirements,difficulty,xp,sort_order)
SELECT @course_go,'Go Starter Project','Build a small practical project using Go.',
'Create a project that demonstrates the concepts learned in the course. Use clean structure and document your work.',
'Beginner',100,1
WHERE NOT EXISTS(SELECT 1 FROM learning_projects WHERE course_id=@course_go AND sort_order=1);

SET @course_rust=(SELECT id FROM learning_courses WHERE slug='rust' LIMIT 1);
INSERT INTO learning_quizzes(course_id,title,description,quiz_type,time_limit_minutes,pass_percent,xp)
SELECT @course_rust,'Rust Fundamentals Quiz','Starter assessment for the Rust course.','chapter',10,70,25
WHERE NOT EXISTS(SELECT 1 FROM learning_quizzes WHERE course_id=@course_rust AND title='Rust Fundamentals Quiz');

INSERT INTO learning_mock_tests(course_id,title,description,question_count,time_limit_minutes,pass_percent,xp)
SELECT @course_rust,'Rust Final Mock Test','Final assessment for the Rust course.',30,30,70,100
WHERE NOT EXISTS(SELECT 1 FROM learning_mock_tests WHERE course_id=@course_rust AND title='Rust Final Mock Test');

INSERT INTO learning_projects(course_id,title,description,requirements,difficulty,xp,sort_order)
SELECT @course_rust,'Rust Starter Project','Build a small practical project using Rust.',
'Create a project that demonstrates the concepts learned in the course. Use clean structure and document your work.',
'Beginner',100,1
WHERE NOT EXISTS(SELECT 1 FROM learning_projects WHERE course_id=@course_rust AND sort_order=1);

SET @course_kotlin=(SELECT id FROM learning_courses WHERE slug='kotlin' LIMIT 1);
INSERT INTO learning_quizzes(course_id,title,description,quiz_type,time_limit_minutes,pass_percent,xp)
SELECT @course_kotlin,'Kotlin Fundamentals Quiz','Starter assessment for the Kotlin course.','chapter',10,70,25
WHERE NOT EXISTS(SELECT 1 FROM learning_quizzes WHERE course_id=@course_kotlin AND title='Kotlin Fundamentals Quiz');

INSERT INTO learning_mock_tests(course_id,title,description,question_count,time_limit_minutes,pass_percent,xp)
SELECT @course_kotlin,'Kotlin Final Mock Test','Final assessment for the Kotlin course.',30,30,70,100
WHERE NOT EXISTS(SELECT 1 FROM learning_mock_tests WHERE course_id=@course_kotlin AND title='Kotlin Final Mock Test');

INSERT INTO learning_projects(course_id,title,description,requirements,difficulty,xp,sort_order)
SELECT @course_kotlin,'Kotlin Starter Project','Build a small practical project using Kotlin.',
'Create a project that demonstrates the concepts learned in the course. Use clean structure and document your work.',
'Beginner',100,1
WHERE NOT EXISTS(SELECT 1 FROM learning_projects WHERE course_id=@course_kotlin AND sort_order=1);

SET @course_swift=(SELECT id FROM learning_courses WHERE slug='swift' LIMIT 1);
INSERT INTO learning_quizzes(course_id,title,description,quiz_type,time_limit_minutes,pass_percent,xp)
SELECT @course_swift,'Swift Fundamentals Quiz','Starter assessment for the Swift course.','chapter',10,70,25
WHERE NOT EXISTS(SELECT 1 FROM learning_quizzes WHERE course_id=@course_swift AND title='Swift Fundamentals Quiz');

INSERT INTO learning_mock_tests(course_id,title,description,question_count,time_limit_minutes,pass_percent,xp)
SELECT @course_swift,'Swift Final Mock Test','Final assessment for the Swift course.',30,30,70,100
WHERE NOT EXISTS(SELECT 1 FROM learning_mock_tests WHERE course_id=@course_swift AND title='Swift Final Mock Test');

INSERT INTO learning_projects(course_id,title,description,requirements,difficulty,xp,sort_order)
SELECT @course_swift,'Swift Starter Project','Build a small practical project using Swift.',
'Create a project that demonstrates the concepts learned in the course. Use clean structure and document your work.',
'Beginner',100,1
WHERE NOT EXISTS(SELECT 1 FROM learning_projects WHERE course_id=@course_swift AND sort_order=1);

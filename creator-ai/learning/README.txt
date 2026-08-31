SMARTTOOLZ LEARNING HUB — ALL LANGUAGE STRUCTURE

Copy this package into:
public_html/creator-ai/learning/

Folders:
python/
html/
css/
javascript/
c/
cpp/
csharp/
java/
php/
sql/
typescript/
go/
rust/
kotlin/
swift/

Every language folder contains:
index.php
module.php
lesson.php
api.php
practice.php
quiz.php
quiz-run.php
quiz-api.php
mock-test.php
mock-run.php
mock-api.php
projects.php
certificate.php
progress.php
bookmarks.php
notes.php
assets/

Common shared engine:
common/bootstrap.php
common/course.php
common/module.php
common/lesson.php
common/api.php
common/practice.php
common/quiz.php
common/quiz-run.php
common/quiz-api.php
common/mock-test.php
common/mock-run.php
common/mock-api.php
common/projects.php
common/certificate.php
common/progress.php
common/bookmarks.php
common/notes.php

DATABASE:
Import learning_schema.sql into the existing Creator AI database.

NOTES:
- Existing Python files in a live installation should be backed up before replacing them.
- The root Learning Hub loads published courses from MySQL.
- Each language folder sets its own LEARNING_LANGUAGE slug and uses the shared engine.
- User progress, saved code, notes, likes, bookmarks, quiz attempts, mock attempts and certificates are per user.
- Try-It Yourself runs HTML/CSS/JS in a sandboxed browser iframe. Server-side arbitrary code execution is NOT enabled.
- The SQL creates one starter module/lesson and starter assessment/project records for each language. More complete curriculum content can be inserted using the same tables.

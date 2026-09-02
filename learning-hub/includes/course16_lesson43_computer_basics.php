<?php
declare(strict_types=1);

function lh_course16_lesson43_computer_basics_override(array $lesson, int $position): ?string
{
    if ((string)($lesson['slug'] ?? '') !== 'course-16-lesson-43') return null;
    return <<<'HTML'
<section class="lh-section"><h2>Why Organize Documents?</h2><p>A good folder system reduces search time, prevents accidental duplicates and makes backup easier. Organization should be simple enough that you will actually maintain it.</p></section>
<section class="lh-section"><h2>Build a Clear Folder Structure</h2><p>Start with broad categories and create subfolders only when needed. For example:</p><pre><code>Documents/
  Work/
  Learning/
  Personal/
  Projects/
    SmartToolz/
      Assets/
      Reports/
      Drafts/
      Final/</code></pre><div class="lh-callout"><strong>Keep it shallow:</strong> Too many nested folders can make files harder to find.</div></section>
<section class="lh-section"><h2>Use Consistent File Names</h2><p>Useful names tell you what the file is without opening it. Include a topic, date or version when helpful.</p><table class="table table-bordered"><thead><tr><th>Weak</th><th>Better</th></tr></thead><tbody><tr><td>final.docx</td><td>computer-basics-project-v2.docx</td></tr><tr><td>IMG1234.png</td><td>lesson40-screenshot.png</td></tr><tr><td>reportnew.pdf</td><td>monthly-report-2026-09.pdf</td></tr></tbody></table></section>
<section class="lh-section"><h2>Dates and Versions</h2><p>For work that changes over time, a date such as <strong>2026-09-02</strong> sorts naturally when written as YYYY-MM-DD. Version labels such as v1, v2 or draft/final can also help, but avoid creating dozens of confusing copies.</p></section>
<section class="lh-section"><h2>Archive and Clean Up</h2><ul><li>Move completed projects to an archive.</li><li>Delete obvious temporary files you no longer need.</li><li>Keep the latest important version easy to find.</li><li>Do not delete files merely because their purpose is unfamiliar.</li><li>Back up important documents before major cleanup.</li></ul></section>
<section class="lh-section"><h2>Search Is Part of Organization</h2><p>Even a well-organized computer needs search. Use meaningful names, file types and dates so Windows Search can help you locate documents quickly.</p></section>
<section class="lh-section"><h2>Practical Activity</h2><ol><li>Choose one messy practice folder.</li><li>Create three logical categories.</li><li>Rename five files using a consistent naming style.</li><li>Move files into the correct folders.</li><li>Archive one completed item.</li><li>Search for a file using part of its new name.</li></ol></section>
<section class="lh-section"><h2>Quick Self-Check</h2><ol><li>Why are descriptive filenames useful?</li><li>Why can YYYY-MM-DD be helpful?</li><li>What is an archive folder for?</li><li>Why should you back up before major cleanup?</li></ol><details class="mt-3"><summary><strong>Show Answers</strong></summary><ol class="mt-3"><li>They make files easier to identify and search.</li><li>It sorts naturally from year to month to day.</li><li>For completed or less frequently used material.</li><li>Cleanup mistakes can otherwise cause data loss.</li></ol></details></section>
<section class="lh-section"><h2>Key Takeaway</h2><div class="lh-callout"><strong>Good organization is simple, consistent and searchable. Use sensible folders, descriptive names, dates and backups to keep documents under control.</strong></div></section>
HTML;
}

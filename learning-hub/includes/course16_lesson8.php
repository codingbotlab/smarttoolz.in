<?php
declare(strict_types=1);

function lh_course16_lesson8_override(array $lesson, int $position): ?string
{
    if ((string)($lesson['slug'] ?? '') !== 'course-16-lesson-8') return null;
    return <<<'HTML'
<section>
<h2>📁 Folder Organization: Keep Your Computer Clean and Easy to Find</h2>
<p>Good folder organization means storing files in a predictable structure so you can find them quickly, avoid duplicates, and keep important work separate from temporary downloads.</p>

<h3>1. Why Folder Organization Matters</h3>
<ul><li>Find files faster.</li><li>Reduce accidental duplicates.</li><li>Keep personal, school, and work files separate.</li><li>Make backups easier.</li><li>Reduce clutter in Downloads and Desktop.</li></ul>

<h3>2. A Simple Folder Structure</h3>
<p>Start broad and become more specific. For example:</p>
<pre><code>Documents/
├── Personal/
├── Work/
├── Study/
│   ├── Computer Basics/
│   ├── Assignments/
│   └── Notes/
└── Projects/
    ├── Website/
    └── Images/</code></pre>
<div class="lh-callout"><strong>Rule of thumb:</strong> A folder should have a clear purpose. If you cannot explain what belongs inside it, the folder may be too broad or too confusing.</div>

<h3>3. Choose Clear Folder Names</h3>
<p>Use names that tell you what is inside. Prefer <code>2026-09 SmartToolz Project</code> over <code>New Folder (7)</code>. Avoid random names that you will forget later.</p>

<h3>4. Organize by Purpose</h3>
<table><thead><tr><th>Folder</th><th>Good contents</th></tr></thead><tbody>
<tr><td>Documents</td><td>PDFs, Word files, text files</td></tr>
<tr><td>Pictures</td><td>Photos and image files</td></tr>
<tr><td>Videos</td><td>Video files and recordings</td></tr>
<tr><td>Projects</td><td>Files belonging to active projects</td></tr>
<tr><td>Archive</td><td>Older files you want to keep but rarely use</td></tr>
<tr><td>Downloads</td><td>Temporary incoming files that should be sorted later</td></tr>
</tbody></table>

<h3>5. Desktop Is Not a Storage System</h3>
<p>Keeping everything on the Desktop may feel convenient, but a crowded Desktop quickly becomes difficult to manage. Use the Desktop for shortcuts and a small number of current items; move important files into proper folders.</p>

<h3>6. Avoid the “Folder Inside Folder Forever” Problem</h3>
<p>Do not create extremely deep structures such as <code>Work → 2026 → September → Week 1 → Project → Final → Final2 → New</code> unless the structure genuinely helps. Keep the hierarchy as simple as possible.</p>

<h3>7. Use Dates When They Help</h3>
<p>For recurring documents, dates make versions easier to sort. A format such as <code>2026-09-02_Report.pdf</code> keeps dates in chronological order.</p>

<h3>8. Separate Originals, Working Files, and Exports</h3>
<p>For creative or technical projects, consider:</p>
<pre><code>Project/
├── Originals/
├── Working/
├── Exports/
└── Assets/</code></pre>
<p>This reduces the chance of accidentally editing or deleting an original file.</p>

<h3>9. Clean Up Downloads Regularly</h3>
<ol><li>Open Downloads.</li><li>Delete files you no longer need.</li><li>Move important files to their proper folders.</li><li>Rename useful files clearly.</li><li>Keep installers only when you have a reason to keep them.</li></ol>

<h3>10. Practical Activity</h3>
<p>Create a folder named <strong>Computer Basics Practice</strong>. Inside it create <strong>Notes</strong>, <strong>Images</strong>, <strong>Documents</strong>, and <strong>Projects</strong>. Put a few sample files into the correct folders, rename unclear files, and leave the Desktop and Downloads as uncluttered as possible.</p>

<h3>11. Common Mistakes</h3>
<ul><li>Saving everything on the Desktop.</li><li>Leaving hundreds of files in Downloads.</li><li>Using names such as <code>abc</code>, <code>new</code>, or <code>finalfinal</code>.</li><li>Creating unnecessary duplicate folders.</li><li>Putting unrelated files into one giant folder.</li><li>Assuming organization is a backup. <strong>It is not.</strong></li></ul>

<h3>12. Quick Self-Check</h3>
<ul><li>Where should an important document be stored?</li><li>Why should Desktop and Downloads not become permanent storage?</li><li>How can dates help organize repeated files?</li><li>Why keep originals separate from working files?</li><li>What makes a folder name useful?</li></ul>

<h3>🎯 Key Takeaway</h3>
<p><strong>A good folder system is simple, predictable, and easy to maintain. Organize files by purpose, use clear names, clean temporary folders regularly, and remember that organization is not the same as backup.</strong></p>
</section>
HTML;
}

<?php
declare(strict_types=1);

function lh_course16_lesson6_override(array $lesson, int $position): ?string
{
    $slug = (string)($lesson['slug'] ?? '');
    if ($slug !== 'course-16-lesson-6') {
        return null;
    }

    return <<<'HTML'
<h2>Files and Folders: Organize Your Computer</h2>
<p>Files are the actual pieces of information you save—documents, photos, videos, music, programs and more. Folders are containers that help you group those files so you can find them again quickly.</p>

<div class="lh-callout"><strong>Simple rule:</strong> A file contains information. A folder helps you organize files and other folders.</div>

<h2>1. File vs Folder</h2>
<table><thead><tr><th>Item</th><th>What it is</th><th>Example</th></tr></thead><tbody>
<tr><td>File</td><td>A saved piece of data</td><td>report.docx, photo.jpg, song.mp3</td></tr>
<tr><td>Folder</td><td>A container used to organize items</td><td>Documents, Photos, Projects</td></tr>
<tr><td>Subfolder</td><td>A folder inside another folder</td><td>Projects → Website</td></tr>
</tbody></table>

<h2>2. Where Files Live</h2>
<p>On Windows, files are stored on a storage drive such as <strong>C:</strong> or another drive letter. A typical path might look like:</p>
<pre>C:\Users\YourName\Documents\Projects\Website\index.html</pre>
<p>Read the path from left to right: the drive contains folders, folders can contain subfolders, and the final item is the file.</p>

<h2>3. File Names Matter</h2>
<p>Use names that tell you what a file contains. Compare:</p>
<ul>
<li><strong>Better:</strong> <code>2026-09-02_Project_Report.docx</code></li>
<li><strong>Weak:</strong> <code>New Document (7).docx</code></li>
</ul>
<p>A good naming system makes search, sorting and backup much easier.</p>

<h2>4. Create a Folder</h2>
<ol>
<li>Open File Explorer with <strong>Windows + E</strong>.</li>
<li>Go to the location where you want the folder.</li>
<li>Right-click an empty area.</li>
<li>Select <strong>New → Folder</strong>.</li>
<li>Give it a meaningful name and press Enter.</li>
</ol>

<h2>5. Rename a File or Folder</h2>
<p>Select the item and press <strong>F2</strong>, or right-click it and choose <strong>Rename</strong>. Rename it without changing the extension unless you understand what the extension does.</p>

<div class="lh-callout"><strong>Important:</strong> Changing <code>.jpg</code> to <code>.docx</code> does not convert an image into a Word document. It can simply make the file difficult for the operating system to open.</div>

<h2>6. Copy vs Move</h2>
<table><thead><tr><th>Action</th><th>What happens</th><th>When to use it</th></tr></thead><tbody>
<tr><td>Copy</td><td>Creates another copy while keeping the original</td><td>Backup, sharing, duplicate workspace</td></tr>
<tr><td>Move</td><td>Places the original item in a different location</td><td>Reorganizing folders</td></tr>
</tbody></table>
<p>Useful shortcuts:</p>
<ul>
<li><strong>Ctrl + C</strong> — Copy</li>
<li><strong>Ctrl + X</strong> — Cut</li>
<li><strong>Ctrl + V</strong> — Paste</li>
<li><strong>Ctrl + Z</strong> — Undo a recent action</li>
</ul>

<h2>7. Delete and Recycle Bin</h2>
<p>When you normally delete a file in Windows, it is moved to the <strong>Recycle Bin</strong> rather than immediately disappearing. You can restore an item from there until the bin is emptied.</p>
<p>Be careful with <strong>Shift + Delete</strong>: it bypasses the normal Recycle Bin workflow, so recovery may be harder.</p>

<h2>8. Search for Files</h2>
<p>If you cannot remember where you saved something, use File Explorer search. Search by a distinctive part of the filename, file type or another useful keyword. For example, searching for <code>invoice</code> can locate files whose names contain that word.</p>

<h2>9. Build a Simple Folder Structure</h2>
<p>A practical structure could be:</p>
<pre>Documents/
├── Work/
│   ├── Reports/
│   └── Invoices/
├── Personal/
│   └── Important/
└── Learning/
    ├── Computer-Basics/
    └── Projects/</pre>
<p>Do not create dozens of tiny folders just for the sake of organization. The goal is to make important files easy to locate.</p>

<h2>10. Practical Activity</h2>
<ol>
<li>Create a folder named <code>Computer-Basics-Practice</code>.</li>
<li>Inside it, create <code>Documents</code>, <code>Images</code> and <code>Projects</code>.</li>
<li>Create a text document inside <code>Documents</code> and name it <code>my-notes.txt</code>.</li>
<li>Copy that file into <code>Projects</code>.</li>
<li>Rename the copied file to <code>project-notes.txt</code>.</li>
<li>Delete the copied file and restore it from the Recycle Bin.</li>
<li>Use File Explorer search to find <code>my-notes.txt</code>.</li>
</ol>

<h2>Common Beginner Mistakes</h2>
<ul>
<li>Saving everything directly on the Desktop.</li>
<li>Using vague names such as <code>final2</code>, <code>new</code> or <code>abc</code>.</li>
<li>Confusing Copy with Move.</li>
<li>Changing file extensions without understanding them.</li>
<li>Assuming the Recycle Bin is a backup.</li>
<li>Keeping important files in only one location.</li>
</ul>

<h2>Quick Self-Check</h2>
<ul>
<li>What is the difference between a file and a folder?</li>
<li>What does a file path tell you?</li>
<li>What is the difference between Copy and Move?</li>
<li>Which shortcut opens File Explorer?</li>
<li>Why should important files have meaningful names?</li>
<li>Why is the Recycle Bin not a real backup?</li>
</ul>

<div class="lh-callout"><strong>Key takeaway:</strong> Good file management is a basic computer skill. Use clear names, sensible folders and regular backups so your files stay easy to find and safe from accidental loss.</div>
HTML;
}

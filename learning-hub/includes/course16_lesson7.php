<?php
declare(strict_types=1);

function lh_course16_lesson7_override(array $lesson, int $position): ?string
{
    if ((string)($lesson['slug'] ?? '') !== 'course-16-lesson-7') return null;

    return <<<'HTML'
<h2>File Extensions: Understanding What a File Really Is</h2>
<p>A <strong>file extension</strong> is the part at the end of a filename that usually tells Windows and other operating systems what type of file it is and which apps can open it. Examples include <code>.jpg</code>, <code>.png</code>, <code>.pdf</code>, <code>.docx</code>, <code>.xlsx</code>, <code>.mp3</code>, and <code>.mp4</code>.</p>

<div class="lh-callout"><strong>Simple rule:</strong> the name tells you what the file is called; the extension gives a strong clue about what kind of data the file contains.</div>

<h3>1. Anatomy of a Filename</h3>
<p>Consider <code>holiday-photo.jpg</code>. <strong>holiday-photo</strong> is the filename and <strong>.jpg</strong> is the extension. Together they form the complete filename.</p>
<ul>
<li><strong>.jpg / .jpeg</strong> — common image files</li>
<li><strong>.png</strong> — images, often useful when transparency is needed</li>
<li><strong>.gif</strong> — images/animations</li>
<li><strong>.pdf</strong> — portable documents</li>
<li><strong>.docx</strong> — Microsoft Word documents</li>
<li><strong>.xlsx</strong> — Microsoft Excel workbooks</li>
<li><strong>.pptx</strong> — PowerPoint presentations</li>
<li><strong>.txt</strong> — plain text</li>
<li><strong>.mp3</strong> — audio</li>
<li><strong>.mp4</strong> — video</li>
<li><strong>.zip</strong> — compressed archive</li>
</ul>

<h3>2. Why Extensions Matter</h3>
<p>Operating systems use file type information to decide how a file should be handled. When you double-click a PDF, for example, Windows can use the default PDF application to open it. Changing an extension does <strong>not</strong> magically convert the underlying file into another format.</p>

<h3>3. File Extension vs File Format</h3>
<p>These terms are related but not identical. An extension is part of the filename. A format describes how the actual data is structured. Simply renaming <code>photo.jpg</code> to <code>photo.png</code> does not convert the JPEG data into a PNG file.</p>

<h3>4. Show Extensions in Windows</h3>
<ol>
<li>Open <strong>File Explorer</strong>.</li>
<li>In Windows 11, select <strong>View → Show → File name extensions</strong>.</li>
<li>In Windows 10, open the <strong>View</strong> tab and enable <strong>File name extensions</strong>.</li>
<li>Now you can see endings such as <code>.jpg</code>, <code>.pdf</code>, and <code>.docx</code> directly in filenames.</li>
</ol>

<div class="lh-callout"><strong>Beginner safety tip:</strong> keeping file extensions visible makes suspicious filenames easier to notice. A file named <code>invoice.pdf.exe</code> is an executable program, not an ordinary PDF document.</div>

<h3>5. Changing the Default App</h3>
<p>If the correct file opens in an unwanted application, you usually do not need to rename the extension. Right-click the file, choose <strong>Open with</strong>, select the appropriate application, and choose the option to always use that app when available.</p>

<h3>6. Renaming an Extension</h3>
<p>Renaming a file is safe when you are only changing its descriptive name. Be careful when changing the extension itself. Windows may warn that the file could become unusable. Do not change extensions just to make a file “look” like another type.</p>

<h3>7. Common File-Type Mistakes</h3>
<table class="table table-bordered"><thead><tr><th>Mistake</th><th>What happens</th><th>Better approach</th></tr></thead><tbody>
<tr><td>Changing .jpg to .png</td><td>The data is still JPEG data.</td><td>Use an image converter/editor to actually convert it.</td></tr>
<tr><td>Hiding extensions</td><td>Important file-type information is less visible.</td><td>Keep extensions visible when learning/troubleshooting.</td></tr>
<tr><td>Deleting an extension</td><td>The file may stop opening normally.</td><td>Keep the correct extension.</td></tr>
<tr><td>Opening unknown executable files</td><td>Potential security risk.</td><td>Verify the source before opening.</td></tr>
</tbody></table>

<h3>8. Practical Activity</h3>
<ol>
<li>Create a folder named <strong>File Extension Practice</strong>.</li>
<li>Create or copy one image, one text/document, one PDF, and one audio/video file into it.</li>
<li>Turn on <strong>File name extensions</strong>.</li>
<li>Write down each filename and its extension.</li>
<li>Right-click each file and check <strong>Open with</strong>.</li>
<li>Pick one file and explain why its extension matches its type.</li>
</ol>

<h3>9. Quick Self-Check</h3>
<ul>
<li>What is a file extension?</li>
<li>What extension is commonly used for PDF documents?</li>
<li>Does renaming .jpg to .png convert the image?</li>
<li>Why is <code>.exe</code> different from <code>.pdf</code>?</li>
<li>How can you make extensions visible in File Explorer?</li>
</ul>

<h3>Key Takeaway</h3>
<p><strong>File extensions help you understand file types and how your computer should handle them.</strong> Learn to recognize common extensions, keep them visible, and never assume that changing a filename ending actually converts the file.</p>
HTML;
}

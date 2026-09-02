<?php
declare(strict_types=1);

function lh_course16_lesson42_computer_basics_override(array $lesson, int $position): ?string
{
    if ((string)($lesson['slug'] ?? '') !== 'course-16-lesson-42') return null;
    return <<<'HTML'
<section class="lh-section"><h2>What Is a ZIP File?</h2><p>ZIP is a common archive format used to package one or more files into a single archive and often reduce their size through compression.</p><div class="lh-callout"><strong>Simple idea:</strong> ZIP is like putting several files into one compressed digital package.</div></section>
<section class="lh-section"><h2>Creating a ZIP</h2><ol><li>Select the files or folder in File Explorer.</li><li>Use the Windows compression option available in the context menu.</li><li>Give the resulting ZIP a clear name.</li><li>Keep the archive in an appropriate folder.</li></ol></section>
<section class="lh-section"><h2>Extracting a ZIP</h2><ol><li>Right-click the ZIP file.</li><li>Choose the available <strong>Extract</strong> option.</li><li>Select the destination folder.</li><li>Open the extracted folder and check the contents.</li></ol><p>Do not extract unknown archives into a sensitive or important folder. Use a safe location first.</p></section>
<section class="lh-section"><h2>Compression vs Encryption</h2><p>Compression makes files smaller or packages them together. It is <strong>not the same as security</strong>. A normal ZIP archive should not be treated as a secure way to protect confidential information.</p><p>If you need encrypted archives, use a trusted tool and a strong password, and understand its encryption options.</p></section>
<section class="lh-section"><h2>ZIP Safety</h2><ul><li>Do not open unexpected ZIP attachments just because the filename looks familiar.</li><li>Scan suspicious downloads with your security software.</li><li>Be cautious with executable files inside archives.</li><li>Keep enough free storage for extraction.</li><li>Verify the source before extracting an archive from an unknown website.</li></ul></section>
<section class="lh-section"><h2>Practical Activity</h2><ol><li>Create a folder containing three harmless practice text files.</li><li>Compress the folder into a ZIP archive.</li><li>Check the ZIP size.</li><li>Extract it to another practice folder.</li><li>Compare the extracted files with the originals.</li></ol></section>
<section class="lh-section"><h2>Quick Self-Check</h2><ol><li>What is a ZIP archive?</li><li>What is extraction?</li><li>Does compression automatically encrypt a file?</li><li>Why should unknown archives be handled carefully?</li></ol><details class="mt-3"><summary><strong>Show Answers</strong></summary><ol class="mt-3"><li>A package containing files/folders, often compressed.</li><li>Unpacking the archive into normal files and folders.</li><li>No.</li><li>They can contain unwanted or malicious files.</li></ol></details></section>
<section class="lh-section"><h2>Key Takeaway</h2><div class="lh-callout"><strong>ZIP files are useful for packaging and compressing files. Remember that compression is not encryption, and always consider the source before extracting an archive.</strong></div></section>
HTML;
}

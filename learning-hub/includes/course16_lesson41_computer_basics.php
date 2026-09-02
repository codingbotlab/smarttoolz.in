<?php
declare(strict_types=1);

function lh_course16_lesson41_computer_basics_override(array $lesson, int $position): ?string
{
    if ((string)($lesson['slug'] ?? '') !== 'course-16-lesson-41') return null;
    return <<<'HTML'
<section class="lh-section"><h2>What Is a PDF?</h2><p>PDF stands for <strong>Portable Document Format</strong>. PDFs are designed to preserve document layout so a file can look consistent across computers and devices.</p></section>
<section class="lh-section"><h2>Opening a PDF</h2><p>Modern web browsers can usually open PDFs directly. Dedicated PDF readers may provide additional tools for forms, annotations, printing or accessibility.</p><ol><li>Open the PDF from a trusted source.</li><li>Check the filename and source before interacting with it.</li><li>Use the browser or PDF reader to view it.</li></ol></section>
<section class="lh-section"><h2>Useful PDF Tasks</h2><table class="table table-bordered"><thead><tr><th>Task</th><th>What to look for</th></tr></thead><tbody><tr><td>Search</td><td>Use the reader's Find/Search function.</td></tr><tr><td>Print</td><td>Check page range, orientation and printer before printing.</td></tr><tr><td>Save</td><td>Use a meaningful filename and correct folder.</td></tr><tr><td>Copy text</td><td>Works when the PDF contains selectable text; scanned pages may need OCR.</td></tr><tr><td>Fill forms</td><td>Use form fields when provided and review entries before submitting.</td></tr></tbody></table></section>
<section class="lh-section"><h2>PDFs and Security</h2><p>A PDF can contain active or malicious content. Treat unexpected attachments and downloads cautiously. Open sensitive documents only from trusted sources and keep your browser/PDF software updated.</p><div class="lh-callout"><strong>Never enter a password or payment detail into a document simply because it asks you to. Verify the organization through an official channel first.</strong></div></section>
<section class="lh-section"><h2>PDF Conversion</h2><p>Many tools can convert documents or images to PDF. Avoid uploading confidential documents to unknown online converters. For sensitive files, prefer a trusted local application or an approved service.</p></section>
<section class="lh-section"><h2>Practical Activity</h2><ol><li>Open a non-sensitive PDF.</li><li>Search for a word or phrase.</li><li>Try selecting text.</li><li>Open Print and inspect the page options without printing.</li><li>Save a copy with a descriptive filename.</li></ol></section>
<section class="lh-section"><h2>Quick Self-Check</h2><ol><li>What does PDF stand for?</li><li>Why is PDF useful for sharing documents?</li><li>What can happen with a scanned PDF?</li><li>Why should unknown PDF attachments be treated cautiously?</li></ol><details class="mt-3"><summary><strong>Show Answers</strong></summary><ol class="mt-3"><li>Portable Document Format.</li><li>It is designed to preserve document layout across systems.</li><li>Its text may not be selectable without OCR.</li><li>Unexpected files can contain malicious content or scams.</li></ol></details></section>
<section class="lh-section"><h2>Key Takeaway</h2><div class="lh-callout"><strong>PDFs are excellent for consistent document sharing. Learn to open, search, print and save them safely—and verify the source of unexpected files.</strong></div></section>
HTML;
}

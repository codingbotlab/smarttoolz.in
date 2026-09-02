<?php
declare(strict_types=1);

function lh_course16_lesson44_computer_basics_override(array $lesson, int $position): ?string
{
    if ((string)($lesson['slug'] ?? '') !== 'course-16-lesson-44') return null;
    return <<<'HTML'
<section class="lh-section"><h2>Sharing Files Safely</h2><p>File sharing is useful for work, study and collaboration, but the wrong link or permission can expose private information. Before sharing, know exactly what file you are sending and who should receive it.</p></section>
<section class="lh-section"><h2>Choose the Right Method</h2><table class="table table-bordered"><thead><tr><th>Method</th><th>Good practice</th></tr></thead><tbody><tr><td>Email attachment</td><td>Verify recipient and attachment before sending.</td></tr><tr><td>Cloud link</td><td>Use the narrowest permission needed.</td></tr><tr><td>Shared folder</td><td>Review who has access and whether they can edit.</td></tr><tr><td>USB drive</td><td>Use a trusted device and scan unknown media.</td></tr></tbody></table></section>
<section class="lh-section"><h2>View vs Edit Access</h2><p>If someone only needs to read a document, give view access when the service supports it. Edit access should be reserved for people who genuinely need to change the file.</p><div class="lh-callout"><strong>Least privilege:</strong> Give the minimum access needed for the task.</div></section>
<section class="lh-section"><h2>Check the Recipient</h2><ol><li>Read the email address or account carefully.</li><li>Confirm the person or organization.</li><li>Check that the attached file is the intended version.</li><li>Verify that no confidential information is included accidentally.</li><li>Send only after the review.</li></ol></section>
<section class="lh-section"><h2>Public Links Are Risky</h2><p>A link set to “Anyone with the link” can be forwarded to other people. If the service supports expiration, passwords or restricted access, use those controls when appropriate.</p><p>After sharing, review access and revoke old links when they are no longer needed.</p></section>
<section class="lh-section"><h2>Sensitive Files</h2><p>For passwords, identity documents, financial information or other confidential material, use an approved secure sharing method. Do not paste secrets into ordinary chat messages or send them to an unverified recipient.</p></section>
<section class="lh-section"><h2>Practical Activity</h2><ol><li>Create a harmless practice document.</li><li>Imagine sharing it with a classmate or coworker.</li><li>Decide whether they need view or edit access.</li><li>Review the recipient and link settings.</li><li>After the exercise, remove access or delete the practice share.</li></ol></section>
<section class="lh-section"><h2>Quick Self-Check</h2><ol><li>What does least privilege mean?</li><li>Why are public links risky?</li><li>What should you verify before sending an attachment?</li><li>Why should old shares be revoked?</li></ol><details class="mt-3"><summary><strong>Show Answers</strong></summary><ol class="mt-3"><li>Give only the minimum access needed.</li><li>They can be forwarded to unintended people.</li><li>The recipient, file, version and sensitivity.</li><li>They are no longer needed and can remain an unnecessary access path.</li></ol></details></section>
<section class="lh-section"><h2>Key Takeaway</h2><div class="lh-callout"><strong>Before sharing a file, check the recipient, content and permission. Prefer restricted access and remove old sharing links when the task is finished.</strong></div></section>
HTML;
}

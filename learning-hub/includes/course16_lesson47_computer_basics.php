<?php
declare(strict_types=1);

function lh_course16_lesson47_computer_basics_override(array $lesson, int $position): ?string
{
    if ((string)($lesson['slug'] ?? '') !== 'course-16-lesson-47') return null;
    return <<<'HTML'
<section class="lh-section"><h2>Why Public Computers Need Extra Care</h2><p>A public computer may be shared by many people, and you usually cannot know how it is configured or who used it before you. Treat it as an untrusted device.</p></section>
<section class="lh-section"><h2>What to Avoid</h2><ul><li>Online banking or sensitive financial activity.</li><li>Entering highly sensitive passwords when another device is available.</li><li>Saving passwords in the browser.</li><li>Downloading confidential files to the local computer.</li><li>Leaving accounts signed in after you leave.</li><li>Connecting unknown USB devices.</li></ul><p>Microsoft recommends avoiding banking and other sensitive activity on public computers. citeturn0search5turn0search17</p></section>
<section class="lh-section"><h2>If You Must Use a Public PC</h2><ol><li>Use a trusted modern browser.</li><li>Use a private browsing window where appropriate.</li><li>Do not save passwords or payment details.</li><li>Download only what is necessary.</li><li>Sign out of every account.</li><li>Close all browser windows.</li><li>Delete downloaded files if you created any and are allowed to do so.</li></ol></section>
<section class="lh-section"><h2>Private Browsing Is Not a Magic Shield</h2><p>InPrivate, Incognito or Private Browsing can reduce locally stored browsing history and similar data, but it does not make you anonymous or protect you from malicious software, network monitoring or websites themselves.</p></section>
<section class="lh-section"><h2>USB and External Devices</h2><p>Do not plug an unknown USB drive into a public or personal computer. External media can carry unwanted software. Use only devices you trust and scan files when appropriate.</p></section>
<section class="lh-section"><h2>After Using the Computer</h2><ol><li>Sign out of websites.</li><li>Close private browsing windows.</li><li>Check that downloaded files are not left behind.</li><li>Remove your USB drive or other media.</li><li>Do not leave personal information on the screen.</li></ol></section>
<section class="lh-section"><h2>Practical Activity</h2><p>Create a checklist for using a library, hotel or cyber-café computer. Include at least five actions you will take before and after signing into any account.</p></section>
<section class="lh-section"><h2>Quick Self-Check</h2><ol><li>Why are public computers considered less trusted?</li><li>Why should you avoid saving passwords?</li><li>Does private browsing make you anonymous?</li><li>What should you do before walking away?</li></ol><details class="mt-3"><summary><strong>Show Answers</strong></summary><ol class="mt-3"><li>You do not control who configured or previously used the device.</li><li>Another person may be able to access saved credentials.</li><li>No.</li><li>Sign out, close windows and remove personal data/media.</li></ol></details></section>
<section class="lh-section"><h2>Key Takeaway</h2><div class="lh-callout"><strong>Assume a public computer is not private. Avoid sensitive activity when possible, never save secrets, and always sign out completely.</strong></div></section>
HTML;
}

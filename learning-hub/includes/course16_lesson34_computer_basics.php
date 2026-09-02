<?php
declare(strict_types=1);

function lh_course16_lesson34_computer_basics_override(array $lesson, int $position): ?string
{
    if ((string)($lesson['slug'] ?? '') !== 'course-16-lesson-34') return null;
    return <<<'HTML'
<section class="lh-section">
  <h2>Start With the Symptoms</h2>
  <p>A slow PC can have many causes: too many startup apps, high CPU or memory usage, low disk space, overheating, outdated software, or unwanted software. Good troubleshooting starts by observing the problem instead of changing random settings.</p>
  <div class="lh-callout"><strong>Rule:</strong> Diagnose first, change one thing at a time, then check whether the problem improved.</div>
</section>
<section class="lh-section">
  <h2>Check Task Manager</h2>
  <ol><li>Press <strong>Ctrl + Shift + Esc</strong>.</li><li>On Processes, check CPU, Memory, Disk, Network and GPU.</li><li>Sort by a resource to find unusually busy processes.</li><li>Match the activity to something you recognize, such as a game, browser, download or update.</li></ol>
  <p>A high percentage is not automatically an error. The important question is whether the usage is expected and whether it matches the symptom.</p>
</section>
<section class="lh-section">
  <h2>Common Causes of Slow Performance</h2>
  <table class="table table-bordered"><thead><tr><th>Symptom</th><th>Things to investigate</th></tr></thead><tbody><tr><td>High CPU</td><td>Heavy applications, background tasks, updates or unwanted processes.</td></tr><tr><td>High Memory</td><td>Too many open apps/tabs or memory-heavy software.</td></tr><tr><td>High Disk</td><td>Updates, indexing, large file operations or low free space.</td></tr><tr><td>Slow after startup</td><td>Too many startup applications.</td></tr><tr><td>Slow during games</td><td>GPU/CPU load, heat, graphics settings or background apps.</td></tr></tbody></table>
</section>
<section class="lh-section">
  <h2>Quick Fixes to Try Safely</h2>
  <ul><li>Close applications you no longer need.</li><li>Restart Windows if the PC has been running for a long time or behaves strangely.</li><li>Check available disk space.</li><li>Install pending Windows and application updates.</li><li>Review unnecessary startup apps.</li><li>Run a security scan if unusual pop-ups, redirects or unexplained slowdowns appear.</li><li>Make sure vents are not blocked and the PC is not overheating.</li></ul>
</section>
<section class="lh-section">
  <h2>Low Storage Can Matter</h2>
  <p>Windows needs working space for temporary files, updates and normal operations. If the system drive is nearly full, performance and maintenance tasks can become harder.</p>
  <p>Remove unnecessary temporary files and uninstall software you do not need. Do not delete unknown system files just to create space.</p>
</section>
<section class="lh-section">
  <h2>When an App Alone Is Slow</h2>
  <ol><li>Check whether the rest of Windows is responsive.</li><li>Close unnecessary tabs or windows.</li><li>Save your work and restart the application.</li><li>Check for application updates.</li><li>If the problem continues, look for the application's own repair or troubleshooting options.</li></ol>
</section>
<section class="lh-section">
  <h2>When the Whole PC Is Slow</h2>
  <p>Use Task Manager and the Performance view to decide whether CPU, memory, disk, GPU or another resource is consistently under pressure. If the cause is not obvious, use Windows' built-in troubleshooting and recovery options rather than changing advanced system files.</p>
  <div class="lh-callout"><strong>Safety:</strong> Avoid registry cleaners, random “PC booster” utilities and unknown optimization scripts. They can create new problems.</div>
</section>
<section class="lh-section">
  <h2>Practical Activity</h2>
  <ol><li>Restart the PC.</li><li>Open Task Manager after startup settles.</li><li>Record the CPU, Memory and Disk percentages.</li><li>Identify the top three processes by CPU and Memory.</li><li>Check free space on the system drive.</li><li>Write down one safe change you could make and why.</li></ol>
</section>
<section class="lh-section"><h2>Quick Self-Check</h2><ol><li>Why should you diagnose before changing settings?</li><li>Which Task Manager views help identify resource pressure?</li><li>Why can low disk space be a concern?</li><li>Why is restarting sometimes useful?</li></ol><details class="mt-3"><summary><strong>Show Answers</strong></summary><ol class="mt-3"><li>It prevents random changes and helps identify the actual cause.</li><li>Processes and Performance are especially useful.</li><li>Windows and applications need working space for normal operations and updates.</li><li>It clears many temporary states and starts system services fresh.</li></ol></details></section>
<section class="lh-section"><h2>Key Takeaway</h2><div class="lh-callout"><strong>A slow PC is a symptom, not a diagnosis. Observe resources, check storage and startup items, scan for threats when appropriate, and make the least risky change first.</strong></div></section>
HTML;
}

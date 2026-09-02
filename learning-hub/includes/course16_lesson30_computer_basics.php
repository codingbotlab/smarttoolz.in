<?php
declare(strict_types=1);

function lh_course16_lesson30_computer_basics_override(array $lesson, int $position): ?string
{
    if ((string)($lesson['slug'] ?? '') !== 'course-16-lesson-30') {
        return null;
    }

    return <<<'HTML'
<section class="lh-section">
    <h2>Computer Maintenance</h2>
    <p>Computer maintenance means regularly checking, cleaning, updating, organizing, and protecting your computer so it stays reliable and performs well. Good maintenance is mostly about small habits rather than waiting until something breaks.</p>
</section>

<section class="lh-section">
    <h2>1. Keep Windows and Software Updated</h2>
    <p>Updates can contain security fixes, bug fixes, compatibility improvements, and new features. Keep Windows, browsers, drivers when appropriate, and regularly used applications up to date.</p>
    <div class="lh-callout"><strong>Good habit:</strong> Install updates from the operating system or the software's trusted update mechanism. Avoid random “update now” pop-ups from unfamiliar websites.</div>
</section>

<section class="lh-section">
    <h2>2. Keep Enough Free Storage</h2>
    <p>A nearly full system drive can cause problems with updates, temporary files, application installs, and general system operation. Windows Storage settings can show which categories are consuming space.</p>
    <ol>
        <li>Open <strong>Settings → System → Storage</strong>.</li>
        <li>Review temporary files, installed apps, large files, and other categories.</li>
        <li>Use <strong>Storage Sense</strong> or Cleanup recommendations when appropriate.</li>
        <li>Move large personal files to suitable external or cloud storage when needed.</li>
    </ol>
</section>

<section class="lh-section">
    <h2>3. Clean the Computer Safely</h2>
    <p>Dust can collect around vents, fans, keyboards, and ports. Excessive dust can restrict airflow and contribute to heat buildup.</p>
    <ul>
        <li>Shut down the computer before physical cleaning.</li>
        <li>Keep vents and air openings unobstructed.</li>
        <li>Use appropriate compressed air or cleaning methods for the device.</li>
        <li>Do not pour liquid directly onto a computer.</li>
        <li>For laptops, avoid opening the chassis unless you know the correct procedure.</li>
    </ul>
</section>

<section class="lh-section">
    <h2>4. Watch for Heat and Performance Problems</h2>
    <p>Unexpected shutdowns, excessive fan noise, severe slowdowns, or repeated overheating can indicate a maintenance or hardware problem.</p>
    <p>Check whether vents are blocked, the device is being used on a suitable surface, storage is nearly full, or a particular application is consuming excessive resources.</p>
</section>

<section class="lh-section">
    <h2>5. Protect Against Malware</h2>
    <p>Use built-in or trusted security software and keep it current. Be careful with unknown downloads, suspicious attachments, cracked software, and unexpected links.</p>
    <p>Windows Security can provide protection and health information. If you suspect malware, run an appropriate security scan rather than installing an unknown “PC cleaner” from a pop-up.</p>
</section>

<section class="lh-section">
    <h2>6. Manage Startup Apps</h2>
    <p>Too many applications launching automatically can make startup slower and consume memory and CPU resources.</p>
    <ol>
        <li>Open <strong>Task Manager</strong>.</li>
        <li>Select <strong>Startup apps</strong>.</li>
        <li>Review applications you recognize.</li>
        <li>Disable unnecessary startup applications when appropriate.</li>
    </ol>
    <div class="lh-callout"><strong>Important:</strong> Do not disable an item just because you do not recognize its name. Research it first or leave it enabled if you are unsure.</div>
</section>

<section class="lh-section">
    <h2>7. Keep Your Files Organized</h2>
    <p>Maintenance also includes digital organization. Periodically review Downloads, Desktop, large media folders, duplicate files, and old installers.</p>
    <p>Delete files only when you are sure they are no longer needed, and keep important personal files backed up separately.</p>
</section>

<section class="lh-section">
    <h2>8. Check Computer Health</h2>
    <p>Windows Security's <strong>Device performance &amp; health</strong> area can report issues involving storage capacity, battery life, applications, and other system health areas.</p>
    <p>A warning does not automatically mean the computer is failing. Read the recommendation and investigate before making changes.</p>
</section>

<section class="lh-section">
    <h2>Maintenance Checklist</h2>
    <table class="table table-bordered">
        <thead><tr><th>Task</th><th>What to Check</th></tr></thead>
        <tbody>
            <tr><td>Updates</td><td>Windows and important applications are current</td></tr>
            <tr><td>Storage</td><td>Enough free space remains on the system drive</td></tr>
            <tr><td>Security</td><td>Security protection is enabled and current</td></tr>
            <tr><td>Physical condition</td><td>Vents, ports, keyboard, and screen are reasonably clean</td></tr>
            <tr><td>Startup</td><td>Unnecessary startup applications are not enabled</td></tr>
            <tr><td>Files</td><td>Important files are organized and backed up</td></tr>
            <tr><td>Performance</td><td>No unexplained overheating, crashes, or extreme slowdowns</td></tr>
        </tbody>
    </table>
</section>

<section class="lh-section">
    <h2>Practical Activity</h2>
    <ol>
        <li>Open <strong>Settings → System → Storage</strong> and note which category uses the most space.</li>
        <li>Check Windows Update and see whether your system reports that it is up to date.</li>
        <li>Open Windows Security and look at the device health/security status.</li>
        <li>Open Task Manager → Startup apps and identify one application you do not need at startup.</li>
        <li>Physically inspect your computer's vents and workspace. Make sure airflow is not blocked.</li>
        <li>Finally, confirm that your important documents and photos have a backup.</li>
    </ol>
</section>

<section class="lh-section">
    <h2>Common Mistakes</h2>
    <ul>
        <li>Installing “PC booster” or “driver updater” software from random advertisements.</li>
        <li>Deleting system files without understanding what they are.</li>
        <li>Keeping the system drive completely full.</li>
        <li>Blocking laptop vents by using the laptop on soft surfaces.</li>
        <li>Ignoring repeated crashes, overheating, or unusual fan behavior.</li>
        <li>Thinking maintenance is the same thing as backup. Maintenance helps the computer; backup protects your data.</li>
    </ul>
</section>

<section class="lh-section">
    <h2>Quick Self-Check</h2>
    <ol>
        <li>Why should you keep some free space on the system drive?</li>
        <li>Why is physical airflow important?</li>
        <li>What should you use before installing a random “PC cleaner” advertised online?</li>
        <li>Where can you review startup applications in Windows?</li>
        <li>Does computer maintenance replace a backup?</li>
    </ol>
    <details class="mt-3">
        <summary><strong>Show Answers</strong></summary>
        <ol class="mt-3">
            <li>Free space is needed for normal operation, temporary files, applications, and updates.</li>
            <li>Good airflow helps prevent excessive heat buildup.</li>
            <li>Use trusted built-in security and maintenance tools, such as Windows Security and Windows Storage settings.</li>
            <li>Task Manager → Startup apps.</li>
            <li>No. Maintenance and backup solve different problems.</li>
        </ol>
    </details>
</section>

<section class="lh-section">
    <h2>Key Takeaway</h2>
    <p><strong>A well-maintained computer is easier to use, safer, and more reliable.</strong> Keep software updated, maintain free storage, protect against malware, keep airflow clear, review unnecessary startup apps, organize your files, and—most importantly—keep separate backups of important data.</p>
</section>
HTML;
}

<?php
declare(strict_types=1);

function lh_course16_lesson32_computer_basics_override(array $lesson, int $position): ?string
{
    if ((string)($lesson['slug'] ?? '') !== 'course-16-lesson-32') {
        return null;
    }

    return <<<'HTML'
<section class="lh-section">
  <h2>Managing Disk Space</h2>
  <p>Disk space is the storage available on your computer for Windows, applications, documents, photos, videos, downloads, and other files. Managing it helps prevent low-storage warnings, failed updates, and unnecessary clutter.</p>
  <div class="lh-callout"><strong>Key idea:</strong> Do not start by deleting random files. First find what is using space, decide what is safe to remove or move, and protect important data.</div>
</section>

<section class="lh-section">
  <h2>Storage Space vs RAM</h2>
  <p>Do not confuse disk storage with RAM.</p>
  <table class="table table-bordered">
    <thead><tr><th>Storage</th><th>RAM</th></tr></thead>
    <tbody>
      <tr><td>Keeps files when the computer is turned off.</td><td>Temporary working memory used while programs run.</td></tr>
      <tr><td>Examples: SSD, HDD, USB drive.</td><td>Example: 8 GB, 16 GB, or 32 GB system memory.</td></tr>
    </tbody>
  </table>
</section>

<section class="lh-section">
  <h2>1. Check How Much Space You Have</h2>
  <ol>
    <li>Open <strong>Settings → System → Storage</strong>.</li>
    <li>Review the total and available storage.</li>
    <li>Open <strong>File Explorer → This PC</strong> for a quick view of free space on drives.</li>
  </ol>
  <p>Windows may show categories such as installed apps, temporary files, documents, pictures, and other content.</p>
</section>

<section class="lh-section">
  <h2>2. Find What Is Taking the Most Space</h2>
  <p>Large videos, old downloads, games, installers, duplicate files, and unused applications can consume many gigabytes.</p>
  <ol>
    <li>Open <strong>Settings → System → Storage</strong>.</li>
    <li>Review the largest categories.</li>
    <li>Open a category before deleting anything.</li>
    <li>Identify files or apps you genuinely no longer need.</li>
  </ol>
</section>

<section class="lh-section">
  <h2>3. Temporary Files</h2>
  <p>Windows and applications create temporary files while performing tasks. Some can be removed through Windows storage tools.</p>
  <p>Open <strong>Settings → System → Storage → Temporary files</strong> and carefully review the categories before selecting <strong>Remove files</strong>.</p>
  <div class="lh-callout"><strong>Important:</strong> Read the description for each category. Do not remove something simply because it appears in a cleanup list.</div>
</section>

<section class="lh-section">
  <h2>4. Storage Sense</h2>
  <p><strong>Storage Sense</strong> can automatically free space by cleaning eligible unnecessary files. Its settings should be reviewed so you understand what Windows may clean automatically.</p>
  <p>It is a convenience tool, not a replacement for deciding what important personal files should be kept and backed up.</p>
</section>

<section class="lh-section">
  <h2>5. Remove Unused Applications</h2>
  <p>Large applications and games can occupy many gigabytes. If you no longer use an app, uninstall it through Windows instead of merely deleting its desktop shortcut.</p>
  <div class="lh-callout"><strong>Remember:</strong> Deleting a shortcut does not uninstall the application.</div>
</section>

<section class="lh-section">
  <h2>6. Manage Downloads and Personal Files</h2>
  <ul>
    <li>Review the <strong>Downloads</strong> folder regularly.</li>
    <li>Delete old installers, duplicate downloads, and files you no longer need.</li>
    <li>Move large photos, videos, and archives to another suitable storage location when appropriate.</li>
    <li>Keep important originals backed up before deleting local copies.</li>
  </ul>
</section>

<section class="lh-section">
  <h2>7. Recycle Bin</h2>
  <p>Deleting a file normally sends it to the Recycle Bin. The space may not be fully reclaimed until the item is permanently removed.</p>
  <p>Empty the Recycle Bin only after checking that you do not need the deleted files.</p>
</section>

<section class="lh-section">
  <h2>8. Do Not Delete System Files Randomly</h2>
  <p>Windows and applications depend on many system files and folders. Manually deleting unknown files can cause errors or stop programs from working.</p>
  <div class="lh-callout"><strong>Safety rule:</strong> If you do not know what a file or folder does, do not delete it just because it is large. Use Windows' built-in storage tools first.</div>
</section>

<section class="lh-section">
  <h2>9. Move Large Files Safely</h2>
  <p>If you need to keep large personal files but do not need them on the internal drive every day, consider moving them to external storage or another suitable location.</p>
  <ol>
    <li>Copy the file to the new location.</li>
    <li>Open the copied file and verify it works.</li>
    <li>Only then consider deleting the original if you truly no longer need it there.</li>
  </ol>
</section>

<section class="lh-section">
  <h2>10. Keep Some Free Space</h2>
  <p>A drive that is nearly full can make updates, temporary files, application installs, and normal storage management harder. Treat a low-space warning as a signal to investigate before the drive reaches zero free space.</p>
</section>

<section class="lh-section">
  <h2>Common Disk-Space Problems</h2>
  <table class="table table-bordered">
    <thead><tr><th>Problem</th><th>What to Check</th></tr></thead>
    <tbody>
      <tr><td>Drive is almost full</td><td>Storage categories, large files, unused apps, temporary files</td></tr>
      <tr><td>Update needs more space</td><td>Temporary files, cleanup recommendations, large personal files</td></tr>
      <tr><td>Downloads folder is huge</td><td>Old installers, archives, videos, duplicate downloads</td></tr>
      <tr><td>Free space did not increase</td><td>Recycle Bin, correct drive, and whether the cleanup actually completed</td></tr>
      <tr><td>Unsure what to delete</td><td>Stop and identify the file before removing it</td></tr>
    </tbody>
  </table>
</section>

<section class="lh-section">
  <h2>Practical Activity</h2>
  <ol>
    <li>Open <strong>Settings → System → Storage</strong>.</li>
    <li>Write down the approximate free space on the system drive.</li>
    <li>Identify the three largest storage categories.</li>
    <li>Open <strong>Temporary files</strong> and review the suggested cleanup items.</li>
    <li>Check your Downloads folder for files you no longer need.</li>
    <li>Find one large personal file that could safely be moved to another storage location.</li>
    <li>Check the Recycle Bin before emptying it.</li>
  </ol>
  <p><strong>Goal:</strong> Understand where your storage is going before you remove anything.</p>
</section>

<section class="lh-section">
  <h2>Common Mistakes</h2>
  <ul>
    <li>Deleting important files just because they are large.</li>
    <li>Confusing RAM with storage.</li>
    <li>Deleting a program's folder instead of uninstalling it properly.</li>
    <li>Forgetting to check the Recycle Bin.</li>
    <li>Moving a file and deleting the original without verifying the copy.</li>
    <li>Deleting unknown Windows system files manually.</li>
    <li>Assuming cloud sync automatically means you have an independent backup.</li>
  </ul>
</section>

<section class="lh-section">
  <h2>Quick Self-Check</h2>
  <ol>
    <li>Where can you see storage usage categories in Windows?</li>
    <li>What is Storage Sense used for?</li>
    <li>Why should you review cleanup suggestions before deleting files?</li>
    <li>What should you do with a large file you want to keep but rarely use?</li>
    <li>Why should you avoid deleting unknown system files?</li>
  </ol>
  <details class="mt-3">
    <summary><strong>Show Answers</strong></summary>
    <ol class="mt-3">
      <li>Settings → System → Storage.</li>
      <li>It can automatically clean eligible unnecessary files to help free disk space.</li>
      <li>Because some files may still be needed or may have consequences if removed.</li>
      <li>Consider moving it to another suitable storage location after verifying the copy.</li>
      <li>Because Windows and applications depend on many system files and folders.</li>
    </ol>
  </details>
</section>

<section class="lh-section">
  <h2>Key Takeaway</h2>
  <div class="lh-callout"><strong>Good disk-space management is a process: check usage, identify the real space consumers, clean safely, move files when appropriate, and protect important data before deleting anything.</strong></div>
</section>
HTML;
}

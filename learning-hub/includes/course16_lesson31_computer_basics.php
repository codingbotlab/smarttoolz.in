<?php
declare(strict_types=1);

function lh_course16_lesson31_computer_basics_override(array $lesson, int $position): ?string
{
    if (($lesson['slug'] ?? '') !== 'course-16-lesson-31') {
        return null;
    }

    return <<<'HTML'
<section class="lh-section">
  <h2>Managing Disk Space</h2>
  <p>Disk space is the amount of storage available on your computer for Windows, apps, documents, photos, videos, downloads, and other files. Learning to manage it helps prevent low-storage warnings, failed updates, and unnecessary clutter.</p>

  <div class="lh-callout">
    <strong>Key idea:</strong> Managing disk space does not simply mean deleting files. First find what is using space, decide what is safe to remove or move, and keep important files backed up.
  </div>

  <h3>1. Storage Space vs Memory</h3>
  <p>Do not confuse disk storage with RAM. Storage keeps files even after the computer is turned off. RAM is temporary working memory used while programs are running.</p>
  <ul>
    <li><strong>Storage:</strong> SSD, HDD, USB drive, memory card.</li>
    <li><strong>RAM:</strong> temporary working space for running programs.</li>
  </ul>

  <h3>2. Check How Much Space You Have</h3>
  <p>In Windows, open <strong>Settings → System → Storage</strong>. You can see how the drive is being used across categories such as installed apps, temporary files, system files, documents, and other content.</p>
  <p>You can also open <strong>File Explorer → This PC</strong> to quickly see the free space on your drives.</p>

  <h3>3. Find What Is Taking the Most Space</h3>
  <p>Large videos, old downloads, games, installers, duplicate files, and unused applications can consume a lot of storage.</p>
  <ol>
    <li>Open <strong>Settings → System → Storage</strong>.</li>
    <li>Review the largest categories.</li>
    <li>Open a category before deleting anything.</li>
    <li>Look for files or apps you genuinely no longer need.</li>
  </ol>

  <h3>4. Temporary Files</h3>
  <p>Windows and applications create temporary files while performing tasks. Some can be removed safely through Windows storage tools.</p>
  <p>Use <strong>Settings → System → Storage → Temporary files</strong> and carefully review the categories before selecting <strong>Remove files</strong>.</p>

  <h3>5. Storage Sense</h3>
  <p><strong>Storage Sense</strong> can automatically free space by cleaning eligible unnecessary files such as temporary files and selected Recycle Bin content. Its settings should be reviewed so you understand what Windows may clean automatically.</p>
  <div class="lh-callout lh-callout-info">
    <strong>Important:</strong> Do not assume every item shown by a cleanup tool is disposable. Read the description and check important files before removing anything.
  </div>

  <h3>6. Cleanup Recommendations</h3>
  <p>Windows can provide cleanup recommendations for categories such as temporary files, large or unused files, cloud-synced content, and unused apps. Review the recommendation rather than blindly deleting everything.</p>

  <h3>7. Remove Unused Apps</h3>
  <p>Large applications and games can occupy many gigabytes. If you no longer use an app, uninstall it through Windows instead of merely deleting its desktop shortcut.</p>
  <p><strong>Remember:</strong> deleting a shortcut does not uninstall the application.</p>

  <h3>8. Manage Downloads and Personal Files</h3>
  <p>The Downloads, Videos, Pictures, and Desktop folders can gradually become storage-heavy.</p>
  <ul>
    <li>Delete files you no longer need.</li>
    <li>Move files you want to keep to another drive or external storage.</li>
    <li>Use cloud storage when appropriate.</li>
    <li>Keep important originals backed up before deleting local copies.</li>
  </ul>

  <h3>9. Recycle Bin</h3>
  <p>Deleting a file normally sends it to the Recycle Bin rather than immediately reclaiming all of its space. Empty it only after checking that you do not need the deleted files.</p>

  <h3>10. Do Not Delete System Files Randomly</h3>
  <p>Some folders and files are required by Windows or applications. Manually deleting unknown files from system locations can cause errors or prevent programs from working.</p>
  <div class="lh-callout lh-callout-warning">
    <strong>Safety rule:</strong> If you do not know what a file or folder does, do not delete it just because it looks large. Use Windows' built-in storage and cleanup tools first.
  </div>

  <h3>11. Move Large Files Instead of Deleting Them</h3>
  <p>If you need to keep large photos, videos, project files, or archives but do not need them on the internal drive every day, move them to an external drive or another suitable storage location.</p>
  <p>Always verify the copy before deleting the original.</p>

  <h3>12. Keep Some Free Space</h3>
  <p>A drive that is nearly full can make normal storage management difficult and may interfere with updates or application operations. Treat a low-space warning as a signal to investigate rather than waiting until the drive is completely full.</p>

  <h3>Common Problems</h3>
  <table class="table table-bordered">
    <thead><tr><th>Problem</th><th>What to Check</th></tr></thead>
    <tbody>
      <tr><td>Drive is almost full</td><td>Storage categories, large files, unused apps, temporary files</td></tr>
      <tr><td>Windows update needs more space</td><td>Temporary files, cleanup recommendations, large personal files</td></tr>
      <tr><td>Downloads folder is huge</td><td>Old installers, archives, videos, duplicate downloads</td></tr>
      <tr><td>Free space did not increase</td><td>Recycle Bin, cloud/local copies, correct drive</td></tr>
      <tr><td>Unsure what to delete</td><td>Stop and identify the file before removing it</td></tr>
    </tbody>
  </table>

  <h3>Practical Activity</h3>
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

  <h3>Common Mistakes</h3>
  <ul>
    <li>Deleting important files just because they are large.</li>
    <li>Confusing RAM with storage.</li>
    <li>Deleting a program's folder instead of uninstalling it properly.</li>
    <li>Forgetting to check the Recycle Bin.</li>
    <li>Moving a file and immediately deleting the original without verifying the copy.</li>
    <li>Deleting unknown Windows system files manually.</li>
    <li>Assuming cloud sync automatically means you have a separate backup.</li>
  </ul>

  <h3>Quick Self-Check</h3>
  <ol>
    <li>Where can you see storage usage categories in Windows?</li>
    <li>What is Storage Sense used for?</li>
    <li>Why should you review cleanup suggestions before deleting files?</li>
    <li>What should you do with a large file you want to keep but rarely use?</li>
    <li>Why should you avoid deleting unknown system files?</li>
  </ol>

  <details class="lh-quiz-answer"><summary>Show Answers</summary>
    <ol>
      <li>Settings → System → Storage.</li>
      <li>It can automatically clean eligible unnecessary files to help free disk space.</li>
      <li>Because some files may still be needed or may have consequences if removed.</li>
      <li>Consider moving it to another suitable storage location after verifying the copy.</li>
      <li>Because Windows and applications depend on many system files and folders.</li>
    </ol>
  </details>

  <div class="lh-callout lh-callout-success">
    <strong>Key Takeaway:</strong> Good disk-space management is a process: check usage, identify the real space consumers, clean safely, move files when appropriate, and protect important data before deleting anything.
  </div>
</section>
HTML;
}

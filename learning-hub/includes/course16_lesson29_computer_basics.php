<?php
declare(strict_types=1);

function lh_course16_lesson29_computer_basics_override(array $lesson, int $position): ?string
{
    if ((string)($lesson['slug'] ?? '') !== 'course-16-lesson-29') {
        return null;
    }

    return <<<'HTML'
<section class="lh-section">
  <h2>What Is a Backup?</h2>
  <p>A <strong>backup</strong> is a separate copy of important data that you can use if the original files are deleted, corrupted, damaged, lost, or made unavailable.</p>
  <div class="lh-callout"><strong>Simple idea:</strong> Your original file is the working copy. A backup is another copy kept so you can recover when something goes wrong.</div>
</section>

<section class="lh-section">
  <h2>Why Backups Matter</h2>
  <ul>
    <li>A laptop or storage drive can fail unexpectedly.</li>
    <li>Files can be deleted by mistake.</li>
    <li>Malware or ransomware can make files unavailable.</li>
    <li>A device can be lost, stolen, or physically damaged.</li>
    <li>Software or synchronization mistakes can affect files.</li>
  </ul>
  <p>Backups reduce the impact of these events. They are about protecting your <strong>data</strong>, not simply keeping the computer running.</p>
</section>

<section class="lh-section">
  <h2>Backup vs Copy vs Sync</h2>
  <table class="table table-bordered">
    <thead><tr><th>Concept</th><th>Meaning</th><th>Important point</th></tr></thead>
    <tbody>
      <tr><td>Copy</td><td>Creates another copy of a file or folder.</td><td>The original normally remains unchanged.</td></tr>
      <tr><td>Backup</td><td>Keeps a recoverable copy for protection against loss.</td><td>It should be available when the original is unavailable.</td></tr>
      <tr><td>Sync</td><td>Keeps data synchronized between locations or devices.</td><td>A change or deletion can sometimes be synchronized too, so sync alone is not always a backup.</td></tr>
    </tbody>
  </table>
</section>

<section class="lh-section">
  <h2>The 3-2-1 Backup Idea</h2>
  <p>A useful general strategy is the <strong>3-2-1 rule</strong>:</p>
  <ol>
    <li><strong>3 copies</strong> of important data.</li>
    <li>Stored on at least <strong>2 different types of storage</strong>.</li>
    <li>Keep at least <strong>1 copy in a separate location</strong>.</li>
  </ol>
  <div class="lh-callout"><strong>Example:</strong> Keep your working files on the PC, a backup on an external drive, and another copy in a trusted cloud or separate physical location.</div>
</section>

<section class="lh-section">
  <h2>Common Backup Destinations</h2>
  <ul>
    <li><strong>External HDD:</strong> Useful for large local backups at relatively low cost.</li>
    <li><strong>External SSD:</strong> Fast and portable, useful for frequently accessed backup sets.</li>
    <li><strong>USB flash drive:</strong> Convenient for smaller sets of files, but do not treat one flash drive as your only backup.</li>
    <li><strong>Cloud storage:</strong> Provides an off-device copy and can help when a local device is lost or damaged.</li>
    <li><strong>Network storage:</strong> Useful for households or organizations that need centralized storage and backup.</li>
  </ul>
</section>

<section class="lh-section">
  <h2>What Should You Back Up?</h2>
  <p>Prioritize files that would be difficult or impossible to recreate.</p>
  <ul>
    <li>Personal documents and certificates.</li>
    <li>Photos and videos.</li>
    <li>School or work projects.</li>
    <li>Important spreadsheets and records.</li>
    <li>Creative work and source files.</li>
    <li>Other data that is important to you.</li>
  </ul>
  <p>Operating-system files and applications can often be reinstalled, while personal data may be irreplaceable.</p>
</section>

<section class="lh-section">
  <h2>How to Make a Simple Local Backup</h2>
  <ol>
    <li>Connect a trusted external drive with enough free space.</li>
    <li>Open <strong>File Explorer</strong> with <strong>Windows + E</strong>.</li>
    <li>Find the important folders you want to protect.</li>
    <li>Copy those folders to a clearly named backup folder on the external drive.</li>
    <li>Wait for the copy operation to finish.</li>
    <li>Open a few copied files from the backup and confirm they work.</li>
    <li>Safely eject the external drive when finished.</li>
  </ol>
  <div class="lh-callout"><strong>Verification matters:</strong> A backup is useful only if the data can actually be recovered.</div>
</section>

<section class="lh-section">
  <h2>Automatic Backups</h2>
  <p>Automatic backup tools can reduce the chance of forgetting to make backups. Depending on the Windows setup, you may use features such as <strong>File History</strong>, Windows backup features, or a trusted backup application.</p>
  <p>Automatic backup does not mean you should ignore the backup destination. Periodically check that backups are completing and that important files are included.</p>
</section>

<section class="lh-section">
  <h2>Backup Frequency</h2>
  <p>The right schedule depends on how often your data changes and how much work you could afford to lose.</p>
  <table class="table table-bordered">
    <thead><tr><th>Data</th><th>Example approach</th></tr></thead>
    <tbody>
      <tr><td>Frequently changing work</td><td>Frequent or automatic backups</td></tr>
      <tr><td>Photos and personal documents</td><td>Regular backups whenever new important files are added</td></tr>
      <tr><td>Rarely changing archives</td><td>Back up when the archive changes, then verify it</td></tr>
    </tbody>
  </table>
</section>

<section class="lh-section">
  <h2>Backup Security</h2>
  <ul>
    <li>Protect sensitive backups from unauthorized access.</li>
    <li>Use strong account security and multi-factor authentication for cloud accounts.</li>
    <li>Do not leave an external backup drive permanently connected if it does not need to be.</li>
    <li>Keep at least one backup separate from the computer so a single incident cannot affect every copy.</li>
    <li>Be careful with ransomware: a constantly connected writable backup can also be affected by malicious software.</li>
  </ul>
</section>

<section class="lh-section">
  <h2>How to Test a Backup</h2>
  <ol>
    <li>Choose a non-critical file from the backup.</li>
    <li>Open it directly from the backup location.</li>
    <li>Check that the file is readable and appears complete.</li>
    <li>For a larger backup system, periodically perform a controlled restore test.</li>
  </ol>
  <p><strong>Remember:</strong> “Backup completed” and “I can restore my data” are related but not identical checks.</p>
</section>

<section class="lh-section">
  <h2>If You Accidentally Delete a File</h2>
  <ol>
    <li>Stop making unnecessary changes to the affected storage when possible.</li>
    <li>Check the <strong>Recycle Bin</strong>.</li>
    <li>Check your backup or cloud version history if available.</li>
    <li>Use the recovery options provided by your backup system.</li>
    <li>If the data is extremely important and no backup exists, avoid random recovery software or repeated writes and consider professional recovery advice.</li>
  </ol>
</section>

<section class="lh-section">
  <h2>Practical Activity</h2>
  <ol>
    <li>Choose a few non-sensitive practice files.</li>
    <li>Create a folder named <strong>Computer Basics Backup Practice</strong>.</li>
    <li>Copy the files to a trusted external drive or suitable cloud location.</li>
    <li>Open one copied file to verify the backup.</li>
    <li>Write down where the backup is stored and when it was created.</li>
    <li>Safely disconnect the external drive if you used one.</li>
  </ol>
  <p><strong>Goal:</strong> Practice the complete backup cycle: select → copy → verify → record → protect.</p>
</section>

<section class="lh-section">
  <h2>Common Mistakes</h2>
  <ul>
    <li>Keeping the only copy of important data on one device.</li>
    <li>Assuming cloud synchronization is automatically a complete backup.</li>
    <li>Never testing whether a backup can be opened or restored.</li>
    <li>Keeping every backup connected to the same computer all the time.</li>
    <li>Backing up only after a data-loss event.</li>
    <li>Forgetting where a backup was stored or which files it contains.</li>
  </ul>
</section>

<section class="lh-section">
  <h2>Quick Self-Check</h2>
  <ol>
    <li>What is a backup?</li>
    <li>What does the 3-2-1 rule mean?</li>
    <li>Why is sync not always the same as backup?</li>
    <li>Why should you test backups?</li>
    <li>Why is a separate backup location useful?</li>
  </ol>
  <details class="mt-3">
    <summary><strong>Show Answers</strong></summary>
    <div class="lh-callout mt-3">
      <p><strong>1.</strong> A separate copy of important data kept for recovery if the original is lost or unavailable.</p>
      <p><strong>2.</strong> Three copies, on two different storage types, with one copy kept separately.</p>
      <p><strong>3.</strong> Synchronization can also synchronize changes or deletions, so it does not necessarily provide an independent recovery copy.</p>
      <p><strong>4.</strong> To confirm that the data is actually readable and recoverable.</p>
      <p><strong>5.</strong> It reduces the chance that one event, such as device failure or theft, destroys every copy.</p>
    </div>
  </details>
</section>

<section class="lh-section">
  <h2>Key Takeaway</h2>
  <div class="lh-callout"><strong>Backups protect your data from unexpected loss. Keep more than one copy, separate important copies when possible, and verify that you can actually recover the files.</strong></div>
</section>
HTML;
}

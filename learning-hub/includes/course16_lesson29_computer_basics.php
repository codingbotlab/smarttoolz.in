<?php
declare(strict_types=1);

function lh_course16_lesson29_computer_basics_override(array $lesson, int $position): ?string
{
    if ((string)($lesson['slug'] ?? '') !== 'course-16-lesson-29') {
        return null;
    }

    return <<<'HTML'
<section class="lh-lesson-section">
  <h2>What Is External Storage?</h2>
  <p><strong>External storage</strong> is a storage device that connects to your computer from outside the computer's main internal drive. It is useful for storing, moving, archiving, and backing up files.</p>
  <div class="lh-callout"><strong>Simple idea:</strong> Your internal drive is the storage inside your PC. An external drive gives you additional storage that you can connect when you need it.</div>
</section>

<section class="lh-lesson-section">
  <h2>Common Types of External Storage</h2>
  <ul>
    <li><strong>USB flash drive:</strong> Small, portable, and convenient for transferring files.</li>
    <li><strong>External HDD:</strong> Usually offers large capacity at a relatively low cost, but contains moving parts.</li>
    <li><strong>External SSD:</strong> Faster and more resistant to physical movement than a traditional HDD, with no spinning platters.</li>
    <li><strong>Memory card:</strong> Common in cameras, phones, and other devices. A card reader may be needed.</li>
  </ul>
</section>

<section class="lh-lesson-section">
  <h2>Why Use an External Drive?</h2>
  <ul>
    <li>Keep extra copies of important documents and photos.</li>
    <li>Move large files between computers.</li>
    <li>Free space on an internal drive by moving appropriate files.</li>
    <li>Keep an archive of files you do not need every day.</li>
    <li>Create an additional backup that is not stored on the computer's internal drive.</li>
  </ul>
  <div class="lh-callout"><strong>Important:</strong> An external drive is not automatically a backup. If the only copy of a file is on that external drive, losing or damaging the drive can still mean losing the file.</div>
</section>

<section class="lh-lesson-section">
  <h2>Connecting an External Drive</h2>
  <ol>
    <li>Connect the drive to a compatible USB port or other supported connection.</li>
    <li>Wait for Windows to detect the device.</li>
    <li>Open <strong>File Explorer</strong> with <strong>Windows + E</strong>.</li>
    <li>Look under <strong>This PC</strong> for the new drive.</li>
    <li>Open it and confirm that the expected folders or files are visible.</li>
  </ol>
  <p>The drive may appear with a letter such as <strong>D:</strong>, <strong>E:</strong>, or another available drive letter. The exact letter can vary between computers.</p>
</section>

<section class="lh-lesson-section">
  <h2>Copying Files to External Storage</h2>
  <ol>
    <li>Open the source folder in File Explorer.</li>
    <li>Select the files or folders you want to copy.</li>
    <li>Press <strong>Ctrl + C</strong>.</li>
    <li>Open the external drive.</li>
    <li>Open or create the destination folder.</li>
    <li>Press <strong>Ctrl + V</strong>.</li>
    <li>Wait until the copy operation finishes before disconnecting the drive.</li>
  </ol>
  <p>For important data, check that the copied files actually open from the external drive before assuming the backup or transfer worked.</p>
</section>

<section class="lh-lesson-section">
  <h2>Copy vs Move</h2>
  <table class="table table-bordered">
    <thead><tr><th>Action</th><th>What happens?</th><th>Typical use</th></tr></thead>
    <tbody>
      <tr><td>Copy</td><td>Creates another copy while the original remains.</td><td>Backup or transfer</td></tr>
      <tr><td>Move</td><td>Places the item in a new location and removes it from the old location.</td><td>Reorganizing storage</td></tr>
    </tbody>
  </table>
  <div class="lh-callout"><strong>For backup work, prefer copying.</strong> Moving your only copy to an external drive does not protect you if that drive fails.</div>
</section>

<section class="lh-lesson-section">
  <h2>Safely Disconnecting the Drive</h2>
  <p>Before unplugging an external drive, make sure file transfers have finished and close files that are being used from the drive.</p>
  <ol>
    <li>Finish all copy or move operations.</li>
    <li>Close File Explorer windows or applications using files on the drive.</li>
    <li>Use Windows' <strong>Safely Remove Hardware</strong> or the drive's eject option when available.</li>
    <li>Wait for Windows to indicate that the device can be removed.</li>
    <li>Disconnect the cable or device.</li>
  </ol>
  <p>This reduces the chance of interrupted writes and file-system problems.</p>
</section>

<section class="lh-lesson-section">
  <h2>External Storage and Backups</h2>
  <p>A good backup plan should protect you from more than one type of failure. For important files, consider keeping multiple copies in different locations or on different types of storage.</p>
  <div class="lh-callout"><strong>3-2-1 concept:</strong> Keep 3 copies of important data, on 2 different types of storage, with 1 copy kept somewhere separate. This is a useful backup strategy, not a requirement for every small file.</div>
  <p>Cloud storage can provide an off-device copy, while an external drive can provide a local copy that may be useful when internet access is unavailable.</p>
</section>

<section class="lh-lesson-section">
  <h2>When an External Drive Is Not Detected</h2>
  <ol>
    <li>Unplug and reconnect the drive.</li>
    <li>Try a different USB port.</li>
    <li>If possible, try another compatible cable.</li>
    <li>Check <strong>File Explorer → This PC</strong>.</li>
    <li>Open <strong>Disk Management</strong> and see whether Windows detects the drive.</li>
    <li>Try the drive on another computer if practical.</li>
  </ol>
  <div class="lh-callout"><strong>Do not immediately format a drive containing important data.</strong> Formatting can erase the information you are trying to recover.</div>
</section>

<section class="lh-lesson-section">
  <h2>Common External-Storage Problems</h2>
  <table class="table table-bordered">
    <thead><tr><th>Problem</th><th>Possible reason</th><th>First step</th></tr></thead>
    <tbody>
      <tr><td>Drive not visible</td><td>Port, cable, power, or detection issue</td><td>Reconnect and try another port</td></tr>
      <tr><td>Very slow transfer</td><td>Large files, slower hardware, or connection limitations</td><td>Check the connection and allow the transfer to finish</td></tr>
      <tr><td>Copy fails</td><td>Not enough space, permissions, or file-system issue</td><td>Check free space and the error message</td></tr>
      <tr><td>Drive disconnects</td><td>Cable, port, power, or hardware problem</td><td>Try another cable/port and avoid moving the drive during transfers</td></tr>
    </tbody>
  </table>
</section>

<section class="lh-lesson-section">
  <h2>Security Tips</h2>
  <ul>
    <li>Do not plug an unknown USB device into your computer just because you found it.</li>
    <li>Scan unfamiliar files with your security software before opening them.</li>
    <li>Keep sensitive files protected and do not leave an external drive unattended.</li>
    <li>If an external drive contains your only backup, consider creating another backup.</li>
    <li>Do not remove a drive while important data is actively being written.</li>
  </ul>
</section>

<section class="lh-lesson-section">
  <h2>Practical Activity</h2>
  <ol>
    <li>Connect a trusted USB flash drive or external drive.</li>
    <li>Open <strong>File Explorer → This PC</strong> and identify the external drive.</li>
    <li>Create a folder named <strong>Computer Basics Backup Practice</strong>.</li>
    <li>Copy a few non-sensitive practice files into that folder.</li>
    <li>Open one copied file directly from the external drive.</li>
    <li>Safely eject the drive.</li>
    <li>Reconnect it and confirm that the copied files are still present.</li>
  </ol>
  <p><strong>Goal:</strong> Learn the complete cycle: connect → identify → copy → verify → safely eject → reconnect.</p>
</section>

<section class="lh-lesson-section">
  <h2>Common Mistakes</h2>
  <ul>
    <li>Assuming an external drive is a backup without checking for another copy.</li>
    <li>Unplugging the drive while files are still transferring.</li>
    <li>Moving files when the goal is to create a backup.</li>
    <li>Formatting a drive before checking whether it contains important data.</li>
    <li>Using unknown USB devices without considering security risks.</li>
  </ul>
</section>

<section class="lh-lesson-section">
  <h2>Quick Self-Check</h2>
  <ol>
    <li>What is the difference between an external HDD and an external SSD?</li>
    <li>Why is copying safer than moving when creating a backup?</li>
    <li>Why should you safely eject an external drive?</li>
    <li>What should you avoid doing if an important drive is not detected?</li>
    <li>What does the 3-2-1 backup concept mean?</li>
  </ol>
  <details class="mt-3">
    <summary><strong>Show answers</strong></summary>
    <div class="lh-callout mt-3">
      <p><strong>1.</strong> An HDD uses spinning magnetic disks; an SSD uses flash memory and has no moving parts.</p>
      <p><strong>2.</strong> Copying leaves the original intact, so you have another copy rather than moving your only copy.</p>
      <p><strong>3.</strong> It helps avoid interrupting active writes and reduces the chance of file-system or data problems.</p>
      <p><strong>4.</strong> Do not format it immediately. First check connections, Disk Management, and other safe troubleshooting options.</p>
      <p><strong>5.</strong> Three copies of important data, on two different storage types, with one copy kept separately.</p>
    </div>
  </details>
</section>

<section class="lh-lesson-section">
  <h2>Key Takeaway</h2>
  <div class="lh-callout"><strong>External storage is useful for extra space, file transfers, and backups—but a backup only helps when there is a separate copy that you can actually recover.</strong></div>
</section>
HTML;
}

<?php
declare(strict_types=1);

function lh_course16_lesson9_computer_basics_override(array $lesson, int $position): ?string
{
    if ((string)($lesson['slug'] ?? '') !== 'course-16-lesson-9') {
        return null;
    }

    return <<<'HTML'
<article class="lh-prose">
  <div class="lh-lesson-intro">
    <p><strong>Copying and moving files are basic but important computer skills.</strong> Copy creates another version of a file, while Move changes where the existing file is stored. Understanding the difference helps you avoid duplicates, lost files and accidental overwrites.</p>
  </div>

  <h2>1. Copy vs Move</h2>
  <div class="table-responsive"><table class="table table-bordered align-middle"><thead><tr><th>Action</th><th>What happens?</th><th>Example</th></tr></thead><tbody>
    <tr><td><strong>Copy</strong></td><td>The original remains and a second copy is created.</td><td>Copy a photo from Downloads to Pictures.</td></tr>
    <tr><td><strong>Move</strong></td><td>The same file is placed in a new location.</td><td>Move a document from Downloads to Documents.</td></tr>
  </tbody></table></div>
  <div class="alert alert-primary"><strong>Easy rule:</strong> Copy = keep the original. Move = change its location.</div>

  <h2>2. Using File Explorer</h2>
  <p>In Windows, open <strong>File Explorer</strong> with <strong>Win + E</strong>. Select the file or folder, choose the required action, and then open the destination folder.</p>
  <ol>
    <li>Select the file or folder.</li>
    <li>For copying, press <strong>Ctrl + C</strong>.</li>
    <li>For moving, press <strong>Ctrl + X</strong>.</li>
    <li>Open the destination folder.</li>
    <li>Press <strong>Ctrl + V</strong> to paste.</li>
  </ol>

  <h2>3. Why Ctrl + C and Ctrl + X are different</h2>
  <p><strong>Ctrl + C</strong> places a copy operation on the clipboard. The source stays where it is. <strong>Ctrl + X</strong> marks the selected item for moving. After you paste it into another location, the item is transferred rather than duplicated.</p>
  <p>You can also right-click a selected item and use <strong>Copy</strong> or <strong>Cut</strong>, then right-click the destination and choose <strong>Paste</strong>.</p>

  <h2>4. Copying between drives or USB devices</h2>
  <p>You can copy files between folders, drives and removable devices such as USB flash drives. Before removing a USB device, use Windows' safe-eject option when appropriate so pending writes can finish.</p>
  <p>When transferring many files, check the destination after the operation. Do not assume that seeing a progress bar means you have verified every important file.</p>

  <h2>5. What happens when a file already exists?</h2>
  <p>If the destination already contains a file with the same name, Windows may ask whether to replace it, keep both files, or compare the files. Do not automatically choose <strong>Replace</strong> when the existing file may be important.</p>
  <p>A safer approach is to compare names, dates, sizes and—when necessary—the actual contents before replacing anything.</p>

  <h2>6. Moving a file does not create a backup</h2>
  <p>This is a very important beginner concept. Moving a file from one folder to another does not protect it from drive failure, accidental deletion or ransomware. A backup is a separate copy stored in another safe location.</p>
  <div class="alert alert-warning"><strong>Remember:</strong> Organizing a file and backing up a file are two different tasks.</div>

  <h2>7. Undo an accidental move or copy</h2>
  <p>If you make a mistake immediately, <strong>Ctrl + Z</strong> can often undo the most recent File Explorer operation. Use it carefully and check what action is being undone before continuing with other file operations.</p>

  <h2>8. Drag and drop: useful, but understand the result</h2>
  <p>Dragging files can copy or move them depending on where you drag them and the storage location involved. If you want predictable behavior, use keyboard shortcuts or right-click drag and choose the exact action.</p>

  <h2>9. Avoiding duplicate files</h2>
  <p>Repeatedly copying the same file can create confusing versions such as <em>report (1)</em>, <em>report (2)</em>, and so on. Use meaningful names and a clear folder structure instead of creating many unexplained copies.</p>
  <p>For important work, version names such as <strong>project-v1</strong>, <strong>project-v2</strong>, and <strong>project-final</strong> can be useful, but avoid creating multiple files all called “final”.</p>

  <h2>10. Practical exercise</h2>
  <ol>
    <li>Create a folder called <strong>Computer-Practice</strong> inside Documents.</li>
    <li>Create three text files inside it: <strong>notes.txt</strong>, <strong>ideas.txt</strong>, and <strong>backup-notes.txt</strong>.</li>
    <li>Create a subfolder called <strong>Archive</strong>.</li>
    <li>Copy <strong>notes.txt</strong> into Archive. Confirm that the original still exists.</li>
    <li>Move <strong>ideas.txt</strong> into Archive. Confirm that it is no longer in the original folder.</li>
    <li>Open both locations and compare the results.</li>
    <li>Practice <strong>Ctrl + Z</strong> on a harmless test operation and observe what changes.</li>
  </ol>

  <h2>11. Troubleshooting file-copy problems</h2>
  <div class="table-responsive"><table class="table table-bordered align-middle"><thead><tr><th>Problem</th><th>First thing to check</th></tr></thead><tbody>
    <tr><td>Not enough space</td><td>Check free storage on the destination drive.</td></tr>
    <tr><td>File is in use</td><td>Close the program using the file and try again.</td></tr>
    <tr><td>Access denied</td><td>Check permissions and whether the destination is protected.</td></tr>
    <tr><td>USB transfer is slow</td><td>Check the USB port, device and size/number of files.</td></tr>
    <tr><td>Duplicate already exists</td><td>Compare files before replacing or keeping another copy.</td></tr>
  </tbody></table></div>

  <h2>Common beginner mistakes</h2>
  <ul>
    <li>Using Move when a backup copy was actually needed.</li>
    <li>Replacing an existing file without checking it.</li>
    <li>Creating many duplicate files with unclear names.</li>
    <li>Removing a USB device while a transfer is still in progress.</li>
    <li>Assuming a file is backed up simply because it exists in another folder on the same drive.</li>
    <li>Moving system files or application folders without knowing their purpose.</li>
  </ul>

  <h2>Quick self-check</h2>
  <ol>
    <li>What is the main difference between Copy and Move?</li>
    <li>What does Ctrl + C do?</li>
    <li>What does Ctrl + X do?</li>
    <li>Why is moving a file not the same as backing it up?</li>
    <li>What should you check before replacing a file with the same name?</li>
  </ol>

  <h2>Key takeaway</h2>
  <p><strong>Copy when you need another version; Move when you need to reorganize the existing file.</strong> Use clear folders, meaningful names and careful checks whenever important files are transferred.</p>
</article>
HTML;
}

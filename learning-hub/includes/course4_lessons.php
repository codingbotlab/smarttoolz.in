<?php
declare(strict_types=1);

/**
 * Topic-specific overrides for Course 4 lessons that are not stored in the
 * general hand-written override map. Kept separate so the main override file
 * stays maintainable as the curriculum grows.
 */
function lh_course4_override(array $lesson): ?string
{
    $slug = (string)($lesson['slug'] ?? '');

    if ($slug === 'course-4-lesson-9') {
        return <<<'HTML'
<article class="lh-prose">
  <div class="lh-lesson-intro">
    <p><strong>Common computer-use patterns</strong> are repeatable ways people interact with a computer: open a program, provide input, work with data, save the result, and verify what happened. Recognizing these patterns helps beginners learn new software faster because the same ideas appear across many applications.</p>
  </div>
  <h2>1. The basic task pattern</h2>
  <p>Most everyday computer tasks can be broken into a simple sequence:</p>
  <ol><li><strong>Goal:</strong> decide what you want to accomplish.</li><li><strong>Open:</strong> choose the correct application, file or website.</li><li><strong>Input:</strong> enter or select the information required.</li><li><strong>Process:</strong> let the application perform the requested operation.</li><li><strong>Save or publish:</strong> keep the result in the appropriate location.</li><li><strong>Verify:</strong> check that the result is correct before moving on.</li></ol>
  <h2>2. Why patterns matter</h2>
  <p>A beginner may think every application works completely differently. In reality, many applications share familiar patterns such as menus, toolbars, search boxes, dialogs, save commands, undo/redo, copy/paste and confirmation messages.</p>
  <div class="alert alert-primary"><strong>Smart tip:</strong> When learning a new application, first look for controls you already know. Familiar patterns reduce the amount of new information you have to learn.</div>
  <h2>3. Input → processing → output</h2>
  <div class="table-responsive"><table class="table table-bordered align-middle"><thead><tr><th>Pattern stage</th><th>Example</th><th>What to check</th></tr></thead><tbody><tr><td>Input</td><td>Typing a value into a spreadsheet cell</td><td>Is the value entered correctly?</td></tr><tr><td>Processing</td><td>A formula calculates a total</td><td>Is the formula using the intended cells?</td></tr><tr><td>Output</td><td>The calculated total appears</td><td>Does the result make sense?</td></tr><tr><td>Storage</td><td>The spreadsheet is saved</td><td>Is it saved in the correct location and format?</td></tr></tbody></table></div>
  <h2>4. Search, select, change, verify</h2>
  <p>Another common pattern appears when working with files, settings or large lists:</p>
  <ol><li><strong>Search:</strong> find the item you need.</li><li><strong>Select:</strong> confirm you are acting on the correct item.</li><li><strong>Change:</strong> perform the intended action.</li><li><strong>Verify:</strong> confirm the change actually happened.</li></ol>
  <p>This pattern is especially useful when deleting, renaming, moving or changing settings. Verification prevents small mistakes from becoming larger problems.</p>
  <h2>5. Save, undo and recovery</h2>
  <p>Many applications provide a way to reverse recent changes. <strong>Undo</strong> is useful for correcting an accidental edit, while a saved copy provides a more durable recovery point. These are different protections and neither should replace a proper backup for important data.</p>
  <ul><li>Use <kbd>Ctrl</kbd> + <kbd>Z</kbd> for a recent mistake when the application supports it.</li><li>Save deliberately after meaningful work.</li><li>For important files, keep an independent backup rather than relying only on Undo.</li></ul>
  <h2>6. A real-world example</h2>
  <p>Suppose you need to prepare a monthly report. You open the spreadsheet, locate the source data, enter or import new values, calculate totals, inspect unusual results, save the updated file, and finally reopen or review it to confirm the correct version was saved. The application may be different from the one you used yesterday, but the underlying workflow is very similar.</p>
  <h2>7. Troubleshooting with patterns</h2>
  <p>When something goes wrong, avoid changing many things at once. Return to the normal workflow and identify the first stage that failed.</p>
  <ul><li>If the program does not open, investigate the launch step.</li><li>If the wrong result appears, inspect the input and processing steps.</li><li>If the result disappears, inspect saving and file location.</li><li>If the change looks correct but is not visible elsewhere, verify synchronization, refresh state or permissions as appropriate.</li></ul>
  <h2>Practice task</h2>
  <p>Choose one task you perform regularly, such as creating a document, sending an email or organizing a folder. Write its workflow as six steps using <strong>goal → open → input → process → save → verify</strong>. Then identify one step where you usually make mistakes and describe how you could verify it earlier.</p>
  <h2>Common mistakes</h2>
  <ul><li>Starting work without defining the desired result.</li><li>Editing the wrong file or record.</li><li>Skipping verification because the operation appeared to succeed.</li><li>Assuming Undo is the same as a backup.</li><li>Changing multiple settings simultaneously during troubleshooting.</li></ul>
  <h2>Quick check</h2>
  <ol><li>What six stages can describe many everyday computer tasks?</li><li>Why is verification important after changing a file or setting?</li><li>How is Undo different from a backup?</li></ol>
  <h2>Key takeaway</h2>
  <p><strong>Learning computer skills becomes easier when you recognize repeatable patterns. Define the goal, work through the inputs and processing steps, save the result, and always verify the outcome.</strong></p>
</article>
HTML;
    }

    if ($slug === 'course-4-lesson-10') {
        return <<<'HTML'
<article class="lh-prose">
  <div class="lh-lesson-intro">
    <p><strong>This is your Computer Basics capstone.</strong> Instead of learning one more isolated feature, you will combine hardware, storage, files, keyboard/mouse skills, internet basics, safe working habits and repeatable computer workflows into one practical routine.</p>
  </div>

  <h2>What you should be able to do</h2>
  <ul>
    <li>Identify the main hardware components and explain their jobs.</li>
    <li>Explain the difference between RAM and permanent storage.</li>
    <li>Create, organize, copy, move and rename files safely.</li>
    <li>Use common keyboard and mouse actions efficiently.</li>
    <li>Describe the difference between the internet, a website, a browser and a URL.</li>
    <li>Use a simple goal → input → process → save → verify workflow.</li>
    <li>Recognize basic safety risks such as suspicious downloads, weak passwords and missing backups.</li>
  </ul>

  <h2>Part 1: Identify a computer's major parts</h2>
  <p>Look at the computer you normally use. Identify the processor, RAM, storage, display, keyboard, pointing device and network connection. You do not need to open the case. Use the operating system's system information or task manager when you need to confirm specifications.</p>
  <div class="table-responsive"><table class="table table-bordered align-middle"><thead><tr><th>Component</th><th>Question to answer</th></tr></thead><tbody>
    <tr><td>CPU</td><td>What processor executes the computer's instructions?</td></tr>
    <tr><td>RAM</td><td>How much working memory is available?</td></tr>
    <tr><td>Storage</td><td>Is the main drive an SSD or HDD, and how much free space remains?</td></tr>
    <tr><td>GPU</td><td>What graphics processor is being used?</td></tr>
    <tr><td>Network</td><td>Is the computer connected through Wi-Fi, Ethernet or another connection?</td></tr>
  </tbody></table></div>

  <h2>Part 2: Build a clean file workspace</h2>
  <p>Create a folder named <code>Computer-Basics-Capstone</code>. Inside it create:</p>
  <pre><code>Computer-Basics-Capstone/
├── Notes/
├── Practice/
├── Screenshots/
└── Archive/</code></pre>
  <p>Create a text document inside <code>Notes</code> called <code>my-computer-checklist.txt</code>. Write your CPU, RAM, storage type and free-space observations in it. Do not include passwords, private keys or other sensitive information.</p>

  <h2>Part 3: Demonstrate file operations</h2>
  <ol>
    <li>Create a small practice note in <code>Practice</code>.</li>
    <li>Copy the note into <code>Archive</code> and confirm that both copies exist.</li>
    <li>Rename the copied version to include a version number, such as <code>practice-v02.txt</code>.</li>
    <li>Move the original note into <code>Notes</code>.</li>
    <li>Open the moved file and verify that its content is unchanged.</li>
  </ol>
  <p>The important skill is not memorizing menu locations. It is knowing whether you intended to <strong>copy</strong>, <strong>move</strong> or <strong>rename</strong>, then checking the result.</p>

  <h2>Part 4: Use keyboard shortcuts</h2>
  <p>In your practice document, type two or three sentences and deliberately edit them using these common shortcuts:</p>
  <ul>
    <li><kbd>Ctrl</kbd> + <kbd>C</kbd> — copy the selected text.</li>
    <li><kbd>Ctrl</kbd> + <kbd>V</kbd> — paste it.</li>
    <li><kbd>Ctrl</kbd> + <kbd>X</kbd> — cut the selection.</li>
    <li><kbd>Ctrl</kbd> + <kbd>Z</kbd> — undo the last supported action.</li>
    <li><kbd>Ctrl</kbd> + <kbd>S</kbd> — save the document in applications that support the shortcut.</li>
  </ul>
  <div class="alert alert-primary"><strong>Skill check:</strong> Do not just press the shortcuts. Explain what state changed after each one. This turns shortcut memorization into an actual understanding of the workflow.</div>

  <h2>Part 5: Complete a safe web task</h2>
  <p>Open your browser and visit a trusted website you already know. Identify the browser, the website name and the URL. Then perform a simple search for a non-sensitive topic.</p>
  <p>Before opening an unfamiliar result, inspect the domain and ask whether the page matches what you expected. Do not download unknown files just because a page tells you that you need an urgent update or a special player.</p>

  <h2>Part 6: Apply the troubleshooting method</h2>
  <p>Imagine that your saved document cannot be found. Use this sequence instead of randomly changing settings:</p>
  <ol>
    <li><strong>Define the problem:</strong> the file was saved, but its location is unknown.</li>
    <li><strong>Search:</strong> use the operating system's file search with the filename.</li>
    <li><strong>Check recent files:</strong> look at the application's recent-document list if available.</li>
    <li><strong>Verify location:</strong> check common folders such as Documents, Downloads or the project folder.</li>
    <li><strong>Check the backup:</strong> if the file is important and still missing, use your known backup or synchronization history.</li>
  </ol>
  <p>This method is safer than immediately reinstalling software, changing system settings or deleting files.</p>

  <h2>Part 7: Safety review</h2>
  <div class="table-responsive"><table class="table table-bordered align-middle"><thead><tr><th>Risk</th><th>Safer habit</th></tr></thead><tbody>
    <tr><td>Weak or reused passwords</td><td>Use long, unique passwords and appropriate multi-factor authentication.</td></tr>
    <tr><td>Suspicious links</td><td>Check the sender, destination and context before opening.</td></tr>
    <tr><td>Unknown downloads</td><td>Download software from trusted sources and verify what you are installing.</td></tr>
    <tr><td>No backup</td><td>Keep important data in a separate, recoverable backup.</td></tr>
    <tr><td>Outdated software</td><td>Install security and operating-system updates from trusted update mechanisms.</td></tr>
    <tr><td>Public or shared computer</td><td>Sign out, avoid saving credentials, and do not leave sensitive files behind.</td></tr>
  </tbody></table></div>

  <h2>Capstone challenge</h2>
  <p>Complete the entire workflow without following a written step-by-step guide:</p>
  <ol>
    <li>Create a new folder for a small learning project.</li>
    <li>Create a document describing the project.</li>
    <li>Save it with a clear filename.</li>
    <li>Make a copy as a second version.</li>
    <li>Move the original into a logical folder.</li>
    <li>Open a browser and find one reliable reference for the project.</li>
    <li>Record the reference in your document.</li>
    <li>Save the final document and verify that you can find it again.</li>
    <li>If possible, make a backup copy in a separate location.</li>
  </ol>

  <h2>Self-assessment</h2>
  <p>Give yourself one point for each statement you can demonstrate without help:</p>
  <ul>
    <li>I can explain CPU, RAM and storage in simple language.</li>
    <li>I can organize files into logical folders.</li>
    <li>I know when Copy and Move produce different results.</li>
    <li>I can use basic keyboard shortcuts.</li>
    <li>I can identify a browser, website and URL.</li>
    <li>I can search for a missing file systematically.</li>
    <li>I can describe at least three safe computer habits.</li>
    <li>I verify important results instead of assuming they worked.</li>
  </ul>
  <p><strong>8/8:</strong> You have a strong beginner foundation. <strong>5–7:</strong> revisit the items you could not demonstrate. <strong>Below 5:</strong> repeat the relevant earlier lessons and perform the capstone again.</p>

  <h2>Final takeaway</h2>
  <p><strong>Computer literacy is the ability to understand what the machine is doing, choose the right action, organize your information, work safely and verify the result.</strong> You do not need to memorize every setting. You need a reliable mental model and the confidence to investigate problems one step at a time.</p>
</article>
HTML;
    }

    return null;
}

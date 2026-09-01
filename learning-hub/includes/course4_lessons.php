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
  <ol>
    <li><strong>Goal:</strong> decide what you want to accomplish.</li>
    <li><strong>Open:</strong> choose the correct application, file or website.</li>
    <li><strong>Input:</strong> enter or select the information required.</li>
    <li><strong>Process:</strong> let the application perform the requested operation.</li>
    <li><strong>Save or publish:</strong> keep the result in the appropriate location.</li>
    <li><strong>Verify:</strong> check that the result is correct before moving on.</li>
  </ol>

  <h2>2. Why patterns matter</h2>
  <p>A beginner may think every application works completely differently. In reality, many applications share familiar patterns such as menus, toolbars, search boxes, dialogs, save commands, undo/redo, copy/paste and confirmation messages.</p>
  <div class="alert alert-primary"><strong>Smart tip:</strong> When learning a new application, first look for controls you already know. Familiar patterns reduce the amount of new information you have to learn.</div>

  <h2>3. Input → processing → output</h2>
  <div class="table-responsive"><table class="table table-bordered align-middle"><thead><tr><th>Pattern stage</th><th>Example</th><th>What to check</th></tr></thead><tbody>
    <tr><td>Input</td><td>Typing a value into a spreadsheet cell</td><td>Is the value entered correctly?</td></tr>
    <tr><td>Processing</td><td>A formula calculates a total</td><td>Is the formula using the intended cells?</td></tr>
    <tr><td>Output</td><td>The calculated total appears</td><td>Does the result make sense?</td></tr>
    <tr><td>Storage</td><td>The spreadsheet is saved</td><td>Is it saved in the correct location and format?</td></tr>
  </tbody></table></div>

  <h2>4. Search, select, change, verify</h2>
  <p>Another common pattern appears when working with files, settings or large lists:</p>
  <ol>
    <li><strong>Search:</strong> find the item you need.</li>
    <li><strong>Select:</strong> confirm you are acting on the correct item.</li>
    <li><strong>Change:</strong> perform the intended action.</li>
    <li><strong>Verify:</strong> confirm the change actually happened.</li>
  </ol>
  <p>This pattern is especially useful when deleting, renaming, moving or changing settings. Verification prevents small mistakes from becoming larger problems.</p>

  <h2>5. Save, undo and recovery</h2>
  <p>Many applications provide a way to reverse recent changes. <strong>Undo</strong> is useful for correcting an accidental edit, while a saved copy provides a more durable recovery point. These are different protections and neither should replace a proper backup for important data.</p>
  <ul>
    <li>Use <kbd>Ctrl</kbd> + <kbd>Z</kbd> for a recent mistake when the application supports it.</li>
    <li>Save deliberately after meaningful work.</li>
    <li>For important files, keep an independent backup rather than relying only on Undo.</li>
  </ul>

  <h2>6. A real-world example</h2>
  <p>Suppose you need to prepare a monthly report. You open the spreadsheet, locate the source data, enter or import new values, calculate totals, inspect unusual results, save the updated file, and finally reopen or review it to confirm the correct version was saved. The application may be different from the one you used yesterday, but the underlying workflow is very similar.</p>

  <h2>7. Troubleshooting with patterns</h2>
  <p>When something goes wrong, avoid changing many things at once. Return to the normal workflow and identify the first stage that failed.</p>
  <ul>
    <li>If the program does not open, investigate the launch step.</li>
    <li>If the wrong result appears, inspect the input and processing steps.</li>
    <li>If the result disappears, inspect saving and file location.</li>
    <li>If the change looks correct but is not visible elsewhere, verify synchronization, refresh state or permissions as appropriate.</li>
  </ul>

  <h2>Practice task</h2>
  <p>Choose one task you perform regularly, such as creating a document, sending an email or organizing a folder. Write its workflow as six steps using <strong>goal → open → input → process → save → verify</strong>. Then identify one step where you usually make mistakes and describe how you could verify it earlier.</p>

  <h2>Common mistakes</h2>
  <ul>
    <li>Starting work without defining the desired result.</li>
    <li>Editing the wrong file or record.</li>
    <li>Skipping verification because the operation appeared to succeed.</li>
    <li>Assuming Undo is the same as a backup.</li>
    <li>Changing multiple settings simultaneously during troubleshooting.</li>
  </ul>

  <h2>Quick check</h2>
  <ol>
    <li>What six stages can describe many everyday computer tasks?</li>
    <li>Why is verification important after changing a file or setting?</li>
    <li>How is Undo different from a backup?</li>
  </ol>

  <h2>Key takeaway</h2>
  <p><strong>Learning computer skills becomes easier when you recognize repeatable patterns. Define the goal, work through the inputs and processing steps, save the result, and always verify the outcome.</strong></p>
</article>
HTML;
    }

    return null;
}

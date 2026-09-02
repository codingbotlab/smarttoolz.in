<?php
declare(strict_types=1);

/* Course 16 Lesson 12: Essential Keyboard Shortcuts — Computer Basics content. */
function lh_course16_lesson12_computer_basics_override(array $lesson, int $position): ?string
{
    $slug = (string)($lesson['slug'] ?? '');
    if ($slug !== 'course-16-lesson-12') {
        return null;
    }

    return <<<'HTML'
<article class="lh-prose">
  <div class="lh-lesson-intro">
    <p><strong>Keyboard shortcuts are combinations of keys that perform common actions faster.</strong> Learning a small set of reliable shortcuts can make everyday computer work quicker, reduce unnecessary mouse movement, and help you work confidently in Windows.</p>
  </div>

  <h2>1. The most useful shortcuts</h2>
  <div class="table-responsive"><table class="table table-bordered align-middle"><thead><tr><th>Shortcut</th><th>Action</th><th>Typical use</th></tr></thead><tbody>
    <tr><td><strong>Ctrl + C</strong></td><td>Copy</td><td>Copy selected text or files.</td></tr>
    <tr><td><strong>Ctrl + X</strong></td><td>Cut</td><td>Prepare selected content to be moved.</td></tr>
    <tr><td><strong>Ctrl + V</strong></td><td>Paste</td><td>Insert previously copied or cut content.</td></tr>
    <tr><td><strong>Ctrl + Z</strong></td><td>Undo</td><td>Reverse a recent action when supported.</td></tr>
    <tr><td><strong>Ctrl + Y</strong></td><td>Redo</td><td>Redo an action after undo when supported.</td></tr>
    <tr><td><strong>Ctrl + A</strong></td><td>Select all</td><td>Select all relevant content in the current area.</td></tr>
    <tr><td><strong>Ctrl + S</strong></td><td>Save</td><td>Save work in applications that support it.</td></tr>
    <tr><td><strong>Ctrl + F</strong></td><td>Find</td><td>Search within a document, page or application.</td></tr>
    <tr><td><strong>Alt + Tab</strong></td><td>Switch windows</td><td>Move between open applications.</td></tr>
    <tr><td><strong>Windows + E</strong></td><td>Open File Explorer</td><td>Quickly access files and folders.</td></tr>
    <tr><td><strong>Windows + D</strong></td><td>Show or hide desktop</td><td>Quickly reveal the desktop.</td></tr>
    <tr><td><strong>Windows + L</strong></td><td>Lock the computer</td><td>Secure the session when stepping away.</td></tr>
  </tbody></table></div>

  <h2>2. Modifier keys: Ctrl, Alt and Windows</h2>
  <p>Many shortcuts use a <strong>modifier key</strong> together with another key. The modifier changes what the other key does. <strong>Ctrl</strong> is commonly used for editing and document actions, <strong>Alt</strong> is commonly used for window and menu commands, and the <strong>Windows key</strong> provides quick access to Windows features.</p>
  <p>On some keyboards the Windows key has a Windows logo. The exact physical layout can differ between keyboards and laptops.</p>

  <h2>3. Copy, cut and paste</h2>
  <p>The classic sequence is <strong>Ctrl + C → move to the destination → Ctrl + V</strong>. This is useful for both text and, in File Explorer, files and folders.</p>
  <p>Cut uses <strong>Ctrl + X</strong>. In supported applications, the selected item is prepared to move rather than simply creating another copy. Remember the distinction from the previous lessons: copying normally leaves the original in place, while moving changes where the item is stored.</p>

  <h2>4. Undo is your safety net</h2>
  <p><strong>Ctrl + Z</strong> can reverse a recent action in many applications. It is especially useful when you accidentally type something, delete content, apply an unwanted change or move an item and the application supports undo for that action.</p>
  <div class="alert alert-primary"><strong>Smart tip:</strong> If something just went wrong, pause before clicking repeatedly. First consider whether <strong>Ctrl + Z</strong> can safely reverse the last action.</div>

  <h2>5. Selecting efficiently</h2>
  <p><strong>Ctrl + A</strong> selects all applicable content in the current context. For example, in a text editor it may select all text; in a file view it may select all items currently shown.</p>
  <p>Selection is the foundation for many other shortcuts. Select first, then copy, cut, delete or format only when you are sure the correct items are selected.</p>

  <h2>6. Save your work</h2>
  <p><strong>Ctrl + S</strong> is one of the most valuable habits to learn. In applications that support it, it saves the current document or project. Some modern applications save automatically, but knowing Ctrl + S is still useful because behaviour varies by application.</p>
  <p>Saving is not the same as backing up. A saved file can still be lost if the storage device fails or the file is accidentally overwritten, so important work should also have an appropriate backup strategy.</p>

  <h2>7. Find text quickly</h2>
  <p><strong>Ctrl + F</strong> opens a find/search function in many browsers, documents and applications. Instead of scanning a long page manually, type a distinctive word or phrase and move through the matching results.</p>

  <h2>8. Switch windows with Alt + Tab</h2>
  <p>When multiple applications are open, hold <strong>Alt</strong> and press <strong>Tab</strong> to move through available windows. Release the keys when the desired window is selected.</p>
  <p>This is particularly useful when you are reading instructions in one window while working in another.</p>

  <h2>9. Windows shortcuts worth memorising</h2>
  <div class="table-responsive"><table class="table table-bordered align-middle"><thead><tr><th>Shortcut</th><th>Why it helps</th></tr></thead><tbody>
    <tr><td>Windows + E</td><td>Opens File Explorer without searching for its icon.</td></tr>
    <tr><td>Windows + D</td><td>Quickly shows the desktop.</td></tr>
    <tr><td>Windows + L</td><td>Locks the computer when you leave it unattended.</td></tr>
    <tr><td>Windows + I</td><td>Opens Windows Settings on supported versions.</td></tr>
    <tr><td>Windows + Shift + S</td><td>Opens the Windows screen-snipping interface on supported versions.</td></tr>
  </tbody></table></div>

  <h2>10. Shortcuts are context-dependent</h2>
  <p>Not every shortcut behaves identically in every application. A shortcut may be unavailable, perform a different action, or depend on what is selected. Always look at the application's menus or help information if you are unsure.</p>
  <p>Keyboard shortcuts also vary across operating systems. This lesson focuses on common Windows usage.</p>

  <h2>Practical exercise: 10-minute shortcut workout</h2>
  <ol>
    <li>Open a text editor and type a short paragraph.</li>
    <li>Use <strong>Ctrl + A</strong> to select it.</li>
    <li>Use <strong>Ctrl + C</strong>, create another location or document, and use <strong>Ctrl + V</strong>.</li>
    <li>Use <strong>Ctrl + S</strong> to save the document where the application supports saving.</li>
    <li>Make a small change, then use <strong>Ctrl + Z</strong> to undo it.</li>
    <li>Use <strong>Ctrl + F</strong> to find a word in a longer piece of text.</li>
    <li>Open File Explorer with <strong>Windows + E</strong>.</li>
    <li>Open another application and practise switching with <strong>Alt + Tab</strong>.</li>
    <li>Use <strong>Windows + D</strong> to show the desktop, then return to your work.</li>
    <li>When finished, use <strong>Windows + L</strong> to lock the computer if you are on your own device and need to step away.</li>
  </ol>

  <h2>Shortcut learning strategy</h2>
  <p>Do not try to memorise fifty shortcuts at once. Start with a small core group: <strong>Ctrl + C, Ctrl + X, Ctrl + V, Ctrl + Z, Ctrl + A, Ctrl + S, Ctrl + F, Alt + Tab</strong>. Use them repeatedly until the key combinations become automatic. Then add Windows-specific shortcuts.</p>

  <h2>Common mistakes</h2>
  <ul>
    <li>Using a shortcut without checking what is selected first.</li>
    <li>Pressing a shortcut repeatedly when one action was enough.</li>
    <li>Assuming every application supports every shortcut in exactly the same way.</li>
    <li>Confusing Ctrl + C (copy) with Ctrl + X (cut).</li>
    <li>Forgetting that saving a file is not the same as making a backup.</li>
    <li>Trying to memorise too many shortcuts before mastering the essential ones.</li>
  </ul>

  <h2>Quick self-check</h2>
  <ol>
    <li>What is the difference between Ctrl + C and Ctrl + X?</li>
    <li>What does Ctrl + V do?</li>
    <li>Which shortcut usually undoes a recent action?</li>
    <li>How can Alt + Tab help when several apps are open?</li>
    <li>Which shortcut opens File Explorer quickly?</li>
    <li>Why should you check what is selected before using a shortcut?</li>
  </ol>

  <h2>Key takeaway</h2>
  <p><strong>You do not need to memorise every shortcut—master the few that you use every day.</strong> Copy, cut, paste, undo, select, save, find and switch-window shortcuts can dramatically improve everyday computer work.</p>
</article>
HTML;
}

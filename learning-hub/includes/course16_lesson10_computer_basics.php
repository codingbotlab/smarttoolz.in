<?php
declare(strict_types=1);

/* Course 16 Lesson 10: Windows Desktop — Computer Basics. */
function lh_course16_lesson10_computer_basics_override(array $lesson, int $position): ?string
{
    if ((string)($lesson['slug'] ?? '') !== 'course-16-lesson-10') {
        return null;
    }

    return <<<'HTML'
<article class="lh-prose">
  <div class="lh-lesson-intro">
    <p><strong>The Windows desktop is your main working area.</strong> It gives you quick access to apps, files, folders, shortcuts and system controls. Learning what each part does makes Windows much easier to navigate.</p>
  </div>

  <h2>1. What is the Windows desktop?</h2>
  <p>After Windows starts and you sign in, the desktop is the main screen you see. Think of it as your digital workspace: icons and shortcuts provide quick access, while the taskbar gives you a persistent way to launch and switch between apps.</p>
  <p>The exact appearance can vary between Windows versions and personal settings, but the basic ideas remain similar.</p>

  <h2>2. Main parts of the desktop</h2>
  <div class="table-responsive"><table class="table table-bordered align-middle"><thead><tr><th>Part</th><th>What it does</th></tr></thead><tbody>
    <tr><td>Desktop background</td><td>The visual background behind your icons and open windows.</td></tr>
    <tr><td>Desktop icons</td><td>Shortcuts or links to apps, files, folders and locations.</td></tr>
    <tr><td>Taskbar</td><td>Provides access to apps, open windows and system controls.</td></tr>
    <tr><td>Start menu</td><td>Provides access to apps, search and other Windows features.</td></tr>
    <tr><td>Notification/system area</td><td>Shows selected status indicators such as network, sound, battery and other background services.</td></tr>
    <tr><td>Recycle Bin</td><td>Temporarily holds many deleted local files so they can potentially be restored.</td></tr>
  </tbody></table></div>

  <h2>3. Icons and shortcuts are not the same as the original file</h2>
  <p>A desktop icon can be a shortcut to a program, file or folder. Deleting a shortcut does not normally delete the original item it points to. However, deleting the actual file or folder is a different action.</p>
  <div class="alert alert-primary"><strong>Remember:</strong> A shortcut is more like an address pointing to something than a second copy of that thing.</div>

  <h2>4. Opening and managing desktop items</h2>
  <ol>
    <li>Single-click an item to select it.</li>
    <li>Double-click an item to open it in the usual desktop configuration.</li>
    <li>Right-click an item to see actions available for that item.</li>
    <li>Use the keyboard to rename, copy, move or delete items when appropriate.</li>
    <li>Drag an item only when you understand whether Windows will copy or move it in that location.</li>
  </ol>

  <h2>5. Keep the desktop organized</h2>
  <p>The desktop is convenient, but it should not become your permanent storage area for everything. Use folders in Documents, Pictures, Videos or another organized location for files you need to keep.</p>
  <p>A useful rule is: <strong>desktop for quick access, folders for organized storage.</strong> Keep only frequently used shortcuts and temporary working items on the desktop.</p>

  <h2>6. Background, personalization and display</h2>
  <p>Windows lets you customize parts of the desktop, including the background and several appearance settings. Personalization can make the computer comfortable to use, but it does not change the underlying files or hardware.</p>
  <p>Display settings are separate from wallpaper. Screen resolution, scaling, orientation and multiple-display options affect how Windows presents content on the screen.</p>

  <h2>7. Working with windows</h2>
  <p>When you open an application, it normally appears in a window. Common window controls let you minimize, maximize/restore and close the window. You can also move and resize many windows by dragging their title bars or edges.</p>
  <div class="table-responsive"><table class="table table-bordered align-middle"><thead><tr><th>Action</th><th>Result</th></tr></thead><tbody>
    <tr><td>Minimize</td><td>Hides the window from the main view while keeping the app open.</td></tr>
    <tr><td>Maximize</td><td>Expands the window to use most or all of the available screen.</td></tr>
    <tr><td>Restore</td><td>Returns a maximized window to a resizable window.</td></tr>
    <tr><td>Close</td><td>Closes that application window; unsaved work may need confirmation.</td></tr>
  </tbody></table></div>

  <h2>8. Search instead of hunting through icons</h2>
  <p>If you cannot find an application or file, use Windows Search rather than creating dozens of desktop shortcuts. Search is often faster and keeps the desktop cleaner.</p>
  <p>For files, remember that good names and folder organization make search results much easier to understand.</p>

  <h2>9. Desktop safety</h2>
  <p>Do not assume a desktop icon is trustworthy simply because it is visible on your computer. Be careful with unfamiliar programs, downloaded installers and executable files. Check the source before opening software you did not intentionally obtain.</p>
  <p>Also avoid deleting unknown system shortcuts or folders just because they look unfamiliar. When in doubt, identify what an item is before changing it.</p>

  <h2>Practical exercise: make your desktop useful</h2>
  <ol>
    <li>Look at your desktop and identify five different items.</li>
    <li>For each item, decide whether it is a shortcut, file, folder or system item.</li>
    <li>Move files that belong in permanent folders out of the desktop.</li>
    <li>Keep only the shortcuts you use regularly.</li>
    <li>Open two applications and practice minimizing, restoring, resizing and closing their windows.</li>
    <li>Use Windows Search to find an application without clicking through the desktop.</li>
  </ol>

  <h2>Common beginner mistakes</h2>
  <ul>
    <li>Saving every file directly on the desktop.</li>
    <li>Confusing a shortcut with the original file or program.</li>
    <li>Creating dozens of shortcuts instead of using Search and organized folders.</li>
    <li>Deleting an item without checking what it actually is.</li>
    <li>Assuming changing the desktop appearance changes the computer's performance or hardware.</li>
    <li>Opening unknown downloaded programs just because they have a familiar-looking icon.</li>
  </ul>

  <h2>Quick self-check</h2>
  <ol>
    <li>What is the Windows desktop used for?</li>
    <li>What is the difference between a shortcut and the original file?</li>
    <li>Why should permanent files usually be organized in folders instead of left on the desktop?</li>
    <li>What is the difference between minimizing and closing a window?</li>
    <li>When should you use Search instead of creating another desktop shortcut?</li>
  </ol>

  <h2>Key takeaway</h2>
  <p><strong>The Windows desktop is a workspace, not a storage dump.</strong> Understand icons, shortcuts, windows, Search and basic personalization, then keep your files organized so the computer stays easy to use.</p>
</article>
HTML;
}

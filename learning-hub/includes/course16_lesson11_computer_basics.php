<?php
declare(strict_types=1);

/* Course 16 Lesson 11: Taskbar and Start Menu — Computer Basics content. */
function lh_course16_lesson11_computer_basics_override(array $lesson, int $position): ?string
{
    $slug = (string)($lesson['slug'] ?? '');
    if ($slug !== 'course-16-lesson-11') {
        return null;
    }

    return <<<'HTML'
<article class="lh-prose">
  <div class="lh-lesson-intro">
    <p><strong>The Taskbar and Start menu are two of the main control points in Windows.</strong> They help you launch apps, switch between open windows, search for files and settings, and quickly access system features.</p>
  </div>

  <h2>1. What is the Taskbar?</h2>
  <p>The <strong>Taskbar</strong> is the bar used to access and manage commonly used apps and Windows features. Its exact appearance can vary between Windows versions and personal settings, but its basic purpose stays the same: it provides a quick way to launch apps and see what is currently running.</p>
  <p>On a typical Windows desktop, you may find the Start button, search, pinned apps, open-app indicators and the notification area on or around the Taskbar.</p>

  <h2>2. Pinned apps vs. running apps</h2>
  <div class="table-responsive"><table class="table table-bordered align-middle"><thead><tr><th>Item</th><th>Meaning</th><th>Example</th></tr></thead><tbody>
    <tr><td>Pinned app</td><td>Saved on the Taskbar for quick access.</td><td>File Explorer or a browser you use often.</td></tr>
    <tr><td>Running app</td><td>An application currently open.</td><td>A document editor with a file open.</td></tr>
    <tr><td>Active window</td><td>The open window currently receiving your keyboard and mouse input.</td><td>The browser window you are typing into.</td></tr>
  </tbody></table></div>
  <p>A pinned icon does not necessarily mean the app is running. Windows uses visual indicators to help distinguish pinned shortcuts from currently open applications.</p>

  <h2>3. What is the Start menu?</h2>
  <p>The <strong>Start menu</strong> is a central place for finding applications, searching for content and accessing important Windows options. Select the Start button or use the <strong>Windows key</strong> on the keyboard to open it.</p>
  <p>Depending on the Windows version, the Start menu may show pinned apps, recommended or recent items, an app list, search and account or power controls.</p>

  <h2>4. Launching an application</h2>
  <ol>
    <li>Open the Start menu.</li>
    <li>Find the application in the pinned apps or app list, or use search.</li>
    <li>Select the application to launch it.</li>
    <li>Once open, use the Taskbar to return to it or switch to another application.</li>
  </ol>
  <div class="alert alert-primary"><strong>Smart tip:</strong> If you know an app's name, Start/search is often faster than browsing through folders manually.</div>

  <h2>5. Switching between applications</h2>
  <p>If several applications are open, you can select their Taskbar icons to switch between them. You can also use <strong>Alt + Tab</strong> to move between open windows quickly.</p>
  <p>Switching is different from closing. When you switch away from an app, it usually remains open. Closing the app ends that window or application session.</p>

  <h2>6. Right-click: the shortcut to more actions</h2>
  <p>Right-clicking a Taskbar or Start-menu item can reveal useful actions. Depending on the app and Windows version, these may include opening the app, unpinning it, accessing recent items or opening other related options.</p>
  <p>Right-click menus are context-sensitive, so the available commands can change depending on what you select.</p>

  <h2>7. Notification area and system controls</h2>
  <p>The notification area provides quick access to system information and controls. Depending on your configuration, you may see network, sound, battery and other status indicators. Selecting the appropriate area can open quick settings or related controls.</p>
  <p>Learn to recognise these indicators, but avoid changing settings randomly. If you do not know what a system option does, check its label before changing it.</p>

  <h2>8. Search: the fastest route to many things</h2>
  <p>Windows Search can help locate applications, files, settings and other indexed content. For example, instead of searching through several folders for a calculator or a Windows setting, type its name into Start/search.</p>
  <p>Search results can contain different categories, so read the result before opening it. This is especially useful when several files or apps have similar names.</p>

  <h2>9. Pinning and unpinning apps</h2>
  <p>If you use an application frequently, pinning it can make it easier to reach. To remove clutter, unpin apps you rarely use. Unpinning a shortcut does <strong>not</strong> normally uninstall the application.</p>
  <p>This distinction is important: <strong>unpinning removes a shortcut from quick access; uninstalling removes the application from Windows.</strong></p>

  <h2>10. Taskbar organization</h2>
  <p>Keep your most-used apps easy to reach, but do not pin everything. A crowded Taskbar reduces the benefit of quick access. Use Start/search for less frequent applications and keep your Taskbar focused on regular tasks.</p>

  <h2>Practical exercise: master Start and Taskbar</h2>
  <ol>
    <li>Open the Start menu using the mouse.</li>
    <li>Open it again using the Windows key.</li>
    <li>Use search to find File Explorer and open it.</li>
    <li>Open a second application.</li>
    <li>Switch between the two using the Taskbar.</li>
    <li>Switch between them again using <strong>Alt + Tab</strong>.</li>
    <li>Right-click an app icon and inspect the available actions without changing anything you do not understand.</li>
    <li>Pin one frequently used application if appropriate, then identify how to unpin it.</li>
  </ol>

  <h2>Common beginner mistakes</h2>
  <ul>
    <li>Assuming a pinned Taskbar icon means the application is currently running.</li>
    <li>Closing an app when the goal was only to switch to another window.</li>
    <li>Uninstalling an application when they only wanted to remove its shortcut.</li>
    <li>Pinning dozens of apps and making quick access harder.</li>
    <li>Changing system settings without checking what the option does.</li>
    <li>Ignoring Search and manually browsing through many menus and folders.</li>
  </ul>

  <h2>Quick self-check</h2>
  <ol>
    <li>What is the main purpose of the Taskbar?</li>
    <li>How is a pinned app different from a running app?</li>
    <li>How can you open the Start menu without clicking it?</li>
    <li>What is the difference between switching and closing an application?</li>
    <li>Does unpinning an app normally uninstall it?</li>
    <li>When can Windows Search be faster than manually browsing folders?</li>
  </ol>

  <h2>Key takeaway</h2>
  <p><strong>Use the Start menu to find and launch things, and use the Taskbar to manage and switch between the things you use.</strong> Once you understand pinned apps, running apps, Search, right-click actions and Alt + Tab, everyday Windows navigation becomes much faster.</p>
</article>
HTML;
}

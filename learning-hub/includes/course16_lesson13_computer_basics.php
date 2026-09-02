<?php
declare(strict_types=1);

/* Course 16 Lesson 13: Mouse Skills — Computer Basics content. */
function lh_course16_lesson13_computer_basics_override(array $lesson, int $position): ?string
{
    $slug = (string)($lesson['slug'] ?? '');
    if ($slug !== 'course-16-lesson-13') {
        return null;
    }

    return <<<'HTML'
<article class="lh-prose">
  <div class="lh-lesson-intro">
    <p><strong>Good mouse control makes everyday computer work faster and more precise.</strong> The important skills are not complicated: move the pointer accurately, click the correct button, double-click when needed, use right-click menus, scroll, drag and drop, and know when a keyboard shortcut is safer or faster.</p>
  </div>

  <h2>1. Know the basic mouse actions</h2>
  <div class="table-responsive"><table class="table table-bordered align-middle"><thead><tr><th>Action</th><th>How to do it</th><th>Common use</th></tr></thead><tbody>
    <tr><td>Move</td><td>Move the mouse to move the pointer.</td><td>Position the pointer over an item.</td></tr>
    <tr><td>Left-click</td><td>Press and release the primary button once.</td><td>Select a button, file or control.</td></tr>
    <tr><td>Double-click</td><td>Press the primary button twice quickly.</td><td>Open items when the interface supports double-click.</td></tr>
    <tr><td>Right-click</td><td>Press the secondary button once.</td><td>Open a context menu with additional actions.</td></tr>
    <tr><td>Scroll</td><td>Rotate the wheel or use the device's scroll surface.</td><td>Move through long pages and lists.</td></tr>
    <tr><td>Drag and drop</td><td>Press, hold, move, then release.</td><td>Move or arrange items where supported.</td></tr>
  </tbody></table></div>

  <h2>2. Pointing accurately</h2>
  <p>The pointer is the link between your physical movement and the item on screen. Move the mouse steadily instead of making many tiny corrections. When an item is small, slow down as you approach it and make sure the pointer is actually over the intended target before clicking.</p>
  <p>If the pointer feels too fast or too slow, Windows provides mouse settings that can adjust pointer behaviour. Make changes gradually and test them rather than changing many settings at once.</p>

  <h2>3. Single-click vs. double-click</h2>
  <p>A single click commonly selects or activates an interface control. A double-click is often used to open files, folders or applications in desktop environments. Some interfaces use single-click to open items instead, so follow the behaviour of the application you are using.</p>
  <div class="alert alert-primary"><strong>Smart tip:</strong> If double-clicking is difficult, do not click harder. Keep the first button press short and make the second click quickly after it.</div>

  <h2>4. Right-click and context menus</h2>
  <p>A right-click often reveals actions relevant to the selected item. In File Explorer, for example, the menu may contain commands such as rename, copy, delete or properties. The exact options depend on what you clicked.</p>
  <p>Read the menu before selecting an action. Right-click menus can contain destructive commands, so avoid clicking blindly.</p>

  <h2>5. Scrolling</h2>
  <p>Use the mouse wheel to move vertically through long pages, documents and lists. If the pointer is over a particular panel, scrolling may affect that panel instead of the whole window. When nothing appears to happen, check where the pointer is positioned.</p>
  <p>For very long pages, you can combine scrolling with keyboard shortcuts or search instead of continuously rotating the wheel.</p>

  <h2>6. Drag and drop</h2>
  <p>Drag and drop means pressing and holding an item, moving it to a destination, and releasing the button. It can be convenient for rearranging files, selecting text or moving objects in supported applications.</p>
  <p>Be careful with files: depending on the source and destination, dragging can copy or move an item. When the result matters, use an explicit copy or move command and verify the destination.</p>

  <h2>7. Selecting multiple items</h2>
  <p>In many Windows interfaces, you can select multiple items by holding <strong>Ctrl</strong> while clicking individual items. You can often select a continuous range by selecting one item and then using <strong>Shift</strong> with another item. Exact behaviour depends on the application.</p>
  <p>Multiple selection is powerful because one command can then affect every selected item. Always look at the selection before deleting, moving or changing anything.</p>

  <h2>8. Mouse + keyboard is faster than mouse alone</h2>
  <p>Efficient computer use does not mean avoiding the keyboard. Combine both devices. For example, select a file with the mouse and use <strong>Ctrl + C</strong>, or use <strong>Alt + Tab</strong> to switch windows instead of searching for the next window with the pointer.</p>
  <p>When an action is repeated frequently, check whether a keyboard shortcut can reduce the number of clicks.</p>

  <h2>9. Mouse settings and accessibility</h2>
  <p>Windows includes options that can change pointer speed and other mouse behaviour. Accessibility features can also help people who have difficulty with precise pointing or clicking. The best setting is the one that gives you accurate control without unnecessary effort.</p>
  <p>If you use a laptop touchpad instead of a physical mouse, many concepts remain the same, but gestures and button behaviour can differ.</p>

  <h2>Practical exercise: mouse control workout</h2>
  <ol>
    <li>Move the pointer slowly to five small targets on screen and click each one accurately.</li>
    <li>Single-click an item, then double-click an appropriate file or folder to open it.</li>
    <li>Right-click an item and identify the context menu.</li>
    <li>Scroll through a long page and stop at a specific heading.</li>
    <li>Drag a harmless desktop item or selected object in an application where drag-and-drop is safe, then return it to its original location.</li>
    <li>In a folder containing several files, practise selecting one item with a click and multiple separate items using Ctrl + click.</li>
    <li>Switch to another application with Alt + Tab instead of using the mouse.</li>
    <li>Repeat the same task while deliberately reducing unnecessary mouse movement.</li>
  </ol>

  <h2>Troubleshooting mouse problems</h2>
  <div class="table-responsive"><table class="table table-bordered align-middle"><thead><tr><th>Problem</th><th>Things to check</th></tr></thead><tbody>
    <tr><td>Pointer does not move</td><td>Check the connection, battery, surface or touchpad state.</td></tr>
    <tr><td>Pointer moves too fast</td><td>Lower pointer speed in mouse settings and test again.</td></tr>
    <tr><td>Double-click does not work reliably</td><td>Slow your hand movement slightly and check the double-click setting or mouse hardware.</td></tr>
    <tr><td>Scroll feels wrong</td><td>Check the scroll direction/settings and whether the correct panel has focus.</td></tr>
    <tr><td>Unexpected file movement</td><td>Check the destination and use Undo when supported; verify copy/move behaviour before dragging again.</td></tr>
  </tbody></table></div>

  <h2>Common mistakes</h2>
  <ul>
    <li>Clicking before the pointer is actually over the intended item.</li>
    <li>Double-clicking when a single click was required.</li>
    <li>Right-clicking and choosing an action without reading it.</li>
    <li>Dragging files without considering whether the result will be a copy or a move.</li>
    <li>Using the mouse for every task when a simple keyboard shortcut would be faster.</li>
    <li>Continuing to use an uncomfortable pointer speed instead of adjusting the setting.</li>
  </ul>

  <h2>Quick self-check</h2>
  <ol>
    <li>What is the difference between a single click and a double-click?</li>
    <li>What does right-click usually provide?</li>
    <li>How do you drag and drop an item?</li>
    <li>Why should you check what is selected before performing an action?</li>
    <li>Why is combining the mouse with keyboard shortcuts useful?</li>
    <li>What should you check if the pointer suddenly becomes difficult to control?</li>
  </ol>

  <h2>Key takeaway</h2>
  <p><strong>Accurate mouse control comes from deliberate pointing, clicking, scrolling and dragging—not from clicking quickly.</strong> Learn the basic actions, use context menus carefully, and combine mouse skills with keyboard shortcuts for efficient computer use.</p>
</article>
HTML;
}

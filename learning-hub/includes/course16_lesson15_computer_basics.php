<?php
declare(strict_types=1);

function lh_course16_lesson15_computer_basics_override(array $lesson, int $position): ?string
{
    if ((string)($lesson['slug'] ?? '') !== 'course-16-lesson-15') {
        return null;
    }

    return <<<'HTML'
<article class="lh-prose">
  <div class="lh-lesson-intro">
    <p><strong>Uninstalling software means removing an application from your computer properly.</strong> Deleting an app's shortcut is not the same as uninstalling it. A proper uninstall removes the program and its installed components while giving you a chance to keep or remove its user data.</p>
  </div>

  <h2>1. Uninstalling is different from deleting a shortcut</h2>
  <p>A desktop shortcut is only a link to an application. Deleting that shortcut does not normally remove the application itself. The program may still be installed, use storage space, run background services, or appear in the Start menu.</p>

  <h2>2. Before you uninstall</h2>
  <ul>
    <li>Save your work and close the application.</li>
    <li>Check whether you have files or projects that depend on the program.</li>
    <li>If the application stores important data locally, export or back it up first.</li>
    <li>If you are unsure what a program does, research its name before removing it.</li>
    <li>For work or school computers, confirm that removing the software is allowed.</li>
  </ul>

  <h2>3. Windows Settings method</h2>
  <p>On modern Windows versions, you can usually remove an application through <strong>Settings → Apps → Installed apps</strong>. Find the application, open its options, choose <strong>Uninstall</strong>, and follow the application's removal wizard if one appears.</p>
  <p>The exact wording can vary by Windows version, but the basic idea is the same: identify the installed application and use the operating system's uninstall control rather than simply deleting its folder.</p>

  <h2>4. Control Panel and older applications</h2>
  <p>Some traditional desktop applications can also be removed through <strong>Control Panel → Programs → Programs and Features</strong>. Select the application and choose <strong>Uninstall</strong> or <strong>Change</strong>, depending on what the software provides.</p>

  <h2>5. What happens to your personal files?</h2>
  <p>Uninstalling an application does not always mean that every document you created with it will be deleted. However, different programs handle settings, caches, saved projects and user data differently.</p>
  <div class="alert alert-primary"><strong>Smart tip:</strong> If the data matters, back it up before uninstalling. Never assume that an uninstall process will preserve everything automatically.</div>

  <h2>6. Why some software needs administrator permission</h2>
  <p>Installed programs can place files in protected system locations or change system settings. Windows may therefore ask for administrator approval during installation or removal. Only approve a prompt when you understand which program is requesting the permission and why.</p>

  <h2>7. When an app will not uninstall</h2>
  <p>Try these safe troubleshooting steps:</p>
  <ol>
    <li>Close the application completely and try again.</li>
    <li>Restart Windows and retry the normal uninstall process.</li>
    <li>Check whether the application has its own uninstall tool.</li>
    <li>Look up the official support instructions for that specific application.</li>
    <li>Avoid downloading random “cleaner” or “uninstaller” programs just because a pop-up recommends them.</li>
  </ol>

  <h2>8. Uninstalling is not the same as deleting program files</h2>
  <p>Manually deleting an application's installation folder can leave behind settings, services, registry entries or other components. It can also break the application's own uninstall process. Use the official uninstall method whenever possible.</p>

  <h2>9. What about updates?</h2>
  <p>Sometimes you do not need to uninstall an application just because it is old. Check whether the software offers an update first. Updates can fix bugs, improve compatibility and address security problems. Uninstall when you genuinely no longer need the software or when a supported troubleshooting process requires a clean removal.</p>

  <h2>10. Practical exercise</h2>
  <ol>
    <li>Choose a small application you are certain you no longer need, or use a test application in a safe practice environment.</li>
    <li>Before removing it, identify where any important files created by that application are stored.</li>
    <li>Back up anything important.</li>
    <li>Open Windows Settings → Apps → Installed apps.</li>
    <li>Find the application and use its Uninstall option.</li>
    <li>Follow the official uninstall wizard if it appears.</li>
    <li>After removal, restart the computer only if the software requests it.</li>
    <li>Check that the application is no longer listed as installed.</li>
  </ol>

  <h2>Common mistakes</h2>
  <ul>
    <li>Deleting only the desktop shortcut and assuming the program is gone.</li>
    <li>Deleting an application's folder manually instead of using its uninstaller.</li>
    <li>Removing software without checking for important local projects or data.</li>
    <li>Approving administrator prompts without checking which application requested them.</li>
    <li>Using unknown third-party “cleaners” downloaded from random websites.</li>
    <li>Removing system components simply because their names are unfamiliar.</li>
  </ul>

  <h2>Quick self-check</h2>
  <ol>
    <li>Why is deleting a shortcut not the same as uninstalling software?</li>
    <li>Where can you normally uninstall applications in Windows Settings?</li>
    <li>Why should important application data be backed up first?</li>
    <li>Why might Windows ask for administrator permission?</li>
    <li>What should you do if an application refuses to uninstall normally?</li>
  </ol>

  <h2>Key takeaway</h2>
  <p><strong>Install software from trusted sources, and remove it through the proper uninstall process.</strong> Before uninstalling, protect important data, understand what you are removing, and use official troubleshooting instructions when something goes wrong.</p>
</article>
HTML;
}

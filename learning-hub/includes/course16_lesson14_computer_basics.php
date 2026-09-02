<?php
declare(strict_types=1);

/**
 * Course 16 — Computer Basics Mastery
 * Lesson 14 — Installing Software
 */
function lh_course16_lesson14_computer_basics_override(array $lesson, int $position): ?string
{
    if ((string)($lesson['slug'] ?? '') !== 'course-16-lesson-14') {
        return null;
    }

    return <<<'HTML'
<section class="lh-lesson-content">
  <h2>Installing Software</h2>
  <p>Software is what tells your computer what to do. A browser, media player, image editor, office suite, or utility is software. Installing software means placing the required program files on your computer and configuring the app so it can run.</p>

  <div class="lh-callout">
    <strong>Golden rule:</strong> Download software from a trusted source, verify what you are installing, and never ignore a security warning just to make an installer work.
  </div>

  <h3>1. Downloading and Installing Are Different</h3>
  <p><strong>Download</strong> copies the installer or app package to your computer. <strong>Install</strong> runs that package and places/configures the program so the operating system can use it.</p>
  <ul>
    <li><strong>Download:</strong> You get a setup file such as <code>.exe</code>, <code>.msi</code>, or another package.</li>
    <li><strong>Install:</strong> You run the setup and choose how the program should be configured.</li>
    <li><strong>Launch:</strong> After installation, you open the installed application from Start, Search, a shortcut, or another supported location.</li>
  </ul>

  <h3>2. Choose a Trusted Source</h3>
  <p>Prefer the software publisher's official website or a trusted app store such as Microsoft Store. Avoid random download portals, cracked/pirated copies, suspicious pop-ups, and installers shared by unknown people.</p>
  <ul>
    <li>Check the publisher/developer name.</li>
    <li>Check that the website address is correct before downloading.</li>
    <li>Be cautious of fake “Download” buttons and bundled installers.</li>
    <li>Keep Windows security protections enabled.</li>
  </ul>

  <h3>3. Check Compatibility Before Installing</h3>
  <p>An app must support your operating system and hardware. Before installing, check its system requirements.</p>
  <ul>
    <li>Supported Windows version</li>
    <li>32-bit or 64-bit architecture when relevant</li>
    <li>Required RAM and free storage</li>
    <li>CPU/GPU requirements for demanding software</li>
    <li>Required frameworks, drivers, or other dependencies</li>
  </ul>
  <p><strong>Remember:</strong> A computer having enough storage does not automatically mean every application will run properly.</p>

  <h3>4. Read the Installer Screens</h3>
  <p>Do not blindly press <strong>Next → Next → Next</strong>. Installers can ask about licenses, shortcuts, installation folders, optional components, updates, and other software.</p>
  <ul>
    <li>Read the license or important terms when required.</li>
    <li>Choose the installation type that matches your needs.</li>
    <li>Use <strong>Custom/Advanced</strong> options when you need to inspect optional components.</li>
    <li>Decline unrelated bundled software or browser changes you do not want.</li>
    <li>Check the installation location before confirming.</li>
  </ul>

  <h3>5. Why Windows May Ask for Permission</h3>
  <p>Windows may show a User Account Control (UAC) prompt when an installer wants permission to make system-level changes.</p>
  <div class="lh-callout">
    <strong>Important:</strong> A UAC prompt does not automatically mean software is safe. Check the publisher and what you intentionally started before approving it. Never approve an unexpected installer simply because it asks for administrator permission.
  </div>

  <h3>6. Installation Locations</h3>
  <p>Many desktop applications install into standard Windows program locations. Some installers let you choose another drive or folder.</p>
  <ul>
    <li>Use the default location unless you have a good reason to change it.</li>
    <li>Keep your personal files separate from application installation folders.</li>
    <li>Do not randomly move installed program folders; the app may depend on registry entries, services, shortcuts, or configuration files.</li>
  </ul>

  <h3>7. After Installation</h3>
  <p>Once installation finishes, launch the application and check that it works normally.</p>
  <ol>
    <li>Open the app.</li>
    <li>Check its version/about screen if available.</li>
    <li>Complete only the setup steps you understand.</li>
    <li>Check for legitimate updates from the app or publisher.</li>
    <li>Keep your operating system and security software updated.</li>
  </ol>

  <h3>8. Installing Updates Safely</h3>
  <p>Updates can fix bugs, improve compatibility, and patch security problems. Use the application's built-in updater or the publisher's official update channel whenever possible.</p>
  <p>Be suspicious of unexpected messages claiming that every program on your PC is “infected” and demanding an urgent download.</p>

  <h3>9. Uninstalling Software Properly</h3>
  <p>To remove an application, use Windows' installed-apps/uninstall controls or the application's official uninstaller. Simply deleting a shortcut or the program folder may leave files, services, settings, or registry entries behind.</p>
  <ul>
    <li>Open Windows Settings and find the installed app.</li>
    <li>Choose <strong>Uninstall</strong> when appropriate.</li>
    <li>Follow the application's removal wizard.</li>
    <li>Restart if the installer/uninstaller requests it.</li>
  </ul>

  <h3>10. Common Mistakes</h3>
  <ul>
    <li>Downloading from an unknown website because it appears first in search results.</li>
    <li>Installing cracked or pirated software.</li>
    <li>Ignoring system requirements.</li>
    <li>Accepting optional bundled software without reading the installer.</li>
    <li>Approving an unexpected administrator/UAC prompt.</li>
    <li>Running multiple suspicious “PC cleaner” or “driver updater” installers.</li>
    <li>Deleting an application's folder instead of uninstalling it properly.</li>
    <li>Installing software without checking whether enough free storage is available.</li>
  </ul>

  <h3>Practical Activity: Install and Remove a Safe App</h3>
  <ol>
    <li>Choose a legitimate, free application that you actually need.</li>
    <li>Find its official publisher page or a trusted app store listing.</li>
    <li>Check the supported Windows version and system requirements.</li>
    <li>Download the installer.</li>
    <li>Before opening it, confirm that the source and file are what you expected.</li>
    <li>Run the installer and carefully review each screen.</li>
    <li>Open the application and confirm that it works.</li>
    <li>When finished practising, uninstall it through Windows' normal uninstall controls if you no longer need it.</li>
  </ol>

  <h3>Quick Troubleshooting</h3>
  <table class="table table-bordered align-middle">
    <thead><tr><th>Problem</th><th>What to check</th></tr></thead>
    <tbody>
      <tr><td>Installer will not run</td><td>Source, file integrity, Windows compatibility, security warnings, and whether the download completed correctly.</td></tr>
      <tr><td>“Not enough space”</td><td>Free storage on the target drive and whether the installer needs temporary extra space.</td></tr>
      <tr><td>App will not start</td><td>System requirements, required components, updates, permissions, and compatibility.</td></tr>
      <tr><td>Unexpected software appeared</td><td>Review the installer choices and uninstall unwanted software using trusted Windows controls.</td></tr>
      <tr><td>Windows shows a security warning</td><td>Do not bypass it blindly. Verify the publisher and source first.</td></tr>
    </tbody>
  </table>

  <h3>Self-Check</h3>
  <ul>
    <li>Can you explain the difference between downloading and installing?</li>
    <li>Can you name two trusted places to obtain software?</li>
    <li>Why should you check system requirements before installing?</li>
    <li>Why can a UAC prompt not be treated as proof that an installer is safe?</li>
    <li>Why is proper uninstalling better than simply deleting a program folder?</li>
  </ul>

  <div class="lh-callout">
    <strong>Key takeaway:</strong> Good software installation is not about clicking through the fastest. It is about choosing a trustworthy source, checking compatibility, reading the installer, protecting your computer, and knowing how to remove the software cleanly.
  </div>
</section>
HTML;
}

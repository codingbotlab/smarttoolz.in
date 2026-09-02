<?php
declare(strict_types=1);

function lh_course16_lesson16_computer_basics_override(array $lesson, int $position): ?string
{
    if ((string)($lesson['slug'] ?? '') !== 'course-16-lesson-16') {
        return null;
    }

    return <<<'HTML'
<article class="lh-prose">
  <div class="lh-lesson-intro">
    <p><strong>Keeping software updated is an important part of basic computer maintenance.</strong> Updates can add features, fix bugs, improve compatibility and—especially for security updates—help protect your computer from known problems.</p>
  </div>

  <h2>1. What is a software update?</h2>
  <p>An update is a newer version or set of fixes for software that is already installed. An update may be small, such as a bug fix, or much larger, such as a new version with redesigned features.</p>
  <p>Do not confuse an <strong>update</strong> with an <strong>upgrade</strong>. An update usually improves the existing software, while an upgrade can move you to a substantially newer release or edition.</p>

  <h2>2. Why updates matter</h2>
  <div class="table-responsive"><table class="table table-bordered align-middle"><thead><tr><th>Reason</th><th>What it can do</th></tr></thead><tbody>
    <tr><td>Security</td><td>Fix known vulnerabilities and reduce exposure to attacks.</td></tr>
    <tr><td>Bug fixes</td><td>Correct crashes, errors and unexpected behaviour.</td></tr>
    <tr><td>Compatibility</td><td>Help software work with newer operating systems, drivers or file formats.</td></tr>
    <tr><td>Features</td><td>Add new functions or improve existing ones.</td></tr>
    <tr><td>Performance</td><td>Sometimes improve speed, stability or resource usage.</td></tr>
  </tbody></table></div>

  <div class="alert alert-primary"><strong>Smart tip:</strong> A notification saying “Update available” does not automatically mean you should click any download link you see. Update through the app's built-in updater, the Microsoft Store, Windows Update, or the software publisher's official website.</div>

  <h2>3. Windows Update</h2>
  <p>Windows has a built-in update system for the operating system and related components. In Windows 11, you can open <strong>Settings → Windows Update</strong> to check the update status and available updates.</p>
  <p>Before a major update, save your work and keep the computer connected to reliable power. If Windows asks you to restart, finish important work first and then restart when appropriate.</p>

  <h2>4. Updating an installed app</h2>
  <p>Different apps use different update methods. Some check automatically when they open. Others have an <strong>Help</strong>, <strong>About</strong> or <strong>Settings</strong> menu with an update option. Apps installed through the Microsoft Store can use the Store's update mechanism.</p>
  <ol>
    <li>Open the application or its official update source.</li>
    <li>Look for an update or version-check option.</li>
    <li>Read the version information and release notes when available.</li>
    <li>Start the update from the trusted source.</li>
    <li>Close the app if requested and allow the installation to finish.</li>
    <li>Restart the app and confirm that it opens normally.</li>
  </ol>

  <h2>5. Automatic updates vs manual updates</h2>
  <p><strong>Automatic updates</strong> reduce the chance that you forget important fixes. <strong>Manual updates</strong> give you more control over when a change happens. For everyday users, leaving security-related updates enabled is generally preferable when the software supports it.</p>
  <p>For work-critical software, you may want to check compatibility and backups before installing a major version change.</p>

  <h2>6. Update safely</h2>
  <ul>
    <li>Use official websites, trusted app stores and built-in update tools.</li>
    <li>Be suspicious of pop-ups claiming your computer is infected and demanding an immediate download.</li>
    <li>Do not install “updates” from random file-sharing sites or unknown links.</li>
    <li>Check the publisher name before approving an installer.</li>
    <li>Keep enough free storage for the update to download and install.</li>
    <li>Back up important files before a major operating-system or application upgrade.</li>
  </ul>

  <h2>7. What if an update fails?</h2>
  <p>Do not repeatedly click random repair tools or download unofficial “fixers.” Start with simple steps: restart the computer, check your internet connection, verify available storage, try the official updater again, and read the exact error message.</p>
  <p>If an application stops working after an update, check the publisher's official support information for known compatibility problems. If the app provides a repair option, that may be safer than deleting program files manually.</p>

  <h2>8. Update vs uninstall</h2>
  <div class="table-responsive"><table class="table table-bordered align-middle"><thead><tr><th>Situation</th><th>Better first step</th></tr></thead><tbody>
    <tr><td>The app has a known bug fix available</td><td>Update it.</td></tr>
    <tr><td>The app is outdated but still needed</td><td>Check for a supported update.</td></tr>
    <tr><td>The app is no longer needed</td><td>Uninstall it properly.</td></tr>
    <tr><td>The app suddenly behaves strangely</td><td>Check for updates, repair options and official support before removing it.</td></tr>
  </tbody></table></div>

  <h2>Practical exercise</h2>
  <ol>
    <li>Open Windows Update on your PC and note whether updates are available.</li>
    <li>Choose one trusted application that you already use.</li>
    <li>Open its official update mechanism and check its current version.</li>
    <li>If an update is available, read the update information before installing it.</li>
    <li>After updating, reopen the application and check that your normal files still work.</li>
    <li>Write down the old and new version numbers if the application displays them.</li>
  </ol>

  <h2>Common mistakes</h2>
  <ul>
    <li>Ignoring security updates for long periods.</li>
    <li>Clicking fake browser pop-ups that pretend to be system warnings.</li>
    <li>Downloading an updater from an unofficial website.</li>
    <li>Turning off all automatic updates without understanding the consequences.</li>
    <li>Starting a major update immediately before an important deadline.</li>
    <li>Forgetting to save work or back up important data before a major upgrade.</li>
  </ul>

  <h2>Quick self-check</h2>
  <ol>
    <li>What is the difference between an update and an upgrade?</li>
    <li>Why are security updates important?</li>
    <li>Name two trusted ways to update software.</li>
    <li>What should you do before a major update if important files are involved?</li>
    <li>What are some safe first steps when an update fails?</li>
  </ol>

  <h2>Key takeaway</h2>
  <p><strong>Updating software is basic computer maintenance.</strong> Keep Windows and important applications reasonably current, use trusted update sources, save your work before major changes, and never trust a random pop-up just because it says your software needs an urgent update.</p>
</article>
HTML;
}

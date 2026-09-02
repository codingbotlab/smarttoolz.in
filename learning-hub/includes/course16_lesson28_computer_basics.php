<?php
declare(strict_types=1);

/**
 * Computer Basics — Lesson 28: Operating System Updates
 */
function lh_course16_lesson28_computer_basics_override(array $lesson, int $position): ?string
{
    if ((string)($lesson['slug'] ?? '') !== 'course-16-lesson-28') {
        return null;
    }

    return <<<'HTML'
<section class="lh-lesson-section">
    <h2>Operating System Updates</h2>
    <p>An operating system update changes or improves the software that controls your computer. Updates can fix security weaknesses, repair bugs, improve compatibility, and add or refine features.</p>

    <div class="lh-callout">
        <strong>Key idea:</strong> An update is not just about getting new features. Security updates can close weaknesses that attackers could otherwise exploit.
    </div>

    <h2>1. What Is an Operating System?</h2>
    <p>The operating system, or OS, is the main software that manages your computer's hardware and provides the environment in which apps run. Windows, macOS, Linux, Android, and iOS are examples of operating systems.</p>
    <p>Your OS manages things such as files, memory, devices, networking, user accounts, and applications.</p>

    <h2>2. Why Do Operating Systems Need Updates?</h2>
    <ul>
        <li><strong>Security fixes:</strong> Close known vulnerabilities and reduce security risks.</li>
        <li><strong>Bug fixes:</strong> Correct software problems that can cause crashes or unexpected behavior.</li>
        <li><strong>Compatibility:</strong> Help the OS work correctly with newer hardware, drivers, and applications.</li>
        <li><strong>Performance and reliability:</strong> Some updates improve stability or fix resource-management problems.</li>
        <li><strong>New or improved features:</strong> Major updates may change how parts of the system work.</li>
    </ul>

    <h2>3. Security Updates Matter</h2>
    <p>When a security weakness is discovered, software vendors may release a patch that changes the affected code. Delaying important updates can leave an unpatched computer exposed for longer.</p>
    <p>This is why keeping the operating system updated is an important part of basic computer security.</p>

    <h2>4. Windows 11: Check for Updates</h2>
    <ol>
        <li>Open <strong>Start</strong>.</li>
        <li>Select <strong>Settings</strong>.</li>
        <li>Open <strong>Windows Update</strong>.</li>
        <li>Select <strong>Check for updates</strong>.</li>
        <li>If updates are available, review them and select the available install option.</li>
        <li>If Windows asks you to restart, save your work and restart when appropriate.</li>
    </ol>
    <p>Windows can also download and install many updates automatically. You can use active-hours and restart options to reduce interruptions.</p>

    <h2>5. Update vs Upgrade</h2>
    <table class="table table-bordered align-middle">
        <thead><tr><th>Term</th><th>Meaning</th></tr></thead>
        <tbody>
            <tr><td><strong>Update</strong></td><td>A change to an existing version that may include security fixes, bug fixes, and smaller improvements.</td></tr>
            <tr><td><strong>Feature update</strong></td><td>A larger update that can introduce substantial changes or new features.</td></tr>
            <tr><td><strong>Upgrade</strong></td><td>Moving to a newer major operating-system version or edition, often with additional compatibility and requirements.</td></tr>
        </tbody>
    </table>

    <h2>6. Before Installing an Update</h2>
    <ul>
        <li>Save open documents and finish important work.</li>
        <li>Keep a backup of important files.</li>
        <li>Connect a laptop to power when possible.</li>
        <li>Make sure there is enough free storage space.</li>
        <li>Use a reliable internet connection for downloads.</li>
        <li>Do not force the computer to shut down while an update is installing unless recovery instructions specifically require it.</li>
    </ul>

    <h2>7. Where Should You Get Updates?</h2>
    <p>Use the operating system's built-in update system or the software manufacturer's official update mechanism. Be careful with random websites that claim your computer is infected and demand that you install an unknown "update" immediately.</p>
    <div class="lh-callout lh-callout-warning">
        <strong>Security warning:</strong> A fake update can be malware. A browser pop-up saying “Your Windows is out of date — click here” is not the same thing as Windows Update.
    </div>

    <h2>8. What If an Update Fails?</h2>
    <p>Do not immediately assume the computer is broken. Common causes include low disk space, interrupted internet access, insufficient battery power, conflicting software, or a temporary update-service problem.</p>
    <ol>
        <li>Restart the computer if Windows recommends it.</li>
        <li>Check your internet connection.</li>
        <li>Check available storage space.</li>
        <li>Return to <strong>Settings → Windows Update</strong> and try again.</li>
        <li>Use Windows' built-in troubleshooting tools if the problem continues.</li>
        <li>Record the exact error code before searching for a solution.</li>
    </ol>

    <h2>9. Update History</h2>
    <p>Windows provides an update history so you can see which updates were installed. This can be useful when troubleshooting a problem that started after a recent update.</p>
    <p>Do not remove an update casually. If an update causes a serious compatibility problem, use Microsoft's documented recovery or uninstall options and understand the security consequences.</p>

    <h2>10. Automatic Updates</h2>
    <p>Automatic updates reduce the chance that important fixes will be forgotten. They can also download or install updates at inconvenient times, so learn how to use active hours and restart scheduling instead of permanently disabling updates.</p>

    <h2>11. Practical Activity</h2>
    <div class="lh-practical">
        <h3>Check Your Update Status</h3>
        <ol>
            <li>Open <strong>Settings → Windows Update</strong> on a Windows 11 computer.</li>
            <li>Look at the current update status.</li>
            <li>Check whether updates are available.</li>
            <li>Open the update history and notice the types of updates listed.</li>
            <li>Check how much free space is available on the system drive.</li>
        </ol>
        <p><strong>Goal:</strong> Learn where to check updates and how to recognize whether the system is waiting for an update or restart.</p>
    </div>

    <h2>12. Common Mistakes</h2>
    <ul>
        <li>Ignoring security updates for long periods.</li>
        <li>Downloading “system updates” from unknown websites.</li>
        <li>Turning off automatic updates without a good reason.</li>
        <li>Shutting down a computer during an active installation without following proper instructions.</li>
        <li>Ignoring low-storage warnings.</li>
        <li>Searching for a solution without copying the exact update error code.</li>
        <li>Assuming every large update is a hardware upgrade.</li>
    </ul>

    <h2>13. Quick Self-Check</h2>
    <ol>
        <li>Why are security updates important?</li>
        <li>Where can you check for Windows updates in Windows 11?</li>
        <li>Why should you save your work before restarting for an update?</li>
        <li>Why is an unknown website a poor place to download a system update?</li>
        <li>Name two things you can check when an update fails.</li>
    </ol>

    <div class="lh-answer-box">
        <h3>Answers</h3>
        <ol>
            <li>They can fix security weaknesses and reduce exposure to known threats.</li>
            <li>Start → Settings → Windows Update → Check for updates.</li>
            <li>A restart can close applications and interrupt unsaved work.</li>
            <li>It may provide a fake update containing unwanted or malicious software.</li>
            <li>For example: internet connection, free disk space, restart status, or the exact error code.</li>
        </ol>
    </div>

    <div class="lh-callout">
        <strong>Key takeaway:</strong> Keep the operating system updated through trusted, built-in update tools. Security patches are an important layer of computer protection, and good update habits also improve reliability and compatibility.
    </div>
</section>
HTML;
}

<?php
declare(strict_types=1);

function lh_course16_lesson33_computer_basics_override(array $lesson, int $position): ?string
{
    if ((string)($lesson['slug'] ?? '') !== 'course-16-lesson-33') {
        return null;
    }

    return <<<'HTML'
<section class="lh-section">
  <h2>What Is Task Manager?</h2>
  <p><strong>Task Manager</strong> is a built-in Windows tool for viewing running applications and processes, checking resource usage, troubleshooting slow or unresponsive programs, and managing startup applications.</p>
  <div class="lh-callout"><strong>Simple idea:</strong> Task Manager is like a live dashboard for what your PC is doing right now.</div>
</section>

<section class="lh-section">
  <h2>How to Open Task Manager</h2>
  <ul>
    <li>Press <strong>Ctrl + Shift + Esc</strong>.</li>
    <li>Right-click the <strong>Start</strong> button and select <strong>Task Manager</strong>.</li>
    <li>Press <strong>Ctrl + Alt + Delete</strong>, then choose Task Manager.</li>
    <li>Search for <strong>Task Manager</strong> from the Start menu.</li>
  </ul>
  <p>The exact layout can vary between Windows versions, but the main concepts are similar.</p>
</section>

<section class="lh-section">
  <h2>Processes: See What Is Running</h2>
  <p>The <strong>Processes</strong> view lists apps and background processes and shows how much of the computer's resources they are using.</p>
  <p>Useful columns commonly include:</p>
  <ul>
    <li><strong>CPU:</strong> How much processor activity a process is using.</li>
    <li><strong>Memory:</strong> How much RAM a process is using.</li>
    <li><strong>Disk:</strong> How much disk activity is associated with the process.</li>
    <li><strong>Network:</strong> Network activity associated with the process.</li>
    <li><strong>GPU:</strong> Graphics processor usage when applicable.</li>
  </ul>
  <div class="lh-callout"><strong>Tip:</strong> Click a column heading to sort the list. For example, sorting by CPU can help you find a process currently using a lot of processor time.</div>
</section>

<section class="lh-section">
  <h2>Apps vs Background Processes</h2>
  <p>An <strong>app</strong> is usually a program you intentionally opened, while background processes can perform work without a normal application window.</p>
  <p>One application can also use multiple processes. Modern browsers, for example, may separate tabs, extensions, and other work into different processes.</p>
  <p>Seeing many processes is not automatically a problem. Focus on unusual resource usage, errors, or a process you recognize as causing a specific issue.</p>
</section>

<section class="lh-section">
  <h2>Finding a Slow or Frozen Program</h2>
  <ol>
    <li>Open Task Manager with <strong>Ctrl + Shift + Esc</strong>.</li>
    <li>Stay on <strong>Processes</strong>.</li>
    <li>Look for the application that is frozen or unusually busy.</li>
    <li>Check CPU, Memory, Disk, or GPU usage.</li>
    <li>Give a busy application a moment if it may simply be processing a large task.</li>
    <li>If the application is clearly unresponsive and normal closing does not work, consider <strong>End task</strong>.</li>
  </ol>
</section>

<section class="lh-section">
  <h2>What Does End Task Do?</h2>
  <p><strong>End task</strong> tells Windows to terminate the selected application or process. It can be useful when an application is frozen and will not close normally.</p>
  <div class="lh-callout"><strong>Warning:</strong> Ending a task can close the program without giving it the normal opportunity to save work. Unsaved changes may be lost.</div>
  <p>Do not repeatedly end processes just because their names look unfamiliar. If you are unsure what a process is, leave it alone and identify it first.</p>
</section>

<section class="lh-section">
  <h2>Performance: See the Big Picture</h2>
  <p>The <strong>Performance</strong> section provides live graphs and information for resources such as CPU, memory, disks, network adapters, and GPU hardware when available.</p>
  <table class="table table-bordered">
    <thead><tr><th>Resource</th><th>What a high value can suggest</th></tr></thead>
    <tbody>
      <tr><td>CPU</td><td>A program may be doing heavy computation or the system may be under processor load.</td></tr>
      <tr><td>Memory</td><td>Many programs or memory-heavy applications may be using most available RAM.</td></tr>
      <tr><td>Disk</td><td>Windows or an application may be reading/writing a large amount of data.</td></tr>
      <tr><td>Network</td><td>A download, upload, streaming task, or other network activity may be active.</td></tr>
      <tr><td>GPU</td><td>A game, video application, browser workload, or other graphics task may be active.</td></tr>
    </tbody>
  </table>
  <p>A high number is not automatically an error. The important question is whether the usage matches what you are doing and whether the computer is experiencing a problem.</p>
</section>

<section class="lh-section">
  <h2>Startup Apps</h2>
  <p>The <strong>Startup apps</strong> section shows applications configured to launch when you sign in to Windows. It can also show their startup impact.</p>
  <ol>
    <li>Open Task Manager.</li>
    <li>Select <strong>Startup apps</strong>.</li>
    <li>Review the applications and their status.</li>
    <li>Disable an unnecessary startup app when you understand what it does.</li>
  </ol>
  <div class="lh-callout"><strong>Important:</strong> Disabling a startup app normally prevents it from launching automatically; it does not uninstall the application.</div>
</section>

<section class="lh-section">
  <h2>App History</h2>
  <p>On Windows versions that provide it, <strong>App history</strong> can show resource usage information for supported applications over time. It can help you understand which applications have used CPU time or network resources.</p>
</section>

<section class="lh-section">
  <h2>Users</h2>
  <p>The <strong>Users</strong> section can show accounts currently signed in to the computer and the resources associated with their running processes.</p>
  <p>This is especially useful on shared computers where more than one user session may be active.</p>
</section>

<section class="lh-section">
  <h2>Details and Services</h2>
  <p><strong>Details</strong> provides a more technical view of running processes. <strong>Services</strong> lists Windows and application services and can provide controls for service management.</p>
  <div class="lh-callout"><strong>Beginner safety rule:</strong> Processes, Details, and Services contain components that Windows or applications may require. Do not stop or change an unfamiliar item simply to make the list shorter.</div>
</section>

<section class="lh-section">
  <h2>A Simple Troubleshooting Method</h2>
  <ol>
    <li><strong>Describe the problem:</strong> Is the PC slow, an app frozen, or the network busy?</li>
    <li><strong>Open Task Manager:</strong> Press Ctrl + Shift + Esc.</li>
    <li><strong>Check Processes:</strong> Look for unusual CPU, memory, disk, network, or GPU usage.</li>
    <li><strong>Check Performance:</strong> See whether the whole system is under pressure.</li>
    <li><strong>Identify the cause:</strong> Match the resource usage to an application or task you recognize.</li>
    <li><strong>Take the least risky action:</strong> Close the application normally first. Use End task only when appropriate.</li>
    <li><strong>Recheck:</strong> See whether the problem improves after the change.</li>
  </ol>
</section>

<section class="lh-section">
  <h2>Example: A Browser Is Frozen</h2>
  <ol>
    <li>Try closing the browser normally.</li>
    <li>If it is completely unresponsive, open Task Manager.</li>
    <li>Find the browser under Processes.</li>
    <li>Confirm that it is the application you intend to close.</li>
    <li>Use <strong>End task</strong> if necessary.</li>
    <li>Reopen the browser and check whether the problem remains.</li>
  </ol>
  <p>If you had unsaved work, it may not be recoverable. That is why End task should not be the first choice for a program that is still responding.</p>
</section>

<section class="lh-section">
  <h2>Common Mistakes</h2>
  <ul>
    <li>Ending every process that uses a lot of CPU without first understanding why.</li>
    <li>Ending Windows system processes because their names look unfamiliar.</li>
    <li>Assuming a high resource number is always a problem.</li>
    <li>Using End task before giving a busy application time to finish a legitimate operation.</li>
    <li>Disabling startup apps without checking what they do.</li>
    <li>Changing Services or advanced Details settings without understanding the consequences.</li>
  </ul>
</section>

<section class="lh-section">
  <h2>Practical Activity</h2>
  <ol>
    <li>Open Task Manager with <strong>Ctrl + Shift + Esc</strong>.</li>
    <li>On <strong>Processes</strong>, sort by CPU and note the top few items.</li>
    <li>Sort by Memory and observe whether the order changes.</li>
    <li>Open <strong>Performance</strong> and look at CPU and Memory graphs.</li>
    <li>Open <strong>Startup apps</strong> and identify one app you recognize that does not need to start automatically.</li>
    <li>Do not end or disable anything during this activity unless you are certain it is safe and unnecessary.</li>
  </ol>
  <p><strong>Goal:</strong> Learn to observe resource usage before taking action.</p>
</section>

<section class="lh-section">
  <h2>Quick Self-Check</h2>
  <ol>
    <li>What is Task Manager used for?</li>
    <li>What does the CPU column show?</li>
    <li>When might End task be useful?</li>
    <li>Why should you be careful with unknown processes and services?</li>
    <li>Where can you manage startup applications?</li>
  </ol>
  <details class="mt-3">
    <summary><strong>Show Answers</strong></summary>
    <ol class="mt-3">
      <li>It monitors running applications/processes, resource usage, startup apps, and other system information.</li>
      <li>It shows processor activity associated with a process.</li>
      <li>When a recognized application is unresponsive and cannot be closed normally.</li>
      <li>Some are required by Windows or applications, and stopping them can cause instability or data loss.</li>
      <li>In Task Manager → Startup apps.</li>
    </ol>
  </details>
</section>

<section class="lh-section">
  <h2>Key Takeaway</h2>
  <div class="lh-callout"><strong>Task Manager is a diagnostic dashboard, not a “kill everything” button. Observe CPU, memory, disk, network, and GPU usage, identify the real cause, and take the least risky action.</strong></div>
</section>
HTML;
}

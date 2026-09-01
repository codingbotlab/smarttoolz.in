<?php
declare(strict_types=1);

/* Curated, topic-specific lesson content. Each entry is applied once to the DB. */
function lh_apply_premium_lesson(PDO $db, array &$lesson): void
{
    $slug = (string)($lesson['slug'] ?? '');
    $content = null;

    if ($slug === 'hardware-and-software') {
        $content = <<<'HTML'
<article class="lh-prose">
  <div class="lh-lesson-intro">
    <p><strong>Hardware</strong> is the physical part of a computer that you can touch. <strong>Software</strong> is the set of programs and instructions that tell that hardware what to do. A useful computer depends on both: hardware provides the capabilities, while software tells those capabilities how to perform a task.</p>
  </div>

  <h2>1. What is hardware?</h2>
  <p>Hardware includes the physical components of a computer system. A desktop may contain a processor, memory, storage drive, motherboard, power supply, cooling system, display, keyboard and mouse. A laptop contains many of the same functions, but several components are built into one compact device.</p>
  <p>Hardware can be grouped by the job it performs:</p>
  <div class="table-responsive">
    <table class="table table-bordered align-middle">
      <thead><tr><th>Hardware</th><th>Main job</th><th>Example</th></tr></thead>
      <tbody>
        <tr><td>CPU</td><td>Processes instructions and calculations</td><td>Runs the steps needed by an application</td></tr>
        <tr><td>RAM</td><td>Holds information currently being used</td><td>Keeps active apps and data quickly accessible</td></tr>
        <tr><td>Storage</td><td>Keeps files and programs for long-term use</td><td>SSD or hard drive</td></tr>
        <tr><td>Input devices</td><td>Send information into the computer</td><td>Keyboard, mouse, microphone</td></tr>
        <tr><td>Output devices</td><td>Present results from the computer</td><td>Monitor, speakers, printer</td></tr>
      </tbody>
    </table>
  </div>

  <h2>2. What is software?</h2>
  <p>Software is the collection of instructions and data that make a computer useful. It is not a physical object you can pick up. When you open a browser, edit a document, play a video or change a setting, software coordinates the hardware needed for that job.</p>
  <p>Two broad categories are helpful for beginners:</p>
  <ul>
    <li><strong>System software:</strong> operating systems, drivers and other components that manage the computer itself.</li>
    <li><strong>Application software:</strong> programs used to perform tasks such as browsing, writing documents, editing images or communicating.</li>
  </ul>

  <h2>3. How hardware and software work together</h2>
  <p>Suppose you open a photo from your computer. The storage device provides the file, the operating system manages access to it, RAM keeps working data available, the CPU processes instructions, and the graphics system helps send the result to the display. None of those parts is doing the whole job alone.</p>
  <div class="alert alert-primary">
    <strong>Think of it like this:</strong> hardware is the equipment, while software is the instructions and workflow that use the equipment.
  </div>

  <h2>4. Hardware versus software</h2>
  <div class="table-responsive">
    <table class="table table-bordered align-middle">
      <thead><tr><th>Question</th><th>Hardware</th><th>Software</th></tr></thead>
      <tbody>
        <tr><td>Can you physically touch it?</td><td>Yes</td><td>No</td></tr>
        <tr><td>Examples</td><td>CPU, RAM, SSD, keyboard</td><td>Windows, browser, Word processor</td></tr>
        <tr><td>Main role</td><td>Provides physical computing resources</td><td>Provides instructions and user-facing functions</td></tr>
        <tr><td>Can it be upgraded?</td><td>Many components can be replaced or upgraded</td><td>Can be installed, updated or removed</td></tr>
      </tbody>
    </table>
  </div>

  <h2>5. Why the difference matters</h2>
  <p>Understanding the difference helps you troubleshoot. A problem with a screen cable, failing storage device or keyboard is different from a problem caused by an application, driver or operating-system setting. Knowing which side you are dealing with can save time.</p>
  <p>For example, if a USB keyboard works on another computer but not on yours, the keyboard itself may be fine. The issue could be the USB port, a driver, a setting or another part of the system.</p>

  <h2>6. A simple troubleshooting method</h2>
  <ol>
    <li>Describe exactly what is not working.</li>
    <li>Decide whether the symptom looks physical, software-related, or both.</li>
    <li>Check simple causes first: power, cables, connections, volume and settings.</li>
    <li>Test one change at a time.</li>
    <li>Restart the application or computer when appropriate.</li>
    <li>Check for useful warnings, driver issues or updates.</li>
    <li>Record what fixed the problem so it can be repeated later.</li>
  </ol>

  <h2>Practical example</h2>
  <p>Imagine the monitor shows “No Signal.” Do not immediately reinstall software. First check whether the monitor is powered on, whether the display cable is connected correctly, and whether the computer is actually running. Only after the physical checks should you move deeper into settings or drivers.</p>

  <h2>Practice task</h2>
  <p>Look at the computer or phone you use every day and make two lists: five pieces of hardware and five pieces of software. For each item, write one sentence explaining what it does and how it helps you complete a real task.</p>

  <h2>Common mistakes</h2>
  <ul>
    <li>Calling an app or operating system “hardware.”</li>
    <li>Assuming every computer problem is caused by a virus.</li>
    <li>Changing many settings at once, making the real cause harder to find.</li>
    <li>Replacing hardware before testing the simpler software or connection issues.</li>
  </ul>

  <h2>Quick check</h2>
  <ol>
    <li>What is the main difference between hardware and software?</li>
    <li>Why is RAM different from storage?</li>
    <li>Give one example of a problem that could be hardware-related and one that could be software-related.</li>
  </ol>

  <h2>Key takeaway</h2>
  <p>A computer is a system, not just a single component. Hardware provides physical resources, while software coordinates those resources to perform useful work. Understanding both gives you a much stronger foundation for troubleshooting and for the lessons that follow.</p>
</article>
HTML;
    }

    if ($content === null) return;

    $marker = '<!-- smarttoolz-premium-content:v1 -->';
    $stored = (string)($lesson['content'] ?? '');
    if (!str_contains($stored, $marker)) {
        $content = $marker . $content;
        $q = $db->prepare('UPDATE learning_lessons SET content=? WHERE id=? LIMIT 1');
        $q->execute([$content, (int)$lesson['id']]);
        $lesson['content'] = $content;
    }
}

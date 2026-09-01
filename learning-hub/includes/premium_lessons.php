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
  <div class="table-responsive"><table class="table table-bordered align-middle"><thead><tr><th>Hardware</th><th>Main job</th><th>Example</th></tr></thead><tbody><tr><td>CPU</td><td>Processes instructions and calculations</td><td>Runs the steps needed by an application</td></tr><tr><td>RAM</td><td>Holds information currently being used</td><td>Keeps active apps and data quickly accessible</td></tr><tr><td>Storage</td><td>Keeps files and programs for long-term use</td><td>SSD or hard drive</td></tr><tr><td>Input devices</td><td>Send information into the computer</td><td>Keyboard, mouse, microphone</td></tr><tr><td>Output devices</td><td>Present results from the computer</td><td>Monitor, speakers, printer</td></tr></tbody></table></div>

  <h2>2. What is software?</h2>
  <p>Software is the collection of instructions and data that make a computer useful. It is not a physical object you can pick up. When you open a browser, edit a document, play a video or change a setting, software coordinates the hardware needed for that job.</p>
  <p>Two broad categories are helpful for beginners:</p>
  <ul><li><strong>System software:</strong> operating systems, drivers and other components that manage the computer itself.</li><li><strong>Application software:</strong> programs used to perform tasks such as browsing, writing documents, editing images or communicating.</li></ul>

  <h2>3. How hardware and software work together</h2>
  <p>Suppose you open a photo from your computer. The storage device provides the file, the operating system manages access to it, RAM keeps working data available, the CPU processes instructions, and the graphics system helps send the result to the display. None of those parts is doing the whole job alone.</p>
  <div class="alert alert-primary"><strong>Think of it like this:</strong> hardware is the equipment, while software is the instructions and workflow that use the equipment.</div>

  <h2>4. Hardware versus software</h2>
  <div class="table-responsive"><table class="table table-bordered align-middle"><thead><tr><th>Question</th><th>Hardware</th><th>Software</th></tr></thead><tbody><tr><td>Can you physically touch it?</td><td>Yes</td><td>No</td></tr><tr><td>Examples</td><td>CPU, RAM, SSD, keyboard</td><td>Windows, browser, Word processor</td></tr><tr><td>Main role</td><td>Provides physical computing resources</td><td>Provides instructions and user-facing functions</td></tr><tr><td>Can it be upgraded?</td><td>Many components can be replaced or upgraded</td><td>Can be installed, updated or removed</td></tr></tbody></table></div>

  <h2>5. Why the difference matters</h2>
  <p>Understanding the difference helps you troubleshoot. A problem with a screen cable, failing storage device or keyboard is different from a problem caused by an application, driver or operating-system setting. Knowing which side you are dealing with can save time.</p>
  <p>For example, if a USB keyboard works on another computer but not on yours, the keyboard itself may be fine. The issue could be the USB port, a driver, a setting or another part of the system.</p>

  <h2>6. A simple troubleshooting method</h2>
  <ol><li>Describe exactly what is not working.</li><li>Decide whether the symptom looks physical, software-related, or both.</li><li>Check simple causes first: power, cables, connections, volume and settings.</li><li>Test one change at a time.</li><li>Restart the application or computer when appropriate.</li><li>Check for useful warnings, driver issues or updates.</li><li>Record what fixed the problem so it can be repeated later.</li></ol>

  <h2>Practical example</h2>
  <p>Imagine the monitor shows “No Signal.” Do not immediately reinstall software. First check whether the monitor is powered on, whether the display cable is connected correctly, and whether the computer is actually running. Only after the physical checks should you move deeper into settings or drivers.</p>

  <h2>Practice task</h2><p>Look at the computer or phone you use every day and make two lists: five pieces of hardware and five pieces of software. For each item, write one sentence explaining what it does and how it helps you complete a real task.</p>
  <h2>Common mistakes</h2><ul><li>Calling an app or operating system “hardware.”</li><li>Assuming every computer problem is caused by a virus.</li><li>Changing many settings at once, making the real cause harder to find.</li><li>Replacing hardware before testing the simpler software or connection issues.</li></ul>
  <h2>Quick check</h2><ol><li>What is the main difference between hardware and software?</li><li>Why is RAM different from storage?</li><li>Give one example of a problem that could be hardware-related and one that could be software-related.</li></ol>
  <h2>Key takeaway</h2><p>A computer is a system, not just a single component. Hardware provides physical resources, while software coordinates those resources to perform useful work. Understanding both gives you a much stronger foundation for troubleshooting and for the lessons that follow.</p>
</article>
HTML;
    }

    if ($slug === 'input-and-output-devices' || $slug === 'course-4-lesson-2') {
        $content = <<<'HTML'
<article class="lh-prose">
  <div class="lh-lesson-intro">
    <p><strong>Input devices</strong> let you send information or commands to a computer. <strong>Output devices</strong> let the computer present the results back to you. Understanding this simple flow makes it much easier to choose the right device, solve everyday problems, and understand how a computer interacts with people.</p>
  </div>

  <h2>1. The input → process → output idea</h2>
  <p>Most computer tasks can be understood as a simple cycle: you provide an input, the computer processes it, and the result appears as an output. For example, when you type a search query, the keyboard provides input, software and hardware process the request, and the monitor displays the results.</p>
  <div class="alert alert-primary"><strong>Remember:</strong> Input goes <em>into</em> the computer. Output comes <em>out</em> of the computer.</div>

  <h2>2. Common input devices</h2>
  <div class="table-responsive"><table class="table table-bordered align-middle"><thead><tr><th>Device</th><th>What it captures</th><th>Real-world use</th></tr></thead><tbody>
    <tr><td>Keyboard</td><td>Text, numbers and commands</td><td>Writing documents, entering passwords and shortcuts</td></tr>
    <tr><td>Mouse / touchpad</td><td>Pointer movement and clicks</td><td>Selecting files, buttons and objects</td></tr>
    <tr><td>Microphone</td><td>Sound</td><td>Calls, voice recording and speech input</td></tr>
    <tr><td>Webcam</td><td>Images and video</td><td>Video meetings and photos</td></tr>
    <tr><td>Scanner</td><td>Printed documents or images</td><td>Digitising paper records</td></tr>
    <tr><td>Touchscreen</td><td>Touch gestures and selections</td><td>Phones, tablets and interactive displays</td></tr>
  </tbody></table></div>

  <h2>3. Common output devices</h2>
  <div class="table-responsive"><table class="table table-bordered align-middle"><thead><tr><th>Device</th><th>What it produces</th><th>Real-world use</th></tr></thead><tbody>
    <tr><td>Monitor</td><td>Visual information</td><td>Apps, documents, photos and videos</td></tr>
    <tr><td>Speakers / headphones</td><td>Audio</td><td>Calls, music, alerts and videos</td></tr>
    <tr><td>Printer</td><td>Printed output</td><td>Documents, forms and reports</td></tr>
    <tr><td>Projector</td><td>Large visual display</td><td>Classrooms and presentations</td></tr>
    <tr><td>Haptic / vibration output</td><td>Physical feedback</td><td>Phone vibration and accessibility feedback</td></tr>
  </tbody></table></div>

  <h2>4. Some devices do both</h2>
  <p>A device does not always belong to only one category. A touchscreen is a good example: it <strong>outputs</strong> images and buttons to the user, while also <strong>inputs</strong> taps, swipes and gestures. A multifunction printer can scan a document as input and print a document as output.</p>

  <h2>5. Choosing the right device</h2>
  <p>The best device depends on the task. A person entering lots of text will usually work faster with a physical keyboard. Someone drawing on a tablet may prefer a stylus and touchscreen. A teacher presenting to a room may need a projector instead of a small monitor.</p>
  <ul><li>Choose for the task, not just the device name.</li><li>Check compatibility with your computer and operating system.</li><li>Consider connection type such as USB, Bluetooth, HDMI or Wi‑Fi.</li><li>Think about accessibility, comfort and the environment in which the device will be used.</li></ul>

  <h2>6. Practical troubleshooting</h2>
  <p>When an input or output device stops working, start with simple checks. Confirm power, cables and wireless connections first. Then check whether the correct device is selected in the operating system or application. Test the device with another application or another computer when possible.</p>
  <h3>Example: no sound</h3>
  <ol><li>Check that speakers or headphones are powered and connected.</li><li>Check the system volume and mute status.</li><li>Confirm the correct output device is selected.</li><li>Test audio in another application.</li><li>Reconnect the device or restart the application if needed.</li></ol>

  <h2>7. Accessibility matters</h2>
  <p>Input and output devices can make technology more accessible. Large keyboards, screen readers, magnification, alternative pointing devices, captions, high-contrast displays and audio feedback can help people interact with computers in different ways.</p>

  <h2>Practice task</h2>
  <p>Choose one task you perform every day, such as joining a video call or printing a document. Write down every input and output device involved. Then identify one point where the task could fail and describe the first troubleshooting check you would perform.</p>

  <h2>Common mistakes</h2>
  <ul><li>Assuming every USB device is automatically ready to use.</li><li>Forgetting to select the correct microphone, camera or audio output.</li><li>Changing advanced settings before checking simple connections.</li><li>Ignoring accessibility or comfort when choosing a device.</li></ul>

  <h2>Quick check</h2>
  <ol><li>What is the difference between an input device and an output device?</li><li>Why is a touchscreen both input and output?</li><li>Name two checks you would perform if a connected device stopped working.</li></ol>

  <h2>Key takeaway</h2>
  <p>Input devices collect information, the computer processes it, and output devices communicate the result. Once you understand that flow, everyday tasks and basic troubleshooting become much easier to reason about.</p>
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

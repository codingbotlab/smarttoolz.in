<?php
declare(strict_types=1);

/* Hand-written lesson content. Add one lesson at a time; never use as a generic fallback. */
function lh_lesson_override(array $course, array $lesson, int $position): ?string {
    $courseSlug = (string)($course['slug'] ?? '');
    if ($courseSlug === 'computer-basics-for-beginners' && $position === 1) {
        return <<<'HTML'
<article class="lh-prose">
  <div class="lh-lesson-intro">
    <p>A computer is an electronic machine that takes <strong>input</strong>, processes it according to instructions, stores information when needed, and produces <strong>output</strong>. Understanding this simple flow makes the rest of computer basics much easier to learn.</p>
  </div>

  <h2>What is a computer?</h2>
  <p>A computer works with data. You give it information through an input device or program, the processor and other components work on that information, and the result is shown or saved. For example, when you open a photo, your computer reads the photo file from storage, loads data into memory, and sends the image to the display.</p>

  <div class="table-responsive"><table class="table table-bordered align-middle">
    <thead><tr><th>Stage</th><th>What happens</th><th>Example</th></tr></thead>
    <tbody>
      <tr><td>Input</td><td>Information is provided to the computer.</td><td>Typing with a keyboard</td></tr>
      <tr><td>Processing</td><td>Instructions are executed and data is transformed.</td><td>Calculating a total</td></tr>
      <tr><td>Storage</td><td>Data is kept for later use.</td><td>Saving a document on an SSD</td></tr>
      <tr><td>Output</td><td>The result is presented to the user or another device.</td><td>Seeing a result on the monitor</td></tr>
    </tbody>
  </table></div>

  <h2>The main parts of a computer</h2>
  <p>Different components have different jobs. You do not need to memorize every specification yet; first understand the role each part plays.</p>
  <ul>
    <li><strong>CPU:</strong> executes instructions and performs calculations.</li>
    <li><strong>RAM:</strong> provides fast temporary working space for programs and data currently in use.</li>
    <li><strong>Storage:</strong> keeps files, applications and the operating system when the computer is turned off.</li>
    <li><strong>Motherboard:</strong> connects major components so they can communicate.</li>
    <li><strong>Power supply:</strong> provides electrical power to the system.</li>
    <li><strong>GPU:</strong> processes graphics and can accelerate supported workloads.</li>
    <li><strong>Input devices:</strong> keyboard, mouse, microphone, scanner and similar devices.</li>
    <li><strong>Output devices:</strong> monitor, speakers, printer and similar devices.</li>
  </ul>

  <h2>Hardware and software are different</h2>
  <p><strong>Hardware</strong> is the physical equipment you can touch. <strong>Software</strong> is the set of programs and instructions that tell the hardware what to do. A browser is software; the keyboard and SSD are hardware.</p>

  <div class="alert alert-primary">
    <strong>Remember:</strong> hardware provides the physical capability, while software provides the instructions and user-facing functionality.
  </div>

  <h2>Real-world example: opening a web browser</h2>
  <ol>
    <li>You click the browser icon with the mouse.</li>
    <li>The operating system starts the browser program from storage.</li>
    <li>The browser and its data are loaded into RAM so the CPU can work with them quickly.</li>
    <li>The CPU executes the program's instructions.</li>
    <li>The display shows the browser window as output.</li>
  </ol>
  <p>This is why a computer is best understood as a system of connected parts rather than one single component.</p>

  <h2>Why this matters when troubleshooting</h2>
  <p>Knowing the role of each part helps you ask better questions. If a computer cannot save a file, storage or permissions may be relevant. If many applications become slow when memory is heavily used, RAM pressure may be involved. If the display has a graphics problem, the display connection or graphics subsystem may need attention.</p>

  <h2>Practice task</h2>
  <p>Look at the computer or phone you use every day. Write down five physical components and describe the job of each in one sentence. Then name three software applications you use and explain what each application helps you do.</p>

  <h2>Common mistakes</h2>
  <ul>
    <li>Thinking RAM and storage are the same thing.</li>
    <li>Assuming the CPU is the only component responsible for performance.</li>
    <li>Confusing a program with the physical device it runs on.</li>
    <li>Trying to troubleshoot without first identifying what changed.</li>
  </ul>

  <h2>Quick check</h2>
  <p>Can you explain the difference between CPU, RAM and storage? Can you describe the input → processing → storage/output flow using one example from your own computer use?</p>

  <h2>Next lesson</h2>
  <p>Next, you will learn about <strong>input and output devices</strong> and how different devices help a person communicate with a computer.</p>
</article>
HTML;
    }
    return null;
}

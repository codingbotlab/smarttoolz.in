<?php
declare(strict_types=1);

/* Course 16 / Lesson 1: Computer Hardware Basics. */
function lh_course16_lesson1_override(array $lesson, int $position): ?string
{
    $slug = (string)($lesson['slug'] ?? '');
    if ($slug !== 'course-16-lesson-1' && $position !== 1) {
        return null;
    }

    return <<<'HTML'
<article class="lh-prose">
  <div class="lh-lesson-intro">
    <p><strong>Computer hardware is the physical equipment that makes a computer system work.</strong> When you understand what each major component does, it becomes much easier to choose a computer, diagnose a problem, plan an upgrade, and explain technical issues clearly.</p>
  </div>

  <h2>1. Hardware vs. software</h2>
  <p><strong>Hardware</strong> is something you can physically touch: the processor, memory modules, storage drive, motherboard, monitor, keyboard, mouse and so on. <strong>Software</strong> is the set of programs and instructions that tell the hardware what to do, such as Windows, a web browser or an image editor.</p>
  <div class="alert alert-primary"><strong>Easy way to remember:</strong> hardware is the machine; software is the instructions. A computer needs both.</div>

  <h2>2. The main computer components</h2>
  <div class="table-responsive"><table class="table table-bordered align-middle">
    <thead><tr><th>Component</th><th>What it does</th><th>Why it matters</th></tr></thead>
    <tbody>
      <tr><td><strong>CPU</strong></td><td>Executes instructions and performs calculations.</td><td>Affects general processing performance and responsiveness.</td></tr>
      <tr><td><strong>RAM</strong></td><td>Temporarily holds data and programs currently being used.</td><td>More usable RAM helps with multitasking and demanding applications.</td></tr>
      <tr><td><strong>Storage</strong></td><td>Keeps files, applications and the operating system when power is off.</td><td>Capacity and drive type affect space, load times and file access.</td></tr>
      <tr><td><strong>Motherboard</strong></td><td>Connects the CPU, RAM, storage, expansion cards and other devices.</td><td>Determines compatibility and provides communication pathways.</td></tr>
      <tr><td><strong>GPU</strong></td><td>Processes graphics and visual workloads.</td><td>Important for games, 3D work, video production and some compute tasks.</td></tr>
      <tr><td><strong>PSU</strong></td><td>Converts incoming electrical power into usable power for the PC.</td><td>Stable, adequate power is essential for reliable operation.</td></tr>
      <tr><td><strong>Cooling</strong></td><td>Moves heat away from components.</td><td>Helps hardware stay within safe operating temperatures.</td></tr>
    </tbody>
  </table></div>

  <h2>3. CPU: the processor</h2>
  <p>The <strong>CPU (Central Processing Unit)</strong> executes instructions from programs. It handles many general-purpose calculations and coordinates work across the system.</p>
  <p>When comparing CPUs, do not look only at the advertised clock speed. Architecture, number of cores, threads, cache, power limits and the actual workload all affect performance. A CPU that is excellent for office work is not automatically the best choice for every video-editing or 3D workload.</p>

  <h2>4. RAM: working memory</h2>
  <p><strong>RAM (Random Access Memory)</strong> is fast temporary working space. When you open a browser, document or application, the computer keeps active data in RAM so the CPU can access it quickly.</p>
  <p>RAM is not the same as storage. If a computer has plenty of storage but too little RAM for its workload, applications may still become slow when many programs are open.</p>
  <div class="alert alert-secondary"><strong>Example:</strong> Think of RAM as your desk and storage as a filing cabinet. A larger desk lets you work with more things at once; the filing cabinet keeps things for later.</div>

  <h2>5. Storage: HDD, SSD and NVMe</h2>
  <p>Storage keeps data even after the computer is turned off. Traditional <strong>HDDs</strong> use spinning magnetic platters and can provide large capacity at a relatively low cost. <strong>SSDs</strong> use flash memory and have no moving parts, usually giving much faster access and better responsiveness.</p>
  <p><strong>NVMe SSDs</strong> communicate over PCIe and can provide very high throughput and low latency. For everyday users, moving from an old HDD to a good SSD can make a computer feel dramatically faster even when the CPU remains unchanged.</p>

  <h2>6. Motherboard: the connection point</h2>
  <p>The motherboard provides sockets, slots and connectors that allow components to communicate. It may contain the CPU socket, RAM slots, storage connectors, expansion slots, USB headers, audio circuitry and networking features.</p>
  <p><strong>Compatibility matters.</strong> A motherboard must support the CPU socket and generation, the correct RAM type, the required storage interfaces and the physical form factor of the build. Never assume that any CPU, RAM module or expansion card will work with any motherboard.</p>

  <h2>7. GPU: graphics processing</h2>
  <p>A <strong>GPU (Graphics Processing Unit)</strong> is designed to process many graphics and parallel workloads efficiently. Some CPUs include integrated graphics, while a desktop may also use a separate dedicated graphics card.</p>
  <p>A dedicated GPU can be especially valuable for modern games, 3D modelling, GPU-accelerated creative applications and other workloads that can use parallel processing. The right choice depends on the software, resolution, quality settings and budget.</p>

  <h2>8. Power supply and cooling</h2>
  <p>The <strong>power supply unit (PSU)</strong> supplies the electrical power required by the components. A PSU should have enough capacity for the system and appropriate connectors, with quality and protection features also important.</p>
  <p>Every active component produces heat. Fans, heatsinks and other cooling systems move that heat away. Poor airflow can increase temperatures, reduce sustained performance and shorten component life. A clean case with sensible airflow is part of good computer maintenance.</p>

  <h2>9. Input, output and internal hardware</h2>
  <p><strong>Input devices</strong> send information to the computer, such as a keyboard, mouse, microphone or scanner. <strong>Output devices</strong> present information, such as a monitor, speakers or printer. Some devices perform both roles; a touchscreen, for example, displays information and accepts touch input.</p>
  <p>Internal hardware is the equipment inside the computer case, while external peripherals connect from outside. Understanding this distinction helps when troubleshooting a problem such as “the PC is running but the display is blank.”</p>

  <h2>10. How the parts work together</h2>
  <p>Imagine opening a large image in an editor. The storage drive provides the file. The operating system and application load data into RAM. The CPU processes instructions, while the GPU may accelerate visual operations. The motherboard provides the connections between these components, and the PSU supplies power to them all. Cooling removes the heat produced during operation.</p>
  <p>This is why a computer should be viewed as a <strong>system</strong>, not a collection of unrelated specifications. A faster component cannot always compensate for a limitation elsewhere.</p>

  <h2>11. A practical troubleshooting method</h2>
  <ol>
    <li><strong>Describe the symptom:</strong> Is the computer not powering on, running slowly, overheating, crashing, or showing no display?</li>
    <li><strong>Identify what changed:</strong> Did you install hardware, update software, move the PC, or connect a new peripheral?</li>
    <li><strong>Check simple causes first:</strong> Power cable, display cable, switch position, loose connection and external device.</li>
    <li><strong>Separate the problem:</strong> Decide whether the evidence points toward power, display, storage, memory, temperature or software.</li>
    <li><strong>Change one thing at a time:</strong> This makes it easier to identify which change affected the result.</li>
    <li><strong>Verify the result:</strong> Test the original symptom again after the change.</li>
  </ol>

  <h2>12. Choosing an upgrade: do not upgrade blindly</h2>
  <p>The best upgrade depends on the bottleneck. If the system constantly runs out of RAM while multitasking, additional compatible RAM may help more than replacing the CPU. If an old HDD makes the whole system feel sluggish, an SSD may provide a more noticeable improvement. If a game is limited by graphics performance, a GPU upgrade may be the sensible target.</p>
  <div class="table-responsive"><table class="table table-bordered align-middle">
    <thead><tr><th>Symptom</th><th>Possible area to investigate</th><th>First useful check</th></tr></thead>
    <tbody>
      <tr><td>Very slow startup and file loading</td><td>Storage</td><td>Check whether the system uses an old HDD and whether the drive is healthy.</td></tr>
      <tr><td>Slowdown with many applications open</td><td>RAM</td><td>Check memory usage while reproducing the slowdown.</td></tr>
      <tr><td>Games have low frame rates</td><td>GPU/CPU</td><td>Check which component is near full utilisation during gameplay.</td></tr>
      <tr><td>System shuts down under heavy load</td><td>Temperature/power</td><td>Check temperatures, airflow and PSU suitability.</td></tr>
      <tr><td>No display</td><td>Display path/GPU/RAM/power</td><td>Check monitor input, cable, power and component seating before replacing parts.</td></tr>
    </tbody>
  </table></div>

  <h2>13. Practical activity: identify your own computer</h2>
  <ol>
    <li>Find the computer's CPU model in the operating system's system information or task manager.</li>
    <li>Record the installed RAM capacity and, if available, its speed and number of modules.</li>
    <li>Identify the storage type and approximate capacity.</li>
    <li>Check whether the system uses integrated graphics or a dedicated GPU.</li>
    <li>Write down three ports you can identify on the computer and what each one is used for.</li>
    <li>Create a small table with <strong>component → job → evidence</strong>. The evidence should be the model/specification you actually found rather than a guess.</li>
  </ol>

  <h2>Common mistakes beginners make</h2>
  <ul>
    <li>Confusing RAM with permanent storage.</li>
    <li>Assuming a higher GHz number automatically means a faster CPU for every workload.</li>
    <li>Buying a component without checking motherboard compatibility.</li>
    <li>Ignoring PSU quality and connector requirements.</li>
    <li>Replacing expensive parts before checking cables, settings and temperatures.</li>
    <li>Judging a computer only by one specification instead of the complete workload.</li>
  </ul>

  <h2>Quick self-check</h2>
  <ol>
    <li>What is the difference between hardware and software?</li>
    <li>Why is RAM different from storage?</li>
    <li>What job does the CPU perform?</li>
    <li>Why can an SSD make an older computer feel much faster?</li>
    <li>What does the motherboard contribute to a computer system?</li>
    <li>When would a dedicated GPU be useful?</li>
    <li>Why should you identify the bottleneck before buying an upgrade?</li>
  </ol>

  <h2>Key takeaway</h2>
  <p><strong>Computer hardware works as a connected system.</strong> Learn the job of each component, understand how the parts depend on one another, and use evidence from the actual machine when troubleshooting or planning an upgrade. That approach is more useful than memorising specifications without understanding what they mean.</p>
</article>
HTML;
}

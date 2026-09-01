<?php
declare(strict_types=1);

/* Curated, topic-specific lesson content. Each entry is applied once to the DB. */
function lh_apply_premium_lesson(PDO $db, array &$lesson): void
{
    $slug = (string)($lesson['slug'] ?? '');
    $content = null;

    if ($slug === 'hardware-and-software') {
        $content = <<<'HTML'
<article class="lh-prose"><div class="lh-lesson-intro"><p><strong>Hardware</strong> is the physical part of a computer that you can touch. <strong>Software</strong> is the set of programs and instructions that tell that hardware what to do.</p></div><h2>1. What is hardware?</h2><p>Hardware includes the CPU, RAM, storage, motherboard, display, keyboard, mouse and other physical components.</p><h2>2. What is software?</h2><p>Software is the collection of programs and instructions that make the hardware useful. Operating systems, drivers and applications are common examples.</p><h2>3. How they work together</h2><p>When you open a photo, storage provides the file, RAM holds working data, the CPU processes instructions, and the graphics system helps display the result.</p><h2>Practice task</h2><p>List five hardware components and five software programs you use every day and write one sentence about each.</p><h2>Key takeaway</h2><p>Hardware provides physical resources; software coordinates them to perform useful work.</p></article>
HTML;
    }

    if ($slug === 'input-and-output-devices' || $slug === 'course-4-lesson-2') {
        $content = <<<'HTML'
<article class="lh-prose"><div class="lh-lesson-intro"><p><strong>Input devices</strong> send information into a computer, while <strong>output devices</strong> communicate results back to the user.</p></div><h2>1. Input → process → output</h2><p>A keyboard can provide input, the computer processes it, and the monitor can show the result.</p><h2>2. Common input devices</h2><table class="table table-bordered align-middle"><thead><tr><th>Device</th><th>Use</th></tr></thead><tbody><tr><td>Keyboard</td><td>Text, numbers and commands</td></tr><tr><td>Mouse / touchpad</td><td>Pointer movement and selection</td></tr><tr><td>Microphone</td><td>Sound and voice input</td></tr><tr><td>Webcam</td><td>Images and video</td></tr><tr><td>Scanner</td><td>Digitising paper documents</td></tr></tbody></table><h2>3. Common output devices</h2><table class="table table-bordered align-middle"><thead><tr><th>Device</th><th>Use</th></tr></thead><tbody><tr><td>Monitor</td><td>Visual information</td></tr><tr><td>Speakers / headphones</td><td>Audio</td></tr><tr><td>Printer</td><td>Printed documents</td></tr><tr><td>Projector</td><td>Large visual presentations</td></tr></tbody></table><h2>4. Devices that do both</h2><p>A touchscreen outputs images and also accepts taps and gestures. A multifunction printer can scan as input and print as output.</p><h2>5. Troubleshooting</h2><ol><li>Check power and connections.</li><li>Confirm the correct device is selected.</li><li>Test another application.</li><li>Reconnect or restart when appropriate.</li></ol><h2>Practice task</h2><p>Pick one daily task such as a video call and list all input and output devices involved.</p><h2>Key takeaway</h2><p>Input collects information, processing works on it, and output communicates the result.</p></article>
HTML;
    }

    if (
        $slug === 'cpu-what-it-does' ||
        $slug === 'course-4-lesson-3' ||
        trim((string)($lesson['title'] ?? '')) === 'CPU: What It Does'
    ) {
        $content = <<<'HTML'
<article class="lh-prose"><div class="lh-lesson-intro"><p>The <strong>CPU (Central Processing Unit)</strong> is the processor that executes instructions from software. It performs calculations, makes logical decisions, and coordinates the active work of a computer. Understanding the CPU helps you understand both everyday performance and why a computer can become slow.</p></div>
<h2>1. What does a CPU do?</h2><p>Software is made from instructions. When you open a browser, calculate a spreadsheet formula, edit a photo, or run a program, the CPU executes the instructions required for that task.</p><ol><li><strong>Fetch:</strong> get an instruction and the data it needs.</li><li><strong>Decode:</strong> determine what the instruction means.</li><li><strong>Execute:</strong> perform the operation.</li><li><strong>Store or pass on the result:</strong> make the result available to the system or application.</li></ol><p>Real processors are much more sophisticated than this simplified model, using caches, pipelines and multiple cores to improve performance.</p>
<h2>2. CPU vs RAM vs storage</h2><div class="table-responsive"><table class="table table-bordered align-middle"><thead><tr><th>Component</th><th>Main role</th><th>Simple analogy</th></tr></thead><tbody><tr><td>CPU</td><td>Executes instructions and calculations</td><td>Worker doing the active processing</td></tr><tr><td>RAM</td><td>Holds data and programs currently in use</td><td>Fast workspace</td></tr><tr><td>Storage</td><td>Keeps files and programs long term</td><td>Filing cabinet</td></tr></tbody></table></div><p>More storage does not automatically make a CPU faster, and more RAM does not replace processor performance. Each component addresses a different part of the system.</p>
<h2>3. CPU cores</h2><p>A <strong>core</strong> is a processing unit inside a CPU. Multi-core processors can handle multiple instruction streams in parallel when the operating system and applications can use that parallelism.</p><p>More cores are especially useful for multitasking and workloads that can be split into parallel work. A higher core count does not guarantee every task will be proportionally faster.</p>
<h2>4. What does GHz mean?</h2><p>CPU clock speed is commonly measured in <strong>GHz</strong>. It describes the frequency of the processor's clock. But clock speed alone is not a complete performance measure because different processor designs can accomplish different amounts of work per cycle.</p><div class="alert alert-primary"><strong>Smart tip:</strong> Compare processor generation, architecture, cores, workload, power limits and cooling instead of looking only at GHz.</div>
<h2>5. CPU cache</h2><p>CPU cache is a small amount of very fast memory located close to the processor. It stores frequently needed instructions and data so the CPU spends less time waiting for information from slower memory.</p>
<h2>6. Real-world example</h2><p>Imagine opening a spreadsheet and calculating a total. The spreadsheet is stored on the SSD. The operating system loads the working data into RAM. The CPU executes the spreadsheet instructions and performs the calculation, while the graphics system and display present the result.</p><h2>7. Why can a computer still feel slow?</h2><p>A computer can feel slow even with a capable CPU. RAM may be full, storage may be busy, an application may be inefficient, many background processes may be running, or the system may be overheating.</p><p>Before upgrading hardware, identify the bottleneck. On Windows, Task Manager can show CPU, memory, disk and network activity.</p>
<h2>8. Practical troubleshooting</h2><ol><li>Observe when the slowdown happens.</li><li>Open Task Manager or another system monitor.</li><li>Check CPU, memory and disk usage.</li><li>Identify the process using an unusual amount of CPU.</li><li>Check updates, background tasks and overheating symptoms.</li></ol>
<h2>Practice task</h2><p>Open your system monitor while doing a normal task. Record CPU and memory usage, then open another application and observe how the readings change. Write down what you think caused the change.</p>
<h2>Common mistakes</h2><ul><li>Assuming higher GHz always means a faster CPU.</li><li>Confusing CPU cores with RAM capacity.</li><li>Replacing the CPU before identifying the actual bottleneck.</li><li>Ignoring cooling and sustained performance.</li></ul>
<h2>Quick check</h2><ol><li>What is the CPU's main job?</li><li>How is RAM different from the CPU?</li><li>Why is GHz alone not enough to compare processors?</li></ol>
<h2>Key takeaway</h2><p>The CPU is the computer's main instruction-processing engine. Understanding cores, clock speed, cache and the relationship between CPU, RAM and storage gives you a practical foundation for understanding system performance.</p></article>
HTML;
    }

    if ($content === null) return;
    $stored = (string)($lesson['content'] ?? '');
    $marker = $slug === 'cpu-what-it-does' || $slug === 'course-4-lesson-3' || trim((string)($lesson['title'] ?? '')) === 'CPU: What It Does'
        ? '<!-- smarttoolz-premium-content:cpu-v2 -->'
        : '<!-- smarttoolz-premium-content:v1 -->';
    $needsWrite = !str_contains($stored, $marker);
    if ($needsWrite) {
        $tagged = $marker.$content;
        $q = $db->prepare('UPDATE learning_lessons SET content=? WHERE id=? LIMIT 1');
        $q->execute([$tagged, (int)$lesson['id']]);
        $lesson['content'] = $tagged;
    }
}

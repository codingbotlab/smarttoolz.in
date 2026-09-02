<?php
declare(strict_types=1);

/* Course 16 - Lesson 3: CPU: What It Does */
function lh_course16_lesson3_override(array $lesson, int $position): ?string
{
    $slug = (string)($lesson['slug'] ?? '');
    if ($slug !== 'course-16-lesson-3') {
        return null;
    }

    return <<<'HTML'
<article class="lh-prose">
  <div class="lh-lesson-intro">
    <p><strong>The CPU is the general-purpose processor at the centre of a computer's work.</strong> It reads and executes instructions, performs calculations, moves data through the system and coordinates many of the tasks that make applications run. Understanding the CPU helps you make sense of performance instead of judging a computer by a single number such as GHz.</p>
  </div>

  <h2>1. What does CPU mean?</h2>
  <p><strong>CPU</strong> stands for <strong>Central Processing Unit</strong>. It is a processor designed to execute instructions from the operating system and applications. When software asks the computer to calculate, compare, organise or transform data, the CPU performs the instructions required to make that work happen.</p>
  <div class="alert alert-primary"><strong>Simple analogy:</strong> Think of the CPU as the worker who reads the instructions, performs the required operations and coordinates the next steps. RAM is the nearby workspace, while storage is the place where information is kept for longer.</div>

  <h2>2. The basic CPU work cycle</h2>
  <p>At a simplified level, a CPU repeatedly performs a cycle of <strong>fetch, decode and execute</strong>.</p>
  <ol>
    <li><strong>Fetch:</strong> retrieve the next instruction and required information from memory.</li>
    <li><strong>Decode:</strong> determine what the instruction means and what operation is required.</li>
    <li><strong>Execute:</strong> perform the operation using the appropriate CPU resources.</li>
    <li><strong>Store/commit the result:</strong> make the result available for the next part of the program.</li>
  </ol>
  <p>Modern processors use sophisticated techniques such as caching, pipelining, prediction and out-of-order execution to keep this process efficient. The basic idea remains the same: instructions are continuously processed so software can do useful work.</p>

  <h2>3. CPU vs RAM vs storage</h2>
  <div class="table-responsive"><table class="table table-bordered align-middle"><thead><tr><th>Component</th><th>Main job</th><th>Think of it as</th></tr></thead><tbody>
    <tr><td><strong>CPU</strong></td><td>Executes instructions and performs calculations.</td><td>The worker/processor.</td></tr>
    <tr><td><strong>RAM</strong></td><td>Holds active data and instructions for quick access.</td><td>The working desk.</td></tr>
    <tr><td><strong>Storage</strong></td><td>Keeps the operating system, applications and files persistently.</td><td>The filing cabinet.</td></tr>
  </tbody></table></div>
  <p>A fast CPU cannot make unlimited RAM or slow storage disappear as a bottleneck. Computer performance depends on how these parts work together.</p>

  <h2>4. Cores: multiple workers inside one CPU</h2>
  <p>A modern CPU usually contains multiple <strong>cores</strong>. Each core can execute its own stream of instructions, allowing the processor to work on multiple tasks at the same time.</p>
  <p>More cores can be valuable for workloads that are designed to use them, such as rendering, compiling, compression and some content-creation tasks. However, more cores do not automatically make every application faster. A program may depend heavily on one main thread or have other limitations.</p>

  <h2>5. Threads and logical processors</h2>
  <p>A <strong>thread</strong> is a sequence of instructions that can be scheduled for execution. Modern CPUs may support technologies that allow a physical core to maintain multiple hardware execution contexts, which an operating system may display as logical processors.</p>
  <p>Do not confuse a CPU core with a software thread. A core is physical processor hardware; a thread is a unit of work that software and the operating system schedule.</p>

  <h2>6. Clock speed: what GHz actually means</h2>
  <p><strong>Clock speed</strong> describes how many clock cycles a processor can operate through per second. It is commonly expressed in gigahertz (GHz), where one GHz represents one billion cycles per second.</p>
  <p>Clock speed is useful information, but it is <strong>not a complete performance score</strong>. Two CPUs running at the same frequency can deliver different performance because their architectures, instructions-per-cycle, cache systems, core counts, power limits and workloads can differ.</p>
  <div class="alert alert-secondary"><strong>Beginner rule:</strong> Never choose a CPU from GHz alone. Compare the actual processor model and the workload you care about.</div>

  <h2>7. Cache: keeping frequently needed data close</h2>
  <p>CPUs contain very fast memory called <strong>cache</strong>. Cache stores frequently needed instructions and data closer to the processing units than main system RAM.</p>
  <p>Processors commonly have multiple cache levels, such as L1, L2 and L3. The exact design differs between CPU families. In general, smaller caches are extremely fast while larger caches provide more capacity but may have different latency characteristics.</p>

  <h2>8. Integrated graphics vs dedicated GPU</h2>
  <p>Some CPUs include <strong>integrated graphics</strong>, allowing a computer to produce display output without a separate graphics card. Other systems use a dedicated GPU with its own processor resources and usually its own graphics memory.</p>
  <p>Integrated graphics can be perfectly suitable for office work, web browsing, media playback and many everyday tasks. A dedicated GPU becomes more important for demanding games, 3D workloads, GPU-accelerated creative software and other applications that can use substantial graphics processing.</p>

  <h2>9. CPU temperature and power</h2>
  <p>A CPU consumes electrical power and produces heat while operating. Modern processors can dynamically adjust frequency and power depending on workload, temperature and system limits.</p>
  <p>If temperatures become too high, a processor can reduce its operating performance to protect itself. This behaviour is often called <strong>thermal throttling</strong>. Good cooling, clean airflow and an appropriate cooler help the CPU sustain performance.</p>

  <h2>10. What happens when you open an application?</h2>
  <p>Suppose you open a photo editor:</p>
  <ol>
    <li>The application files are read from storage.</li>
    <li>The operating system loads the required code and data into RAM.</li>
    <li>The CPU executes instructions that initialise the application.</li>
    <li>The application may use the CPU for image calculations and other general-purpose tasks.</li>
    <li>If supported, graphics work may be sent to the GPU.</li>
    <li>The display hardware presents the resulting interface and image to you.</li>
  </ol>
  <p>This example shows why the CPU is essential but not the only component involved in performance.</p>

  <h2>11. CPU performance: what actually matters?</h2>
  <div class="table-responsive"><table class="table table-bordered align-middle"><thead><tr><th>Factor</th><th>Why it matters</th></tr></thead><tbody>
    <tr><td>Architecture</td><td>Different designs can complete different amounts of work per clock cycle.</td></tr>
    <tr><td>Core count</td><td>Useful for workloads that can effectively run across multiple cores.</td></tr>
    <tr><td>Single-core performance</td><td>Important for applications whose critical work is concentrated in one or a few threads.</td></tr>
    <tr><td>Clock behaviour</td><td>Frequency can change with workload, temperature and power limits.</td></tr>
    <tr><td>Cache</td><td>Helps keep frequently used data and instructions close to the CPU.</td></tr>
    <tr><td>Power and cooling</td><td>A processor may not sustain its highest performance if system limits or temperatures restrict it.</td></tr>
    <tr><td>Workload</td><td>The best CPU for gaming may not be the best choice for rendering, compiling or office work.</td></tr>
  </tbody></table></div>

  <h2>12. How to identify your CPU</h2>
  <p>Do not guess the processor model from the computer's brand name. Check the actual system information.</p>
  <ol>
    <li>On Windows, open <strong>Task Manager → Performance → CPU</strong> to see the processor model and current activity.</li>
    <li>You can also open Windows <strong>System Information</strong> and inspect the processor entry.</li>
    <li>Record the exact model name rather than only writing “Intel” or “AMD”.</li>
    <li>Search the manufacturer's specifications when you need details such as cores, threads, cache and supported memory.</li>
  </ol>

  <h2>13. Understanding CPU utilisation</h2>
  <p><strong>CPU utilisation</strong> shows how much of the available processing capacity is being used at a particular moment. High utilisation is not automatically a problem. A demanding task may intentionally use nearly all available CPU resources.</p>
  <p>The useful question is: <strong>what is causing the CPU usage, and is the performance expected?</strong> A video export using high CPU is normal. A computer sitting idle with unexplained sustained high CPU usage deserves investigation.</p>

  <h2>14. Troubleshooting CPU-related slowdowns</h2>
  <ol>
    <li>Check Task Manager or another system monitor to see which process is using CPU time.</li>
    <li>Check whether the slowdown happens only in one application or everywhere.</li>
    <li>Check RAM usage too; memory pressure can create symptoms that feel like a CPU problem.</li>
    <li>Check storage activity and available free space.</li>
    <li>Check CPU temperature if performance drops during long workloads.</li>
    <li>Look for background applications, updates or malware only after observing the actual evidence.</li>
    <li>Avoid replacing the CPU until you have identified the real bottleneck.</li>
  </ol>

  <h2>15. Real-world example: gaming</h2>
  <p>In a game, the CPU may handle game logic, physics, simulation, input and other tasks while the GPU renders the visual scene. If the CPU cannot prepare frames quickly enough, the GPU may be left waiting. If the GPU is already fully occupied rendering, upgrading the CPU may produce little improvement at the same graphics settings.</p>
  <div class="alert alert-primary"><strong>Smart upgrade rule:</strong> Measure the bottleneck before buying hardware. “My PC is slow” is a symptom, not a diagnosis.</div>

  <h2>16. Practical activity: observe your CPU</h2>
  <ol>
    <li>Open your operating system's task manager or system monitor.</li>
    <li>Record your CPU model, core count if shown, and current utilisation.</li>
    <li>Open a browser with several tabs and observe how CPU usage changes.</li>
    <li>Run one demanding application and observe CPU usage again.</li>
    <li>If temperatures are available, record the temperature at idle and during the workload.</li>
    <li>Write three observations: <strong>what changed, what stayed stable, and what you think caused the change</strong>.</li>
  </ol>

  <h2>Common beginner mistakes</h2>
  <ul>
    <li>Thinking the CPU is the entire computer.</li>
    <li>Assuming higher GHz always means a faster processor.</li>
    <li>Assuming more cores automatically make every program faster.</li>
    <li>Confusing CPU cores with software threads.</li>
    <li>Calling every slowdown a “CPU problem” without checking RAM, storage, temperature or software.</li>
    <li>Buying a CPU without checking motherboard compatibility and supported memory.</li>
  </ul>

  <h2>Quick self-check</h2>
  <ol>
    <li>What does CPU stand for?</li>
    <li>What are the basic stages of the CPU instruction cycle?</li>
    <li>Why can two CPUs with the same GHz perform differently?</li>
    <li>What is the difference between a CPU core and a thread?</li>
    <li>What is CPU cache used for?</li>
    <li>Why can a CPU slow down when it becomes too hot?</li>
    <li>Why should you measure the bottleneck before upgrading a processor?</li>
  </ol>

  <h2>Key takeaway</h2>
  <p><strong>The CPU executes instructions, performs calculations and coordinates a huge amount of the computer's work.</strong> To understand CPU performance, look beyond GHz and consider architecture, cores, workload, cache, power and cooling. Most importantly, learn to observe the actual system before deciding that a CPU upgrade is necessary.</p>
</article>
HTML;
}

<?php
declare(strict_types=1);

/* Course 16 - Lesson 4: RAM vs Storage */
function lh_course16_lesson4_override(array $lesson, int $position): ?string
{
    $slug = (string)($lesson['slug'] ?? '');
    if ($slug !== 'course-16-lesson-4') {
        return null;
    }

    return <<<'HTML'
<article class="lh-prose">
  <div class="lh-lesson-intro">
    <p><strong>RAM and storage both hold data, but they solve very different problems.</strong> RAM is the computer's fast temporary working area, while storage keeps files and programs for the long term. Understanding the difference helps you diagnose slowdowns, choose upgrades and avoid confusing “more space” with “more memory.”</p>
  </div>

  <h2>1. RAM and storage in one sentence</h2>
  <div class="alert alert-primary"><strong>Easy rule:</strong> RAM helps the computer <strong>work with things right now</strong>; storage keeps things <strong>for later</strong>.</div>
  <p>When you open a browser, document or application, active data is loaded into RAM so the processor can work with it quickly. When you save a document or install an application, the data is normally stored on a drive so it remains available after the computer is turned off.</p>

  <h2>2. The core difference</h2>
  <div class="table-responsive"><table class="table table-bordered align-middle">
    <thead><tr><th>Feature</th><th>RAM</th><th>Storage</th></tr></thead>
    <tbody>
      <tr><td><strong>Purpose</strong></td><td>Temporary working memory</td><td>Long-term data storage</td></tr>
      <tr><td><strong>Power off</strong></td><td>Active contents are lost</td><td>Data normally remains saved</td></tr>
      <tr><td><strong>Typical capacity</strong></td><td>Measured in GB, commonly much smaller than storage</td><td>Measured in GB or TB</td></tr>
      <tr><td><strong>Used for</strong></td><td>Running applications and active data</td><td>Operating system, applications, documents, photos and videos</td></tr>
      <tr><td><strong>Performance role</strong></td><td>Helps multitasking and active workloads</td><td>Affects loading, saving and file access</td></tr>
    </tbody>
  </table></div>

  <h2>3. A simple desk and cupboard analogy</h2>
  <p>Imagine working at a desk. Your <strong>desk</strong> is RAM: it is the space where you keep the things you are actively using. Your <strong>cupboard</strong> is storage: it holds many more things, but you need to take something out before you can work with it.</p>
  <p>A bigger cupboard does not automatically give you a bigger desk. In the same way, a 1 TB SSD does not give a computer 1 TB of RAM.</p>

  <h2>4. What happens when you open an application?</h2>
  <ol>
    <li>The application files are stored on the storage drive.</li>
    <li>You launch the application.</li>
    <li>The operating system reads the required files from storage.</li>
    <li>Active program data is loaded into RAM.</li>
    <li>The CPU works with that active data and executes instructions.</li>
    <li>The application may read more data from storage or save results back to storage as you work.</li>
  </ol>
  <p>This explains why RAM and storage cooperate but are not interchangeable.</p>

  <h2>5. What does “low RAM” feel like?</h2>
  <p>If your workload needs more RAM than the computer can comfortably provide, the operating system may have to move less-active data between RAM and storage. This can make heavy multitasking feel sluggish, especially when many browser tabs or demanding applications are open.</p>
  <p>Typical clues include applications becoming slow when many programs are open, frequent pauses during multitasking, or memory usage staying close to the system's available capacity.</p>
  <div class="alert alert-secondary"><strong>Important:</strong> Do not diagnose low RAM from one symptom alone. Check actual memory usage while reproducing the slowdown.</div>

  <h2>6. What does “low storage” feel like?</h2>
  <p>Low storage is a different problem. You may see warnings that the drive is nearly full, have difficulty installing updates or applications, or be unable to save new files because there is not enough free space.</p>
  <p>A nearly full drive can also reduce the room available for temporary files and other system operations. Keeping reasonable free space is therefore part of normal maintenance.</p>

  <h2>7. HDD, SSD and NVMe storage</h2>
  <div class="table-responsive"><table class="table table-bordered align-middle">
    <thead><tr><th>Type</th><th>Basic idea</th><th>Typical characteristic</th></tr></thead>
    <tbody>
      <tr><td><strong>HDD</strong></td><td>Uses spinning magnetic platters</td><td>Large capacities can be economical, but mechanical movement makes it slower than modern SSDs for many tasks</td></tr>
      <tr><td><strong>SATA SSD</strong></td><td>Uses flash memory and a SATA interface</td><td>Much faster access and responsiveness than a traditional HDD</td></tr>
      <tr><td><strong>NVMe SSD</strong></td><td>Uses flash storage over PCIe</td><td>High throughput and low latency; useful for demanding workloads</td></tr>
    </tbody>
  </table></div>
  <p>These are all storage technologies. None of them replaces RAM as the computer's main working memory.</p>

  <h2>8. Why an SSD can make a computer feel faster</h2>
  <p>Replacing an old HDD with an SSD can significantly improve boot times, application launches and file access because the SSD can access data much more quickly and has no spinning mechanical parts.</p>
  <p>However, an SSD does not magically increase CPU performance or installed RAM. If a system is already limited by insufficient RAM or a heavily loaded CPU, storage alone may not solve every slowdown.</p>

  <h2>9. RAM capacity vs RAM speed</h2>
  <p>RAM has more than one specification. <strong>Capacity</strong> tells you how much working data can fit in memory. <strong>Speed</strong> describes how quickly memory can transfer data under the relevant platform conditions.</p>
  <p>For many everyday users, having enough RAM for the workload is more important than chasing a small speed difference. A computer that constantly runs short of memory can benefit more from additional compatible capacity than from a modest increase in memory speed.</p>

  <h2>10. Virtual memory and the page file</h2>
  <p>Modern operating systems can use part of a storage drive as additional virtual memory when RAM is under pressure. On Windows this is commonly associated with the page file.</p>
  <p>Virtual memory is useful for managing memory pressure, but storage is much slower than RAM. Using it does not mean an SSD has become “extra RAM” with the same performance characteristics.</p>

  <h2>11. What should you upgrade?</h2>
  <div class="table-responsive"><table class="table table-bordered align-middle">
    <thead><tr><th>Problem</th><th>Likely direction to investigate</th><th>Useful first check</th></tr></thead>
    <tbody>
      <tr><td>Many apps open and the system becomes sluggish</td><td>RAM</td><td>Watch memory usage while reproducing the slowdown</td></tr>
      <tr><td>Boot and application launches are very slow on an old PC</td><td>Storage</td><td>Check whether the system is still using an HDD</td></tr>
      <tr><td>Cannot install a large application</td><td>Storage capacity</td><td>Check free space on the relevant drive</td></tr>
      <tr><td>Games run at low frame rates</td><td>Usually CPU/GPU rather than storage capacity</td><td>Check CPU/GPU utilisation during gameplay</td></tr>
      <tr><td>Computer freezes under heavy multitasking</td><td>RAM, CPU or software</td><td>Check memory, CPU usage and the applications involved</td></tr>
    </tbody>
  </table></div>
  <p><strong>Never upgrade from a guess.</strong> Measure the bottleneck first, then choose a compatible component that addresses the actual limitation.</p>

  <h2>12. How to check RAM and storage in Windows</h2>
  <p>On a Windows PC, you can use <strong>Task Manager</strong> to inspect memory usage and identify the processor and drives visible to the system. The Settings or System Information areas can also provide hardware details, while File Explorer shows available storage space on individual drives.</p>
  <ol>
    <li>Open Task Manager and look at the <strong>Performance</strong> section.</li>
    <li>Select <strong>Memory</strong> and note installed capacity and current usage.</li>
    <li>Select the available <strong>Disk</strong> entries to inspect drive activity and identify storage devices.</li>
    <li>Open File Explorer and check how much free space remains on the main drive.</li>
    <li>Record the information instead of relying on memory or assumptions.</li>
  </ol>

  <h2>13. Practical activity: find your own bottleneck</h2>
  <ol>
    <li>Write down your computer's installed RAM capacity.</li>
    <li>Open your normal set of applications and browser tabs.</li>
    <li>Observe memory usage while doing your usual work.</li>
    <li>Record how much free storage remains on the main drive.</li>
    <li>Open a large application and notice whether the main delay happens during loading or while several applications are already running.</li>
    <li>Decide whether the evidence points more toward storage speed, storage capacity, RAM capacity or another component.</li>
  </ol>
  <p><strong>Goal:</strong> make an upgrade decision from evidence rather than from a specification number seen in an advertisement.</p>

  <h2>14. Common beginner mistakes</h2>
  <ul>
    <li>Calling storage “memory” and assuming RAM and storage are the same thing.</li>
    <li>Thinking a bigger SSD automatically makes every application run faster.</li>
    <li>Assuming an SSD can replace insufficient RAM.</li>
    <li>Buying RAM without checking motherboard compatibility, supported type and capacity limits.</li>
    <li>Adding RAM when the real bottleneck is the CPU, GPU, software or a failing drive.</li>
    <li>Ignoring free storage space until the system is almost completely full.</li>
  </ul>

  <h2>Quick self-check</h2>
  <ol>
    <li>What is the main purpose of RAM?</li>
    <li>Why does data normally remain on storage after the computer is turned off?</li>
    <li>Why can an SSD improve boot and application loading without increasing RAM?</li>
    <li>What is the difference between RAM capacity and RAM speed?</li>
    <li>Why is virtual memory not the same as having more physical RAM?</li>
    <li>What evidence should you collect before deciding to upgrade RAM or storage?</li>
  </ol>

  <h2>Key takeaway</h2>
  <p><strong>RAM is the computer's active workspace; storage is its long-term filing system.</strong> RAM capacity affects how comfortably the system handles active workloads, while storage capacity and technology affect how much data you can keep and how quickly the system can access it. Learn to identify which resource is actually limiting your computer before spending money on an upgrade.</p>
</article>
HTML;
}

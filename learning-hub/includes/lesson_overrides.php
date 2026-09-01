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
  <p>A computer works with data. You give it information through an input device or program, the processor and other components work on that information, and the result is shown or saved.</p>
  <h2>The main parts of a computer</h2>
  <ul><li><strong>CPU:</strong> executes instructions and performs calculations.</li><li><strong>RAM:</strong> provides fast temporary working space.</li><li><strong>Storage:</strong> keeps files and programs long term.</li><li><strong>Motherboard:</strong> connects major components.</li></ul>
  <h2>Practice task</h2><p>Write down five physical components and describe the job of each.</p>
</article>
HTML;
    }

    if ($courseSlug === 'computer-basics-for-beginners' && $position === 4) {
        return <<<'HTML'
<article class="lh-prose">
  <div class="lh-lesson-intro">
    <p><strong>RAM and storage are not the same thing.</strong> RAM is fast working memory used while programs are running. Storage, such as an SSD or HDD, keeps files and programs even after the computer is turned off.</p>
  </div>

  <h2>RAM: your computer's working space</h2>
  <p>When you open a browser, document or game, the operating system loads the information the program needs into RAM. The CPU can access active data from RAM much more quickly than it can from permanent storage.</p>
  <p>RAM is <strong>volatile memory</strong>: its contents are normally lost when power is removed. That is why unsaved work can disappear after a sudden shutdown.</p>

  <h2>Storage: keeping data for the long term</h2>
  <p>An SSD or HDD stores the operating system, applications, photos, videos and documents. Storage is <strong>non-volatile</strong>, so its data remains available after the computer is turned off.</p>

  <h2>RAM vs storage</h2>
  <div class="table-responsive">
    <table class="table table-bordered align-middle">
      <thead><tr><th>Feature</th><th>RAM</th><th>Storage</th></tr></thead>
      <tbody>
        <tr><td>Main purpose</td><td>Active working data</td><td>Long-term files and programs</td></tr>
        <tr><td>Power off</td><td>Data is normally lost</td><td>Data remains saved</td></tr>
        <tr><td>Typical technology</td><td>DRAM</td><td>SSD / HDD</td></tr>
        <tr><td>Example</td><td>Keeping a browser and spreadsheet open</td><td>Saving the spreadsheet file</td></tr>
      </tbody>
    </table>
  </div>

  <h2>A simple real-world example</h2>
  <p>Suppose you open a 200 MB project from an SSD. The file remains on the SSD, but the application loads the working parts into RAM. As you edit the project, active data is held in RAM while the application writes saved changes back to storage.</p>

  <div class="alert alert-primary"><strong>Smart tip:</strong> Think of RAM as the desk where you work and storage as the cabinet where you keep your files.</div>

  <h2>Why more RAM can help</h2>
  <p>When available RAM becomes limited, an operating system may move some less-active data to storage. Storage is slower than RAM, so heavy memory pressure can make multitasking feel less responsive. More RAM can help when your normal workload regularly runs short of working memory.</p>

  <h2>Why more storage is different</h2>
  <p>Adding storage gives you more room for files and applications. It does not automatically give a program more working memory. A computer with a large SSD can still feel slow if its workload needs more RAM or if another component is the bottleneck.</p>

  <h2>Practical task</h2>
  <ol><li>Open your system monitor or Task Manager.</li><li>Look at current memory usage.</li><li>Open two or three normal applications.</li><li>Observe how memory usage changes.</li><li>Record whether the computer remains responsive.</li></ol>

  <h2>Common mistakes</h2>
  <ul><li>Calling an SSD “memory” in the same sense as RAM.</li><li>Assuming a larger storage drive makes every program faster.</li><li>Thinking unused RAM is automatically wasted RAM.</li><li>Buying more RAM without checking what the actual slowdown is.</li></ul>

  <h2>Quick check</h2>
  <p>What happens to RAM when a computer is powered off? Why can adding an SSD increase available storage without increasing working memory?</p>

  <h2>Key takeaway</h2>
  <p><strong>RAM holds the data you are actively working with; storage keeps your data and programs for the long term.</strong> Knowing the difference helps you choose upgrades intelligently and troubleshoot slow computers.</p>
</article>
HTML;
    }

    if ($courseSlug === 'computer-basics-for-beginners' && $position === 5) {
        return <<<'HTML'
<article class="lh-prose">
  <div class="lh-lesson-intro">
    <p><strong>Hard drives (HDDs) and solid-state drives (SSDs)</strong> both provide permanent storage, but they store and retrieve data using very different technologies. Understanding the difference helps you choose the right drive and diagnose slow storage.</p>
  </div>

  <h2>HDD: magnetic storage with moving parts</h2>
  <p>A hard disk drive stores data magnetically on spinning platters. A mechanical actuator moves read/write heads over the platter surface to access data. Because the drive contains moving parts, physical shocks, vibration and mechanical wear can matter.</p>

  <h2>SSD: flash storage with no moving parts</h2>
  <p>A solid-state drive stores data in NAND flash memory. It has no spinning platter or moving read/write head. The controller manages where data is stored and performs tasks such as wear management and error correction.</p>

  <h2>HDD vs SSD</h2>
  <div class="table-responsive">
    <table class="table table-bordered align-middle">
      <thead><tr><th>Feature</th><th>HDD</th><th>SSD</th></tr></thead>
      <tbody>
        <tr><td>Storage technology</td><td>Magnetic platters</td><td>NAND flash memory</td></tr>
        <tr><td>Moving parts</td><td>Yes</td><td>No</td></tr>
        <tr><td>Typical access latency</td><td>Higher</td><td>Much lower</td></tr>
        <tr><td>Noise</td><td>Can produce mechanical noise</td><td>Silent during normal operation</td></tr>
        <tr><td>Shock resistance</td><td>More sensitive while operating</td><td>Generally more resistant to physical shock</td></tr>
        <tr><td>Common strength</td><td>Large capacity at relatively low cost</td><td>Fast everyday responsiveness</td></tr>
      </tbody>
    </table>
  </div>

  <h2>Why an SSD can make a computer feel faster</h2>
  <p>Replacing a mechanical system drive with an SSD can reduce storage access latency dramatically. This is noticeable during tasks such as starting the operating system, launching applications, opening many small files and loading projects. The SSD does not make the CPU itself faster; it reduces waiting for storage operations.</p>

  <div class="alert alert-primary">
    <strong>Smart tip:</strong> “SSD is faster” does not mean every workload becomes equally faster. CPU-heavy calculations, insufficient RAM, network delays and other bottlenecks can still dominate performance.
  </div>

  <h2>Capacity is different from speed</h2>
  <p>A 1 TB drive describes how much data it can hold, not how quickly every task will run. Two drives with the same capacity can have very different performance. When comparing storage, consider capacity, interface, sustained performance, workload, reliability and price.</p>

  <h2>What are SATA and NVMe?</h2>
  <p><strong>SATA</strong> is a storage interface commonly used by 2.5-inch SSDs and older HDDs. <strong>NVMe</strong> is a protocol designed for non-volatile memory and is commonly used by SSDs connected through PCIe. NVMe SSDs can provide much higher throughput and lower overhead than SATA SSDs, especially for demanding workloads.</p>

  <h2>Practical example: choosing a drive</h2>
  <p>Imagine a home computer used for web browsing, documents, photos and backups. An SSD is a strong choice for the operating system and everyday applications because quick access improves responsiveness. A large HDD can still be useful for storing a large collection of less frequently accessed files when capacity per unit cost is the priority.</p>

  <h2>Checking your own drive</h2>
  <ol>
    <li>Open your operating system's storage or system information screen.</li>
    <li>Identify whether the installed drive is an HDD or SSD.</li>
    <li>Note its capacity and how much free space remains.</li>
    <li>Observe disk activity while opening a large application or copying a file.</li>
    <li>Record whether storage activity appears to be the limiting factor.</li>
  </ol>

  <h2>Storage health and backups</h2>
  <p>A healthy drive can still fail. Important files should have a backup separate from the computer. For an HDD, unusual clicking, grinding or repeated read errors can be warning signs. SSDs also have finite write endurance and can fail without the same mechanical symptoms, so monitoring and backups remain important for both.</p>

  <h2>Common mistakes</h2>
  <ul>
    <li>Thinking an SSD is a type of RAM.</li>
    <li>Assuming more gigabytes automatically means more speed.</li>
    <li>Believing an SSD makes the CPU more powerful.</li>
    <li>Keeping the only copy of important files on one drive.</li>
    <li>Buying an NVMe SSD without checking whether the computer supports the required form factor and interface.</li>
  </ul>

  <h2>Practice task</h2>
  <p>Check the storage device in your computer. Write down its type, capacity, interface if available, and free space. Then choose one real workload—such as booting, launching an application or copying files—and explain why storage speed might or might not be the bottleneck.</p>

  <h2>Quick check</h2>
  <ol>
    <li>What physical difference separates an HDD from an SSD?</li>
    <li>Why can an SSD improve application launch times?</li>
    <li>Does a larger capacity drive automatically have higher performance?</li>
    <li>Why are backups important even when a drive appears healthy?</li>
  </ol>

  <h2>Key takeaway</h2>
  <p><strong>HDDs use magnetic spinning media, while SSDs use flash memory.</strong> SSDs usually provide much lower access latency and faster everyday responsiveness, while HDDs can remain attractive when large capacity at low cost is the main goal.</p>
</article>
HTML;
    }

    return null;
}

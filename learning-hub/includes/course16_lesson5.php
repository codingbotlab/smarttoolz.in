<?php
declare(strict_types=1);

/* Course 16 - Lesson 5: Hard Drives and SSDs */
function lh_course16_lesson5_override(array $lesson, int $position): ?string
{
    $slug = (string)($lesson['slug'] ?? '');
    if ($slug !== 'course-16-lesson-5') {
        return null;
    }

    return <<<'HTML'
<article class="lh-prose">
  <div class="lh-lesson-intro">
    <p><strong>Storage is where your files, applications and operating system are kept when the computer is turned off.</strong> Understanding HDDs and SSDs helps you choose the right drive, diagnose slow computers, protect important data and plan sensible upgrades.</p>
  </div>

  <h2>1. What does a storage drive do?</h2>
  <p>A storage drive provides <strong>persistent storage</strong>. Unlike RAM, its contents remain available after the computer loses power. Documents, photos, videos, applications and the operating system are normally stored here.</p>
  <div class="alert alert-primary"><strong>Easy way to remember:</strong> RAM is your computer's short-term working space; storage is where the computer keeps things for later.</div>

  <h2>2. HDD vs SSD at a glance</h2>
  <div class="table-responsive"><table class="table table-bordered align-middle"><thead><tr><th>Feature</th><th>HDD</th><th>SSD</th></tr></thead><tbody>
    <tr><td>Technology</td><td>Magnetic platters with moving parts</td><td>Flash memory with no spinning platters</td></tr>
    <tr><td>Typical responsiveness</td><td>Slower random access</td><td>Much faster random access</td></tr>
    <tr><td>Noise</td><td>Can make mechanical noise</td><td>Silent</td></tr>
    <tr><td>Shock resistance</td><td>More sensitive to physical shock while operating</td><td>Generally more resistant to movement and shock</td></tr>
    <tr><td>Best-known advantage</td><td>Large capacity at relatively low cost</td><td>Speed and responsiveness</td></tr>
  </tbody></table></div>

  <h2>3. How a hard disk drive works</h2>
  <p>A traditional <strong>HDD (Hard Disk Drive)</strong> stores data magnetically on spinning platters. A read/write head moves across the platter surface to access data.</p>
  <p>Because an HDD contains moving mechanical parts, accessing many small pieces of data can involve physical movement. This is one reason an old HDD can make an operating system feel sluggish even when the CPU is still capable of handling the workload.</p>

  <h2>4. How an SSD works</h2>
  <p>An <strong>SSD (Solid State Drive)</strong> stores data in flash memory rather than on spinning magnetic platters. There are no mechanical read/write heads moving across a disk.</p>
  <p>This allows an SSD to provide very low access latency and much faster everyday responsiveness. Booting the operating system, launching applications and opening many small files can feel dramatically quicker after moving from an old HDD to an SSD.</p>

  <h2>5. SATA SSD vs NVMe SSD</h2>
  <p>Not all SSDs use the same interface. A <strong>SATA SSD</strong> commonly uses the same broad storage interface family as many older hard drives. An <strong>NVMe SSD</strong> communicates over PCIe and is designed for high-performance flash storage.</p>
  <div class="table-responsive"><table class="table table-bordered align-middle"><thead><tr><th>Drive type</th><th>Typical connection</th><th>What to know</th></tr></thead><tbody>
    <tr><td>HDD</td><td>SATA in many desktop/laptop systems</td><td>High capacity, mechanical and slower random access</td></tr>
    <tr><td>SATA SSD</td><td>SATA</td><td>Large everyday responsiveness improvement over HDD</td></tr>
    <tr><td>NVMe SSD</td><td>PCIe, commonly through an M.2 slot</td><td>Very high throughput and low latency; requires compatible hardware</td></tr>
  </tbody></table></div>
  <p><strong>Important:</strong> M.2 describes a physical form factor, not automatically a performance level. An M.2 drive can use different interfaces, so check the motherboard and drive specifications before buying.</p>

  <h2>6. Capacity is not the same as speed</h2>
  <p>A drive's capacity tells you how much data it can hold. A larger capacity does not automatically mean a faster drive. Performance also depends on the storage technology, interface, controller, workload and other specifications.</p>
  <p>For example, a large HDD can hold many files but still feel slow during operating-system startup. A smaller SSD may provide much better responsiveness while offering less total space.</p>

  <h2>7. Why an SSD can transform an old computer</h2>
  <p>When a computer uses an old HDD as its system drive, many everyday operations involve relatively slow storage access. Replacing that drive with a suitable SSD can reduce access latency and improve application launch and boot responsiveness.</p>
  <p>This does <strong>not</strong> mean an SSD magically makes every workload faster. A CPU-heavy calculation, for example, may still be limited by the processor. The improvement is strongest when storage access was the bottleneck.</p>

  <h2>8. Read speed, write speed and IOPS</h2>
  <p><strong>Read speed</strong> describes how quickly data can be retrieved under a given test. <strong>Write speed</strong> describes how quickly data can be written. Sequential workloads move large blocks of data, while random workloads access many smaller pieces.</p>
  <p><strong>IOPS</strong> (input/output operations per second) is another useful performance measure, especially for workloads involving many small operations. Do not judge a drive only by one large sequential speed number; real workloads can behave differently.</p>

  <h2>9. SSD endurance and health</h2>
  <p>Flash storage has a finite amount of write endurance, and SSDs use controllers and techniques such as wear levelling to distribute writes. Modern consumer SSDs are designed for normal workloads, but keeping backups is still essential.</p>
  <p>A drive-health indicator can provide useful warnings, but it should never be treated as a replacement for backups. A drive can fail unexpectedly even when a health tool appears normal.</p>

  <h2>10. The most important storage rule: backup</h2>
  <p><strong>Storage is not backup.</strong> A second copy on the same physical drive does not protect you from drive failure. Important files should have additional copies, ideally using a backup strategy that separates copies from the original system.</p>
  <ul>
    <li>Keep important documents in more than one location.</li>
    <li>Use automatic backups when practical.</li>
    <li>Test that backups can actually be restored.</li>
    <li>Do not assume cloud sync is identical to a full backup.</li>
  </ul>

  <h2>11. Free space matters</h2>
  <p>A nearly full system drive can make storage management harder and may affect some workloads. Operating systems and applications also need working space for updates, temporary files, caches and other operations.</p>
  <p>Instead of waiting until the drive is completely full, regularly review large files, unused applications and temporary data. Never delete system files blindly just to create space.</p>

  <h2>12. Choosing the right drive</h2>
  <div class="table-responsive"><table class="table table-bordered align-middle"><thead><tr><th>Your situation</th><th>Sensible direction</th><th>Why</th></tr></thead><tbody>
    <tr><td>Old PC with a slow HDD</td><td>Compatible SSD</td><td>Usually the biggest responsiveness improvement</td></tr>
    <tr><td>Need lots of inexpensive bulk storage</td><td>Large HDD or suitable bulk-storage solution</td><td>Capacity can be cost-effective</td></tr>
    <tr><td>Modern desktop with compatible M.2 PCIe slot</td><td>NVMe SSD</td><td>High performance and convenient form factor</td></tr>
    <tr><td>Important files</td><td>Any reliable primary drive + separate backup</td><td>Reduces risk from hardware failure or accidental deletion</td></tr>
  </tbody></table></div>

  <h2>13. Troubleshooting a slow storage system</h2>
  <ol>
    <li><strong>Identify the drive:</strong> Find out whether the system uses an HDD, SATA SSD or NVMe SSD.</li>
    <li><strong>Check free space:</strong> Look for a critically full system volume.</li>
    <li><strong>Observe disk usage:</strong> Check whether storage activity is unusually high during the slowdown.</li>
    <li><strong>Look for the workload:</strong> Identify which application or process is causing heavy reads or writes.</li>
    <li><strong>Check drive health:</strong> Use an appropriate operating-system or manufacturer diagnostic.</li>
    <li><strong>Back up important files:</strong> Do this before attempting risky repair or replacement steps.</li>
  </ol>

  <h2>14. Practical activity: inspect your own storage</h2>
  <ol>
    <li>Open your operating system's storage or system-information screen.</li>
    <li>Record the drive model and approximate capacity.</li>
    <li>Identify whether it is an HDD, SATA SSD or NVMe SSD.</li>
    <li>Record how much free space remains on the system drive.</li>
    <li>Identify your three largest folders or categories of data.</li>
    <li>Check whether your important files have a separate backup.</li>
    <li>Write one sentence explaining whether your current storage is limited by <strong>capacity, speed, compatibility or backup safety</strong>.</li>
  </ol>

  <h2>Common beginner mistakes</h2>
  <ul>
    <li>Thinking every SSD is automatically NVMe.</li>
    <li>Assuming M.2 always means the same interface or performance.</li>
    <li>Choosing a drive only by capacity or advertised sequential speed.</li>
    <li>Using the same drive as both the original data and the only backup.</li>
    <li>Replacing a drive without checking compatibility, physical size and connector/interface.</li>
    <li>Assuming an SSD will fix a CPU or RAM bottleneck.</li>
  </ul>

  <h2>Quick self-check</h2>
  <ol>
    <li>What makes an HDD different from an SSD?</li>
    <li>Why does an SSD usually feel more responsive than an old HDD?</li>
    <li>What is the difference between SATA SSD and NVMe SSD?</li>
    <li>Does a larger-capacity drive automatically perform faster?</li>
    <li>Why is storage not the same thing as backup?</li>
    <li>What should you check before buying an M.2 SSD?</li>
  </ol>

  <h2>Key takeaway</h2>
  <p><strong>Choose storage based on the actual job: capacity, responsiveness, compatibility and data safety all matter.</strong> For many older computers, a compatible SSD is a high-impact upgrade, but no drive should be trusted as the only copy of important data.</p>
</article>
HTML;
}

<?php
declare(strict_types=1);

/* Hand-written lesson content. Add one lesson at a time; never use as a generic fallback. */
function lh_lesson_override(array $course, array $lesson, int $position): ?string {
    $courseSlug = (string)($course['slug'] ?? '');

    if ($courseSlug === 'computer-basics-for-beginners' && $position === 1) {
        return <<<'HTML'
<article class="lh-prose"><div class="lh-lesson-intro"><p>A computer is an electronic machine that takes <strong>input</strong>, processes it according to instructions, stores information when needed, and produces <strong>output</strong>.</p></div><h2>What is a computer?</h2><p>A computer works with data. You give it information, the processor and other components work on it, and the result is shown or saved.</p><h2>The main parts</h2><ul><li><strong>CPU:</strong> executes instructions.</li><li><strong>RAM:</strong> provides fast temporary working space.</li><li><strong>Storage:</strong> keeps files and programs long term.</li><li><strong>Motherboard:</strong> connects major components.</li></ul><h2>Practice task</h2><p>Write down five physical components and describe the job of each.</p></article>
HTML;
    }

    if ($courseSlug === 'computer-basics-for-beginners' && $position === 4) {
        return <<<'HTML'
<article class="lh-prose"><div class="lh-lesson-intro"><p><strong>RAM and storage are not the same thing.</strong> RAM is fast working memory used while programs are running. Storage keeps files and programs after the computer is turned off.</p></div><h2>RAM</h2><p>When you open a browser or document, the operating system loads working data into RAM. RAM is volatile memory, so its contents are normally lost when power is removed.</p><h2>Storage</h2><p>An SSD or HDD stores the operating system, applications, photos, videos and documents. Storage is non-volatile.</p><h2>RAM vs storage</h2><div class="table-responsive"><table class="table table-bordered align-middle"><thead><tr><th>Feature</th><th>RAM</th><th>Storage</th></tr></thead><tbody><tr><td>Purpose</td><td>Active working data</td><td>Long-term data</td></tr><tr><td>Power off</td><td>Normally loses contents</td><td>Keeps data</td></tr><tr><td>Examples</td><td>DRAM</td><td>SSD / HDD</td></tr></tbody></table></div><h2>Practice</h2><p>Open your system monitor, note memory use, then open two or three applications and observe the change.</p><h2>Key takeaway</h2><p><strong>RAM is working space; storage is long-term space.</strong></p></article>
HTML;
    }

    if ($courseSlug === 'computer-basics-for-beginners' && $position === 5) {
        return <<<'HTML'
<article class="lh-prose"><div class="lh-lesson-intro"><p><strong>Hard drives (HDDs) and solid-state drives (SSDs)</strong> both provide permanent storage, but use different technologies.</p></div><h2>HDD</h2><p>An HDD stores data magnetically on spinning platters. A mechanical actuator moves read/write heads to access data.</p><h2>SSD</h2><p>An SSD stores data in NAND flash memory and has no spinning platter or mechanical read/write head.</p><h2>HDD vs SSD</h2><div class="table-responsive"><table class="table table-bordered align-middle"><thead><tr><th>Feature</th><th>HDD</th><th>SSD</th></tr></thead><tbody><tr><td>Technology</td><td>Magnetic platters</td><td>NAND flash</td></tr><tr><td>Moving parts</td><td>Yes</td><td>No</td></tr><tr><td>Access latency</td><td>Higher</td><td>Much lower</td></tr><tr><td>Strength</td><td>Large capacity at relatively low cost</td><td>Fast everyday responsiveness</td></tr></tbody></table></div><h2>Practical task</h2><ol><li>Identify your system drive type.</li><li>Record capacity and free space.</li><li>Observe disk activity while opening an application.</li></ol><h2>Key takeaway</h2><p><strong>HDDs use magnetic spinning media; SSDs use flash memory.</strong></p></article>
HTML;
    }

    if ($courseSlug === 'computer-basics-for-beginners' && $position === 6) {
        return <<<'HTML'
<article class="lh-prose">
  <div class="lh-lesson-intro"><p>The <strong>motherboard</strong> is the main circuit board that provides the electrical connections and communication pathways between many important computer components. It does not perform every computation itself; its job is to let the CPU, memory, storage, expansion devices and other hardware work together.</p></div>
  <h2>What is a motherboard?</h2>
  <p>A motherboard contains sockets, slots, connectors and circuitry that allow components to communicate. The exact layout differs between desktops, laptops and other devices, but the underlying idea is the same: components need reliable pathways for data, control signals and power.</p>
  <h2>Important motherboard areas</h2>
  <div class="table-responsive"><table class="table table-bordered align-middle"><thead><tr><th>Part</th><th>What it is used for</th></tr></thead><tbody>
    <tr><td>CPU socket</td><td>Holds and electrically connects the processor.</td></tr>
    <tr><td>DIMM / memory slots</td><td>Connect system RAM to the memory controller.</td></tr>
    <tr><td>PCIe slots</td><td>Connect expansion hardware such as graphics cards and some other add-in devices.</td></tr>
    <tr><td>M.2 slots</td><td>Provide a compact connection for supported SSDs or other devices.</td></tr>
    <tr><td>SATA connectors</td><td>Connect compatible SATA storage drives and some other devices.</td></tr>
    <tr><td>Rear I/O ports</td><td>Provide external connections such as USB, networking, audio and display outputs depending on the board.</td></tr>
  </tbody></table></div>
  <h2>Chipset and platform compatibility</h2>
  <p>The motherboard must be compatible with the CPU platform, memory type and other hardware. A processor cannot simply be installed into any board: the socket, firmware support, power requirements and platform features all matter. Likewise, a DDR memory module must match the memory technology supported by the board.</p>
  <div class="alert alert-primary"><strong>Smart tip:</strong> When planning an upgrade, check the motherboard's manual or manufacturer specifications instead of relying only on the physical appearance of a connector.</div>
  <h2>How the motherboard fits into a real task</h2>
  <p>Suppose you open a program stored on an SSD. The storage connection transfers data into system memory, the CPU works with that data, and the motherboard provides the physical and electrical pathways that let these components communicate. If you install a graphics card, its PCIe connection provides another high-speed communication path.</p>
  <h2>Why motherboard problems can be confusing</h2>
  <p>A motherboard connects many subsystems, so a fault can appear as a problem with another component. A failed memory slot can look like bad RAM. A damaged storage connector can look like a missing drive. A power-delivery problem can prevent a system from starting at all. Diagnosis therefore requires testing components and connections systematically rather than immediately replacing the motherboard.</p>
  <h2>Practical troubleshooting checklist</h2>
  <ol><li>Power the computer off before opening a desktop case and follow safe handling procedures.</li><li>Check that major power connectors are firmly seated.</li><li>Check whether RAM, expansion cards and storage are correctly installed.</li><li>Use the motherboard manual to identify diagnostic LEDs, beep codes or status indicators when available.</li><li>Test one change at a time so you know which change affected the result.</li></ol>
  <h2>Practice task</h2>
  <p>Find a diagram or photograph of a motherboard you own or can safely inspect. Identify the CPU socket, RAM slots, storage connectors, expansion slots and rear I/O area. Write down what device is connected to each area.</p>
  <h2>Common mistakes</h2>
  <ul><li>Thinking the motherboard is the same thing as the CPU.</li><li>Assuming every slot supports every generation of hardware.</li><li>Forcing a connector that does not fit naturally.</li><li>Replacing a motherboard before checking cables, seating and compatibility.</li></ul>
  <h2>Quick check</h2>
  <ol><li>What is the motherboard's main role?</li><li>What type of hardware commonly uses a PCIe slot?</li><li>Why should you check compatibility before installing a CPU or RAM?</li></ol>
  <h2>Key takeaway</h2>
  <p><strong>The motherboard is the system's central hardware platform: it connects components and provides the pathways that allow them to communicate and receive power.</strong></p>
</article>
HTML;
    }

    /* Course 16 = Graphic Design Foundations in the master curriculum. */
    if (($courseSlug === 'graphic-design-foundations' || $courseSlug === 'course-16') && $position === 1) {
        return <<<'HTML'
<article class="lh-prose"><div class="lh-lesson-intro"><p><strong>Graphic design is visual problem-solving.</strong> A good design helps a specific audience understand, notice or act on information.</p></div><h2>Start with the goal</h2><p>Before choosing fonts or colours, decide who will see the design, what they should notice first and what action they should take.</p><h2>Core building blocks</h2><ul><li><strong>Typography:</strong> communicates words and tone.</li><li><strong>Colour:</strong> creates emphasis and relationships.</li><li><strong>Images:</strong> show, explain or create emotion.</li><li><strong>Layout:</strong> organises information.</li><li><strong>Whitespace:</strong> creates breathing room.</li></ul><h2>Practice project</h2><p>Create a simple 1080 × 1080 workshop announcement with a title, date, short description and call to action. Make the primary message visually dominant and review it at phone size.</p></article>
HTML;
    }

    return null;
}

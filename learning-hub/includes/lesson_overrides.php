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
<article class="lh-prose"><div class="lh-lesson-intro"><p>The <strong>motherboard</strong> is the main circuit board that provides the electrical connections and communication pathways between many important computer components.</p></div><h2>What is a motherboard?</h2><p>A motherboard contains sockets, slots, connectors and circuitry that allow components to communicate.</p><h2>Important motherboard areas</h2><div class="table-responsive"><table class="table table-bordered align-middle"><thead><tr><th>Part</th><th>What it is used for</th></tr></thead><tbody><tr><td>CPU socket</td><td>Holds and electrically connects the processor.</td></tr><tr><td>Memory slots</td><td>Connect system RAM.</td></tr><tr><td>PCIe slots</td><td>Connect expansion hardware such as graphics cards.</td></tr><tr><td>M.2 slots</td><td>Connect supported SSDs and other devices.</td></tr><tr><td>SATA connectors</td><td>Connect compatible SATA drives.</td></tr></tbody></table></div><h2>Practical troubleshooting</h2><ol><li>Check power connections.</li><li>Check RAM, expansion cards and storage seating.</li><li>Use motherboard diagnostic indicators when available.</li><li>Test one change at a time.</li></ol><h2>Key takeaway</h2><p><strong>The motherboard connects major hardware and provides pathways for communication and power.</strong></p></article>
HTML;
    }

    /* Course 16 = Graphic Design Foundations in the master curriculum. */
    if (($courseSlug === 'graphic-design-foundations' || $courseSlug === 'course-16') && $position === 1) {
        return <<<'HTML'
<article class="lh-prose"><div class="lh-lesson-intro"><p><strong>Graphic design is visual problem-solving.</strong> A good design helps a specific audience understand, notice or act on information.</p></div><h2>Start with the goal</h2><p>Before choosing fonts or colours, decide who will see the design, what they should notice first and what action they should take.</p><h2>Core building blocks</h2><ul><li><strong>Typography:</strong> communicates words and tone.</li><li><strong>Colour:</strong> creates emphasis and relationships.</li><li><strong>Images:</strong> show, explain or create emotion.</li><li><strong>Layout:</strong> organises information.</li><li><strong>Whitespace:</strong> creates breathing room.</li></ul><h2>Practice project</h2><p>Create a simple 1080 × 1080 workshop announcement with a title, date, short description and call to action. Make the primary message visually dominant and review it at phone size.</p></article>
HTML;
    }

    if (($courseSlug === 'graphic-design-foundations' || $courseSlug === 'course-16') && $position === 2) {
        return <<<'HTML'
<article class="lh-prose">
  <div class="lh-lesson-intro"><p>Graphic design becomes easier to discuss when you know the vocabulary. These terms describe the building blocks and decisions you will use in almost every design project.</p></div>
  <h2>Essential terms</h2>
  <div class="table-responsive"><table class="table table-bordered align-middle"><thead><tr><th>Term</th><th>Meaning</th><th>Practical use</th></tr></thead><tbody>
    <tr><td>Composition</td><td>How visual elements are arranged as a whole.</td><td>Controls where the viewer looks and how information feels organised.</td></tr>
    <tr><td>Hierarchy</td><td>The visual order that tells viewers what to notice first, second and third.</td><td>Make a headline stronger than supporting text.</td></tr>
    <tr><td>Alignment</td><td>Positioning elements along shared edges, centres or guides.</td><td>Keeps a poster or social post from looking randomly assembled.</td></tr>
    <tr><td>Contrast</td><td>Visible difference between elements.</td><td>Separate a call-to-action from surrounding information.</td></tr>
    <tr><td>Whitespace</td><td>Intentional empty space around or between elements.</td><td>Improves readability and gives important content room to stand out.</td></tr>
    <tr><td>Typography</td><td>The design and arrangement of written text.</td><td>Choose typefaces, sizes, weights, spacing and line length for clarity.</td></tr>
    <tr><td>Grid</td><td>A system of guides used to organise content consistently.</td><td>Keep cards, columns and text blocks aligned across multiple designs.</td></tr>
    <tr><td>Palette</td><td>A selected set of colours used together.</td><td>Creates consistency across a brand or campaign.</td></tr>
  </tbody></table></div>
  <h2>Terms work together</h2>
  <p>Good design rarely depends on one principle. For example, a social-media announcement might use a grid to establish alignment, a large heading to create hierarchy, contrast to highlight the date, whitespace to separate sections, and a limited colour palette to maintain consistency.</p>
  <div class="alert alert-primary"><strong>Designer habit:</strong> Do not ask only “Does this look nice?” Ask “What should the viewer notice first, and which design decision makes that happen?”</div>
  <h2>Mini analysis task</h2>
  <ol><li>Choose one poster, advertisement or social post you see today.</li><li>Identify its strongest hierarchy decision.</li><li>Find one example of alignment and one example of contrast.</li><li>Notice where whitespace has been used.</li><li>Write one change that would improve clarity without adding another decorative element.</li></ol>
  <h2>Common mistakes</h2>
  <ul><li>Adding more elements when the real problem is weak hierarchy.</li><li>Using contrast everywhere until nothing feels important.</li><li>Treating whitespace as wasted space.</li><li>Choosing terminology without understanding the visual problem it solves.</li></ul>
  <h2>Quick self-check</h2>
  <p>Can you explain the difference between hierarchy and alignment? Can you point to one design where whitespace improves readability and describe why?</p>
  <h2>Key takeaway</h2>
  <p><strong>Design vocabulary gives you a precise way to diagnose and improve visual communication.</strong></p>
</article>
HTML;
    }

    return null;
}

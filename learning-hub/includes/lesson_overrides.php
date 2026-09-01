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

    if ($courseSlug === 'computer-basics-for-beginners' && $position === 7) {
        return <<<'HTML'
<article class="lh-prose"><div class="lh-lesson-intro"><p>A <strong>GPU (Graphics Processing Unit)</strong> is a processor designed to perform graphics and other workloads that can be divided into many parallel operations.</p></div><h2>What does a GPU do?</h2><p>A GPU is built to handle large numbers of similar calculations in parallel, while the CPU is designed for broader general-purpose instruction processing.</p><h2>Integrated vs dedicated graphics</h2><div class="table-responsive"><table class="table table-bordered align-middle"><thead><tr><th>Type</th><th>Typical setup</th><th>Practical trade-off</th></tr></thead><tbody><tr><td>Integrated GPU</td><td>Built into the processor or platform</td><td>Usually lower power and cost; often shares system memory.</td></tr><tr><td>Dedicated GPU</td><td>Separate graphics processor/card</td><td>Usually higher graphics throughput, with more power and cost.</td></tr></tbody></table></div><h2>GPU memory</h2><p>Dedicated graphics cards commonly have <strong>VRAM</strong> for textures, frame data and graphics resources. Integrated graphics commonly use part of system RAM.</p><h2>Practical task</h2><ol><li>Identify your graphics processor.</li><li>Record whether it is integrated or dedicated.</li><li>Observe GPU usage during a graphics-heavy task.</li></ol><h2>Key takeaway</h2><p><strong>The GPU is a specialized parallel processor that accelerates graphics and suitable workloads.</strong></p></article>
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
<article class="lh-prose"><div class="lh-lesson-intro"><p>Graphic design becomes easier to discuss when you know the vocabulary. These terms describe the building blocks and decisions you will use in almost every design project.</p></div><h2>Essential terms</h2><div class="table-responsive"><table class="table table-bordered align-middle"><thead><tr><th>Term</th><th>Meaning</th><th>Practical use</th></tr></thead><tbody><tr><td>Composition</td><td>How visual elements are arranged as a whole.</td><td>Controls where the viewer looks.</td></tr><tr><td>Hierarchy</td><td>The visual order that tells viewers what to notice first, second and third.</td><td>Make a headline stronger than supporting text.</td></tr><tr><td>Alignment</td><td>Positioning elements along shared edges, centres or guides.</td><td>Keeps a layout organised.</td></tr><tr><td>Contrast</td><td>Visible difference between elements.</td><td>Separate a call-to-action from surrounding information.</td></tr><tr><td>Whitespace</td><td>Intentional empty space around or between elements.</td><td>Improves readability.</td></tr><tr><td>Typography</td><td>The design and arrangement of written text.</td><td>Controls readability and tone.</td></tr><tr><td>Grid</td><td>A system of guides used to organise content consistently.</td><td>Keeps columns and blocks aligned.</td></tr><tr><td>Palette</td><td>A selected set of colours used together.</td><td>Creates visual consistency.</td></tr></tbody></table></div><h2>Mini analysis task</h2><ol><li>Choose one poster, advertisement or social post.</li><li>Identify its strongest hierarchy decision.</li><li>Find alignment and contrast.</li><li>Notice its whitespace.</li><li>Write one change that would improve clarity.</li></ol><h2>Key takeaway</h2><p><strong>Design vocabulary gives you a precise way to diagnose and improve visual communication.</strong></p></article>
HTML;
    }

    if (($courseSlug === 'graphic-design-foundations' || $courseSlug === 'course-16') && $position === 3) {
        return <<<'HTML'
<article class="lh-prose">
  <div class="lh-lesson-intro"><p><strong>A design process turns a vague idea into a deliberate visual solution.</strong> Instead of opening a design app and decorating until something feels right, a designer moves through a sequence of decisions, tests them, and improves the result.</p></div>
  <h2>1. Define the problem</h2>
  <p>Start by identifying the communication goal. Ask: Who is the audience? What must they understand? What action should they take? Where will the design appear?</p>
  <div class="alert alert-primary"><strong>Example:</strong> “Make a poster” is a task. “Create a mobile-friendly event poster that makes students notice the event name, date and registration action in five seconds” is a design problem.</div>
  <h2>2. Research and collect references</h2>
  <p>Study relevant examples before designing. Look at competitors, similar campaigns, typography, colour approaches and layouts. The purpose is not to copy; it is to understand what conventions exist and where your solution can be clearer.</p>
  <h2>3. Generate ideas</h2>
  <p>Produce several rough directions instead of polishing the first idea. Thumbnail sketches are useful because they let you test hierarchy and composition quickly without becoming attached to details.</p>
  <h2>4. Build a first concept</h2>
  <p>Choose the strongest direction and create a simple version. Establish the headline, supporting information, imagery, spacing and call to action. Keep decorative decisions secondary to communication.</p>
  <h2>5. Test at the real viewing size</h2>
  <p>A design that looks excellent when zoomed in may fail on a phone, poster wall or presentation screen. View it at the size and distance your audience will actually use. Check whether the main message is immediately obvious.</p>
  <h2>6. Get feedback</h2>
  <p>Ask focused questions rather than “Do you like it?” For example: “What did you notice first?” or “What do you think this poster wants you to do?” If viewers consistently miss the intended message, the hierarchy needs work.</p>
  <h2>7. Refine and deliver</h2>
  <p>Make targeted changes, then check alignment, spacing, typography, colour consistency, image quality and export settings. Keep an editable source file and export the format required by the destination.</p>
  <h2>A simple workflow</h2>
  <div class="table-responsive"><table class="table table-bordered align-middle"><thead><tr><th>Stage</th><th>Main question</th><th>Useful output</th></tr></thead><tbody>
    <tr><td>Define</td><td>What problem are we solving?</td><td>Brief and success criteria</td></tr>
    <tr><td>Research</td><td>What context and conventions matter?</td><td>References and notes</td></tr>
    <tr><td>Ideate</td><td>What different solutions could work?</td><td>Thumbnail concepts</td></tr>
    <tr><td>Design</td><td>Which direction communicates best?</td><td>First concept</td></tr>
    <tr><td>Test</td><td>Does it work for the real viewer?</td><td>Observations</td></tr>
    <tr><td>Refine</td><td>What specific changes improve it?</td><td>Final design</td></tr>
  </tbody></table></div>
  <h2>Practice project</h2>
  <p>Design a fictional “Design Workshop” social post. First write a three-sentence brief defining the audience, goal and viewing context. Then sketch three different layouts, choose one, create a rough digital version, and ask one person what they noticed first. Record the feedback and make one purposeful revision.</p>
  <h2>Common mistakes</h2>
  <ul><li>Starting with decoration before defining the communication goal.</li><li>Polishing one idea before exploring alternatives.</li><li>Asking for vague feedback instead of testing specific communication outcomes.</li><li>Judging a design only at desktop zoom instead of its real viewing size.</li><li>Changing many variables at once, making it hard to learn what improved the design.</li></ul>
  <h2>Quick check</h2>
  <ol><li>Why should a designer define the audience before choosing visual style?</li><li>What is the benefit of making several thumbnail concepts?</li><li>What question can reveal whether a viewer understood the intended hierarchy?</li></ol>
  <h2>Key takeaway</h2>
  <p><strong>Good design is not a single moment of inspiration; it is a repeatable process of defining, exploring, testing and refining a visual solution.</strong></p>
</article>
HTML;
    }

    return null;
}

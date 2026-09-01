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
  <div class="lh-lesson-intro"><p><strong>RAM and storage are not the same thing.</strong> RAM is fast working memory used while programs are running. Storage, such as an SSD or HDD, keeps files and programs even after the computer is turned off.</p></div>
  <h2>RAM: your computer's working space</h2><p>When you open a browser, document or game, the operating system loads the information the program needs into RAM. The CPU can access active data from RAM much more quickly than it can from permanent storage.</p>
  <p>RAM is <strong>volatile memory</strong>: its contents are normally lost when power is removed. That is why unsaved work can disappear after a sudden shutdown.</p>
  <h2>Storage: keeping data for the long term</h2><p>An SSD or HDD stores the operating system, applications, photos, videos and documents. Storage is <strong>non-volatile</strong>, so its data remains available after the computer is turned off.</p>
  <h2>RAM vs storage</h2><div class="table-responsive"><table class="table table-bordered align-middle"><thead><tr><th>Feature</th><th>RAM</th><th>Storage</th></tr></thead><tbody><tr><td>Main purpose</td><td>Active working data</td><td>Long-term files and programs</td></tr><tr><td>Power off</td><td>Data is normally lost</td><td>Data remains saved</td></tr><tr><td>Typical technology</td><td>DRAM</td><td>SSD / HDD</td></tr><tr><td>Example</td><td>Keeping a browser and spreadsheet open</td><td>Saving the spreadsheet file</td></tr></tbody></table></div>
  <h2>A simple real-world example</h2><p>Suppose you open a 200 MB project from an SSD. The file remains on the SSD, but the application loads the working parts into RAM. As you edit the project, active data is held in RAM while the application writes saved changes back to storage.</p>
  <div class="alert alert-primary"><strong>Smart tip:</strong> Think of RAM as the desk where you work and storage as the cabinet where you keep your files.</div>
  <h2>Why more RAM can help</h2><p>When available RAM becomes limited, an operating system may move some less-active data to storage. Storage is slower than RAM, so heavy memory pressure can make multitasking feel less responsive.</p>
  <h2>Practical task</h2><ol><li>Open your system monitor or Task Manager.</li><li>Look at current memory usage.</li><li>Open two or three normal applications.</li><li>Observe how memory usage changes.</li><li>Record whether the computer remains responsive.</li></ol>
  <h2>Common mistakes</h2><ul><li>Calling an SSD “memory” in the same sense as RAM.</li><li>Assuming a larger storage drive makes every program faster.</li><li>Buying more RAM without checking what the actual slowdown is.</li></ul>
  <h2>Quick check</h2><p>What happens to RAM when a computer is powered off? Why can adding an SSD increase available storage without increasing working memory?</p>
  <h2>Key takeaway</h2><p><strong>RAM holds the data you are actively working with; storage keeps your data and programs for the long term.</strong></p>
</article>
HTML;
    }

    if ($courseSlug === 'computer-basics-for-beginners' && $position === 5) {
        return <<<'HTML'
<article class="lh-prose">
  <div class="lh-lesson-intro"><p><strong>Hard drives (HDDs) and solid-state drives (SSDs)</strong> both provide permanent storage, but they use very different technologies.</p></div>
  <h2>HDD: magnetic storage with moving parts</h2><p>A hard disk drive stores data magnetically on spinning platters. A mechanical actuator moves read/write heads over the platter surface to access data.</p>
  <h2>SSD: flash storage with no moving parts</h2><p>A solid-state drive stores data in NAND flash memory. It has no spinning platter or moving read/write head.</p>
  <h2>HDD vs SSD</h2><div class="table-responsive"><table class="table table-bordered align-middle"><thead><tr><th>Feature</th><th>HDD</th><th>SSD</th></tr></thead><tbody><tr><td>Technology</td><td>Magnetic platters</td><td>NAND flash</td></tr><tr><td>Moving parts</td><td>Yes</td><td>No</td></tr><tr><td>Typical access latency</td><td>Higher</td><td>Much lower</td></tr><tr><td>Common strength</td><td>Large capacity at relatively low cost</td><td>Fast everyday responsiveness</td></tr></tbody></table></div>
  <h2>Why an SSD can feel faster</h2><p>Replacing a mechanical system drive with an SSD can reduce storage access latency dramatically. This is noticeable during booting, launching applications and loading many small files. The SSD does not make the CPU itself faster; it reduces waiting for storage operations.</p>
  <div class="alert alert-primary"><strong>Smart tip:</strong> Capacity and speed are different specifications. A larger drive does not automatically perform better.</div>
  <h2>Practical task</h2><ol><li>Identify whether your system drive is an HDD or SSD.</li><li>Record its capacity and free space.</li><li>Observe disk activity while opening an application.</li><li>Decide whether storage appears to be a bottleneck.</li></ol>
  <h2>Backups matter</h2><p>A healthy drive can still fail. Important files should have a separate backup. HDDs can show mechanical warning signs, while SSDs can fail without obvious mechanical symptoms.</p>
  <h2>Quick check</h2><ol><li>What physical difference separates an HDD from an SSD?</li><li>Why can an SSD improve application launch times?</li><li>Why are backups important even when a drive appears healthy?</li></ol>
  <h2>Key takeaway</h2><p><strong>HDDs use magnetic spinning media, while SSDs use flash memory.</strong> SSDs usually provide much lower access latency, while HDDs remain useful when large capacity at low cost is the priority.</p>
</article>
HTML;
    }

    /* Course 16 = Graphic Design Foundations in the master curriculum. */
    if (($courseSlug === 'graphic-design-foundations' || $courseSlug === 'course-16') && $position === 1) {
        return <<<'HTML'
<article class="lh-prose">
  <div class="lh-lesson-intro">
    <p><strong>Graphic design is visual problem-solving.</strong> A good design does more than look attractive: it helps a specific audience understand, notice or act on information. This first lesson builds the foundation you will use throughout the course.</p>
  </div>

  <h2>What graphic design actually does</h2>
  <p>Graphic design combines text, images, shapes, spacing, colour and hierarchy to communicate a message. A poster might need to make an event date obvious. A social-media graphic might need to stop scrolling and make one idea easy to understand. A product banner might need to guide attention toward an offer.</p>
  <p>The design process therefore starts with the <strong>communication goal</strong>, not with decoration.</p>

  <h2>1. Start with the audience and goal</h2>
  <p>Before choosing fonts or colours, answer three questions:</p>
  <ol>
    <li><strong>Who</strong> is going to see this?</li>
    <li><strong>What</strong> should they understand or notice first?</li>
    <li><strong>What action</strong>, if any, should they take next?</li>
  </ol>
  <p>For example, a restaurant promotion aimed at mobile users needs a different information hierarchy from a printed menu. The content may be similar, but the viewing situation changes the design decisions.</p>

  <h2>2. The core building blocks</h2>
  <div class="table-responsive">
    <table class="table table-bordered align-middle">
      <thead><tr><th>Element</th><th>Purpose</th><th>Beginner question</th></tr></thead>
      <tbody>
        <tr><td>Typography</td><td>Communicates words and tone</td><td>Can the text be read quickly?</td></tr>
        <tr><td>Colour</td><td>Creates emphasis, mood and relationships</td><td>Does the contrast support readability?</td></tr>
        <tr><td>Images</td><td>Shows, explains or creates emotion</td><td>Does the image support the message?</td></tr>
        <tr><td>Layout</td><td>Organises information in space</td><td>Where does the eye go first?</td></tr>
        <tr><td>Shape</td><td>Groups, separates or highlights content</td><td>Does it clarify the structure?</td></tr>
        <tr><td>Whitespace</td><td>Creates breathing room and separation</td><td>Is anything unnecessarily crowded?</td></tr>
      </tbody>
    </table>
  </div>

  <h2>3. Visual hierarchy</h2>
  <p><strong>Hierarchy</strong> tells the viewer what to notice first, second and third. Size, weight, colour, position and spacing can all create hierarchy. If every headline, button and image has the same visual emphasis, the viewer has to work harder to understand the page.</p>
  <div class="alert alert-primary"><strong>Smart tip:</strong> A useful test is the three-second test. Look at your design briefly, then ask: “What did I notice first?” If the answer is not the intended message, adjust the hierarchy.</div>

  <h2>4. Alignment and consistency</h2>
  <p>Alignment creates invisible lines that make a composition feel organised. Consistency means related elements use related treatments—for example, headings share a typographic style and repeated cards use the same spacing. These choices reduce visual noise and make a design easier to scan.</p>

  <h2>5. Contrast is not decoration</h2>
  <p>Contrast helps separate information. It can come from light versus dark, large versus small, bold versus regular, or one shape against another. Strong contrast should have a purpose. Poor contrast can make text difficult to read, especially on phones or for people with visual impairments.</p>

  <h2>Practical project</h2>
  <p>Create a simple 1080 × 1080 social-media announcement for an imaginary workshop. Include a title, date, one short description and a clear call to action.</p>
  <ol>
    <li>Write the communication goal in one sentence.</li>
    <li>Choose one primary message and make it visually dominant.</li>
    <li>Use no more than two font families.</li>
    <li>Create a clear spacing system instead of placing every element independently.</li>
    <li>Check the design at a small phone-sized view.</li>
    <li>Remove any element that does not help the message.</li>
  </ol>

  <h2>Self-review checklist</h2>
  <ul>
    <li>The intended audience is clear.</li>
    <li>The most important message is obvious within a few seconds.</li>
    <li>Text is readable at the actual viewing size.</li>
    <li>Elements align consistently.</li>
    <li>Whitespace separates related and unrelated information.</li>
    <li>Colour and imagery support the message rather than compete with it.</li>
    <li>The design still makes sense if decorative elements are removed.</li>
  </ul>

  <h2>Common beginner mistakes</h2>
  <ul>
    <li>Starting with effects instead of the communication goal.</li>
    <li>Using too many fonts, colours or decorative shapes.</li>
    <li>Making every element equally large or bold.</li>
    <li>Ignoring alignment and spacing.</li>
    <li>Designing only at full zoom and never checking the real viewing size.</li>
  </ul>

  <h2>Quick self-check</h2>
  <ol>
    <li>Why should the communication goal come before decoration?</li>
    <li>Name three ways to create visual hierarchy.</li>
    <li>Why does whitespace improve a design?</li>
    <li>What should you test before publishing a mobile graphic?</li>
  </ol>

  <h2>Key takeaway</h2>
  <p><strong>Good graphic design makes the intended message easier to see, understand and remember.</strong> Learn to control hierarchy, alignment, contrast, typography, colour, imagery and whitespace before relying on visual effects.</p>
</article>
HTML;
    }

    return null;
}

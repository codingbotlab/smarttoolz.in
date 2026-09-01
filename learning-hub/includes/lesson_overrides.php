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
  <ol>
    <li>Open your system monitor or Task Manager.</li>
    <li>Look at current memory usage.</li>
    <li>Open two or three normal applications.</li>
    <li>Observe how memory usage changes.</li>
    <li>Record whether the computer remains responsive.</li>
  </ol>

  <h2>Common mistakes</h2>
  <ul>
    <li>Calling an SSD “memory” in the same sense as RAM.</li>
    <li>Assuming a larger storage drive makes every program faster.</li>
    <li>Thinking unused RAM is automatically wasted RAM.</li>
    <li>Buying more RAM without checking what the actual slowdown is.</li>
  </ul>

  <h2>Quick check</h2>
  <p>What happens to RAM when a computer is powered off? Why can adding an SSD increase available storage without increasing working memory?</p>

  <h2>Key takeaway</h2>
  <p><strong>RAM holds the data you are actively working with; storage keeps your data and programs for the long term.</strong> Knowing the difference helps you choose upgrades intelligently and troubleshoot slow computers.</p>
</article>
HTML;
    }

    return null;
}

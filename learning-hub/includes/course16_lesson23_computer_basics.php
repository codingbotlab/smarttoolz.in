<?php
declare(strict_types=1);

/**
 * Course 16 — Computer Basics Mastery
 * Lesson 23 — Safe Web Searching
 */
function lh_course16_lesson23_computer_basics_override(array $lesson, int $position): ?string
{
    if ((string)($lesson['slug'] ?? '') !== 'course-16-lesson-23') {
        return null;
    }

    return <<<'HTML'
<section class="lh-topic">
  <h2>🔎 What Is Web Searching?</h2>
  <p><strong>Web searching</strong> means using a search engine to find information, websites, images, documents, services, and answers on the Web. A search engine helps you discover relevant pages, but the search results themselves are not automatically proof that every result is accurate or trustworthy.</p>
  <div class="lh-callout"><strong>Most important idea:</strong> a search engine helps you <strong>find</strong> information. You still need to <strong>check</strong> the source, date, context, and reliability before trusting important information.</div>
</section>

<section class="lh-topic">
  <h2>🌐 Browser vs Search Engine</h2>
  <table class="table table-bordered align-middle">
    <thead><tr><th>Tool</th><th>Purpose</th></tr></thead>
    <tbody>
      <tr><td><strong>Web Browser</strong></td><td>Software used to open and interact with websites and web content.</td></tr>
      <tr><td><strong>Search Engine</strong></td><td>A web service that helps you find relevant pages and other online resources.</td></tr>
      <tr><td><strong>Website</strong></td><td>A collection of web pages and related resources hosted online.</td></tr>
    </tbody>
  </table>
  <p>You can normally type a search query directly into a browser's address bar, but the browser and search engine are still different things.</p>
</section>

<section class="lh-topic">
  <h2>✍️ Write Better Search Queries</h2>
  <p>Good searches are usually <strong>specific enough to describe what you need</strong> without adding unnecessary words.</p>
  <ul>
    <li>Instead of <code>printer</code>, try <code>Windows 11 printer not detected</code>.</li>
    <li>Instead of <code>SSD</code>, try <code>SSD vs HDD differences for everyday PC use</code>.</li>
    <li>For a known website, include its name, such as <code>Microsoft Windows 11 Wi-Fi troubleshooting</code>.</li>
    <li>Add important details such as the product, operating system, error message, location, or year when relevant.</li>
  </ul>
  <div class="lh-callout"><strong>Tip:</strong> if the first search is too broad, add one or two useful keywords instead of typing a very long sentence.</div>
</section>

<section class="lh-topic">
  <h2>🎯 Search for the Exact Problem</h2>
  <p>When troubleshooting a computer, search using the <strong>exact error message</strong> when possible. Error text is often more useful than a vague description.</p>
  <ol>
    <li>Read the error carefully.</li>
    <li>Copy the important wording or type the exact phrase.</li>
    <li>Add the device or software name.</li>
    <li>Add the operating system if it matters.</li>
    <li>Compare results from more than one reliable source.</li>
  </ol>
  <p>For example, <code>Windows 11 "Bluetooth device not recognized"</code> is generally more useful than simply searching for <code>Bluetooth problem</code>.</p>
</section>

<section class="lh-topic">
  <h2>🧠 How to Judge a Search Result</h2>
  <table class="table table-bordered align-middle">
    <thead><tr><th>Check</th><th>Question to ask</th></tr></thead>
    <tbody>
      <tr><td><strong>Source</strong></td><td>Who published this information? Is it an official organization, manufacturer, university, established publication, or unknown site?</td></tr>
      <tr><td><strong>Date</strong></td><td>Is the information recent enough for this topic?</td></tr>
      <tr><td><strong>Evidence</strong></td><td>Does the page explain how it reached its conclusion or provide supporting references?</td></tr>
      <tr><td><strong>Purpose</strong></td><td>Is the page trying to inform you, sell something, collect information, or get you to click?</td></tr>
      <tr><td><strong>Agreement</strong></td><td>Do other trustworthy sources report the same thing?</td></tr>
    </tbody>
  </table>
</section>

<section class="lh-topic">
  <h2>🏛️ Prefer Primary and Trusted Sources</h2>
  <p>For technical questions, start with the organization that actually makes or maintains the product or technology when possible.</p>
  <ul>
    <li>For Windows problems, check official Microsoft documentation.</li>
    <li>For a printer or laptop problem, check the manufacturer's official support pages.</li>
    <li>For programming and Web standards, prefer authoritative documentation and established technical references.</li>
    <li>For important facts, compare multiple reputable sources rather than relying on one random result.</li>
  </ul>
  <div class="lh-callout"><strong>Rule of thumb:</strong> the higher the cost or risk of being wrong, the more carefully you should verify the information.</div>
</section>

<section class="lh-topic">
  <h2>⚠️ Ads, Sponsored Results, and Clickbait</h2>
  <p>Search pages can contain advertisements or sponsored results alongside ordinary results. A prominent result is not necessarily the best or most trustworthy result.</p>
  <ul>
    <li>Look for labels indicating advertisements or sponsored content.</li>
    <li>Do not assume the first result is automatically the correct answer.</li>
    <li>Be suspicious of sensational headlines, impossible promises, and urgent claims.</li>
    <li>Avoid downloading software simply because a search result says your computer is infected or outdated.</li>
    <li>Do not give passwords, payment information, or verification codes to a page just because it appeared in search results.</li>
  </ul>
</section>

<section class="lh-topic">
  <h2>🛡️ Avoid Fake Support and Scam Results</h2>
  <p>Some pages are designed to imitate support websites or pressure users into calling a phone number, installing remote-control software, or paying for unnecessary services.</p>
  <ol>
    <li>Do not call an unknown support number shown by a random pop-up or suspicious search result.</li>
    <li>Open the official company website yourself when possible and navigate to its support section.</li>
    <li>Never share passwords, one-time codes, or remote-access permissions with an unverified person.</li>
    <li>If a page says your computer has an emergency infection, stop and verify the claim through a trusted source.</li>
  </ol>
</section>

<section class="lh-topic">
  <h2>🧩 Useful Search Techniques</h2>
  <ul>
    <li><strong>Quotes:</strong> search an exact phrase, such as <code>"device not recognized"</code>.</li>
    <li><strong>Specific terms:</strong> add the product, model, operating system, or error code.</li>
    <li><strong>Site restriction:</strong> when supported by your search engine, use <code>site:example.com</code> to focus on a particular domain.</li>
    <li><strong>File type:</strong> when supported, use terms such as <code>filetype:pdf</code> to find documents in a particular format.</li>
    <li><strong>Alternative wording:</strong> if results are poor, replace one keyword with a common synonym.</li>
  </ul>
  <p>Search operators can be powerful, but you do not need complicated syntax for everyday searches. Start simple and make the query more specific only when needed.</p>
</section>

<section class="lh-topic">
  <h2>🔄 Verify Before You Act</h2>
  <p>Finding instructions is only half the job. Before changing a computer setting, installing software, deleting data, or spending money, verify that the instructions apply to your exact situation.</p>
  <ul>
    <li>Check the device model and operating system.</li>
    <li>Read the full instructions instead of following a single line from a search snippet.</li>
    <li>Prefer official documentation for drivers, firmware, software downloads, and account recovery.</li>
    <li>Back up important data before risky changes.</li>
    <li>If two sources disagree, investigate why instead of choosing the answer that simply sounds better.</li>
  </ul>
</section>

<section class="lh-topic">
  <h2>🎯 Practical Activity</h2>
  <p>Practice safe searching with a harmless computer topic:</p>
  <ol>
    <li>Search for <code>Windows 11 show file extensions</code>.</li>
    <li>Find the official Microsoft result.</li>
    <li>Open the page and check who published it.</li>
    <li>Compare it with one other reputable source.</li>
    <li>Now search for a computer error using the exact wording of the error message.</li>
    <li>Identify which results look official, which look commercial, and which look suspicious.</li>
  </ol>
  <div class="lh-callout"><strong>Goal:</strong> learn to separate <strong>searching</strong> from <strong>trusting</strong>. A good search finds candidates; verification decides what you should rely on.</div>
</section>

<section class="lh-topic">
  <h2>❌ Common Mistakes</h2>
  <ul>
    <li>Trusting the first search result without checking the source.</li>
    <li>Confusing sponsored results with independent recommendations.</li>
    <li>Using outdated instructions for a newer version of Windows or another application.</li>
    <li>Downloading drivers or utilities from unknown websites.</li>
    <li>Following a troubleshooting command without understanding what it changes.</li>
    <li>Sharing personal information because a search result asks for it.</li>
    <li>Believing a scary pop-up or headline without independent verification.</li>
  </ul>
</section>

<section class="lh-topic">
  <h2>🧠 Quick Self-Check</h2>
  <ol>
    <li>What is the difference between a search engine and a web browser?</li>
    <li>Why can an exact error message be useful in a search?</li>
    <li>Name two things you should check before trusting a search result.</li>
    <li>Why should technical downloads usually come from official sources?</li>
    <li>Does appearing first in search results prove that a page is trustworthy?</li>
    <li>What should you do if a page claims your computer has an emergency problem and asks you to call an unknown number?</li>
  </ol>
  <details class="mt-3"><summary><strong>Show answers</strong></summary>
    <div class="lh-callout mt-3">
      <ol>
        <li>A browser is software used to access Web content; a search engine helps find relevant online resources.</li>
        <li>Exact wording can reduce unrelated results and match documentation for the same problem.</li>
        <li>Check the source, date, purpose, evidence, and agreement with other reliable sources.</li>
        <li>Official sources reduce the risk of modified, outdated, bundled, or malicious downloads.</li>
        <li>No. Search ranking does not by itself prove accuracy or trustworthiness.</li>
        <li>Do not call or provide sensitive information; verify the problem through a trusted official source.</li>
      </ol>
    </div>
  </details>
</section>

<section class="lh-topic">
  <h2>✅ Key Takeaway</h2>
  <p>Safe web searching is not just about finding an answer quickly. It is about asking a clear question, choosing useful results, checking the source and date, comparing important claims, and verifying information before you act on it.</p>
</section>
HTML;
}

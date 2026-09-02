<?php
declare(strict_types=1);

/**
 * Course 16 — Computer Basics Mastery
 * Lesson 25 — Downloading Files Safely
 */
function lh_course16_lesson25_computer_basics_override(array $lesson, int $position): ?string
{
    if ((string)($lesson['slug'] ?? '') !== 'course-16-lesson-25') {
        return null;
    }

    return <<<'HTML'
<section class="lh-topic">
  <h2>📥 What Is a Download?</h2>
  <p>A <strong>download</strong> is the process of copying a file or other data from an online service to your device. You might download a PDF, photo, document, ZIP archive, music file, installer, or another type of file.</p>
  <div class="lh-callout"><strong>Important:</strong> downloading a file does not automatically mean the file is safe. Safety depends on where it came from, what it contains, and what you do with it afterward.</div>
</section>

<section class="lh-topic">
  <h2>🌐 Where Do Downloads Come From?</h2>
  <table class="table table-bordered align-middle">
    <thead><tr><th>Source</th><th>Typical risk</th><th>Best practice</th></tr></thead>
    <tbody>
      <tr><td><strong>Official website</strong></td><td>Usually lower risk, but still verify the domain.</td><td>Prefer the publisher's real website.</td></tr>
      <tr><td><strong>Microsoft Store / trusted app store</strong></td><td>Generally safer because apps are subject to store controls.</td><td>Use the official store when suitable.</td></tr>
      <tr><td><strong>Email or message attachment</strong></td><td>May be malicious or unexpected.</td><td>Verify the sender and file before opening.</td></tr>
      <tr><td><strong>Random download site</strong></td><td>May bundle unwanted or malicious software.</td><td>Look for an official source instead.</td></tr>
      <tr><td><strong>Pirated/cracked software site</strong></td><td>High risk of malware, modified installers, or scams.</td><td>Avoid it and use legitimate sources.</td></tr>
    </tbody>
  </table>
  <p>Microsoft recommends downloading and installing programs only from trusted publishers and retail websites.</p>
</section>

<section class="lh-topic">
  <h2>🔎 Check the Website Before Downloading</h2>
  <p>Search results can lead to advertisements, copied pages, fake support sites, or look-alike domains. Before downloading software, check that you are on the publisher's genuine website.</p>
  <ul>
    <li>Read the domain name carefully.</li>
    <li>Watch for misspellings or extra words designed to imitate a trusted company.</li>
    <li>Prefer links from the official product or organization website.</li>
    <li>Do not trust a page merely because it has a professional-looking design.</li>
    <li>Be cautious when a download page uses fake warnings, countdowns, or multiple misleading Download buttons.</li>
  </ul>
  <div class="lh-callout"><strong>Good habit:</strong> when you need an application, search for the official publisher first instead of searching only for a generic “free download.”</div>
</section>

<section class="lh-topic">
  <h2>⬇️ Downloading a Normal File</h2>
  <ol>
    <li>Open a trusted website or service.</li>
    <li>Find the exact file you need.</li>
    <li>Check the file name and expected file type.</li>
    <li>Select the legitimate Download button or link.</li>
    <li>Choose <strong>Save</strong> or <strong>Save as</strong> when your browser asks.</li>
    <li>Wait for the download to finish.</li>
    <li>Open your browser's Downloads list or File Explorer to locate the file.</li>
  </ol>
  <p>On Windows, files downloaded through a browser are commonly saved in the <strong>Downloads</strong> folder unless you choose another location.</p>
</section>

<section class="lh-topic">
  <h2>📄 Know the File Type</h2>
  <p>The file extension gives you an important clue about what a file is intended to be.</p>
  <table class="table table-bordered align-middle">
    <thead><tr><th>Extension</th><th>Common purpose</th></tr></thead>
    <tbody>
      <tr><td><code>.pdf</code></td><td>Portable document</td></tr>
      <tr><td><code>.jpg</code> / <code>.png</code></td><td>Image</td></tr>
      <tr><td><code>.docx</code></td><td>Word document</td></tr>
      <tr><td><code>.xlsx</code></td><td>Spreadsheet</td></tr>
      <tr><td><code>.zip</code></td><td>Compressed archive</td></tr>
      <tr><td><code>.exe</code> / <code>.msi</code></td><td>Windows program installer or executable</td></tr>
    </tbody>
  </table>
  <div class="lh-callout"><strong>Red flag:</strong> if you expected a PDF but the downloaded file is an executable such as <code>.exe</code>, stop and verify the download before opening it.</div>
</section>

<section class="lh-topic">
  <h2>🛡️ Browser and Windows Security Warnings</h2>
  <p>Modern browsers and Windows can warn you when a downloaded file looks suspicious. Windows can keep security information about files downloaded from the Internet and may show a warning before opening them.</p>
  <ul>
    <li>Read the warning instead of automatically clicking through it.</li>
    <li>If you do not recognize the file or source, cancel the download or delete the file.</li>
    <li>Do not turn off SmartScreen or other security protections just because a download is blocked.</li>
    <li>If a trusted file is unexpectedly flagged, verify its source and obtain it again from the official website.</li>
  </ul>
</section>

<section class="lh-topic">
  <h2>🧪 Scan Before You Open</h2>
  <p>For a downloaded file that you have a legitimate reason to use, especially an installer or archive, make sure your security software can inspect it before you open it.</p>
  <ol>
    <li>Confirm the source is trusted.</li>
    <li>Confirm the file type is what you expected.</li>
    <li>Check the file name and size for anything unusual.</li>
    <li>Let Windows Security or your antivirus protection scan the file when available.</li>
    <li>Do not ignore a serious malware warning just because you want the file.</li>
  </ol>
  <p>Microsoft Defender Antivirus is built into modern Windows and can help protect against malware and potentially unwanted applications.</p>
</section>

<section class="lh-topic">
  <h2>📦 ZIP Files and Compressed Downloads</h2>
  <p>A <strong>ZIP</strong> file can contain many files in a smaller package. ZIP is useful, but it does not make the contents automatically safe.</p>
  <ul>
    <li>Download ZIP files only from sources you trust.</li>
    <li>Look inside the archive before opening files from it.</li>
    <li>Be especially careful if a ZIP contains an unexpected executable or script.</li>
    <li>Password-protected archives deserve extra caution because security tools may have less visibility into their contents before extraction.</li>
  </ul>
</section>

<section class="lh-topic">
  <h2>⚠️ Fake Download Buttons</h2>
  <p>Some websites place advertisements or fake buttons next to the real download link. A large button is not necessarily the correct one.</p>
  <ul>
    <li>Read the surrounding text before clicking.</li>
    <li>Move the pointer over a link when appropriate to inspect its destination.</li>
    <li>Be suspicious of “Your PC is infected,” “Update now,” or similar messages that appear on unrelated websites.</li>
    <li>Do not install a “cleaner,” “driver updater,” or remote-support tool just because a pop-up tells you to.</li>
  </ul>
  <p>Be especially cautious with third-party software downloads and unexpected tech-support messages.</p>
</section>

<section class="lh-topic">
  <h2>💻 Downloading Software Safely</h2>
  <p>Software deserves extra caution because an installer can make changes to your computer.</p>
  <ol>
    <li>Identify the software publisher.</li>
    <li>Go to the publisher's official website or a trusted app store.</li>
    <li>Check that the software supports your Windows version and device.</li>
    <li>Download the installer from the legitimate source.</li>
    <li>Read the installer screens instead of clicking Next repeatedly.</li>
    <li>Decline optional bundled software you do not need.</li>
    <li>Pay attention to permissions and administrator prompts.</li>
  </ol>
  <div class="lh-callout"><strong>Remember:</strong> “free” does not mean “safe.” A free program can still contain unwanted software, advertising, tracking, or malware.</div>
</section>

<section class="lh-topic">
  <h2>🗑️ What to Do With a Suspicious Download</h2>
  <p>If you downloaded something and then realized the source was suspicious, <strong>do not open it</strong>.</p>
  <ol>
    <li>Close the suspicious webpage.</li>
    <li>Do not run the file or approve an administrator prompt.</li>
    <li>Delete the suspicious file.</li>
    <li>Empty the Recycle Bin if appropriate.</li>
    <li>Run a security scan if you are concerned that the file was opened or executed.</li>
    <li>Change important passwords if you believe credentials may have been exposed.</li>
  </ol>
</section>

<section class="lh-topic">
  <h2>🐢 Troubleshooting a Failed Download</h2>
  <table class="table table-bordered align-middle">
    <thead><tr><th>Problem</th><th>Things to check</th></tr></thead>
    <tbody>
      <tr><td>Download stops</td><td>Check Internet connection, available storage, and browser/network errors.</td></tr>
      <tr><td>File cannot be found</td><td>Open the browser Downloads list and check the configured download folder.</td></tr>
      <tr><td>File is incomplete</td><td>Delete the partial file and download again from the trusted source.</td></tr>
      <tr><td>Browser blocks the file</td><td>Read the warning and verify the source before deciding what to do.</td></tr>
      <tr><td>Not enough disk space</td><td>Free space or choose a suitable storage location.</td></tr>
      <tr><td>Installer will not run</td><td>Verify the file is complete, compatible, and from the official source.</td></tr>
    </tbody>
  </table>
</section>

<section class="lh-topic">
  <h2>🧭 A Safe Download Checklist</h2>
  <ol>
    <li><strong>Source:</strong> Do I trust the website or sender?</li>
    <li><strong>Domain:</strong> Am I on the real publisher's website?</li>
    <li><strong>File:</strong> Is this the file type I expected?</li>
    <li><strong>Purpose:</strong> Do I actually need this download?</li>
    <li><strong>Warning:</strong> Is my browser or Windows warning me?</li>
    <li><strong>Security:</strong> Has the file been checked by my security software?</li>
    <li><strong>Installer:</strong> If it is software, did I read the installation options?</li>
  </ol>
</section>

<section class="lh-topic">
  <h2>🎯 Practical Activity</h2>
  <p>Practice with a harmless document rather than an unknown program:</p>
  <ol>
    <li>Open a trusted website that provides a public PDF.</li>
    <li>Check the domain before downloading.</li>
    <li>Download the PDF.</li>
    <li>Open your browser's Downloads list.</li>
    <li>Find the file in File Explorer.</li>
    <li>Check its extension and Properties.</li>
    <li>Compare the expected file type with the actual file type.</li>
    <li>Delete the test file when finished if you no longer need it.</li>
  </ol>
  <div class="lh-callout"><strong>Goal:</strong> build the habit of checking the <strong>source → file type → warning → purpose</strong> before opening a download.</div>
</section>

<section class="lh-topic">
  <h2>❌ Common Mistakes</h2>
  <ul>
    <li>Downloading software from the first search result without checking the publisher.</li>
    <li>Clicking a fake Download button that is actually an advertisement.</li>
    <li>Ignoring browser or Windows security warnings.</li>
    <li>Opening an unexpected <code>.exe</code>, script, or archive.</li>
    <li>Disabling security protection to force a blocked download to run.</li>
    <li>Installing optional bundled software without reading the installer.</li>
    <li>Using pirated or cracked software because it appears free.</li>
  </ul>
</section>

<section class="lh-topic">
  <h2>🧠 Quick Self-Check</h2>
  <ol>
    <li>What is a download?</li>
    <li>Why is an official publisher website usually preferable for software?</li>
    <li>Why should you check a file extension before opening a download?</li>
    <li>What should you do when Windows or your browser warns that a download may be unsafe?</li>
    <li>Does a ZIP file automatically make its contents safe?</li>
    <li>Name two signs of a suspicious download page.</li>
    <li>What should you do with a suspicious file that you have not opened?</li>
  </ol>
  <details class="mt-3"><summary><strong>Show answers</strong></summary>
    <div class="lh-callout mt-3">
      <ol>
        <li>A download copies a file or data from an online source to your device.</li>
        <li>It makes it easier to verify that the software came from the actual publisher rather than a modified third-party package.</li>
        <li>The extension helps you confirm whether the file is the type you expected and can reveal unexpected executables.</li>
        <li>Read the warning, verify the source, and do not bypass protection blindly.</li>
        <li>No. A ZIP archive can contain unsafe files.</li>
        <li>Examples include fake Download buttons, urgent infection warnings, suspicious domains, excessive pop-ups, or unexpected installers.</li>
        <li>Do not open it; delete it and run a security scan if necessary.</li>
      </ol>
    </div>
  </details>
</section>

<section class="lh-topic">
  <h2>✅ Key Takeaway</h2>
  <p>Safe downloading is a simple habit: <strong>trust the source, verify the website, check the file type, pay attention to security warnings, scan when appropriate, and never open something just because a webpage tells you to.</strong></p>
</section>
HTML;
}

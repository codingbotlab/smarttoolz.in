<?php
declare(strict_types=1);

/**
 * Course 16 — Computer Basics Mastery
 * Lesson 18 — Printers and Scanners
 */
function lh_course16_lesson18_computer_basics_override(array $lesson, int $position): ?string
{
    if ((string)($lesson['slug'] ?? '') !== 'course-16-lesson-18') {
        return null;
    }

    return <<<'HTML'
<section class="lh-section">
    <h2>🖨️ Printers and Scanners: What They Do</h2>
    <p>A <strong>printer</strong> converts digital information into a physical copy on paper. A <strong>scanner</strong> does the opposite: it captures a physical document or photo and turns it into a digital file.</p>
    <div class="lh-grid">
        <div class="lh-card"><h3>Printer</h3><p><strong>Digital → Physical</strong></p><p>Creates paper copies of documents, photos and other files.</p></div>
        <div class="lh-card"><h3>Scanner</h3><p><strong>Physical → Digital</strong></p><p>Creates digital copies of paper documents and photos.</p></div>
        <div class="lh-card"><h3>All-in-One</h3><p><strong>Print + Scan + Copy</strong></p><p>Combines several functions in one device.</p></div>
    </div>
</section>

<section class="lh-section">
    <h2>1. How Printers Work</h2>
    <p>When you click <strong>Print</strong>, Windows sends the document to the selected printer through its printing system and the appropriate driver. The printer processes the job and places ink or toner onto paper.</p>
    <h3>Common printer types</h3>
    <table class="table table-bordered align-middle">
        <thead><tr><th>Type</th><th>Good for</th><th>How it works</th></tr></thead>
        <tbody>
            <tr><td><strong>Inkjet</strong></td><td>Photos, colour documents, home use</td><td>Uses liquid ink.</td></tr>
            <tr><td><strong>Laser</strong></td><td>Frequent text printing and offices</td><td>Uses toner and laser-based imaging.</td></tr>
            <tr><td><strong>Thermal</strong></td><td>Receipts and labels</td><td>Uses heat-sensitive media or thermal transfer.</td></tr>
        </tbody>
    </table>
    <div class="lh-callout"><strong>Remember:</strong> Purchase price is only one part of printer cost. Ink/toner, page yield, paper and maintenance also matter.</div>
</section>

<section class="lh-section">
    <h2>2. Connecting a Printer</h2>
    <ul>
        <li><strong>USB:</strong> direct wired connection to the computer.</li>
        <li><strong>Wi-Fi:</strong> the printer connects to a wireless network.</li>
        <li><strong>Ethernet:</strong> wired network connection, common in offices.</li>
        <li><strong>Bluetooth:</strong> available on some compact or specialised printers.</li>
    </ul>
    <p>For wireless printing, make sure the computer and printer can communicate through the intended network. If the printer is not discovered, check power, network status and the network name shown on the printer.</p>
</section>

<section class="lh-section">
    <h2>3. Adding a Printer in Windows</h2>
    <p>Windows can automatically discover many modern printers. In Windows 11, a common path is <strong>Settings → Bluetooth &amp; devices → Printers &amp; scanners → Add device</strong>.</p>
    <ol>
        <li>Turn on the printer and check that paper is loaded.</li>
        <li>Connect the USB cable, or connect the printer to the intended Wi-Fi network.</li>
        <li>Open <strong>Settings → Bluetooth &amp; devices → Printers &amp; scanners</strong>.</li>
        <li>Select <strong>Add device</strong> and wait for Windows to search.</li>
        <li>Select the correct printer and finish setup.</li>
        <li>Print a small test document.</li>
    </ol>
    <div class="lh-callout"><strong>Tip:</strong> Many printers work with Windows without a separate installer. If extra features or a driver update are required, prefer Windows Update or the printer manufacturer's official support page.</div>
</section>

<section class="lh-section">
    <h2>4. Print Settings You Should Understand</h2>
    <p>Before printing, check the settings. A few seconds of checking can prevent wasted paper and incorrect output.</p>
    <div class="lh-grid">
        <div class="lh-card"><h3>Copies</h3><p>Number of copies to print.</p></div>
        <div class="lh-card"><h3>Pages</h3><p>All pages or a selected range.</p></div>
        <div class="lh-card"><h3>Orientation</h3><p>Portrait or landscape.</p></div>
        <div class="lh-card"><h3>Paper size</h3><p>For example A4 or another supported size.</p></div>
        <div class="lh-card"><h3>Colour</h3><p>Colour or black-and-white/grayscale.</p></div>
        <div class="lh-card"><h3>Duplex</h3><p>Both sides when supported.</p></div>
    </div>
    <div class="lh-callout"><strong>Best habit:</strong> Use <strong>Print Preview</strong> whenever available. It can reveal blank pages, cut-off text, wrong orientation and other layout problems before you waste paper.</div>
</section>

<section class="lh-section">
    <h2>5. Understanding the Print Queue</h2>
    <p>The <strong>print queue</strong> is the list of print jobs waiting to be processed. If several documents are sent to one printer, they normally wait in sequence.</p>
    <ul>
        <li>Cancel a wrong job before it prints.</li>
        <li>Pause or resume jobs when supported.</li>
        <li>Do not repeatedly click Print when the first job is already queued.</li>
        <li>If a job is stuck, inspect the queue before sending more jobs.</li>
    </ul>
    <p>If the queue remains stuck, restarting the printer/PC or using Windows printer troubleshooting can help.</p>
</section>

<section class="lh-section">
    <h2>6. Ink, Toner and Paper</h2>
    <table class="table table-bordered align-middle">
        <thead><tr><th>Problem</th><th>Possible cause</th><th>First check</th></tr></thead>
        <tbody>
            <tr><td>Nothing prints</td><td>Power, paper, offline state, connection or queue</td><td>Power, paper, connection and queue</td></tr>
            <tr><td>Faded colour</td><td>Low/empty ink or toner, wrong media/settings</td><td>Consumable level and print settings</td></tr>
            <tr><td>Lines or missing areas</td><td>Blocked nozzles or print-head issue on some inkjets</td><td>Printer maintenance/cleaning option</td></tr>
            <tr><td>Paper jam</td><td>Misfed, damaged or incorrectly loaded paper</td><td>Follow the printer's jam-removal procedure</td></tr>
            <tr><td>Wrong paper size</td><td>Document and printer settings disagree</td><td>Check paper size in both places</td></tr>
        </tbody>
    </table>
</section>

<section class="lh-section">
    <h2>7. What a Scanner Does</h2>
    <p>A scanner uses a sensor to capture a document or photo and produce digital data. The result can be saved as an image or document depending on the application and settings.</p>
    <h3>Useful scan formats</h3>
    <ul>
        <li><strong>PDF:</strong> useful for forms, documents and multi-page records.</li>
        <li><strong>JPEG:</strong> commonly used for photographs and general images.</li>
        <li><strong>PNG:</strong> useful for lossless storage of graphics and images.</li>
    </ul>
    <h3>Resolution and DPI</h3>
    <p><strong>DPI (dots per inch)</strong> is commonly used when describing scan and print resolution. Higher scan resolution can preserve more detail, but it also creates larger files and may take longer to process.</p>
    <p>For normal text documents, extremely high resolution is usually unnecessary. Choose a sensible setting based on whether you are reading, archiving or preserving a detailed photograph.</p>
</section>

<section class="lh-section">
    <h2>8. Scanning a Document in Windows</h2>
    <ol>
        <li>Turn on the scanner or multifunction printer.</li>
        <li>Place the document on the flatbed or in the automatic document feeder, if available.</li>
        <li>Check <strong>Settings → Bluetooth &amp; devices → Printers &amp; scanners</strong> to make sure Windows can see the device.</li>
        <li>Open a compatible scanning application, such as Windows Scan when available.</li>
        <li>Select the scanner and choose suitable document/photo settings.</li>
        <li>Start the scan and save it with a meaningful filename.</li>
    </ol>
    <div class="lh-callout"><strong>File naming tip:</strong> Instead of <code>Scan001.pdf</code>, use something like <code>2026-09-02_College_Form.pdf</code>. Good names make future searching much easier.</div>
</section>

<section class="lh-section">
    <h2>9. Printer vs Scanner: Think in Directions</h2>
    <table class="table table-bordered align-middle">
        <thead><tr><th>Task</th><th>Device</th><th>Result</th></tr></thead>
        <tbody>
            <tr><td>Print a resume</td><td>Printer</td><td>Paper copy</td></tr>
            <tr><td>Save a paper certificate on a PC</td><td>Scanner</td><td>Digital file</td></tr>
            <tr><td>Make a paper copy</td><td>All-in-One / copier</td><td>New paper copy</td></tr>
            <tr><td>Email a paper form</td><td>Scanner + email/app</td><td>Digital attachment</td></tr>
        </tbody>
    </table>
</section>

<section class="lh-section">
    <h2>10. Troubleshooting Checklist</h2>
    <div class="lh-callout">
        <p><strong>Printer not found?</strong></p>
        <ol>
            <li>Check power and the printer's status/display.</li>
            <li>Check the USB cable or Wi-Fi connection.</li>
            <li>Open Printers &amp; scanners and search/refresh.</li>
            <li>Confirm that you selected the correct printer.</li>
            <li>Check for appropriate Windows or manufacturer driver updates.</li>
            <li>If needed, remove and reinstall the printer.</li>
        </ol>
    </div>
    <div class="lh-callout">
        <p><strong>Printer says Offline?</strong></p>
        <p>Check power, connection, paper, selected printer and the print queue. If the problem continues, restart the printer and PC and use Windows printer troubleshooting or reinstall the printer.</p>
    </div>
    <div class="lh-callout">
        <p><strong>Scanner not detected?</strong></p>
        <p>Check that it is powered on and connected by USB or to the correct network. Then check Printers &amp; scanners and add the scanner if Windows did not discover it automatically.</p>
    </div>
</section>

<section class="lh-section">
    <h2>11. Safe Maintenance</h2>
    <ul>
        <li>Use paper supported by the printer and keep it dry and flat.</li>
        <li>Do not force a jammed sheet; follow the manufacturer's procedure.</li>
        <li>Keep the scanner glass clean with a suitable soft, lint-free material.</li>
        <li>Avoid touching sensitive internal parts unnecessarily.</li>
        <li>Use suitable genuine or reputable compatible consumables.</li>
        <li>Download printer drivers from Windows Update or the manufacturer's official support source, not random driver websites.</li>
    </ul>
</section>

<section class="lh-section">
    <h2>🧪 Practical Activity: Print + Scan Workflow</h2>
    <ol>
        <li>Create a one-page text document with a heading and a few lines of text.</li>
        <li>Open Print Preview and check page size, orientation and margins.</li>
        <li>Print one copy using the correct printer.</li>
        <li>Place the printed page on the scanner.</li>
        <li>Scan it at a sensible resolution.</li>
        <li>Save it as a PDF with a meaningful filename.</li>
        <li>Open the PDF and compare it with the original.</li>
    </ol>
    <p><strong>Goal:</strong> understand the full loop: <strong>digital document → printer → paper → scanner → digital file</strong>.</p>
</section>

<section class="lh-section">
    <h2>⚠️ Common Mistakes</h2>
    <ul>
        <li>Printing many copies without checking Print Preview.</li>
        <li>Choosing the wrong printer when several are installed.</li>
        <li>Assuming “offline” always means the printer is broken.</li>
        <li>Ignoring the print queue when a job is stuck.</li>
        <li>Scanning everything at unnecessarily high resolution.</li>
        <li>Saving scans with meaningless names such as <code>Scan001</code>.</li>
        <li>Downloading printer drivers from unknown websites.</li>
        <li>Assuming every multifunction printer's scanning function is configured automatically just because printing works.</li>
    </ul>
</section>

<section class="lh-section">
    <h2>🧠 Quick Self-Check</h2>
    <ol>
        <li>What is the basic difference between a printer and a scanner?</li>
        <li>What is the difference between an inkjet and a laser printer?</li>
        <li>Why is Print Preview useful?</li>
        <li>What is a print queue?</li>
        <li>Why might a wireless printer not appear on your computer?</li>
        <li>When is PDF a useful format for a scan?</li>
        <li>What does DPI describe in scanning?</li>
        <li>Why should printer drivers come from trusted sources?</li>
    </ol>
    <details class="mt-3">
        <summary><strong>Show short answers</strong></summary>
        <div class="lh-callout mt-3">
            <p><strong>1.</strong> Printer: digital → physical. Scanner: physical → digital.</p>
            <p><strong>2.</strong> Inkjet uses liquid ink; laser uses toner and laser-based imaging.</p>
            <p><strong>3.</strong> It catches layout and page problems before paper is wasted.</p>
            <p><strong>4.</strong> The list of print jobs waiting to be processed.</p>
            <p><strong>5.</strong> It may be disconnected, on another network, powered off or not yet added to Windows.</p>
            <p><strong>6.</strong> PDFs are convenient for document storage, sharing and multi-page records.</p>
            <p><strong>7.</strong> It is a common way to express scan/image resolution.</p>
            <p><strong>8.</strong> Trusted sources reduce the risk of incorrect, incompatible or unsafe drivers.</p>
        </div>
    </details>
</section>

<section class="lh-section">
    <h2>🎯 Key Takeaway</h2>
    <div class="lh-callout">
        <p><strong>Printers put digital information onto paper; scanners bring paper information into the digital world.</strong></p>
        <p>Good computer skills mean more than knowing where to click. You should also be able to choose sensible print/scan settings, check connections, understand queues and consumables, name files properly and troubleshoot problems step by step.</p>
    </div>
</section>
HTML;
}

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
    <p>A <strong>printer</strong> takes digital information from a computer or phone and produces a physical copy on paper. A <strong>scanner</strong> does the opposite: it captures a physical document or photo and turns it into a digital file.</p>

    <div class="lh-grid">
        <div class="lh-card">
            <h3>Printer</h3>
            <p><strong>Digital → Physical</strong></p>
            <p>Documents, photos, labels and other files become printed pages.</p>
        </div>
        <div class="lh-card">
            <h3>Scanner</h3>
            <p><strong>Physical → Digital</strong></p>
            <p>Paper documents and photos become image files or PDFs.</p>
        </div>
        <div class="lh-card">
            <h3>All-in-One</h3>
            <p><strong>Print + Scan + Copy</strong></p>
            <p>Many multifunction devices combine a printer and scanner in one machine.</p>
        </div>
    </div>
</section>

<section class="lh-section">
    <h2>1. How a Printer Works</h2>
    <p>When you click <strong>Print</strong>, the computer sends the document to the printer through a driver and the operating system's printing system. The printer then processes the job and places ink or toner onto paper.</p>

    <h3>Common printer types</h3>
    <table class="table table-bordered align-middle">
        <thead><tr><th>Type</th><th>Best suited for</th><th>Main idea</th></tr></thead>
        <tbody>
            <tr><td><strong>Inkjet</strong></td><td>Photos, colour documents, occasional home printing</td><td>Uses liquid ink sprayed onto paper.</td></tr>
            <tr><td><strong>Laser</strong></td><td>Frequent documents, offices, high-volume text</td><td>Uses toner and a laser-based printing process.</td></tr>
            <tr><td><strong>Thermal</strong></td><td>Receipts, labels and specialised uses</td><td>Uses heat-sensitive media or thermal transfer.</td></tr>
        </tbody>
    </table>

    <div class="lh-callout">
        <strong>Remember:</strong> The cheapest printer is not always the cheapest to operate. Ink/toner cost, page yield, paper requirements and maintenance matter too.
    </div>
</section>

<section class="lh-section">
    <h2>2. Connecting a Printer</h2>
    <p>A printer can communicate with your computer in several ways:</p>
    <ul>
        <li><strong>USB:</strong> a direct wired connection between the computer and printer.</li>
        <li><strong>Wi-Fi:</strong> the printer joins your wireless network so compatible devices can print without a direct cable.</li>
        <li><strong>Ethernet:</strong> a wired network connection, common in offices.</li>
        <li><strong>Bluetooth:</strong> available on some specialised or compact printers.</li>
    </ul>
    <p>For a wireless printer, the computer and printer generally need to be reachable on the same network. If the printer is not discovered, first check its power, network connection and displayed network name.</p>
</section>

<section class="lh-section">
    <h2>3. Adding a Printer in Windows</h2>
    <p>Modern Windows versions can automatically discover many printers. In Windows 11, a common path is <strong>Settings → Bluetooth &amp; devices → Printers &amp; scanners → Add device</strong>.</p>
    <ol>
        <li>Turn the printer on and make sure it has paper.</li>
        <li>For USB, connect the cable securely. For Wi-Fi, connect the printer to the intended wireless network.</li>
        <li>Open <strong>Settings → Bluetooth &amp; devices → Printers &amp; scanners</strong>.</li>
        <li>Select <strong>Add device</strong> and wait while Windows searches.</li>
        <li>Select the correct printer and complete the setup.</li>
        <li>Print a small test document to confirm that the connection works.</li>
    </ol>
    <p>Windows normally supports many printers without a separate installer, while updated or manufacturer-specific drivers may provide additional functionality. citeturn0search1turn0search5</p>
</section>

<section class="lh-section">
    <h2>4. Understanding Print Settings</h2>
    <p>Before printing, check the settings instead of blindly clicking Print. A few choices can dramatically change the result and the amount of paper used.</p>

    <div class="lh-grid">
        <div class="lh-card"><h3>Copies</h3><p>How many copies should be printed?</p></div>
        <div class="lh-card"><h3>Pages</h3><p>Print all pages or only a selected range.</p></div>
        <div class="lh-card"><h3>Orientation</h3><p>Portrait for vertical pages, landscape for wider content.</p></div>
        <div class="lh-card"><h3>Paper size</h3><p>For example, A4 or another supported size.</p></div>
        <div class="lh-card"><h3>Colour</h3><p>Colour or black-and-white/grayscale.</p></div>
        <div class="lh-card"><h3>Duplex</h3><p>Print on both sides when the printer supports it.</p></div>
    </div>

    <div class="lh-callout">
        <strong>Best habit:</strong> Use <strong>Print Preview</strong> whenever available. It can reveal cut-off text, blank pages, wrong orientation and unnecessary pages before paper is wasted.
    </div>
</section>

<section class="lh-section">
    <h2>5. The Print Queue</h2>
    <p>The <strong>print queue</strong> is the list of jobs waiting to be processed by a printer. If several documents are sent at once, they normally wait in order.</p>
    <p>A stuck job can make later jobs appear to be frozen. Open the printer's queue and check whether a document is paused, showing an error, or simply waiting for an earlier job.</p>
    <ul>
        <li>Cancel a wrong document before it prints.</li>
        <li>Pause/resume jobs when supported.</li>
        <li>Do not repeatedly click Print if the first job is already queued.</li>
        <li>If jobs remain stuck, restarting the printer or using Windows printer troubleshooting may help.</li>
    </ul>
    <p>Microsoft's current Windows guidance specifically covers stuck queues, printer connection problems and reinstalling a printer when necessary. citeturn0search2turn0search3</p>
</section>

<section class="lh-section">
    <h2>6. Ink, Toner and Paper</h2>
    <p>A printer can be perfectly connected and still fail to produce a good page.</p>
    <table class="table table-bordered align-middle">
        <thead><tr><th>Symptom</th><th>Possible cause</th><th>First thing to check</th></tr></thead>
        <tbody>
            <tr><td>Nothing prints</td><td>Out of paper, offline state, connection or queue problem</td><td>Power, paper, connection and queue</td></tr>
            <tr><td>Faded colour</td><td>Low/empty ink or toner, wrong media/settings</td><td>Consumable level and print settings</td></tr>
            <tr><td>Lines or missing areas</td><td>Blocked ink nozzles or print-head issue on some inkjets</td><td>Printer maintenance/cleaning option</td></tr>
            <tr><td>Paper jam</td><td>Misfed, damaged or incorrectly loaded paper</td><td>Follow the printer's jam-removal instructions</td></tr>
            <tr><td>Wrong paper size</td><td>Document and printer tray settings disagree</td><td>Paper size in both the app and printer</td></tr>
        </tbody>
    </table>
</section>

<section class="lh-section">
    <h2>7. What a Scanner Does</h2>
    <p>A scanner uses a sensor to capture the appearance of a document or photo. The result can be saved as a digital image or document, depending on the software and workflow.</p>

    <h3>Common scan formats</h3>
    <ul>
        <li><strong>PDF:</strong> useful for documents, forms and multi-page records.</li>
        <li><strong>JPEG:</strong> commonly used for photographs and general images.</li>
        <li><strong>PNG:</strong> useful when you want lossless image storage and good support for graphics.</li>
    </ul>

    <h3>Resolution and DPI</h3>
    <p><strong>DPI (dots per inch)</strong> is commonly used when discussing scan or print resolution. Higher scan resolution can preserve more detail, but it also creates larger files and can take longer to process.</p>
    <p>For an ordinary text document, extremely high resolution is usually unnecessary. Choose a sensible setting based on whether the goal is reading text, archiving a document or preserving a detailed photograph.</p>
</section>

<section class="lh-section">
    <h2>8. Scanning a Document in Windows</h2>
    <ol>
        <li>Turn on the scanner or multifunction printer.</li>
        <li>Place the document correctly on the flatbed or in the automatic document feeder, if available.</li>
        <li>Make sure Windows can see the scanner under <strong>Settings → Bluetooth &amp; devices → Printers &amp; scanners</strong>.</li>
        <li>Open a compatible scanning application, such as Windows Scan when available.</li>
        <li>Select the scanner, choose the document/photo settings and start the scan.</li>
        <li>Choose a useful file format and save the result with a meaningful filename.</li>
    </ol>
    <p>Windows can automatically discover many USB and network scanners. For wireless scanning, the scanner and Windows device generally need to be on the same network. citeturn0search0</p>

    <div class="lh-callout">
        <strong>File naming tip:</strong> Instead of <code>Scan001.pdf</code>, use something like <code>2026-09-02_College_Form.pdf</code>. Good names make future searching much easier.
    </div>
</section>

<section class="lh-section">
    <h2>9. Printer vs Scanner: Think in Directions</h2>
    <table class="table table-bordered align-middle">
        <thead><tr><th>Task</th><th>Device</th><th>Result</th></tr></thead>
        <tbody>
            <tr><td>Print a resume</td><td>Printer</td><td>Paper copy</td></tr>
            <tr><td>Save a paper certificate to your PC</td><td>Scanner</td><td>Digital file</td></tr>
            <tr><td>Make a paper copy of a document</td><td>All-in-One / copier</td><td>New paper copy</td></tr>
            <tr><td>Email a paper form</td><td>Scanner + email/app</td><td>Digital attachment</td></tr>
        </tbody>
    </table>
</section>

<section class="lh-section">
    <h2>10. Troubleshooting Checklist</h2>
    <div class="lh-callout">
        <p><strong>Printer not found?</strong></p>
        <ol>
            <li>Check power and the printer's display/status.</li>
            <li>Check the USB cable or confirm the printer is connected to the correct Wi-Fi network.</li>
            <li>Open Printers &amp; scanners and refresh/search for the device.</li>
            <li>Make sure you selected the correct printer model.</li>
            <li>Check for driver or Windows updates when appropriate.</li>
            <li>If the problem persists, remove and reinstall the printer.</li>
        </ol>
    </div>

    <div class="lh-callout">
        <p><strong>Printer says Offline?</strong></p>
        <p>Check power, network/cable connection, paper and the selected printer. Then inspect the print queue. If the issue continues, Windows' printer troubleshooting and reinstall steps can help. citeturn0search2turn0search3</p>
    </div>

    <div class="lh-callout">
        <p><strong>Scanner not detected?</strong></p>
        <p>Check that it is powered on, connected by USB or connected to the same network as the computer. Then check Printers &amp; scanners and add the scanner if it was not discovered automatically. citeturn0search0</p>
    </div>
</section>

<section class="lh-section">
    <h2>11. Safe Maintenance</h2>
    <ul>
        <li>Use paper supported by the printer and store it dry and flat.</li>
        <li>Do not force a jammed sheet; follow the manufacturer's removal procedure.</li>
        <li>Keep the scanner glass clean with a suitable soft, lint-free material.</li>
        <li>Do not touch sensitive internal parts unnecessarily.</li>
        <li>Use genuine or reputable compatible consumables appropriate for the model.</li>
        <li>Download drivers and printer software from Windows Update or the printer manufacturer's official support source rather than random driver websites.</li>
    </ul>
</section>

<section class="lh-section">
    <h2>🧪 Practical Activity: Print + Scan Workflow</h2>
    <p>Practice the complete workflow if you have access to a printer/scanner.</p>
    <ol>
        <li>Create a one-page text document containing a heading and a few lines of text.</li>
        <li>Open Print Preview and check page size, orientation and margins.</li>
        <li>Print one copy using the correct printer.</li>
        <li>Place the printed page on the scanner.</li>
        <li>Scan it at a sensible resolution.</li>
        <li>Save it as a PDF with a meaningful filename.</li>
        <li>Open the saved PDF and compare it with the original printed page.</li>
    </ol>
    <p><strong>Goal:</strong> understand the full loop: <strong>digital document → printer → paper → scanner → digital file</strong>.</p>
</section>

<section class="lh-section">
    <h2>⚠️ Common Mistakes</h2>
    <ul>
        <li>Printing many copies without checking Print Preview.</li>
        <li>Choosing the wrong printer when several printers are installed.</li>
        <li>Assuming “offline” always means the printer is broken.</li>
        <li>Ignoring the print queue when a job is stuck.</li>
        <li>Scanning everything at an unnecessarily huge resolution.</li>
        <li>Saving scans with meaningless names such as <code>Scan001</code> and then losing track of them.</li>
        <li>Downloading printer drivers from unknown websites.</li>
        <li>Confusing a printer's ability to print with an all-in-one device's separate scanning capability.</li>
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
            <p><strong>1.</strong> Printer converts digital information to a physical page; scanner converts physical material to digital data.</p>
            <p><strong>2.</strong> Inkjet uses liquid ink; laser uses toner and a laser-based imaging process.</p>
            <p><strong>3.</strong> It catches layout and page problems before paper is wasted.</p>
            <p><strong>4.</strong> It is the list of print jobs waiting to be processed.</p>
            <p><strong>5.</strong> It may be on another network, disconnected, powered off or not yet added to Windows.</p>
            <p><strong>6.</strong> PDFs are convenient for document storage, sharing and multi-page records.</p>
            <p><strong>7.</strong> It is a common way of expressing image/scan resolution.</p>
            <p><strong>8.</strong> Untrusted drivers can be incorrect, incompatible or unsafe.</p>
        </div>
    </details>
</section>

<section class="lh-section">
    <h2>🎯 Key Takeaway</h2>
    <div class="lh-callout">
        <p><strong>Printers put digital information onto paper; scanners bring paper information into the digital world.</strong></p>
        <p>Good computer skills are not just knowing where to click. They also mean choosing sensible print/scan settings, checking connections, understanding queues and consumables, naming files properly, and troubleshooting problems step by step.</p>
    </div>
</section>
HTML;
}

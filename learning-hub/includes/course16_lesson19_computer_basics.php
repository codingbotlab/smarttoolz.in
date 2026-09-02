<?php
declare(strict_types=1);

/**
 * Course 16 — Computer Basics Mastery
 * Lesson 19 — USB Devices
 */
function lh_course16_lesson19_computer_basics_override(array $lesson, int $position): ?string
{
    if ((string)($lesson['slug'] ?? '') !== 'course-16-lesson-19') {
        return null;
    }

    return <<<'HTML'
<section class="lh-section">
    <h2>🔌 USB Devices: The Everyday Connection</h2>
    <p><strong>USB</strong> stands for <strong>Universal Serial Bus</strong>. It is a common connection system used to connect computers to devices such as keyboards, mice, flash drives, phones, printers, webcams and external storage.</p>

    <div class="lh-grid">
        <div class="lh-card"><h3>Input</h3><p>Keyboard, mouse, game controller, microphone and webcam.</p></div>
        <div class="lh-card"><h3>Storage</h3><p>USB flash drives, external SSDs and external hard drives.</p></div>
        <div class="lh-card"><h3>Output</h3><p>Printers, some audio devices and other USB peripherals.</p></div>
        <div class="lh-card"><h3>Charging</h3><p>Many phones and accessories can receive power through USB.</p></div>
    </div>
</section>

<section class="lh-section">
    <h2>1. What USB Actually Does</h2>
    <p>USB can carry <strong>data, power, or both</strong>. That is why one small connector can be used for so many different jobs.</p>
    <p>When you connect a USB device, the computer detects the connection and communicates with the device using the appropriate hardware support and driver. Some devices work immediately through built-in Windows support; others need manufacturer software or drivers.</p>

    <div class="lh-callout">
        <strong>Simple idea:</strong> A USB port is not just a place to plug something in. It provides a communication path between the computer and the connected device.
    </div>
</section>

<section class="lh-section">
    <h2>2. USB Connector Types</h2>
    <p>USB has appeared in several connector shapes. The connector shape and the USB version are <strong>not the same thing</strong>.</p>
    <table class="table table-bordered align-middle">
        <thead><tr><th>Connector</th><th>Where you may see it</th><th>Important point</th></tr></thead>
        <tbody>
            <tr><td><strong>USB-A</strong></td><td>Many older and current PCs, hubs and accessories</td><td>Large rectangular connector.</td></tr>
            <tr><td><strong>USB-C</strong></td><td>Modern laptops, phones, tablets and accessories</td><td>Small, reversible connector.</td></tr>
            <tr><td><strong>Micro-USB</strong></td><td>Older phones and accessories</td><td>Still found on some older hardware.</td></tr>
            <tr><td><strong>Mini-USB</strong></td><td>Older cameras and specialised devices</td><td>Much less common today.</td></tr>
        </tbody>
    </table>
    <div class="lh-callout">
        <strong>Important:</strong> USB-C describes the connector shape. It does not automatically tell you the speed, charging capability, display support or other features of a particular port or cable.
    </div>
</section>

<section class="lh-section">
    <h2>3. USB Versions and Speed</h2>
    <p>USB technology has evolved over time. Different USB generations can support different maximum data rates, and the actual speed depends on the device, port, cable and workload.</p>
    <table class="table table-bordered align-middle">
        <thead><tr><th>Term you may encounter</th><th>What to understand</th></tr></thead>
        <tbody>
            <tr><td>USB 2.0</td><td>Older, widely compatible USB generation.</td></tr>
            <tr><td>USB 3.x</td><td>Family of faster USB generations commonly used for external storage and other high-speed devices.</td></tr>
            <tr><td>USB4</td><td>Newer USB technology designed for high-performance data and other capabilities.</td></tr>
        </tbody>
    </table>
    <p>Do not assume that a fast-looking USB-C port means every connected device will operate at the same high speed. The weakest compatible link in the connection can limit the result.</p>
</section>

<section class="lh-section">
    <h2>4. Data Transfer vs Charging</h2>
    <p>A cable that physically fits a port may not support every feature you expect.</p>
    <ul>
        <li>Some cables support both <strong>data and charging</strong>.</li>
        <li>Some cables are designed mainly for <strong>power/charging</strong>.</li>
        <li>USB ports can provide different amounts of power depending on the hardware and standard.</li>
        <li>Fast charging is not guaranteed simply because the connector is USB-C.</li>
    </ul>
    <div class="lh-callout">
        <strong>Real-world example:</strong> If your phone charges when connected to a PC but does not appear in File Explorer, the connection may be using a charge-only cable or the phone may not be configured for data transfer.
    </div>
</section>

<section class="lh-section">
    <h2>5. Connecting a USB Device Correctly</h2>
    <ol>
        <li>Identify the correct port and connector.</li>
        <li>Inspect the cable and connector for visible damage.</li>
        <li>Insert the connector gently and straight. Never force it.</li>
        <li>Wait a moment for Windows to detect and configure the device.</li>
        <li>Open the appropriate app or File Explorer to confirm that the device works.</li>
        <li>For storage devices, wait until file activity has finished before disconnecting.</li>
    </ol>
    <p>For some peripherals, manufacturer instructions may require software or drivers to be installed before connecting the device. citeturn0search8</p>
</section>

<section class="lh-section">
    <h2>6. USB Flash Drives and External Storage</h2>
    <p>A USB flash drive is a small removable storage device. External SSDs and hard drives can also connect through USB and are useful for moving or backing up larger amounts of data.</p>
    <table class="table table-bordered align-middle">
        <thead><tr><th>Device</th><th>Typical use</th><th>Remember</th></tr></thead>
        <tbody>
            <tr><td>USB flash drive</td><td>Moving files, portable documents, quick transfers</td><td>Small and convenient, but still needs backups.</td></tr>
            <tr><td>External SSD</td><td>Fast portable storage and backups</td><td>Usually faster and more resistant to movement than an HDD.</td></tr>
            <tr><td>External HDD</td><td>Large-capacity storage and backups</td><td>Usually offers high capacity at lower cost per GB.</td></tr>
        </tbody>
    </table>
    <div class="lh-callout">
        <strong>Never treat one USB drive as your only backup.</strong> If the drive is lost, damaged or corrupted, the files may be gone too.
    </div>
</section>

<section class="lh-section">
    <h2>7. Finding a USB Storage Device in Windows</h2>
    <ol>
        <li>Connect the USB storage device.</li>
        <li>Open <strong>File Explorer</strong>.</li>
        <li>Look under <strong>This PC</strong> for a new drive.</li>
        <li>Open it and check that the expected folders/files are visible.</li>
        <li>Before disconnecting, finish copying or saving files.</li>
    </ol>
    <p>If the drive does not appear in File Explorer, it may still be detected by Windows but have a partition, file-system, drive-letter or hardware problem. Disk Management and Device Manager can provide more information.</p>
</section>

<section class="lh-section">
    <h2>8. Safely Removing USB Storage</h2>
    <p>For removable storage, the safest habit is to make sure file activity has finished and use Windows' <strong>Safely Remove Hardware and Eject Media</strong> option when appropriate. Microsoft documents the eject process for Windows 10 and Windows 11. citeturn0search0</p>
    <ol>
        <li>Finish copying, opening or saving files on the USB drive.</li>
        <li>Close File Explorer windows or applications using the drive.</li>
        <li>Use the system-tray <strong>Safely Remove Hardware and Eject Media</strong> option when available.</li>
        <li>Select the correct device and wait for Windows to indicate that it is safe to remove.</li>
        <li>Disconnect the device gently.</li>
    </ol>
    <div class="lh-callout">
        <strong>Most important rule:</strong> Never pull out a storage device while important data is actively being written to it. Safe removal reduces the chance of unfinished writes and file-system problems. citeturn0search0
    </div>
</section>

<section class="lh-section">
    <h2>9. When Windows Says “USB Device Not Recognized”</h2>
    <p>Do not immediately assume the USB device is dead. Work through the simplest possibilities first.</p>
    <table class="table table-bordered align-middle">
        <thead><tr><th>Possible cause</th><th>What to try</th></tr></thead>
        <tbody>
            <tr><td>Loose connection</td><td>Disconnect and reconnect carefully.</td></tr>
            <tr><td>Bad USB port</td><td>Try another compatible port.</td></tr>
            <tr><td>Bad cable</td><td>Try a known-good cable where appropriate.</td></tr>
            <tr><td>Device problem</td><td>Test the device on another computer.</td></tr>
            <tr><td>Driver/device state issue</td><td>Check Device Manager and restart Windows if needed.</td></tr>
            <tr><td>Insufficient power</td><td>Try a different port or a properly powered setup for devices that need more power.</td></tr>
        </tbody>
    </table>
    <p>Common troubleshooting guidance includes testing another port/cable, testing the device on another computer and checking Device Manager for warning symbols. citeturn0search3turn0search1</p>
</section>

<section class="lh-section">
    <h2>10. Device Manager and USB</h2>
    <p><strong>Device Manager</strong> can show Windows' view of connected hardware and can help identify devices with driver or configuration problems.</p>
    <ol>
        <li>Open <strong>Device Manager</strong>.</li>
        <li>Look for <strong>Universal Serial Bus controllers</strong> and the relevant device category.</li>
        <li>Look for warning icons or devices with an error status.</li>
        <li>Open device properties when you need more information.</li>
        <li>After reconnecting hardware, Windows can also be asked to scan for hardware changes.</li>
    </ol>
    <p>Microsoft's USBView documentation also describes Device Manager as a useful place to inspect USB information and troubleshoot common USB problems. citeturn0search1</p>
</section>

<section class="lh-section">
    <h2>11. USB Hubs</h2>
    <p>A <strong>USB hub</strong> lets one USB port connect to multiple devices. Hubs are useful when a laptop has fewer physical ports than the number of accessories you need.</p>
    <ul>
        <li>Bus-powered hubs receive their power from the computer.</li>
        <li>Powered hubs have their own power supply and can be better suited to multiple or higher-power devices.</li>
        <li>Adding several devices to one hub can create bandwidth or power limitations.</li>
        <li>For important external storage, connect directly to the computer when troubleshooting rather than adding a hub as another variable.</li>
    </ul>
</section>

<section class="lh-section">
    <h2>12. USB Safety and Security</h2>
    <ul>
        <li>Do not plug an unknown USB device into your main computer just because you found it somewhere.</li>
        <li>Be careful with USB drives received from unknown sources.</li>
        <li>Keep Windows security features and antivirus protection up to date.</li>
        <li>Do not open suspicious files just because they are on a USB drive.</li>
        <li>Keep important files backed up somewhere other than the removable drive.</li>
    </ul>
    <div class="lh-callout">
        <strong>Security lesson:</strong> USB is a physical connection, not a guarantee that the connected device or its files are trustworthy.
    </div>
</section>

<section class="lh-section">
    <h2>🧪 Practical Activity: USB Skills Test</h2>
    <p>If you have a USB flash drive or another safe USB device, practice the complete workflow.</p>
    <ol>
        <li>Connect the USB device and wait for Windows to detect it.</li>
        <li>Open File Explorer and locate the device under This PC.</li>
        <li>Create a folder named <code>USB_Practice</code>.</li>
        <li>Copy one small test file into that folder.</li>
        <li>Open the copied file to confirm the transfer worked.</li>
        <li>Close the file and finish all disk activity.</li>
        <li>Use the safe-eject option when available.</li>
        <li>Disconnect the USB device.</li>
    </ol>
    <p><strong>Goal:</strong> learn the full cycle: <strong>connect → detect → use → finish activity → eject → disconnect</strong>.</p>
</section>

<section class="lh-section">
    <h2>🔧 Troubleshooting Practice</h2>
    <p>Imagine a USB flash drive is not appearing in File Explorer. Follow this order:</p>
    <ol>
        <li>Check whether the connector is inserted correctly.</li>
        <li>Try another USB port.</li>
        <li>Try another known-good cable if the device uses a detachable cable.</li>
        <li>Restart Windows and reconnect the device.</li>
        <li>Test the device on another computer.</li>
        <li>Check Device Manager for errors.</li>
        <li>For storage devices, check Disk Management before changing partitions or formatting anything.</li>
    </ol>
    <div class="lh-callout">
        <strong>Warning:</strong> Do not format a USB storage device just because Windows suggests it unless you understand the consequences and have confirmed that the data is not needed. Formatting can erase access to existing files.
    </div>
</section>

<section class="lh-section">
    <h2>⚠️ Common Mistakes</h2>
    <ul>
        <li>Forcing a connector into the wrong port or orientation.</li>
        <li>Assuming every USB-C cable has the same speed and features.</li>
        <li>Using a damaged cable or port and repeatedly reconnecting it.</li>
        <li>Unplugging a storage device during file transfer.</li>
        <li>Keeping the only copy of important files on a USB drive.</li>
        <li>Blaming the device before testing another port or computer.</li>
        <li>Formatting a drive without understanding what will happen to existing data.</li>
        <li>Installing random USB drivers from untrusted websites.</li>
    </ul>
</section>

<section class="lh-section">
    <h2>🧠 Quick Self-Check</h2>
    <ol>
        <li>What does USB stand for?</li>
        <li>What are two things USB can carry?</li>
        <li>What is the difference between USB-A and USB-C?</li>
        <li>Does USB-C automatically mean the fastest USB speed?</li>
        <li>Why might a phone charge but not appear for file transfer?</li>
        <li>What should you do before disconnecting USB storage?</li>
        <li>Name two things to try when Windows does not recognize a USB device.</li>
        <li>Why should important files not exist only on a USB flash drive?</li>
    </ol>
    <details class="mt-3">
        <summary><strong>Show short answers</strong></summary>
        <div class="lh-callout mt-3">
            <p><strong>1.</strong> Universal Serial Bus.</p>
            <p><strong>2.</strong> Data and power.</p>
            <p><strong>3.</strong> They are different connector shapes; USB-C is the smaller reversible connector.</p>
            <p><strong>4.</strong> No. USB-C describes the connector shape, not every capability.</p>
            <p><strong>5.</strong> The cable may be charge-only, or the phone may not be configured for data transfer.</p>
            <p><strong>6.</strong> Finish file activity and use the safe-eject process when appropriate.</p>
            <p><strong>7.</strong> Try another port/cable and test the device on another computer; also check Device Manager.</p>
            <p><strong>8.</strong> A USB drive can fail, be lost, damaged or corrupted, so backups are necessary.</p>
        </div>
    </details>
</section>

<section class="lh-section">
    <h2>🎯 Key Takeaway</h2>
    <div class="lh-callout">
        <p><strong>USB is one of the most useful ways to connect a computer to the outside world.</strong></p>
        <p>Good USB skills mean understanding connectors, data and power, storage devices, safe removal, basic troubleshooting and security. When something goes wrong, start with the simple checks: <strong>port → cable → device → Windows detection → driver/status</strong>.</p>
    </div>
</section>
HTML;
}

<?php
declare(strict_types=1);

/**
 * Course 16 — Computer Basics Mastery
 * Lesson 20 — Bluetooth Connections
 */
function lh_course16_lesson20_computer_basics_override(array $lesson, int $position): ?string
{
    if ((string)($lesson['slug'] ?? '') !== 'course-16-lesson-20') {
        return null;
    }

    return <<<'HTML'
<section class="lh-section">
    <h2>📶 Bluetooth Connections: Wireless Communication</h2>
    <p><strong>Bluetooth</strong> is a short-range wireless technology that lets devices communicate without a USB cable. It is commonly used for keyboards, mice, headphones, speakers, phones, game controllers and other accessories.</p>

    <div class="lh-grid">
        <div class="lh-card"><h3>Wireless</h3><p>No physical data cable is required after pairing.</p></div>
        <div class="lh-card"><h3>Short Range</h3><p>Bluetooth is designed for nearby devices rather than long-distance networking.</p></div>
        <div class="lh-card"><h3>Many Devices</h3><p>Different Bluetooth profiles support different jobs such as audio, input and data exchange.</p></div>
    </div>
</section>

<section class="lh-section">
    <h2>1. Bluetooth vs Wi-Fi</h2>
    <p>Both technologies are wireless, but they are designed for different jobs.</p>
    <table class="table table-bordered align-middle">
        <thead><tr><th>Bluetooth</th><th>Wi-Fi</th></tr></thead>
        <tbody>
            <tr><td>Usually connects nearby accessories directly.</td><td>Usually connects devices to a local network and often the internet.</td></tr>
            <tr><td>Useful for keyboards, mice, earbuds and controllers.</td><td>Useful for web access, streaming, downloads and network communication.</td></tr>
            <tr><td>Generally designed for short-range, lower-power connections.</td><td>Generally designed for higher-throughput network communication.</td></tr>
        </tbody>
    </table>
    <div class="lh-callout"><strong>Easy memory trick:</strong> Bluetooth is often the “accessory connection”; Wi-Fi is often the “network connection.” They can also work together on the same computer.</div>
</section>

<section class="lh-section">
    <h2>2. Pairing and Connecting Are Not Exactly the Same</h2>
    <p><strong>Pairing</strong> means establishing a relationship between two Bluetooth devices so they can recognize and authenticate each other. <strong>Connecting</strong> means actually using that paired device for a particular function.</p>
    <p>For example, your wireless headphones may be paired with your laptop but not currently connected because they are turned off or connected to another device.</p>
</section>

<section class="lh-section">
    <h2>3. What You Need Before Pairing</h2>
    <ul>
        <li>Your computer must have Bluetooth built in or have a compatible Bluetooth adapter.</li>
        <li>Bluetooth should be turned on.</li>
        <li>The accessory should have enough battery or power.</li>
        <li>The accessory must be in <strong>pairing/discoverable mode</strong> when required.</li>
        <li>Keep the devices reasonably close during setup.</li>
        <li>If the accessory is already connected to another device, disconnect it there when necessary.</li>
    </ul>
    <p>Microsoft notes that a device should be turned on and, when required, placed into pairing mode and kept near the PC during setup. citeturn0search0</p>
</section>

<section class="lh-section">
    <h2>4. Turn Bluetooth On in Windows 11</h2>
    <p>In current Windows 11, a common path is <strong>Start → Settings → Bluetooth &amp; devices</strong>. From there, Bluetooth can be switched on or off. It can also be available through Quick Settings near the taskbar clock.</p>
    <ol>
        <li>Open <strong>Settings</strong>.</li>
        <li>Select <strong>Bluetooth &amp; devices</strong>.</li>
        <li>Turn <strong>Bluetooth</strong> on.</li>
        <li>Keep this area open while adding a new device.</li>
    </ol>
    <p>If the Bluetooth option is completely missing, Windows may not be detecting a Bluetooth adapter or its driver may not be installed correctly. citeturn0search4</p>
</section>

<section class="lh-section">
    <h2>5. Pair a Bluetooth Device</h2>
    <ol>
        <li>Turn on Bluetooth on the computer.</li>
        <li>Turn on the accessory.</li>
        <li>Put the accessory into pairing/discoverable mode according to its instructions.</li>
        <li>Open <strong>Settings → Bluetooth &amp; devices → Devices</strong>.</li>
        <li>Choose <strong>Add device</strong>.</li>
        <li>Select <strong>Bluetooth</strong>.</li>
        <li>Wait for the accessory name to appear.</li>
        <li>Select it and follow any PIN, confirmation or on-screen instructions.</li>
        <li>After pairing, test the device.</li>
    </ol>
    <p>Windows can also support faster pairing experiences such as Swift Pair for compatible accessories. citeturn0search3</p>
</section>

<section class="lh-section">
    <h2>6. Different Bluetooth Devices Behave Differently</h2>
    <div class="lh-grid">
        <div class="lh-card"><h3>⌨️ Keyboard</h3><p>Used for wireless typing and shortcuts. Check battery level if keys stop responding.</p></div>
        <div class="lh-card"><h3>🖱️ Mouse</h3><p>Used as a wireless pointing device. Movement problems may be caused by battery, connection or surface issues.</p></div>
        <div class="lh-card"><h3>🎧 Headphones</h3><p>Used mainly for audio. Windows must select the correct output device.</p></div>
        <div class="lh-card"><h3>📱 Phone</h3><p>Can pair for selected features, depending on the phone and Windows software.</p></div>
    </div>
</section>

<section class="lh-section">
    <h2>7. Bluetooth Audio: Pairing Is Only Step One</h2>
    <p>Sometimes headphones show as connected but you still hear sound from the laptop speakers. In that case, check the Windows audio output device.</p>
    <ol>
        <li>Make sure the headphones are connected.</li>
        <li>Select the sound control near the taskbar.</li>
        <li>Choose the correct Bluetooth headphones/speaker as the output device.</li>
        <li>If necessary, open <strong>Settings → System → Sound</strong> and check Output.</li>
    </ol>
    <p>Microsoft recommends checking the selected Bluetooth output when a device is connected but no sound is heard. citeturn0search2</p>
</section>

<section class="lh-section">
    <h2>8. Why Bluetooth Devices Sometimes Disconnect</h2>
    <ul>
        <li>Low battery or the device has powered off.</li>
        <li>The devices are too far apart or the signal is obstructed.</li>
        <li>The accessory has connected to another nearby device.</li>
        <li>Bluetooth has been turned off.</li>
        <li>The Bluetooth adapter or driver has a problem.</li>
        <li>The device or computer has temporarily entered a faulty state.</li>
        <li>Compatibility or device-specific limitations.</li>
    </ul>
    <div class="lh-callout"><strong>Don't panic:</strong> A disconnected Bluetooth device does not automatically mean the hardware is damaged. Start with power, distance, Bluetooth status and pairing.</div>
</section>

<section class="lh-section">
    <h2>9. Troubleshooting: Device Won't Pair</h2>
    <ol>
        <li>Confirm Bluetooth is enabled on the computer.</li>
        <li>Confirm the accessory is on and has enough battery.</li>
        <li>Put the accessory back into pairing mode.</li>
        <li>Move it close to the computer.</li>
        <li>Turn Bluetooth off, wait briefly, then turn it on again.</li>
        <li>If the device is already listed but refuses to connect, remove it and pair it again.</li>
        <li>Restart the computer and accessory if needed.</li>
    </ol>
    <p>Microsoft's troubleshooting guidance includes turning Bluetooth off and on again and removing/re-adding a device when pairing or connection problems persist. citeturn0search2turn0search4</p>
</section>

<section class="lh-section">
    <h2>10. Troubleshooting: Bluetooth Is Missing</h2>
    <div class="lh-callout">
        <p><strong>Check the adapter:</strong> Open <strong>Device Manager</strong> and look for the Bluetooth section. If the adapter is present, an available driver update may help.</p>
        <p><strong>Check Windows Update:</strong> Some Bluetooth driver updates are delivered through Windows Update.</p>
        <p><strong>Check Airplane Mode:</strong> Airplane mode can disable wireless functions including Bluetooth.</p>
    </div>
    <p>Microsoft recommends checking Device Manager, Bluetooth drivers, Airplane Mode and Windows Update when Bluetooth disappears or cannot be enabled. citeturn0search8turn0search9</p>
</section>

<section class="lh-section">
    <h2>11. Managing Old Bluetooth Devices</h2>
    <p>Over time, your computer may contain a long list of old headphones, mice, phones and controllers. Removing devices you no longer use makes the list easier to understand.</p>
    <ol>
        <li>Open <strong>Settings → Bluetooth &amp; devices</strong>.</li>
        <li>Find the old device.</li>
        <li>Use its options to remove/unpair it.</li>
        <li>Pair it again later if you need it.</li>
    </ol>
    <div class="lh-callout"><strong>Useful habit:</strong> If a device is listed but refuses to connect, removing it and pairing it again can be more effective than repeatedly clicking Connect. citeturn0search2</div>
</section>

<section class="lh-section">
    <h2>12. Bluetooth Security Basics</h2>
    <ul>
        <li>Only pair with devices you recognize.</li>
        <li>Do not accept unexpected pairing requests.</li>
        <li>Use manufacturer instructions for pairing codes or PINs.</li>
        <li>Remove old or unknown paired devices.</li>
        <li>Keep your operating system and device drivers updated.</li>
        <li>Be careful when pairing in crowded public places where many devices may be nearby.</li>
    </ul>
    <p>Bluetooth is convenient, but wireless convenience should not replace basic security awareness.</p>
</section>

<section class="lh-section">
    <h2>13. Bluetooth and Battery Life</h2>
    <p>Bluetooth accessories are designed to operate wirelessly, but they still consume power. Battery life varies by device, usage and technology.</p>
    <p>If earbuds, a mouse or keyboard repeatedly disconnects, check its battery before changing complicated Windows settings. A low battery is one of the simplest explanations.</p>
</section>

<section class="lh-section">
    <h2>🧪 Practical Activity: Pair, Test and Remove</h2>
    <p>If you have a Bluetooth mouse, keyboard, speaker or headphones, practice this workflow.</p>
    <ol>
        <li>Turn Bluetooth on in Windows.</li>
        <li>Put the accessory into pairing mode.</li>
        <li>Add it from Windows Bluetooth settings.</li>
        <li>Confirm the device appears in your paired/connected devices.</li>
        <li>Test its actual function: type, move, play sound or perform another supported action.</li>
        <li>Disconnect it.</li>
        <li>Reconnect it without repeating the entire pairing process.</li>
        <li>Finally, remove the device from Windows and understand where that option is.</li>
    </ol>
    <p><strong>Goal:</strong> learn the difference between <strong>discover → pair → connect → use → disconnect → remove</strong>.</p>
</section>

<section class="lh-section">
    <h2>⚠️ Common Mistakes</h2>
    <ul>
        <li>Forgetting to put the accessory into pairing mode.</li>
        <li>Trying to pair a device that is already actively connected to another device.</li>
        <li>Assuming paired means currently connected.</li>
        <li>Blaming Bluetooth when the real problem is a dead battery.</li>
        <li>Forgetting to select Bluetooth headphones as the audio output.</li>
        <li>Downloading random Bluetooth drivers from unofficial websites.</li>
        <li>Accepting unknown pairing requests without checking the device.</li>
        <li>Leaving dozens of unused old devices in the Bluetooth list.</li>
    </ul>
</section>

<section class="lh-section">
    <h2>🧠 Quick Self-Check</h2>
    <ol>
        <li>What is Bluetooth mainly used for?</li>
        <li>What is the difference between pairing and connecting?</li>
        <li>Why must some devices be placed in pairing mode?</li>
        <li>How is Bluetooth different from Wi-Fi?</li>
        <li>What should you check if Bluetooth headphones are connected but there is no sound?</li>
        <li>What can you try if a previously paired device will not reconnect?</li>
        <li>Where can you look for the Bluetooth adapter in Windows?</li>
        <li>Why should you avoid accepting unexpected Bluetooth pairing requests?</li>
    </ol>
    <details class="mt-3">
        <summary><strong>Show short answers</strong></summary>
        <div class="lh-callout mt-3">
            <p><strong>1.</strong> Connecting nearby wireless accessories and other supported devices.</p>
            <p><strong>2.</strong> Pairing establishes the device relationship; connecting starts an active connection.</p>
            <p><strong>3.</strong> It makes the accessory discoverable so the computer can find and pair with it.</p>
            <p><strong>4.</strong> Bluetooth commonly connects nearby accessories; Wi-Fi commonly provides network connectivity.</p>
            <p><strong>5.</strong> Check the selected audio output under Windows Sound settings.</p>
            <p><strong>6.</strong> Check power, pairing mode, distance, Bluetooth status, then remove and re-pair it.</p>
            <p><strong>7.</strong> Device Manager → Bluetooth.</p>
            <p><strong>8.</strong> Unexpected pairing can connect your computer to an unknown device and create a security/privacy risk.</p>
        </div>
    </details>
</section>

<section class="lh-section">
    <h2>🎯 Key Takeaway</h2>
    <div class="lh-callout">
        <p><strong>Bluetooth makes nearby device connections wireless, but successful Bluetooth use depends on pairing mode, battery, distance, correct device selection and drivers.</strong></p>
        <p>When something fails, troubleshoot in a simple order: <strong>power → pairing mode → distance → Bluetooth status → device list → remove/re-pair → drivers/Windows updates</strong>.</p>
    </div>
</section>
HTML;
}

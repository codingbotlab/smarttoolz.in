<?php
declare(strict_types=1);

/* Course 16, Lesson 17: Device Drivers */
function lh_course16_lesson17_computer_basics_override(array $lesson, int $position): ?string
{
    $slug = (string)($lesson['slug'] ?? '');
    if ($slug !== 'course-16-lesson-17') {
        return null;
    }

    return <<<'HTML'
<article class="lh-prose">
  <div class="lh-lesson-intro">
    <p><strong>A device driver is software that helps Windows communicate with a hardware device.</strong> Your keyboard, mouse, graphics card, printer, Wi-Fi adapter and many other components depend on drivers so the operating system knows how to use their features correctly.</p>
  </div>

  <h2>1. What is a device driver?</h2>
  <p>Hardware and the operating system do not automatically speak the same language. A driver acts as a software layer between them. When Windows needs to send instructions to a device, the appropriate driver helps translate those instructions into something the hardware can understand.</p>
  <p>For example, Windows may know that a printer is connected, but the printer's driver can provide the information and commands needed for printing, paper settings, status reporting and other supported features.</p>

  <div class="alert alert-primary"><strong>Easy analogy:</strong> Think of hardware as a worker who speaks a specific language and the driver as the translator. Without the right translator, communication may be limited or may fail completely.</div>

  <h2>2. Which devices use drivers?</h2>
  <p>Many types of hardware use drivers. Some basic devices can work immediately with drivers already included in Windows, while specialised hardware may need a manufacturer-provided driver.</p>
  <div class="table-responsive"><table class="table table-bordered align-middle"><thead><tr><th>Device</th><th>What the driver helps Windows do</th></tr></thead><tbody>
    <tr><td>Keyboard</td><td>Receive key input and special keys.</td></tr>
    <tr><td>Mouse / touchpad</td><td>Receive pointer movement, clicks, gestures and buttons.</td></tr>
    <tr><td>Graphics card</td><td>Handle display output, graphics acceleration and supported features.</td></tr>
    <tr><td>Audio device</td><td>Provide sound input/output and device-specific controls.</td></tr>
    <tr><td>Wi-Fi / Ethernet adapter</td><td>Allow Windows to communicate over a network.</td></tr>
    <tr><td>Printer / scanner</td><td>Support printing, scanning and device-specific functions.</td></tr>
    <tr><td>USB devices</td><td>Provide the software interface required by the particular hardware.</td></tr>
  </tbody></table></div>

  <h2>3. Drivers are not the same as hardware</h2>
  <p>A common beginner mistake is to think that a driver is part of the physical device. It is software. The physical device remains hardware; the driver is software installed in the operating system that helps control or communicate with it.</p>
  <p>This distinction becomes useful when troubleshooting. A device can be physically connected and still fail to work correctly because its driver is missing, incompatible, corrupted or outdated.</p>

  <h2>4. Plug and Play</h2>
  <p>Modern Windows versions can automatically detect many devices and install a compatible driver. This is why you can often connect a keyboard, mouse, USB device or other hardware and begin using it without manually downloading anything.</p>
  <p>Automatic detection does not mean every device has every driver available locally. Specialised hardware may still require a driver or software package from the manufacturer.</p>

  <h2>5. How to check drivers in Windows</h2>
  <p><strong>Device Manager</strong> is one of the main Windows tools for viewing installed hardware and its driver status.</p>
  <ol>
    <li>Right-click the <strong>Start</strong> button.</li>
    <li>Open <strong>Device Manager</strong>.</li>
    <li>Expand a category such as Display adapters, Network adapters, Sound, video and game controllers, or Printers.</li>
    <li>Select a device and open its properties.</li>
    <li>Use the <strong>Driver</strong> tab to view driver information and available management options.</li>
  </ol>
  <p>You can also use Windows Update to obtain many driver updates. For important hardware, always prefer a compatible driver from Windows Update or the device manufacturer's official support page rather than an unknown download site.</p>

  <h2>6. What does a yellow warning icon mean?</h2>
  <p>A warning symbol in Device Manager can indicate a problem with a device or its driver. Possible causes include a missing driver, failed installation, incompatible software, disabled hardware or another configuration problem.</p>
  <p>Do not immediately assume that reinstalling a random driver will fix it. First identify the exact device and read the error information available in its properties.</p>

  <h2>7. Updating a driver safely</h2>
  <p>Driver updates can add compatibility, fix bugs or improve support for newer versions of Windows and hardware. But newer is not automatically better for every situation.</p>
  <ol>
    <li>Identify the exact hardware model.</li>
    <li>Check whether the current driver is already working correctly.</li>
    <li>Use Windows Update or the manufacturer's official support page.</li>
    <li>Check that the driver matches your Windows version and hardware.</li>
    <li>Create a restore point or otherwise make sure you can recover if the update causes a problem.</li>
    <li>Install the driver and restart if requested.</li>
    <li>Test the device after installation.</li>
  </ol>

  <div class="alert alert-warning"><strong>Security warning:</strong> Avoid websites that offer “driver updater” programs or suspicious driver packs with unclear origins. A driver is system-level software, so downloading one from an untrusted source can create security and stability risks.</div>

  <h2>8. Driver rollback</h2>
  <p>Sometimes a new driver creates a problem even though the installation succeeds. Windows may provide a <strong>Roll Back Driver</strong> option in Device Manager when a previous driver is available.</p>
  <p>Rollback is useful when a device worked correctly before an update and immediately became unstable afterward. It is better to diagnose the change first than to keep installing unrelated driver versions.</p>

  <h2>9. Driver vs firmware</h2>
  <p>Drivers and firmware are both software-related, but they are not the same thing. A driver normally runs as part of the operating system environment and helps Windows communicate with hardware. <strong>Firmware</strong> is software stored on the device itself and controls lower-level device behaviour.</p>
  <p>Because firmware updates can affect the device at a deeper level, follow the manufacturer's instructions carefully and never interrupt a critical firmware update without understanding the recovery process.</p>

  <h2>10. When should you troubleshoot a driver?</h2>
  <div class="table-responsive"><table class="table table-bordered align-middle"><thead><tr><th>Symptom</th><th>Possible direction</th></tr></thead><tbody>
    <tr><td>Device is not detected</td><td>Check connection, power, Device Manager and hardware detection.</td></tr>
    <tr><td>Device appears with a warning</td><td>Open properties and inspect the reported error.</td></tr>
    <tr><td>Device stopped working after an update</td><td>Consider rollback, reinstall or a known-good manufacturer driver.</td></tr>
    <tr><td>Basic device works but advanced features do not</td><td>Check whether the manufacturer's driver/software is required.</td></tr>
    <tr><td>Random driver installer is being recommended</td><td>Do not install it blindly; verify the source and exact hardware first.</td></tr>
  </tbody></table></div>

  <h2>Practical exercise</h2>
  <ol>
    <li>Open <strong>Device Manager</strong> on your Windows computer.</li>
    <li>Choose one category, such as Display adapters, Network adapters or Keyboards.</li>
    <li>Select a device and open its Properties.</li>
    <li>Look at the Driver tab and note the driver provider, date and version.</li>
    <li>Do not change anything if the device is working normally.</li>
    <li>Write down the exact device model and its manufacturer. This information will be useful if you ever need to find official support.</li>
  </ol>

  <h2>Common mistakes</h2>
  <ul>
    <li>Downloading drivers from random websites.</li>
    <li>Installing a driver meant for a different hardware model.</li>
    <li>Using third-party “driver updater” tools without understanding what they will install.</li>
    <li>Assuming every hardware problem is caused by a driver.</li>
    <li>Ignoring the exact error message in Device Manager.</li>
    <li>Interrupting a firmware update because it appears to take a long time.</li>
  </ul>

  <h2>Quick self-check</h2>
  <ol>
    <li>What is the main job of a device driver?</li>
    <li>Why can a device be connected but still not work correctly?</li>
    <li>Where can you inspect hardware and driver status in Windows?</li>
    <li>Why should you use official driver sources?</li>
    <li>When might rolling back a driver make sense?</li>
    <li>What is one important difference between a driver and firmware?</li>
  </ol>

  <h2>Key takeaway</h2>
  <p><strong>Drivers are the software bridge between Windows and hardware.</strong> Learn to identify the device, check its status in Device Manager, use trusted update sources and troubleshoot systematically. You do not need to update every driver simply because a newer version exists—update when there is a clear reason and a compatible, trustworthy source.</p>
</article>
HTML;
}

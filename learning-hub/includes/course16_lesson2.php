<?php
declare(strict_types=1);

/* Course 16 - Lesson 2: Input and Output Devices */
function lh_course16_lesson2_override(array $lesson, int $position): ?string
{
    $slug = (string)($lesson['slug'] ?? '');
    if ($slug !== 'course-16-lesson-2') {
        return null;
    }

    return <<<'HTML'
<article class="lh-prose">
  <div class="lh-lesson-intro">
    <p><strong>Input and output devices are how a computer communicates with the outside world.</strong> Input devices send information or commands into the computer, while output devices present the computer's results back to you. Understanding this distinction makes it much easier to choose peripherals, troubleshoot problems and understand how a complete computer system works.</p>
  </div>

  <h2>1. What is an input device?</h2>
  <p>An <strong>input device</strong> is hardware that lets a person, another device or a sensor provide data or commands to a computer. The computer receives that information, processes it and may then produce an output.</p>
  <p>The keyboard is a simple example: you press a key, the computer receives that input, software interprets it, and the result may appear on the screen.</p>

  <h2>2. What is an output device?</h2>
  <p>An <strong>output device</strong> takes information produced by the computer and presents it in a form that a person or another system can use. A monitor shows images, speakers produce sound and a printer creates a physical copy.</p>
  <div class="alert alert-primary"><strong>Easy rule:</strong> If information is going <em>into</em> the computer, think <strong>input</strong>. If the computer is sending a result <em>out</em>, think <strong>output</strong>.</div>

  <h2>3. Common input devices</h2>
  <div class="table-responsive"><table class="table table-bordered align-middle"><thead><tr><th>Device</th><th>What it does</th><th>Typical use</th></tr></thead><tbody>
    <tr><td><strong>Keyboard</strong></td><td>Sends text, numbers, shortcuts and commands.</td><td>Typing documents, coding, searching and controlling software.</td></tr>
    <tr><td><strong>Mouse</strong></td><td>Controls a pointer and sends click, scroll and movement input.</td><td>Selecting items, navigating interfaces and working in design software.</td></tr>
    <tr><td><strong>Touchpad</strong></td><td>Detects finger movement and gestures.</td><td>Pointer control on laptops.</td></tr>
    <tr><td><strong>Touchscreen</strong></td><td>Detects touch directly on a display.</td><td>Phones, tablets, kiosks and some laptops.</td></tr>
    <tr><td><strong>Microphone</strong></td><td>Captures sound and converts it into electronic/digital data.</td><td>Calls, voice recording, streaming and speech recognition.</td></tr>
    <tr><td><strong>Webcam</strong></td><td>Captures still images or video.</td><td>Video calls, recording and online meetings.</td></tr>
    <tr><td><strong>Scanner</strong></td><td>Captures a physical document or image.</td><td>Digitising documents, photos and forms.</td></tr>
    <tr><td><strong>Game controller</strong></td><td>Sends button, stick, trigger and motion input.</td><td>Games and interactive applications.</td></tr>
  </tbody></table></div>

  <h2>4. Keyboard: more than typing</h2>
  <p>A keyboard can send far more than letters and numbers. Modifier keys such as <strong>Ctrl</strong>, <strong>Alt</strong> and <strong>Shift</strong> combine with other keys to create commands. Function keys, arrow keys and special keys provide additional controls.</p>
  <p>For example, <strong>Ctrl + C</strong> usually copies selected content and <strong>Ctrl + V</strong> usually pastes it. These commands are processed by software after the keyboard sends the key input.</p>
  <h3>Practical check</h3>
  <ol><li>Open a text editor.</li><li>Type a short sentence.</li><li>Select part of the sentence.</li><li>Use a keyboard shortcut to copy and paste it.</li><li>Notice that the keyboard provided the input while the screen provided visual output.</li></ol>

  <h2>5. Mouse and touchpad</h2>
  <p>A mouse normally reports movement and button actions to the computer. Modern optical and laser mice use a sensor to detect movement rather than a traditional mechanical ball.</p>
  <p>A laptop touchpad performs a similar pointer-control role but detects finger movement and gestures on a flat surface. Two-finger scrolling, tapping and multi-finger gestures are examples of software interpreting touchpad input.</p>
  <p><strong>Important distinction:</strong> the pointer shown on the screen is output; the physical movement and click that control it are input.</p>

  <h2>6. Microphone and webcam</h2>
  <p>A microphone captures sound waves and converts them into an electrical signal that the computer can process. A webcam captures visual information and sends image frames to the computer.</p>
  <p>That means a video call can involve several devices at once: the microphone provides audio input, the webcam provides video input, the CPU and software process the data, and the speakers and display provide output.</p>

  <h2>7. Common output devices</h2>
  <div class="table-responsive"><table class="table table-bordered align-middle"><thead><tr><th>Device</th><th>What it outputs</th><th>Typical use</th></tr></thead><tbody>
    <tr><td><strong>Monitor</strong></td><td>Visual information such as text, images and video.</td><td>Everyday computing, design, gaming and editing.</td></tr>
    <tr><td><strong>Speakers</strong></td><td>Audio.</td><td>Music, videos, alerts, meetings and games.</td></tr>
    <tr><td><strong>Headphones</strong></td><td>Audio directly to the listener.</td><td>Private listening, calls and monitoring.</td></tr>
    <tr><td><strong>Printer</strong></td><td>Physical printed output.</td><td>Documents, forms, labels and photos.</td></tr>
    <tr><td><strong>Projector</strong></td><td>A larger projected visual image.</td><td>Presentations, classrooms and meetings.</td></tr>
    <tr><td><strong>Haptic device</strong></td><td>Physical vibration or tactile feedback.</td><td>Controllers, phones and accessibility interfaces.</td></tr>
  </tbody></table></div>

  <h2>8. Monitor basics</h2>
  <p>A monitor displays the graphical result of the computer's processing. Important specifications include <strong>resolution</strong>, <strong>refresh rate</strong>, <strong>panel technology</strong>, brightness, colour capability and connection type.</p>
  <ul>
    <li><strong>Resolution:</strong> the number of pixels used to form the image, such as 1920 × 1080.</li>
    <li><strong>Refresh rate:</strong> how many times per second the display can refresh, commonly expressed in Hz.</li>
    <li><strong>Connection:</strong> HDMI, DisplayPort and other interfaces carry video and sometimes audio.</li>
  </ul>
  <p>A higher specification is not automatically better for every person. The right display depends on the work being done, the computer's capabilities, desk setup and budget.</p>

  <h2>9. Speakers, headphones and audio output</h2>
  <p>Speakers and headphones convert an audio signal into sound. A computer may have several possible output routes, such as built-in speakers, a headphone jack, USB audio or wireless audio.</p>
  <p>If you cannot hear sound, check the selected output device and system volume before assuming the hardware is damaged. A muted application, incorrect output selection or disconnected cable can create the same symptom as a hardware fault.</p>

  <h2>10. Some devices are both input and output</h2>
  <p>Not every device fits neatly into only one category. A <strong>touchscreen</strong> is a classic example: it displays information (output) and detects touches (input).</p>
  <p>A multifunction printer can also scan a document into the computer as input and print a document as output. A network adapter sends and receives data, so it is another example of hardware that performs both directions of communication.</p>
  <div class="table-responsive"><table class="table table-bordered align-middle"><thead><tr><th>Device</th><th>Input</th><th>Output</th></tr></thead><tbody>
    <tr><td>Touchscreen</td><td>Touch and gestures</td><td>Images and interface</td></tr>
    <tr><td>Multifunction printer</td><td>Scanned documents</td><td>Printed documents</td></tr>
    <tr><td>Network adapter</td><td>Received network data</td><td>Transmitted network data</td></tr>
    <tr><td>Gaming headset</td><td>Microphone audio</td><td>Headphone audio</td></tr>
  </tbody></table></div>

  <h2>11. How input and output work together</h2>
  <p>Think of a computer as a communication loop:</p>
  <ol>
    <li><strong>Input:</strong> a user or sensor provides information.</li>
    <li><strong>Processing:</strong> the CPU and software interpret the information.</li>
    <li><strong>Storage, if needed:</strong> data may be saved temporarily or permanently.</li>
    <li><strong>Output:</strong> the result is shown, heard, printed or otherwise delivered.</li>
    <li><strong>New input:</strong> the user responds and the cycle continues.</li>
  </ol>
  <p>For example, when you search for something online, the keyboard or touchscreen provides the query, the computer processes your actions, network hardware communicates with the internet, and the monitor displays the returned page.</p>

  <h2>12. Troubleshooting input devices</h2>
  <p>When a keyboard, mouse, microphone or webcam stops working, troubleshoot from simple causes to more specific ones.</p>
  <ol>
    <li>Check whether the device has power or a battery.</li>
    <li>Check the cable, connector or wireless connection.</li>
    <li>Try another USB port when appropriate.</li>
    <li>Check whether the operating system detects the device.</li>
    <li>Check application permissions, especially for microphones and cameras.</li>
    <li>Test the device in another application.</li>
    <li>Restart the application or computer if the device is intermittently recognised.</li>
    <li>Only then investigate drivers or possible hardware failure.</li>
  </ol>

  <h2>13. Troubleshooting output devices</h2>
  <div class="table-responsive"><table class="table table-bordered align-middle"><thead><tr><th>Symptom</th><th>First checks</th></tr></thead><tbody>
    <tr><td>Monitor shows no image</td><td>Power, selected input, cable, computer output and display settings.</td></tr>
    <tr><td>No sound</td><td>Volume, mute state, selected output device, cable or wireless connection.</td></tr>
    <tr><td>Printer does not print</td><td>Power, paper, ink/toner, selected printer, queue and connection.</td></tr>
    <tr><td>Image looks wrong</td><td>Resolution, refresh rate, cable and graphics/display settings.</td></tr>
  </tbody></table></div>
  <div class="alert alert-primary"><strong>Smart troubleshooting rule:</strong> Change one thing at a time. If you unplug three cables, reinstall a driver and change five settings together, you may fix the problem without learning what caused it.</div>

  <h2>14. Wired vs wireless peripherals</h2>
  <p>Wired devices usually connect through USB, HDMI, DisplayPort, audio connectors or other physical interfaces. Wireless devices may use Bluetooth, Wi-Fi or a dedicated wireless receiver.</p>
  <p>Wireless does not automatically mean better. Wired devices can be simple and reliable, while wireless devices can reduce cable clutter and improve flexibility. Choose based on the task rather than the label.</p>

  <h2>15. Accessibility and alternative input</h2>
  <p>Input and output are not limited to a standard keyboard, mouse and monitor. Voice input, screen readers, switch controls, eye-tracking systems, alternative keyboards and haptic feedback can make computers usable for people with different needs.</p>
  <p>This is an important lesson in computer basics: <strong>the goal of a computer interface is communication, not a particular physical device.</strong></p>

  <h2>Real-world example: joining an online meeting</h2>
  <p>Consider a laptop video meeting. The webcam captures your video, the microphone captures your voice, and the keyboard or mouse lets you control the meeting interface. The CPU and software process the data. Your display shows the other participants and your speakers or headphones play their voices.</p>
  <p>One everyday task therefore uses multiple input and output devices simultaneously.</p>

  <h2>Practical activity: map your own computer</h2>
  <ol>
    <li>Look at your computer or laptop and list every physical device you use with it.</li>
    <li>Mark each item as <strong>Input</strong>, <strong>Output</strong> or <strong>Both</strong>.</li>
    <li>Write down how each device connects: USB, Bluetooth, HDMI, DisplayPort, Wi-Fi, audio jack or another method.</li>
    <li>Pick one input device and one output device and explain what happens from the moment you interact with it until you see or hear the result.</li>
    <li>Disconnect and reconnect one non-critical peripheral, then observe how the operating system responds.</li>
  </ol>

  <h2>Common beginner mistakes</h2>
  <ul>
    <li>Thinking that everything visible on a screen is an input device. The display itself is normally output.</li>
    <li>Assuming a device is broken before checking power, cables and selected settings.</li>
    <li>Forgetting that touchscreens and multifunction devices can perform both input and output.</li>
    <li>Confusing a connector with the device itself. USB, HDMI and DisplayPort are connection interfaces, not complete device categories.</li>
    <li>Buying a peripheral based only on specifications without checking compatibility with the computer and intended workload.</li>
  </ul>

  <h2>Quick self-check</h2>
  <ol>
    <li>What is the difference between an input device and an output device?</li>
    <li>Why is a keyboard an input device?</li>
    <li>Why is a monitor an output device?</li>
    <li>Give two examples of devices that can perform both input and output.</li>
    <li>What should you check first when a peripheral suddenly stops working?</li>
    <li>How can a video meeting use input and output devices at the same time?</li>
  </ol>

  <h2>Key takeaway</h2>
  <p><strong>Input devices give the computer information; output devices communicate the computer's results back to the world.</strong> Once you understand this flow, everyday hardware becomes easier to use, compare and troubleshoot. The most useful skill is not memorising a list of devices—it is recognising what information is entering the system, what is leaving it, and where the processing happens in between.</p>
</article>
HTML;
}

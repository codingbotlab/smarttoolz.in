<?php
declare(strict_types=1);

/**
 * Course 16 — Computer Basics Mastery
 * Lesson 21 — Wi-Fi Basics
 */
function lh_course16_lesson21_computer_basics_override(array $lesson, int $position): ?string
{
    if ((string)($lesson['slug'] ?? '') !== 'course-16-lesson-21') {
        return null;
    }

    return <<<'HTML'
<section class="lh-topic">
  <h2>📶 What Is Wi-Fi?</h2>
  <p><strong>Wi-Fi</strong> is a wireless networking technology that connects laptops, phones, tablets, TVs, and other devices to a local network without an Ethernet cable. At home, that local network usually connects to the Internet through a router or access point.</p>
  <div class="lh-callout"><strong>Most important idea:</strong> Wi-Fi and the Internet are not the same thing. Wi-Fi is the wireless connection between your device and the local network; the Internet is the larger online network reached through that connection.</div>
</section>

<section class="lh-topic">
  <h2>🏠 Router, Modem, and Access Point</h2>
  <ul>
    <li><strong>Router:</strong> Manages traffic between devices on the local network and shares the Internet connection.</li>
    <li><strong>Access Point:</strong> Provides Wi-Fi connectivity so wireless devices can join a network. Many home routers have a built-in access point.</li>
    <li><strong>Modem/ONT:</strong> Connects the home network to the service provided by the ISP. In many homes, the modem/ONT and router are combined into one device.</li>
  </ul>
</section>

<section class="lh-topic">
  <h2>📡 SSID and Wi-Fi Password</h2>
  <p><strong>SSID</strong> is the name of a wireless network, such as <code>Home_WiFi</code>. When Windows lists available wireless networks, the SSID is the name you see.</p>
  <p><strong>Password</strong> is used to authenticate your device on the protected network. Always make sure you are selecting the correct SSID, especially when several nearby networks have similar names.</p>
</section>

<section class="lh-topic">
  <h2>⚡ 2.4 GHz, 5 GHz, and 6 GHz</h2>
  <table class="table table-bordered align-middle">
    <thead><tr><th>Band</th><th>Typical advantage</th><th>Keep in mind</th></tr></thead>
    <tbody>
      <tr><td><strong>2.4 GHz</strong></td><td>Longer range and often better coverage through walls</td><td>Usually more interference and lower throughput</td></tr>
      <tr><td><strong>5 GHz</strong></td><td>Often provides higher speeds with less congestion</td><td>Distance and walls can reduce the signal more quickly</td></tr>
      <tr><td><strong>6 GHz</strong></td><td>Modern compatible devices can get additional capacity, lower congestion, and low latency</td><td>Requires compatible router and device support and generally has shorter range</td></tr>
    </tbody>
  </table>
  <p>Do not assume that <strong>5 GHz is always better</strong>. Distance, walls, interference, router placement, and device support all affect which band works best.</p>
</section>

<section class="lh-topic">
  <h2>🪟 Connecting to Wi-Fi in Windows 11</h2>
  <ol>
    <li>Open the <strong>Network / Sound / Battery</strong> area on the taskbar.</li>
    <li>Open the Wi-Fi network list or Wi-Fi connection controls.</li>
    <li>Select a network that you recognize and trust.</li>
    <li>Select <strong>Connect</strong> and enter the network password.</li>
    <li>If appropriate, enable <strong>Connect automatically</strong> so Windows can reconnect when the network is available.</li>
  </ol>
  <div class="lh-callout"><strong>Safety:</strong> Connect only to networks you recognize or trust. Be especially careful when using public Wi-Fi for sensitive activities.</div>
</section>

<section class="lh-topic">
  <h2>📶 Why Does Wi-Fi Signal Strength Change?</h2>
  <p>Wi-Fi performance is affected by distance, walls, furniture, metal objects, and other wireless devices. A router placed inside a cabinet or in a corner can provide weaker coverage.</p>
  <ul>
    <li>Move closer to the router and compare the signal.</li>
    <li>Expect more signal loss when there are many walls or obstacles between the device and access point.</li>
    <li>When possible, place the router in a more central and elevated location.</li>
    <li>Test the connection in more than one location before deciding what is causing the problem.</li>
  </ul>
</section>

<section class="lh-topic">
  <h2>🚨 Wi-Fi Connected but There Is No Internet</h2>
  <p>This often means the PC is connected to the router but communication from the router to the Internet is not working. Troubleshoot it step by step:</p>
  <ol>
    <li>Confirm that Windows shows the Wi-Fi network as <strong>Connected</strong>.</li>
    <li>Make sure Airplane mode is turned off.</li>
    <li>Test the same Wi-Fi network on another phone or laptop.</li>
    <li>Turn Wi-Fi off and back on, then reconnect.</li>
    <li>Use <strong>Forget</strong> on the network and connect again with the password.</li>
    <li>If necessary, restart the modem/router and wait for it to finish starting up.</li>
    <li>Run Windows network diagnostics or the Get Help network troubleshooter.</li>
    <li>If the problem affects only one PC, check the Wi-Fi adapter, driver, and Windows updates.</li>
  </ol>
</section>

<section class="lh-topic">
  <h2>🛠️ Common Wi-Fi Problems</h2>
  <table class="table table-bordered align-middle">
    <thead><tr><th>Problem</th><th>First checks</th></tr></thead>
    <tbody>
      <tr><td>Wi-Fi network is not visible</td><td>Check Wi-Fi is on, Airplane mode is off, the device is within range, and the wireless adapter is working.</td></tr>
      <tr><td>Password is rejected</td><td>Confirm the correct SSID and carefully enter the current password.</td></tr>
      <tr><td>Frequent disconnects</td><td>Check signal strength, distance, interference, adapter drivers, and updates.</td></tr>
      <tr><td>Connected but no Internet</td><td>Test another device to determine whether the issue is limited to the PC or affects the whole network.</td></tr>
      <tr><td>Very slow speed</td><td>Test closer to the router and compare available 2.4 GHz and 5 GHz networks when both are supported.</td></tr>
    </tbody>
  </table>
</section>

<section class="lh-topic">
  <h2>🔐 Public Wi-Fi Safety</h2>
  <ul>
    <li>Do not blindly trust a network name; verify the official network name when possible.</li>
    <li>Be cautious with banking, passwords, and other sensitive activities on unknown public networks.</li>
    <li>Keep file sharing and network discovery appropriately restricted on public networks.</li>
    <li>Prefer HTTPS websites and trusted applications.</li>
    <li>Do not publicly expose or casually share your private Wi-Fi password.</li>
  </ul>
</section>

<section class="lh-topic">
  <h2>🎯 Practical Activity</h2>
  <p>Try this exercise on your Windows PC:</p>
  <ol>
    <li>Open the available Wi-Fi network list and identify your own network SSID.</li>
    <li>Note the current signal strength.</li>
    <li>Move closer to the router and check the signal again.</li>
    <li>If both 2.4 GHz and 5 GHz networks are available, test both from the same location and compare speed or response time.</li>
    <li>Disconnect and reconnect to the network. If <strong>Connect automatically</strong> is enabled, observe what happens when the network becomes available again.</li>
  </ol>
  <div class="lh-callout"><strong>Goal:</strong> Do not just learn that Wi-Fi works. Learn to distinguish signal strength, frequency band, local network connectivity, and Internet access.</div>
</section>

<section class="lh-topic">
  <h2>❌ Common Mistakes</h2>
  <ul>
    <li>Assuming that being connected to Wi-Fi automatically means the Internet is working.</li>
    <li>Connecting to the wrong or fake SSID because its name looks familiar.</li>
    <li>Blaming every slow connection on the ISP without testing the local Wi-Fi signal.</li>
    <li>Placing the router inside a cabinet, behind heavy obstacles, or in a poor corner location.</li>
    <li>Immediately using network reset or uninstalling drivers without first trying simpler checks.</li>
    <li>Using unknown public Wi-Fi for sensitive activities without taking appropriate precautions.</li>
  </ul>
</section>

<section class="lh-topic">
  <h2>🧠 Quick Self-Check</h2>
  <ol>
    <li>What is the difference between Wi-Fi and the Internet?</li>
    <li>What does SSID mean?</li>
    <li>Which Wi-Fi band generally provides longer range: 2.4 GHz or 5 GHz?</li>
    <li>What should you test if Wi-Fi says Connected but websites do not load?</li>
    <li>Why can moving closer to the router improve Wi-Fi performance?</li>
  </ol>
  <div class="lh-callout">
    <strong>Answers:</strong><br>
    1. Wi-Fi connects your device to the local wireless network; the Internet is the wider online network reached through it.<br>
    2. SSID is the name of a wireless network.<br>
    3. 2.4 GHz generally has longer range.<br>
    4. Test another device, reconnect to Wi-Fi, check the router/ISP connection, and run Windows network diagnostics.<br>
    5. A shorter distance usually means a stronger signal and fewer effects from walls and obstacles.
  </div>
</section>

<section class="lh-topic">
  <h2>✅ Key Takeaway</h2>
  <p>Wi-Fi is the wireless bridge between your device and a local network. Good Wi-Fi troubleshooting means separating <strong>signal strength</strong>, <strong>Wi-Fi connection</strong>, <strong>router/network health</strong>, and <strong>Internet access</strong> instead of treating them as one problem.</p>
</section>
HTML;
}

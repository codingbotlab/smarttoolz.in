<?php
declare(strict_types=1);

/**
 * Computer Basics — Lesson 22: Web Browsers
 */
function lh_course16_lesson22_computer_basics_override(array $lesson, int $position): ?string
{
    if ((string)($lesson['slug'] ?? '') !== 'course-16-lesson-22') {
        return null;
    }

    ob_start();
    ?>
    <section class="lh-section">
        <h2>🌐 What Is a Web Browser?</h2>
        <p>A <strong>web browser</strong> is an application used to open and interact with websites and web-based services. Popular browsers include Microsoft Edge, Google Chrome, Mozilla Firefox and Apple Safari.</p>
        <div class="lh-callout"><strong>Simple idea:</strong> the Internet is the huge network; the Web is a collection of online resources; the browser is one of the main tools you use to access those web pages.</div>
    </section>

    <section class="lh-section">
        <h2>🧭 The Main Parts of a Browser</h2>
        <div class="table-responsive"><table class="table table-bordered align-middle">
            <thead><tr><th>Part</th><th>What it does</th></tr></thead>
            <tbody>
                <tr><td><strong>Tab</strong></td><td>Lets you keep multiple web pages open in one browser window.</td></tr>
                <tr><td><strong>Address bar</strong></td><td>Enter a website address such as <code>https://smarttoolz.in</code> or type a search query.</td></tr>
                <tr><td><strong>Back / Forward</strong></td><td>Move through pages you recently visited.</td></tr>
                <tr><td><strong>Refresh</strong></td><td>Requests the current page again.</td></tr>
                <tr><td><strong>Bookmarks / Favorites</strong></td><td>Save useful websites so you can open them quickly later.</td></tr>
                <tr><td><strong>History</strong></td><td>Shows pages you have visited in the browser.</td></tr>
                <tr><td><strong>Downloads</strong></td><td>Shows files downloaded through the browser.</td></tr>
                <tr><td><strong>Settings</strong></td><td>Controls privacy, appearance, permissions, search, downloads and other browser behavior.</td></tr>
            </tbody>
        </table></div>
    </section>

    <section class="lh-section">
        <h2>🔗 Website Address vs Search</h2>
        <p>The address bar can do two jobs:</p>
        <ul>
            <li>Enter a direct URL, such as <code>https://www.microsoft.com</code>.</li>
            <li>Enter words you want to search for when you do not know the exact address.</li>
        </ul>
        <div class="lh-callout"><strong>Tip:</strong> check the spelling of an important website address before entering sensitive information. A fake website can look very similar to a real one.</div>
    </section>

    <section class="lh-section">
        <h2>📑 Working with Tabs</h2>
        <ul>
            <li><strong>Ctrl + T:</strong> open a new tab.</li>
            <li><strong>Ctrl + W:</strong> close the current tab.</li>
            <li><strong>Ctrl + Shift + T:</strong> reopen a recently closed tab.</li>
            <li><strong>Ctrl + Tab:</strong> move to the next tab.</li>
            <li><strong>Ctrl + L:</strong> select the address bar.</li>
        </ul>
        <p>Tabs are useful when researching a topic. Instead of opening many browser windows, keep related pages together and close tabs you no longer need.</p>
    </section>

    <section class="lh-section">
        <h2>⭐ Bookmarks, History & Downloads</h2>
        <h3>Bookmarks / Favorites</h3>
        <p>Use bookmarks for websites you expect to revisit. Create folders such as <strong>Work</strong>, <strong>Learning</strong> and <strong>Tools</strong> to keep them organized.</p>
        <h3>History</h3>
        <p>History helps you find a page you visited earlier. It is also useful for understanding what the browser has stored locally about your browsing activity.</p>
        <h3>Downloads</h3>
        <p>Downloaded files normally appear in the browser's Downloads area and often in the Windows <strong>Downloads</strong> folder. Always check what a downloaded file is before opening it.</p>
    </section>

    <section class="lh-section">
        <h2>🔒 Browser Safety Basics</h2>
        <ul>
            <li>Prefer websites using <strong>HTTPS</strong> for sensitive activities.</li>
            <li>Do not enter passwords or payment details into suspicious pages.</li>
            <li>Be careful with unexpected pop-ups, fake warnings and urgent messages.</li>
            <li>Download software from trusted sources instead of random download sites.</li>
            <li>Keep the browser updated so security fixes can be applied.</li>
            <li>Review website permissions such as camera, microphone and location when a site requests them.</li>
        </ul>
        <div class="lh-callout"><strong>Remember:</strong> the lock/HTTPS indicator does not prove that a website is honest. It mainly indicates that the connection is protected with HTTPS; you still need to verify that you are on the correct website.</div>
    </section>

    <section class="lh-section">
        <h2>⚙️ Browser Settings You Should Know</h2>
        <p>Most modern browsers provide settings for:</p>
        <ul>
            <li>Default search engine</li>
            <li>Homepage and startup behavior</li>
            <li>Downloads location</li>
            <li>Privacy and tracking protection</li>
            <li>Cookies and site data</li>
            <li>Saved passwords and autofill</li>
            <li>Website permissions</li>
            <li>Extensions or add-ons</li>
            <li>Browser updates</li>
        </ul>
        <p>Do not change advanced settings just because a website tells you to. If a page asks you to install an unknown extension or allow unusual permissions, stop and verify the request first.</p>
    </section>

    <section class="lh-section">
        <h2>🛠️ Common Browser Problems</h2>
        <div class="table-responsive"><table class="table table-bordered align-middle">
            <thead><tr><th>Problem</th><th>What to try</th></tr></thead>
            <tbody>
                <tr><td>Page will not load</td><td>Check your Internet connection, refresh the page, then try another website.</td></tr>
                <tr><td>Browser is very slow</td><td>Close unused tabs, disable unnecessary extensions and restart the browser.</td></tr>
                <tr><td>Website looks broken</td><td>Refresh, check zoom, try a private window or another browser, and check whether the site itself is having an issue.</td></tr>
                <tr><td>Pop-ups keep appearing</td><td>Do not click suspicious prompts. Review site permissions and remove unwanted extensions.</td></tr>
                <tr><td>Downloaded file cannot be found</td><td>Open the browser's Downloads page and check the configured download folder.</td></tr>
                <tr><td>Browser keeps crashing</td><td>Restart it, update it, disable recently installed extensions and check available system resources.</td></tr>
            </tbody>
        </table></div>
    </section>

    <section class="lh-section">
        <h2>🧪 Practical Activity</h2>
        <ol>
            <li>Open your preferred web browser.</li>
            <li>Open a new tab and visit a trusted website.</li>
            <li>Open two more tabs and practice switching between them.</li>
            <li>Use <strong>Ctrl + L</strong> to select the address bar and search for a simple topic.</li>
            <li>Bookmark a useful website.</li>
            <li>Open your browsing history and locate the page you just visited.</li>
            <li>Open the Downloads section and identify where downloaded files are stored.</li>
            <li>Close one tab with <strong>Ctrl + W</strong>, then reopen it with <strong>Ctrl + Shift + T</strong>.</li>
        </ol>
    </section>

    <section class="lh-section">
        <h2>❌ Common Mistakes</h2>
        <ul>
            <li>Confusing a browser with the Internet itself.</li>
            <li>Opening dozens of tabs and leaving them running unnecessarily.</li>
            <li>Assuming HTTPS automatically means the website is trustworthy.</li>
            <li>Installing random extensions because a pop-up says they are required.</li>
            <li>Downloading files without checking the source or file type.</li>
            <li>Ignoring browser updates for long periods.</li>
            <li>Sharing saved passwords or browser profiles with other people.</li>
        </ul>
    </section>

    <section class="lh-section">
        <h2>🧠 Quick Self-Check</h2>
        <ol>
            <li>What is the main purpose of a web browser?</li>
            <li>What is the address bar used for?</li>
            <li>What does Ctrl + T do?</li>
            <li>What is a bookmark?</li>
            <li>Does HTTPS alone prove that a website is trustworthy?</li>
            <li>Where would you normally look for a recently downloaded file?</li>
        </ol>
        <details class="mt-3"><summary><strong>Show answers</strong></summary>
            <div class="lh-callout mt-3">
                <ol>
                    <li>It lets you access and interact with websites and web content.</li>
                    <li>It is used to enter a URL or search query.</li>
                    <li>It opens a new browser tab.</li>
                    <li>A saved website link for quick access later.</li>
                    <li>No. You should still verify the site's address and legitimacy.</li>
                    <li>In the browser's Downloads area and usually the Windows Downloads folder.</li>
                </ol>
            </div>
        </details>
    </section>

    <section class="lh-section">
        <h2>🎯 Key Takeaway</h2>
        <p>A browser is your everyday tool for accessing the Web. Learn the address bar, tabs, bookmarks, history and downloads first. Then build good habits around updates, permissions, downloads and website verification. These simple skills make browsing both faster and safer.</p>
    </section>
    <?php
    return ob_get_clean();
}

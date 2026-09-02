<?php
declare(strict_types=1);

/**
 * Course 16 — Computer Basics Mastery
 * Lesson 27 — Password Security
 */
function lh_course16_lesson27_computer_basics_override(array $lesson, int $position): ?string
{
    if ((string)($lesson['slug'] ?? '') !== 'course-16-lesson-27') {
        return null;
    }

    return <<<'HTML'
<section class="lh-topic">
  <h2>🔐 What Is Password Security?</h2>
  <p><strong>Password security</strong> means protecting the credentials that control access to your accounts and information. A password is only one part of account security; modern accounts can also use multi-factor authentication (MFA), passkeys, security keys, and other protections.</p>
  <div class="lh-callout"><strong>Core rule:</strong> never treat a password as something to share. Your password should be a secret known only to you and the authentication system that verifies it.</div>
</section>

<section class="lh-topic">
  <h2>🧩 Why Passwords Matter</h2>
  <p>A compromised password can give an attacker access to email, social media, cloud storage, shopping accounts, or other services. One reused password can be especially dangerous because an attacker may try credentials stolen from one service on other services.</p>
  <p>NIST recommends using distinct passwords for different services to reduce the risk of <strong>password stuffing</strong>, where a stolen password is tried against other accounts. citeturn0search24</p>
</section>

<section class="lh-topic">
  <h2>📏 Long and Unique Beats Clever and Reused</h2>
  <p>When you must create a password, prioritize <strong>length and uniqueness</strong>. A memorable passphrase made from several unrelated words can be easier to remember than a short password packed with forced symbols.</p>
  <ul>
    <li>Use a long password or passphrase.</li>
    <li>Use a different password for every important account.</li>
    <li>Avoid names, birthdays, phone numbers, usernames, or predictable personal information.</li>
    <li>Do not use common passwords or simple patterns such as <code>Password123!</code>.</li>
    <li>Do not make tiny variations of the same password for different websites.</li>
  </ul>
  <p>Current NIST consumer guidance recommends a password of at least 15 characters when a password is required, while its broader digital-identity guidance emphasizes allowing long passphrases and avoiding arbitrary composition rules. citeturn0search0turn0search24</p>
</section>

<section class="lh-topic">
  <h2>🗝️ Use a Password Manager</h2>
  <p>A <strong>password manager</strong> can generate and securely store unique passwords so you do not have to memorize every password yourself.</p>
  <ul>
    <li>Generate a different password for each service.</li>
    <li>Store passwords securely instead of writing them on paper or in an ordinary text file.</li>
    <li>Use autofill carefully and verify the website before submitting credentials.</li>
    <li>Protect the password manager itself with strong authentication and MFA when available.</li>
  </ul>
  <div class="lh-callout"><strong>Important:</strong> your password manager becomes highly important because it protects many other credentials. Secure its main account carefully.</div>
  <p>NIST specifically recommends password managers for accounts that still require passwords. citeturn0search0</p>
</section>

<section class="lh-topic">
  <h2>📱 Multi-Factor Authentication (MFA)</h2>
  <p><strong>MFA</strong> requires more than one type of authentication. For example, you may enter a password and then approve a sign-in through an authenticator app or another security method.</p>
  <p>MFA adds another layer of protection if your password is stolen. NIST recommends enabling MFA when it is available, particularly for important accounts. citeturn0search0turn0search5</p>
  <table class="table table-bordered align-middle">
    <thead><tr><th>Method</th><th>Example</th><th>Security idea</th></tr></thead>
    <tbody>
      <tr><td>Something you know</td><td>Password or PIN</td><td>A secret you remember.</td></tr>
      <tr><td>Something you have</td><td>Phone, security key, authenticator device</td><td>A physical device or credential you possess.</td></tr>
      <tr><td>Something you are</td><td>Fingerprint or face recognition</td><td>A biometric characteristic.</td></tr>
    </tbody>
  </table>
</section>

<section class="lh-topic">
  <h2>🪪 Passkeys</h2>
  <p><strong>Passkeys</strong> are a newer way to sign in without typing a traditional password. They use cryptographic credentials stored on supported devices and can be unlocked using a device PIN or biometric method.</p>
  <p>Unlike traditional passwords, passkeys are designed to resist common phishing attacks because there is no password for a fake website to collect. NIST identifies passkeys as an important passwordless authentication option. citeturn0search0</p>
</section>

<section class="lh-topic">
  <h2>🎣 Phishing Can Steal Good Passwords</h2>
  <p>Even a long, unique password can be exposed if you type it into a fake login page. Attackers may send messages that imitate banks, email providers, social networks, delivery companies, or other trusted services.</p>
  <ul>
    <li>Do not log in through unexpected links in messages.</li>
    <li>Check the website address before entering credentials.</li>
    <li>Be suspicious of urgent threats such as “your account will be closed today.”</li>
    <li>Never provide a password or MFA code to someone who asks for it by phone, chat, or email.</li>
    <li>If you are unsure, open the official app or type the known website address yourself.</li>
  </ul>
  <p>NIST identifies phishing as a common way attackers obtain passwords by tricking users into entering them on fake websites. citeturn0search0</p>
</section>

<section class="lh-topic">
  <h2>🚨 Never Share MFA Codes</h2>
  <p>An attacker who has your password may try to trick you into revealing the second factor too. This is sometimes done by pretending to be support staff or by asking you to approve a login you did not start.</p>
  <div class="lh-callout"><strong>If you did not initiate the sign-in, do not approve it.</strong> Do not read a one-time code aloud to another person.</div>
</section>

<section class="lh-topic">
  <h2>🔄 When Should You Change a Password?</h2>
  <p>Do not change a strong password merely on a fixed schedule if there is no reason to do so. Change it when you suspect compromise, after a confirmed breach affecting the account, or when a service requires a reset for a security reason.</p>
  <p>NIST guidance does not recommend arbitrary periodic password changes; evidence of compromise is a key reason to force a change. citeturn0search4</p>
</section>

<section class="lh-topic">
  <h2>🧯 What To Do If a Password Is Compromised</h2>
  <ol>
    <li>Change the compromised password immediately using the legitimate service.</li>
    <li>If you reused it elsewhere, change those accounts too.</li>
    <li>Enable MFA or a passkey if available.</li>
    <li>Review recent sign-ins and account activity.</li>
    <li>Remove unfamiliar recovery methods or devices.</li>
    <li>Check for unauthorized changes such as forwarding rules or new account permissions where relevant.</li>
    <li>Contact the service through its official support channel if you cannot regain control.</li>
  </ol>
</section>

<section class="lh-topic">
  <h2>📧 Protect Your Most Important Accounts First</h2>
  <p>Not every account has the same impact. Your primary email account is especially important because it may be used to reset passwords for many other services.</p>
  <p>Prioritize strong, unique authentication for:</p>
  <ul>
    <li>Primary email</li>
    <li>Banking and financial accounts</li>
    <li>Cloud storage</li>
    <li>Social media</li>
    <li>Work or school accounts</li>
    <li>Password manager account</li>
  </ul>
</section>

<section class="lh-topic">
  <h2>⚠️ Common Password Mistakes</h2>
  <table class="table table-bordered align-middle">
    <thead><tr><th>Mistake</th><th>Why it is risky</th><th>Better approach</th></tr></thead>
    <tbody>
      <tr><td>Same password everywhere</td><td>One breach can expose multiple accounts.</td><td>Use unique passwords.</td></tr>
      <tr><td>Short predictable password</td><td>Easier to guess or crack.</td><td>Use a long passphrase or generated password.</td></tr>
      <tr><td>Password based on personal details</td><td>Information may be discoverable online.</td><td>Use unrelated words or a password manager.</td></tr>
      <tr><td>Saving passwords in an ordinary text file</td><td>Anyone who gets the file may see everything.</td><td>Use a reputable password manager.</td></tr>
      <tr><td>Sharing OTP/MFA codes</td><td>The code may complete an attacker's login.</td><td>Keep codes private.</td></tr>
      <tr><td>Logging in from an unexpected link</td><td>Could be a phishing page.</td><td>Use the official app or known website.</td></tr>
    </tbody>
  </table>
</section>

<section class="lh-topic">
  <h2>🎯 Practical Activity</h2>
  <p>Improve the security of your accounts without exposing any real passwords in this activity.</p>
  <ol>
    <li>Make a list of your most important accounts without writing their passwords.</li>
    <li>Mark which accounts reuse a password. Plan to replace reused passwords with unique ones.</li>
    <li>Check whether your primary email supports MFA and enable it if you have not already.</li>
    <li>Check whether your important services support passkeys.</li>
    <li>If you do not use a password manager, research the security features of a reputable option before choosing one.</li>
    <li>Review recent account activity and recovery options for your most important account.</li>
  </ol>
  <div class="lh-callout"><strong>Safety rule:</strong> never type an actual password into this lesson, a chat message, a screenshot, or a practice worksheet.</div>
</section>

<section class="lh-topic">
  <h2>🧠 Quick Self-Check</h2>
  <ol>
    <li>Why is password reuse dangerous?</li>
    <li>What is a password manager?</li>
    <li>What does MFA add to account security?</li>
    <li>What is a passkey?</li>
    <li>Why can phishing defeat even a strong password?</li>
    <li>When should you change a password?</li>
    <li>Which account should you protect especially carefully because it can reset other accounts?</li>
  </ol>
  <details class="mt-3"><summary><strong>Show answers</strong></summary>
    <div class="lh-callout mt-3">
      <ol>
        <li>A stolen password from one service can be tried against other services.</li>
        <li>An app that securely stores and can generate unique passwords so you do not have to memorize them all.</li>
        <li>It requires an additional authentication factor, making a stolen password less useful by itself.</li>
        <li>A passwordless authentication credential based on cryptographic keys stored on a supported device.</li>
        <li>A fake login page can trick you into giving the password directly to the attacker.</li>
        <li>When it is suspected or known to be compromised, or when a service requires a security reset.</li>
        <li>Your primary email account is especially important because it can often be used for password recovery.</li>
      </ol>
    </div>
  </details>
</section>

<section class="lh-topic">
  <h2>✅ Key Takeaway</h2>
  <p><strong>Use long, unique credentials, protect them with a password manager when appropriate, enable MFA or passkeys, and never share passwords or authentication codes.</strong> Good password security is not just about inventing a complicated password—it is about protecting the entire sign-in process.</p>
</section>
HTML;
}

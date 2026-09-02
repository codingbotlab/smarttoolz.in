<?php
declare(strict_types=1);

/** Course 16 — Computer Basics Mastery — Lesson 24 — Email Basics */
function lh_course16_lesson24_computer_basics_override(array $lesson, int $position): ?string
{
    if ((string)($lesson['slug'] ?? '') !== 'course-16-lesson-24') return null;

    return <<<'HTML'
<section class="lh-topic">
  <h2>📧 What Is Email?</h2>
  <p><strong>Email</strong> (electronic mail) is a way to send and receive digital messages over the Internet. An email can contain text, links, photos, documents, and other files.</p>
  <div class="lh-callout"><strong>Think of email like digital post:</strong> your email address identifies your mailbox, the message is the letter, and an attachment is a file sent along with it.</div>
</section>

<section class="lh-topic">
  <h2>🔤 Understanding an Email Address</h2>
  <p>A typical address looks like <code>name@example.com</code>.</p>
  <table class="table table-bordered align-middle"><thead><tr><th>Part</th><th>Example</th><th>Meaning</th></tr></thead><tbody>
    <tr><td>Local part</td><td><code>name</code></td><td>Identifies the mailbox or account.</td></tr>
    <tr><td><code>@</code></td><td><code>@</code></td><td>Separates the mailbox from the domain.</td></tr>
    <tr><td>Domain</td><td><code>example.com</code></td><td>Identifies the email service or organization.</td></tr>
  </tbody></table>
  <p>Email addresses are precise. A single wrong character can cause delivery to fail or send a message to the wrong person.</p>
</section>

<section class="lh-topic">
  <h2>📥 Know Your Mail Folders</h2>
  <table class="table table-bordered align-middle"><thead><tr><th>Folder</th><th>Purpose</th></tr></thead><tbody>
    <tr><td><strong>Inbox</strong></td><td>Messages you receive.</td></tr>
    <tr><td><strong>Sent</strong></td><td>Messages you have sent.</td></tr>
    <tr><td><strong>Drafts</strong></td><td>Messages started but not sent yet.</td></tr>
    <tr><td><strong>Spam/Junk</strong></td><td>Unwanted or potentially suspicious messages.</td></tr>
    <tr><td><strong>Trash/Deleted</strong></td><td>Messages you deleted.</td></tr>
    <tr><td><strong>Archive</strong></td><td>Keeps a message without leaving it in the inbox.</td></tr>
  </tbody></table>
</section>

<section class="lh-topic">
  <h2>✍️ Writing a New Email</h2>
  <p>The main fields are <strong>To</strong>, <strong>Cc</strong>, <strong>Bcc</strong>, <strong>Subject</strong>, and the message body.</p>
  <ol>
    <li>Select <strong>Compose</strong>, <strong>New Email</strong>, or a similar button.</li>
    <li>Enter the recipient in <strong>To</strong>.</li>
    <li>Add Cc or Bcc recipients only when necessary.</li>
    <li>Write a short, useful <strong>Subject</strong>.</li>
    <li>Write the message body.</li>
    <li>Add attachments if needed.</li>
    <li>Review recipients, subject, message, and attachments before selecting <strong>Send</strong>.</li>
  </ol>
  <div class="lh-callout"><strong>Best habit:</strong> check the <strong>To</strong> field one final time before sending.</div>
</section>

<section class="lh-topic">
  <h2>👥 To, Cc, and Bcc</h2>
  <table class="table table-bordered align-middle"><thead><tr><th>Field</th><th>Use it when...</th></tr></thead><tbody>
    <tr><td><strong>To</strong></td><td>The person is a main recipient expected to read or act.</td></tr>
    <tr><td><strong>Cc</strong></td><td>The person should receive a copy mainly for awareness.</td></tr>
    <tr><td><strong>Bcc</strong></td><td>Recipients should receive the message without seeing the Bcc recipient list.</td></tr>
  </tbody></table>
  <p>For normal conversations, fewer recipients often means a clearer and safer email.</p>
</section>

<section class="lh-topic">
  <h2>📝 Write a Useful Subject</h2>
  <p>A good subject tells the recipient what the email is about before they open it.</p>
  <ul>
    <li>Good: <code>Computer Basics Lesson 24 Feedback</code></li>
    <li>Good: <code>Invoice for September — SmartToolz</code></li>
    <li>Weak: <code>Hi</code></li>
    <li>Weak: <code>Important</code></li>
  </ul>
</section>

<section class="lh-topic">
  <h2>📎 Email Attachments</h2>
  <p>An <strong>attachment</strong> is a file sent with an email, such as a PDF, photo, spreadsheet, or document.</p>
  <ol>
    <li>Compose the email.</li>
    <li>Select the attachment/paperclip option.</li>
    <li>Choose the file.</li>
    <li>Wait for the upload to finish if necessary.</li>
    <li>Check the file name before sending.</li>
  </ol>
  <div class="lh-callout"><strong>Remember:</strong> attachment size limits depend on the email service. If a file is too large, use an approved cloud-storage link when appropriate.</div>
</section>

<section class="lh-topic">
  <h2>🛡️ Handle Attachments Safely</h2>
  <p>Attachments can contain harmful software. Never open an unexpected file simply because the message sounds urgent.</p>
  <ul>
    <li>Check the real sender, not only the display name.</li>
    <li>Be cautious with unexpected ZIP files, executable files, scripts, or password-protected documents.</li>
    <li>If you were not expecting the file, confirm with the sender through a trusted channel.</li>
    <li>Keep your operating system and security software updated.</li>
    <li>Do not disable security warnings to open a suspicious file.</li>
  </ul>
</section>

<section class="lh-topic">
  <h2>↩️ Reply, Reply All, and Forward</h2>
  <table class="table table-bordered align-middle"><thead><tr><th>Action</th><th>Meaning</th></tr></thead><tbody>
    <tr><td><strong>Reply</strong></td><td>Responds to the relevant sender.</td></tr>
    <tr><td><strong>Reply All</strong></td><td>Responds to the sender and the other appropriate recipients.</td></tr>
    <tr><td><strong>Forward</strong></td><td>Sends the message to a new recipient.</td></tr>
  </tbody></table>
  <p>Use <strong>Reply All</strong> only when everyone needs your answer. Before forwarding, check the old conversation for private information or attachments that the new recipient should not see.</p>
</section>

<section class="lh-topic">
  <h2>🔎 Search and Organize Email</h2>
  <ul>
    <li>Search by sender when looking for messages from one person.</li>
    <li>Search by subject or important words.</li>
    <li>Use folders, labels, or categories for projects, bills, study, and work.</li>
    <li>Archive messages you want to keep but do not need in the inbox.</li>
    <li>Delete unnecessary messages and clear trash when appropriate.</li>
  </ul>
</section>

<section class="lh-topic">
  <h2>🧹 Spam and Junk Mail</h2>
  <p><strong>Spam</strong> is unwanted email, often sent in bulk. Some spam is merely annoying; other messages may attempt to steal information or deliver malware.</p>
  <ul>
    <li>Do not click suspicious links just to unsubscribe.</li>
    <li>Use <strong>Report spam</strong> or <strong>Report phishing</strong> when appropriate.</li>
    <li>Do not reply to obvious scam messages.</li>
    <li>Check the Spam/Junk folder occasionally because legitimate messages can sometimes be filtered incorrectly.</li>
  </ul>
</section>

<section class="lh-topic">
  <h2>🎣 Recognize Phishing Emails</h2>
  <p><strong>Phishing</strong> is an attempt to trick you into revealing sensitive information or taking an unsafe action. A phishing message may pretend to be from a bank, company, coworker, delivery service, or another trusted organization.</p>
  <table class="table table-bordered align-middle"><thead><tr><th>Warning sign</th><th>Safer response</th></tr></thead><tbody>
    <tr><td>Unexpected password or payment request</td><td>Do not respond. Verify through the official website or another trusted channel.</td></tr>
    <tr><td>Urgent threat such as “act now”</td><td>Stop and verify instead of reacting under pressure.</td></tr>
    <tr><td>Strange sender address</td><td>Do not trust the display name alone.</td></tr>
    <tr><td>Suspicious link</td><td>Do not click; open the official site yourself.</td></tr>
    <tr><td>Unexpected attachment</td><td>Do not open it until it is verified.</td></tr>
  </tbody></table>
  <div class="lh-callout"><strong>Golden rule:</strong> never reveal your password or one-time verification code because an unexpected email asks for it.</div>
</section>

<section class="lh-topic">
  <h2>🔐 Protect Your Email Account</h2>
  <ul>
    <li>Use a strong, unique password.</li>
    <li>Enable two-step verification or multi-factor authentication when available.</li>
    <li>Never share passwords or one-time security codes.</li>
    <li>Review account recovery information and security alerts.</li>
    <li>Sign out of shared computers after using your account.</li>
    <li>Be careful when granting third-party apps access to your mailbox.</li>
  </ul>
  <p>Your email is especially important because password-reset links for other services may be delivered there.</p>
</section>

<section class="lh-topic">
  <h2>🤝 Email Etiquette</h2>
  <ul>
    <li>Use a clear subject and an appropriate greeting.</li>
    <li>Keep the message focused and readable.</li>
    <li>Use paragraphs or bullet points for longer messages.</li>
    <li>Check names, dates, numbers, spelling, and attachments before sending.</li>
    <li>Avoid unnecessary Reply All messages.</li>
    <li>Do not send confidential information to the wrong recipient.</li>
    <li>Remember that email can be forwarded or stored.</li>
  </ul>
</section>

<section class="lh-topic">
  <h2>🧪 Practical Activity</h2>
  <p>Practice without sending anything to a real person:</p>
  <ol>
    <li>Create a new draft.</li>
    <li>Put your own address or a safe test address in <strong>To</strong>.</li>
    <li>Use the subject <code>Computer Basics Practice</code>.</li>
    <li>Write a short three-sentence message.</li>
    <li>Attach a harmless small file.</li>
    <li>Review the recipient, subject, message, and attachment.</li>
    <li>Save the draft if you do not want to send it.</li>
    <li>Open an existing message and locate Reply, Reply All, Forward, and Report Spam.</li>
  </ol>
</section>

<section class="lh-topic">
  <h2>❌ Common Mistakes</h2>
  <ul>
    <li>Sending before checking the recipient address.</li>
    <li>Forgetting an attachment after writing “attached is...”</li>
    <li>Using Reply All when only one person needs the answer.</li>
    <li>Forwarding private information without checking the old conversation.</li>
    <li>Opening unexpected attachments because the sender name looks familiar.</li>
    <li>Clicking a login link in an unexpected email instead of opening the official site directly.</li>
    <li>Reusing the same password for email and other important accounts.</li>
  </ul>
</section>

<section class="lh-topic">
  <h2>🧠 Quick Self-Check</h2>
  <ol>
    <li>What is the purpose of the To field?</li>
    <li>When might you use Cc?</li>
    <li>What is Bcc used for?</li>
    <li>What is the difference between Reply and Forward?</li>
    <li>Why should you check an attachment before opening it?</li>
    <li>Name two warning signs of phishing.</li>
    <li>Why is a strong, unique email password important?</li>
  </ol>
  <details class="mt-3"><summary><strong>Show answers</strong></summary>
    <div class="lh-callout mt-3"><ol>
      <li>To identifies the main recipient or recipients.</li>
      <li>Cc is useful when someone should receive a copy mainly for awareness.</li>
      <li>Bcc hides Bcc recipients from the other recipients.</li>
      <li>Reply responds to an existing conversation; Forward sends the message to a new recipient.</li>
      <li>An unexpected attachment may contain harmful software or a malicious document.</li>
      <li>Examples include urgency, suspicious links, strange sender addresses, unexpected attachments, or requests for sensitive information.</li>
      <li>Email can provide access to password resets and other important accounts.</li>
    </ol></div>
  </details>
</section>

<section class="lh-topic">
  <h2>✅ Key Takeaway</h2>
  <p>Email becomes easy when you understand the workflow: <strong>choose the right recipient, write a clear subject and message, handle attachments carefully, choose Reply/Reply All/Forward correctly, and verify suspicious messages before clicking or sharing information.</strong></p>
</section>
HTML;
}

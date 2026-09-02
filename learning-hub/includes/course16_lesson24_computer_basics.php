<?php
declare(strict_types=1);

/**
 * Course 16 — Computer Basics Mastery
 * Lesson 24 — Email Basics
 */
function lh_course16_lesson24_computer_basics_override(array $lesson, int $position): ?string
{
    if ((string)($lesson['slug'] ?? '') !== 'course-16-lesson-24') {
        return null;
    }

    return <<<'HTML'
<section class="lh-topic">
  <h2>📧 What Is Email?</h2>
  <p><strong>Email</strong> (electronic mail) is a way to send and receive digital messages over the Internet. An email can contain text, links, pictures, documents, and other files.</p>
  <div class="lh-callout"><strong>Think of email like digital post:</strong> your email address identifies your mailbox, the message is the letter, and an attachment is a file sent along with it.</div>
</section>

<section class="lh-topic">
  <h2>🔤 Understanding an Email Address</h2>
  <p>A typical email address looks like <code>name@example.com</code>.</p>
  <table class="table table-bordered align-middle">
    <thead><tr><th>Part</th><th>Example</th><th>Meaning</th></tr></thead>
    <tbody>
      <tr><td>Local part</td><td><code>name</code></td><td>Identifies the mailbox or account.</td></tr>
      <tr><td><code>@</code></td><td><code>@</code></td><td>Separates the mailbox name from the domain.</td></tr>
      <tr><td>Domain</td><td><code>example.com</code></td><td>Identifies the email service or organization.</td></tr>
    </tbody>
  </table>
  <p>Email addresses are precise. A single missing character or extra space can send a message to the wrong place or cause delivery to fail.</p>
</section>

<section class="lh-topic">
  <h2>📥 Know Your Mail Folders</h2>
  <table class="table table-bordered align-middle">
    <thead><tr><th>Folder</th><th>Purpose</th></tr></thead>
    <tbody>
      <tr><td><strong>Inbox</strong></td><td>Messages you receive.</td></tr>
      <tr><td><strong>Sent</strong></td><td>Messages you have sent.</td></tr>
      <tr><td><strong>Drafts</strong></td><td>Messages started but not yet sent.</td></tr>
      <tr><td><strong>Spam/Junk</strong></td><td>Messages identified as unwanted or potentially suspicious.</td></tr>
      <tr><td><strong>Trash/Deleted</strong></td><td>Messages you have deleted; they may remain here temporarily.</td></tr>
      <tr><td><strong>Archive</strong></td><td>Messages removed from the inbox without necessarily deleting them.</td></tr>
    </tbody>
  </table>
</section>

<section class="lh-topic">
  <h2>✍️ Writing a New Email</h2>
  <p>When composing an email, the main fields are <strong>To</strong>, <strong>Cc</strong>, <strong>Bcc</strong>, <strong>Subject</strong>, and the message body.</p>
  <ol>
    <li>Choose <strong>Compose</strong>, <strong>New Email</strong>, or a similar button.</li>
    <li>Enter the recipient's email address in <strong>To</strong>.</li>
    <li>Add other recipients only when they genuinely need the message.</li>
    <li>Write a short, useful <strong>Subject</strong>.</li>
    <li>Write the message in the body.</li>
    <li>Attach files if needed.</li>
    <li>Review the recipients, subject, message, and attachments before selecting <strong>Send</strong>.</li>
  </ol>
  <div class="lh-callout"><strong>Final check:</strong> the easiest email mistake to prevent is sending the right message to the wrong person. Check the <strong>To</strong> field before sending.</div>
</section>

<section class="lh-topic">
  <h2>👥 To, Cc, and Bcc</h2>
  <table class="table table-bordered align-middle">
    <thead><tr><th>Field</th><th>Use it when...</th></tr></thead>
    <tbody>
      <tr><td><strong>To</strong></td><td>The person is a main recipient who is expected to read or act on the message.</td></tr>
      <tr><td><strong>Cc</strong></td><td>The person should receive a copy for awareness, but is not necessarily the main person responsible for the action.</td></tr>
      <tr><td><strong>Bcc</strong></td><td>You need recipients to receive the message without exposing the Bcc recipient list to other recipients.</td></tr>
    </tbody>
  </table>
  <p>Use Cc and Bcc carefully. For ordinary conversations, sending to fewer people is often clearer and safer.</p>
</section>

<section class="lh-topic">
  <h2>📝 Write a Useful Subject</h2>
  <p>A good subject lets the recipient understand the purpose of the email before opening it.</p>
  <ul>
    <li>Good: <code>Computer Basics Lesson 24 Feedback</code></li>
    <li>Good: <code>Invoice for September — SmartToolz</code></li>
    <li>Weak: <code>Hi</code></li>
    <li>Weak: <code>Important</code></li>
  </ul>
  <p>Keep the subject specific and reasonably short. Avoid writing the entire message in the subject line.</p>
</section>

<section class="lh-topic">
  <h2>📎 Email Attachments</h2>
  <p>An <strong>attachment</strong> is a file sent with an email, such as a PDF, photo, spreadsheet, or document. Most email services provide an attachment button, often shown with a paperclip icon.</p>
  <ol>
    <li>Compose your email.</li>
    <li>Select the attachment option.</li>
    <li>Choose the file from your computer or cloud storage.</li>
    <li>Wait for the upload to finish if necessary.</li>
    <li>Check the attachment name before sending.</li>
  </ol>
  <div class="lh-callout"><strong>Important:</strong> attachment size limits depend on the email service. For example, personal Gmail accounts currently have a 25 MB attachment limit; larger files may be sent through Google Drive instead. citeturn0search2</div>
</section>

<section class="lh-topic">
  <h2>🛡️ Handle Attachments Safely</h2>
  <p>Attachments can carry harmful software. Never open a file simply because an email says you must do it urgently.</p>
  <ul>
    <li>Check who actually sent the message, not just the display name.</li>
    <li>Be cautious with unexpected ZIP files, executable files, scripts, or password-protected documents.</li>
    <li>If you were not expecting the file, confirm with the sender through a trusted channel before opening it.</li>
    <li>Keep your operating system and security software updated.</li>
    <li>Do not disable security warnings just to open a suspicious attachment.</li>
  </ul>
  <p>Email providers may block or warn about dangerous attachment types. Gmail, for example, warns about suspicious attachments and blocks certain executable files. citeturn0search15turn0search2</p>
</section>

<section class="lh-topic">
  <h2>↩️ Reply, Reply All, and Forward</h2>
  <table class="table table-bordered align-middle">
    <thead><tr><th>Action</th><th>What it does</th><th>Use it when...</th></tr></thead>
    <tbody>
      <tr><td><strong>Reply</strong></td><td>Sends your response back to the relevant sender.</td><td>Only the sender needs your answer.</td></tr>
      <tr><td><strong>Reply All</strong></td><td>Replies to the sender and the other appropriate recipients in the conversation.</td><td>Everyone on the conversation genuinely needs your response.</td></tr>
      <tr><td><strong>Forward</strong></td><td>Sends the message to a new recipient.</td><td>Someone who was not in the original conversation needs the information.</td></tr>
    </tbody>
  </table>
  <p>When forwarding, check the old message carefully. It may contain private information or attachments that the new recipient should not receive. Replying normally does not automatically include the original attachment; forwarding can include original attachments. citeturn0search5turn0search6</p>
</section>

<section class="lh-topic">
  <h2>🔎 Search and Organize Email</h2>
  <p>You do not need to keep every useful message in the inbox. Use your email service's search, folders, labels, categories, stars, or archive features to organize messages.</p>
  <ul>
    <li>Search by sender when looking for messages from one person.</li>
    <li>Search by subject or important words from the message.</li>
    <li>Use folders or labels for projects, bills, study, work, or other useful groups.</li>
    <li>Archive messages that you want to keep but do not need in the inbox.</li>
    <li>Delete unnecessary messages and periodically clear trash if appropriate.</li>
  </ul>
</section>

<section class="lh-topic">
  <h2>🧹 Spam and Junk Mail</h2>
  <p><strong>Spam</strong> is unwanted email, often sent in bulk. Some spam is merely annoying, while other messages may attempt to steal information or install malware.</p>
  <ul>
    <li>Do not click suspicious links just to unsubscribe.</li>
    <li>Use the email service's <strong>Report spam</strong> or <strong>Report phishing</strong> feature when appropriate.</li>
    <li>Do not reply to obvious scam messages.</li>
    <li>Check the Spam/Junk folder occasionally because legitimate messages can sometimes be filtered incorrectly.</li>
  </ul>
</section>

<section class="lh-topic">
  <h2>🎣 Recognize Phishing Emails</h2>
  <p><strong>Phishing</strong> is an attempt to trick you into revealing sensitive information or taking an unsafe action. A phishing email may pretend to come from a bank, company, coworker, delivery service, or another trusted organization. citeturn0search4</p>
  <table class="table table-bordered align-middle">
    <thead><tr><th>Warning sign</th><th>What to do</th></tr></thead>
    <tbody>
      <tr><td>Unexpected password or payment request</td><td>Do not respond. Verify through the organization's official website or another trusted channel.</td></tr>
      <tr><td>Urgent threat such as “act now or your account closes”</td><td>Stop and verify. Urgency is commonly used to pressure people into mistakes.</td></tr>
      <tr><td>Unknown or strange sender address</td><td>Do not trust the display name alone.</td></tr>
      <tr><td>Suspicious link</td><td>Do not click. Navigate to the official site yourself instead.</td></tr>
      <tr><td>Unexpected attachment</td><td>Do not open it until the sender and file are verified.</td></tr>
    </tbody>
  </table>
  <div class="lh-callout"><strong>Golden rule:</strong> legitimate organizations should not need you to reveal your password or one-time verification code through an unexpected email.</div>
</section>

<section class="lh-topic">
  <h2>🔐 Protect Your Email Account</h2>
  <ul>
    <li>Use a strong, unique password.</li>
    <li>Enable two-step verification or multi-factor authentication when available.</li>
    <li>Never share your password or one-time security code.</li>
    <li>Review account recovery information and security alerts.</li>
    <li>Sign out of shared computers after using your account.</li>
    <li>Be careful when granting third-party apps access to your mailbox.</li>
  </ul>
  <p>Your email account can be especially valuable because password-reset links for other services may be delivered to it.</p>
</section>

<section class="lh-topic">
  <h2>🤝 Email Etiquette</h2>
  <ul>
    <li>Use a clear subject.</li>
    <li>Start with an appropriate greeting.</li>
    <li>Keep the message focused and readable.</li>
    <li>Use paragraphs or bullet points for longer messages.</li>
    <li>Check spelling, names, dates, numbers, and attachments before sending.</li>
    <li>Avoid unnecessary Reply All messages.</li>
    <li>Do not send confidential information to the wrong recipient.</li>
    <li>Remember that email can be forwarded or stored; write accordingly.</li>
  </ul>
</section>

<section class="lh-topic">
  <h2>🧪 Practical Activity</h2>
  <p>Practice without sending anything to a real person:</p>
  <ol>
    <li>Open your email service and create a new draft.</li>
    <li>Enter a safe test address or your own address in <strong>To</strong>.</li>
    <li>Write the subject <code>Computer Basics Practice</code>.</li>
    <li>Write a short three-sentence message.</li>
    <li>Attach a harmless small file, such as a practice text document.</li>
    <li>Review the recipient, subject, message, and attachment.</li>
    <li>Save the draft if you do not want to send it.</li>
    <li>Open an existing message and identify where Reply, Reply All, Forward, and Report Spam are located.</li>
  </ol>
  <div class="lh-callout"><strong>Goal:</strong> become comfortable with the email interface before you need to send an important message.</div>
</section>

<section class="lh-topic">
  <h2>❌ Common Mistakes</h2>
  <ul>
    <li>Sending an email before checking the recipient address.</li>
    <li>Forgetting the attachment after writing “attached is...” in the message.</li>
    <li>Using Reply All when only one person needs the answer.</li>
    <li>Forwarding private information without checking the old conversation.</li>
    <li>Opening unexpected attachments because the sender name looks familiar.</li>
    <li>Clicking a login link in an unexpected email instead of opening the official site directly.</li>
    <li>Using the same password for email and other important accounts.</li>
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
    <li>Name two warning signs of a phishing email.</li>
    <li>Why is a strong, unique email password important?</li>
  </ol>
  <details class="mt-3"><summary><strong>Show answers</strong></summary>
    <div class="lh-callout mt-3">
      <ol>
        <li>To identifies the main recipient or recipients of the message.</li>
        <li>Cc is useful when someone should receive a copy mainly for awareness.</li>
        <li>Bcc hides Bcc recipients from the other recipients of the message.</li>
        <li>Reply responds to an existing conversation; Forward sends the message to a new recipient.</li>
        <li>An unexpected attachment may contain harmful software or a malicious document.</li>
        <li>Examples include an urgent request, suspicious link, strange sender address, unexpected attachment, or request for sensitive information.</li>
        <li>Email can provide access to password resets and other important accounts, so protecting it is especially important.</li>
      </ol>
    </div>
  </details>
</section>

<section class="lh-topic">
  <h2>✅ Key Takeaway</h2>
  <p>Email becomes easy when you understand the basic workflow: <strong>choose the right recipient, write a clear subject and message, handle attachments carefully, choose Reply/Reply All/Forward correctly, and verify suspicious messages before clicking or sharing information.</strong></p>
</section>
HTML;
}

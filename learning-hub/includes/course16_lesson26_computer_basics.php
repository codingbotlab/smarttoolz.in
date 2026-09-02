<?php
declare(strict_types=1);

/**
 * Course 16 — Computer Basics Mastery
 * Lesson 26 — Cloud Storage
 */
function lh_course16_lesson26_computer_basics_override(array $lesson, int $position): ?string
{
    if ((string)($lesson['slug'] ?? '') !== 'course-16-lesson-26') {
        return null;
    }

    return <<<'HTML'
<section class="lh-topic">
  <h2>☁️ What Is Cloud Storage?</h2>
  <p><strong>Cloud storage</strong> means storing files on remote computers operated by a cloud service so you can access those files through the Internet. Services such as OneDrive and Google Drive let you upload files, organize them, and access them from supported devices.</p>
  <div class="lh-callout"><strong>Simple idea:</strong> your computer has local storage; cloud storage gives you another place to keep and access files over the Internet.</div>
</section>

<section class="lh-topic">
  <h2>💻 Local Storage vs Cloud Storage</h2>
  <table class="table table-bordered align-middle">
    <thead><tr><th>Local storage</th><th>Cloud storage</th></tr></thead>
    <tbody>
      <tr><td>Files are stored on your computer, USB drive, or another local device.</td><td>Files are stored on servers managed by a cloud provider.</td></tr>
      <tr><td>Usually available without an Internet connection.</td><td>Access and syncing normally depend on the service and connection.</td></tr>
      <tr><td>You are responsible for protecting the physical storage device.</td><td>The provider manages the underlying servers, while you still need to protect your account.</td></tr>
      <tr><td>Sharing may require another method.</td><td>Sharing and collaboration are common built-in features.</td></tr>
    </tbody>
  </table>
</section>

<section class="lh-topic">
  <h2>🔄 Upload, Download, and Sync</h2>
  <ul>
    <li><strong>Upload:</strong> copy a file from your device to cloud storage.</li>
    <li><strong>Download:</strong> copy a cloud file to your device.</li>
    <li><strong>Sync:</strong> keep a local folder and cloud copy coordinated so changes can be reflected across devices.</li>
    <li><strong>Share:</strong> give other people access to a file or folder according to the permissions you choose.</li>
  </ul>
  <p>For example, OneDrive can sync files between your computer and the cloud, so changes made in the synced folder can be reflected online and across other connected devices.</p>
</section>

<section class="lh-topic">
  <h2>📁 Uploading Files to the Cloud</h2>
  <p>The exact buttons vary between services, but the basic process is similar:</p>
  <ol>
    <li>Sign in to your cloud-storage account.</li>
    <li>Open the folder where you want the file.</li>
    <li>Select <strong>Upload</strong> or a similar command.</li>
    <li>Choose a file or folder from your computer.</li>
    <li>Wait for the upload to finish.</li>
    <li>Confirm that the file appears in the correct cloud folder.</li>
  </ol>
  <p>OneDrive supports uploading files or folders through its website, and Google Drive provides file and folder upload options.</p>
</section>

<section class="lh-topic">
  <h2>📂 Organizing Cloud Files</h2>
  <p>Cloud storage still needs good organization. A messy cloud drive can become just as difficult to use as a messy Downloads folder.</p>
  <ul>
    <li>Create folders based on projects, subjects, or purposes.</li>
    <li>Use clear file names and consistent naming.</li>
    <li>Keep related files together.</li>
    <li>Archive old projects instead of mixing them with active work.</li>
    <li>Use the service's search feature when you cannot remember a file's location.</li>
  </ul>
  <div class="lh-callout"><strong>Good structure:</strong> <code>Projects → Website → Images / Documents / Exports</code> is easier to manage than hundreds of unrelated files in one folder.</div>
</section>

<section class="lh-topic">
  <h2>🔗 Sharing Files Safely</h2>
  <p>Cloud services can make sharing easy, but a share link is still access to your data.</p>
  <table class="table table-bordered align-middle">
    <thead><tr><th>Permission</th><th>Meaning</th><th>Use carefully</th></tr></thead>
    <tbody>
      <tr><td><strong>View</strong></td><td>The recipient can usually read or download the file.</td><td>Good when someone only needs to see the information.</td></tr>
      <tr><td><strong>Comment</strong></td><td>The recipient can add comments where the service supports it.</td><td>Useful for review without full editing access.</td></tr>
      <tr><td><strong>Edit</strong></td><td>The recipient can change the shared content.</td><td>Give this only to people who genuinely need editing access.</td></tr>
    </tbody>
  </table>
  <p>Before sharing, check who can access the item and whether the link is restricted to specific people or open to anyone with the link.</p>
</section>

<section class="lh-topic">
  <h2>🔐 Protect Your Cloud Account</h2>
  <p>Your cloud account may contain documents, photos, backups, and personal information, so account security matters.</p>
  <ul>
    <li>Use a strong, unique password.</li>
    <li>Enable two-step verification or multi-factor authentication when available.</li>
    <li>Never share your password or one-time security code.</li>
    <li>Review unfamiliar sign-ins and security alerts.</li>
    <li>Be careful when granting third-party apps access to your cloud files.</li>
    <li>Sign out from shared computers when appropriate.</li>
  </ul>
</section>

<section class="lh-topic">
  <h2>🛡️ Cloud Storage Is Not Automatically a Backup</h2>
  <p>People often say “my files are in the cloud, so they are backed up.” That can be misleading. If a synced file is deleted or changed, that change may also synchronize to the cloud. A separate backup strategy can still be important.</p>
  <div class="lh-callout"><strong>Remember:</strong> <strong>sync</strong> keeps locations coordinated; <strong>backup</strong> is a separate recoverable copy designed to help you restore data after loss or damage.</div>
  <p>Some cloud services provide folder backup and recovery features, but you should understand exactly what the service protects before treating it as your only backup. OneDrive, for example, provides options for backing up important Windows folders such as Desktop, Documents, Pictures, and Videos.</p>
</section>

<section class="lh-topic">
  <h2>💾 Files On-Demand and Device Space</h2>
  <p>Some cloud applications let you see cloud files in File Explorer without keeping every file fully downloaded on the device. This can save local disk space.</p>
  <p>OneDrive's Files On-Demand can show files that are online-only, locally available, or always available, allowing you to choose what stays downloaded on the computer.</p>
  <div class="lh-callout"><strong>Important:</strong> online-only files may require an Internet connection before you can open their full contents.</div>
</section>

<section class="lh-topic">
  <h2>📱 Access From Multiple Devices</h2>
  <p>One major benefit of cloud storage is access from more than one device. You can sign in to the same service on a computer, phone, tablet, or browser and access files according to the service's settings.</p>
  <p>For example, Microsoft describes OneDrive as allowing synced files to be accessed from a computer, mobile device, or the OneDrive website.</p>
  <p>This is useful when you start work on one device and need the same file on another.</p>
</section>

<section class="lh-topic">
  <h2>📦 Storage Limits</h2>
  <p>Cloud accounts have storage limits or quotas. The amount available depends on the service and account type. When storage becomes full, uploads and syncing can be affected.</p>
  <ul>
    <li>Check your storage usage regularly.</li>
    <li>Delete unnecessary large files.</li>
    <li>Empty the cloud service's trash or recycle area when appropriate.</li>
    <li>Move old data to another suitable storage location if you no longer need it online.</li>
    <li>Do not assume that deleting a local copy always means the cloud copy will remain; understand how your service's sync behavior works first.</li>
  </ul>
</section>

<section class="lh-topic">
  <h2>🔍 Finding Missing Cloud Files</h2>
  <p>If a file seems to have disappeared, do not immediately assume it is permanently lost.</p>
  <ol>
    <li>Search the cloud service by file name or keyword.</li>
    <li>Check the correct account.</li>
    <li>Check other folders and shared locations.</li>
    <li>Check the service's trash or recycle area.</li>
    <li>Check whether synchronization has completed.</li>
    <li>Look for version history or recovery options if the service provides them.</li>
  </ol>
  <p>Microsoft recommends starting a missing-file search on the OneDrive website because it represents the cloud storage used across devices.</p>
</section>

<section class="lh-topic">
  <h2>⚠️ Common Cloud Storage Problems</h2>
  <table class="table table-bordered align-middle">
    <thead><tr><th>Problem</th><th>What to check</th></tr></thead>
    <tbody>
      <tr><td>File is not syncing</td><td>Check Internet connection, account sign-in, sync status, and whether the folder is selected for syncing.</td></tr>
      <tr><td>Storage is full</td><td>Review large files, deleted items, and available quota.</td></tr>
      <tr><td>File is online-only</td><td>Connect to the Internet or make the file available offline when supported.</td></tr>
      <tr><td>Wrong account</td><td>Check whether you are signed into the personal, work, or school account that contains the file.</td></tr>
      <tr><td>Shared file cannot be edited</td><td>Check the permission level granted by the owner.</td></tr>
      <tr><td>Duplicate/conflicting files</td><td>Check sync status and compare file versions before deleting anything.</td></tr>
    </tbody>
  </table>
</section>

<section class="lh-topic">
  <h2>🎯 Practical Activity</h2>
  <p>Practice with a harmless test file:</p>
  <ol>
    <li>Create a small text document named <code>Cloud Storage Practice.txt</code>.</li>
    <li>Upload it to a new folder in your cloud storage account.</li>
    <li>Confirm that the file appears online.</li>
    <li>Rename or edit the test file and observe whether the change syncs.</li>
    <li>Open the service on another device or in a browser and find the same file.</li>
    <li>Practice creating a view-only share or inspect the sharing settings without sending the link.</li>
    <li>Delete the test file and check where the deleted item goes.</li>
  </ol>
  <div class="lh-callout"><strong>Goal:</strong> understand the complete flow: <strong>create → upload → sync → access → share → recover/delete</strong>.</div>
</section>

<section class="lh-topic">
  <h2>❌ Common Mistakes</h2>
  <ul>
    <li>Thinking cloud storage and backup are exactly the same thing.</li>
    <li>Sharing a file with “anyone with the link” when restricted sharing would be safer.</li>
    <li>Giving edit permission when view access is enough.</li>
    <li>Deleting a synced file without understanding that the deletion may sync elsewhere.</li>
    <li>Ignoring storage limits until uploads stop working.</li>
    <li>Saving sensitive files in a shared folder without checking its permissions.</li>
    <li>Using the wrong personal, work, or school account and assuming the file is missing.</li>
  </ul>
</section>

<section class="lh-topic">
  <h2>🧠 Quick Self-Check</h2>
  <ol>
    <li>What is cloud storage?</li>
    <li>What is the difference between upload and download?</li>
    <li>What does synchronization do?</li>
    <li>Why should you check sharing permissions before sending a cloud link?</li>
    <li>Why is sync not necessarily the same as backup?</li>
    <li>What should you check if a cloud file appears to be missing?</li>
    <li>How can cloud storage help when you use more than one device?</li>
  </ol>
  <details class="mt-3"><summary><strong>Show answers</strong></summary>
    <div class="lh-callout mt-3">
      <ol>
        <li>It is a service for storing and accessing files on remote servers through the Internet.</li>
        <li>Upload copies data from your device to the cloud; download copies cloud data to your device.</li>
        <li>Sync keeps supported copies of files coordinated across locations.</li>
        <li>A share link can give other people access, so the permission and audience should match the information's sensitivity.</li>
        <li>A synchronized deletion or change can propagate, while a backup is intended to provide a recoverable copy.</li>
        <li>Check the account, search, folders, trash, sync status, and available recovery/version features.</li>
        <li>The same cloud files can be accessed from supported computers, phones, tablets, or browsers.</li>
      </ol>
    </div>
  </details>
</section>

<section class="lh-topic">
  <h2>✅ Key Takeaway</h2>
  <p><strong>Cloud storage gives you a convenient way to store, sync, access, and share files across devices.</strong> Use clear organization, protect your account, check sharing permissions, understand sync behavior, and keep a separate backup strategy for important data.</p>
</section>
HTML;
}

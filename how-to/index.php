<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/bootstrap.php';
require_once dirname(__DIR__) . '/lib/tools.php';

$slug = strtolower(trim((string)($_GET['slug'] ?? '')));
$tool = null;
foreach (smarttoolz_tools() as $item) {
    if (smarttoolz_slug((string)$item['name']) === $slug || trim((string)$item['url'], '/') === 'tools/'.$slug) {
        $tool = $item;
        break;
    }
}
if (!$tool) {
    http_response_code(404); require dirname(__DIR__) . '/error.php'; exit;
}

$name = (string)$tool['name'];
$category = (string)$tool['category'];
$description = (string)$tool['description'];
$canonical = 'https://smarttoolz.in/how-to/' . rawurlencode($slug) . '/';

/*
 * Tool-specific editorial content. Keep this map focused on genuinely useful
 * instructions rather than repeating a generic five-step article on every URL.
 * Unknown/future tools receive a useful fallback based on their tool metadata.
 */
$guides = [
'image-compressor'=>[
 'intro'=>'Reduce image file size for faster uploads, email attachments and websites while checking that the result still looks sharp.',
 'when'=>'Useful when a photo is too large to upload, a website image is slowing a page down, or you need a smaller copy for sharing.',
 'steps'=>[
  ['Choose the source image','Select a JPG, PNG or WebP image. Start with the original file when possible so you always have a clean copy to return to.'],
  ['Check the preview','Look at the image before compression and note its dimensions and visual details, especially small text or fine edges.'],
  ['Adjust compression','Use the available compression control to balance file size and image quality. For photographs, a moderate reduction is often a good starting point.'],
  ['Compare the result','Check the compressed preview at a useful viewing size. Look for blurry text, blocky areas, banding or obvious loss of detail.'],
  ['Download the smaller file','When the result looks good, download it and keep the original separately. Rename the output if it is going into a website or project.'],
 ],
 'tips'=>['Compress a copy rather than overwriting your original.','For web images, reduce unnecessary dimensions as well as quality when the tool provides that option.','Compare file size and appearance together; the smallest file is not always the best file.'],
 'faqs'=>[
  ['What image formats can I compress?','The SmartToolz Image Compressor supports JPG, PNG and WebP according to the tool description.'],
  ['Should I use maximum compression?','Not automatically. Choose the strongest reduction that still preserves the details your image needs.'],
  ['Why can two images with the same dimensions have different file sizes?','Compression, image content, metadata and the original encoding can all affect file size.'],
 ]
],
'image-resizer'=>[
 'intro'=>'Resize an image to a specific width and height for websites, profiles, documents or social media without relying on a desktop editor.',
 'when'=>'Use it when a platform asks for exact dimensions or when a large image needs to be prepared for a smaller screen or layout.',
 'steps'=>[
  ['Select your image','Open the Image Resizer and choose the source image you want to resize. Keep the original file if you may need its full resolution later.'],
  ['Set the target size','Enter the required width and height. If the destination has a fixed aspect ratio, use matching proportions to avoid distortion.'],
  ['Review the dimensions','Check the displayed output size before processing. A very small target can remove details that cannot be recovered by enlarging later.'],
  ['Resize the image','Run the resize action and wait for the output preview to appear.'],
  ['Inspect and download','Check faces, text and edges for unwanted stretching or softness, then download the resized image.'],
 ],
 'tips'=>['Keep the aspect ratio when the image must not look stretched.','Downscaling is usually safer than repeatedly enlarging an image.','Keep a high-resolution original for future edits.'],
 'faqs'=>[
  ['Will resizing improve a low-quality image?','No. Resizing changes dimensions; it cannot recreate detail that was never present in the source.'],
  ['Why does the resized image look soft?','Reducing dimensions removes pixels. Some softness is normal, especially when a highly detailed image is made much smaller.'],
  ['What size should I choose?','Use the dimensions required by the destination rather than choosing a larger size without a reason.'],
 ]
],
'jpg-to-png'=>[
 'intro'=>'Convert a JPG or JPEG image into PNG format when you need PNG output for transparency-aware workflows or lossless-style image handling.',
 'when'=>'PNG is useful for graphics, screenshots and images where you want to avoid another JPEG compression pass. Remember that converting a JPG cannot restore information already lost in the JPG.',
 'steps'=>[
  ['Select a JPG or JPEG','Open the converter and choose the image you want to convert. The tool accepts JPG/JPEG input.'],
  ['Check the preview','Confirm that you selected the correct image and review its dimensions before conversion.'],
  ['Convert to PNG','Click the conversion button. The browser creates the PNG output from the selected image.'],
  ['Review the output','Check the converted preview and make sure the image dimensions and appearance are what you expect.'],
  ['Download the PNG','Save the generated PNG with a clear filename so it is easy to identify later.'],
 ],
 'tips'=>['Converting JPG to PNG does not recreate lost JPG detail.','Use PNG when your next workflow benefits from PNG, not simply because the extension is different.','Keep the original JPG if you may need the source later.'],
 'faqs'=>[
  ['Does JPG to PNG add transparency?','No. A normal JPG does not contain transparency that can be recovered by conversion.'],
  ['Will the PNG always look better?','Not necessarily. PNG is a different encoding; converting a JPG does not restore compression artifacts.'],
  ['Can I convert without uploading the image to a server?','The SmartToolz converter is designed as a browser-based workflow; follow the page interface and its privacy information for the current behavior.'],
 ]
],
'png-to-jpg'=>[
 'intro'=>'Convert a PNG image to JPG when you need a widely supported photographic format or a smaller file for sharing and uploads.',
 'when'=>'JPG is commonly accepted by forms, websites and image-sharing services. It is generally better suited to photographs than graphics with sharp transparency edges.',
 'steps'=>[
  ['Choose the PNG','Select the PNG you want to convert and make sure it is the intended source image.'],
  ['Preview the source','Review the image dimensions and appearance before conversion. Pay attention to transparent areas.'],
  ['Convert to JPG','Run the converter. Because JPG does not support transparency, transparent areas need a background in the resulting image.'],
  ['Check the JPG','Inspect edges, text and transparent regions in the output preview before downloading.'],
  ['Download and name the file','Save the JPG with a useful filename and retain the PNG if you need the original transparency later.'],
 ],
 'tips'=>['Keep the PNG if transparency matters.','JPG uses lossy compression, so avoid repeatedly converting the same image between formats.','For logos or screenshots with transparency, PNG may remain the better source format.'],
 'faqs'=>[
  ['Does JPG support transparency?','No. JPG has no alpha-transparency channel, so transparent regions must become a visible background.'],
  ['Is JPG smaller than PNG?','Often for photographs, but not for every image. File size depends on the image content and encoding.'],
  ['Can I convert a logo to JPG?','Yes, but inspect the background and edges because transparent logo areas will no longer remain transparent.'],
 ]
],
'word-counter'=>[
 'intro'=>'Count words, characters, sentences and paragraphs in a draft before submitting, publishing or editing it.',
 'when'=>'Useful for assignments, application limits, articles, captions, metadata drafts and any form that imposes a length requirement.',
 'steps'=>[
  ['Paste or type your text','Enter the draft into the text area. Include the actual text you plan to submit rather than a placeholder.'],
  ['Review the counters','Check the word, character, sentence and paragraph totals shown by the tool.'],
  ['Match the requirement','Compare the relevant count with the limit you were given. Use character count when a form specifies characters rather than words.'],
  ['Edit the draft','Trim repeated phrases or add missing detail, then check the counters again.'],
  ['Copy the final text','Once the length is right, copy the final version into your destination.'],
 ],
 'tips'=>['A word limit and a character limit are different constraints.','Recheck the count after major edits.','If a platform has its own counting rules, treat its displayed count as the final authority.'],
 'faqs'=>[
  ['Does word count equal character count?','No. Word count measures words, while character count measures individual characters, including spaces depending on the tool’s counting rules.'],
  ['Why can another website show a different count?','Different platforms may define words, whitespace, punctuation or special characters differently.'],
  ['Can I use it for an assignment?','Yes, as a quick drafting check, but follow your institution or submission platform if it provides its own official count.'],
 ]
],
'case-converter'=>[
 'intro'=>'Change text between uppercase, lowercase, sentence case and title-style capitalization without retyping the content.',
 'when'=>'Helpful when cleaning copied text, standardizing headings, preparing labels or quickly changing the presentation of a draft.',
 'steps'=>[
  ['Enter the text','Paste the text you want to transform into the input area.'],
  ['Choose a case','Select the case that matches your purpose, such as UPPERCASE, lowercase, sentence case or title case.'],
  ['Review punctuation and names','Check the converted result manually. Automated capitalization cannot always know whether a brand, person or acronym should keep unusual casing.'],
  ['Make any final corrections','Restore intentional capitalization such as product names, acronyms and proper nouns.'],
  ['Copy the result','Copy the finished text and use it in your document, post or application.'],
 ],
 'tips'=>['Title case conventions vary, so review short words and branded names.','Keep acronyms such as API or HTML in their intended form.','Use sentence case for readable prose and headings when your style guide recommends it.'],
 'faqs'=>[
  ['Will case conversion understand every proper noun?','No. Capitalization tools transform text mechanically, so important names and acronyms should be reviewed.'],
  ['Can I convert a whole paragraph?','Yes, paste the text into the tool and review the resulting formatting before copying it.'],
  ['Does changing case change the meaning?','Usually not, but capitalization can carry meaning for names, acronyms and technical terms, so proofreading is still important.'],
 ]
],
'json-formatter'=>[
 'intro'=>'Turn compact or messy JSON into readable formatted data and use validation to spot syntax problems while debugging APIs and configuration files.',
 'when'=>'Useful for inspecting API responses, configuration files, request payloads and nested objects that are difficult to read in one line.',
 'steps'=>[
  ['Paste the JSON','Copy your JSON into the formatter. Start with the complete object or array when you are debugging a response.'],
  ['Format the structure','Run the formatter to add indentation and line breaks so nested objects and arrays are easier to inspect.'],
  ['Read validation errors','If the input is invalid, look for the reported problem and inspect nearby punctuation such as commas, braces, brackets and quotation marks.'],
  ['Correct and format again','Fix one issue at a time, then run the formatter again. Valid JSON should parse into a consistent object or array structure.'],
  ['Copy the clean JSON','Use the formatted output for debugging or documentation. Preserve the original payload separately if it is important evidence.'],
 ],
 'tips'=>['JSON strings require double quotes.','A trailing comma can make otherwise familiar JavaScript-style data invalid JSON.','When debugging an API, compare the failing payload with a known-valid request to isolate the change.'],
 'faqs'=>[
  ['What does a JSON formatter do?','It improves the visual structure of JSON with indentation and line breaks, making nested data easier to inspect.'],
  ['Why is my JSON invalid?','Common causes include missing quotes, unmatched braces or brackets, missing commas, and trailing commas.'],
  ['Is JSON the same as a JavaScript object?','They look similar, but JSON is a data interchange format with stricter syntax rules than a JavaScript object literal.'],
 ]
],
'qr-generator'=>[
 'intro'=>'Create a QR code from a URL or text and test the finished code before printing or sharing it.',
 'when'=>'Useful for menus, event pages, product information, contact details, Wi-Fi instructions and links that people need to open quickly on a phone.',
 'steps'=>[
  ['Choose what the QR should contain','Decide whether the code should point to a web address or contain plain text. Keep the destination or message final before generating it.'],
  ['Enter the content','Paste the URL or type the text into the generator. For a URL, include the correct HTTPS address if the destination supports it.'],
  ['Generate the QR code','Run the generator and wait for the QR preview to appear.'],
  ['Test it with a phone','Scan the displayed code from a second device or screen. Confirm that the destination opens correctly and that the encoded text is accurate.'],
  ['Download and publish','Download the QR image and place it where users can scan it. Leave enough surrounding space and avoid stretching it disproportionately.'],
 ],
 'tips'=>['Always test a QR code after generating and again after printing.','Use a short, stable destination URL when the QR will be printed permanently.','Do not place the code on a busy background or crop its quiet border.'],
 'faqs'=>[
  ['What can I put in a QR code?','SmartToolz’s generator is intended for URLs and text. Keep the content concise and test the result before sharing.'],
  ['Why will a QR code not scan?','Low contrast, blur, distortion, insufficient size, damaged edges or a missing quiet zone can make scanning difficult.'],
  ['Can I print the QR code?','Yes. Test the downloaded image at the size and print quality you plan to use before producing a large batch.'],
 ]
],
'password-generator'=>[
 'intro'=>'Generate a random password for a new account or test environment instead of inventing a predictable phrase yourself.',
 'when'=>'Useful when you need a fresh password with a chosen length and a mix of character types.',
 'steps'=>[
  ['Set the password requirements','Choose the length and character options offered by the generator. Prefer the longest length accepted by the service.'],
  ['Generate a password','Create a new random password. If it is difficult to type manually, use a password manager rather than weakening it.'],
  ['Check the generated value','Make sure the password meets the target service’s requirements and does not contain an accidental modification from copying.'],
  ['Store it safely','Save the password in a reputable password manager or the service’s supported credential system rather than a public note.'],
  ['Use it once','Avoid reusing the same password across important accounts. Generate a separate credential when a different service requires a new login.'],
 ],
 'tips'=>['Length is a major part of password security.','Never publish real passwords in screenshots, logs or support requests.','A password manager can generate, store and fill unique credentials for you.'],
 'faqs'=>[
  ['How long should a password be?','Use the longest practical length accepted by the service. Longer random passwords generally provide more possible combinations than short ones.'],
  ['Should I reuse a generated password?','No. Unique passwords reduce the damage if one service suffers a credential leak.'],
  ['Can I paste a generated password into a password manager?','Yes, provided the password manager and the destination account support normal secure credential storage.'],
 ]
],
'password-strength-checker'=>[
 'intro'=>'Evaluate a password against common strength signals such as length and character variety, then improve weak patterns before using the credential.',
 'when'=>'Use it as a quick educational check when creating a password. It is a guide, not a guarantee that a password has never been exposed elsewhere.',
 'steps'=>[
  ['Enter a test password','Enter a password you want to evaluate. For an important account, avoid exposing a password that is already in active use.'],
  ['Review the strength result','Read the tool’s feedback about length, character variety and other visible rules.'],
  ['Look for predictable choices','Check whether the password uses names, dates, keyboard sequences, repeated characters or common words.'],
  ['Create a stronger replacement','Prefer a longer unique password or a password-manager-generated credential rather than making many small predictable changes.'],
  ['Store the final password securely','Save the unique credential in a password manager and avoid sharing it in plain text.'],
 ],
 'tips'=>['Do not treat a high score as proof that a password is breach-free.','Avoid personal information and common substitutions such as replacing “a” with “@”.','Use unique credentials for important services.'],
 'faqs'=>[
  ['Does a strength score prove a password is safe?','No. A score can measure visible characteristics, but it cannot establish whether a password has appeared in a breach or leaked password list.'],
  ['What makes a password stronger?','Greater length, uniqueness and less predictable structure are important factors.'],
  ['Should I test my real password?','For sensitive accounts, it is safer to use a test value or create a new credential with a password manager rather than exposing an active secret unnecessarily.'],
 ]
],
'password-entropy-calculator'=>[
 'intro'=>'Estimate password entropy to understand how length and character choices affect the size of a password’s possible search space.',
 'when'=>'Useful for learning why longer random passwords are preferable and for comparing different password-generation strategies.',
 'steps'=>[
  ['Enter the password characteristics','Provide the length and character-set information requested by the calculator.'],
  ['Review the estimated entropy','Read the result as an estimate of uncertainty or possible combinations, not as a direct probability of being cracked.'],
  ['Compare alternatives','Try different lengths or character sets to see which change gives the largest improvement.'],
  ['Prefer random generation','Use a password generator or password manager for real credentials so the choices are not biased by human habits.'],
  ['Apply the result to your policy','Use the estimate as an educational input when deciding on password requirements, alongside service-specific limits and authentication controls.'],
 ],
 'tips'=>['Entropy is an estimate; human-chosen passwords often have less effective randomness than their apparent character set suggests.','Length can provide a large security benefit.','For real accounts, use unique generated passwords and multi-factor authentication where available.'],
 'faqs'=>[
  ['What is password entropy?','It is a measure commonly expressed in bits that estimates how difficult a password may be to guess under a specified model.'],
  ['Is more entropy always better?','Higher estimated entropy generally means a larger search space, but account security also depends on rate limiting, password reuse, breaches and other controls.'],
  ['Can entropy tell me whether my password was leaked?','No. Entropy calculations do not check breach databases or establish whether a specific password has been exposed.'],
 ]
],
'password-generator-advanced'=>[
 'intro'=>'Create customizable random passwords when a website or development environment has specific length and character requirements.',
 'when'=>'Helpful when a normal generator does not provide enough control over symbols, numbers, casing or length.',
 'steps'=>[
  ['Choose a length','Set a length that is practical for the service and as long as the service allows.'],
  ['Select character types','Enable the character groups required by your target, such as uppercase, lowercase, numbers and symbols.'],
  ['Avoid unnecessary restrictions','If the destination accepts all generated characters, do not reduce the character pool without a reason.'],
  ['Generate and inspect','Create the password and verify it meets the target policy. Watch for characters that could be confused when manually reading a value.'],
  ['Store it securely','Save the credential in a password manager and use it only for the intended account or environment.'],
 ],
 'tips'=>['Prefer length over arbitrary complexity rules when the destination allows it.','Keep a separate credential for each service.','For automated systems, test generated credentials against the destination’s actual allowed character set.'],
 'faqs'=>[
  ['When should I use an advanced generator?','Use it when you need control over password length or character categories beyond the basic generator options.'],
  ['Can I generate passwords for development?','Yes, but never use production secrets as test data. Use separate development credentials.'],
  ['What if a website rejects a generated symbol?','Check the site’s password policy and regenerate using only the character types it accepts.'],
 ]
],
'password-hash-generator'=>[
 'intro'=>'Generate a password hash for development and testing, while understanding that hashing and password storage are not the same thing as encrypting a password.',
 'when'=>'Useful for learning how deterministic hash functions behave or for non-production testing where a hash value is needed.',
 'steps'=>[
  ['Enter test input','Use a test password or non-sensitive sample when experimenting. Do not paste production secrets into an unfamiliar service.'],
  ['Select the available hash method','Choose the hashing algorithm provided by the tool and confirm it matches the format expected by your application or test.'],
  ['Generate the hash','Run the tool to produce the corresponding hash value.'],
  ['Compare or verify','For a known test input, compare the output with your expected value. A changed input should produce a different digest.'],
  ['Use the right production approach','For real application password storage, use a dedicated password-hashing function such as Argon2id, bcrypt or scrypt through your platform’s security library rather than treating a generic hash as password storage.'],
 ],
 'tips'=>['Hashing is one-way in intent; encryption is designed for later decryption.','Never store users’ plaintext passwords.','Use a vetted password-hashing library with salts and an appropriate work factor for production authentication.'],
 'faqs'=>[
  ['Is a hash the same as encryption?','No. A cryptographic hash is designed to produce a digest that is not normally reversed, while encryption is designed to be decrypted with a key.'],
  ['Can I use a generic hash as a production password hash?','Generally no. Password storage needs a password-specific scheme with salting and deliberately expensive computation.'],
  ['Why does the same input usually produce the same hash?','A deterministic hash function maps the same input to the same digest. Password-hashing schemes add salts specifically to avoid simple identical-output behavior across accounts.'],
 ]
],
'password-pattern-checker'=>[
 'intro'=>'Inspect a password for predictable patterns such as repeated characters, sequences and familiar structures that people commonly choose.',
 'when'=>'Useful for security awareness and for spotting weak construction habits before a credential is put into use.',
 'steps'=>[
  ['Enter a test value','Use a sample password when possible. Avoid exposing an active password unnecessarily.'],
  ['Run the pattern check','Let the checker inspect the visible structure of the value.'],
  ['Read each warning','Look for repeated characters, ascending or descending sequences, keyboard-style patterns or other predictable construction.'],
  ['Replace predictable structure','Choose a longer random password rather than simply changing one character in an obvious pattern.'],
  ['Store the replacement securely','Save the final unique password in a password manager.'],
 ],
 'tips'=>['Small edits to a predictable password often remain predictable.','Avoid names, dates and keyboard walks.','Use a unique generated password for important accounts.'],
 'faqs'=>[
  ['What is a password pattern?','It is a recognizable structure that can reduce the unpredictability of a password, such as sequences or repeated characters.'],
  ['Is a random-looking password guaranteed to be safe?','No. Pattern analysis is only one signal and cannot determine whether a password has been exposed elsewhere.'],
  ['What is the best response to a weak pattern warning?','Replace the password with a longer, unique, randomly generated credential rather than making a predictable minor change.'],
 ]
],
'password-policy-checker'=>[
 'intro'=>'Check whether a password meets common policy requirements before you submit it to a service or application.',
 'when'=>'Helpful during account setup, QA and development when a password must satisfy a known set of minimum rules.',
 'steps'=>[
  ['Enter a test password','Provide the password or sample value you want to evaluate. Use test data when working with sensitive systems.'],
  ['Review the policy checks','Look at which requirements pass or fail, such as minimum length, uppercase, lowercase, numbers or symbols.'],
  ['Fix the failed requirements','Adjust the password or, preferably, generate a new unique credential that naturally meets the policy.'],
  ['Check again','Run the checker again until the required conditions pass.'],
  ['Apply the real service policy','Before saving the credential, confirm the destination’s current password rules because its requirements may differ from a generic checker.'],
 ],
 'tips'=>['Policy compliance is not the same as strong security.','Long unique passwords are preferable to short passwords padded only to satisfy rules.','For applications, document the actual authentication policy separately from a client-side check.'],
 'faqs'=>[
  ['Why can a password pass a policy but still be weak?','A policy can check visible requirements without measuring predictability, reuse or exposure in a breach.'],
  ['Should I follow a website’s exact password rules?','Yes. The website’s current requirements determine whether the credential can be accepted.'],
  ['Can this replace server-side validation?','No. In an application, security-sensitive password validation must be enforced on the server as well.'],
 ]
],
'password-rule-audit'=>[
 'intro'=>'Audit the visible rules applied to a password, including length and character requirements, to identify gaps in a password policy.',
 'when'=>'Useful for reviewing a proposed password requirement set or checking why a sample credential does or does not satisfy expected rules.',
 'steps'=>[
  ['Provide the sample','Enter a test value or sample credential rather than a sensitive production secret.'],
  ['Review every rule','Read the audit result line by line instead of relying only on an overall pass/fail result.'],
  ['Identify weak requirements','Notice rules that allow short, predictable or reused passwords even if they technically satisfy the policy.'],
  ['Improve the credential','Prefer longer unique credentials and modern authentication practices where the service supports them.'],
  ['Document the final policy','For development teams, record the intended requirements so the client and server implementations can be tested consistently.'],
 ],
 'tips'=>['Avoid policies that encourage predictable substitutions or forced periodic changes without a security reason.','Combine passwords with MFA where possible.','Treat policy checks as one layer, not the entire authentication strategy.'],
 'faqs'=>[
  ['What does a rule audit tell me?','It shows whether the supplied value satisfies the rules the audit checks and helps expose missing or weak requirements.'],
  ['Is a passed audit proof of security?','No. It does not prove that a password is unique, unexposed or resistant to every attack.'],
  ['Can this help with application testing?','Yes. Test values can be used to verify that expected password rules are consistently represented during development and QA.'],
 ]
],
'password-token-generator'=>[
 'intro'=>'Generate random token strings for development, testing and non-user-facing application values where a random token is appropriate.',
 'when'=>'Useful for test fixtures, temporary identifiers and development workflows that need random strings. Production authentication tokens should follow the security design of the application that consumes them.',
 'steps'=>[
  ['Choose token settings','Select the length and options offered by the generator according to what your test or application expects.'],
  ['Generate the token','Run the generator to create a new random value.'],
  ['Copy it carefully','Copy the complete token without adding spaces or changing characters.'],
  ['Use it only for the intended purpose','Do not place a generated test token into production configuration unless it has been reviewed for that exact use.'],
  ['Rotate or discard temporary values','Remove test tokens when they are no longer needed and rotate sensitive production credentials according to your application’s security policy.'],
 ],
 'tips'=>['Do not commit real secrets or long-lived tokens to source control.','Use environment variables or a secrets manager for production credentials.','If a token grants access, treat it like a password: protect it and rotate it when exposed.'],
 'faqs'=>[
  ['What is a token?','A token is a string used by software for purposes such as identification, temporary access or authentication. Its security depends on how the consuming system uses it.'],
  ['Can I use generated tokens in production?','Only when the token format, entropy, lifetime, storage and rotation are appropriate for the application.'],
  ['Should tokens be committed to Git?','Sensitive access tokens should not be committed to a public or shared repository. Use a suitable secret-management mechanism instead.'],
 ]
],
'age-calculator'=>[
 'intro'=>'Calculate a person’s age from a date of birth and get a clear result in years, months and days.',
 'when'=>'Useful for forms, eligibility checks, planning birthdays and quickly understanding the elapsed age between a birth date and the calculation date.',
 'steps'=>[
  ['Enter the date of birth','Choose the correct day, month and year. Double-check the date format if you are working from an international document.'],
  ['Choose the calculation date','Use today’s date or another date when you need to calculate age at a specific point in time.'],
  ['Calculate','Run the calculator to produce the age result.'],
  ['Read the detailed result','Check the years, months and days shown. For boundary cases around birthdays, verify the dates carefully.'],
  ['Use the result in context','Copy the result or use it as a quick reference. For official eligibility or legal decisions, confirm the applicable rules with the relevant authority.'],
 ],
 'tips'=>['Enter the correct calendar date; a one-day error changes the result.','For official forms, follow the date format requested by the organization.','Age-based legal eligibility can depend on jurisdiction-specific rules beyond a simple date calculation.'],
 'faqs'=>[
  ['Does the calculator use the current date?','It can calculate from the supplied date of birth against the calculation date used by the tool.'],
  ['Can I calculate age on a past or future date?','Use the date controls provided by the calculator when you need an age at a specific date.'],
  ['Is the result suitable for legal eligibility?','It is a date calculation. Official eligibility can depend on laws and definitions that should be checked separately.'],
 ]
],
'color-picker'=>[
 'intro'=>'Pick a color visually and copy its HEX, RGB, HSL or CSS representation for use in a design, website or stylesheet.',
 'when'=>'Useful when matching a UI color, checking a brand color, creating a CSS value or translating a color between common formats.',
 'steps'=>[
  ['Pick or enter a color','Use the color control to select the shade you need. If the tool accepts a color value, you can start from a known HEX or equivalent value.'],
  ['Review the color values','Check the generated HEX, RGB and HSL representations. These are different ways of describing the same color.'],
  ['Choose the format your project needs','Use HEX for common CSS declarations, RGB when working with channel values, or HSL when adjusting hue, saturation and lightness.'],
  ['Copy the value','Copy the required value directly from the tool.'],
  ['Test it in the destination','Paste the value into your design or CSS and inspect it against the surrounding colors, text and accessibility requirements.'],
 ],
 'tips'=>['Do not judge contrast from the color swatch alone; check text contrast where accessibility matters.','Keep brand colors documented with their exact values.','HSL can be convenient when creating lighter or darker variations of an existing color.'],
 'faqs'=>[
  ['What is HEX?','HEX is a compact hexadecimal representation commonly used for RGB colors in web development.'],
  ['What is HSL?','HSL represents a color using hue, saturation and lightness, which can make certain visual adjustments easier to reason about.'],
  ['Which format should I use in CSS?','Choose the representation that best fits your codebase. Modern CSS supports several color notations, including HEX, RGB and HSL.'],
 ]
],
'pdf-to-jpg'=>[
 'intro'=>'Convert PDF pages into JPG images when you need a shareable image preview, a page snapshot or a raster version of a PDF page.',
 'when'=>'Useful for sharing individual document pages as images, creating previews or using a PDF page in a workflow that accepts JPG rather than PDF.',
 'steps'=>[
  ['Select the PDF','Choose the PDF you want to convert. Keep the original document so you can return to the source if the image output is not suitable.'],
  ['Wait for the pages to render','The converter processes the document and prepares image output for its pages. Large or image-heavy PDFs may take longer.'],
  ['Review the page images','Inspect text, tables and small details at a useful zoom. Raster images can make tiny text harder to read than the original PDF.'],
  ['Choose the required output','If the tool offers page selection or download controls, select the pages you actually need rather than creating unnecessary files.'],
  ['Download and verify','Download the JPG output and open it before sharing. Confirm that the correct page and orientation were preserved.'],
 ],
 'tips'=>['Keep the original PDF as the authoritative source document.','For searchable text or print-quality document workflows, PDF may be preferable to JPG.','Check image dimensions when the converted page will be printed or reused in another design.'],
 'faqs'=>[
  ['Does PDF to JPG preserve selectable text?','No. JPG is a raster image, so text in the output is no longer normally selectable as text.'],
  ['Why can a JPG look different from the PDF?','Rendering converts vector text and graphics into pixels, and the output resolution can affect sharpness.'],
  ['Should I convert every page?','Only convert the pages you actually need for your workflow when page-level image files are required.'],
 ]
],
];

$guide = $guides[$slug] ?? null;
if (!$guide) {
    $guide = [
      'intro'=>'Learn the practical workflow for using this SmartToolz utility, including what to prepare, how to check the result and when to use the output.',
      'when'=>'Use this guide when you are trying the tool for the first time or want a quick checklist before using its result in another workflow.',
      'steps'=>[
        ['Prepare your input','Open the tool and gather the text, file, value or other input it asks for. Use a copy when the original is important.'],
        ['Enter the input carefully','Follow the field labels and examples. Check units, file type, formatting and required values before running the tool.'],
        ['Run the tool','Use the main action control and wait for the result. If an error appears, read the message and correct the input rather than repeatedly submitting the same value.'],
        ['Review the output','Compare the result with your original input and the purpose of your task. Check important values, formatting and downloaded files.'],
        ['Use or download the result','When it looks correct, copy or download it and keep the original input when you may need to reproduce the task later.'],
      ],
      'tips'=>['Use a copy of important files or data.','Read the output before publishing, sending or deploying it.','For sensitive information, follow the tool’s privacy information and avoid entering real secrets unless necessary.'],
      'faqs'=>[
        ['Is this tool free?','SmartToolz provides free browser-based utilities for everyday digital tasks.'],
        ['Do I need an account?','The normal SmartToolz tool workflow does not require an account.'],
        ['Can I trust the result without checking it?','For routine tasks, the result may be ready to use, but important outputs should always be reviewed before they are published, submitted or deployed.'],
      ]
    ];
}

$steps = $guide['steps'];
$faqs = $guide['faqs'];
$title = 'How to Use ' . $name . ' — Step-by-Step Guide';
$meta = 'Learn how to use ' . $name . ' with practical step-by-step instructions, tips, examples and FAQs. A focused SmartToolz guide for ' . strtolower($category) . '.';
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?> | SmartToolz</title>
<meta name="description" content="<?= htmlspecialchars($meta, ENT_QUOTES, 'UTF-8') ?>">
<meta name="robots" content="index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1">
<link rel="canonical" href="<?= htmlspecialchars($canonical, ENT_QUOTES, 'UTF-8') ?>">
<meta property="og:type" content="article"><meta property="og:site_name" content="SmartToolz"><meta property="og:title" content="<?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?>"><meta property="og:description" content="<?= htmlspecialchars($meta, ENT_QUOTES, 'UTF-8') ?>"><meta property="og:url" content="<?= htmlspecialchars($canonical, ENT_QUOTES, 'UTF-8') ?>">
<?php require dirname(__DIR__) . '/head.php'; ?>
<style>
.how-page{width:min(1050px,calc(100% - 32px));margin:0 auto 80px}.how-hero{text-align:center;padding:68px 0 44px}.how-hero .eyebrow{color:#5b43ff;font-size:10px;font-weight:900;letter-spacing:1.8px}.how-hero h1{margin:14px auto;max-width:850px;font-size:clamp(38px,6vw,62px);line-height:1.02;letter-spacing:-3px;color:#111936}.how-hero p{max-width:760px;margin:auto;color:#667085;font-size:15px;line-height:1.8}.how-hero-actions{display:flex;justify-content:center;gap:12px;margin-top:26px;flex-wrap:wrap}.how-btn{display:inline-flex;align-items:center;justify-content:center;padding:12px 18px;border-radius:11px;text-decoration:none;font-size:13px;font-weight:800;background:#5542ff;color:#fff}.how-btn.secondary{background:#f1efff;color:#5542ff}.how-layout{display:grid;grid-template-columns:minmax(0,1fr) 300px;gap:22px;align-items:start}.how-card,.how-side-card{background:#fff;border:1px solid #e7eaf0;border-radius:22px;box-shadow:0 14px 38px rgba(16,24,40,.055)}.how-card{padding:30px}.how-card h2{margin:0 0 12px;color:#111936;font-size:27px;letter-spacing:-1px}.how-card>p,.how-side-card p{color:#475467;font-size:14px;line-height:1.8}.how-when{margin:18px 0 5px;padding:17px 18px;border-radius:15px;background:#f7f7fb;border:1px solid #ececf3}.how-when strong{display:block;font-size:13px;color:#17213f;margin-bottom:5px}.how-when p{margin:0;color:#667085;font-size:12px;line-height:1.7}.how-step{display:grid;grid-template-columns:42px 1fr;gap:15px;padding:20px 0;border-top:1px solid #edf0f4}.how-number{display:grid;place-items:center;width:42px;height:42px;border-radius:13px;background:#f0edff;color:#5542ff;font-size:13px;font-weight:900}.how-step h3{margin:1px 0 6px;font-size:16px;color:#17213f}.how-step p{margin:0;color:#667085;font-size:13px;line-height:1.7}.how-tips{margin-top:18px;padding:20px;border:1px solid #e8e6ff;border-radius:16px;background:#faf9ff}.how-tips h3{margin:0 0 8px;font-size:15px;color:#17213f}.how-tips ul{margin:0;padding-left:19px;color:#667085;font-size:12px;line-height:1.8}.how-side{display:grid;gap:16px}.how-side-card{padding:22px;border-radius:18px}.how-side-card h2{font-size:16px;margin:0 0 10px;color:#111936}.how-side-card p{margin:0;font-size:13px}.how-side-card a{display:inline-block;margin-top:13px;color:#5542ff;font-size:12px;font-weight:800;text-decoration:none}.how-faq{margin-top:28px}.how-faq details{border-top:1px solid #edf0f4;padding:16px 0}.how-faq summary{cursor:pointer;font-weight:800;font-size:14px;color:#17213f}.how-faq p{margin:8px 0 0;color:#667085;font-size:13px;line-height:1.7}.how-bottom{margin-top:22px;text-align:center;padding:28px;border-radius:20px;background:#111936;color:#fff}.how-bottom h2{margin:0 0 8px;font-size:23px}.how-bottom p{margin:0 0 18px;color:#c9d0e1;font-size:13px}.how-bottom .how-btn{background:#fff;color:#5542ff}@media(max-width:800px){.how-layout{grid-template-columns:1fr}}@media(max-width:560px){.how-page{width:calc(100% - 20px)}.how-hero{padding:48px 0 32px}.how-hero h1{letter-spacing:-2px}.how-card{padding:22px}.how-step{grid-template-columns:36px 1fr}.how-number{width:36px;height:36px}}
</style>
<script type="application/ld+json">
<?= json_encode(['@context'=>'https://schema.org','@type'=>'HowTo','name'=>'How to Use '.$name,'description'=>$guide['intro'],'url'=>$canonical,'step'=>array_map(static fn($s,$i)=>['@type'=>'HowToStep','position'=>$i+1,'name'=>$s[0],'text'=>$s[1]],$steps,array_keys($steps)),'mainEntity'=>array_map(static fn($f)=>['@type'=>'Question','name'=>$f[0],'acceptedAnswer'=>['@type'=>'Answer','text'=>$f[1]]],$faqs)], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) ?>
</script>
</head>
<body>
<?php require dirname(__DIR__) . '/header.php'; ?>
<main class="how-page">
<section class="how-hero"><span class="eyebrow">HOW TO USE • <?= htmlspecialchars(strtoupper($category), ENT_QUOTES, 'UTF-8') ?></span><h1><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></h1><p><?= htmlspecialchars($guide['intro'], ENT_QUOTES, 'UTF-8') ?></p><div class="how-hero-actions"><a class="how-btn" href="<?= htmlspecialchars($tool['url'], ENT_QUOTES, 'UTF-8') ?>">Open <?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?> →</a><a class="how-btn secondary" href="/tools/">Browse all tools</a></div></section>
<div class="how-layout"><article class="how-card"><h2>Step-by-step instructions</h2><div class="how-when"><strong>When is this useful?</strong><p><?= htmlspecialchars($guide['when'], ENT_QUOTES, 'UTF-8') ?></p></div><?php foreach($steps as $i=>[$stepTitle,$text]): ?><div class="how-step"><span class="how-number"><?= $i+1 ?></span><div><h3><?= htmlspecialchars($stepTitle, ENT_QUOTES, 'UTF-8') ?></h3><p><?= htmlspecialchars($text, ENT_QUOTES, 'UTF-8') ?></p></div></div><?php endforeach; ?><div class="how-tips"><h3>Practical tips</h3><ul><?php foreach($guide['tips'] as $tip): ?><li><?= htmlspecialchars($tip, ENT_QUOTES, 'UTF-8') ?></li><?php endforeach; ?></ul></div><div class="how-faq"><h2>Frequently asked questions</h2><?php foreach($faqs as [$q,$a]): ?><details><summary><?= htmlspecialchars($q, ENT_QUOTES, 'UTF-8') ?></summary><p><?= htmlspecialchars($a, ENT_QUOTES, 'UTF-8') ?></p></details><?php endforeach; ?></div></article><aside class="how-side"><div class="how-side-card"><h2>About this guide</h2><p>This guide is written specifically for <strong><?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?></strong>. It explains the task, the important checks and the practical limitations to consider.</p><a href="<?= htmlspecialchars($tool['url'], ENT_QUOTES, 'UTF-8') ?>">Open the tool →</a></div><div class="how-side-card"><h2>Category</h2><p>Explore more utilities in <strong><?= htmlspecialchars($category, ENT_QUOTES, 'UTF-8') ?></strong>.</p><a href="/tools/?category=<?= rawurlencode(smarttoolz_slug($category)) ?>">Browse category →</a></div><div class="how-side-card"><h2>Need another tool?</h2><p>Browse SmartToolz to find another free utility for your next task.</p><a href="/tools/">Explore all tools →</a></div></aside></div>
<section class="how-bottom"><h2>Ready to try it?</h2><p>Open the tool and use these instructions as your quick reference.</p><a class="how-btn" href="<?= htmlspecialchars($tool['url'], ENT_QUOTES, 'UTF-8') ?>">Use <?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?> →</a></section>
</main>
<?php require dirname(__DIR__) . '/footer.php'; ?>
</body>
</html>

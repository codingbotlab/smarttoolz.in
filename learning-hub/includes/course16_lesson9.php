<?php
declare(strict_types=1);

/* Course 16 Lesson 9: topic-specific imagery and iconography content. */
function lh_course16_lesson9_override(array $lesson, int $position): ?string
{
    $slug = (string)($lesson['slug'] ?? '');
    if ($slug !== 'course-16-lesson-9' && $position !== 9) {
        return null;
    }

    return <<<'HTML'
<article class="lh-prose">
  <div class="lh-lesson-intro">
    <p><strong>Images and icons are visual language.</strong> They can explain a product, establish a mood, show evidence, or help a viewer recognise an action before reading every word. Good imagery supports the message; weak imagery competes with it.</p>
  </div>

  <h2>1. Choose imagery for a job</h2>
  <p>Start by asking what the image needs to accomplish. A product photo may need to show accurate detail. A hero image may need to create atmosphere. An instructional graphic may need to make a process easier to understand. If you cannot describe the image's job, it is often being used only as decoration.</p>
  <div class="table-responsive"><table class="table table-bordered align-middle"><thead><tr><th>Imagery job</th><th>Useful approach</th><th>What to check</th></tr></thead><tbody>
    <tr><td>Explain</td><td>Use a diagram, screenshot or clearly framed photo.</td><td>Can the viewer understand the subject quickly?</td></tr>
    <tr><td>Prove</td><td>Show the actual product, result or evidence.</td><td>Is the image accurate and trustworthy?</td></tr>
    <tr><td>Set mood</td><td>Use composition, lighting and colour intentionally.</td><td>Does the feeling support the message?</td></tr>
    <tr><td>Guide action</td><td>Use a visual cue or icon near the relevant action.</td><td>Does the cue clarify rather than distract?</td></tr>
  </tbody></table></div>

  <h2>2. Image selection: relevance before beauty</h2>
  <p>A beautiful photograph can still be the wrong choice. Look for subject relevance, composition, resolution, lighting, visual tone and the amount of usable negative space. If text must sit beside or over an image, choose a photograph with a quiet area where the text can remain readable.</p>
  <p>Also check whether the image matches the audience and context. A polished corporate page, a playful children's poster and a technical tutorial should not automatically use the same visual language.</p>

  <h2>3. Crop with intention</h2>
  <p>Cropping changes meaning. A close crop can increase emphasis and energy; a wider crop can provide context. When cropping a person or product, protect the important subject details and avoid accidental cuts through visually important areas.</p>
  <div class="alert alert-primary"><strong>Smart tip:</strong> Design the crop for the final placement. An image that looks excellent as a square may fail when converted to a wide banner or a tall mobile card.</div>

  <h2>4. Resolution and output size</h2>
  <p>Use enough source resolution for the intended output, but do not treat a huge file as automatically better. Oversized images increase loading time and storage requirements. Export an appropriately sized version for the actual placement and keep an original source separately when future editing is likely.</p>
  <p>For digital work, inspect the final image at the size people will actually see it. Small details that look impressive while zoomed in may disappear on a phone.</p>

  <h2>5. Icons are a system, not a collection</h2>
  <p>Icons work best when they share a visual grammar. Decide whether the set is outline, filled, rounded, geometric or another consistent style. Keep stroke weight, corner treatment, proportions and visual complexity reasonably consistent.</p>
  <p>A single icon can be attractive while still being wrong for the system. If five interface icons use thin outlines and one uses a heavy filled shape, the outlier can look like a different product or signal a different meaning.</p>

  <h2>6. Icon clarity and recognisability</h2>
  <p>An icon should communicate its intended meaning quickly. Prefer familiar symbols when the action is familiar. If a symbol could be interpreted in several ways, pair it with a label instead of forcing users to guess.</p>
  <p>Test icons at their smallest intended size. Details that are visible at 64 px may turn into noise at 16 or 20 px. Simplify rather than adding more detail.</p>

  <h2>7. Consistency with the rest of the design</h2>
  <p>Imagery should connect with typography, colour and layout. For example, a restrained brand palette paired with extremely saturated stock photography can create a visual conflict. Similarly, highly detailed illustrations may overwhelm a layout designed around generous whitespace.</p>
  <p>Create simple rules for a project: preferred image treatment, corner radius, icon style, aspect ratios, background treatment and spacing around visual assets. These rules make later designs faster and more consistent.</p>

  <h2>8. Accessibility and meaning</h2>
  <p>Important information should not depend only on an image. Provide meaningful text alternatives where the platform supports them, keep essential instructions visible as text, and avoid using decorative imagery as a substitute for a required label.</p>
  <p>For icons, do not assume a symbol is self-explanatory for every user. A tooltip, accessible label or visible text can remove ambiguity, especially for less familiar actions.</p>

  <h2>Real-world example: a course landing-page hero</h2>
  <p>Imagine a graphic-design course page. Choose an image that shows a believable design workspace rather than a generic decorative photo. Reserve a calm area for the headline, make sure the crop works on both desktop and mobile, and use one consistent icon style for the course benefits. The image establishes context while typography and layout deliver the actual information.</p>

  <h2>Practical exercise: build a small visual kit</h2>
  <ol>
    <li>Choose one project, such as a course page or social campaign.</li>
    <li>Select three images that could serve different purposes: hero, supporting detail and proof/example.</li>
    <li>For each image, write one sentence explaining why it belongs in the design.</li>
    <li>Create a set of four icons using one consistent visual style.</li>
    <li>Place the images and icons into a simple layout using the grid and hierarchy principles from earlier lessons.</li>
    <li>View the result at thumbnail and phone sizes and remove any visual asset that does not add useful information.</li>
  </ol>

  <h2>Asset checklist</h2>
  <div class="table-responsive"><table class="table table-bordered align-middle"><thead><tr><th>Check</th><th>Question</th></tr></thead><tbody>
    <tr><td>Purpose</td><td>What job does this image or icon perform?</td></tr>
    <tr><td>Relevance</td><td>Does it support the actual message and audience?</td></tr>
    <tr><td>Crop</td><td>Does the composition survive the final aspect ratio?</td></tr>
    <tr><td>Quality</td><td>Is the asset sharp enough without being unnecessarily large?</td></tr>
    <tr><td>Consistency</td><td>Does its style belong to the project's visual system?</td></tr>
    <tr><td>Clarity</td><td>Can the viewer understand the visual without guessing?</td></tr>
    <tr><td>Accessibility</td><td>Is essential information available without depending only on the visual?</td></tr>
  </tbody></table></div>

  <h2>Common mistakes</h2>
  <ul>
    <li>Choosing an image because it looks attractive without checking its communication purpose.</li>
    <li>Using low-resolution assets and trying to rescue them with sharpening.</li>
    <li>Ignoring how a crop changes when the same design is viewed on mobile.</li>
    <li>Mixing unrelated icon styles in one interface or graphic.</li>
    <li>Adding icons everywhere until the visual hierarchy becomes noisy.</li>
    <li>Using an icon as the only explanation for an unfamiliar action.</li>
    <li>Forgetting usage rights, attribution requirements or the source of an asset.</li>
  </ul>

  <h2>Quick self-check</h2>
  <ol>
    <li>What is the difference between decorative imagery and functional imagery?</li>
    <li>Why should a crop be tested at the final aspect ratio?</li>
    <li>What visual properties should remain consistent across an icon set?</li>
    <li>How can an icon become unclear at a small size?</li>
    <li>What information in your design would still need to work if the image were removed?</li>
  </ol>

  <h2>Key takeaway</h2>
  <p><strong>Choose visual assets for communication, then make them fit the system.</strong> Relevant imagery, deliberate crops, appropriately sized files, consistent icons and accessible labels make a design clearer without adding unnecessary visual noise.</p>
</article>
HTML;
}

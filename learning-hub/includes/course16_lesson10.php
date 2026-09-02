<?php
declare(strict_types=1);

/* Course 16 Lesson 10: Branding Basics — topic-specific content. */
function lh_course16_lesson10_override(array $lesson, int $position): ?string
{
    $slug = (string)($lesson['slug'] ?? '');
    if ($slug !== 'course-16-lesson-10' && $position !== 10) {
        return null;
    }

    return <<<'HTML'
<article class="lh-prose">
  <div class="lh-lesson-intro">
    <p><strong>Branding is the system that makes a business, product or project recognisable and consistent.</strong> A logo is only one part of that system. A useful brand combines a clear promise with repeatable choices in colour, typography, imagery, layout, voice and usage.</p>
  </div>

  <h2>1. What a brand actually does</h2>
  <p>A brand helps people form an expectation about an organisation or product. When someone sees the same visual and verbal signals repeatedly, those signals become associated with the experience behind them. Good branding therefore starts with meaning and audience, not decoration.</p>
  <p>For a beginner design project, ask three questions: <strong>Who is this for?</strong> <strong>What should they expect?</strong> <strong>What should make the project recognisable next time?</strong></p>

  <h2>2. Brand identity vs. logo</h2>
  <div class="table-responsive"><table class="table table-bordered align-middle"><thead><tr><th>Part</th><th>Purpose</th><th>Example decision</th></tr></thead><tbody>
    <tr><td>Logo</td><td>A recognisable mark or wordmark.</td><td>Choose a simple mark that remains identifiable at small sizes.</td></tr>
    <tr><td>Colour system</td><td>Creates a repeatable visual association.</td><td>Define primary, supporting and accent colours.</td></tr>
    <tr><td>Typography</td><td>Controls tone and information hierarchy.</td><td>Specify a heading and body type treatment.</td></tr>
    <tr><td>Imagery</td><td>Creates a consistent visual mood.</td><td>Define preferred photo, illustration or icon styles.</td></tr>
    <tr><td>Layout language</td><td>Creates consistency across different pieces.</td><td>Reuse spacing, alignment and composition rules.</td></tr>
    <tr><td>Voice</td><td>Controls how the brand communicates in words.</td><td>Decide whether copy should feel direct, friendly, technical or formal.</td></tr>
  </tbody></table></div>

  <h2>3. Start with audience and positioning</h2>
  <p>Before selecting a colour palette, describe the intended audience and the promise the brand needs to communicate. A children's learning product, a premium consulting service and a technical developer tool may all need professional design, but they should not automatically look identical.</p>
  <p>Write a one-sentence positioning statement such as: <em>“For beginner creators, this course platform provides practical design skills in short, approachable lessons.”</em> The sentence becomes a filter for later visual decisions.</p>

  <h2>4. Build a small visual identity</h2>
  <p>You do not need a fifty-page brand manual for a small project. Start with a compact set of rules that another designer could follow without asking you to approve every graphic.</p>
  <ol>
    <li>Choose one primary type family and define how headings and body text are used.</li>
    <li>Select a small colour palette with named roles such as background, primary, supporting and accent.</li>
    <li>Define the image direction: for example, clean product photography, candid people photography or simple geometric illustrations.</li>
    <li>Choose an icon style and keep stroke weight, corner treatment and visual complexity consistent.</li>
    <li>Set a spacing rhythm and a few layout rules for repeated content.</li>
  </ol>

  <div class="alert alert-primary"><strong>Smart tip:</strong> Consistency does not mean every graphic must look identical. Keep the underlying rules stable while allowing the content and composition to change.</div>

  <h2>5. Make colour serve the brand</h2>
  <p>Brand colours should have jobs. A primary colour may identify the brand, a neutral may carry most of the interface, and an accent may signal actions or important information. If five colours compete for attention, the viewer loses the visual cue that makes the system recognisable.</p>
  <p>Record colour values rather than relying on memory. A simple palette sheet can include HEX values for digital work and, when print is involved, the appropriate print specifications supplied by the production workflow.</p>

  <h2>6. Make typography repeatable</h2>
  <p>Brand typography is more useful when it is defined as a system. For example, specify a display style for major headings, a body style for readable paragraphs, and one emphasis treatment for important information. Avoid changing fonts simply because a particular post needs more visual excitement.</p>
  <p>Test the type system in different situations: a headline, a long paragraph, a button or call to action, and a small mobile card. A brand type choice that works only in a large logo is not a complete typography system.</p>

  <h2>7. Create rules for imagery and icons</h2>
  <p>Decide what makes an image feel like it belongs to the brand. Consider subject matter, lighting, colour treatment, crop style, background and level of detail. Apply the same thinking to icons and illustrations.</p>
  <p>This connects directly to the previous lesson: a collection of individually attractive images can still feel inconsistent if each uses a different visual language.</p>

  <h2>8. Design a simple brand touchpoint</h2>
  <p>A brand becomes real when its rules are used in an actual communication piece. Choose one touchpoint such as a social post, course card, event poster or landing-page header. Include the logo or wordmark only where it adds recognition; do not use it as a substitute for hierarchy.</p>
  <p>Build the touchpoint using the same colours, typography, imagery and spacing rules. Then remove the logo temporarily and ask whether the remaining visual system still feels connected to the brand.</p>

  <h2>Practical exercise: make a one-page mini brand guide</h2>
  <ol>
    <li>Invent a small brand for a realistic audience, such as a local design workshop, study app or online course.</li>
    <li>Write its audience, promise and three personality words.</li>
    <li>Choose two or three brand colours and give each a functional role.</li>
    <li>Choose a heading and body type treatment.</li>
    <li>Define an imagery direction and an icon style in one sentence each.</li>
    <li>Create one social post using the rules.</li>
    <li>Create a second post with different content but the same rules.</li>
    <li>Compare the two posts. If they look unrelated, identify which rule was applied inconsistently.</li>
  </ol>

  <h2>Brand consistency checklist</h2>
  <div class="table-responsive"><table class="table table-bordered align-middle"><thead><tr><th>Check</th><th>Question</th></tr></thead><tbody>
    <tr><td>Audience</td><td>Is the visual personality appropriate for the intended people?</td></tr>
    <tr><td>Recognition</td><td>Could someone recognise the brand from more than just the logo?</td></tr>
    <tr><td>Colour</td><td>Do colours have clear roles instead of competing equally?</td></tr>
    <tr><td>Typography</td><td>Are type choices repeatable across headings, body copy and actions?</td></tr>
    <tr><td>Imagery</td><td>Do photos, illustrations and icons share a visual direction?</td></tr>
    <tr><td>Layout</td><td>Are alignment, spacing and hierarchy rules consistent?</td></tr>
    <tr><td>Flexibility</td><td>Can the system handle new content without every design becoming a copy?</td></tr>
  </tbody></table></div>

  <h2>Common branding mistakes</h2>
  <ul>
    <li>Treating the logo as the entire brand identity.</li>
    <li>Choosing colours because they look good without defining their roles.</li>
    <li>Changing fonts, image styles or icon styles from one graphic to the next.</li>
    <li>Copying a competitor's visual style instead of defining a distinct audience promise.</li>
    <li>Creating rules so rigid that every piece of content becomes visually identical.</li>
    <li>Making a brand guide that looks polished but gives no practical instructions to a designer.</li>
  </ul>

  <h2>Quick self-check</h2>
  <ol>
    <li>Why is a logo only one part of a brand identity?</li>
    <li>What job should a brand colour perform?</li>
    <li>How can typography become a repeatable brand rule?</li>
    <li>What makes two different graphics feel like they belong to the same brand?</li>
    <li>Could another designer reproduce your visual style from your mini brand guide?</li>
  </ol>

  <h2>Key takeaway</h2>
  <p><strong>A strong brand is a repeatable visual and verbal system built around an audience and a clear promise.</strong> Define the meaning first, then make colour, type, imagery, icons and layout reinforce that meaning consistently.</p>
</article>
HTML;
}

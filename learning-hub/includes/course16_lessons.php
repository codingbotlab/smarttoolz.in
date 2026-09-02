<?php
declare(strict_types=1);

/* Course 16: one lesson at a time, topic-specific content. */
function lh_course16_override(array $lesson, int $position): ?string
{
    $slug = (string)($lesson['slug'] ?? '');

    if ($slug === 'course-16-lesson-5' || $position === 5) {
        return <<<'HTML'
<article class="lh-prose">
  <div class="lh-lesson-intro">
    <p><strong>Design principles are the rules that help visual elements work together.</strong> The elements from the previous lesson are your raw materials; principles such as hierarchy, contrast, balance, alignment, proximity, repetition and unity help you turn those materials into a clear composition.</p>
  </div>

  <h2>1. Hierarchy: decide what gets noticed first</h2>
  <p>Hierarchy creates a visual order. A viewer should be able to recognise the most important message before reading every detail. Size, weight, colour, position and whitespace can all create hierarchy.</p>
  <p>For a workshop poster, the event name might be largest, the date and time next, and supporting details smaller. If every line is equally large and bold, the viewer has to work harder to understand the message.</p>

  <h2>2. Contrast: create useful difference</h2>
  <p>Contrast separates things that should not look the same. You can create contrast through size, colour, weight, shape, texture or spacing. Good contrast improves scanning and can make an important call to action easier to find.</p>
  <div class="alert alert-primary"><strong>Smart tip:</strong> Contrast is not the same as making everything loud. One strong difference is often more effective than many competing effects.</div>

  <h2>3. Balance: control visual weight</h2>
  <p>Balance is about how visual weight is distributed across a composition. A large dark image can visually outweigh several small light elements. Symmetrical layouts often feel stable, while asymmetrical layouts can feel more dynamic when their visual weights are still controlled.</p>

  <h2>4. Alignment: create relationships</h2>
  <p>Alignment gives elements an invisible structure. Shared left edges, centres or baselines make a layout feel intentional. Use guides or a grid when possible.</p>

  <h2>5. Proximity: group related information</h2>
  <p>Items placed close together are usually perceived as related. Put an event date near the event title rather than leaving the date floating elsewhere on the canvas. Increase the gap between unrelated groups to make the structure easier to understand.</p>

  <h2>6. Repetition: build consistency</h2>
  <p>Repeating a visual treatment creates a system. The same heading style, button treatment, icon style or spacing rhythm can make several pieces of content feel like one brand.</p>

  <h2>7. Unity: make the design feel like one thing</h2>
  <p>Unity is the overall sense that the parts belong together. Consistent typography, colour, spacing, imagery and visual language help create unity.</p>

  <h2>How the principles work together</h2>
  <div class="table-responsive"><table class="table table-bordered align-middle">
    <thead><tr><th>Principle</th><th>Main question</th><th>Useful result</th></tr></thead>
    <tbody>
      <tr><td>Hierarchy</td><td>What should the viewer notice first?</td><td>Clear reading order</td></tr>
      <tr><td>Contrast</td><td>What needs to stand apart?</td><td>Emphasis and readability</td></tr>
      <tr><td>Balance</td><td>Where is the visual weight?</td><td>Stability or controlled energy</td></tr>
      <tr><td>Alignment</td><td>Which edges or centres should relate?</td><td>Order and structure</td></tr>
      <tr><td>Proximity</td><td>Which items belong together?</td><td>Clear grouping</td></tr>
      <tr><td>Repetition</td><td>What should feel consistent?</td><td>Rhythm and recognition</td></tr>
      <tr><td>Unity</td><td>Do all parts feel like one system?</td><td>Coherence</td></tr>
    </tbody>
  </table></div>

  <h2>Real-world example: a mobile event poster</h2>
  <p>Imagine a 1080 × 1350 social post for a free design workshop. Make the workshop name visually dominant, keep the date and time close to it, use one deliberate accent for registration, align supporting details consistently and leave enough space around the main message for quick phone reading.</p>

  <h2>Practical exercise</h2>
  <ol>
    <li>Create an event poster containing a title, date, location, short description and call to action.</li>
    <li>Make three clear hierarchy levels: primary, secondary and supporting information.</li>
    <li>Align the text to one main edge.</li>
    <li>Group related information with proximity.</li>
    <li>Use one deliberate contrast point for the call to action.</li>
    <li>Duplicate the poster and change only alignment or spacing. Compare which version is easier to scan.</li>
  </ol>

  <h2>Common mistakes</h2>
  <ul>
    <li>Making every element bold, large or brightly coloured.</li>
    <li>Using alignment inconsistently between similar blocks.</li>
    <li>Adding borders everywhere instead of using proximity and whitespace.</li>
    <li>Using contrast without checking text readability.</li>
    <li>Repeating decorative effects until the design loses hierarchy.</li>
  </ul>

  <h2>Quick self-check</h2>
  <ol>
    <li>What should a viewer notice first in your design?</li>
    <li>Which elements are grouped because they belong together?</li>
    <li>Where is the strongest contrast, and why is it needed?</li>
    <li>Can you point to the alignment system?</li>
    <li>Which repeated choices make the design feel consistent?</li>
  </ol>

  <h2>Key takeaway</h2>
  <p><strong>Design principles turn individual visual elements into an organised communication system.</strong> When a design feels confusing, check hierarchy, contrast, balance, alignment, proximity, repetition and unity before adding more decoration.</p>
</article>
HTML;
    }

    if ($slug === 'course-16-lesson-6' || $position === 6) {
        return <<<'HTML'
<article class="lh-prose">
  <div class="lh-lesson-intro">
    <p><strong>Typography is the design of written language.</strong> It is not simply choosing a font. A designer uses typeface, size, weight, spacing, line length and hierarchy to make information readable and to give it an appropriate visual voice.</p>
  </div>

  <h2>1. Typeface, font and family</h2>
  <p>A <strong>typeface</strong> is the broader design of a set of letters, while a font traditionally refers to a particular style or cut within that design. In everyday design software, people often use “font” for both. A type family can contain regular, medium, semibold, bold and italic styles.</p>
  <p>For beginners, the important skill is not memorising terminology. It is learning to choose type that supports the purpose of the message.</p>

  <h2>2. Serif and sans-serif</h2>
  <div class="table-responsive"><table class="table table-bordered align-middle">
    <thead><tr><th>Category</th><th>Visual characteristic</th><th>Common use</th></tr></thead>
    <tbody>
      <tr><td>Serif</td><td>Letters have small finishing strokes.</td><td>Editorial, literary, formal or traditional communication.</td></tr>
      <tr><td>Sans-serif</td><td>Letters generally lack those finishing strokes.</td><td>Interfaces, modern branding, signage and many digital layouts.</td></tr>
      <tr><td>Display</td><td>Designed for stronger visual personality, often at larger sizes.</td><td>Headlines, posters and short attention-grabbing text.</td></tr>
    </tbody>
  </table></div>
  <p>These are tendencies, not rules. A typeface should be judged by readability, context and the personality you want to communicate.</p>

  <h2>3. Build hierarchy with type</h2>
  <p>Typography can tell the viewer what to read first. A useful hierarchy might contain a large headline, a medium-sized supporting line and smaller body copy. You can create this difference with size, weight, spacing and placement instead of using many unrelated fonts.</p>
  <div class="alert alert-primary"><strong>Smart tip:</strong> If everything is visually important, nothing is clearly important. Give the main message the strongest typographic treatment and let supporting information stay quieter.</div>

  <h2>4. Font pairing</h2>
  <p>Two typefaces can work together when they have a clear relationship but enough difference to create hierarchy. A common beginner approach is to pair a distinctive heading face with a simple, highly readable body face.</p>
  <p>Avoid pairing fonts that are almost identical but not quite. That small difference can look accidental rather than intentional. Also avoid using many typefaces simply because they are available.</p>

  <h2>5. Readability: size, line height and line length</h2>
  <p>Readable type needs enough space to breathe. <strong>Line height</strong> controls the vertical distance between lines. Very tight leading can make paragraphs feel dense; excessive leading can break the relationship between lines.</p>
  <p><strong>Line length</strong> also matters. Extremely wide paragraphs force the eyes to travel a long distance and make it easier to lose the next line. Shorter text blocks are especially useful on mobile screens.</p>

  <h2>6. Letter spacing and kerning</h2>
  <p><strong>Tracking</strong> changes the overall spacing across a range of letters. <strong>Kerning</strong> adjusts the space between specific letter pairs. These controls become especially visible in large headlines, logos and all-caps text.</p>
  <p>Do not use extra letter spacing as a universal fix. First choose an appropriate typeface and size; then make small spacing adjustments when the design actually needs them.</p>

  <h2>7. Weight and emphasis</h2>
  <p>Weight can create emphasis without changing the typeface. Regular body text paired with a semibold heading often creates a cleaner hierarchy than using bold, italic, underline, colour and a different font all at once.</p>

  <h2>8. Typography and accessibility</h2>
  <p>Good typography should remain understandable for people with different visual needs and devices. Maintain sufficient contrast between text and its background, avoid tiny body text, and do not communicate important information through colour alone.</p>

  <h2>Practical example: design a workshop announcement</h2>
  <p>Imagine a mobile poster that says “Graphic Design Workshop”. Make the title large enough to recognise quickly. Place the date and time immediately below it with a smaller but still strong treatment. Use a readable body style for the description. Give the registration action a distinct typographic treatment, but keep the overall type system limited and consistent.</p>

  <h2>Typography checklist</h2>
  <div class="table-responsive"><table class="table table-bordered align-middle">
    <thead><tr><th>Check</th><th>Question</th></tr></thead>
    <tbody>
      <tr><td>Purpose</td><td>Does the type style match the audience and message?</td></tr>
      <tr><td>Hierarchy</td><td>Can you identify the first, second and third reading levels?</td></tr>
      <tr><td>Readability</td><td>Can the body copy be read comfortably at the intended size?</td></tr>
      <tr><td>Spacing</td><td>Are line height and letter spacing helping rather than hurting clarity?</td></tr>
      <tr><td>Consistency</td><td>Are headings, body text and emphasis styles used consistently?</td></tr>
      <tr><td>Contrast</td><td>Does text remain clearly distinguishable from its background?</td></tr>
    </tbody>
  </table></div>

  <h2>Practical exercise</h2>
  <ol>
    <li>Create a small event poster with a headline, date, description and call to action.</li>
    <li>Use one type family first and create hierarchy using size and weight.</li>
    <li>Make a second version using a carefully chosen second typeface for contrast.</li>
    <li>View both versions at phone size rather than zoomed in.</li>
    <li>Ask someone to read the poster for five seconds, then tell you what they noticed first.</li>
    <li>Adjust the typography based on the result instead of adding more decorative effects.</li>
  </ol>

  <h2>Common mistakes</h2>
  <ul>
    <li>Using too many typefaces in one design.</li>
    <li>Choosing a decorative display face for long paragraphs.</li>
    <li>Making body text too small or low-contrast.</li>
    <li>Using bold, colour, underline and size changes all at once for emphasis.</li>
    <li>Ignoring line height and paragraph width.</li>
    <li>Judging typography only while zoomed in on a large screen.</li>
  </ul>

  <h2>Quick self-check</h2>
  <ol>
    <li>What is the main job of typography in a design?</li>
    <li>How can size and weight create hierarchy?</li>
    <li>Why can line length and line height affect readability?</li>
    <li>What is the difference between tracking and kerning?</li>
    <li>Can you explain why you selected a particular typeface for your audience?</li>
  </ol>

  <h2>Key takeaway</h2>
  <p><strong>Good typography makes information easier to understand while reinforcing the character of the design.</strong> Choose type for purpose, establish a clear hierarchy, protect readability, and make spacing decisions deliberately.</p>
</article>
HTML;
    }

    return null;
}

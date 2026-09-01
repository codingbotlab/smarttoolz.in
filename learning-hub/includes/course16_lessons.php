<?php
declare(strict_types=1);

/* Course 16: one lesson at a time, topic-specific content. */
function lh_course16_override(array $lesson, int $position): ?string
{
    $slug = (string)($lesson['slug'] ?? '');
    if ($slug !== 'course-16-lesson-5' && $position !== 5) {
        return null;
    }

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
  <p>When a layout feels as if it is “falling” to one side, do not automatically add another object. Try changing the size, position, whitespace or colour intensity of an existing element first.</p>

  <h2>4. Alignment: create relationships</h2>
  <p>Alignment gives elements an invisible structure. Shared left edges, centres or baselines make a layout feel intentional. Randomly placing text boxes often creates small inconsistencies that make the whole design feel unfinished.</p>
  <p>Use guides or a grid when possible. If three text blocks are related, aligning them to the same edge can communicate that relationship without adding borders or decorative shapes.</p>

  <h2>5. Proximity: group related information</h2>
  <p>Items placed close together are usually perceived as related. Put an event date near the event title rather than leaving the date floating elsewhere on the canvas. Increase the gap between unrelated groups to make the structure easier to understand.</p>

  <h2>6. Repetition: build consistency</h2>
  <p>Repeating a visual treatment creates a system. The same heading style, button treatment, corner radius, icon style or spacing rhythm can make several pieces of content feel like one brand.</p>
  <p>Repetition should be purposeful. If every element receives the same emphasis, repetition can flatten the hierarchy instead of strengthening it.</p>

  <h2>7. Unity: make the design feel like one thing</h2>
  <p>Unity is the overall sense that the parts belong together. Consistent typography, colour, spacing, imagery and visual language help create unity. A design can contain excellent individual elements and still feel weak if those elements look as though they came from unrelated projects.</p>

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
  <p>Imagine a 1080 × 1350 social post for a free design workshop. Put the workshop name near the top with strong size and weight. Place the date and time close to it so they are understood as one information group. Use a contrasting accent for the registration action. Align the supporting details to a consistent edge, and leave enough space around the main message that it can be read quickly on a phone.</p>
  <p>Notice how no single principle solves the entire layout. Hierarchy chooses priority, proximity creates groups, alignment creates structure, contrast creates emphasis, and repetition keeps the visual language consistent.</p>

  <h2>Practical exercise</h2>
  <ol>
    <li>Create a simple event poster containing a title, date, location, short description and call to action.</li>
    <li>Make three clear hierarchy levels: primary, secondary and supporting information.</li>
    <li>Align the text to one main edge.</li>
    <li>Group related information with proximity rather than extra boxes.</li>
    <li>Use one deliberate contrast point for the call to action.</li>
    <li>Duplicate the poster and change only the alignment or spacing. Compare which version feels easier to scan.</li>
  </ol>

  <h2>Common mistakes</h2>
  <ul>
    <li>Making every element bold, large or brightly coloured.</li>
    <li>Using alignment inconsistently between similar blocks.</li>
    <li>Adding borders everywhere instead of using proximity and whitespace.</li>
    <li>Using contrast without checking text readability.</li>
    <li>Repeating decorative effects so much that the design loses hierarchy.</li>
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

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
  <div class="table-responsive"><table class="table table-bordered align-middle"><thead><tr><th>Principle</th><th>Main question</th><th>Useful result</th></tr></thead><tbody><tr><td>Hierarchy</td><td>What should the viewer notice first?</td><td>Clear reading order</td></tr><tr><td>Contrast</td><td>What needs to stand apart?</td><td>Emphasis and readability</td></tr><tr><td>Balance</td><td>Where is the visual weight?</td><td>Stability or controlled energy</td></tr><tr><td>Alignment</td><td>Which edges or centres should relate?</td><td>Order and structure</td></tr><tr><td>Proximity</td><td>Which items belong together?</td><td>Clear grouping</td></tr><tr><td>Repetition</td><td>What should feel consistent?</td><td>Rhythm and recognition</td></tr><tr><td>Unity</td><td>Do all parts feel like one system?</td><td>Coherence</td></tr></tbody></table></div>
  <h2>Real-world example: a mobile event poster</h2><p>Imagine a 1080 × 1350 social post for a free design workshop. Make the workshop name visually dominant, keep the date and time close to it, use one deliberate accent for registration, align supporting details consistently and leave enough space around the main message for quick phone reading.</p>
  <h2>Practical exercise</h2><ol><li>Create an event poster containing a title, date, location, short description and call to action.</li><li>Make three clear hierarchy levels: primary, secondary and supporting information.</li><li>Align the text to one main edge.</li><li>Group related information with proximity.</li><li>Use one deliberate contrast point for the call to action.</li><li>Duplicate the poster and change only alignment or spacing. Compare which version is easier to scan.</li></ol>
  <h2>Common mistakes</h2><ul><li>Making every element bold, large or brightly coloured.</li><li>Using alignment inconsistently between similar blocks.</li><li>Adding borders everywhere instead of using proximity and whitespace.</li><li>Using contrast without checking text readability.</li><li>Repeating decorative effects until the design loses hierarchy.</li></ul>
  <h2>Quick self-check</h2><ol><li>What should a viewer notice first in your design?</li><li>Which elements are grouped because they belong together?</li><li>Where is the strongest contrast, and why is it needed?</li><li>Can you point to the alignment system?</li><li>Which repeated choices make the design feel consistent?</li></ol>
  <h2>Key takeaway</h2><p><strong>Design principles turn individual visual elements into an organised communication system.</strong> When a design feels confusing, check hierarchy, contrast, balance, alignment, proximity, repetition and unity before adding more decoration.</p>
</article>
HTML;
    }

    if ($slug === 'course-16-lesson-6' || $position === 6) {
        return <<<'HTML'
<article class="lh-prose">
  <div class="lh-lesson-intro"><p><strong>Typography is the design of written language.</strong> It is not simply choosing a font. A designer uses typeface, size, weight, spacing, line length and hierarchy to make information readable and to give it an appropriate visual voice.</p></div>
  <h2>1. Typeface, font and family</h2><p>A <strong>typeface</strong> is the broader design of a set of letters, while a font traditionally refers to a particular style or cut within that design. In everyday design software, people often use “font” for both. A type family can contain regular, medium, semibold, bold and italic styles.</p><p>For beginners, the important skill is not memorising terminology. It is learning to choose type that supports the purpose of the message.</p>
  <h2>2. Serif and sans-serif</h2><div class="table-responsive"><table class="table table-bordered align-middle"><thead><tr><th>Category</th><th>Visual characteristic</th><th>Common use</th></tr></thead><tbody><tr><td>Serif</td><td>Letters have small finishing strokes.</td><td>Editorial, literary, formal or traditional communication.</td></tr><tr><td>Sans-serif</td><td>Letters generally lack those finishing strokes.</td><td>Interfaces, modern branding, signage and many digital layouts.</td></tr><tr><td>Display</td><td>Designed for stronger visual personality, often at larger sizes.</td><td>Headlines, posters and short attention-grabbing text.</td></tr></tbody></table></div><p>These are tendencies, not rules. A typeface should be judged by readability, context and the personality you want to communicate.</p>
  <h2>3. Build hierarchy with type</h2><p>Typography can tell the viewer what to read first. A useful hierarchy might contain a large headline, a medium-sized supporting line and smaller body copy. You can create this difference with size, weight, spacing and placement instead of using many unrelated fonts.</p><div class="alert alert-primary"><strong>Smart tip:</strong> If everything is visually important, nothing is clearly important. Give the main message the strongest typographic treatment and let supporting information stay quieter.</div>
  <h2>4. Font pairing</h2><p>Two typefaces can work together when they have a clear relationship but enough difference to create hierarchy. A common beginner approach is to pair a distinctive heading face with a simple, highly readable body face.</p><p>Avoid pairing fonts that are almost identical but not quite. That small difference can look accidental rather than intentional. Also avoid using many typefaces simply because they are available.</p>
  <h2>5. Readability: size, line height and line length</h2><p>Readable type needs enough space to breathe. <strong>Line height</strong> controls the vertical distance between lines. Very tight leading can make paragraphs feel dense; excessive leading can break the relationship between lines.</p><p><strong>Line length</strong> also matters. Extremely wide paragraphs force the eyes to travel a long distance and make it easier to lose the next line. Shorter text blocks are especially useful on mobile screens.</p>
  <h2>6. Letter spacing and kerning</h2><p><strong>Tracking</strong> changes the overall spacing across a range of letters. <strong>Kerning</strong> adjusts the space between specific letter pairs. These controls become especially visible in large headlines, logos and all-caps text.</p><p>Do not use extra letter spacing as a universal fix. First choose an appropriate typeface and size; then make small spacing adjustments when the design actually needs them.</p>
  <h2>7. Weight and emphasis</h2><p>Weight can create emphasis without changing the typeface. Regular body text paired with a semibold heading often creates a cleaner hierarchy than using bold, italic, underline, colour and a different font all at once.</p>
  <h2>8. Typography and accessibility</h2><p>Good typography should remain understandable for people with different visual needs and devices. Maintain sufficient contrast between text and its background, avoid tiny body text, and do not communicate important information through colour alone.</p>
  <h2>Practical example: design a workshop announcement</h2><p>Imagine a mobile poster that says “Graphic Design Workshop”. Make the title large enough to recognise quickly. Place the date and time immediately below it with a smaller but still strong treatment. Use a readable body style for the description. Give the registration action a distinct typographic treatment, but keep the overall type system limited and consistent.</p>
  <h2>Typography checklist</h2><div class="table-responsive"><table class="table table-bordered align-middle"><thead><tr><th>Check</th><th>Question</th></tr></thead><tbody><tr><td>Purpose</td><td>Does the type style match the audience and message?</td></tr><tr><td>Hierarchy</td><td>Can you identify the first, second and third reading levels?</td></tr><tr><td>Readability</td><td>Can the body copy be read comfortably at the intended size?</td></tr><tr><td>Spacing</td><td>Are line height and letter spacing helping rather than hurting clarity?</td></tr><tr><td>Consistency</td><td>Are headings, body text and emphasis styles used consistently?</td></tr><tr><td>Contrast</td><td>Does text remain clearly distinguishable from its background?</td></tr></tbody></table></div>
  <h2>Practical exercise</h2><ol><li>Create a small event poster with a headline, date, description and call to action.</li><li>Use one type family first and create hierarchy using size and weight.</li><li>Make a second version using a carefully chosen second typeface for contrast.</li><li>View both versions at phone size rather than zoomed in.</li><li>Ask someone to read the poster for five seconds, then tell you what they noticed first.</li><li>Adjust the typography based on the result instead of adding more decorative effects.</li></ol>
  <h2>Common mistakes</h2><ul><li>Using too many typefaces in one design.</li><li>Choosing a decorative display face for long paragraphs.</li><li>Making body text too small or low-contrast.</li><li>Using bold, colour, underline and size changes all at once for emphasis.</li><li>Ignoring line height and paragraph width.</li><li>Judging typography only while zoomed in on a large screen.</li></ul>
  <h2>Quick self-check</h2><ol><li>What is the main job of typography in a design?</li><li>How can size and weight create hierarchy?</li><li>Why can line length and line height affect readability?</li><li>What is the difference between tracking and kerning?</li><li>Can you explain why you selected a particular typeface for your audience?</li></ol>
  <h2>Key takeaway</h2><p><strong>Good typography makes information easier to understand while reinforcing the character of the design.</strong> Choose type for purpose, establish a clear hierarchy, protect readability, and make spacing decisions deliberately.</p>
</article>
HTML;
    }

    if ($slug === 'course-16-lesson-7' || $position === 7) {
        return <<<'HTML'
<article class="lh-prose">
  <div class="lh-lesson-intro"><p><strong>Colour is a communication tool, not just decoration.</strong> In graphic design, colour can create emphasis, separate information, establish mood and connect a piece of work to a brand. Good colour choices also protect readability and accessibility.</p></div>
  <h2>1. Hue, saturation and value</h2><p><strong>Hue</strong> is the basic colour family, such as red, blue or green. <strong>Saturation</strong> describes how intense or muted a colour appears. <strong>Value</strong> describes how light or dark it is. Changing these three properties can produce very different visual results from the same basic hue.</p><p>For example, a highly saturated blue can feel energetic, while a low-saturation blue-grey may feel calmer and more restrained. A very light tint and a very dark shade of the same hue can create strong hierarchy when used together.</p>
  <h2>2. Warm and cool colours</h2><p>Reds, oranges and yellows are commonly described as warm colours, while blues and many blue-greens are described as cool. This is a useful design shorthand rather than a rigid emotional rule. Context, saturation and surrounding colours can change how a colour feels.</p>
  <h2>3. Useful colour relationships</h2><div class="table-responsive"><table class="table table-bordered align-middle"><thead><tr><th>Relationship</th><th>How it works</th><th>Useful when</th></tr></thead><tbody><tr><td>Monochromatic</td><td>Different values or saturations of one hue.</td><td>You want a controlled, cohesive palette.</td></tr><tr><td>Analogous</td><td>Neighbouring hues on the colour wheel.</td><td>You want related colours with gentle variation.</td></tr><tr><td>Complementary</td><td>Opposing hues on the colour wheel.</td><td>You need strong contrast or a clear accent.</td></tr><tr><td>Split complementary</td><td>One hue paired with the neighbours of its complement.</td><td>You want contrast with a little more flexibility.</td></tr></tbody></table></div>
  <h2>4. Build a functional palette</h2><p>A practical design system often needs more than a single “main colour”. Start with a background or neutral, a primary colour, a secondary or supporting colour, and an accent for actions or important information. You can then create lighter and darker variants as needed.</p><div class="alert alert-primary"><strong>Smart tip:</strong> Pick colours based on the job they perform. If every colour is an accent, the viewer loses the ability to tell what deserves attention.</div>
  <h2>5. Contrast and readability</h2><p>Colour contrast must support the content. Light grey text on a white background may look subtle in a design editor but become difficult to read in real use. Check text against its actual background and test the design on the device where it will be viewed.</p><p>Do not rely on colour alone to communicate status or meaning. For example, instead of showing only green for success and red for failure, include a word, icon or other clear cue as well.</p>
  <h2>6. Colour and brand consistency</h2><p>When creating a series of posts, thumbnails or pages, record the exact colour values you choose. Reusing the same palette makes the work feel connected. If the palette changes for every design, even good individual pieces can look like unrelated projects.</p>
  <h2>Real-world example: a workshop poster</h2><p>Imagine a poster for a beginner design workshop. Use a neutral background, one confident primary colour for the title, a quieter supporting colour for secondary information, and a contrasting accent for registration. Keep the body text in a high-contrast colour and leave enough empty space around the accent so it remains meaningful.</p>
  <h2>Practical exercise</h2><ol><li>Choose one subject for a social post, such as a design workshop or product announcement.</li><li>Create a four-colour palette: neutral, primary, supporting and accent.</li><li>Make one version using a monochromatic approach.</li><li>Make another version using a complementary accent.</li><li>Check the smallest text first. If it is hard to read, adjust the colour or background rather than adding a shadow and hoping it fixes the problem.</li><li>Save the final colour values so you can reproduce the palette later.</li></ol>
  <h2>Common mistakes</h2><ul><li>Choosing colours only because they look attractive in isolation.</li><li>Using too many saturated colours at the same visual strength.</li><li>Putting low-contrast text over a busy or similarly coloured background.</li><li>Changing brand colours from one graphic to the next.</li><li>Using colour as the only signal for an important message.</li></ul>
  <h2>Quick self-check</h2><ol><li>What is the difference between hue, saturation and value?</li><li>Why might a monochromatic palette be useful?</li><li>Where is the accent colour in your design, and what job does it perform?</li><li>Can every important piece of information still be understood without relying only on colour?</li><li>Have you checked the smallest text for sufficient contrast?</li></ol>
  <h2>Key takeaway</h2><p><strong>Use colour deliberately: establish hierarchy, support readability, create a consistent visual language and reserve strong accents for information that truly needs attention.</strong></p>
</article>
HTML;
    }

    if ($slug === 'course-16-lesson-8' || $position === 8) {
        return <<<'HTML'
<article class="lh-prose">
  <div class="lh-lesson-intro">
    <p><strong>Layout and composition decide how a viewer moves through a design.</strong> A strong layout gives every element a place, creates a predictable reading path, and makes the relationship between text, images and empty space easy to understand.</p>
  </div>

  <h2>1. Start with the communication goal</h2>
  <p>Before moving boxes around the canvas, decide what the design must communicate. A social poster, landing-page hero, product card and magazine spread may contain similar ingredients but need different layouts because their audiences and reading situations are different.</p>
  <p>Write the message in one sentence first. Then identify the information that must be noticed immediately, the information that supports it, and the information that can wait.</p>

  <h2>2. Use a grid as a decision-making tool</h2>
  <p>A grid is an invisible structure made from columns, rows, margins and guides. It does not mean every element must become a rigid rectangle. The grid gives you consistent reference points so alignment and spacing are deliberate rather than guessed.</p>
  <div class="alert alert-primary"><strong>Smart tip:</strong> Use the grid to create relationships, not to make every block identical. A controlled break from the grid can create emphasis when the surrounding structure is strong.</div>

  <h2>3. Margins, columns and gutters</h2>
  <div class="table-responsive"><table class="table table-bordered align-middle"><thead><tr><th>Part</th><th>Purpose</th><th>Design question</th></tr></thead><tbody><tr><td>Margin</td><td>Creates breathing room around the main content area.</td><td>Does the content feel cramped against the edge?</td></tr><tr><td>Column</td><td>Provides repeatable vertical alignment points.</td><td>Which content blocks should share an edge?</td></tr><tr><td>Gutter</td><td>Separates neighbouring columns.</td><td>Is the gap large enough to prevent unrelated content from merging?</td></tr><tr><td>Baseline/row</td><td>Helps text and repeated modules align vertically.</td><td>Do repeated elements share a consistent rhythm?</td></tr></tbody></table></div>

  <h2>4. Create a visual path</h2>
  <p>Viewers do not inspect every element with equal attention. Position, size, contrast, direction and whitespace can guide the eye. A common structure is a dominant headline or image, followed by supporting information, then a clear action or conclusion.</p>
  <p>Test the path by shrinking your design until details are difficult to read. You should still be able to identify the main subject and roughly understand where the eye should go next.</p>

  <h2>5. Balance positive and negative space</h2>
  <p><strong>Positive space</strong> is occupied by visible elements; <strong>negative space</strong> is the empty area around and between them. Empty space is not wasted space. It separates groups, improves focus and can make a design feel more premium and easier to scan.</p>
  <p>If a composition feels crowded, do not immediately reduce the font size. First try removing unnecessary elements, increasing gaps between groups, or simplifying the visual hierarchy.</p>

  <h2>6. Use image and text together</h2>
  <p>An image can dominate a layout, support a headline, or provide context. Give the image enough room to be understood and avoid placing important text over visually noisy areas unless contrast is carefully controlled.</p>
  <p>When cropping a photograph, keep the subject's important visual information in mind. Cropping should support the message rather than simply filling an empty rectangle.</p>

  <h2>7. Repetition creates rhythm</h2>
  <p>Repeated cards, headings, icons or spacing values create rhythm across a page or series of graphics. Define a small spacing system instead of choosing a different gap for every element. Consistency makes the layout faster to scan and easier to maintain.</p>

  <h2>Real-world example: a course promotion graphic</h2>
  <p>Imagine a 1080 × 1350 graphic promoting a beginner design course. Reserve the upper area for the course name, place a strong image or graphic in the middle, group the key benefits in a compact block, and give the registration action a clear position near the end of the reading path. Keep the margins consistent and leave enough space around the call to action.</p>

  <h2>Practical exercise: make two layouts from the same content</h2>
  <ol>
    <li>Collect a headline, one image, three short benefits, a date and one call to action.</li>
    <li>Create a simple two-column or modular grid before placing the content.</li>
    <li>Build Version A with a conventional top-to-bottom reading path.</li>
    <li>Build Version B with a more dynamic asymmetrical composition while preserving clear hierarchy.</li>
    <li>Compare the versions at thumbnail size and at normal viewing size.</li>
    <li>Remove one unnecessary element from the stronger version and check whether clarity improves.</li>
  </ol>

  <h2>Layout quality checklist</h2>
  <div class="table-responsive"><table class="table table-bordered align-middle"><thead><tr><th>Check</th><th>What to look for</th></tr></thead><tbody><tr><td>Hierarchy</td><td>Can you identify the main message within a few seconds?</td></tr><tr><td>Alignment</td><td>Do related elements share deliberate edges, centres or baselines?</td></tr><tr><td>Spacing</td><td>Are related items closer than unrelated groups?</td></tr><tr><td>Balance</td><td>Does one side feel accidentally heavier than the other?</td></tr><tr><td>Whitespace</td><td>Is empty space helping focus rather than leaving accidental gaps?</td></tr><tr><td>Reading path</td><td>Is it obvious what the viewer should notice next?</td></tr></tbody></table></div>

  <h2>Common mistakes</h2>
  <ul><li>Placing elements wherever there is room instead of establishing a structure first.</li><li>Using many unrelated spacing values.</li><li>Filling every empty area with decoration.</li><li>Making everything the same size and visual weight.</li><li>Ignoring mobile or thumbnail viewing conditions.</li><li>Changing alignment from one repeated block to another.</li></ul>

  <h2>Quick self-check</h2>
  <ol><li>What is the first thing a viewer should notice?</li><li>Which grid or alignment points hold your layout together?</li><li>Where is negative space doing useful work?</li><li>Can you describe the intended reading path in one sentence?</li><li>What could you remove without damaging the message?</li></ol>

  <h2>Key takeaway</h2>
  <p><strong>Good composition is controlled organisation.</strong> Start with the message, build a structure, guide attention with hierarchy, and use alignment, spacing and negative space to make the design easy to understand.</p>
</article>
HTML;
    }

    return null;
}

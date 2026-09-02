<?php
declare(strict_types=1);

/*
 * SmartToolz individual tool pages are intentionally full-width.
 *
 * This file remains a compatibility include for legacy tools. The shared
 * All Tools rail is retired from individual tool pages, while this small
 * stylesheet normalizes common legacy page wrappers to use the full viewport.
 */
?>
<style>
/* Full-width tool canvas: remove legacy 1200px/1320px wrapper caps. */
body > .page-layout,
body > .wrap,
body > .tool-page,
body > .tool-container,
body > .tool-wrapper,
body > main.page-layout,
body > main.wrap,
body > main.tool-page,
body > main.tool-container,
body > main.tool-wrapper {
  width:100% !important;
  max-width:none !important;
}

/* Common inner tool shells should also fill the available tool canvas. */
.page-layout,
.wrap,
.tool-page,
.tool-container,
.tool-wrapper {
  max-width:none !important;
}
</style>
<?php
return;

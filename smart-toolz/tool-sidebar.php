<?php
declare(strict_types=1);

/*
 * SmartToolz tool pages are intentionally full-width.
 *
 * The old shared All Tools rail is retired across every individual tool.
 * Tool-specific documentation now lives in the Knowledge Base and is linked
 * automatically from the shared header/footer using the current tool slug.
 */
?>
<style>
/* Normalize legacy tool wrappers that were capped at ~1200/1320px. */
body > .page-layout,
body > .wrap,
body > .tool-page,
body > .tool-container,
body > .tool-wrapper,
body > main.page-layout,
body > main.wrap,
body > main.tool-page,
body > main.tool-container,
body > main.tool-wrapper{width:100%!important;max-width:none!important}
.page-layout,.wrap,.tool-page,.tool-container,.tool-wrapper{max-width:none!important}
</style>
<?php
return;

<?php
// Small compatibility layer for hosts/themes that collapse the Knowledge Base guide grid.
// Kept scoped to the guide layout classes so it does not alter tool functionality.
?>
<style>
.layout{display:grid!important;grid-template-columns:minmax(245px,265px) minmax(0,1fr)!important;align-items:start!important;width:min(1420px,calc(100% - 28px))!important;margin:22px auto!important;gap:20px!important}
.side{width:auto!important;min-width:0!important}
.main{display:block!important;width:auto!important;min-width:0!important;max-width:none!important}
.hero,.grid2,.steps,.tips,.mistakes,.faq,.related{width:100%!important;max-width:none!important}
.grid2{display:grid!important;grid-template-columns:minmax(0,1fr) minmax(0,1fr)!important}
.related-grid{width:100%!important}
@media(max-width:700px){
  .layout{display:block!important;width:calc(100% - 20px)!important}
  .side{position:fixed!important;z-index:1200!important;left:12px!important;top:76px!important;width:min(320px,calc(100% - 24px))!important;height:calc(100vh - 88px)!important;max-height:none!important;transform:translateX(-120%)!important;transition:.22s!important}
  .side.open{transform:translateX(0)!important}
  .grid2{grid-template-columns:1fr!important}
}
</style>

/* SmartToolz public tool count helper. */
(() => {
  'use strict';
  const endpoint = '/tool-registry.php';
  const count = document.querySelector('#tools-count');
  if (!count) return;
  const load = async () => {
    try {
      const response = await fetch(`${endpoint}?_=${Date.now()}`, {cache:'no-store',headers:{Accept:'application/json'}});
      if (!response.ok) return;
      const data = await response.json();
      count.textContent = Number(data.count || (Array.isArray(data.tools) ? data.tools.length : 0)).toLocaleString('en-IN');
    } catch (_) {}
  };
  load();
})();

/*
 * SmartToolz - Dynamic Tools Counter
 * Uses the site's public tool registry instead of the private GitHub API.
 */
(() => {
    "use strict";

    const CONFIG = {
        endpoint: "/smart-toolz/tool-registry.php",
        countSelector: "#tools-count",
        listSelector: "#tools-list",
        refreshMs: 5 * 60 * 1000
    };

    function ensureCountElement() {
        let countEl = document.querySelector(CONFIG.countSelector);
        if (countEl) return countEl;

        const stats = document.querySelector(".stats");
        if (!stats) return null;

        const stat = document.createElement("div");
        stat.className = "stat";
        stat.innerHTML = `
            <span class="stat-icon"><span class="material-symbols-rounded">build</span></span>
            <strong id="tools-count">—</strong>
            <small>Tools Available</small>
        `;
        stats.appendChild(stat);
        return stat.querySelector("#tools-count");
    }

    async function loadTools() {
        const countEl = ensureCountElement();
        const listEl = document.querySelector(CONFIG.listSelector);

        if (!countEl && !listEl) return;

        const apiUrl = new URL(CONFIG.endpoint, window.location.origin);
        apiUrl.searchParams.set("_", Date.now().toString());

        try {
            const response = await fetch(apiUrl.toString(), {
                cache: "no-store",
                headers: { Accept: "application/json" }
            });

            if (!response.ok) {
                throw new Error(`Tool registry returned ${response.status}`);
            }

            const payload = await response.json();
            const entries = Array.isArray(payload.tools) ? payload.tools : [];

            const tools = [];
            const seenUrls = new Set();

            for (const tool of entries) {
                const name = String(tool?.name || "").trim();
                const url = String(tool?.url || "").trim();
                if (!name || !url || seenUrls.has(url)) continue;
                seenUrls.add(url);
                tools.push({ ...tool, name, url });
            }

            if (countEl) {
                countEl.textContent = tools.length.toLocaleString("en-IN");
                countEl.setAttribute("data-tools-updated", new Date().toISOString());
            }

            if (listEl) {
                listEl.replaceChildren(
                    ...tools.map(tool => {
                        const link = document.createElement("a");
                        link.href = tool.url;
                        link.textContent = tool.name;
                        return link;
                    })
                );
            }
        } catch (error) {
            // Keep the existing UI intact when the registry is temporarily unavailable.
            console.error("Unable to update SmartToolz tools count:", error);
        }
    }

    loadTools();
    window.setInterval(loadTools, CONFIG.refreshMs);
})();

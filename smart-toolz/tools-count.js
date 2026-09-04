/*
 * Smart Toolz - Dynamic GitHub Tools Counter
 * Reads smart-toolz/tools/ and keeps the homepage count/list synchronized.
 */
(() => {
    "use strict";

    const CONFIG = {
        owner: "codingbotlab",
        repo: "smarttoolz.in",
        branch: "main",
        path: "smart-toolz/tools",
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

        const apiUrl = new URL(
            `https://api.github.com/repos/${CONFIG.owner}/${CONFIG.repo}/contents/${CONFIG.path}`
        );
        apiUrl.searchParams.set("ref", CONFIG.branch);
        apiUrl.searchParams.set("_", Date.now().toString());

        try {
            const response = await fetch(apiUrl.toString(), {
                cache: "no-store",
                headers: { Accept: "application/vnd.github+json" }
            });

            if (!response.ok) {
                throw new Error(`GitHub API returned ${response.status}`);
            }

            const entries = await response.json();

            const tools = entries
                .filter(item =>
                    item.type === "file" &&
                    item.name.toLowerCase().endsWith(".php") &&
                    !item.name.startsWith(".") &&
                    !item.name.startsWith("_")
                )
                .sort((a, b) => a.name.localeCompare(b.name));

            if (countEl) {
                countEl.textContent = tools.length.toLocaleString("en-IN");
                countEl.setAttribute("data-tools-updated", new Date().toISOString());
            }

            if (listEl) {
                listEl.replaceChildren(
                    ...tools.map(tool => {
                        const link = document.createElement("a");
                        link.href = `tools/${encodeURIComponent(tool.name)}`;
                        link.textContent = tool.name.replace(/\.php$/i, "").replace(/[-_]+/g, " ");
                        return link;
                    })
                );
            }
        } catch (error) {
            console.error("Unable to update GitHub tools count:", error);
        }
    }

    loadTools();
    window.setInterval(loadTools, CONFIG.refreshMs);
})();

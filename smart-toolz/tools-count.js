/*
 * Smart Toolz - Dynamic GitHub Tools Counter
 * Reads smart-toolz/tools/ from the GitHub repository and keeps the
 * frontend count/list synchronized without changing the existing tools.
 *
 * HTML usage:
 *   <span id="tools-count">0</span>
 *   <script src="tools-count.js" defer></script>
 *
 * Optional list container:
 *   <div id="tools-list"></div>
 *
 * v1.0 - dynamic tools count/list
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

    const countEl = document.querySelector(CONFIG.countSelector);
    const listEl = document.querySelector(CONFIG.listSelector);

    if (!countEl && !listEl) return;

    async function loadTools() {
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

            // In this repository the actual tools are PHP files.
            // Ignore dot-files and internal/trigger files beginning with "_".
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

            countEl?.setAttribute("data-tools-updated", new Date().toISOString());
        } catch (error) {
            console.error("Unable to update GitHub tools count:", error);
            // Keep the last successful value instead of replacing it with 0.
        }
    }

    loadTools();
    window.setInterval(loadTools, CONFIG.refreshMs);
})();

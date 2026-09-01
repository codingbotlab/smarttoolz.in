# SmartToolz Video Automation

Free/open-source-first content pipeline for Knowledge Base how-to videos.

Planned pipeline:

1. Read real SmartToolz tool metadata/code.
2. Capture the real tool workflow with Playwright/Chromium.
3. Store screenshots and source captures in the tool's generation folder.
4. Build a Remotion composition from the captured assets.
5. Render a long how-to video and a separate short.
6. Generate captions and attach narration.
7. Copy final published assets into `knowledge-base/<tool-slug>/videos/`.
8. Keep every generation under `knowledge-base/_generated/<tool-slug>/<date>/`.
9. Upload to YouTube only after the quality gate passes.

This project does not modify existing SmartToolz tool implementations. It consumes them as source material.

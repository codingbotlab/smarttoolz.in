import { chromium } from 'playwright';
import fs from 'node:fs';
import path from 'node:path';

const toolUrl = 'https://smarttoolz.in/smart-toolz/tools/qr-generator.php';
const outDir = path.resolve(process.cwd(), '../output');
const maxDurationMs = 60_000;
fs.mkdirSync(outDir, { recursive: true });

// Keep the browser demo deliberately shorter than one minute.
// Page-load and real interaction time are included in the recording, so the
// scene holds total 47 seconds and leave a small safety margin below 60s.
const scenes = [
  ['intro', 5], ['open-tool', 6], ['enter-content', 7], ['options', 7],
  ['generate', 7], ['result', 6], ['practical-tips', 5], ['outro', 4]
];

const browser = await chromium.launch({ headless: true });
const context = await browser.newContext({
  viewport: { width: 1280, height: 720 },
  deviceScaleFactor: 1,
  recordVideo: { dir: outDir, size: { width: 1280, height: 720 } }
});
const page = await context.newPage();
const recordingStart = Date.now();

try {
  await page.goto(toolUrl, { waitUntil: 'networkidle', timeout: 90_000 });
  await page.waitForTimeout(1000);

  for (const [scene, seconds] of scenes) {
    if (scene === 'enter-content') {
      const input = page.locator('#qrText');
      await input.scrollIntoViewIfNeeded();
      await input.fill('https://smarttoolz.in/');
      await page.waitForTimeout(500);
    }

    if (scene === 'generate') {
      const generate = page.getByRole('button', { name: /generate/i }).first();
      if (await generate.count()) {
        await generate.scrollIntoViewIfNeeded();
        await page.waitForTimeout(400);
        await generate.click();
        await page.waitForTimeout(1800);
      }
    }

    console.log(`Recording scene: ${scene}`);
    await page.waitForTimeout(seconds * 1000);

    if (Date.now() - recordingStart >= maxDurationMs) {
      console.log('Reached the one-minute recording safety limit.');
      break;
    }
  }
} finally {
  const video = page.video();
  await context.close();
  await browser.close();

  if (video) {
    const recordedPath = await video.path();
    const finalPath = path.join(outDir, 'browser-demo.webm');
    fs.copyFileSync(recordedPath, finalPath);
    console.log('Saved real browser recording:', finalPath);
  }
}

import { chromium } from 'playwright';
import fs from 'node:fs';
import path from 'node:path';

const toolUrl = 'https://smarttoolz.in/smart-toolz/tools/qr-generator.php';
const outDir = path.resolve(process.cwd(), '../output');
const targetMs = 300_000;
fs.mkdirSync(outDir, { recursive: true });

const scenes = [
  ['intro', 20], ['open-tool', 25], ['enter-content', 45], ['options', 55],
  ['generate', 45], ['result', 35], ['practical-tips', 35], ['outro', 40]
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
  await page.waitForTimeout(1800);

  for (const [scene, seconds] of scenes) {
    if (scene === 'enter-content') {
      const input = page.locator('#qrText');
      await input.scrollIntoViewIfNeeded();
      await input.fill('https://smarttoolz.in/');
      await page.waitForTimeout(900);
    }

    if (scene === 'generate') {
      const generate = page.getByRole('button', { name: /generate/i }).first();
      if (await generate.count()) {
        await generate.scrollIntoViewIfNeeded();
        await page.waitForTimeout(700);
        await generate.click();
        await page.waitForTimeout(2500);
      }
    }

    console.log(`Recording scene: ${scene}`);
    await page.waitForTimeout(seconds * 1000);
  }

  const remaining = targetMs - (Date.now() - recordingStart);
  if (remaining > 0) {
    console.log(`Holding final frame for ${remaining}ms to reach 300 seconds.`);
    await page.waitForTimeout(remaining);
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

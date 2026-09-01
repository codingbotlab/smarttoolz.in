import { chromium } from 'playwright';
import fs from 'node:fs';
import path from 'node:path';

const toolUrl = 'https://smarttoolz.in/smart-toolz/tools/qr-generator.php';
const outDir = path.resolve(process.cwd(), '../output');
fs.mkdirSync(outDir, { recursive: true });

const browser = await chromium.launch({ headless: true });
const context = await browser.newContext({
  viewport: { width: 1280, height: 720 },
  deviceScaleFactor: 1,
  recordVideo: { dir: outDir, size: { width: 1280, height: 720 } }
});
const page = await context.newPage();

await page.goto(toolUrl, { waitUntil: 'networkidle', timeout: 90000 });
await page.waitForTimeout(1800);

const input = page.locator('#qrText');
await input.scrollIntoViewIfNeeded();
await input.fill('https://smarttoolz.in/');
await page.waitForTimeout(900);

// Let the real page expose its own controls; do not invent selectors/features.
const generate = page.getByRole('button', { name: /generate/i }).first();
if (await generate.count()) {
  await generate.scrollIntoViewIfNeeded();
  await page.waitForTimeout(700);
  await generate.click();
  await page.waitForTimeout(2500);
}

await page.waitForTimeout(2500);
await context.close();
await browser.close();

console.log('Real SmartToolz QR browser recording created in:', outDir);

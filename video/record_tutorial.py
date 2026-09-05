import asyncio
import json
from pathlib import Path

from playwright.async_api import async_playwright

ROOT = Path(__file__).resolve().parent
JOB = ROOT / 'public' / 'job.json'

async def main():
    job = json.loads(JOB.read_text(encoding='utf-8'))
    base = job.get('base_url', 'https://smarttoolz.in')
    recording = ROOT / 'public' / 'recording'
    recording.mkdir(parents=True, exist_ok=True)
    for p in recording.glob('*.png'):
        p.unlink()

    async with async_playwright() as p:
        browser = await p.chromium.launch()
        page = await browser.new_page(viewport={'width': 1440, 'height': 900}, device_scale_factor=1)
        url = base.rstrip('/') + job['url']
        await page.goto(url, wait_until='networkidle', timeout=60000)
        await page.screenshot(path=str(recording / '001-open.png'), full_page=False)

        picker = page.locator('#picker')
        input_file = Path(job['input']).resolve()
        if await picker.count() and input_file.exists():
            await picker.set_input_files(str(input_file))
            await page.wait_for_timeout(1200)
            await page.screenshot(path=str(recording / '002-uploading.png'), full_page=False)
            try:
                await page.locator('#status').wait_for(state='visible', timeout=15000)
            except Exception:
                pass
            await page.wait_for_timeout(12000)
            await page.screenshot(path=str(recording / '003-result.png'), full_page=False)
            download = page.locator('#download')
            if await download.count():
                await download.scroll_into_view_if_needed()
                await page.screenshot(path=str(recording / '004-download-ready.png'), full_page=False)

        await browser.close()

if __name__ == '__main__':
    asyncio.run(main())

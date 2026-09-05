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
    for p in recording.glob('*'):
        if p.is_file():
            p.unlink()

    async with async_playwright() as p:
        browser = await p.chromium.launch()
        context = await browser.new_context(
            viewport={'width': 1440, 'height': 900},
            device_scale_factor=1,
            record_video_dir=str(recording),
            record_video_size={'width': 1440, 'height': 900},
        )
        page = await context.new_page()
        url = base.rstrip('/') + job['url']
        await page.goto(url, wait_until='networkidle', timeout=60000)
        await page.wait_for_timeout(1500)

        picker = page.locator('#picker')
        input_file = Path(job['input']).resolve()
        if not await picker.count() or not input_file.exists():
            await context.close()
            raise SystemExit(f'Tutorial input or file picker missing: {input_file}')

        await picker.set_input_files(str(input_file))
        await page.wait_for_timeout(2500)

        try:
            await page.get_by_text('Background removed', exact=True).wait_for(timeout=120000)
        except Exception:
            await page.wait_for_timeout(5000)

        download = page.locator('#download')
        if await download.count():
            await download.scroll_into_view_if_needed()
            await page.wait_for_timeout(1500)

        await page.wait_for_timeout(1200)
        await context.close()
        await browser.close()

    videos = sorted(recording.glob('*.webm'), key=lambda x: x.stat().st_mtime, reverse=True)
    if not videos:
        raise SystemExit('Playwright did not produce a browser video')
    source = videos[0]
    target = recording / 'tutorial.webm'
    if source != target:
        source.replace(target)
    print(f'Recorded tutorial video: {target}')

if __name__ == '__main__':
    asyncio.run(main())

import asyncio
import json
import os
import re
from pathlib import Path

import edge_tts

ROOT = Path(__file__).resolve().parent
PUBLIC = ROOT / 'public'
AUDIO = PUBLIC / 'audio'
JOB = PUBLIC / 'job.json'

VOICES = {
    'hi-IN-MadhurNeural': {'language': 'hi-IN', 'rate': '-4%', 'pitch': '-1Hz'},
    'en-IN-PrabhatNeural': {'language': 'en-IN', 'rate': '-3%', 'pitch': '-1Hz'},
    'en-US-GuyNeural': {'language': 'en-US', 'rate': '-4%', 'pitch': '-1Hz'},
}


def clean_text(value: str) -> str:
    value = re.sub(r'\s+', ' ', value.replace('\r', ' ').replace('\n', ' ')).strip()
    return value


def split_sections(script: str):
    paragraphs = [clean_text(x) for x in re.split(r'\n\s*\n+', script) if clean_text(x)]
    if paragraphs:
        return paragraphs
    return [clean_text(script)]


async def generate():
    job = json.loads(JOB.read_text(encoding='utf-8'))
    title = str(job.get('title', 'SmartToolz Tutorial')).strip() or 'SmartToolz Tutorial'
    script = str(job.get('script', '')).strip()
    voice = str(job.get('voice', 'hi-IN-MadhurNeural'))
    if voice not in VOICES:
        raise ValueError(f'Unsupported voice: {voice}')
    if not script:
        raise ValueError('Script is empty')

    sections = split_sections(script)
    AUDIO.mkdir(parents=True, exist_ok=True)

    for old in AUDIO.glob('section-*.mp3'):
        old.unlink()

    cfg = VOICES[voice]
    result = []
    for i, text in enumerate(sections, 1):
        filename = f'section-{i:03d}.mp3'
        output = AUDIO / filename
        communicate = edge_tts.Communicate(text, voice, rate=cfg['rate'], pitch=cfg['pitch'])
        await communicate.save(str(output))
        result.append({'text': text, 'audio': f'audio/{filename}'})
        print(f'Generated {filename}: {len(text)} chars')

    render_props = {
        'title': title,
        'sections': result,
        'format': str(job.get('format', 'landscape')) if str(job.get('format', 'landscape')) in ('landscape', 'portrait') else 'landscape',
    }
    (PUBLIC / 'render-props.json').write_text(json.dumps(render_props, ensure_ascii=False, indent=2), encoding='utf-8')
    print(f'Prepared {len(result)} narrated sections with voice {voice}.')


if __name__ == '__main__':
    asyncio.run(generate())

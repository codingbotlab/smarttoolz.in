import json
import re
from pathlib import Path

ROOT = Path(__file__).resolve().parent
CONFIG = ROOT / 'tutorials.json'
OUT = ROOT / 'public' / 'job.json'


def clean(value):
    return re.sub(r'\s+', ' ', str(value)).strip()


def build_script(tool):
    name = clean(tool['name'])
    description = clean(tool.get('description', ''))
    steps = [clean(x) for x in tool.get('steps', []) if clean(x)]
    paragraphs = [
        f"In this SmartToolz tutorial, we are going to use {name}. {description}",
    ]
    for i, step in enumerate(steps, 1):
        paragraphs.append(f"Step {i}. {step}")
    paragraphs.append("That is it. The tool is ready to use, and the result can be downloaded directly from SmartToolz.")
    return '\n\n'.join(paragraphs)


def main():
    data = json.loads(CONFIG.read_text(encoding='utf-8'))
    slug = __import__('os').environ.get('TUTORIAL_SLUG', 'image-background-remover')
    if slug not in data:
        raise SystemExit(f'Unknown tutorial: {slug}')
    tool = data[slug]
    job = {
        'title': f"{tool['name']} — SmartToolz Tutorial",
        'script': build_script(tool),
        'voice': tool.get('voice', 'hi-IN-MadhurNeural'),
        'format': tool.get('format', 'landscape'),
        'recording_dir': str(ROOT / 'public' / 'recording'),
        'output': tool.get('output', f'video-library/{slug}.mp4'),
    }
    OUT.parent.mkdir(parents=True, exist_ok=True)
    OUT.write_text(json.dumps(job, ensure_ascii=False, indent=2), encoding='utf-8')
    print(job['script'])


if __name__ == '__main__':
    main()

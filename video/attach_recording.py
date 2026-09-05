import json
from pathlib import Path

ROOT = Path(__file__).resolve().parent
props_path = ROOT / 'public' / 'render-props.json'
recording = ROOT / 'public' / 'recording'
props = json.loads(props_path.read_text(encoding='utf-8'))
shots = sorted(recording.glob('*.png'))
if not shots:
    raise SystemExit('No browser screenshots were recorded')
for i, section in enumerate(props.get('sections', [])):
    shot = shots[min(i, len(shots) - 1)]
    section['screenshot'] = f'recording/{shot.name}'
props_path.write_text(json.dumps(props, ensure_ascii=False, indent=2), encoding='utf-8')
print(f'Attached {len(shots)} screenshots to {len(props.get("sections", []))} narration sections')

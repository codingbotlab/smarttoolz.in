import json
import os
from pathlib import Path

import numpy as np
import soundfile as sf
from kokoro import KPipeline

ROOT = Path(__file__).resolve().parent
SCRIPT = json.loads((ROOT / "script.json").read_text(encoding="utf-8"))
OUT = ROOT / "output" / "voice"
OUT.mkdir(parents=True, exist_ok=True)

# Natural-sounding open-weight voice. Kokoro's af_heart is an American-English
# female voice; keep one consistent voice across the whole tutorial.
VOICE = os.getenv("KOKORO_VOICE", "af_heart")
SPEED = float(os.getenv("KOKORO_SPEED", "1.02"))

pipeline = KPipeline(lang_code="a")

files = []
for scene in SCRIPT["scenes"]:
    safe = "".join(c if c.isalnum() or c in "_-" else "-" for c in scene["id"])
    wav = OUT / f"{safe}.wav"
    chunks = []

    for _, _, audio in pipeline(scene["voice"], voice=VOICE, speed=SPEED):
        if audio is not None:
            chunks.append(audio.detach().cpu().numpy().astype(np.float32))

    if not chunks:
        raise RuntimeError(f"Kokoro produced no audio for scene {scene['id']}")

    audio = np.concatenate(chunks)
    sf.write(wav, audio, 24000, subtype="PCM_16")
    files.append(wav)
    print(f"Generated {scene['id']}: {len(audio) / 24000:.2f}s")

# ffmpeg handles the final mix/resample and pads the track to the 5-minute
# composition duration expected by Remotion.
concat = OUT / "concat.txt"
concat.write_text(
    "\n".join(f"file '{str(f).replace(chr(39), chr(39)+chr(92)+chr(39)+chr(39))}'" for f in files) + "\n",
    encoding="utf-8",
)
print(f"Kokoro scenes generated in {OUT}")

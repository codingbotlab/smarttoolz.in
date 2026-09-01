import fs from 'node:fs';
import path from 'node:path';
import { spawnSync } from 'node:child_process';

const root = path.dirname(new URL(import.meta.url).pathname);
const python = process.env.KOKORO_PYTHON || 'python3';
const ttsScript = path.join(root, 'tts_kokoro.py');
const out = path.join(root, 'output', 'voice');
fs.mkdirSync(out, { recursive: true });

console.log(`Using Kokoro TTS via ${python}`);
const tts = spawnSync(python, [ttsScript], { stdio: 'inherit' });
if (tts.status !== 0) throw new Error('Kokoro TTS generation failed');

const concat = path.join(out, 'concat.txt');
if (!fs.existsSync(concat) || fs.statSync(concat).size === 0) {
  throw new Error('Kokoro concat manifest was not created');
}

const narration = path.join(root, 'output', 'narration.wav');
const r = spawnSync('ffmpeg', [
  '-y',
  '-f', 'concat',
  '-safe', '0',
  '-i', concat,
  '-ar', '48000',
  '-ac', '2',
  '-af', 'loudnorm=I=-16:TP=-1.5:LRA=11,apad',
  '-t', '300',
  narration,
], { stdio: 'inherit' });
if (r.status !== 0) throw new Error('ffmpeg narration concat failed');

console.log('Natural Kokoro narration created:', narration);

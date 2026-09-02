import fs from 'node:fs';
import path from 'node:path';
import { fileURLToPath } from 'node:url';
import { spawnSync } from 'node:child_process';

const root = path.dirname(fileURLToPath(import.meta.url));
const python = process.env.KOKORO_PYTHON || 'python';
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
const durationFile = path.join(root, 'output', 'narration-duration.txt');
const r = spawnSync('ffmpeg', [
  '-y',
  '-f', 'concat',
  '-safe', '0',
  '-i', concat,
  '-ar', '48000',
  '-ac', '2',
  '-af', 'loudnorm=I=-16:TP=-1.5:LRA=11',
  narration,
], { stdio: 'inherit' });
if (r.status !== 0) throw new Error('ffmpeg narration concat failed');

const probe = spawnSync('ffprobe', [
  '-v', 'error',
  '-show_entries', 'format=duration',
  '-of', 'default=noprint_wrappers=1:nokey=1',
  narration,
], { encoding: 'utf8' });
if (probe.status !== 0) throw new Error('ffprobe narration duration check failed');

const duration = Number.parseFloat(probe.stdout.trim());
if (!Number.isFinite(duration) || duration <= 0) {
  throw new Error(`Invalid narration duration: ${probe.stdout}`);
}
if (duration > 60.0) {
  throw new Error(`Narration is too long (${duration.toFixed(2)}s). Maximum is 60 seconds.`);
}

fs.writeFileSync(durationFile, `${duration.toFixed(3)}\n`);
console.log(`Natural Kokoro narration created: ${narration}`);
console.log(`Narration duration: ${duration.toFixed(3)} seconds`);

import fs from 'node:fs';
import path from 'node:path';
import { spawnSync } from 'node:child_process';

const root = path.dirname(new URL(import.meta.url).pathname);
const script = JSON.parse(fs.readFileSync(path.join(root, 'script.json'), 'utf8'));
const out = path.join(root, 'output', 'voice');
fs.mkdirSync(out, { recursive: true });

const files = [];
for (const scene of script.scenes) {
  const safe = scene.id.replace(/[^a-z0-9_-]/gi, '-');
  const wav = path.join(out, `${safe}.wav`);
  const r = spawnSync('espeak-ng', ['-v', 'en', '-s', '145', '-p', '48', '-w', wav, scene.voice], { stdio: 'inherit' });
  if (r.status !== 0) throw new Error(`espeak-ng failed for ${scene.id}`);
  files.push(wav);
}

const concat = path.join(out, 'concat.txt');
fs.writeFileSync(concat, files.map(f => `file '${f.replaceAll("'", "'\\''")}'`).join('\n'));
const narration = path.join(root, 'output', 'narration.wav');
const r = spawnSync('ffmpeg', ['-y', '-f', 'concat', '-safe', '0', '-i', concat, '-ar', '48000', '-ac', '2', narration], { stdio: 'inherit' });
if (r.status !== 0) throw new Error('ffmpeg narration concat failed');
console.log('Narration created:', narration);

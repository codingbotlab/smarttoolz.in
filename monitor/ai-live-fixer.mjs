import { readFile, writeFile, access } from 'node:fs/promises';
import { execFileSync } from 'node:child_process';

const apiKey = process.env.OPENAI_API_KEY || '';
const model = process.env.OPENAI_MODEL || 'gpt-5';
const reportPath = 'monitor/latest-report.json';
const maxAttempts = Number(process.env.FIX_MAX_ATTEMPTS || 3);
const baseUrl = process.env.CRAWLER_BASE_URL || 'https://smarttoolz.in/smart-toolz/';

if (!apiKey) {
  console.log('OPENAI_API_KEY is not configured; skipping automatic code fix.');
  process.exit(0);
}

async function exists(path) {
  try { await access(path); return true; } catch { return false; }
}

function run(cmd, args) {
  return execFileSync(cmd, args, { encoding: 'utf8', stdio: ['ignore', 'pipe', 'pipe'] });
}

const report = JSON.parse(await readFile(reportPath, 'utf8'));
if (!report.errors?.length) {
  console.log('No errors to fix.');
  process.exit(0);
}

for (let attempt = 1; attempt <= maxAttempts; attempt++) {
  const files = run('git', ['ls-files', 'smart-toolz', 'monitor']).trim().split('\n').filter(Boolean);
  const candidates = [];
  for (const path of files.slice(0, 500)) {
    if (!/\.(php|js|html|css|json|mjs)$/.test(path)) continue;
    try {
      const content = await readFile(path, 'utf8');
      if (content.length <= 50000) candidates.push({ path, content });
    } catch {}
  }

  const prompt = `You are a conservative production bug fixer for SmartToolz. Fix only the concrete live error(s) in the supplied crawler report. Do not redesign, refactor unrelated code, add dependencies, expose secrets, or change URLs unless required. Return STRICT JSON with this shape: {"summary":"...","files":[{"path":"repo/path","content":"complete replacement file content"}]}. Only include files that must change. If the error cannot be safely fixed from the supplied evidence, return files: [].\n\nLIVE BASE URL: ${baseUrl}\nATTEMPT: ${attempt}/${maxAttempts}\nREPORT:\n${JSON.stringify(report.errors, null, 2)}\n\nREPOSITORY FILES:\n${candidates.map(f => `\\n--- ${f.path} ---\\n${f.content}`).join('\\n')}`;

  const response = await fetch('https://api.openai.com/v1/responses', {
    method: 'POST',
    headers: { authorization: `Bearer ${apiKey}`, 'content-type': 'application/json' },
    body: JSON.stringify({ model, input: prompt }),
  });
  if (!response.ok) throw new Error(`OpenAI API ${response.status}: ${await response.text()}`);
  const data = await response.json();
  const text = data.output_text || data.output?.flatMap(x => x.content || []).map(x => x.text || '').join('') || '';
  const jsonText = text.match(/\{[\s\S]*\}/)?.[0];
  if (!jsonText) throw new Error('AI returned no JSON patch.');
  const patch = JSON.parse(jsonText);
  if (!Array.isArray(patch.files) || patch.files.length === 0) {
    console.log(`No safe fix proposed: ${patch.summary || 'unknown reason'}`);
    process.exit(0);
  }

  // Safety checkpoint before every AI modification.
  const tag = `auto-fix-backup-${new Date().toISOString().replace(/[:.]/g, '-')}`;
  run('git', ['config', 'user.name', 'SmartToolz Auto Fixer']);
  run('git', ['config', 'user.email', 'actions@smarttoolz.in']);
  run('git', ['tag', tag]);
  run('git', ['push', 'origin', tag]);

  for (const file of patch.files) {
    if (!file.path || !file.content || !files.includes(file.path)) throw new Error(`Refusing unknown or empty file: ${file.path}`);
    if (!file.path.startsWith('smart-toolz/') && !file.path.startsWith('monitor/')) throw new Error(`Refusing path outside allowed scope: ${file.path}`);
    await writeFile(file.path, file.content, 'utf8');
  }

  // Basic syntax validation for PHP files changed by the fixer.
  for (const file of patch.files.filter(f => f.path.endsWith('.php'))) run('php', ['-l', file.path]);

  run('git', ['add', '--', ...patch.files.map(f => f.path)]);
  run('git', ['commit', '-m', `Auto-fix live error: ${String(patch.summary || 'production error').slice(0, 120)}`]);
  run('git', ['push', 'origin', 'HEAD:main']);
  console.log(`Applied and pushed attempt ${attempt}: ${patch.summary || 'live error fix'}`);
  break;
}

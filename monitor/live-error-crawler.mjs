import { writeFile } from 'node:fs/promises';

const BASE_URL = process.env.CRAWLER_BASE_URL || 'https://smarttoolz.in/smart-toolz/';
const WEBHOOK = process.env.DISCORD_WEBHOOK_URL || '';
const MAX_PAGES = Number(process.env.CRAWLER_MAX_PAGES || 40);
const TIMEOUT_MS = Number(process.env.CRAWLER_TIMEOUT_MS || 15000);
const SAME_ORIGIN_ONLY = true;

const root = new URL(BASE_URL);
const seen = new Set();
const queue = [root.href];
const errors = [];
const checked = [];

function normalize(raw, base) {
  try {
    const u = new URL(raw, base);
    u.hash = '';
    if (u.protocol !== 'http:' && u.protocol !== 'https:') return null;
    if (SAME_ORIGIN_ONLY && u.origin !== root.origin) return null;
    return u.href;
  } catch { return null; }
}

async function get(url) {
  const controller = new AbortController();
  const timer = setTimeout(() => controller.abort(), TIMEOUT_MS);
  try {
    const response = await fetch(url, {
      redirect: 'follow',
      signal: controller.signal,
      headers: { 'user-agent': 'SmartToolz-LiveErrorCrawler/1.0', accept: 'text/html,application/xhtml+xml,*/*;q=0.8' },
    });
    const text = await response.text();
    return { response, text };
  } finally { clearTimeout(timer); }
}

function addError(type, url, detail, extra = {}) {
  errors.push({ type, url, detail, ...extra });
}

while (queue.length && checked.length < MAX_PAGES) {
  const url = queue.shift();
  if (seen.has(url)) continue;
  seen.add(url);
  try {
    const { response, text } = await get(url);
    checked.push({ url, status: response.status, contentType: response.headers.get('content-type') || '' });

    if (!response.ok) addError('HTTP', url, `HTTP ${response.status} ${response.statusText}`);

    const lower = text.toLowerCase();
    const fatalMarkers = [
      'fatal error', 'uncaught error', 'uncaught exception',
      'parse error', 'maximum execution time', 'allowed memory size',
      'call to undefined function', 'call to a member function',
      'internal server error'
    ];
    for (const marker of fatalMarkers) {
      const i = lower.indexOf(marker);
      if (i >= 0) {
        const excerpt = text.slice(Math.max(0, i - 180), Math.min(text.length, i + 420)).replace(/\s+/g, ' ').trim();
        addError('PAGE_ERROR', url, excerpt);
        break;
      }
    }

    const contentType = response.headers.get('content-type') || '';
    if (contentType.includes('text/html')) {
      const hrefs = [...text.matchAll(/(?:href|src|action)=["']([^"']+)["']/gi)].map(m => m[1]);
      for (const raw of hrefs) {
        const next = normalize(raw, url);
        if (next && !seen.has(next) && queue.length < MAX_PAGES * 3) queue.push(next);
      }
    }
  } catch (error) {
    const detail = error?.name === 'AbortError' ? `Timeout after ${TIMEOUT_MS}ms` : (error?.stack || error?.message || String(error));
    checked.push({ url, status: null, contentType: '' });
    addError('REQUEST', url, detail);
  }
}

const report = {
  generatedAt: new Date().toISOString(),
  baseUrl: BASE_URL,
  pagesChecked: checked.length,
  errorCount: errors.length,
  errors,
  checked,
};

await writeFile('monitor/latest-report.json', JSON.stringify(report, null, 2));

if (WEBHOOK) {
  const lines = errors.length
    ? errors.slice(0, 10).map((e, i) => `**${i + 1}. ${e.type}**\n${e.url}\n\`${e.detail.slice(0, 900)}\``)
    : ['No live errors detected.'];
  const content = `🔎 **SmartToolz Live Error Crawler**\n**URL:** ${BASE_URL}\n**Checked:** ${checked.length} pages\n**Errors:** ${errors.length}\n**Time:** ${report.generatedAt}\n\n${lines.join('\n\n')}`;
  await fetch(WEBHOOK, {
    method: 'POST',
    headers: { 'content-type': 'application/json' },
    body: JSON.stringify({ content: content.slice(0, 1900) }),
  });
}

console.log(JSON.stringify(report, null, 2));
if (errors.length) process.exitCode = 1;

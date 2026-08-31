from pathlib import Path
import re

ROOT = Path('smart-toolz/tools')
HEADER_INCLUDE = "<?php require_once dirname(__DIR__) . '/header.php'; ?>"

# Keep exactly one sidebar: the existing tool-sidebar.php / page sidebar.
# Remove the old fixed global sidebar that was previously injected into pages.
GLOBAL_PATTERNS = [
    r'<style\s+id=["\']smarttoolz-global-sidebar-css["\'][\s\S]*?</style>',
    r'<aside\s+id=["\']smarttoolz-global-sidebar["\'][\s\S]*?</aside>',
    r'<button\s+id=["\']smarttoolz-tools-toggle["\'][\s\S]*?</button>',
    r'<script>\(function\(\)\{const s=document\.getElementById\(["\']smarttoolz-global-sidebar["\'][\s\S]*?</script>',
]

for path in sorted(ROOT.glob('*.php')):
    if path.name.startswith('_') or path.name in {'tool-shell.php', 'tool-sidebar.php'}:
        continue
    text = path.read_text(encoding='utf-8-sig')
    if '<!doctype html' not in text.lower():
        continue

    original = text

    # Remove every copy of the old global/floating sidebar and its toggle.
    for pattern in GLOBAL_PATTERNS:
        text = re.sub(pattern, '', text, flags=re.I)

    # Remove the old desktop body offset left behind by the floating sidebar.
    text = re.sub(r'\s*body\.smarttoolz-sidebar-page\s*\{[^}]*\}', '', text, flags=re.I)

    # Ensure the common header is present exactly once.
    text = re.sub(r'<header\s+class=["\']site-header["\'][\s\S]*?</header>', '', text, count=1, flags=re.I)
    if HEADER_INCLUDE not in text:
        text, count = re.subn(r'(<body(?:\s[^>]*)?>)', r'\1\n' + HEADER_INCLUDE, text, count=1, flags=re.I)

    if text != original:
        path.write_text(text, encoding='utf-8')
        print(f'cleaned: {path}')

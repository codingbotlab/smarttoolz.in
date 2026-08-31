from pathlib import Path
import re

ROOT = Path('smart-toolz/tools')
INCLUDE = "<?php require_once dirname(__DIR__) . '/header.php'; ?>"

for path in sorted(ROOT.glob('*.php')):
    if path.name == 'tool-sidebar.php':
        continue
    text = path.read_text(encoding='utf-8-sig')
    if '<!DOCTYPE html' not in text and '<!doctype html' not in text:
        continue
    if 'class="site-header"' not in text:
        continue

    # Remove the page-local header markup only. Keep each tool's own CSS intact.
    new_text, count = re.subn(
        r'<header\s+class=["\']site-header["\'][\s\S]*?</header>',
        '',
        text,
        count=1,
        flags=re.IGNORECASE,
    )
    if count != 1:
        continue

    if INCLUDE not in new_text:
        new_text = re.sub(
            r'(<body(?:\s[^>]*)?>)',
            r'\1\n' + INCLUDE,
            new_text,
            count=1,
            flags=re.IGNORECASE,
        )

    path.write_text(new_text, encoding='utf-8')
    print(f'migrated: {path}')

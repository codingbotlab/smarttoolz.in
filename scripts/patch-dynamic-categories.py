from pathlib import Path

p = Path('smart-toolz/tool.php')
s = p.read_text(encoding='utf-8')
marker = 'SMARTTOOLZ_DYNAMIC_CATEGORIES_V1'
if marker in s:
    print('dynamic categories already wired')
    raise SystemExit(0)

needle = '/* Home uses this file only as the shared tool registry. Do not render All Tools there. */'
insert = '''/* SMARTTOOLZ_DYNAMIC_CATEGORIES_V1 */\nrequire_once __DIR__ . '/lib/categories.php';\nsmarttoolz_apply_tool_categories($tools);\n\n'''
if needle not in s:
    raise SystemExit('tool registry marker not found')

s = s.replace(needle, insert + needle, 1)
p.write_text(s, encoding='utf-8')
print('dynamic categories wired')

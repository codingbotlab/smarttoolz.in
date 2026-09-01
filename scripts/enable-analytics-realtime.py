from pathlib import Path
import re

p = Path('analytics/index.php')
s = p.read_text(encoding='utf-8')

# Remove the old full-page 30s reload block.
s = re.sub(r"\n\s*<script>\s*/\*\s*\|[-\s]*Auto refresh.*?</script>\s*\n\s*</body>", "\n<script src=\"/analytics/realtime.js?v=20260901-1\" defer></script>\n\n</body>", s, flags=re.I|re.S)

# If the old block isn't present, still add the realtime script before </body>.
if '/analytics/realtime.js' not in s:
    s = s.replace('</body>', '<script src="/analytics/realtime.js?v=20260901-1" defer></script>\n\n</body>', 1)

p.write_text(s, encoding='utf-8')
print('analytics realtime enabled')

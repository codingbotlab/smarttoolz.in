# SmartToolz Developer Architecture

SmartToolz is a modular PHP platform. The repository should be understood in layers: public entry points, product modules, shared application code, automation, and developer documentation.

## Target mental model

```text
smarttoolz.in/
├── public entry points        # index.php + product route directories
├── smart-toolz/               # core tools product
├── knowledge-base/            # knowledge product
├── learning-hub/              # learning product
├── creator-ai/                # creator/AI product
├── ai-social-media/           # experimental social product
├── Reddott-films/             # content/video product
├── video-automation/          # Remotion automation
├── analytics/                 # analytics and operations
├── auth/                      # shared authentication surface
├── config/                    # environment templates and configuration docs
├── core/                      # shared platform infrastructure
├── scripts/                   # maintenance and migrations
├── .github/workflows/         # CI/CD and scheduled automation
├── assets/                    # global front-end CSS/JS/media
└── docs/                      # developer documentation
```

## Rules for new code

### HTML / CSS / JavaScript
- Page markup belongs with its product module.
- Shared visual primitives belong in `assets/css/`.
- Shared browser behavior belongs in `assets/js/`.
- Avoid large inline `<style>` and `<script>` blocks when code is reusable.

### PHP
- Product PHP stays inside the product that owns it.
- Cross-product helpers belong in `core/` or a clearly named shared library.
- API endpoints should live under the owning module's `api/` directory.
- Database access must use prepared statements.
- Secrets must come from server environment/configuration, never source code.

### Python
- Automation and content/video scripts belong in `scripts/` or the owning automation module.
- Keep Python dependencies and entry points documented next to the script family.
- Generated files should never be treated as source code.

## Public routing principle

Existing public URLs are valuable. Do not change a public URL merely to make an internal folder name prettier. When refactoring, keep compatibility entry points/redirects where required and move implementation behind them.

## Front-end navigation

The root SmartToolz front page is the project-level hub. It links the major products directly:

- Tools → `/smart-toolz/`
- Knowledge → `/knowledge-base/`
- Learning → `/learning-hub/`
- Creator AI → `/creator-ai/`
- AI Social World → `/ai-social-media/`
- Reddott Films → `/Reddott-films/blogs/`
- Analytics → `/analytics/`
- Admin → `/smart-toolz/admin/`

The global stylesheet and JavaScript are under `/assets/css/` and `/assets/js/`.

## Refactor workflow

1. Identify the owning product for a file.
2. Separate shared code from product-specific code.
3. Move implementation first; preserve the old public route when needed.
4. Replace hard-coded relative paths with stable application paths.
5. Update imports/includes and navigation links.
6. Run link, PHP syntax, and workflow checks.
7. Remove the compatibility layer only after production URLs are verified.

## Naming

Use lowercase, descriptive directory names for new infrastructure. Use existing branded product names (`Reddott-films`, `SmartToolz`) where changing the URL or public identity would create unnecessary breakage.

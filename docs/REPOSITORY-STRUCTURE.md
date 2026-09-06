# SmartToolz Repository Structure

This is the canonical developer map for the monorepo.

```text
smarttoolz.in/
├── index.php                  # Public platform home / front door
├── assets/                    # Global browser assets
│   ├── css/
│   ├── js/
│   └── img/
├── smart-toolz/               # Core online tools application
│   ├── tools/                 # Individual public tools
│   ├── api/                   # Tool APIs / JSON endpoints
│   ├── admin/                 # Tool administration
│   ├── lib/                   # Tool-specific libraries
│   └── saas/                  # Plans, usage and account features
├── creator-ai/                # AI + creator application
├── learning-hub/              # Courses, lessons and practice
├── knowledge-base/            # Guides and reference content
├── analytics/                 # Tracking and analytics
├── Reddott-films/             # Content and YouTube workflows
├── video-automation/          # Remotion/video automation
├── monitor/                   # Site health and live error tooling
├── keddy-bot/                 # Bot application
├── auth/                      # Authentication/shared auth integration
├── config/                    # Environment/configuration helpers
├── core/                      # Future repository-wide shared backend
├── scripts/                   # Repository-wide maintenance/migrations
├── developer/                 # Developer-facing navigation and architecture
├── docs/                      # Engineering documentation
└── .github/workflows/         # CI/CD and scheduled automation
```

## File placement

**HTML/CSS/JS**

- Global CSS/JS → `/assets/css/` and `/assets/js/`.
- Module-only CSS/JS → that module's own `assets/` directory.
- Do not create random CSS/JS files at the repository root.

**PHP**

- Public page/controller → the owning module.
- Shared module PHP → `<module>/core/` or `<module>/includes/`.
- JSON/API endpoint → `<module>/api/`.
- Admin-only code → `<module>/admin/`.
- Database migrations/seeds → `<module>/database/`, `<module>/migrations/` or `<module>/seed/`.

**Python / Node / automation**

- Workflow-owned scripts → the owning module.
- Repository-wide maintenance → `/scripts/`.
- Remotion code → `/video-automation/`.

## Routing rule

Public URLs are treated as an API. Reorganise implementation behind a stable entry point instead of changing production URLs unnecessarily.

The root `/index.php`, `/developer/`, and `/site-wire-map.php` provide the front-door and developer navigation into the ecosystem.

## Migration rule

Refactors happen module-by-module. Before moving a file:

1. Find every include/import/reference.
2. Create the new canonical location.
3. Update internal references.
4. Keep a compatibility entry point if the old public URL is used.
5. Verify the route and dependent workflows.
6. Delete the old implementation only after verification.

This prevents a clean-looking tree from breaking the live site.

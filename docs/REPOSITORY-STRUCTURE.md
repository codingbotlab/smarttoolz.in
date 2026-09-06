# SmartToolz Repository Structure

This is the canonical developer map for the active SmartToolz monorepo.

```text
smarttoolz.in/
├── index.php                  # Public SmartToolz front door
├── assets/                    # Global browser assets
│   ├── css/
│   ├── js/
│   └── img/
├── smart-toolz/               # Core online-tools application
│   ├── tools/                 # Individual public tools
│   ├── api/                   # Tool APIs / JSON endpoints
│   ├── admin/                 # Administration
│   ├── lib/                   # Libraries/helpers
│   └── saas/                  # Plans, usage and account features
├── learning-hub/              # Courses, lessons and practice
├── knowledge-base/            # Guides and reference content
├── ai-social-media/           # Social/world application
├── analytics/                 # Tracking and analytics
├── keddy-bot/                 # Bot application
├── auth/                      # Shared authentication integration
├── config/                    # Environment/configuration helpers
├── core/                      # Repository-wide shared backend
├── scripts/                   # Maintenance/migrations/patches
├── developer/                 # Developer navigation and documentation
├── docs/                      # Engineering documentation
└── .github/workflows/         # Active CI/CD automation
```

## File placement

**HTML/CSS/JS**

- Global CSS/JS → `/assets/css/` and `/assets/js/`.
- Module-only CSS/JS → the owning module's `assets/` directory.
- Avoid random CSS/JS files at the repository root.

**PHP**

- Public page/controller → the owning module.
- Shared module PHP → `<module>/core/` or `<module>/includes/`.
- JSON/API endpoint → `<module>/api/`.
- Admin-only code → `<module>/admin/`.
- Database migrations/seeds → the owning module's database/migrations/seed area.

**Python / Node / automation**

- Workflow-owned scripts → the owning module.
- Repository-wide maintenance → `/scripts/`.

## Routing rule

Public URLs are treated as an API. Keep the root entry and active module routes stable while reorganising implementation.

The root `/index.php` is the public front door and `/developer/` is the developer entry point.

## Refactor rule

Before moving active code:

1. Find includes/imports/references.
2. Create the canonical destination.
3. Update internal references.
4. Verify the route and dependent code.
5. Remove obsolete implementations only after their replacement is live.

Retired products should not remain linked from the public site or developer navigation.

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

- Global CSS/JS → `/assets/css/` and `/assets/js/`.
- Module frontend assets → the owning module's `assets/` directory.
- Public PHP → the owning module.
- Shared PHP → `<module>/core/` or `<module>/includes/`.
- JSON/API endpoints → `<module>/api/`.
- Admin code → `<module>/admin/`.
- Repository-wide maintenance → `/scripts/`.

## Routing

Public URLs are treated as an API. The root `/index.php` is the public front door and `/developer/` is the developer entry point.

## Refactor rule

Before moving active code, find references, create the canonical destination, update imports/includes, verify the route, then remove the obsolete implementation. Retired products should not remain linked from the public site or developer navigation.

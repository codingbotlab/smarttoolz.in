# SmartToolz Repository Structure

Active SmartToolz monorepo map.

```text
smarttoolz.in/
├── index.php
├── assets/
│   ├── css/
│   ├── js/
│   └── img/
├── smart-toolz/          # Core tools, APIs, admin and SaaS
├── knowledge-base/       # Guides and reference content
├── learning-hub/         # Courses, lessons and curriculum
├── ai-social-media/      # Social/world application
├── analytics/            # Analytics and tracking
├── keddy-bot/            # Bot application
├── auth/                 # Authentication integration
├── config/               # Configuration helpers
├── core/                 # Shared backend foundation
├── scripts/              # Maintenance and migrations
├── developer/            # Developer entry point
├── docs/                 # Engineering documentation
└── .github/workflows/    # Active CI/CD automation
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

`/index.php` is the public SmartToolz front door. `/developer/` is the developer entry point. Retired products are not kept in public navigation or active automation.

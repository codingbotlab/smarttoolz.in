# SmartToolz Developer Hub

Developer entry point for the active `smarttoolz.in` monorepo.

## Active modules

| Module | Purpose | Public entry |
|---|---|---|
| `smart-toolz/` | Online tools platform | `/smart-toolz/` |
| `learning-hub/` | Courses and lessons | `/learning-hub/` |
| `knowledge-base/` | Guides and articles | `/knowledge-base/` |
| `ai-social-media/` | Social/world application | `/ai-social-media/` |
| `analytics/` | Tracking and analytics | `/analytics/` |
| `keddy-bot/` | Bot application | `/keddy-bot/` |

## Repository rules

- Keep each active module self-contained.
- Put global CSS/JS in `/assets/css/` and `/assets/js/`.
- Put module-only frontend assets in that module's `assets/` directory.
- Put APIs under `<module>/api/`.
- Put admin code under `<module>/admin/`.
- Put shared module PHP under `<module>/core/` or `<module>/includes/`.
- Put repository-wide maintenance in `/scripts/`.
- Keep secrets in protected environment/server configuration.
- Preserve working public routes when reorganising active code.
- Remove retired products from navigation, registries and automation.

## Request flow

```text
User → Root Entry → Router → Active Module → API/Service → UI → Response
```

Use `docs/REPOSITORY-STRUCTURE.md` as the canonical repository map.

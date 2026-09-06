# SmartToolz Developer Hub

Developer entry point for the `smarttoolz.in` monorepo.

## Repository rules

- Keep each product/module self-contained.
- Put shared application PHP in the owning module's `core/` or `includes/` directory.
- Put browser CSS in `assets/css/` and browser JavaScript in `assets/js/`.
- Put images, icons and other static media in `assets/img/`, `assets/icons/` or the module's existing asset tree.
- Put APIs under `api/`.
- Put administrative code under `admin/`.
- Put migrations/seeds under `database/`, `migrations/` or `seed/` according to the owning module.
- Put automation scripts beside the module they automate; repository-wide maintenance stays in `/scripts/`.
- Keep secrets in environment configuration, never in committed source.
- Preserve public routes while reorganising internals; use compatibility entry points when a move would otherwise break production URLs.

## Product modules

| Module | Purpose | Public entry |
|---|---|---|
| `smart-toolz/` | Online tools platform | `/smart-toolz/` |
| `creator-ai/` | AI + creator application | `/creator-ai/` |
| `learning-hub/` | Courses and lessons | `/learning-hub/` |
| `knowledge-base/` | Guides and articles | `/knowledge-base/` |
| `analytics/` | Tracking and analytics | `/analytics/` |
| `Reddott-films/` | Video/content workflows | `/Reddott-films/` |
| `video-automation/` | Remotion automation | `/video-automation/` |
| `monitor/` | Site health automation | `/monitor/` |
| `keddy-bot/` | Bot application | `/keddy-bot/` |

## Visual map

Use `/site-wire-map.php` to inspect the current end-to-end request flow.

## Migration strategy

Reorganisation is done in small, reviewable commits. Production entry points are not deleted until their replacement has been wired and verified. This keeps deployment URLs stable while the codebase moves toward predictable module boundaries.

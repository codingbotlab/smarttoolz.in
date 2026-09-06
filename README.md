# SmartToolz.in 🛠️

> **Useful tools. Practical learning. One growing platform.**

SmartToolz is a PHP-based web platform for browser-friendly utilities, searchable knowledge, structured learning, analytics and developer-focused infrastructure.

## Platform

- Website: https://smarttoolz.in/
- Tools: https://smarttoolz.in/smart-toolz/
- Knowledge Base: https://smarttoolz.in/knowledge-base/
- Learning Hub: https://smarttoolz.in/learning-hub/
- AI Social World: https://smarttoolz.in/ai-social-media/
- Analytics: https://smarttoolz.in/analytics/
- Developer Hub: https://smarttoolz.in/developer/

## Active modules

- `smart-toolz/` — core online utilities, APIs, administration and SaaS.
- `knowledge-base/` — guides and reference content.
- `learning-hub/` — courses, lessons, practice and curriculum.
- `ai-social-media/` — experimental social/world experience.
- `analytics/` — event tracking and analytics.
- `keddy-bot/` — bot application.
- `developer/` — developer navigation and documentation.

## Architecture

```text
smarttoolz.in/
├── index.php
├── assets/                 # Global CSS, JS and images
├── smart-toolz/            # Core tools, APIs, admin and SaaS
├── knowledge-base/         # Guides and reference content
├── learning-hub/           # Courses and curriculum
├── ai-social-media/        # Social/world application
├── analytics/              # Analytics and tracking
├── keddy-bot/              # Bot application
├── auth/                   # Authentication integration
├── config/                 # Configuration helpers
├── core/                   # Shared backend foundation
├── scripts/                # Maintenance and migrations
├── developer/              # Developer entry point
├── docs/                   # Engineering documentation
└── .github/workflows/      # Active CI/CD automation
```

## Developer rules

1. Keep each active module self-contained.
2. Put global browser assets in `/assets/css/`, `/assets/js/` and `/assets/img/`.
3. Put module APIs in `<module>/api/`.
4. Put module administration in `<module>/admin/`.
5. Put shared module PHP in `<module>/core/` or `<module>/includes/`.
6. Put repository-wide maintenance in `/scripts/`.
7. Keep secrets out of Git and use protected server/environment configuration.
8. Preserve working public routes when reorganising active code.
9. Remove retired products from navigation, registries and automation.
10. Update `docs/REPOSITORY-STRUCTURE.md` when architecture changes.

## Local development

```bash
git clone https://github.com/codingbotlab/smarttoolz.in.git
cd smarttoolz.in
```

Run the project with a PHP-capable web server and configure the database/environment values required by the active modules.

## Security

Never commit API keys, OAuth tokens, database passwords or other private credentials. Validate input, use prepared database statements, protect authenticated routes and keep development utilities restricted.

## Legal

- Privacy Policy: https://smarttoolz.in/privacy-policy.php
- Terms: https://smarttoolz.in/terms.php

---

**SmartToolz.in — Build useful things. Automate the boring parts.**

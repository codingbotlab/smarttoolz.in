# SmartToolz.in 🛠️

> **Useful tools. Practical learning. Creator workflows. One growing platform.**

[SmartToolz.in](https://smarttoolz.in/) is a PHP-based web platform that brings together browser-friendly online utilities, learning experiences, knowledge content, creator workflows, analytics, and SaaS features under one SmartToolz ecosystem.

The project is designed around a simple idea: make common digital tasks faster, keep the experience responsive, and build reusable platform infrastructure instead of isolated one-off pages.

## 🌐 Platform

- **Website:** https://smarttoolz.in/
- **Tools:** https://smarttoolz.in/smart-toolz/
- **Knowledge Base:** https://smarttoolz.in/knowledge-base/
- **Learning Hub:** https://smarttoolz.in/learning-hub/

## ✨ What SmartToolz Includes

### 🧰 SmartToolz — Online Utilities

The main tools platform provides a growing collection of practical utilities across multiple categories, including:

- Image compression, resizing, cropping, rotating, flipping, background removal and format conversion
- JPG, PNG and WebP conversion
- GIF creation and conversion
- PDF conversion, merging, splitting and compression
- Text utilities such as word counting, case conversion, reversing text and whitespace cleanup
- Developer utilities including JSON formatting, Base64 encoding/decoding, URL encoding/decoding and HTML encoding/decoding
- Markdown to HTML conversion and text-to-slug generation
- QR generation and QR reading
- Password and random-number generation
- Calculators such as age, BMI and percentage calculators
- Unit and timestamp conversion
- Stopwatch/timer and UUID generation
- Lorem Ipsum and meme generation

The tool engine, router, registry, shared header/footer and sidebar are structured so new tools can be added without rebuilding the entire platform.

## 📚 Knowledge Base

SmartToolz includes a dedicated Knowledge Base for useful, searchable content around the tools and their use cases.

The Knowledge Base currently contains platform components for:

- Articles and guides
- Visual/help content
- Tool-specific documentation
- Tool-to-guide linking
- Search-friendly content structures
- Automated wiring of Knowledge Base links to tools

Relevant implementation lives under `knowledge-base/` and is connected with the tool platform through the SmartToolz tool layer.

## 🎓 Learning Hub

The Learning Hub extends SmartToolz from a tool directory into a learning platform.

It includes:

- Course catalog and course pages
- Lesson pages and lesson navigation
- Practice and project areas
- Search
- User enrollment and dashboard flows
- Progress tracking
- Bookmarks and notes
- Quizzes and quiz execution
- Certificates
- Premium lesson support
- Curriculum/content seeding and repair utilities
- Admin tools for curriculum management and lesson enrichment

The repository also contains a large computer-basics curriculum and additional programming-language learning structures, including C, C++, C#, CSS, Go, HTML, Java, JavaScript, Kotlin, PHP, Python, Rust, SQL, Swift and TypeScript.

## 🤖 Creator Platform

The `creator-ai/` area provides a separate creator-focused application layer with:

- User authentication and Google Login integration
- Sessions and conversation handling
- Chat APIs
- Live interaction/WebSocket support
- Model listing/integration endpoints
- Creator dashboard functionality
- A dedicated learning subsystem

The creator learning subsystem has reusable course, module, lesson, quiz, practice, notes, progress, bookmarks, project and certificate components backed by a learning schema.

## 🌍 AI Social World

The `ai-social-media/` area contains an experimental social/world layer for SmartToolz with components for:

- Public conversations and chat rooms
- World/pulse-style activity views
- Bot-driven interactions
- Public observer views
- Job/recruitment experiences
- Shared world state and supporting assets

This area is intentionally kept as a separate platform module so it can evolve independently from the core tools experience.

## 🎬 Reddott Films & Content Automation

`Reddott-films/` contains the platform's creator/content automation work, including:

- Blog/content management infrastructure
- Editorial and content-engine workflows
- Article/post rendering
- Transcript handling
- Sitemap generation
- Content synchronization
- YouTube management and OAuth-related integration
- Video upload tooling
- Knowledge-video production workflows
- Remotion-based video composition
- Narration/TTS processing
- Automated QR-generator knowledge-video production

GitHub Actions workflows are also present for parts of the Reddott content/video pipeline.

## 📊 Analytics & Operations

SmartToolz contains its own analytics and operational tooling rather than treating analytics as an afterthought.

The `analytics/` and `smart-toolz/admin/` areas provide infrastructure for:

- Event tracking
- User/tool activity tracking
- Live/realtime analytics views
- Advanced analytics dashboards
- Geographic resolution support
- User and event inspection
- Download tracking
- Tool/category management
- Database administration views
- Platform settings
- Operational dashboards

The repository also contains automation scripts and GitHub Actions workflows used to apply and maintain parts of the analytics and platform infrastructure.

## 💳 SaaS & Monetization Layer

The core SmartToolz application includes a SaaS layer for usage and account management.

Current platform code includes support for:

- User accounts
- Usage/credit tracking
- Trials
- Plan/upgrade flows
- Referral claiming
- Referral commissions
- Wallet/payout functionality
- Monthly credit renewal logic
- Usage and transaction history

The exact commercial configuration is kept in the application's database/configuration rather than hard-coded into this README.

## 🔐 Authentication & Privacy

Authentication and account-aware features are used where required by the platform.

SmartToolz also follows a browser-first approach where practical. Operations that can safely be performed in the browser can avoid unnecessary server-side file processing, while server/API processing is used where the feature requires it.

Security-sensitive configuration should be supplied through protected server configuration or environment/hosting secrets and **must not be committed to the repository**.

## 🏗️ Architecture

SmartToolz is primarily a PHP application backed by MySQL/MariaDB-style relational data storage.

The repository is organized into platform modules rather than a single monolithic page:

```text
smarttoolz.in/
├── .github/
│   └── workflows/             # CI/CD and maintenance automation
│
├── smart-toolz/               # Core online-tools platform
│   ├── index.php              # Tools application entry
│   ├── home.php               # Tools home experience
│   ├── tool.php               # Tool registry/routing UI
│   ├── tool-engine.php        # Shared tool execution layer
│   ├── tool-router.php        # Tool routing/dispatch
│   ├── tool-sidebar.php       # Shared tools navigation
│   ├── header.php             # Shared platform header
│   ├── footer.php             # Shared platform footer
│   ├── admin/                 # Administration and operations
│   ├── api/                   # Tool/platform APIs
│   ├── assets/                # Frontend assets
│   ├── lib/                   # Shared platform helpers
│   ├── saas/                  # Plans, credits, referrals and payouts
│   └── tools/                 # Individual online tools
│
├── knowledge-base/            # Articles, guides and visual knowledge
├── learning-hub/              # Courses, lessons, practice and curriculum
├── creator-ai/                # Creator application + learning subsystem
├── ai-social-media/           # Social/world experimentation
├── analytics/                 # Event, live and advanced analytics
├── Reddott-films/             # Content, video and YouTube automation
├── video-automation/          # Remotion-based video automation
├── scripts/                   # Maintenance/migration/patch scripts
│
├── index.php                  # Root entry point
├── privacy-policy.php         # Privacy policy
├── terms.php                  # Terms
└── README.md
```

## ⚙️ Core Technology

| Area | Technology / Approach |
|---|---|
| Application | PHP |
| Database | MySQL / MariaDB-compatible relational storage |
| Frontend | HTML, CSS and JavaScript with responsive UI |
| Authentication | Session-based account flows + Google Login where configured |
| APIs | PHP HTTP/JSON endpoints |
| Analytics | First-party event and activity tracking infrastructure |
| Automation | GitHub Actions + repository maintenance scripts |
| Video | Remotion-based workflows where applicable |
| Media processing | Browser-side processing and server-side processing where required |

## 🔄 Automation & GitHub Actions

The repository includes workflows for platform maintenance and deployment-oriented tasks, including:

- Knowledge Base/tool-link automation
- Dynamic category wiring
- Shared-header migrations
- Analytics updates and realtime features
- Admin dashboard maintenance
- Background/sidebar changes
- Reddott QR/video generation workflows

Maintenance scripts under `scripts/` provide repeatable migrations and patches instead of requiring manual edits across many PHP files.

## 🧩 Design Principles

SmartToolz is being developed around a few consistent principles:

1. **Reusable platform components** — shared infrastructure should be reused across tools and modules.
2. **Responsive by default** — pages should work across desktop, tablet and mobile layouts.
3. **Browser-first when practical** — avoid sending user files to a server when the operation can safely happen locally.
4. **Server-side enforcement** — authentication, metering and database-backed rules must not depend only on JavaScript.
5. **Modular growth** — tools, learning, knowledge, analytics and creator systems remain separable modules.
6. **Automation over repetition** — repository scripts and Actions should handle repeatable maintenance work.
7. **Useful content around tools** — tools should be supported by guides, lessons and contextual knowledge rather than existing as isolated utilities.

## 🚀 Local Development

SmartToolz is a PHP web application. A typical local setup requires:

1. PHP with the extensions required by the active modules.
2. MySQL or MariaDB for database-backed features.
3. A web server such as Apache or an equivalent PHP-capable server.
4. Google OAuth credentials if Google Login is enabled locally.
5. Any API/provider credentials required by the specific creator or automation module.

Clone the repository:

```bash
git clone https://github.com/codingbotlab/smarttoolz.in.git
cd smarttoolz.in
```

Configure the required database and server settings for the modules you want to run, then serve the project through your PHP web server.

> **Important:** Do not copy production credentials, OAuth secrets, database passwords or API keys into source files or commit them to Git.

## 🛡️ Production Checklist

Before deploying a production instance:

- Configure database credentials outside publicly accessible source files.
- Configure OAuth redirect URLs for the production domain.
- Keep secrets out of Git history and public repository files.
- Disable or protect development/admin utilities that should not be public.
- Review upload limits and PHP execution limits.
- Verify HTTPS and secure session/cookie settings.
- Verify database permissions and backups.
- Test authentication, metering, analytics and payout/referral flows.
- Test responsive layouts on mobile and desktop.
- Verify scheduled GitHub Actions and any self-hosted automation runners.

## 📁 Important Entry Points

| Area | Entry point |
|---|---|
| Core tools | `smart-toolz/index.php` |
| Tool registry | `smart-toolz/tool.php` |
| Tool engine | `smart-toolz/tool-engine.php` |
| Tool router | `smart-toolz/tool-router.php` |
| Account | `smart-toolz/account.php` |
| Admin | `smart-toolz/admin/` |
| SaaS | `smart-toolz/saas/` |
| Knowledge Base | `knowledge-base/index.php` |
| Learning Hub | `learning-hub/index.php` |
| Learning lessons | `learning-hub/lesson.php` |
| Creator platform | `creator-ai/index.php` |
| Social/world module | `ai-social-media/index.php` |
| Analytics | `analytics/index.php` |
| Reddott content | `Reddott-films/` |
| Video automation | `video-automation/` |

## 🗺️ Project Direction

SmartToolz is evolving from a collection of online utilities into a broader digital platform:

**Tools → Knowledge → Learning → Creator workflows → Community/World experiences → Analytics → SaaS infrastructure**

The long-term direction is to keep the individual experiences useful on their own while sharing a common platform foundation for accounts, content, analytics, automation and monetization.

## 🤝 Contributing

The repository is actively evolving. When modifying the project:

- Keep changes scoped to the relevant module.
- Reuse existing shared components before introducing duplicates.
- Preserve responsive behavior.
- Avoid committing credentials or private configuration.
- Prefer prepared statements and validated inputs for database operations.
- Keep user-facing copy and URLs consistent with SmartToolz branding.
- Update relevant documentation when introducing a new platform module or workflow.

## 📄 Legal & Policies

- [Privacy Policy](https://smarttoolz.in/privacy-policy.php)
- [Terms](https://smarttoolz.in/terms.php)

---

### ❤️ SmartToolz.in

**Build useful things. Automate the boring parts. Keep improving.**

This repository contains the evolving implementation of the SmartToolz platform and its supporting experiments, learning systems, content workflows and automation infrastructure.

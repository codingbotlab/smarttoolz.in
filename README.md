# SmartToolz

**Free, fast and simple online tools.**

SmartToolz is a collection of focused browser-based utilities for images, PDFs, text, developer tasks, calculators, generators and everyday work.

## Repository structure

```text
/
├── index.php                 # Public homepage entry point
├── home.php                  # Homepage UI/content
├── tool.php                  # Tool registry + All Tools page
├── header.php                # Shared navigation + global styles
├── footer.php                # Shared footer/navigation
├── bootstrap.php             # Lightweight application bootstrap
├── about.php                 # About page
├── contact.php               # Contact/help page
├── privacy-policy.php        # Privacy policy
├── cookie-policy.php         # Cookie policy
├── terms.php                 # Terms of use
├── disclaimer.php            # General disclaimer
├── 404.php                   # Friendly not-found page
├── robots.txt                # Crawler rules
├── sitemap.xml               # Search-engine sitemap
│
├── assets/
│   ├── css/                  # Site styles
│   ├── js/                   # Site JavaScript
│   ├── icons/                # Local icon library
│   ├── home/                 # Homepage assets
│   └── tools/                # Tool-specific assets
│
├── lib/                      # Small shared PHP helpers
└── tools/
    └── <tool-name>/          # One self-contained folder per public tool
        ├── <tool-name>.php   # Main tool implementation
        └── assets/            # Optional tool-specific files
```

## Tool organization

Every public tool belongs in its own `tools/<tool-name>/` directory. Keep the main PHP file named exactly like its folder so clean URLs map predictably:

`/tools/image-compressor/` → `tools/image-compressor/image-compressor.php`

Tool-specific JavaScript, CSS, images and other assets should stay inside that tool's folder unless they are genuinely shared by multiple tools.

## Architecture principles

- Database-free core architecture.
- No user accounts or admin panel.
- No IP-based usage/download tracking in the core site.
- Local assets where practical; avoid unnecessary third-party CDNs.
- Clean, descriptive tool URLs.
- Shared site code stays small and understandable.
- User-facing tools should remain focused on one clear task.

## SEO and publishing

- `robots.txt` points crawlers to `sitemap.xml`.
- The sitemap lists the public homepage, informational pages and tool URLs.
- Private application directories are not linked as public content.
- Public pages should have unique titles, descriptions and useful explanatory content.
- Keep navigation clear and make every public tool reachable from the All Tools page.

## AdSense readiness

SmartToolz is structured to support a clear, user-first publishing experience: useful tool pages, straightforward navigation, accessible informational pages and transparent privacy/terms documentation. AdSense approval is determined by Google's review and policies; this repository does not claim or guarantee approval.

## Local development

The project is plain PHP/HTML/CSS/JavaScript and does not require a database for the core site. A PHP 8.x web server with Apache rewrite support is recommended for matching production URL behavior.

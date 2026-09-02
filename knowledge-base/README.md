# SmartToolz Knowledge Base

The Knowledge Base provides a responsive, searchable guide for the SmartToolz tool collection.

## Guide system

Tool guides are generated dynamically from the central SmartToolz registry in `smart-toolz/tool.php`.

```text
knowledge-base/
├── index.php        # Guide directory and category browsing
├── guide.php        # Rich dynamic how-to guide renderer
├── article.php      # Legacy article renderer kept for compatibility
└── .htaccess        # /<tool-slug>/article/ routing
```

A guide URL follows this pattern:

```text
https://smarttoolz.in/knowledge-base/<tool-slug>/article/
```

The dynamic guide includes:

- tool-specific title, description, icon, category, and CTA
- category-aware instructions and input guidance
- step-by-step workflow
- practical tips
- common mistakes to avoid
- frequently asked questions
- related tools from the same category
- responsive mobile navigation and category search
- canonical URL and HowTo/Breadcrumb structured data
- Font Awesome 6 icons with a safe fallback

The registry remains the source of truth, so adding a tool to `smart-toolz/tool.php` automatically makes it available to the Knowledge Base renderer.

## Media

Generated tutorial media can still be organized per tool when needed:

```text
knowledge-base/<tool-slug>/
├── images/
└── videos/
    ├── long/
    └── shorts/
```

Generation history belongs under `_generated/<tool-slug>/<date>/` so previous assets are not overwritten.

The Knowledge Base content layer must not modify the underlying SmartToolz tool implementation.

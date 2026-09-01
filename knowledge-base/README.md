# SmartToolz Knowledge Base

Generated how-to guides for SmartToolz tools.

Each tool keeps its published article and generated media together:

```text
knowledge-base/<tool-slug>/
├── article/
├── images/
└── videos/
    ├── long/
    └── shorts/
```

Generation history is kept separately under `_generated/<tool-slug>/<date>/` so previous assets are not overwritten.

The generator must use the real SmartToolz tool URL and workflow as the source of truth. Existing SmartToolz tool code is not modified by the content generator.

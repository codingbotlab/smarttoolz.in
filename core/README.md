# SmartToolz WebApp Core

This directory contains shared, framework-free infrastructure for all SmartToolz webapps.

## Layout
- `auth/` — canonical login entrypoints. Authentication remains the Creator AI auth implementation; these endpoints provide one stable URL for every app.
- `ui/` — shared mobile-app shell assets: fixed top bar, bottom navigation, safe-area handling and responsive utilities.
- `config/` — non-secret configuration conventions and environment-variable documentation.

## Rule
Existing app/business logic stays in its current module until its imports/routes are migrated and verified. This keeps the cleanup non-destructive.

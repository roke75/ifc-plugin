---
name: ifc-plugin-change
description: Use for Instant Feedback Collector WordPress/PHP plugin changes in ifc.php, includes classes, admin handlers, shortcodes, AJAX handlers, activation/deactivation hooks, or shared plugin behavior. Keeps work aligned with local WordPress APIs, security checks, and PHP lint gates.
---

# IFC Plugin Change Skill

## Start

- Inspect the touched PHP files before relying on docs.
- Read `.agents/knowledge/app-architecture.md` and `.agents/validators/php-plugin-change.md`.
- Read narrower knowledge/validator docs when the change touches admin UI, shortcodes/AJAX, persistence, assets, i18n, or security.

## Required Checks

- Preserve WordPress hook wiring in `ifc.php` and class `run()` methods.
- Keep admin mutations capability-gated and nonce-protected.
- Keep AJAX requests nonce-protected.
- Sanitize inputs and escape outputs for the correct context.
- Use `$wpdb->prepare()` or structured `$wpdb` calls for user-controlled values.
- Keep user-facing strings translation-ready with text domain `ifc-plugin`.

## Verification

- Run `php -l` on changed PHP files when feasible.
- Report WordPress runtime checks that were not run.

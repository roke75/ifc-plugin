---
name: ifc-database-persistence
description: Use for Instant Feedback Collector database schema, WordPress $wpdb table access, activation/dbDelta behavior, question and answer persistence, deletes, migrations, and data compatibility changes.
---

# IFC Database Persistence Skill

## Start

- Read `.agents/knowledge/persistence.md` and `.agents/validators/database-persistence-change.md`.
- Inspect `includes/class-ifc-activator.php` and every touched `$wpdb` access site.

## Rules

- Preserve `ifc_questions` and `ifc_answers` table names unless migration/reset behavior is explicit.
- Account for existing installations when schema changes.
- Use `dbDelta()`-compatible SQL in activation/upgrade paths.
- Use `$wpdb->prepare()` for user-controlled SQL values.
- Keep destructive actions protected by capability and nonce checks.

## Verification

- Run `php -l` on changed PHP files when feasible.
- Report absence of WordPress integration tests when schema behavior cannot be executed locally.

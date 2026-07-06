# Persistence Agent

Owns plugin database schema, data access, and compatibility.

## Source Anchors

- `includes/class-ifc-activator.php`
- `includes/class-ifc-admin.php`
- `includes/class-ifc-shortcodes.php`
- `includes/class-ifc-ajax.php`
- `includes/admin-page.php`

## Responsibilities

- Track `ifc_questions` and `ifc_answers` table usage.
- Preserve existing data unless the task explicitly defines deletion or migration behavior.
- Validate schema changes against `dbDelta()` expectations.
- Verify inserts, updates, deletes, and selects use safe WordPress database APIs.
- Check admin deletes are nonce-protected and capability-gated.

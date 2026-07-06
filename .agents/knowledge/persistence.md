# Database Persistence

Use this for schema changes, data writes, deletes, migrations, and table access.

## Anchors

- `includes/class-ifc-activator.php`
- `includes/class-ifc-admin.php`
- `includes/class-ifc-shortcodes.php`
- `includes/class-ifc-ajax.php`
- `includes/admin-page.php`

## Tables

- `$wpdb->prefix . 'ifc_questions'`: `id`, `question`, `created_at`.
- `$wpdb->prefix . 'ifc_answers'`: `id`, `question_id`, `answer`, `time`.

## Rules

- Preserve existing table names unless an explicit migration is requested.
- Schema changes must account for already-installed plugins, not just fresh activation.
- Use `dbDelta()` constraints and syntax carefully when changing schemas.
- Use structured `$wpdb->insert()`, `$wpdb->update()`, `$wpdb->delete()` with formats where possible.
- Use `$wpdb->prepare()` for SQL containing user-controlled values.
- Keep destructive admin actions nonce-protected and capability-gated.
- Do not drop user data on deactivation unless explicitly requested.

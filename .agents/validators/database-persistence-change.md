# Database Persistence Change Validator

Use for table schema, activation, data inserts, updates, deletes, query behavior, or migration compatibility changes.

## Inspect

- `includes/class-ifc-activator.php`
- All touched `$wpdb` access sites.
- Existing table names and README shortcode behavior.

## Validate

- Table names remain compatible or migration/reset behavior is explicit.
- `dbDelta()` SQL remains valid for WordPress.
- Existing installations have a clear upgrade path for schema changes.
- Destructive behavior is intentional, protected, and documented.
- User-controlled values are prepared or passed through structured `$wpdb` APIs.

## Verification

- Run `php -l` on changed PHP files when feasible.
- Report absence of WordPress integration tests when applicable.

# Admin UI Change Validator

Use for admin page markup, admin menu, admin-post handlers, notices, and question/answer management flows.

## Inspect

- `includes/class-ifc-admin.php`
- `includes/admin-page.php`
- Related database table access.

## Validate

- Admin mutations require `manage_options` or an explicitly justified capability.
- Admin forms include nonce fields and handlers verify them.
- Redirects use WordPress URL helpers.
- Dynamic values are escaped.
- Destructive actions remain explicit and protected.

## Verification

- Run `php -l` on changed PHP files when feasible.
- Report whether admin runtime testing was available.

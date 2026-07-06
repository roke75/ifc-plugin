# PHP Plugin Change Validator

Use for PHP changes in plugin bootstrap, classes, admin handlers, shortcodes, AJAX handlers, or activation/deactivation hooks.

## Inspect

- Affected PHP files.
- Related hook registrations in `ifc.php` or class `run()` methods.
- Related knowledge files under `.agents/knowledge/`.

## Validate

- WordPress hooks remain registered intentionally.
- Inputs are sanitized.
- Outputs are escaped.
- User-controlled SQL uses `$wpdb->prepare()` or structured `$wpdb` methods with formats.
- Admin mutations check capability and nonce.
- AJAX mutations or reads verify nonce.
- User-facing strings use text domain `ifc-plugin`.

## Verification

- Run `php -l` on changed PHP files when feasible.
- Report any WordPress runtime check that was not run.

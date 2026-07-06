# App Implementation Agent

Owns PHP implementation and WordPress hook wiring.

## Source Anchors

- `ifc.php`
- `includes/class-ifc-admin.php`
- `includes/class-ifc-public.php`
- `includes/class-ifc-shortcodes.php`
- `includes/class-ifc-ajax.php`
- `includes/class-ifc-activator.php`
- `includes/class-ifc-deactivator.php`

## Responsibilities

- Match local PHP and WordPress API style.
- Keep bootstrap wiring explicit and readable.
- Preserve existing shortcode, AJAX, admin-post, activation, and deactivation hooks unless intentionally changed.
- Coordinate with Persistence for schema/table changes.
- Coordinate with Privacy/Safety for form, AJAX, SQL, and output handling.
- Run PHP lint on changed PHP files when feasible.

# Shortcodes And AJAX Change Validator

Use for `[ifc]`, `[ifc_results]`, AJAX actions, localized script data, polling, and word-cloud behavior.

## Inspect

- `includes/class-ifc-shortcodes.php`
- `includes/class-ifc-ajax.php`
- `includes/class-ifc-public.php`
- `js/ifc.js`
- `css/ifc.css` when layout changes.

## Validate

- Existing shortcode contracts remain compatible unless explicitly changed.
- AJAX action names and response shapes remain compatible with JS or are updated together.
- AJAX nonce verification remains in place.
- Question ids are cast and invalid ids handled.
- Output HTML is escaped.
- Submitted answers are sanitized.

## Verification

- Run `php -l` on changed PHP files when feasible.
- Inspect JS behavior and report whether browser/WordPress runtime testing was run.

# Shortcodes And AJAX Agent

Owns public shortcode contracts, answer submission, polling, and word-cloud data behavior.

## Source Anchors

- `includes/class-ifc-shortcodes.php`
- `includes/class-ifc-ajax.php`
- `includes/class-ifc-public.php`
- `js/ifc.js`
- `css/ifc.css`

## Responsibilities

- Preserve `[ifc]` and `[ifc_results]` contracts unless explicitly changed.
- Verify shortcode attributes are sanitized and invalid ids fail clearly.
- Verify AJAX requests use localized URL and nonce.
- Keep response formats compatible with `js/ifc.js` unless updating both sides together.
- Ensure dynamic HTML is escaped before output.

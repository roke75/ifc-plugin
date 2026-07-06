# UI And Accessibility Agent

Owns WordPress admin UI, shortcode markup, frontend styling, and accessibility.

## Source Anchors

- `includes/admin-page.php`
- `includes/class-ifc-shortcodes.php`
- `includes/class-ifc-public.php`
- `css/ifc.css`
- `js/ifc.js`

## Responsibilities

- Preserve WordPress admin form/table conventions.
- Keep labels, required fields, notices, and buttons understandable.
- Check shortcode output on narrow content widths when layout changes.
- Keep dynamic text escaped and translation-ready.
- Avoid broad global CSS that can damage host themes.

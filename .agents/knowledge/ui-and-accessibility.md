# UI And Accessibility

Use this for admin markup, shortcode output, public CSS, and frontend interaction behavior.

## Anchors

- `includes/admin-page.php`
- `includes/class-ifc-shortcodes.php`
- `includes/class-ifc-public.php`
- `js/ifc.js`
- `css/ifc.css`

## Guidance

- Preserve WordPress admin conventions for forms, notices, tables, capabilities, nonces, and redirects.
- Keep labels associated with inputs where forms are rendered.
- Escape translated text and dynamic values in HTML attributes and bodies.
- Keep shortcode output responsive enough for common theme content widths.
- Do not assume Bootstrap is always conflict-free in a WordPress theme; minimize global styling changes.
- Avoid inline styles in new code unless matching existing localized patterns is the narrowest change.
- Keep visible text translation-ready with text domain `ifc-plugin`.

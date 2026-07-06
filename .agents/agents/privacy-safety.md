# Privacy And Safety Agent

Owns WordPress security, privacy, and external-service risk review.

## Source Anchors

- `ifc.php`
- `includes/`
- `js/ifc.js`
- `README.md`

## Responsibilities

- Verify nonces for forms and AJAX.
- Verify `manage_options` or suitable capabilities for admin mutations.
- Verify sanitization on input and escaping on output.
- Verify prepared SQL for user-controlled values.
- Check that no secrets, credentials, database dumps, or personal environment files are added.
- Flag new tracking, analytics, CDN, or external network behavior unless explicitly requested.

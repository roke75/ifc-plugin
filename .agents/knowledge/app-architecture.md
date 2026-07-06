# Plugin Architecture

This plugin uses a compact WordPress class-per-surface structure.

## Anchors

- `ifc.php`
- `includes/class-ifc-admin.php`
- `includes/admin-page.php`
- `includes/class-ifc-public.php`
- `includes/class-ifc-shortcodes.php`
- `includes/class-ifc-ajax.php`
- `includes/class-ifc-activator.php`

## Current Architecture

- `ifc.php` defines constants, requires class files, registers activation/deactivation hooks, and creates class instances.
- Admin behavior is split between registration/handlers in `IFC_Admin` and markup in `admin-page.php`.
- Public asset loading is centralized in `IFC_Public`.
- Shortcode rendering and front-end form submission live in `IFC_Shortcodes`.
- Polling endpoints live in `IFC_AJAX`.
- Database creation lives in `IFC_Activator`.

## Guidance

- Keep WordPress hooks close to the class that owns the behavior.
- Keep shortcode output escaped and contract-compatible.
- Keep mutation handlers nonce-protected and capability-checked when admin-only.
- Prefer WordPress APIs for URLs, redirects, nonces, translation, escaping, and database access.
- Avoid broad rewrites unless the task requires changing ownership boundaries.

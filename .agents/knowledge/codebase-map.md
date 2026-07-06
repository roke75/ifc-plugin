# Codebase Map

Use this as the first map after inspecting the task-specific source files.

## Main Paths

- `ifc.php` declares plugin metadata, constants, required classes, activation/deactivation hooks, and runtime initialization.
- `includes/class-ifc-activator.php` creates `ifc_questions` and `ifc_answers` tables through `dbDelta()`.
- `includes/class-ifc-deactivator.php` is the deactivation hook surface.
- `includes/class-ifc-admin.php` registers the admin menu and admin-post handlers for question and answer management.
- `includes/admin-page.php` renders the admin management screen.
- `includes/class-ifc-public.php` enqueues Bootstrap, jQCloud, plugin CSS/JS, and localizes AJAX data.
- `includes/class-ifc-shortcodes.php` renders `[ifc]` answer forms and `[ifc_results]` result views.
- `includes/class-ifc-ajax.php` serves polling updates for answers and word-cloud data.
- `js/ifc.js` polls admin-ajax.php and renders result cards or word clouds.
- `css/ifc.css` styles shortcode output.
- `languages/` contains translation sources and compiled catalogs.

## Runtime Flow

1. WordPress loads `ifc.php`.
2. Activation creates questions and answers tables.
3. Admin users manage questions through `admin.php?page=ifc-plugin` and admin-post actions.
4. Visitors submit answers through `[ifc id="X"]`.
5. Result pages render `[ifc_results id="X"]` and poll AJAX for new answers or word-cloud data.

## Change Boundaries

Prefer keeping changes local:

- Bootstrap and wiring: `ifc.php`.
- Admin behavior: `class-ifc-admin.php` and `admin-page.php`.
- Public rendering: `class-ifc-shortcodes.php`.
- AJAX responses: `class-ifc-ajax.php` and `js/ifc.js`.
- Schema and persistence: `class-ifc-activator.php` plus all table access sites.
- Styling: `css/ifc.css`.
- Translations: `languages/`.

Do not add new frameworks, build systems, service dependencies, analytics, or tracking unless the task explicitly requires them.

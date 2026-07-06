# Patterns

- 2026-07-06: This repository is a WordPress plugin. Runtime wiring starts in `ifc.php`, which loads IFC classes, registers activation/deactivation hooks, and initializes admin, public, AJAX, and shortcode surfaces. Source: `ifc.php`.
- 2026-07-06: Questions and answers are stored in `$wpdb->prefix . 'ifc_questions'` and `$wpdb->prefix . 'ifc_answers'`. Source: `includes/class-ifc-activator.php`.
- 2026-07-06: Public live results use shortcodes plus AJAX polling through `ifc_ajax_obj`. Source: `includes/class-ifc-shortcodes.php`, `includes/class-ifc-ajax.php`, `js/ifc.js`.

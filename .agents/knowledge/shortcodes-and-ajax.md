# Shortcodes And AJAX

Use this for shortcode rendering, form submission, live answer updates, and word-cloud behavior.

## Anchors

- `includes/class-ifc-shortcodes.php`
- `includes/class-ifc-ajax.php`
- `includes/class-ifc-public.php`
- `js/ifc.js`
- `css/ifc.css`

## Contracts

- `[ifc id="X"]` renders an answer form for a question.
- `[ifc_results id="X"]` renders live answer cards.
- `[ifc_results id="X" view="word_cloud"]` renders a word-cloud container.
- AJAX action `ifc_update_answers` returns answer HTML and the latest answer id.
- AJAX action `ifc_update_word_cloud` returns weighted word-cloud data.
- JavaScript uses `ifc_ajax_obj.ajax_url` and `ifc_ajax_obj.nonce` from `wp_localize_script()`.

## Security And Data Handling

- Verify AJAX requests with `check_ajax_referer( 'ifc_ajax_nonce', 'nonce' )`.
- Cast shortcode ids and AJAX ids with `intval()` and reject non-positive ids.
- Escape shortcode/admin output with `esc_html()`, `esc_attr()`, or URL helpers as appropriate.
- Sanitize submitted answers with WordPress sanitizers before insert.
- Use `$wpdb->prepare()` for user-controlled SQL values.

## UX Notes

- Result polling currently runs every 5 seconds.
- Word-cloud rendering depends on jQCloud and fixed dimensions in CSS/JS.
- Avoid console noise in production-facing changes unless debugging is explicitly requested.

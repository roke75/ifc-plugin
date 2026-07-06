# Frontend Assets Change Validator

Use for `js/ifc.js`, `css/ifc.css`, public enqueue behavior, answer card layout, and word-cloud rendering.

## Inspect

- `includes/class-ifc-public.php`
- `js/ifc.js`
- `css/ifc.css`
- Shortcode markup in `includes/class-ifc-shortcodes.php`.

## Validate

- AJAX URL and nonce use `ifc_ajax_obj`.
- Selectors match rendered shortcode markup.
- Polling behavior remains intentional.
- CSS changes avoid broad host-theme damage.
- Layout remains usable in typical WordPress content widths.

## Verification

- Run available JS/CSS tooling only if present.
- Report whether browser/WordPress runtime testing was run.

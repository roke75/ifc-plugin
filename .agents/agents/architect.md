# Architect Agent

Owns design boundaries and cross-cutting impact for the WordPress plugin.

## Source Anchors

- `ifc.php`
- `includes/`
- `js/ifc.js`
- `css/ifc.css`
- `languages/`

## Responsibilities

- Keep bootstrap, admin, public, shortcode, AJAX, and persistence responsibilities separated.
- Prefer existing WordPress class and hook patterns over new architecture.
- Identify impact across database schema, security, translations, assets, and shortcode contracts.
- Reject unrelated platform assumptions such as Swift, Xcode, iOS, or Sudoku logic.

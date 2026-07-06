---
name: ifc-shortcodes-ajax
description: Use for Instant Feedback Collector shortcode rendering, [ifc] answer forms, [ifc_results] live results, word cloud mode, AJAX polling actions, localized script data, and frontend response compatibility.
---

# IFC Shortcodes And AJAX Skill

## Start

- Read `.agents/knowledge/shortcodes-and-ajax.md` and `.agents/validators/shortcodes-ajax-change.md`.
- Inspect `includes/class-ifc-shortcodes.php`, `includes/class-ifc-ajax.php`, `includes/class-ifc-public.php`, and `js/ifc.js` when behavior crosses PHP/JS boundaries.

## Contracts

- Preserve `[ifc id="X"]`.
- Preserve `[ifc_results id="X"]`.
- Preserve `[ifc_results id="X" view="word_cloud"]`.
- Preserve AJAX action names and response shapes unless PHP and JS are updated together.

## Security

- Validate ids with `intval()` and reject non-positive ids.
- Verify AJAX nonce with `check_ajax_referer()`.
- Sanitize submitted answers.
- Escape rendered question and answer content.

## Verification

- Run `php -l` on changed PHP files when feasible.
- Inspect JS behavior and report whether a WordPress/browser runtime was available.

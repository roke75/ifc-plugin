---
name: ifc-frontend-accessibility
description: Use for Instant Feedback Collector admin UI, shortcode markup, public CSS, JavaScript interactions, answer cards, word cloud layout, responsive behavior, labels, notices, and accessibility changes.
---

# IFC Frontend Accessibility Skill

## Start

- Read `.agents/knowledge/ui-and-accessibility.md` and `.agents/validators/frontend-assets-change.md`.
- Inspect markup and assets together: admin page or shortcode PHP plus `css/ifc.css` and `js/ifc.js` when relevant.

## Rules

- Preserve WordPress admin UI conventions.
- Keep labels associated with form controls.
- Keep shortcode output usable in typical theme content widths.
- Avoid broad global CSS that can damage host themes.
- Escape dynamic content and keep visible text translation-ready.

## Verification

- Run PHP lint for changed PHP markup files.
- Report whether browser or WordPress runtime inspection was available.

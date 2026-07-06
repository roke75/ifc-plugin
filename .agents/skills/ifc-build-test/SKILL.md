---
name: ifc-build-test
description: Use to choose and run Instant Feedback Collector WordPress plugin validation gates, including PHP lint, WordPress security inspection, JavaScript/CSS behavior checks, translation checks, and GitHub verification workflow context.
---

# IFC Build And Test Skill

## Start

- Read `.agents/knowledge/testing.md` and `.agents/config/quality-gates.yaml`.
- Inspect changed files before choosing gates.

## Baseline Gates

- PHP changes: `php -l <changed-php-file>`.
- Security-sensitive changes: inspect nonce, capability, sanitization, escaping, and prepared SQL around touched flows.
- JS/CSS changes: inspect selectors, localized AJAX object usage, polling behavior, and shortcode markup compatibility.
- i18n changes: verify text domain `ifc-plugin` and language file impact.

## Reporting

- Use `passed`, `not_run`, or `not_applicable`.
- Give a concrete reason for each `not_run` gate.
- Do not claim PHPUnit, Composer, npm, or WordPress integration coverage unless that tooling is present and was run.

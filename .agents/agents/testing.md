# Testing Agent

Owns validation strategy and command execution for this WordPress plugin.

## Source Anchors

- `.agents/config/quality-gates.yaml`
- Changed PHP files
- Changed JS/CSS files
- Touched `includes/` flows

## Responsibilities

- Run `php -l` on changed PHP files when feasible.
- Inspect available project tooling before assuming PHPUnit, Composer, npm, or WordPress test harness exists.
- For JS/CSS changes, verify behavior by code inspection unless a local WordPress/browser environment is available.
- Report missing tooling as a gap, not as a pass.
- Summarize residual risk clearly.

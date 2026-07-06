# Testing

The current baseline is PHP lint plus targeted WordPress security and behavior inspection. There is no committed PHPUnit, WordPress test suite, npm, or composer setup in this repository at the time this framework was updated.

## Anchors

- `.agents/config/quality-gates.yaml`
- Changed PHP files
- Changed JS/CSS files
- Touched WordPress flows in `includes/`

## Baseline Commands

- PHP lint: `php -l <changed-php-file>`.
- Search for stale project assumptions: `rg -n "Sudoku|Swift|Xcode|iOS" .agents AGENTS.md CLAUDE.md` after framework edits.

## Review Checks

- Nonces and capabilities for admin mutations.
- AJAX nonce verification for public polling endpoints.
- Sanitization on input and escaping on output.
- Prepared SQL or structured `$wpdb` calls for user-controlled values.
- Shortcode output remains contract-compatible.
- Translation text domain remains `ifc-plugin`.

Report unavailable tooling plainly instead of inventing tests.

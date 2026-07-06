# Supervisor Agent

Owns coordination, scope control, and quality gates for the Instant Feedback Collector WordPress plugin.

## Responsibilities

- Classify the task before delegating or editing.
- Choose the relevant knowledge file and validator.
- Keep changes scoped to the WordPress plugin surface.
- Prevent stale Swift/iOS/Sudoku assumptions from entering work.
- Route fixes and new features through a GitHub pull request on a non-default branch unless the user explicitly asks for a direct push.
- During PR work, retry authenticated `gh` commands with sandbox escalation if sandboxed `gh auth status` reports an invalid token; the user's auth may be in the macOS keyring.
- Merge role outputs and resolve conflicts.
- Report verification status for each applicable gate.

## Change Categories

- PHP plugin logic
- Admin UI
- Shortcodes/AJAX
- Database persistence
- Frontend assets
- i18n
- Project/config/packaging
- Tests
- Security/privacy-sensitive
- Markdown/config

## Delegation Map

- App Implementation: PHP class wiring and WordPress hook behavior.
- Shortcodes/AJAX: shortcode contracts, polling endpoints, localized JS behavior.
- UI/Accessibility: admin page, shortcode markup, CSS, responsive behavior.
- Persistence: schema, `$wpdb` writes, deletes, and data compatibility.
- Project Config: plugin metadata, packaging, translations, repo config.
- Privacy/Safety: nonce/capability/sanitization/escaping/prepared SQL and external services.
- Testing: lint and targeted verification gates.
- Documentation: README, inline docs, and user-facing change notes.
- Code Review: regression, security, and maintainability findings.

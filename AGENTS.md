# Agent Guide for Instant Feedback Collector

This repository is a WordPress/PHP plugin named Instant Feedback Collector. Treat the plugin source, WordPress hook behavior, database schema, shortcode output, AJAX handlers, assets, translations, and local validation commands as authoritative. Prose guidance is useful only after checking the app path.

The source is hosted on GitHub at `git@github.com:roke75/ifc-plugin.git`. The GitHub CLI (`gh`) is installed for repository, authentication, push, pull request, and CI workflows; verify auth with `gh auth status` before using GitHub operations.

## Start Here

Read only the context needed for the task:

- Plugin bootstrap: `ifc.php`.
- Activation/deactivation: `includes/class-ifc-activator.php`, `includes/class-ifc-deactivator.php`.
- Admin UI and handlers: `includes/class-ifc-admin.php`, `includes/admin-page.php`.
- Public assets and shortcode rendering: `includes/class-ifc-public.php`, `includes/class-ifc-shortcodes.php`.
- AJAX handlers: `includes/class-ifc-ajax.php`.
- Frontend assets: `js/ifc.js`, `css/ifc.css`.
- Translations: `languages/`.
- Framework knowledge: `.agents/knowledge/`.
- Validators and gates: `.agents/validators/`, `.agents/config/quality-gates.yaml`.

## Non-Negotiable Invariants

- This is a WordPress plugin, not an iOS app. Do not introduce Swift, Xcode, simulator, Sudoku, backend-service, or unrelated app assumptions.
- Preserve WordPress hook wiring in `ifc.php`: admin, public, AJAX, and shortcode classes are initialized there.
- Database table names use `$wpdb->prefix . 'ifc_questions'` and `$wpdb->prefix . 'ifc_answers'`; schema changes must consider activation, upgrades, and existing installations.
- All SQL with user-controlled values must use `$wpdb->prepare()` or structured `$wpdb` methods with formats.
- All form, admin-post, AJAX, and shortcode flows must keep nonce checks, capability checks where required, sanitization on input, and escaping on output.
- Public shortcode behavior must preserve `[ifc id="X"]` and `[ifc_results id="X"]`, including `view="word_cloud"` unless the task explicitly changes shortcode contracts.
- JavaScript must use the localized `ifc_ajax_obj.ajax_url` and `ifc_ajax_obj.nonce` for plugin AJAX calls.
- Text shown to users must stay translation-ready with text domain `ifc-plugin`.
- Do not commit generated build folders, dependency vendors, local WordPress installs, credentials, database dumps, or personal environment files.
- Keep `.agents/skills/` aligned with this repository when the agent framework changes.

## Communication Style

Use a direct, firm engineering tone globally.

- Be concise and specific.
- Do not over-sympathize, flatter, or mirror the user's emotions.
- Do not use soft filler such as "great call", "totally", or "happy to".
- State risks, trade-offs, and blockers plainly.
- Push back when a request conflicts with plugin correctness, WordPress security, data integrity, accessibility, privacy, or maintainability.
- Prefer "I found X; I changed Y; Z was not run" over conversational reassurance.
- Apologize only for concrete mistakes, then state the correction.

## Working Flow

1. Classify the change: PHP plugin logic, admin UI, shortcode output, AJAX/frontend behavior, database persistence, assets, i18n, testing, markdown/config, or security/privacy-sensitive.
2. Read the matching `.agents/knowledge/` file and validator.
3. Inspect source files before relying on any prose docs.
4. Make focused edits that match local WordPress and PHP style.
5. Run applicable quality gates or report why they were not run.
6. Update `.agents/memory/` only for durable, code-verified decisions or lessons.
7. For GitHub publishing, inspect `git status --short --branch`, stage only intended files, commit with a terse message, and push with `git push -u origin <branch>`. Use `gh` for auth checks, remote context, PR discovery/creation, and CI status when needed.

## Subagents

Do not spawn independent subagents by default. If the user explicitly asks to "use subagents", "spawn agents", "run this with agents", or equivalent wording, use available multi-agent tooling where the environment supports it.

When using subagents:

- The main session acts as Supervisor and owns the final answer.
- Delegate only bounded work packages tied to `.agents/agents/` roles, such as App Implementation, Shortcodes/AJAX, UI/Accessibility, Database Persistence, Project Config, Privacy/Safety, Testing, Documentation, or Code Review.
- Give each subagent source-file anchors and the relevant `.agents/knowledge/` and `.agents/validators/` files.
- Require each subagent to report findings, files inspected, recommended changes, and verification status.
- The Supervisor merges results, resolves conflicts, performs final edits or review, and reports gates.

If no subagent tooling is available, say so and simulate the role split within the main session.

## Verification Baseline

- PHP code: run `php -l` on changed PHP files when feasible.
- WordPress security changes: inspect nonce, capability, sanitization, escaping, and `$wpdb->prepare()` usage around touched flows.
- JavaScript/CSS changes: inspect browser-facing behavior and AJAX payloads; run available linters only if project tooling exists.
- Database/schema changes: inspect activation and upgrade path; use WordPress/dbDelta constraints where relevant.
- Translation changes: inspect text domain `ifc-plugin` and update source strings consistently; regenerate compiled `.mo` only when tooling is available and needed.
- Markdown/config only: inspect links, file paths, and syntax.
- GitHub push/PR work: confirm `gh --version`, `gh auth status`, current branch, remote, and staged file scope before pushing.

Final responses should include what changed and verification status.

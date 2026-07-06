# Claude Guide for Instant Feedback Collector

Use the shared repo framework in `AGENTS.md` and `.agents/`.

Core rules:

- Source code, WordPress hook wiring, database schema, assets, translations, and local validation commands are authoritative.
- This is a WordPress/PHP plugin, not a Swift/iOS app. Do not introduce Swift, Xcode, simulator, Sudoku, or unrelated backend-service assumptions.
- Preserve shortcode contracts: `[ifc id="X"]`, `[ifc_results id="X"]`, and `[ifc_results id="X" view="word_cloud"]` unless the task explicitly changes them.
- Keep admin and AJAX flows protected with capabilities, nonces, sanitization, escaping, and prepared SQL.
- Keep user-facing strings translation-ready with text domain `ifc-plugin`.
- Do not add analytics, tracking, external services, package managers, or build systems unless the user explicitly asks or the code already depends on them.
- Do not use independent subagents by default. If the user explicitly asks to use subagents and the environment supports them, delegate bounded work using `.agents/agents/`; otherwise simulate the role split in the main session.
- Use a direct, firm engineering tone globally. Be concise and specific; do not over-sympathize, flatter, mirror emotions, or use soft filler. State risks and blockers plainly, and push back on unsafe or unmaintainable requests.

Useful entry points:

- `.agents/config/framework.yaml`
- `.agents/config/quality-gates.yaml`
- `.agents/knowledge/codebase-map.md`
- `.agents/knowledge/app-architecture.md`
- `.agents/knowledge/shortcodes-and-ajax.md`
- `.agents/knowledge/persistence.md`
- `.agents/knowledge/ui-and-accessibility.md`
- `.agents/knowledge/build-and-project.md`
- `.agents/validators/`
- `.agents/skills/ifc-plugin-change/SKILL.md`
- `.agents/skills/ifc-database-persistence/SKILL.md`

When changing code, report applicable gates as passed, not run, or not applicable.

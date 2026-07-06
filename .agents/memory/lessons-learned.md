# Lessons Learned

- 2026-07-06: Copied agent frameworks must be reconciled against actual source paths before use; this workspace is Instant Feedback Collector, a WordPress/PHP plugin. Source: user correction and source inspection.
- 2026-07-06: Quality gates should use `php -l` and targeted WordPress security inspection as the baseline because no PHPUnit, Composer, npm, or WordPress test harness is committed. Source: repository structure.
- 2026-07-06: If `gh auth status` reports an invalid token inside Codex but works in the user's terminal, retry the `gh` command with sandbox escalation because GitHub CLI auth is stored in the macOS keyring and sandboxed commands may not read that keyring entry. Use `sandbox_permissions: "require_escalated"` for authenticated `gh` commands such as `gh auth status`, `gh pr view`, `gh pr create`, and `gh pr merge`. Source: user instruction and escalated `gh auth status` verification.

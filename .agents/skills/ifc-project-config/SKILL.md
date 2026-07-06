---
name: ifc-project-config
description: Use for Instant Feedback Collector plugin metadata, README, .gitignore, language files, packaging, repository configuration, GitHub workflow context, dependency posture, and release-sensitive configuration changes.
---

# IFC Project Config Skill

## Start

- Read `.agents/knowledge/build-and-project.md` and relevant quality gates.
- Inspect `ifc.php`, `README.md`, `.gitignore`, and `languages/` when touched.

## Rules

- Keep plugin header metadata accurate.
- Keep text domain `ifc-plugin` consistent.
- Do not add dependency managers, vendors, local WordPress installs, archives, dumps, or generated files unless explicitly requested.
- Package only files needed by WordPress.
- For GitHub work, verify `gh --version`, `gh auth status`, branch, remote, and staged scope first.
- For fixes and new features, create or use a non-default branch, commit the scoped change, push it, and open a GitHub pull request. Direct pushes to `main` require an explicit user request or a narrow repository-maintenance task where the user asks for that flow.

## Verification

- Validate changed YAML/Markdown paths and syntax by inspection.
- Run PHP lint if plugin metadata or PHP files changed.

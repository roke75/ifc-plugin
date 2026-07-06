# Project Config Agent

Owns plugin metadata, packaging, translations, dependency posture, and repository configuration.

## Source Anchors

- `ifc.php`
- `README.md`
- `.gitignore`
- `languages/`
- `.agents/config/quality-gates.yaml`

## Responsibilities

- Keep plugin header fields accurate.
- Keep text domain and language file assumptions aligned.
- Avoid committing generated archives, local WordPress installs, credentials, dumps, or vendors.
- Verify packaging instructions include only WordPress plugin source.
- Confirm GitHub remote and auth before push/PR work.
- Use GitHub pull requests for fixes and new features: work on a non-default branch, push it, and open a PR unless the user explicitly requests a direct push.

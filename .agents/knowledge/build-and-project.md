# Build And Project

Use this for plugin metadata, repository structure, packaging, dependencies, translation files, and GitHub workflows.

## Anchors

- `ifc.php`
- `README.md`
- `languages/`
- `.gitignore`
- `.agents/config/quality-gates.yaml`

## Project Facts

- Product: Instant Feedback Collector.
- Platform: WordPress plugin.
- Main plugin file: `ifc.php`.
- Text domain: `ifc-plugin`.
- Remote: `git@github.com:roke75/ifc-plugin.git`.
- Runtime assets: `css/ifc.css`, `js/ifc.js`.
- External frontend libraries are enqueued from CDNs in `IFC_Public`.

## Guidance

- Keep plugin header metadata accurate when release information changes.
- Do not add dependency managers or generated vendor folders unless explicitly requested.
- Package only plugin source files needed by WordPress; exclude `.git`, local installs, dumps, generated archives, and personal config.
- If translation source strings change, inspect `.po`/`.pot` impact and compile `.mo` only when tooling is available and required.
- For GitHub work, verify `gh auth status`, current branch, remote, and staged file scope before pushing.
- For fixes and new features, use a GitHub pull request: create or use a non-default branch, commit the scoped change, push it, and open a PR. Direct pushes to `main` are only for explicit user requests or non-feature/non-fix repository maintenance where the user asks for that flow.

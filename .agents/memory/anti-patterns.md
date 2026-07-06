# Anti-Patterns

- 2026-07-06: Do not apply copied Sudoku, SwiftUI, Xcode, or iOS assumptions to this repository. Source: user correction and source inspection.
- 2026-07-06: Do not change schema or destructive admin actions without checking activation, existing data, nonce/capability protection, and `$wpdb` usage. Source: `includes/class-ifc-activator.php`, `includes/class-ifc-admin.php`.
- 2026-07-06: Do not add external services, tracking, dependency managers, or build systems unless explicitly requested or already present. Source: repository structure.

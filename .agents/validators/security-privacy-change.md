# Security And Privacy Change Validator

Use for authentication, authorization, nonces, submitted data, stored answers, external services, assets loaded from CDNs, or anything security/privacy-sensitive.

## Inspect

- Touched PHP, JS, README, and config files.
- Existing nonce/capability/sanitization/escaping patterns.
- External URLs and network behavior.

## Validate

- No secrets, credentials, database dumps, or personal files are added.
- Admin mutations are capability-gated.
- Forms and AJAX endpoints verify nonces.
- Inputs are sanitized before use or storage.
- Outputs are escaped for the correct context.
- SQL is prepared for user-controlled values.
- New external services, tracking, or analytics are explicitly requested and documented.

## Verification

- Run `php -l` on changed PHP files when feasible.
- Report any runtime security test that was not available.

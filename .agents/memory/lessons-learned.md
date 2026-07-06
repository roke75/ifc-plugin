# Lessons Learned

- 2026-07-06: Copied agent frameworks must be reconciled against actual source paths before use; this workspace is Instant Feedback Collector, a WordPress/PHP plugin. Source: user correction and source inspection.
- 2026-07-06: Quality gates should use `php -l` and targeted WordPress security inspection as the baseline because no PHPUnit, Composer, npm, or WordPress test harness is committed. Source: repository structure.

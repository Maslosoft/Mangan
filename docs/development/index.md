# Development and testing

Work on PHP 8.5 or newer. Install Composer and the extensions declared in
`composer.json`. Mangan requires EmbeDi 3, but released 3.0.0 lacks the companion
fix for magic-accessed configuration properties. Use the local override below
until that fix is released. A clean install of the currently locked remote
release does not include the fix used by the development checks.

## Local EmbeDi fix

With the fixed EmbeDi source checkout beside Mangan at `../embedi`, run:

```bash
php -d register_argc_argv=1 tools/use-local-embedi.php
COMPOSER=composer.local.json composer update maslosoft/embedi --with-all-dependencies
COMPOSER=composer.local.json composer check-platform-reqs
composer lint
composer test:compatibility
composer test:runtime
composer test:build
```

Pass another checkout path as the script's first argument when needed. The helper
creates gitignored `composer.local.json` and `composer.local.lock`, seeds the local
lock from the normal lock, and configures a symlink to the source checkout. It
does not apply the EmbeDi fix; the selected checkout must already contain it.
Keep subsequent local dependency install/update operations on the local manifest.
The normal manifest and lock remain suitable for tracking published dependencies.
Regenerating the helper's files replaces the previous local manifest and lock.

Once a release includes the fix, update the normal lock and use `composer install`
without the override. Review both manifest and lock changes for deliberate
updates. See [dependency migration notes](../upgrading/php-85/index.md).

## MongoDB integration suite

The Codeception suite contains database integration tests despite its `unit`
name. It creates and removes data in dedicated test databases. Run it against a
disposable local MongoDB replica set, never an application database.

The default URI is `mongodb://127.0.0.1:27017/?replicaSet=rs0`. `MONGODB_URI` can
point the suite at another disposable replica set:

```bash
MONGODB_URI='mongodb://127.0.0.1:27017/?replicaSet=rs0' composer test
```

The CI workflow in `.github/workflows/ci.yml` shows how to start and initialize
its MongoDB container. A standalone MongoDB server is insufficient for the
transaction tests. `tests/_bootstrap.php` defines test database names, while
`tests/extensions/MongoWipe.php` performs cleanup.

`composer check` runs lint, standalone compatibility and runtime regressions,
and the Codeception integration suite. `test:runtime` needs no database server;
its coroutine checks run with a loaded Swoole/OpenSwoole extension and otherwise
report a skip. CI loads Swoole and checks both the locked dependency set and
the lowest versions allowed by the manifest.

For a focused check after setup:

```bash
composer test -- Criteria
```

Run the relevant focused tests while iterating and the full suite before
finishing changes that affect shared persistence or transformation behavior.
Report skipped tests and external-service limitations separately from passing
checks. Without a loaded Swoole/OpenSwoole extension, synchronous context tests
do not establish behavior under real concurrent coroutines.

## Source map

| Path | Responsibility |
| --- | --- |
| `src/Mangan.php`, `src/ConfigManager.php` | Configuration and connection selection |
| `src/Meta`, `src/Annotations` | Model metadata and mapping annotations |
| `src/Transformers`, `src/Decorators`, `src/Sanitizers` | Model/BSON conversion |
| `src/Finder.php`, `src/Adapters/Finder` | Queries and cursor adaptation |
| `src/EntityManager.php`, `src/Transaction.php` | Persistence and sessions |
| `src/Events`, `src/Signals` | Lifecycle hooks |
| `tests/models`, `tests/unit` | Fixtures and regression coverage |

## Runtime state

Use `maslosoft/light` for state that belongs to an application execution context.
Inspect the installed dependency's API before changing scope logic. Keep
immutable class facts separate from configuration, model instances, sessions,
and handlers. Reusing a Light object does not coordinate work across processes.
Regression tests should cover reuse within a context, isolation after cleanup,
connection selection, and real coroutines when available.

Do not regenerate `src/Traits/AvailableCommands.php` during ordinary tests. It is
server-version-dependent generated API surface; regenerate only when intentionally
updating commands, and review the resulting diff.

## Documentation and skills

Documentation is plain Markdown under `docs`; rendering and navigation belong
to the host UI. Do not add PHP templates or links labelled “Documentation home”.
Use links to specific topic files and executable examples with explicit imports.

The repository owns `.agents/skills/mangan/SKILL.md` for application integration and
`.agents/skills/mangan-dev/SKILL.md` for library maintenance. Keep both aligned with API,
dependency, lifecycle, and workflow changes. Codex discovers these project skills from `.agents/skills`. The existing local
`~/.codex/skills` symlinks also point here for use in other projects; resolve a
symlink before following relative documentation links. `AGENTS.md` records repository
working conventions.

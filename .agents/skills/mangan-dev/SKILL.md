---
name: mangan-dev
description: Maintain the maslosoft/mangan library, including ODM internals, dependencies, PHP compatibility, tests, documentation, and scoped runtime state. For application integration, use mangan.
---

# Developing Mangan

Read the repository's `AGENTS.md`, `composer.json`, and affected tests before
changing behavior. This branch supports PHP 8.5 or newer. Resolve this skill's
real path when installed through a symlink; the repository is three levels above.

## Work with the model pipeline

Trace mapping changes through `Annotations`/`Meta`, `Decorators`, `Sanitizers`,
and transformer filters. Model defaults and DocBlock annotations affect hydration,
validation, and persistence independently of native PHP types. Put annotations
on separate DocBlock lines: some inline forms fail in the current Addendum parser. Check both read
and write directions, null/empty values, embedded/reference arrays, and field
filters when the change touches them.

`Finder` and its Mongo adapter perform queries; `EntityManager` writes documents;
`Transaction` supplies the active session for the selected connection. Preserve
the distinction between `sessionOptions` and `transactionOptions`. Verify connection selection from
`Mangan::fromModel()` and named connections when touching those paths. Avoid
changing public signatures without documenting the effect on custom interfaces,
traits, and subclasses.

## Scope mutable state deliberately

Use installed Light and EmbeDi source to verify APIs. Mangan owns context-scoped
connection instances, handles, events, transformer factories, transaction state,
and index-manager instances. Immutable built-in configuration/version data and
class reflection facts may remain process-wide. Do not migrate every static
mechanically or assume a Light factory performs shared work exactly once.

Check reuse within a context and isolation across contexts. `Mangan::reset()` is
narrower than `Context::clear()` and rejects an active transaction. Plain Fibers
have no automatic isolation. Actual Swoole/OpenSwoole checks are necessary to
claim coroutine coverage. Shared Addendum metadata and Signals configuration
remain upstream limitations; avoid documenting stronger guarantees.

Index markers represent shared database/schema work. Keep cache identities tied
to connection/database/model/languages/index definitions and write success markers only after
successful operations. `IndexManager::fly()->reset()` resets its in-memory cache;
it does not remove deployed indexes or filesystem markers.

## Validate and maintain supporting material

Use `composer lint`, `composer test:compatibility`, `composer test:runtime`,
`composer test:build`, and focused Codeception tests. The standalone runtime
checks exercise native coroutines when Swoole/OpenSwoole is loaded and report
a skip otherwise. `composer check` runs all checks and the integration suite. The
suite named `unit` includes destructive fixture cleanup and needs a disposable
MongoDB replica set; inspect the bootstrap and MongoWipe extension before changing
its connection. `MONGODB_URI` selects the server. Run `composer test` for shared
persistence changes and distinguish passing, skipped, and unavailable checks.
See [Development](../../../docs/development/index.md) for setup and the source map.

Do not fix dependencies by editing `vendor`, bypass platform checks, or regenerate
`AvailableCommands.php` as an incidental test step. Review Composer constraints
and the lock file together. EmbeDi 3 uses the stable `^3.0` constraint, but released
3.0.0 lacks the companion magic-property configuration fix. For now use the fixed
source checkout through `tools/use-local-embedi.php` and its emitted Composer
command. Local manifests/locks are gitignored; do not claim a clean remote install
contains that fix until a follow-up release is published and locked. ImageThumb uses Imagine, not PHPThumb inheritance.

Keep both `.agents/skills/mangan/SKILL.md` and this skill synchronized with changes to
APIs, dependency requirements, runtime lifetimes, or development commands.
Update specific `docs` topics and migration guidance in the same change.
Documentation is standalone Markdown for an external UI; no PHP rendering
wrappers or documentation-home links. Validate example signatures against source.
Read [Application servers](../../../docs/application-servers/index.md) for lifecycle
contracts and [PHP 8.5 migration](../../../docs/upgrading/php-85/index.md) for breaks.

# Working on Mangan

Mangan is a MongoDB ODM for PHP 8.5 or newer. Its production namespace is
`Maslosoft\Mangan`; Composer maps it to `src/`. Preserve public behavior unless a
change is intentional and documented in the migration guide.

## Skills and documentation

- Use `.agents/skills/mangan-dev/SKILL.md` for changes to this library.
- Use `.agents/skills/mangan/SKILL.md` for application integration guidance.
- Update these skills alongside changes to APIs, supported dependencies,
  application lifetimes, or development workflows. Update the relevant pages
  under `docs` in the same change; keep examples aligned with the implementation.
- Documentation is plain Markdown. The host UI provides layout and navigation;
  do not add executable PHP templates or documentation-home/back links. Link to
  specific topic files when a cross-reference helps.

## Runtime and model conventions

Use `maslosoft/light` for application-context-dependent registries and factories.
Keep immutable class facts separate from mutable configuration, connections,
model references, handlers, and sessions. Follow the installed Light/EmbeDi APIs;
plain Fibers and sequential worker jobs do not gain automatic coroutine isolation.
Do not promise context isolation for upstream global metadata or signal state.

Mangan uses Addendum DocBlock annotations. Model defaults, sanitizers, decorators,
and transformer filters work together; a PHP property type does not replace the
mapping metadata. Preserve annotation comments and test round trips when changing
hydration or persistence.

Do not edit `vendor` as the project fix. Change the dependency constraint or its
upstream source through an explicitly scoped change. Keep Composer's PHP and
extension checks enabled. Review dependency and lock-file changes together.

## Checks

Use `composer lint`, `composer test:compatibility`, `composer test:runtime`,
`composer test:build`, and relevant Codeception tests while iterating.
`composer check` runs the complete set. Run `composer test` for changes affecting shared persistence behavior.
The suite named `unit` includes MongoDB integration tests and requires a disposable
replica set (`rs0` by default); `MONGODB_URI` selects the test server. It deletes
fixture data. Read `tests/_bootstrap.php` and `tests/extensions/MongoWipe.php`
before changing test connection settings.

Add regression coverage for changed behavior, especially BSON conversion,
validation, connection selection, and context isolation. Report unavailable
services, skipped tests, and coroutine-runtime coverage explicitly.

`src/Traits/AvailableCommands.php` is generated from server command metadata.
Do not regenerate it as a test side effect or overwrite unrelated working-tree
changes. Regenerate only for an intentional command API update and inspect its
diff. Preserve the existing source's formatting in focused edits.

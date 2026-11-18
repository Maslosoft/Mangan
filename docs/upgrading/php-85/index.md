# Migrating to PHP 8.5

This branch requires PHP **8.5 or newer**. Upgrade CLI, web, worker, and CI
runtimes together, including the extensions required by Composer.

## Dependencies

Mangan now requires MongoDB's PHP extension and library from the 2.x series,
Maslosoft Light 2.x, and the modern EmbeDi implementation. Use Composer's actual
platform checks; do not bypass PHP or extension requirements.

```bash
composer update --with-all-dependencies
composer check-platform-reqs
composer audit
```

Mangan now requires stable EmbeDi 3 (`^3.0`). The released 3.0.0 still needs the
companion source fix for configuration snapshots of declared properties exposed
through magic accessors, as used by Addendum/Gazebo. That follow-up fix is local
and has not been published; installing 3.0.0 alone does not supply it.
Use the [local development override](../../development/index.md#local-embedi-fix)
until a release containing the fix is available, then update and commit the
normal dependency lock file. Do not lower application dependency stability.

## Image helper

`ImageThumb` now uses `imagine/imagine` instead of the abandoned PHPThumb package.
It no longer extends `PHPThumb\GD`. Applications relying on inherited PHPThumb
methods must migrate. The maintained surface is construction from a file and
optional `resizeUp`/`jpegQuality` options, `resize()`, `adaptiveResize()`, `save()`,
`getCurrentDimensions()`, and `getImageAsString()`. Test application image paths
and output formats after upgrading.

## PHP signatures and model properties

Nullable typed parameters now declare nullability explicitly, for example
`?CriteriaInterface $criteria = null`. Overrides and custom implementations
must remain compatible with their interfaces.

Declare model properties instead of relying on deprecated dynamic properties.
Initialize typed properties that will be read by metadata and transformation
code. Nullable PHP types and `@Nullable` have different jobs: a PHP type permits
`null`, while `@Nullable` converts PHP-empty values, including zero and `false`,
to null during transformations.

Use `MongoDB\BSON\ObjectId` and `MongoDB\BSON\UTCDateTime` in new code. Do not
restore the removed legacy `mongo` extension or an adapter for it.

## Query and transformation behavior

`JsonString::toModel()` throws `JsonException` for malformed JSON. Handle that at
the application input boundary instead of expecting an empty model.
`YamlString`/`YamlArray` apply modern Symfony YAML flags, including invalid-type
handling. Date conversion preserves canonical Extended JSON milliseconds and
accepts `DateTimeInterface`; retain regression coverage for legacy date payloads.

Finder counts use `countDocuments()` and count the full matching set independently
of a criteria limit or offset. `findAndModify` now uses MongoDB's before/after
return-document options. Review callers that relied on the old accidental behavior.

## Transaction configuration

Move `causalConsistency` from `transactionOptions` to `sessionOptions`.
Session options go to `Client::startSession()`; transaction options go to
`Session::startTransaction()`. The default transaction options are now an empty
array. Active-session lookup is connection-aware, so an unrelated connection
cannot accidentally receive another connection's session.

The internal `IndexManager::$haveIndex` and `$haveDir` static hooks are removed;
use `IndexManager::fly()->reset()` for in-memory test cleanup. Index definitions
participate in the cache fingerprint, and creation errors propagate instead of
being recorded as successful.

## Application lifetime

Mangan's connection instances and mutable registries now live in the current
Light context. Configure EmbeDi and attach event handlers inside that context.
A handler registered in one coroutine is not automatically registered in another.

Sequential workers must end the Light context between jobs. Do not retain
models, finders, entity managers, or transactions across that boundary. See
[application servers](../../application-servers/index.md) for a complete pattern
and the limits of coroutine and Fiber support.

## Validate the upgrade

Run the application's tests on PHP 8.5 with `E_ALL`, including model hydration,
validation, BSON date/ID conversion, GridFS if used, and transactions against a
replica set. Include two sequential requests with different configuration and
handlers when the application uses a persistent worker.

For library development, use the [development checks](../../development/index.md).

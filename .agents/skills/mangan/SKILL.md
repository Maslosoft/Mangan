---
name: mangan
description: Use Maslosoft Mangan in PHP applications, including model mapping, queries, persistence, validation, and application-server lifetimes. For changes to the Mangan library itself, use mangan-dev.
---

# Using Mangan

Inspect the application's installed Mangan version and configuration first.
This checkout targets PHP 8.5+, MongoDB PHP driver/library 2.x, and Light 2.x.
Resolve this skill's real path if installed through a symlink before following
relative guides. The installed source is authoritative for older applications.

## Integrate models and persistence

- Configure `Mangan::fly()` (default ID `mongodb`) before model metadata or database
  access. Use `dbName` and `connectionString`, or an EmbeDi adapter registered
  before lookup. `@ConnectionId` selects a named connection; resolve it with
  `Mangan::fromModel($model)` when following model configuration.
- Choose `Document` for active-document convenience methods, `EmbeddedDocument`
  for owned embedded values, or `AnnotatedInterface` for a plain model using
  `Finder`, `EntityManager`, and `Validator` services.
- Declare and initialize persistent properties. Mapping uses Addendum DocBlock
  annotations, not PHP attributes. Put annotations on their own DocBlock lines;
  the current Addendum parser rejects some inline annotation forms. Native property types do not replace BSON,
  embedding, reference, or validation annotations.
- Use `MongoDB\BSON\ObjectId` and `UTCDateTime`. Configure a sanitizer for nullable
  BSON properties. `@Nullable` uses PHP-empty semantics and can turn zero and
  false into null; it is not just a PHP nullable type.
- Use safe assignment for external input and validate before writing. `save()`
  may return false for validation failure; database exceptions are separate.
  Apply application authorization independently of safe-field filtering.
- Iterate `findAll()` cursors. Keep a transaction and all participating models on
  one connection and in one context; explicitly commit or roll back. Session
  options (such as `causalConsistency`) belong in `sessionOptions`; transaction
  options belong in `transactionOptions` or the Transaction constructor.

Read [Getting started](../../../docs/getting-started/index.md),
[Configuration](../../../docs/configuration/index.md), and
[Queries and persistence](../../../docs/queries/index.md) for examples. For mapping
changes, consult [Annotations](../../../docs/annotations/index.md),
[Validation](../../../docs/validators/index.md), or
[Sanitizers](../../../docs/sanitizers/index.md) as needed.

## Respect application lifetimes

Mangan instances, clients, handlers, factories, and transaction registries are
scoped through Light. Configure each coroutine context independently. Children
do not inherit the parent's registry; passing an object shares that reference.
Plain Fibers are not automatically isolated. Sequential workers must explicitly
end their scope and release references at job boundaries.

`Mangan::reset()` clears only Mangan-owned state and rejects active transactions.
It does not reset EmbeDi configuration. `Context::clear()` ends the whole current
Light scope, including other consumers, and does not roll back database work.
Use it only where that broader boundary is intended.

Upstream Addendum and Signals retain process-wide metadata/configuration. Keep
annotation namespaces/defaults and shared signal registration consistent within
a worker. Do not claim complete tenant isolation by changing those globals.
See [Application servers](../../../docs/application-servers/index.md) before adapting
a persistent worker, and validate the actual coroutine runtime when used.

For an upgrade, read [PHP 8.5 migration](../../../docs/upgrading/php-85/index.md),
including the EmbeDi 3 requirement, its not-yet-published companion source fix,
and the image helper change. Do not broadly lower application dependency stability.

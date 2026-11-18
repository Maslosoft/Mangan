# Application servers and workers

Mangan uses `maslosoft/light` for application-dependent state: connection
instances and handles, event registrations, transformation factories,
transaction registries, and index-manager instances. Immutable built-in
configuration, version information, class reflection facts, and the criteria/
modifier operator tables remain shared. Define grammar extensions before serving
requests; do not mutate those tables per request.

## Choose the boundary

| Runtime | Default Light behavior |
| --- | --- |
| Ordinary PHP request | Registry lasts for the PHP execution |
| Sequential worker jobs | Registry persists until explicitly reset |
| Active Swoole/OpenSwoole coroutine | Separate registry per coroutine |
| Plain PHP Fiber | No automatic isolation |

Connection IDs choose instances inside the current context. They do not create
a request boundary. Child coroutines have independent registries; they do not
automatically inherit the parent's configured Mangan instance or event handlers.
Explicitly passing an object keeps that same object shared.

Light scopes object lookup. It does not make blocking database calls asynchronous,
make a shared client safe for arbitrary concurrent use, or coordinate work across
worker processes. Validate your actual server and extension combination.

## Sequential worker example

Use `Context::clear()` at a boundary where the entire application's Light scope
should end. Configure the new context before creating models or accessing Mangan.

```php
use Maslosoft\Light\Context;
use Maslosoft\Mangan\Mangan;

function handleJob(array $job): void
{
    Context::clear();
    try {
        $mangan = Mangan::fly();
        $mangan->connectionString = 'mongodb://127.0.0.1:27017';
        $mangan->dbName = $job['database'];
        $mangan->init();

        // Register this job's handlers, then create and use models here.
        // Commit or roll back all transactions before returning.
    } finally {
        Context::clear();
    }
}
```

The example assumes `database` comes from trusted worker configuration. When
using EmbeDi, register the context's adapters before the first `Mangan::fly()`.
Release application references to models, finders, entity managers, and clients
when the job ends. Clearing a registry does not destroy references held elsewhere.

## Reset only Mangan

`Mangan::reset()` removes Mangan-owned state from the current context while
leaving other Light consumers alone. It rejects reset while a Mangan transaction
is active; finish the transaction first. Existing objects held by application
code remain alive, so stop using them before resolving replacements.

This narrower reset does not reset EmbeDi adapters. Use `EmbeDi::reset()` when
changing that configuration, or `Context::clear()` when ending the entire scope.
Neither operation rolls back database work for you.

## Coroutine applications

Bootstrap Mangan and its handlers inside each supported coroutine context.
Resolve models and services in that same context. Do not capture a configured
Mangan instance, finder, or transaction from a parent coroutine and assume it
will be copied. Plain Fibers and sequential workers require an explicit lifecycle;
Light's synchronous runner fallback does not create isolation.

## Shared dependencies and schema state

Mangan's scoped registries do not make all upstream packages context-local.
Addendum metadata/configuration and Signals registrations currently include
process-wide state. Keep annotation namespaces, annotation defaults, and shared
signal configuration identical for the worker's lifetime. Do not implement tenant
isolation by changing those settings between requests. Separate processes are
appropriate when tenants need different metadata definitions.

Index creation has shared effects on MongoDB and filesystem markers. Its cache
identity includes connection, database, model, language configuration, and index
definitions. Changed definitions therefore select a new marker; existing MongoDB
indexes still need deliberate schema migration when their names or options
conflict. Failed creation is not marked successful and remains retryable.
`IndexManager::fly()->reset()` clears its in-memory results only. Remove obsolete
filesystem markers as part of maintenance; a Light reset does not remove them or
modify deployed indexes.

## Verify isolation

Exercise two contexts with the same connection ID but different databases and
handlers. Ensure the second cannot see the first's registrations or clients.
Exercise transaction cleanup after exceptions. Also test actual concurrent
coroutines if the deployment uses them; synchronous tests alone do not verify
Swoole/OpenSwoole behavior.

Run `composer test:runtime` for the standalone registry and coroutine regression
checks. It reports a skip when neither Swoole nor OpenSwoole is loaded. These
checks exercise native coroutine isolation when an extension is available, but
do not replace application tests against its database and server configuration.

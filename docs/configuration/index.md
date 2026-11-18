# Configuration and connections

Mangan's default connection ID is `mongodb`, with a local MongoDB URI.
Set `dbName` before using the connection. Use environment configuration for
credentials and deployment-specific database names.

## Direct configuration

```php
use Maslosoft\Mangan\Command;
use Maslosoft\Mangan\Mangan;

$mangan = Mangan::fly();
$mangan->connectionString = getenv('MONGODB_URI') ?: 'mongodb://127.0.0.1:27017';
$mangan->dbName = getenv('MONGODB_DATABASE') ?: 'garden';
$mangan->init();

$result = (new Command())->ping();
```

A successful ping verifies that a server answers. Test an operation on the
intended collection to verify the application's required permissions.

## EmbeDi configuration

Register an adapter before resolving the corresponding Mangan instance:

```php
use Maslosoft\EmbeDi\Adapters\ArrayAdapter;
use Maslosoft\EmbeDi\EmbeDi;
use Maslosoft\Mangan\Mangan;

EmbeDi::fly()->addAdapter(new ArrayAdapter([
    'mongodb' => [
        'class' => Mangan::class,
        'connectionString' => 'mongodb://127.0.0.1:27017',
        'dbName' => 'garden',
    ],
    'archive' => [
        'class' => Mangan::class,
        'connectionString' => 'mongodb://127.0.0.1:27017',
        'dbName' => 'garden_archive',
    ],
]));

$mangan = Mangan::fly();
$archive = Mangan::fly('archive');
```

A model selects a named connection through `@ConnectionId`:

```php
use Maslosoft\Mangan\Document;

/**

 * @ConnectionId('archive')

 */
class ArchivedPlant extends Document
{
    public string $name = '';
}
```

`Mangan::fromModel($model)` resolves that model's connection. Configure each
connection before its first use. Do not change credentials or database selection
on an instance that is already used by an active operation.

## Replica sets

A replica-set URI contains one credential section and a comma-separated seed
list, for example:

```text
mongodb://app:password@db1:27017,db2:27017/garden?replicaSet=rs0&authSource=admin
```

Percent-encode reserved characters in credentials. The URI's default database
and authentication source do not replace Mangan's `dbName` setting.

## Runtime configuration

Mangan instances, clients, and event handlers belong to their execution
context. Register adapters and resolve connections inside the active context
when using application servers. Keep upstream annotation and signal configuration
consistent for the worker lifetime. See [application servers](../application-servers/index.md)
for cleanup and coroutine boundaries, and [annotation defaults](annotations/index.md)
for metadata configuration.

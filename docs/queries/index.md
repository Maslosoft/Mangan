# Queries and persistence

`Finder` queries models; `EntityManager` writes them. `Document` exposes these
operations as convenience methods. The examples below assume a configured
connection and a `Plant` model with `name` and `height` properties.

## Build criteria

```php
use Maslosoft\Mangan\Criteria;
use Maslosoft\Mangan\Finder;

$criteria = new Criteria();
$criteria->addCond('height', '>=', 10);
$criteria->sort('name', Criteria::SortAsc);
$criteria->limit(20);

foreach ((new Finder(Plant::class))->findAll($criteria) as $plant) {
    echo $plant->name;
}
```

Criteria support equality assignment (`$criteria->name = 'Basil'`),
`addCond($field, $operator, $value)`, sorting, limits, and offsets. Use model field
names so decorators can handle aliases and internationalized fields. For
repeatable pagination, include a unique field in the sort order.

`findAll()` returns a cursor; iterate it to avoid hydrating all results at once.
The legacy `Mangan::$useCursor` property does not select an array result.
`findByPk()` returns a model or `null` when nothing matches.

## Accept external data

Use safe assignment for request data rather than assigning every key received
from a client. Mark editable fields with `@Safe` or appropriate validators and
keep server-controlled fields unsafe.

```php
use Maslosoft\Mangan\EntityManager;

$plant = new Plant();
$manager = new EntityManager($plant);
$manager->setAttributes(['name' => 'Mint', 'height' => 8]);

if (!$manager->save()) {
    // Report the model's validation errors to the caller.
}
```

Filtering input fields and validating values are separate operations. Safe
assignment does not grant permission to edit a record; perform application
access checks before writing.

## Transform models

```php
use Maslosoft\Mangan\Transformers\JsonString;
use Maslosoft\Mangan\Transformers\SafeArray;

$json = JsonString::fromModel($plant);
SafeArray::toModel(['name' => 'Mint'], $plant, $plant);
```

Choose a transformer for the boundary: `SafeArray` handles external assignment,
`JsonArray` and `JsonString` represent JSON output, and `DocumentArray` represents
persistence data. Do not substitute an unrestricted transformer for safe input
assignment.

## Transactions

Use `Transaction` only with a MongoDB deployment that supports transactions.
All participating models must use the same connection. Include the model types
used by the operation when starting the transaction, and finish it explicitly.

```php
use Maslosoft\Mangan\Transaction;

$transaction = new Transaction([$plant]);
try {
    if (!$plant->save()) {
        throw new RuntimeException('Plant validation failed');
    }
    $transaction->commit();
} catch (Throwable $error) {
    $transaction->rollback();
    throw $error;
}
```

Keep a transaction within one execution context. Releasing a Light context is
not a substitute for committing or rolling back database work.

### Session and transaction options

`Mangan::$sessionOptions` configures `Client::startSession()`; its default is
`['causalConsistency' => true]`. `Mangan::$transactionOptions` configures
`Session::startTransaction()` and defaults to an empty array. A nonempty second
argument to `new Transaction($models, $options)` overrides the transaction
options for that operation. Keep session-only options such as
`causalConsistency` out of transaction options.

Built-in database operations select the active session for their connection.
For custom database operations, `Transaction::getRunningSession($mangan)` returns
the most recently started active session for that connection, or `null`.
This experimental helper does not add transaction support to arbitrary driver
calls: pass the session through the driver's operation options when needed.

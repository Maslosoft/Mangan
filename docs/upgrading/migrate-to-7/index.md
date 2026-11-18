# Migrating older models to the MongoDB driver

Mangan 7 replaced the legacy `mongo` extension and its adapter with the modern
MongoDB extension and PHP library. For the current PHP runtime and dependency
changes, also follow [Migrating to PHP 8.5](../php-85/index.md).

## BSON values

Replace legacy `MongoId` and `MongoDate` class names with
`MongoDB\BSON\ObjectId` and `MongoDB\BSON\UTCDateTime`. Imports can retain old
local names while updating existing application code:

```php
use MongoDB\BSON\ObjectId as MongoId;
use MongoDB\BSON\UTCDateTime as MongoDate;
```

New code should prefer the modern names. `UTCDateTime` stores milliseconds;
retrieve a Unix timestamp through its DateTime representation:

```php
use MongoDB\BSON\UTCDateTime;

$date = new UTCDateTime(time() * 1000);
$timestamp = $date->toDateTime()->getTimestamp();
```

There is no legacy `sec` field. Replace `MongoId::isValid()` calls with
`Maslosoft\Mangan\Helpers\IdHelper::isId()` where Mangan ID detection is intended.

`ImageParams` now uses public typed properties. Its legacy fluent setter API
is no longer supported.

## Transactions

Transactions use MongoDB sessions and require a suitable replica set or sharded
deployment. Pass participating models to `Transaction`; all models must resolve
to the same connection. Review [queries and persistence](../../queries/index.md)
for the current transaction example. Finish transactions explicitly before
ending an application execution context.

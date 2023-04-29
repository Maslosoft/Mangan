<?php

use Maslosoft\Mangan\Helpers\IdHelper;
use Maslosoft\Mangan\Model\ImageParams;
use Maslosoft\Mangan\Transaction;
use Maslosoft\Zamm\Namer;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

$mongoId = new Namer(ObjectId::class);
$mongoDate = new Namer(UTCDateTime::class);
$imageParams = new Namer(ImageParams::class);
$idHelper = new Namer(IdHelper::class);
$transaction = new Namer(Transaction::class);
/* $var $mongoDate UTCDateTime */
?>

<title>1. Upgrade to version 7</title>

# Upgrade to version 7

The version 7 removes old mongo library, and it's adapter dependency, involving breaking changes.

## Deprecated types

The most important aspect is that the `MongoId` and `MongoDate` classes have new names now,
<?php echo $mongoId ?> and <?php echo $mongoDate ?> respectively. These classes cannot be aliased,
as PHP doesn't allow aliasing built-in classes. Therefore, these class names occurrences need to
be changed in code. To minimize required edits, it is recommended to import classes with alias:

```php
use MongoDB\BSON\ObjectId as MongoId;
use MongoDB\BSON\UTCDateTime as MongoDate;
```

The stored class names in database will be handled by Mangan automatically.

The <?= $imageParams; ?> class no longer have fluent setters. Additionally, properties are now
public and typed. Getters and setters still can be used.

The <?= $mongoDate; ?> no longer have `sec` attribute available, to obtain timestamp,
and it required milliseconds as constructor parameter, following construct must be used:

```php
$timestamp = time();
$date = new UTCDateTime($timestamp * 1000)
echo $date->toDateTime()->getTimestamp();
```

The function `MongoId::isValid()` is no longer available. Use <?= $idHelper::isId(); ?> instead.

## Transactions

The <?= $transaction; ?> second parameter is now [session options][session-options].

When using the <?= $transaction ?> with many models, these should be included as the
first parameter as array, as mongodb cannot create new collections while in transaction.

### Replica set

Transactions require [replica set to be setup][setup-rs], even for single server.

To quickly summarize, the `mongodb.conf` should contain `replication` configured:

```yaml
replication:
  oplogSizeMB: 2000
  replSetName: rs0
  enableMajorityReadConcern: false
```

Then replica set must be initialized with `mongo` shell, by calling command:

```javascript
rs.initiate()
```

NOTE: The command read *initiate* **not** *initialize* as one might confuse.

The Mangan must have property `replicaSet` set to same value as the configuration option
`replSetName`, in this example `rs0`.

[session-options]: https://www.php.net/manual/en/mongodb-driver-manager.startsession.php
[setup-rs]: https://stackoverflow.com/a/67341026/5444623

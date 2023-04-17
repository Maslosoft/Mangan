<?php

use Maslosoft\Mangan\Model\ImageParams;
use Maslosoft\Zamm\Namer;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

$mongoId = new Namer(ObjectId::class);
$mongoDate = new Namer(UTCDateTime::class);
$imageParams = new Namer(ImageParams::class);
?>

<title>1. Upgrade to version 7</title>

# Upgrade to version 7

The version 7 removes old mongo library, and it's adapter dependency, involving breaking changes.

The most important aspect is that the `MongoId` and `MongoDate` classes have new names now,
<?php echo $mongoId ?> and <?php echo $mongoDate ?> respectively. These classes cannot be aliased,
as PHP doesn't allow aliasing built-in classes. Therefore, these class names occurrences need to
be changed in code. To minimize required edits, it is recommended to import classes with alias:

```php
use MongoDB\BSON\ObjectId as MongoId;
```

The stored class names in database will be handled by Mangan automatically.

The <?= $imageParams; ?> class no longer have fluent setters. Additionally, properties are now
public and typed. Getters and setters still can be used.
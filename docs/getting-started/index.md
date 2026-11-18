# Getting started

Use PHP 8.5 or newer with Composer and the extensions declared in `composer.json`.
MongoDB must be reachable by the application. Transactions additionally require
a replica set or sharded deployment.

```bash
composer require maslosoft/mangan
composer check-platform-reqs
```

## Configure a connection

Configure Mangan before creating models or querying metadata. The default
connection ID is `mongodb`.

```php
require 'vendor/autoload.php';

use Maslosoft\Mangan\Mangan;

$mangan = Mangan::fly();
$mangan->connectionString = 'mongodb://127.0.0.1:27017';
$mangan->dbName = 'garden';
$mangan->init();
```

See [configuration](../configuration/index.md) for dependency injection and
multiple connections. Long-running workers should use the lifecycle described
in [application servers](../application-servers/index.md).

## Define a model

Declare persistent properties and their initial values. Mangan uses Addendum
DocBlock annotations; the following `@RequiredValidator` is not a PHP attribute.
Keep DocBlock comments available in your deployment's OPcache configuration.

```php
use Maslosoft\Mangan\Document;

class Plant extends Document
{
    /**
     * @RequiredValidator
     */
    public string $name = '';

    public int $height = 0;
}
```

`Document` supplies `_id`, the `id` alias, validation, language, scenario,
finder, and persistence methods. Do not redeclare inherited public properties
with incompatible PHP types. See [plain models](../define-model/plain/index.md)
when you want to avoid a base class.

## Save and read

```php
$plant = new Plant();
$plant->name = 'Basil';
$plant->height = 15;

if (!$plant->save()) {
    throw new RuntimeException(json_encode($plant->getErrors(), JSON_THROW_ON_ERROR));
}

$loaded = Plant::model()->findByPk($plant->_id);
if ($loaded !== null) {
    echo $loaded->name;
}
```

Check `save()`'s result: a validation failure returns `false`. Database errors
can throw exceptions and need separate application error handling. Use
[queries and persistence](../queries/index.md) for criteria, iteration, and
external input handling.

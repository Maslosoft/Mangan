# Active documents

Extend `Maslosoft\Mangan\Document` to put persistence and query methods on your
model. `EmbeddedDocument` provides transformation, validation, language, and
scenario support for objects saved inside another document.

```php
use Maslosoft\Mangan\Document;

class Plant extends Document
{
    /**
     * @RequiredValidator
     */
    public string $name = '';
}

$plant = new Plant();
$plant->name = 'Basil';
$saved = $plant->save();
$errors = $plant->getErrors();
$loaded = Plant::model()->findByPk($plant->_id);
```

Useful inherited methods include `save()`, `insert()`, `update()`, `delete()`,
`validate()`, `getErrors()`, `findByPk()`, `findAll()`, `count()`, and `exists()`.
Check return values and handle database exceptions separately from validation
failures. `model()` constructs a finder prototype; it is not a process singleton.

`Document` supplies `_id` as a BSON ObjectId and `id` as an alias. For custom
primary keys and models with a different base class, use the
[plain model](../plain/index.md) approach and
[primary-key mapping](../../annotations/primary-key/index.md).

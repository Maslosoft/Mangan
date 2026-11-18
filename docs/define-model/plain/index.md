# Plain PHP models

Implement `AnnotatedInterface` to use Mangan without extending a base class.
Initialize properties so metadata has meaningful defaults. Explicitly configure
BSON conversion for IDs and dates.

```php
use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Sanitizers\MongoObjectId;
use MongoDB\BSON\ObjectId;

class Plant implements AnnotatedInterface
{
    /**
     * @Sanitizer(MongoObjectId)
     */
    public ?ObjectId $_id = null;

    /**

     * @RequiredValidator

     */
    public string $name = '';
}
```

Use separate services for operations:

```php
use Maslosoft\Mangan\EntityManager;
use Maslosoft\Mangan\Finder;
use Maslosoft\Mangan\Validator;

$plant = new Plant();
$plant->name = 'Basil';
$validator = new Validator($plant);
if (!$validator->validate()) {
    throw new RuntimeException(json_encode($validator->getErrors(), JSON_THROW_ON_ERROR));
}

$manager = new EntityManager($plant);
$saved = $manager->save();
$loaded = (new Finder(Plant::class))->findByPk($plant->_id);
```

The same annotations support embedded documents, references, validators, and
sanitizers. Add [traits](../../traits/index.md) and their corresponding interfaces
only when the model needs those methods. Use [active documents](../active-document/index.md)
when inherited convenience methods are preferable.

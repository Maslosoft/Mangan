# ObjectId Sanitizers

ObjectId sanitizers will ensure that value is `MongoDB\BSON\ObjectId`. This sanitizers will not be
autodetected and need to be explicitly set.

There are two distinct, but similar sanitizer for MongoDB ObjectId:

* `MongoObjectId` - will ensure object ID as an object
* `MongoStringId` - will ensure object ID as a string

Both sanitizers have `MongoObjectId->nullable` option to allow `null` values,
defaults to false.
If not set, or set to `false`, ObjectId will be generated as needed.

Full example of using `MongoStringId` and `MongoObjectId`:

```php
use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Sanitizers\MongoObjectId;
use Maslosoft\Mangan\Sanitizers\MongoStringId;

class MyClass implements AnnotatedInterface
{
	/**
	* @Sanitizer(MongoStringId)
	*/
	public $myStringId = '';

	/**
	* @Sanitizer(MongoObjectId)
	*/
	public $myObjectId = null;
}
```

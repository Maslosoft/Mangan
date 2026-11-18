# Date Sanitizer

Date sanitizers will ensure that value is `MongoDB\BSON\UTCDateTime`. This sanitizer will not be
autodetected and need to be explicitly set.

Full example of using `DateSanitizer`:

```php
use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Sanitizers\DateSanitizer;

class MyClass implements AnnotatedInterface
{
	/**
	* @Sanitizer(DateSanitizer)
	*/
	public $date = null;
}
```

# Integer Sanitizer

Integer sanitizer will ensure that value is numeric and it is integer.

It is recommended to use either default value of some integer number to
auto set this sanitizer or [explicitly define it](../index.md) with [class literal](https://maslosoft.com/addendum/docs/data-types/class-literals/).

Full example of using `IntegerSanitizer`:

```php
use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Sanitizers\IntegerSanitizer;

class MyClass implements AnnotatedInterface
{
	public $autodetected = 0;

	/**
	* @Sanitizer(IntegerSanitizer)
	*/
	public $explicitlySet;
}
```

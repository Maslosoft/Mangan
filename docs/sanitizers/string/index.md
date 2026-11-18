# String Sanitizer

String sanitizer will ensure that value is string, it will cast any value to
string using `(string)` operator.

It is recommended to use either default value of some string (ie. `''`) to
auto set this sanitizer or [explicitly define it](../index.md) with [class literal](https://maslosoft.com/addendum/docs/data-types/class-literals/).

Full example of using `StringSanitizer`:

```php
use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Sanitizers\StringSanitizer;

class MyClass implements AnnotatedInterface
{
	public $autodetected = '';

	/**
	* @Sanitizer(StringSanitizer)
	*/
	public $explicitlySet;
}
```

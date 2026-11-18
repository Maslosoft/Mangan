# Double Sanitizer

Double sanitizer will ensure that value is numeric and it is floating point number.

It is recommended to use either default value of some floating point number (ie. `0.0`) to
auto set this sanitizer or [explicitly define it](../index.md) with [class literal](https://maslosoft.com/addendum/docs/data-types/class-literals/).

Full example of using `DoubleSanitizer`:

```php
use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Sanitizers\DoubleSanitizer;

class MyClass implements AnnotatedInterface
{
	public $autodetected = 0.0;

	/**
	* @Sanitizer(DoubleSanitizer)
	*/
	public $explicitlySet;
}
```

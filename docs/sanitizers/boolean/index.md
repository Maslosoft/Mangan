# Boolean Sanitizer

Boolean sanitizer will ensure that value is either `true` or `false`.

It is recommended to use either default value of `true` or `false` to
auto set this sanitizer or [explicitly define it](../index.md) with [class literal](https://maslosoft.com/addendum/docs/data-types/class-literals/).

Full example of using `BooleanSanitizer`:

```php
use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Sanitizers\BooleanSanitizer;

class MyClass implements AnnotatedInterface
{
	public $autodetected = true;

	/**
	* @Sanitizer(BooleanSanitizer)
	*/
	public $explicitlySet;
}
```

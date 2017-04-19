<?php

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Sanitizers\StringSanitizer;
use Maslosoft\Zamm\ShortNamer;
?>
<?php
/* @var $x AnnotatedInterface */
ShortNamer::defaults()->md();
$string = new ShortNamer(StringSanitizer::class);
?>
<title>4. String</title>

#String Sanitizer

String sanitizer will ensure that value is string, it will cast any value to
string using `(string)` operator.

It is recommended to use either default value of some string (ie. `''`) to
auto set this sanitizer or [explicitly define it](../) with [class literal](/addendum/docs/data-types/class-literals/).

Full example of using <?= $string; ?>:

<pre>
use Maslosoft\Addendum\Interfaces\AnnotatedInterface
use Maslosoft\Mangan\Sanitizers\StringSanitizer;

class MyClass implements AnnotatedInterface
{
	public $autodetected = '';

	/**
	* @Sanitizer(StringSanitizer)
	*/
	public $explicitlySet;
}
</pre>
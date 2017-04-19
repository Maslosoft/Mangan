<?php

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Sanitizers\DoubleSanitizer;
use Maslosoft\Zamm\ShortNamer;
?>
<?php
/* @var $x AnnotatedInterface */
ShortNamer::defaults()->md();
$double = new ShortNamer(DoubleSanitizer::class);
?>
<title>3. Double</title>

#Double Sanitizer

Double sanitizer will ensure that value is numeric and it is floating point number.

It is recommended to use either default value of some floating point number (ie. `0.0`) to
auto set this sanitizer or [explicitly define it](../) with [class literal](/addendum/docs/data-types/class-literals/).

Full example of using <?= $double; ?>:

<pre>
use Maslosoft\Addendum\Interfaces\AnnotatedInterface
use Maslosoft\Mangan\Sanitizers\DoubleSanitizer;

class MyClass implements AnnotatedInterface
{
	public $autodetected = 0.0;

	/**
	* @Sanitizer(DoubleSanitizer)
	*/
	public $explicitlySet;
}
</pre>
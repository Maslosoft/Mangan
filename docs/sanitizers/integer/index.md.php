<?php

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Sanitizers\IntegerSanitizer;
use Maslosoft\Zamm\ShortNamer;
?>
<?php
/* @var $x AnnotatedInterface */
ShortNamer::defaults()->md();
$int = new ShortNamer(IntegerSanitizer::class);
?>
<title>2. Integer</title>

#Integer Sanitizer

Integer sanitizer will ensure that value is numeric and it is integer.

It is recommended to use either default value of some integer number to
auto set this sanitizer or [explicitly define it](../) with [class literal](/addendum/docs/data-types/class-literals/).

Full example of using <?= $int; ?>:

<pre>
use Maslosoft\Addendum\Interfaces\AnnotatedInterface
use Maslosoft\Mangan\Sanitizers\IntegerSanitizer;

class MyClass implements AnnotatedInterface
{
	public $autodetected = 0;

	/**
	* @Sanitizer(IntegerSanitizer)
	*/
	public $explicitlySet;
}
</pre>
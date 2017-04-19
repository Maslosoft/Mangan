<?php

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Sanitizers\BooleanSanitizer;
use Maslosoft\Zamm\ShortNamer;
?>
<?php
/* @var $x AnnotatedInterface */
ShortNamer::defaults()->md();
$boolean = new ShortNamer(BooleanSanitizer::class);
?>
<title>Boolean sanitizer</title>

#Boolean Sanitizer

Boolean sanitizer will ensure that value is either `true` or `false`.

It is recommended to use either default value of `true` or `false` to
auto set this sanitizer or [explicitly define it](../) with [class literal](/addendum/docs/data-types/class-literals/).

Full example of using boolean sanitizer:

<pre>
use Maslosoft\Addendum\Interfaces\AnnotatedInterface
use Maslosoft\Mangan\Sanitizers\BooleanSanitizer;

class MyClass implements AnnotatedInterface
{
	public $autodetected = true;

	/**
	* @Sanitizer(BooleanSanitizer)
	*/
	public $explicitlySet;
}
</pre>
<?php

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Sanitizers\None;
use Maslosoft\Zamm\ShortNamer;
?>
<?php
/* @var $x AnnotatedInterface */
ShortNamer::defaults()->md();
$none = new ShortNamer(None::class);
?>
<title>5. None</title>

#None Sanitizer

`None` sanitizer will disable any sanitization. Use this sanitizer if property
has default value but sanitization should not be performed. However in such case
it is better to create custom sanitizer.

If property does not have default value, or it's default vale is `null`, it
will not be sanitized - equivalent to use <?= $none; ?>

Full example of using <?= $none; ?>:

<pre>
use Maslosoft\Addendum\Interfaces\AnnotatedInterface
use Maslosoft\Mangan\Sanitizers\None;

class MyClass implements AnnotatedInterface
{
	public $autodetected;

	/**
	* @Sanitizer(None)
	*/
	public $explicitlySet;
}
</pre>
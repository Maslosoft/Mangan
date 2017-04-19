<?php

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Sanitizers\DateSanitizer;
use Maslosoft\Mangan\Sanitizers\MongoStringId;
use Maslosoft\Zamm\ShortNamer;
?>
<?php
/* @var $x AnnotatedInterface */
ShortNamer::defaults()->md();
$date = new ShortNamer(DateSanitizer::class);
?>
<title>7. Date</title>

#Date Sanitizer

Date sanitizers will ensure that value is `MongoDate`. This sanitizer will not be
autodetected and need to be explicitly set.

Full example of using <?= $date ?>:

<pre>
use Maslosoft\Addendum\Interfaces\AnnotatedInterface
use Maslosoft\Mangan\Sanitizers\DateSanitizer;

class MyClass implements AnnotatedInterface
{
	/**
	* @Sanitizer(DateSanitizer)
	*/
	public $date = null;
}
</pre>
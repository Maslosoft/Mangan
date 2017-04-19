<?php

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Sanitizers\MongoObjectId;
use Maslosoft\Mangan\Sanitizers\MongoStringId;
use Maslosoft\Zamm\ShortNamer;
?>
<?php
/* @var $x AnnotatedInterface */
ShortNamer::defaults()->md();
$objectId = new ShortNamer(MongoObjectId::class);
$stringId = new ShortNamer(MongoStringId::class);
?>
<title>6. ObjectId</title>

#ObjectId Sanitizers

ObjectId sanitizers will ensure that value is MongoId. This sanitizers will not be
autodetected and need to be explicitly set.

There are two distinct, but similar sanitizer for MongoDB ObjectId:

* <?= $objectId; ?> - will ensure object ID as an object
* <?= $stringId; ?> - will ensure object ID as a string

Both sanitizers have <?= $objectId->nullable; ?> option to allow `null` values,
defaults to false.
If not set, or set to `false`, ObjectId will be generated as needed.

Full example of using <?= $stringId; ?> and <?= $objectId; ?>:

<pre>
use Maslosoft\Addendum\Interfaces\AnnotatedInterface
use Maslosoft\Mangan\Sanitizers\MongoObjectId;
use Maslosoft\Mangan\Sanitizers\MongoStringId;

class MyClass implements AnnotatedInterface
{
	/**
	* @Sanitizer(MongoStringId)
	*/
	public $myStringId = '';

	/**
	* @Sanitizer(MongoObjectId)
	*/
	public $myObjectId = null;
}
</pre>
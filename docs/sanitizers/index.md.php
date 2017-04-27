<?php

use Maslosoft\Mangan\Sanitizers\None;
use Maslosoft\Staple\Widgets\SubNavRecursive;
use Maslosoft\Zamm\ShortNamer;
?>
<?php
ShortNamer::defaults()->md();
$none = new ShortNamer(None::class);
?>
<title>3. Data sanitization</title>

#Data sanitization

MongoDB allows You to store schema-less structures in it's storage engine.
In most cases however, there is some schema, or some constraints which are
required for proper operation of system.

Most basic principle of schema are data types. Having PHP model with defined
properties, each one might have some type specified - or accept multiple types.
Whichever the case, this type always has some signature which should be
respected. Especially when it comes from untrusted source.

Mangan provides facility to enforce proper types, be it PHP scalar values, or
custom objects. So that once defined model will always have specified type.

Even more, this works semi-automatically for PHP scalar values - Mangan will
guess required enforcing class when there is a default value set.
This type enforcing classes are called Sanitizers. To set sanitizer
manually, use [`@Sanitizer`](../annotations/sanitizer) annotation.

To disable sanitization for specified property do not set it's default value,
set it to null or use <?= $none; ?> sanitizer.

### In summary

**Sanitizer can be defined by:**

* Setting default type for property
* By using [`@Sanitizer`](../annotations/sanitizer) annotation

**Can be disabled by:**

* Setting default value to null
* Omitting default value
* Using <?= $none; ?> sanitizer

### Available sanitizers:

<?php
echo new SubNavRecursive([
'root' => __DIR__,
 'path' => '.',
 'skipLevel' => 1,
]);
?>
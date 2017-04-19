<?php

use Maslosoft\Mangan\Annotations\SanitizerAnnotation;
use Maslosoft\Mangan\Annotations\SanitizerArrayAnnotation;
use Maslosoft\Zamm\DocBlock;
use Maslosoft\Zamm\ShortNamer;
?>
<?php
ShortNamer::defaults()->md();
$sanitizer = new ShortNamer(SanitizerAnnotation::class);
$doc = new DocBlock(SanitizerArrayAnnotation::class);
?>
<title>Sanitizer Array</title>

#Sanitizer Array

Sanitizer array works exactly like [`@Sanitizer`](../sanitizer/) annotation,
except that it will enforce arrays of specified type.

For example place this annotation on property to enforce array of integers:

```
@SanitizerArray(IntegerSanitizer)
```

[Read more about data sanitization](../../sanitizers/)
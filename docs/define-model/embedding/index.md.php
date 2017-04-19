<?php

use Maslosoft\Ilmatar\Widgets\MsWidget;
use Maslosoft\Mangan\Annotations\EmbeddedAnnotation;
use Maslosoft\Zamm\ShortNamer;
?>
<?php
/* @var $this MsWidget */
ShortNamer::defaults()->md();
$embed = new ShortNamer(EmbeddedAnnotation::class);
?>
<title>Embedding Objects</title>

# Embedding Objects

As MongoDB comes with storage engine capable of storing nested structures,
Mangan has facility to store objects compositions. Most simple solution for
this is to embed sub object in the same document, just like it is created
at runtime.

To store sub object in database, use [@Embedded](../../annotations/embedded/)
annotation on one of object property:


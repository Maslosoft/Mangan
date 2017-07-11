<?php

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Ilmatar\Widgets\MsWidget;
use Maslosoft\Mangan\Annotations\EmbeddedAnnotation;
use Maslosoft\Mangan\Document;
use Maslosoft\Mangan\EntityManager;
use Maslosoft\Mangan\Finder;
use Maslosoft\Mangan\Validator;
use Maslosoft\Zamm\ShortNamer;
?>
<?php
/* @var $this MsWidget */
ShortNamer::defaults()->md();
$embed = new ShortNamer(EmbeddedAnnotation::class);
$iface = new ShortNamer(AnnotatedInterface::class);
$document = new ShortNamer(Document::class);
$finder = new ShortNamer(Finder::class);
$em = new ShortNamer(EntityManager::class);
$validator = new ShortNamer(Validator::class);
?>
<title>3. Composing Models</title>

# Composing Models

Some of developers, or companies like [active document][ad] approach, while
others are more keen to use dedicated classes of each operation on the model
life cycle and keep [model even plain PHP class][plain].

Mangan allows You to choose any of approach You like, or blend it
using traits. This allows developer to choose which methods should belong
to model, or which should be in separate classes.

[annotation]: ../../annotations/embedded/
[repo]: https://github.com/MaslosoftGuides/mangan.embedding
[plain]: ../plain/
[annotations]: /addendum/
[ad]: ../active-document/
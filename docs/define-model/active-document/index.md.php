<?php

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Ilmatar\Widgets\MsWidget;
use Maslosoft\Mangan\Annotations\EmbeddedAnnotation;
use Maslosoft\Mangan\Document;
use Maslosoft\Mangan\EmbeddedDocument;
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
$edocument = new ShortNamer(EmbeddedDocument::class);
$finder = new ShortNamer(Finder::class);
$em = new ShortNamer(EntityManager::class);
$validator = new ShortNamer(Validator::class);
?>
<title>Active Document</title>

# Active Document

Active document is a model approach that allows to invoke model
operations directly on it's instance.

Mangan has [pre-composed][composing] class, which have all
required active document methods: <?= $document; ?> along
with it's a bit lighter counterpart for embedded documents: <?= $edocument; ?>

[annotation]: ../../annotations/embedded/
[repo]: https://github.com/MaslosoftGuides/mangan.embedding
[plain]: ../plain/
[annotations]: /addendum/
[composing]: ../composing/
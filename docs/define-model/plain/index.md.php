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
<title>2. Plain PHP Object</title>

# Plain PHP Object

Mangan is capable of storing plain PHP objects. That means, that to manage
object state in MongoDB, model class can extend from any user provided class,
or non at all. Only requirement is to implement <?= $iface; ?>, as it is
required for parsing annotations. Using [annotations][annotations] allows
to separate storing logic from model code.

All Mangan features are available on either plain PHP objects, or when
using provided base class, for instance <?= $document; ?>.

The difference, is how those operations are performed. When working
with plain objects, for each of operation kind there are separate
classes:

* Validating objects: <?= $validator; ?>.
* Storing and removing objects: <?= $em; ?>.
* Retrieving objects: <?= $finder; ?>.

In contrast to this, [pre-composed][composing] <?= $document; ?> class have all CRUD
methods built-in.

It is sole model developer whether he want to use [active document][ad] approach
or if he decides to use [entity manager/finder/validator][composing] or any mix of those.

[annotation]: ../../annotations/embedded/
[repo]: https://github.com/MaslosoftGuides/mangan.embedding
[plain]: ../plain/
[annotations]: /addendum/
[composing]: ../composing/
[ad]: ../active-document/
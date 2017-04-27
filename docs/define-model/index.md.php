<?php

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Ilmatar\Components\Controller;
use Maslosoft\Ilmatar\Widgets\Form\ActiveForm;
use Maslosoft\Zamm\ShortNamer;
use Maslosoft\Staple\Widgets\SubNavRecursive;
?>
<?php
/* @var $this Controller */
/* @var $form ActiveForm */
ShortNamer::defaults()->md();
$ann = new ShortNamer(AnnotatedInterface::class);
?>
<template>docs</template>
<title>2. Define Model</title>
#Define Model

In many cases, model can be defined as a plain PHP class, with all public
properties being stored in MongoDB. These might be [sanitized](../sanitizers/)
to ensure their type during object life cycle.

Only requirement is to implement <?= $ann; ?> interface, which is empty, but it
instructs annotations engine that it should process this class.

To fine tune, or to create compositions of objects, annotations can be used.

All model properties can be defined with [annotations](../annotations/) implemented by [addendum project](/addendum/).

Using [annotations](/addendum/), allows storing arbitrary PHP object without the need of extending
from any base class or implementing any methods.

### More about creating models:

<?php
echo new SubNavRecursive([
'root' => __DIR__,
 'path' => '.',
 'skipLevel' => 1,
]);
?>

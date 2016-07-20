<?php

use Maslosoft\Ilmatar\Components\Controller;
use Maslosoft\Ilmatar\Widgets\Form\ActiveForm;
use Maslosoft\Mangan\Events\Event;
use Maslosoft\Zamm\DocBlock;
use Maslosoft\Zamm\ShortNamer;
?>
<?php
/* @var $this Controller */
/* @var $form ActiveForm */
?>
<?php
$doc = (new DocBlock(Event::class));
$n = (new ShortNamer(Event::class));
?>
<title>Events</title>

# Events

Events can be attached to model instance, or by class name.

## Function <?= $n->on()->md ?>
<?= $doc->method('on'); ?>

## Function <?= $n->off()->md ?>
<?= $doc->method('off'); ?>

## Function <?= $n->trigger()->md ?>
<?= $doc->method('trigger'); ?>
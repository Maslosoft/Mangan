<?php

use Maslosoft\Mangan\Events\Event;
use Maslosoft\Zamm\DocBlock;
use Maslosoft\Zamm\ShortNamer;
use Maslosoft\Zamm\Widgets\DocNavRecursive;

?>
<?php
$doc = (new DocBlock(Event::class));
$n = (new ShortNamer(Event::class));
?>
<title>6. Events</title>

# Events

Events can be attached to model instance, class name, interface name or even trait name.

<p class="alert alert-success">
    When using class names, it is recommended to use <code>::class</code> magic constant.
</p>

### Function <?= $n->on()->md ?>
<?= $doc->method('on'); ?>

### Function <?= $n->off()->md ?>
<?= $doc->method('off'); ?>

### Function <?= $n->trigger()->md ?>
<?= $doc->method('trigger'); ?>

## More on events:

<?= new DocNavRecursive; ?>

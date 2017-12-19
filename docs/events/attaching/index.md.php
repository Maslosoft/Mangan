<?php

use Maslosoft\Ilmatar\Components\Controller;
use Maslosoft\Ilmatar\Widgets\Form\ActiveForm;
use Maslosoft\Mangan\Events\Event;
use Maslosoft\Mangan\Interfaces\EntityManagerInterface;
use Maslosoft\Mangan\Interfaces\EventHandlersInterface;
use Maslosoft\Mangan\Interfaces\FinderInterface;
use Maslosoft\Mangan\Interfaces\InternationalInterface;
use Maslosoft\Mangan\Interfaces\TrashInterface;
use Maslosoft\Mangan\Interfaces\ValidatableInterface;
use Maslosoft\Mangan\Mangan;
use Maslosoft\Zamm\DocBlock;
use Maslosoft\Zamm\ShortNamer;
?>
<?php
/* @var $this Controller */
/* @var $form ActiveForm */
?>
<?php
ShortNamer::defaults()->md;
$doc = (new DocBlock(Event::class));
$n = (new ShortNamer(Event::class));
$m = (new ShortNamer(Mangan::class));

$eh = (new ShortNamer(EventHandlersInterface::class));

$em = (new ShortNamer(EntityManagerInterface::class));
$f = (new ShortNamer(FinderInterface::class));
$v = (new ShortNamer(ValidatableInterface::class));
$i = (new ShortNamer(InternationalInterface::class));
$t = (new ShortNamer(TrashInterface::class));

/* @var Mangan $m */
?>
<title>Attaching Events</title>

# Attaching Events

<p class="alert alert-warning">
    Events <i>might</i> dramatically change execution of various
    actions, like finding, inserting - including skipping of this actions
    too!
</p>

## Attaching Events Basics

To attach event to model, call <?= $n->on(); ?> method with
first parameter being one of:

* Document instance
* Document class name
* Interface class name
* Trait class name

<p class="alert alert-success">
    All document classes extending from class, implementing interface or using trait
    that have attached event will trigger that event too
</p>

The second parameter should be name of event. <?= $m; ?> built in events
can be found on interfaces regarding particular functionality, for example:

* <?= $em; ?>* *
* <?= $f; ?>* *
* <?= $v; ?>* *
* <?= $i; ?>* *
* <?= $t; ?>* *

Custom event types can be defined too. The best way is to create class constant
with event name.

<p class="alert alert-warning">
    <b>Always</b> set <code>isValid</code> property of event if you wan't to continue
    program after event. Setting <code>isValid</code> to false or not setting it <i>might</i>
    stop action after event - it depends on action logic.
</p>

<p class="alert alert-warning">
    Set <code>handled</code> property to true, to stop
    further events processing.
</p>

<p class="alert alert-danger">
    Make sure that the event handlers are not bound multiple times as this might
    hurt performance or have unexpected results.
</p>

<p class="alert alert-success">
    Always try to create immutable event handlers. In other words these
    handlers should not rely on application state.
</p>

### Attaching to Model

The most simple way to attach event handler, it to define it right in model
class constructor. Just make sure that it is attached once.

#### Example

```
class MyDocument extends Document
{
    public function __construct($scenario = ScenariosInterface::Insert, $lang = '')
    {
        // Initialize events
        static $once = false;
        if (!$once)
        {
            Event::on(__CLASS__, EntityManagerInterface::EventAfterSave, [$this, 'notify']);
            Event::on(__CLASS__, EntityManagerInterface::EventAfterUpdate, [$this, 'notify']);
            $once = true;
        }
    }

    public function notify(ModelEvent $event)
    {
        var_dump($event->sender);
    }
}
```

In above example after saving or updating `MyDocument` or any derived class, the `notify` method will
be called.

<?php
ShortNamer::defaults()->html;
?>
<p class="alert alert-warning">
    Please note that <?= $em; ?> have different events for save, insert and update operations
    and these need to be attached separately.
</p>
<?php
ShortNamer::defaults()->md;
?>

### Attaching Globally

<?= $m; ?> offers facility to attach events globally as an configuration
option, so that these events work somewhat like database triggers. They
are always active and will handle any triggered event on application
lifecycle.

To create globally attached event handler, implement interface <?= $eh; ?>
and attach it to <?= $m->eventHandlers; ?> property. This property
should contain array of event handlers.

Each array element can be specified as following:

* As a class name implementing <?= $eh; ?> interface
* [EmbeDi][di] compatible configuration array

#### Example Configurations

```
'eventHandlers' = [
    MyEventHandler::class,
    [
        MyConfigurableEventHandler::class,
        'option' => 'My Option'
    ]
];
```

[di]: /embedi/

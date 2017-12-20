<?php

use Maslosoft\Mangan\Events\Event;
use Maslosoft\Mangan\Events\ModelEvent;
use Maslosoft\Mangan\Finder;
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
ShortNamer::defaults()->md;
$doc = (new DocBlock(Event::class));
$n = (new ShortNamer(Event::class));
$me = (new ShortNamer(ModelEvent::class));
$m = (new ShortNamer(Mangan::class));

$finder = (new ShortNamer(Finder::class));

$eh = (new ShortNamer(EventHandlersInterface::class));

$em = (new ShortNamer(EntityManagerInterface::class));
$f = (new ShortNamer(FinderInterface::class));
$v = (new ShortNamer(ValidatableInterface::class));
$i = (new ShortNamer(InternationalInterface::class));
$t = (new ShortNamer(TrashInterface::class));

/* @var Mangan $m */
/* @var ModelEvent $me */
/* @var Finder $finder*/
?>
<title>2. Finder</title>

# Finder Events

The <?= $finder; ?> events are divided by two *categories*:

* Events before find
* Events after find

Names of these events are defined as constants in <?= $f; ?>,
and it is highly recommended to use this constants to [attach][at]
events.

All events of each category work in the same way. The difference
is when these are raised.

This finder *actions* can be divided like following:

* `find` - executed before or after `find*` methods. For example:
    * <?= $finder->find(); ?>.
    * <?= $finder->findByAttributes(); ?>.
    * <?= $finder->findAll(); ?>.
    * <?= $finder->findAllByPk(); ?>.
* `exists` - executed before or after executing <?= $finder->exists(); ?>.
* `count` - executed before or after `count*` methids. For example:
    * <?= $finder->count(); ?>.
    * <?= $finder->countByAttributes(); ?>.

## Before Find (Exists, Count)

The <?= $me->sender; ?> property of before find/count/exists events
contains *new* or any model passed to finder method. It does **not**
contain found model - as the query execusion is not performed yet.

#### Available *before* events:

* `<?= FinderInterface::EventBeforeFind; ?>` - defined as constant `FinderInterface::EventBeforeFind`
* `<?= FinderInterface::EventBeforeExists; ?>` - defined as constant `FinderInterface::EventBeforeExists`
* `<?= FinderInterface::EventBeforeCount; ?>` - defined as constant `FinderInterface::EventBeforeCount`


#### Prevent finding

Events before find can actually prevent executing query. This might
be useful for access control checking or any scenario where
data should not be returned for any reason.

To prevent action execution event property <?= $me->isValid; ?> must be
set to `false`. However, different event can set this value back to `true`!

To ensure that no other events will try to *handle* current event, set property
<?= $me->handled; ?> to `true`. This will stop event chain and return to action execution.

<p class="alert alert-warning">
    To ensure that <i>currently</i> handled event will be considered
    as stopping action, ensure that <?= $me->handled->html(); ?> is set.
</p>

#### Other use cases

Events can be used also on other scenarios, including but not limited to:

* Modifying criteria to add multi-tenant scope
* Access control checking by adding extra criteria
* Logging queries

## After Find (Exists, Count)

The after find, exists, count events can be used to do something with found model.

For example:

* Change some of the object properties
* Log views count of concrete document

As action was already performed, the <?= $me->isValid; ?> property is irrelevant.
However <?= $me->handled; ?> property if set to `true` while handling event, will prevent
other event handlers to be executed. Please note however, that events order is *not guaranteed*
so do not rely on this behavior.

<p class="alert alert-success">
    The <?= $me->sender->html(); ?> property in events kind <i>afterFind</i> contain actually found model.
</p>

<p class="alert alert-warning">
    In case of <i>afterExists</i> and <i>afterCount</i> events, the property <?= $me->sender->html(); ?>
    contains model passed to <code>Finder</code> instance - as in this methods model
    is not even retrieved from database.
</p>

<p class="alert alert-warning">
    The after find event will be triggered for <i>every</i> model
    in found set. That is, it can fire many, many times.
</p>

#### Available *after* events:

* `<?= FinderInterface::EventAfterFind; ?>` - defined as constant `FinderInterface::EventAfterFind`
* `<?= FinderInterface::EventAfterExists; ?>` - defined as constant `FinderInterface::EventAfterExists`
* `<?= FinderInterface::EventAfterCount; ?>` - defined as constant `FinderInterface::EventAfterCount`


[attach]: ../attaching/
# Events

Attach handlers using `Maslosoft\Mangan\Events\Event`. A target may be a model
object, a class name, an interface, or a trait. Passing an object selects its
class; it does not limit the handler to that one object.

```php
use Maslosoft\Mangan\Events\Event;
use Maslosoft\Mangan\Events\ModelEvent;
use Maslosoft\Mangan\Interfaces\EntityManagerInterface;

$handler = static function (ModelEvent $event): void {
    $savedModel = $event->sender;
    // React to this model's completed save.
};

Event::on(Plant::class, EntityManagerInterface::EventAfterSave, $handler);
// ... perform application work ...
Event::off(Plant::class, EntityManagerInterface::EventAfterSave, $handler);
```

Register handlers during application-context bootstrap. Each `on()` call adds a
registration; repeated registration can cause repeated invocation. Handlers live
in the current Light context, so register them again for a new request context.
Avoid constructor-local static flags that skip registration in later contexts.

`ModelEvent::$sender` identifies the triggering model. Setting `handled` stops
later handlers; it does not mean that the operation should be cancelled. Before
events that support cancellation use their validity contract.

See [attaching handlers](attaching/index.md), [finder events](finder/index.md),
and [application-server lifecycle](../application-servers/index.md).

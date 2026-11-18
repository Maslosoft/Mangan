# Attaching event handlers

Call `Event::on($target, $name, $handler, $data = null, $append = true)`.
Use interface constants for event names and a callable receiving the event.
`$data` is available through the event's `data` property. With `$append = false`,
the registration is prepended to that target's handlers.

```php
use Maslosoft\Mangan\Events\Event;
use Maslosoft\Mangan\Events\ModelEvent;
use Maslosoft\Mangan\Interfaces\EntityManagerInterface;

$onInsert = static function (ModelEvent $event): void {
    $model = $event->sender;
    $tag = $event->data;
};

Event::on(Plant::class, EntityManagerInterface::EventAfterInsert, $onInsert, 'garden');
```

Remove that registration with the same callable:

```php
Event::off(Plant::class, EntityManagerInterface::EventAfterInsert, $onInsert);
```

A class registration also applies when a derived model triggers the event.
Passing a model instance still registers against its class. Do not capture a
specific model in a shared handler when the event's `sender` is the intended
object.

## Configured handler collections

Implement `Maslosoft\Mangan\Interfaces\EventHandlersInterface` and define
`setupHandlers()`. Add the implementation's class name or EmbeDi configuration
to Mangan's `eventHandlers` configuration before resolving the connection.
Mangan initializes configured handlers within the current context.

Register once per context and connection as appropriate to your application.
Do not use process-wide static flags for request initialization. See
[application servers](../../application-servers/index.md).

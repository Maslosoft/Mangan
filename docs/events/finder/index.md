# Finder events

Finder hooks use constants in `Maslosoft\Mangan\Interfaces\FinderInterface`:

| Operation | Before | After |
| --- | --- | --- |
| Find | `EventBeforeFind` | `EventAfterFind` |
| Exists | `EventBeforeExists` | `EventAfterExists` |
| Count | `EventBeforeCount` | `EventAfterCount` |

Before events receive the finder model as `ModelEvent::$sender`; the query has
not retrieved a result yet. They can restrict criteria or prevent execution.
Set `isValid = false` to cancel and `handled = true` to prevent later handlers
from changing that decision.

```php
use Maslosoft\Mangan\Events\Event;
use Maslosoft\Mangan\Events\ModelEvent;
use Maslosoft\Mangan\Interfaces\FinderInterface;

Event::on(Plant::class, FinderInterface::EventBeforeFind,
    static function (ModelEvent $event): void {
        // Example: this context must not query Plant documents.
        $event->isValid = false;
        $event->handled = true;
    }
);
```

`EventAfterFind` receives the hydrated result model and fires for each model in
a result set. After-count and after-exists events receive the finder model,
since those operations do not hydrate a result document. Setting `isValid` in
an after event cannot undo the query.

See [attaching handlers](../attaching/index.md) for registration and removal.

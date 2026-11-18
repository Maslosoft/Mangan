# Creating a sanitizer

Implement `SanitizerInterface` with `read($model, $dbValue)` and
`write($model, $phpValue)`. Return the converted value; do not mutate unrelated
model state. Both methods may be called repeatedly during transformations.

```php
use Maslosoft\Mangan\Interfaces\Sanitizers\Property\SanitizerInterface;

class TrimmedString implements SanitizerInterface
{
    public function read($model, $dbValue): string
    {
        return trim((string) $dbValue);
    }

    public function write($model, $phpValue): string
    {
        return trim((string) $phpValue);
    }
}
```

Import the class in the model file and select it explicitly:

```php
/**
 * @Sanitizer(TrimmedString)
 */
public string $title = '';
```

Sanitizers normalize data. Use validators to reject values and provide messages.
A sanitizer may be reused within an execution context, so avoid storing the
current user, model, or request in its properties. Test both directions,
including null and empty input when the property permits them.

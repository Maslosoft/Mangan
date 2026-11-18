# Number validation

Use `@NumericalValidator` or `@NumberValidator` on numeric properties.

```php
/**
 * @NumericalValidator(min = 64, max = 1024, integerOnly = true)
 */
public int $maxSize = 128;
```

`min` and `max` are inclusive bounds. `gt` and `lt` are exclusive bounds.
`integerOnly` restricts accepted input to integer values. `allowEmpty` controls
whether an empty value bypasses validation; set it explicitly when zero or empty
input must be checked.

The validator also supports scenario selection through `on`, safe assignment
through `safe`, and `skipOnError`. Customize messages through `msgNumber`,
`msgInteger`, `msgTooSmall`, `msgTooBig`, `msgGt`, and `msgLt`.

Validation does not replace input conversion. See [sanitizers](../../sanitizers/index.md).

# Sanitizer Array

Sanitizer array works exactly like [`@Sanitizer`](../sanitizer/index.md) annotation,
except that it will enforce arrays of specified type.

For example place this annotation on property to enforce array of integers:

```
@SanitizerArray(IntegerSanitizer)
```

[Read more about data sanitization](../../sanitizers/index.md)

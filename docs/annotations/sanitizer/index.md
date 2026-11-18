# Sanitizer

Use `Sanitizer` annotation to enforce particular data type.

There are numerous built-in sanitizers which can be used, as well as any
custom sanitizer can be build. First annotation value must be sanitizer class
literal, class name as string or short string literal based for built-in
sanitizers. Some sanitizers can also have some parameters.
To get list of parameters, read particular sanitizer documentation.

**Note: There can be only one sanitizer per field.**

Example usage:
```
@Sanitizer(MongoObjectId)
```

For built-in sanitizers, also short string notation can be used,
without importing class:

```
@Sanitizer('MongoObjectId')
```

To skip variable sanitization either make default value `null` or define
`None` sanitizer:
```
@Sanitizer(None)
@Sanitizer('None')
```

Example of using sanitizer with parameters:
```
@Sanitizer(MongoObjectId, nullable = true)
```

[Read more about data sanitization](../../sanitizers/index.md)

# Embedded Array annotation

Annotation for array of embedded documents in MongoDB
default class name will be used for getting empty properties,
but any type of embedded document can be stored within this field.

Examples:

Embedded array with any model:
```
@EmbeddedArray
```

Embedded array with default class:
```
@EmbeddedArray(Company\ClassName)
```

Embedded array with default class and compare key `_id`
```
@EmbeddedArray(Company\ClassName, '_id')
```

Embedded array with default class and composite compare key of `login` and `email`:
```
@EmbeddedArray(Company\ClassName, {'login', 'email'})
```

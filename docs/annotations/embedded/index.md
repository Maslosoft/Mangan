# Embedded annotation

Annotation for embedded document in mongo
`defaultClassName` will be used for getting empty properties,
but any type of embedded document can be stored within this field
Examples:

Embed with namespaced class literal:

```
@Embedded(Company\Product\EmbeddedClassName)
```

Embed with default class - short notation - `EmbeddedClassName` imported via
use statement:

```
@Embedded(EmbeddedClassName)
```

# Index Annotation

Index Annotation `@Index` can be used to create indices to
speed up database operations.

Mangan will decorate indexes according to model definition,
    for instance will create index for each of languages for `@I18N` fields.

## Simple Indexes

This annotation should be placed on model property. By default
it will create ascending index when placed without parameters:

```
class MyModel implements AnnotatedInterface
{
    /**
     * @Index
     */
    public $myField = '';
}
```

### Directions

The index can be ascending, descending or both at the same time. Simplified
syntax for index directions requires only passing sorting direction as a
parameter.

When using directions, it is recommended to use class constants
    to indicate sorting for better comprehensibility.

```
class MyModel implements AnnotatedInterface
{
    /**
     * @Index(Sort::SortAsc)
     * @Index(Sort::SortDesc)
     */
    public $myField = '';
}
```

## Composite Indexes

The annotation has `IndexAnnotation->keys` property which allows to define
on which keys index should be created as whole. In other
words, the `IndexAnnotation->keys` value define composite index.

This annotation can be really placed on any property when
having full options defined. For sake of readability
it is recommended to place it on one of indexed property.

[JSON Syntax](https://maslosoft.com/addendum/docs/data-types/json/) can be used
    to define indexes, so the configuration can be applied right
    like in [MongoDB documentation](https://docs.mongodb.com/manual/core/index-compound/).

When using `IndexAnnotation->keys` option, the value should
be array with field names as keys and values indicating sorting order.

```
class MyModel implements AnnotatedInterface
{
    /**
     * @Index('keys' = {'userName': 1, 'status': 1})
     */
    public $userName = '';

    public $status = 1;
}
```

### Extra Options

Index options can be passed as a second argument `IndexAnnotation->options`, and are
same as according to [documentation of MongoDB](https://docs.mongodb.com/manual/core/index-unique/).

```
class MyModel implements AnnotatedInterface
{
    /**
     * @Index('keys' = {'userName': 1, 'status': 1}, 'options' = {'unique': true})
     */
    public $userName = '';

    public $status = 1;
}
```

#### Shortened Notation

These can be even more shortened, to be literally two JSON values:

```
class MyModel implements AnnotatedInterface
{
    /**
     * @Index({'userName': 1, 'status': 1}, {'unique': true})
     */
    public $userName = '';

    public $status = 1;
}
```

Which is equivalent of calling following code in Mongo shell:

```
db.MyModel.createIndex({"userName": 1, "status": 1}, {"unique": true})
```

## Other Index Types

Other indexes can be defined according to [MongoDB Documentation](https://docs.mongodb.com/manual/indexes/).

For example to create [2d Sphere index](https://docs.mongodb.com/manual/core/2dsphere/),
we need embedded document and set annotation value to `2dsphere`.

```
class ModelWith2dSphere extends Document
{
    /**
     * @Index(IndexManager::IndexType2dSphere)
     * @Embedded(Geo)
     */
    public $loc = null;
}
```

Or with extended notation, including key name:

```
class ModelWith2dSphere extends Document
{
    /**
     * @Index({'loc': IndexManager::IndexType2dSphere})
     * @Embedded(Geo)
     */
    public $loc = null;
}
```

[sanitizer]: ../../sanitizers/objectid/index.md

<?php

use Maslosoft\Mangan\Annotations\Indexes\IndexAnnotation;
use Maslosoft\Mangan\Annotations\PrimaryKeyAnnotation;
use Maslosoft\Mangan\Document;
use Maslosoft\Zamm\DocBlock;
use Maslosoft\Zamm\ShortNamer;
?>
<?php

/* @var $ia IndexAnnotation */
$doc = new DocBlock(IndexAnnotation::class);
$ia = new ShortNamer(IndexAnnotation::class);
$md = new ShortNamer(Document::class);
ShortNamer::defaults()->md;
?>
<title>Index</title>

#Index Annotation

Index Annotation `@Index` can be used to create indices to
speed up database operations.

<p class="alert alert-success">
    Mangan will decorate indexes according to model definition,
    for instance will create index for each of languages for <code>@I18N</code> fields.
</p>

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

<p class="alert alert-success">
    When using directions, it is recommended to use class constants
    to indicate sorting for better comprehensibility.
</p>

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

The annotation has <?= $ia->keys; ?> property which allows to define
on which keys index should be created as whole. In other
words, the <?= $ia->keys; ?> value define composite index.

This annotation can be really placed on any property when
having full options defined. For sake of readability
it is recommended to place it on one of indexed property.

<p class="alert alert-success">
    <a href="/addendum/docs/data-types/json/">JSON Syntax</a> can be used
    to define indexes, so the configuration can be applied right
    like in <a href="https://docs.mongodb.com/manual/core/index-compound/">MongoDB documentation</a>.
</p>

When using <?= $ia->keys; ?> option, the value should
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

Index options can be passed as a second argument <?= $ia->options; ?>, and are
same as according to <a href="https://docs.mongodb.com/manual/core/index-unique/">documentation of MongoDB</a>.

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
db.MyModel.createIndex({"username": 1, "status": 1}, {"unique": true})
```

## Other Index Types

Other indexes can be defined according to <a href="https://docs.mongodb.com/manual/indexes/">MongoDB Documentation</a>.

For example to create <a href="https://docs.mongodb.com/manual/core/2dsphere/">2d Sphere index</a>,
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

[sanitizer]: ../../sanitizers/objectid/
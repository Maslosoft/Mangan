# Related Array Annotation

The `@RelatedArray` will populate document property with array
of sub objects of type defined with this annotation.

This annotation works exacly the same as [`@Related`][r], except
that it will load *array* of documents.

See [related annotation documentation](../related/index.md) for more details.

#### Minimal example of `@RelatedArray` with `join`

This will load documents having `entity_id` value same as owner's document `_id`
value.

```
/**
 * @RelatedArray(Projects, join = {'_id' = 'entity_id'})
 */
public $projects = [];
```

#### Minimal example of `@RelatedArray` with `condition`

This will load documents having `type` value of `opensource`.

```
/**
 * @RelatedArray(Projects, condition = {'type' = 'opensource'})
 */
public $projects = [];
```

[r]: ../related/index.md

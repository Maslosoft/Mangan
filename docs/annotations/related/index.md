# Related Annotation

See [example repository](https://github.com/MaslosoftGuides/mangan.related)
	for working example

The `@Related` annotion allow to load document from other (or same) collection
based on owner model value relating the same value of related document with `join`
parameter.

Also allows loading document by static criteria with `condition` parameter.

This annotation will instruct [Mangan][mangan] to load only one document,
compared to [related array annotions][ra].

Loaded document will be attached to property of owner document.

Related document must have primary key

### Syntax

This annotation requires related document class name, prefably in form of
[class literal][ci] and one of `join` or `condition` parameters. Parameters `join` and
`condition` can be used together.

#### Join

Join parameter will be used in criteria for finding related object, and will
compare against related document value and owner document value.

##### Example owner document annotation for `join`

In this example, owner property value of `_id` will be used as a value for `parentId`
when loading `MyDocument`.

```
/**
 * @Related(MyDocument, join = {'_id' = 'parentId'})
 */
public $subDocument = null;
```

###### Example of related document

```
class MyDocument implements AnnotatedInterface
{
	/**
	 * @Sanitizer(MongoObjectId)
	 */
	public $_id;

	/**
	 * @Sanitizer(MongoObjectId)
	 */
	public $parentId = null;
}
```

#### Condition

The `condition` parameter can be used to load documents based on static condition.
The value used for criteria for loading document will literally same as declared
in `@Related` annotation.

##### Example owner document annotation for `condition`

In this example related document having property `type` of value `image` will
be loaded into property `subDocument`.
It is recommended to use class constants if possible instead of
	*<a href="https://en.wikipedia.org/wiki/Magic_number_(programming)" targe="_blank">magic string</a>*.

When there are many documents matching `condition`, the first one will
	be loaded. Order can be changed with `sort` parameter.

```
/**
 * @Related(MyDocument, condition = {'type' = MyDocument::TypeImage})
 */
public $subDocument = null;
```

###### Example of related document

```
class MyDocument implements AnnotatedInterface
{
	const TypeImage = 'image';
	const TypeText = 'text';

	/**
	 * @Sanitizer(MongoObjectId)
	 */
	public $_id;

	public $type = '';
}
```

#### Sort

While this parameter is more relevant for [related array][ra], it can be added
also for `@Related`. This might be useful combined with `condition` parameter,
to have more control of what's might be loaded.

For example `sort` can be used to load latest related document.

###### Example of using `sort` parameter

For setting sort direction is is strongly recommended to use class constants
	of `SortInterface::SortAsc` and `SortInterface::SortDesc`
	instead of *
		<a 
			href="https://en.wikipedia.org/wiki/Magic_number_(programming)"
			targe="_blank">
			magic number
		</a>*

```
/**
 * @Related(MyDocument, condition = {'type' = MyDocument::TypeImage}, sort = {createDate = SortInterface::SortAsc})
 */
public $subDocument = null;
```

###### Example of related document

```
class MyDocument implements AnnotatedInterface
{
	const TypeImage = 'image';
	const TypeText = 'text';

	/**
	 * @Sanitizer(MongoObjectId)
	 */
	public $_id;

	public $type = '';

	/**
	 * @Sanitizer(DateSanitizer)
	 */
	public $createDate = null;
}
```

#### Updatable

By default related document are `updatable`, so that any changes of sub document
will be stored in database when saving owner document.

This behavior can be changed by setting `updatable` parameter to `false`

Properties from both `join` and `condition` will be
	stored in related	model even when `updatable` is `false`.

###### Example of using `updatable` parameter

In this example `MyDocument` attached to `subDocument` property will not be
updated when storing owner document. However it's property `type` will be set
to `MyDocument::TypeImage` too keep relation consistent.

```
/**
 * @Related(MyDocument, condition = {'type' = MyDocument::TypeImage}, updatable = false)
 */
public $subDocument = null;
```

[mangan]: https://maslosoft.com/mangan/
[ra]: ../related-array/index.md
[ms]: https://en.wikipedia.org/wiki/Magic_number_(programming)
[mn]: https://en.wikipedia.org/wiki/Magic_number_(programming)
[ci]: https://maslosoft.com/addendum/docs/data-types/class-literals/
[repo]: https://github.com/MaslosoftGuides/mangan.related

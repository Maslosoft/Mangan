# Primary Key Annotation

To define simple primary key mark field with `PrimaryKey` annotation.
To define composite primary key, use this annotation on several fields.

When defining custom primary key it is highly recommended
    to *not* define `_id` field.
    See [this issue](https://github.com/Maslosoft/Mangan/issues/63).

When inheriting from `Document`, primary key is set to be `_id` field, but it
is not defined explicitly. Field `_id` is default primary key in MongoDB, and
it is fallback primary key in mangan if there are no other keys are defined.

Example of simple pk:

```
class MyModel implements AnnotatedInterface
{
	/**
    * @PrimaryKey
    */
	public $myId = '';
}
```

Example of composite primary key when extending from `Document`:

```
class MyModel extends Document
{
	/**
    * @PrimaryKey
    */
	public $code = '';

	/**
    * @PrimaryKey
    */
	public $language = '';
}
```

#### Fallback primary key, with `_id` field.

When not using `Document`, and no special primary key is required, there should
be `_id` field for default MongoDB key. It will be used as a fallback PK when
there is no `@PrimaryKey`.

It is important to use [ObjectId sanitizer][sanitizer] on `_id`:

```
class MyModel implements AnnotatedInterface
{
	/**
    * @Sanitizer(MongoObjectId)
    */
	public $_id = '';
}
```

[sanitizer]: ../../sanitizers/objectid/index.md

<?php
use Maslosoft\Mangan\Annotations\PrimaryKeyAnnotation;
use Maslosoft\Mangan\Document;
use Maslosoft\Zamm\DocBlock;
use Maslosoft\Zamm\ShortNamer;
?>
<?php

$doc = new DocBlock(PrimaryKeyAnnotation::class);
$md = new ShortNamer(Document::class);
?>
<title>Primary Key</title>

#Primary Key Annotation

<?= $doc; ?>

<p class="alert alert-danger">
    When defining custom primary key it is highly recommended
    to <i>not</i> define <code>_id</code> field.
    See <a href="https://github.com/Maslosoft/Mangan/issues/63">this issue</a>.
</p>

When inheriting from <?= $md; ?>, primary key is set to be `_id` field, but it
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

Example of composite primary key when extending from <?= $md; ?>:

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

When not using <?= $md; ?>, and no special primary key is required, there should
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

[sanitizer]: ../../sanitizers/objectid/
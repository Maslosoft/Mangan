<?php

use Maslosoft\Mangan\Annotations\Validators\ValidatorAnnotation;
use Maslosoft\Zamm\DocBlock;
use Maslosoft\Zamm\ShortNamer;
?>
<?php
ShortNamer::defaults()->md();
$validator = new ShortNamer(ValidatorAnnotation::class);
$doc = new DocBlock(ValidatorAnnotation::class);
?>
<title>Validator</title>

#Validator Annotation

Annotation `@Validator` provides option to place arbitrary validator on
model attribute. It takes as a first argument validator [class name][class-type]
and other [arguments][params] will be passed to validator properties.

Minimal example of using `@Validator` with (custom) validator:

```
@Validator(MyValidator)
```

To pass extra arguments for validator, use [named parameters][params].

Example uf using built-in validator with parameter:

```
@Validator(StringValidator, min = 10)
```

There are also extra annotations for common validators. This is to provide
easier and possibly more obvious validation instructions.

Example of using built-in predefined validator annotations:

```
/**
* @RequiredValidator
* @UniqueValidator
*/
public $host = '';
```

Above code will ensure that attribute is not empty and that it is unique in
collection.

[Read more about data validation][validators]

[validators]: ../../validators/
[class-type]: /addendum/docs/data-types/class-literals/
[params]: /addendum/docs/data-types/params/
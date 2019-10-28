<?php

use Maslosoft\Mangan\Document;
use Maslosoft\Mangan\EntityManager;
use Maslosoft\Mangan\Interfaces\Validators\ValidatorInterface;
use Maslosoft\Mangan\Traits\ValidatableTrait;
use Maslosoft\Mangan\Validator;
use Maslosoft\Mangan\Validators\BuiltIn\NumberValidator;
use Maslosoft\Zamm\ShortNamer;
use Maslosoft\Zamm\Widgets\PropertiesDocs;
?>
<?php
ShortNamer::defaults()->md();

$iface = new ShortNamer(ValidatorInterface::class);
$validator = new ShortNamer(Validator::class);
$trait = new ShortNamer(ValidatableTrait::class);
$document = new ShortNamer(Document::class);
$em = new ShortNamer(EntityManager::class);
$n = new ShortNamer(NumberValidator::class);
/* @var $validator Validator */
/* @var $document Document */
/* @var $em EntityManager */
?>

<title>1. Number</title>

# Number

Number validator allows to check numeric value if it is over maximum or
below minimum threshold. Can also check if number is integer.

### Usage

To use number validator, use `@NumericalValidator` annotation on numeric property, 
with options:

```
/**
 * @NumericalValidator('min' = 64, 'max' = 1024)
 */
public $maxSize = 128;
```

Besides this validator specific properties (all are optional):

* <?= $n->min; ?> - minimum required value
* <?= $n->max; ?> - maximum allowed value
* <?= $n->integerOnly; ?> - whether require integer number

It has far more, common properties:

<?php
echo new PropertiesDocs(NumberValidator::class);
?>

[sanitizers]: ../sanitizers/
[annotation]: ../annotations/validator/
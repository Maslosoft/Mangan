<?php

use Maslosoft\Mangan\Document;
use Maslosoft\Mangan\EntityManager;
use Maslosoft\Mangan\Interfaces\Validators\ValidatorInterface;
use Maslosoft\Mangan\Traits\ValidatableTrait;
use Maslosoft\Mangan\Validator;
use Maslosoft\Zamm\ShortNamer;
use Maslosoft\Zamm\Widgets\DocNavRecursive;
?>
<?php
ShortNamer::defaults()->md();

$iface = new ShortNamer(ValidatorInterface::class);
$validator = new ShortNamer(Validator::class);
$trait = new ShortNamer(ValidatableTrait::class);
$document = new ShortNamer(Document::class);
$em = new ShortNamer(EntityManager::class);
/* @var $validator Validator */
/* @var $document Document */
/* @var $em EntityManager */
?>

<title>4. Validators</title>

# Validators

Keeping data safe and consistent is crucial for application stability.
To ensure proper types [sanitizers][sanitizers] were introduced, however
often when working with external user provided data, we need to check
and inform user if his input is fine.

Data validation can be performed by placing appropriate [annotation][annotation]
on model attribute.

### Standalone validator

Validation can be performed by using <?= $validator; ?> class, by caling
<?= $validator->validate(); ?> method. This will trigger all model validators,
and return `true` or `false` depending on validation result. Additionally
error messages can be obtained by calling <?= $validator->getErrors(); ?>.

Example of using <?= $validator; ?> class:

```
$validator = new Validator($model);
$isValid = $validator->validate(); // Returns boolean value
$errors = $validator->getErrors(); // Array with errors
```

### Incorporate validation into model

Validation methods can be added directly to model by either using
<?= $trait; ?> or by extending from pre-composed <?= $document; ?> class.

The result of it, is that model can be validated without having to `use` additional
class. Usage is exact the same as with standalone validator, except that methods
are available directly on model instance.

This is kind of active document approach:

```
$isValid = $model->validate(); // Returns boolean value
$errors = $model->getErrors(); // Array with errors
```

### Validation before saving model

To ensure that data is validated, validation will be performed before saving
by default. This validation can be skipped if desired.

This behavior is the same for <?= $em; ?> <?= $em->save(); ?> method
and also for pre-composed <?= $document; ?> <?= $document->save(); ?> method.

Example of using document <?= $document->save(); ?>:

```
$saved = $model->save(); // Will return false if not valid
$errors = $model->getErrors(); // Will contain error messages if not valid
```

<?= new DocNavRecursive(); ?>

[sanitizers]: ../sanitizers/
[annotation]: ../annotations/validator/
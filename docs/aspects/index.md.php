<?php

use Maslosoft\Mangan\AspectManager;
use Maslosoft\Mangan\Interfaces\AspectsInterface;
use Maslosoft\Mangan\Traits\AspectsTrait;
use Maslosoft\Zamm\ShortNamer;

?>
<?php
/* @var $this Controller */
/* @var $form ActiveForm */
$am = new ShortNamer(AspectManager::class);
$if = new ShortNamer(AspectsInterface::class);
$tr = new ShortNamer(AspectsTrait::class);
?>

<template>docs</template>
<title>Aspects</title>

# Aspects

Aspects are the point of view, of whats going on
on model. These are similar to scenarios, except
that model can have many aspects.

This has advantage, that depending on situation
aspects can be added or removed.

Aspects can be used for example in sanitizers,
to sanitize according to aspects. Or in validators
to possibly validate in different way or even skip
validation in some cases.

The aspect itself is simply string, which identifies
aspect. Aspects can be added removed or checked for existence.

<p class="alert alert-success">
    Aspects are set recursively, so that sub objects
    will also have aspect set or removed on root model.
</p>

### Using aspect manager

To use aspects model must implement <?= $if; ?>,
which can be done with predefined <?= $tr; ?>

To simplify using aspects, the <?= $am; ?>
class can be used which checks for <?= $if; ?>
implementation and adds, removes or checks for aspect.

```
AspectManager::addAspect($model, 'myAspect');
AspectManager::removeAspect($model, 'myAspect');
AspectManager::hasAspect($model, 'myAspect'); // Will return true
```

##### Example of aspects flow

```
$model = new MyModel;
AspectManager::addAspect($model, MyModel::AspectViewing);
// Further in code
if(AspectManager::hasAspect($model, MyModel::AspectViewing))
{
    echo "Viewing $model";
}
```

###### Using built-in aspects in sanitizer

The `EntityManagerInterface::AspectSaving` is set just before
saving and removed after save, regardless if it was successful
or not.

```
class MySanitizer implements SanitizerInterface
{
    public function read($model, $value)
    {
        return $value;
    }

    public function write($model, $value)
    {
        if(AspectManager::hasAspect($model, EntityManagerInterface::AspectSaving)
        {
            // Do some extensive computing
            return $value + 2;
        }
        return $value;
    }

}
```
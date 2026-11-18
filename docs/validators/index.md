# Validators

Keeping data safe and consistent is crucial for application stability.
To ensure proper types [sanitizers][sanitizers] were introduced, however
often when working with external user provided data, we need to check
and inform user if his input is fine.

Data validation can be performed by placing appropriate [annotation][annotation]
on model attribute.

### Standalone validator

Validation can be performed by using `Validator` class, by caling
`Validator->validate()` method. This will trigger all model validators,
and return `true` or `false` depending on validation result. Additionally
error messages can be obtained by calling `Validator->getErrors()`.

Example of using `Validator` class:

```
$validator = new Validator($model);
$isValid = $validator->validate(); // Returns boolean value
$errors = $validator->getErrors(); // Array with errors
```

### Incorporate validation into model

Validation methods can be added directly to model by either using
`ValidatableTrait` or by extending from pre-composed `Document` class.

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

This behavior is the same for `EntityManager` `EntityManager->save()` method
and also for pre-composed `Document` `Document->save()` method.

Example of using document `Document->save()`:

```
$saved = $model->save(); // Will return false if not valid
$errors = $model->getErrors(); // Will contain error messages if not valid
```

[sanitizers]: ../sanitizers/index.md
[annotation]: ../annotations/validator/index.md

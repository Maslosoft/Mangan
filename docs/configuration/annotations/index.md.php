<?php

use Maslosoft\Mangan\Annotations\I18NAnnotation;
use Maslosoft\Mangan\Command;
use Maslosoft\Mangan\Mangan;
use Maslosoft\Mangan\Model\Command\Roles;
use Maslosoft\Mangan\Model\Command\User;
use Maslosoft\Zamm\ShortNamer;
?>
<?php
ShortNamer::defaults()->md();
/* @var $mangan Mangan */
/* @var $i18n I18NAnnotation */
/* @var $user User */
/* @var $roles User */
$mangan = new ShortNamer(Mangan::class);
$i18n = new ShortNamer(I18NAnnotation::class);
$user = new ShortNamer(User::class);
$roles = new ShortNamer(Roles::class);
?>
<title>10. Annotations Defaults</title>

#Annotations Defaults

<?= $mangan; ?> allow You to set default (initial) values for annotations. This
allows You to change some behaviors globally, without changing code.

<p class="alert alert-warning">
This is somewhat advanced feature, misusing
it might cause problems.
</p>

<p class="alert alert-info">
    For technical reasons, annotations configuration can be set up only for default Mangan instance.<br />
    In other words, it cannot be model/connection dependent.
</p>

End of warnings :)

For instance, the behavior of international fields can be changed to
always return some value. So that instead of setting <?= $i18n->allowAny; ?> or <?= $i18n->allowDefault; ?>
for each field, this can be configured globally with option to override
for any property.

### Example for <?= $i18n; ?>

The properties <?= $i18n->allowAny; ?> and <?= $i18n->allowDefault; ?> can
be set on any annotation to set empty model field value to - respectively -
any language that has *some* value or to use *default* language value:

```
/**
 * @I18N(allowAny = true, allowDefault = true)
 */
public $title = '';
```

By default these properties are both false. <?= $mangan; ?> allows
You to change these default values at one go. The configuration
property for annotations default values is <?= $mangan->annotationsDefaults; ?>.

This needs to have keys with annotation class names, and values as a hashmap
matching keys to annotation class properties. The values will be applied
to any new annotation instance.

#### Example <?= $mangan->annotationsDefaults; ?> configuration

<p class="alert alert-success">
    It is recommended to use <code>::class</code> magic constant, eg <code>I18NAnnotation::class</code>
</p>

```
'annotationsDefaults' => [
    I18NAnnotation::class => [
        'allowAny' => true,
        'allowDefault' => true,
    ]
]
```

With such configuration, the <?= $i18n; ?> will always return any or default value.

To make exception or to make property immutable from default behavior, set these
values explicitly:

```
/**
* @I18N(allowAny = false, allowDefault = false)
*/
public $title = '';
```
# Annotations Defaults

`Mangan` allow You to set default (initial) values for annotations. This
allows You to change some behaviors globally, without changing code.

This is somewhat advanced feature, misusing
it might cause problems.

Annotation defaults are resolved from the default Mangan connection. They cannot
be model- or connection-dependent. Upstream metadata caches are process-wide: set
these defaults before metadata is first read and keep them consistent for a
worker lifetime. See [application servers](../../application-servers/index.md).

For instance, the behavior of international fields can be changed to
always return some value. So that instead of setting `I18NAnnotation->allowAny` or `I18NAnnotation->allowDefault`
for each field, this can be configured globally with option to override
for any property.

### Example for `I18NAnnotation`

The properties `I18NAnnotation->allowAny` and `I18NAnnotation->allowDefault` can
be set on any annotation to set empty model field value to - respectively -
any language that has *some* value or to use *default* language value:

```
/**
 * @I18N(allowAny = true, allowDefault = true)
 */
public $title = '';
```

By default these properties are both false. `Mangan` allows
You to change these default values at one go. The configuration
property for annotations default values is `Mangan->annotationsDefaults`.

This needs to have keys with annotation class names, and values as a hashmap
matching keys to annotation class properties. The values will be applied
to any new annotation instance.

#### Example `Mangan->annotationsDefaults` configuration

It is recommended to use `::class` magic constant, eg `I18NAnnotation::class`

```
'annotationsDefaults' => [
    I18NAnnotation::class => [
        'allowAny' => true,
        'allowDefault' => true,
    ]
]
```

With such configuration, the `I18NAnnotation` will always return any or default value.

To make exception or to make property immutable from default behavior, set these
values explicitly:

```
/**
* @I18N(allowAny = false, allowDefault = false)
*/
public $title = '';
```

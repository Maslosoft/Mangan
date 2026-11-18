# I18N (International) Annotation

This annotation indicates internationalized fields. This is not limited to text fields. Any kind of field can
be set to be internationalized, having value dependent on selected language.

It is possible to use fallback language from default application language,
any available value or both.

Use fallback to default language:
```
@I18N('allowDefault' = true)
```

Use fallback to value in any available language:
```
@I18N('allowAny' = true)
```

Use fallback to default language if set, or any other language if any value is available:
```
@I18N('allowDefault' = true, 'allowAny' = true)
```

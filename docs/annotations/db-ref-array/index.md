# Db Ref Array Annotation

DB reference array annotation is used to create reference to other
or same collection items.

All parameters are optional. However it is recommended to set
first parameter - default class for referenced objects.

It takes first parameter as class name or class literal, and
second parameter to indicate if it should be updated along with
parent model.

Most simple example - will allow many of any object types to be referenced:
```
@DbRefArray
```

Example of updatable DB reference:
```
@DbRefArray(ClassLiteral, true)
```

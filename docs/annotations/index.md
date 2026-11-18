# Annotations

Mangan uses Addendum DocBlock annotations on classes and public properties.
Models implement `Maslosoft\Addendum\Interfaces\AnnotatedInterface`, directly
or through a base class such as `Document`. Import class literals used in
annotations with ordinary PHP `use` statements.

| Concern | Annotation |
| --- | --- |
| Embedded objects | [`@Embedded`](embedded/index.md), [`@EmbeddedArray`](embedded-array/index.md) |
| Referenced documents | `@DbRef`, [`@DbRefArray`](db-ref-array/index.md) |
| Queried relations | [`@Related`](related/index.md), [`@RelatedArray`](related-array/index.md) |
| Internationalized values | [`@I18N`](i18n/index.md) |
| Primary keys | [`@PrimaryKey`](primary-key/index.md) |
| Database indexes | [`@Index`](index/index.md) |
| Value conversion | [`@Sanitizer`](sanitizer/index.md), [`@SanitizerArray`](sanitizer-array/index.md) |
| Empty values | [`@Nullable`](nullable/index.md) |
| Validation | [`@Validator`](validator/index.md) and named validator annotations |
| Query constraints | [`@Scope`](scope/index.md) |

Additional useful annotations include `@CollectionName`, `@ConnectionId`,
`@Persistent(false)`, `@Safe`, `@Unsafe`, and `@Alias`. Annotations configure
mapping behavior; they do not create PHP native property types.

# Data sanitization

Sanitizers convert values while reading from and writing to model representations.
They are separate from validators, which reject invalid values and report errors.

Scalar default values select common sanitizers. Explicit `@Sanitizer` annotations
are useful for BSON objects, custom conversion, and properties initialized to
null. `None` disables conversion. Native property types do not replace mapping
annotations for BSON dates, ObjectIds, or embedded documents.

| Value | Sanitizer guide |
| --- | --- |
| Boolean | [Boolean](boolean/index.md) |
| Integer | [Integer](integer/index.md) |
| Floating point | [Double](double/index.md) |
| String | [String](string/index.md) |
| BSON ObjectId | [ObjectId](objectid/index.md) |
| BSON date | [Date](date/index.md) |
| Unchanged | [None](none/index.md) |

Use [`@SanitizerArray`](../annotations/sanitizer-array/index.md) for arrays of a
single converted type. Use [`@Nullable`](../annotations/nullable/index.md) with
care: PHP-empty values include `0`, `'0'`, and `false`, so they can become null.
See [creating a sanitizer](creating-sanitizer/index.md) for custom conversions.

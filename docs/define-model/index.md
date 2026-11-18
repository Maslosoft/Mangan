# Defining models

Mangan stores public model properties and applies mapping annotations to
customize their representation. Choose the model style that fits the application:

- [Active documents](active-document/index.md) extend `Document` and expose
  persistence, finder, validation, language, and scenario methods.
- [Plain PHP models](plain/index.md) implement `AnnotatedInterface` and use
  separate `Finder`, `EntityManager`, and `Validator` services.
- [Composition](composing/index.md) adds selected traits and interfaces.
- [Embedded documents](embedding/index.md) store objects inside their owner.

Initialize persistent properties. Scalar defaults help select sanitizers;
explicit annotations handle BSON IDs, dates, embedded objects, and references.
Use `@Persistent(false)` for public properties that are not database fields.
See [annotations](../annotations/index.md) and [sanitization](../sanitizers/index.md).

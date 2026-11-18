# Mangan

Mangan maps PHP objects to MongoDB documents. Models can extend `Document` for
active-document methods or implement `AnnotatedInterface` and use a separate
`Finder`, `EntityManager`, and `Validator`.

This branch requires **PHP 8.5 or newer**. Install the MongoDB, GD, and Fileinfo
extensions and let Composer verify the complete dependency requirements:

```bash
composer require maslosoft/mangan
composer check-platform-reqs
```

Start with [Getting started](getting-started/index.md), then choose a topic:

- [Configuration and connections](configuration/index.md)
- [Defining models](define-model/index.md) and [annotations](annotations/index.md)
- [Queries and persistence](queries/index.md)
- [Validation](validators/index.md) and [sanitization](sanitizers/index.md)
- [Events](events/index.md)
- [Application servers and workers](application-servers/index.md)
- [Migration to PHP 8.5](upgrading/php-85/index.md)
- [Development and testing](development/index.md)

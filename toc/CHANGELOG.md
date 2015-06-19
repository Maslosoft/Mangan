# Change Log

## Verision 4 possibly backward incompatible changes:

Ranamed interfaces:
```php
Maslosoft\Mangan\Interfaces\IActiveDocument -> Maslosoft\Mangan\Interfaces\ActiveDocumentInterface
Maslosoft\Mangan\Interfaces\IActiveRecord -> Maslosoft\Mangan\Interfaces\ActiveRecordInterface
Maslosoft\Mangan\Interfaces\IWithCollectionName -> Maslosoft\Mangan\Interfaces\CollectionNameInterface
Maslosoft\Mangan\Interfaces\IWithCriteria -> Maslosoft\Mangan\Interfaces\CriteriaInterface
Maslosoft\Mangan\Interfaces\Decorators\Model\IModelDecorator -> Maslosoft\Mangan\Interfaces\Decorators\Model\ModelDecoratorInterface
Maslosoft\Mangan\Interfaces\Decorators\Property\IDecorator -> Maslosoft\Mangan\Interfaces\Decorators\Property\DecoratorInterface
Maslosoft\Mangan\Interfaces\IEntityManager -> Maslosoft\Mangan\Interfaces\EntityManagerInterface
Maslosoft\Mangan\Interfaces\Events\IEvent -> Maslosoft\Mangan\Interfaces\Events\EventInterface
Maslosoft\Mangan\Interfaces\Filters\Property\ITransformatorFilter -> Maslosoft\Mangan\Interfaces\Filters\Property\TransformatorFilterInterface
Maslosoft\Mangan\Interfaces\IFinder -> Maslosoft\Mangan\Interfaces\FinderInterface
Maslosoft\Mangan\Interfaces\Initializable -> Maslosoft\Mangan\Interfaces\InitInterface
Maslosoft\Mangan\Interfaces\I18NAble -> Maslosoft\Mangan\Interfaces\InternationalInterface
Maslosoft\Mangan\Interfaces\IModel -> Maslosoft\Mangan\Interfaces\ModelInterface
Maslosoft\Mangan\Interfaces\IOwnered -> Maslosoft\Mangan\Interfaces\OwneredInterface
Maslosoft\Mangan\Interfaces\Sanitizers\Property\ISanitizer -> Maslosoft\Mangan\Interfaces\Sanitizers\Property\SanitizerInterface
Maslosoft\Mangan\Interfaces\IScenarios -> Maslosoft\Mangan\Interfaces\ScenariosInterface
Maslosoft\Mangan\Interfaces\IScope -> Maslosoft\Mangan\Interfaces\ScopeInterface
Maslosoft\Mangan\Interfaces\IScopes -> Maslosoft\Mangan\Interfaces\ScopesInterface
Maslosoft\Mangan\Interfaces\ISimpleTree -> Maslosoft\Mangan\Interfaces\SimpleTreeInterface
Maslosoft\Mangan\Interfaces\Transformators\ITransformator -> Maslosoft\Mangan\Interfaces\Transformators\TransformatorInterface
Maslosoft\Mangan\Interfaces\ITrash -> Maslosoft\Mangan\Interfaces\TrashInterface
Maslosoft\Mangan\Interfaces\IValidatable -> Maslosoft\Mangan\Interfaces\ValidatableInterface
Maslosoft\Mangan\Interfaces\Validators\IValidator -> Maslosoft\Mangan\Interfaces\Validators\ValidatorInterface
Maslosoft\Mangan\Interfaces\Validators\IValidatorProxy -> Maslosoft\Mangan\Interfaces\Validators\ValidatorProxyInterface
```

## [Unreleased](https://github.com/Maslosoft/Mangan/tree/HEAD)

[Full Changelog](https://github.com/Maslosoft/Mangan/compare/3.1.5...HEAD)

**Fixed bugs:**

- Implement array instances updating for DbRef's [\#29](https://github.com/Maslosoft/Mangan/issues/29)

**Closed issues:**

- Alias PassThrough sanitizer [\#32](https://github.com/Maslosoft/Mangan/issues/32)

## [3.1.5](https://github.com/Maslosoft/Mangan/tree/3.1.5) (2015-04-30)

[Full Changelog](https://github.com/Maslosoft/Mangan/compare/3.0.24-alpha...3.1.5)

## [3.0.24-alpha](https://github.com/Maslosoft/Mangan/tree/3.0.24-alpha) (2015-04-30)

[Full Changelog](https://github.com/Maslosoft/Mangan/compare/3.0.23-alpha...3.0.24-alpha)

**Closed issues:**

- Check sanitizers [\#31](https://github.com/Maslosoft/Mangan/issues/31)

## [3.0.23-alpha](https://github.com/Maslosoft/Mangan/tree/3.0.23-alpha) (2015-04-30)

[Full Changelog](https://github.com/Maslosoft/Mangan/compare/3.1.4-alpha...3.0.23-alpha)

**Fixed bugs:**

- UniqueValidator i18n [\#2](https://github.com/Maslosoft/Mangan/issues/2)

**Closed issues:**

- Use decorators and sanitizers in criteria [\#12](https://github.com/Maslosoft/Mangan/issues/12)

## [3.1.4-alpha](https://github.com/Maslosoft/Mangan/tree/3.1.4-alpha) (2015-03-26)

[Full Changelog](https://github.com/Maslosoft/Mangan/compare/3.1.3-alpha...3.1.4-alpha)

**Closed issues:**

- Move all interfaces into `Interfaces\\*` namespace [\#27](https://github.com/Maslosoft/Mangan/issues/27)

## [3.1.3-alpha](https://github.com/Maslosoft/Mangan/tree/3.1.3-alpha) (2015-03-12)

[Full Changelog](https://github.com/Maslosoft/Mangan/compare/3.1.2-alpha...3.1.3-alpha)

## [3.1.2-alpha](https://github.com/Maslosoft/Mangan/tree/3.1.2-alpha) (2015-03-12)

[Full Changelog](https://github.com/Maslosoft/Mangan/compare/3.1.0-alpha...3.1.2-alpha)

**Fixed bugs:**

- Need infrastructure to notify nested objects [\#26](https://github.com/Maslosoft/Mangan/issues/26)

## [3.1.0-alpha](https://github.com/Maslosoft/Mangan/tree/3.1.0-alpha) (2015-03-10)

[Full Changelog](https://github.com/Maslosoft/Mangan/compare/3.0.22-alpha...3.1.0-alpha)

**Closed issues:**

- Add alias annotation [\#18](https://github.com/Maslosoft/Mangan/issues/18)

## [3.0.22-alpha](https://github.com/Maslosoft/Mangan/tree/3.0.22-alpha) (2015-03-09)

[Full Changelog](https://github.com/Maslosoft/Mangan/compare/3.0.21-alpha...3.0.22-alpha)

## [3.0.21-alpha](https://github.com/Maslosoft/Mangan/tree/3.0.21-alpha) (2015-03-04)

[Full Changelog](https://github.com/Maslosoft/Mangan/compare/3.0.20-alpha...3.0.21-alpha)

## [3.0.20-alpha](https://github.com/Maslosoft/Mangan/tree/3.0.20-alpha) (2015-02-27)

[Full Changelog](https://github.com/Maslosoft/Mangan/compare/3.0.19-alpha...3.0.20-alpha)

**Closed issues:**

- Sanitize arrays [\#17](https://github.com/Maslosoft/Mangan/issues/17)

## [3.0.19-alpha](https://github.com/Maslosoft/Mangan/tree/3.0.19-alpha) (2015-02-27)

[Full Changelog](https://github.com/Maslosoft/Mangan/compare/3.0.18-alpha...3.0.19-alpha)

## [3.0.18-alpha](https://github.com/Maslosoft/Mangan/tree/3.0.18-alpha) (2015-02-27)

[Full Changelog](https://github.com/Maslosoft/Mangan/compare/3.0.17-alpha...3.0.18-alpha)

**Closed issues:**

- Create EntityManager class [\#6](https://github.com/Maslosoft/Mangan/issues/6)

## [3.0.17-alpha](https://github.com/Maslosoft/Mangan/tree/3.0.17-alpha) (2015-02-27)

[Full Changelog](https://github.com/Maslosoft/Mangan/compare/3.0.16-alpha...3.0.17-alpha)

**Closed issues:**

- Create EventDispatcher class [\#10](https://github.com/Maslosoft/Mangan/issues/10)

- Migration guide from the readme does not excist [\#1](https://github.com/Maslosoft/Mangan/issues/1)

## [3.0.16-alpha](https://github.com/Maslosoft/Mangan/tree/3.0.16-alpha) (2015-02-25)

[Full Changelog](https://github.com/Maslosoft/Mangan/compare/3.0.15-alpha...3.0.16-alpha)

## [3.0.15-alpha](https://github.com/Maslosoft/Mangan/tree/3.0.15-alpha) (2015-02-18)

[Full Changelog](https://github.com/Maslosoft/Mangan/compare/3.0.14-alpha...3.0.15-alpha)

## [3.0.14-alpha](https://github.com/Maslosoft/Mangan/tree/3.0.14-alpha) (2015-02-18)

[Full Changelog](https://github.com/Maslosoft/Mangan/compare/3.0.13-alpha...3.0.14-alpha)

## [3.0.13-alpha](https://github.com/Maslosoft/Mangan/tree/3.0.13-alpha) (2015-02-10)

[Full Changelog](https://github.com/Maslosoft/Mangan/compare/3.0.12-alpha...3.0.13-alpha)

**Closed issues:**

- Document array transformator [\#24](https://github.com/Maslosoft/Mangan/issues/24)

- JSON array Transformator [\#23](https://github.com/Maslosoft/Mangan/issues/23)

- ITransformatorInterface [\#22](https://github.com/Maslosoft/Mangan/issues/22)

## [3.0.12-alpha](https://github.com/Maslosoft/Mangan/tree/3.0.12-alpha) (2015-02-09)

[Full Changelog](https://github.com/Maslosoft/Mangan/compare/3.0.11-alpha...3.0.12-alpha)

## [3.0.11-alpha](https://github.com/Maslosoft/Mangan/tree/3.0.11-alpha) (2015-02-09)

[Full Changelog](https://github.com/Maslosoft/Mangan/compare/3.0.10-alpha...3.0.11-alpha)

**Fixed bugs:**

- Fix typo in method name `setLangauges` [\#20](https://github.com/Maslosoft/Mangan/issues/20)

- I18N sometimes generates garbage arrays [\#19](https://github.com/Maslosoft/Mangan/issues/19)

**Closed issues:**

- Rename FromRawArray and FromToDocument [\#16](https://github.com/Maslosoft/Mangan/issues/16)

- Create Finder class [\#8](https://github.com/Maslosoft/Mangan/issues/8)

## [3.0.10-alpha](https://github.com/Maslosoft/Mangan/tree/3.0.10-alpha) (2015-01-22)

[Full Changelog](https://github.com/Maslosoft/Mangan/compare/3.0.9-alpha...3.0.10-alpha)

## [3.0.9-alpha](https://github.com/Maslosoft/Mangan/tree/3.0.9-alpha) (2015-01-22)

[Full Changelog](https://github.com/Maslosoft/Mangan/compare/3.0.8-alpha...3.0.9-alpha)

**Closed issues:**

- Db Refs [\#4](https://github.com/Maslosoft/Mangan/issues/4)

## [3.0.8-alpha](https://github.com/Maslosoft/Mangan/tree/3.0.8-alpha) (2015-01-13)

[Full Changelog](https://github.com/Maslosoft/Mangan/compare/3.0.7-alpha...3.0.8-alpha)

## [3.0.7-alpha](https://github.com/Maslosoft/Mangan/tree/3.0.7-alpha) (2015-01-13)

[Full Changelog](https://github.com/Maslosoft/Mangan/compare/3.0.6-alpha...3.0.7-alpha)

## [3.0.6-alpha](https://github.com/Maslosoft/Mangan/tree/3.0.6-alpha) (2015-01-12)

[Full Changelog](https://github.com/Maslosoft/Mangan/compare/3.0.5-alpha...3.0.6-alpha)

**Closed issues:**

- Change Finder constructor param to accept model [\#13](https://github.com/Maslosoft/Mangan/issues/13)

## [3.0.5-alpha](https://github.com/Maslosoft/Mangan/tree/3.0.5-alpha) (2015-01-10)

[Full Changelog](https://github.com/Maslosoft/Mangan/compare/3.0.4-alpha...3.0.5-alpha)

## [3.0.4-alpha](https://github.com/Maslosoft/Mangan/tree/3.0.4-alpha) (2015-01-07)

[Full Changelog](https://github.com/Maslosoft/Mangan/compare/3.0.3-alpha...3.0.4-alpha)

## [3.0.3-alpha](https://github.com/Maslosoft/Mangan/tree/3.0.3-alpha) (2014-12-30)

[Full Changelog](https://github.com/Maslosoft/Mangan/compare/3.0.2-alpha...3.0.3-alpha)

**Closed issues:**

- Create @CollectionName annotation [\#7](https://github.com/Maslosoft/Mangan/issues/7)

- Create sanitizers infrastructure [\#5](https://github.com/Maslosoft/Mangan/issues/5)

## [3.0.2-alpha](https://github.com/Maslosoft/Mangan/tree/3.0.2-alpha) (2014-12-29)

[Full Changelog](https://github.com/Maslosoft/Mangan/compare/3.0.1-alpha...3.0.2-alpha)

## [3.0.1-alpha](https://github.com/Maslosoft/Mangan/tree/3.0.1-alpha) (2014-12-29)

[Full Changelog](https://github.com/Maslosoft/Mangan/compare/2.1.1...3.0.1-alpha)

**Closed issues:**

- Rename sanitizers methods [\#11](https://github.com/Maslosoft/Mangan/issues/11)

**Merged pull requests:**

- Add EMongoDb::$enableProfiling param [\#3](https://github.com/Maslosoft/Mangan/pull/3) ([wapmorgan](https://github.com/wapmorgan))

## [2.1.1](https://github.com/Maslosoft/Mangan/tree/2.1.1) (2014-06-02)

[Full Changelog](https://github.com/Maslosoft/Mangan/compare/2.1.0...2.1.1)

## [2.1.0](https://github.com/Maslosoft/Mangan/tree/2.1.0) (2014-04-24)

[Full Changelog](https://github.com/Maslosoft/Mangan/compare/2.0.1...2.1.0)

## [2.0.1](https://github.com/Maslosoft/Mangan/tree/2.0.1) (2013-12-04)

[Full Changelog](https://github.com/Maslosoft/Mangan/compare/2.0.0...2.0.1)

## [2.0.0](https://github.com/Maslosoft/Mangan/tree/2.0.0) (2013-10-30)

[Full Changelog](https://github.com/Maslosoft/Mangan/compare/v1.3.6.3...2.0.0)

## [v1.3.6.3](https://github.com/Maslosoft/Mangan/tree/v1.3.6.3) (2011-02-16)

[Full Changelog](https://github.com/Maslosoft/Mangan/compare/v1.3.6.2...v1.3.6.3)

## [v1.3.6.2](https://github.com/Maslosoft/Mangan/tree/v1.3.6.2) (2011-02-11)

[Full Changelog](https://github.com/Maslosoft/Mangan/compare/v1.3.6.1...v1.3.6.2)

## [v1.3.6.1](https://github.com/Maslosoft/Mangan/tree/v1.3.6.1) (2011-02-10)

[Full Changelog](https://github.com/Maslosoft/Mangan/compare/v1.3.6...v1.3.6.1)

## [v1.3.6](https://github.com/Maslosoft/Mangan/tree/v1.3.6) (2011-01-28)

[Full Changelog](https://github.com/Maslosoft/Mangan/compare/v1.3.5...v1.3.6)

## [v1.3.5](https://github.com/Maslosoft/Mangan/tree/v1.3.5) (2011-01-07)

[Full Changelog](https://github.com/Maslosoft/Mangan/compare/v1.3.4.1...v1.3.5)

## [v1.3.4.1](https://github.com/Maslosoft/Mangan/tree/v1.3.4.1) (2011-01-05)

[Full Changelog](https://github.com/Maslosoft/Mangan/compare/v1.3.4...v1.3.4.1)

## [v1.3.4](https://github.com/Maslosoft/Mangan/tree/v1.3.4) (2010-12-31)

[Full Changelog](https://github.com/Maslosoft/Mangan/compare/v1.3.3...v1.3.4)

## [v1.3.3](https://github.com/Maslosoft/Mangan/tree/v1.3.3) (2010-12-25)

[Full Changelog](https://github.com/Maslosoft/Mangan/compare/v1.3.2...v1.3.3)

## [v1.3.2](https://github.com/Maslosoft/Mangan/tree/v1.3.2) (2010-12-23)

[Full Changelog](https://github.com/Maslosoft/Mangan/compare/v1.3.1...v1.3.2)

## [v1.3.1](https://github.com/Maslosoft/Mangan/tree/v1.3.1) (2010-12-21)

[Full Changelog](https://github.com/Maslosoft/Mangan/compare/v1.3...v1.3.1)

## [v1.3](https://github.com/Maslosoft/Mangan/tree/v1.3) (2010-12-16)

[Full Changelog](https://github.com/Maslosoft/Mangan/compare/v1.2.3...v1.3)

## [v1.2.3](https://github.com/Maslosoft/Mangan/tree/v1.2.3) (2010-12-16)

[Full Changelog](https://github.com/Maslosoft/Mangan/compare/v1.2.2...v1.2.3)

## [v1.2.2](https://github.com/Maslosoft/Mangan/tree/v1.2.2) (2010-12-15)

[Full Changelog](https://github.com/Maslosoft/Mangan/compare/v1.1...v1.2.2)

## [v1.1](https://github.com/Maslosoft/Mangan/tree/v1.1) (2010-12-08)

[Full Changelog](https://github.com/Maslosoft/Mangan/compare/v1.0.2...v1.1)

## [v1.0.2](https://github.com/Maslosoft/Mangan/tree/v1.0.2) (2010-12-04)

[Full Changelog](https://github.com/Maslosoft/Mangan/compare/v1.0...v1.0.2)

## [v1.0](https://github.com/Maslosoft/Mangan/tree/v1.0) (2010-12-02)

[Full Changelog](https://github.com/Maslosoft/Mangan/compare/v1.0.8...v1.0)

## [v1.0.8](https://github.com/Maslosoft/Mangan/tree/v1.0.8) (2010-11-26)



\* *This Change Log was automatically generated by [github_changelog_generator](https://github.com/skywinder/Github-Changelog-Generator)*
# Scope Annotation

Annotation can be used to add extra scope
for model. Scopes will add extra criteria when
finding, updating and removing model.

The scope is a simple class implementing `ScopeInterface`.
The `ScopeInterface` method `getCriteria` must return `CriteriaInterface`,
most likely `Criteria` instance.

Usage:

```
@Scope(ClassName)
```

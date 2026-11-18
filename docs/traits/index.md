# Traits

Mangan's traits let plain models opt into convenience methods. Implement the
corresponding interface as well: Mangan uses interfaces to detect capabilities.
`Document` and `EmbeddedDocument` already compose common capabilities.

| Trait | Purpose |
| --- | --- |
| `Traits\EntityManagerTrait` | Save, update, and delete convenience methods |
| `Traits\FinderTrait` | Finder convenience methods |
| `Traits\ValidatableTrait` | Validation and error messages |
| `Traits\ScenariosTrait` | Current validation/persistence scenario |
| `Traits\AspectsTrait` | Temporary processing aspects |
| `Traits\I18NAbleTrait` | Language selection and localized values |
| `Traits\CollectionNameTrait` | Collection naming support |
| `Traits\Model\TrashableTrait` | Trash/restore behavior |

All names above are under `Maslosoft\Mangan`. Consult the trait's source for
required interfaces and methods before mixing it into a custom base class.
Avoid redeclaring private cache properties supplied by a trait. Model instances
and their trait state should remain within the application context that owns them.

<?php

/**
 * This software package is licensed under AGPL or Commercial license.
 *
 * @package   maslosoft/mangan
 * @licence   AGPL or Commercial
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com>
 * @copyright Copyright (c) Maslosoft
 * @copyright Copyright (c) Others as mentioned in code
 * @link      https://maslosoft.com/mangan/
 */

namespace Maslosoft\Mangan\Annotations;

use Maslosoft\Addendum\Helpers\ParamsExpander;
use Maslosoft\Mangan\Interfaces\SortInterface;
use Maslosoft\Mangan\Meta\ManganPropertyAnnotation;
use Maslosoft\Mangan\Meta\RelatedMeta;

/**
 * RelatedOrderingAnnotation
 *
 * Use this annotation to store order of related documents.
 * This should be used on same field as Related or RelatedArray annotations are applied.
 * This will apply incrementing values on selected field.
 *
 * Will also apply sort if not set.
 *
 * Compact notation:
 *
 * ```
 * @RelatedOrdering('order')
 * ```
 *
 * Compact notation with specified order:
 *
 * ```
 * @RelatedOrdering('order', SortInterface::SortAsc)
 * ```
 *
 * Extended notation:
 *
 * ```
 * @RelatedOrdering('orderField' = 'order', 'direction' = SortInterface::SortAsc)
 * ```
 *
 * @Conflicts('Embedded')
 * @Conflicts('EmbeddedArray')
 * @Conflicts('DbRef')
 * @Conflicts('DbRefArray')
 *
 * @see      SortInterface
 * @template RelatedOrdering('${orderField}')
 * @author   Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class RelatedOrderingAnnotation extends ManganPropertyAnnotation
{

	public $value;

	public $orderField;

	public $direction;

	public function init()
	{
		$data = ParamsExpander::expand($this, ['orderField', 'direction']);
		if (empty($this->getEntity()->related))
		{
			$relMeta = new RelatedMeta();
		}
		else
		{
			$relMeta = $this->getEntity()->related;
		}

		foreach ($data as $key => $val)
		{
			$relMeta->$key = $val;
		}

		// Ensure array
		if (!is_array($relMeta->sort))
		{
			$relMeta->sort = [];
		}

		// Sort might be set by @RelatedAnnotation (defaults to _id)
		// or or by many @RelatedOrdering to sort on multiple fields.
		// Place current order in front if only sorted by _id
		// or append, placing _id at the end.
		if (count($relMeta->sort) === 1 && array_key_exists('_id', $relMeta->sort))
		{
			$relMeta->sort = array_merge([$relMeta->orderField => $relMeta->direction], $relMeta->sort);
		}
		else
		{
			$idSort = null;
			if (array_key_exists('_id', $relMeta->sort))
			{
				$idSort = $relMeta->sort['_id'];
				unset($relMeta->sort['_id']);
			}
			$relMeta->sort = array_merge($relMeta->sort, [$relMeta->orderField => $relMeta->direction]);
			if (null !== $idSort)
			{
				$relMeta->sort = array_merge($relMeta->sort, ['_id' => $idSort]);
			}
		}

		$this->getEntity()->related = $relMeta;
	}

}

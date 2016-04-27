<?php

/**
 * This software package is licensed under AGPL or Commercial license.
 *
 * @package maslosoft/mangan
 * @licence AGPL or Commercial
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com>
 * @copyright Copyright (c) Maslosoft
 * @copyright Copyright (c) Others as mentioned in code
 * @link http://maslosoft.com/mangan/
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
 * RelatedOrdering('order')
 *
 * Extended notation:
 *
 * RelatedOrdering(orderField = 'order')
 *
 *
 * @Conflicts('Embedded')
 * @Conflicts('EmbeddedArray')
 * @Conflicts('DbRef')
 * @Conflicts('DbRefArray')
 *
 * @template RelatedOrdering('${orderField}')
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class RelatedOrderingAnnotation extends ManganPropertyAnnotation
{

	public $value;
	public $orderField;

	public function init()
	{
		$data = ParamsExpander::expand($this, ['orderField']);
		if (empty($this->_entity->related))
		{
			$relMeta = new RelatedMeta();
		}
		else
		{
			$relMeta = $this->_entity->related;
		}
		foreach ($data as $key => $val)
		{
			$relMeta->$key = $val;
		}
		if (empty($relMeta->sort))
		{
			$relMeta->sort = [
				$relMeta->orderField => SortInterface::SortAsc
			];
		}
		$this->_entity->related = $relMeta;
	}

}

<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
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

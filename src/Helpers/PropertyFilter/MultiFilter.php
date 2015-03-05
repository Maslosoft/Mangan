<?php

/**
 * This software package is licensed under New BSD license.
 *
 * @package maslosoft/mangan
 * @licence New BSD
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com>
 * @copyright Copyright (c) Maslosoft
 * @copyright Copyright (c) Others as mentioned in code
 * @link http://maslosoft.com/mangan/
 */

namespace Maslosoft\Mangan\Helpers\PropertyFilter;

use Maslosoft\Mangan\Meta\DocumentPropertyMeta;
use Maslosoft\Mangan\Transformers\Filters\ITransformatorFilter;

/**
 * MultiFilter
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class MultiFilter implements ITransformatorFilter
{

	/**
	 * Decorators
	 * @var ITransformatorFilter[]
	 */
	private $_filters = [];

	/**
	 *
	 * @param ITransformatorFilter[] $decorators
	 */
	public function __construct($decorators)
	{
		$this->_filters = $decorators;
	}

	public function fromModel($model, DocumentPropertyMeta $fieldMeta)
	{
		foreach ($this->_filters as $filter)
		{
			/* @var $filter ITransformatorFilter */
			if (!$filter->fromModel($model, $fieldMeta))
			{
				return false;
			}
		}
		return true;
	}

	public function toModel($model, DocumentPropertyMeta $fieldMeta)
	{
		foreach ($this->_filters as $filter)
		{
			/* @var $filter ITransformatorFilter */
			if (!$filter->toModel($model, $fieldMeta))
			{
				return false;
			}
		}
		return true;
	}

}

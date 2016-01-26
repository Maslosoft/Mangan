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

namespace Maslosoft\Mangan\Helpers\PropertyFilter;

use Maslosoft\Mangan\Interfaces\Filters\Property\TransformatorFilterInterface;
use Maslosoft\Mangan\Meta\DocumentPropertyMeta;

/**
 * MultiFilter
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class MultiFilter implements TransformatorFilterInterface
{

	/**
	 * Filters
	 * @var TransformatorFilterInterface[]
	 */
	private $filters = [];

	/**
	 *
	 * @param TransformatorFilterInterface[] $filters
	 */
	public function __construct($filters)
	{
		$this->filters = $filters;
	}

	public function fromModel($model, DocumentPropertyMeta $fieldMeta)
	{
		foreach ($this->filters as $filter)
		{
			/* @var $filter TransformatorFilterInterface */
			if (!$filter->fromModel($model, $fieldMeta))
			{
				return false;
			}
		}
		return true;
	}

	public function toModel($model, DocumentPropertyMeta $fieldMeta)
	{
		foreach ($this->filters as $filter)
		{
			/* @var $filter TransformatorFilterInterface */
			if (!$filter->toModel($model, $fieldMeta))
			{
				return false;
			}
		}
		return true;
	}

}

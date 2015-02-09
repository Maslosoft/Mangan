<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
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

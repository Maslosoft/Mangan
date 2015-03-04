<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Criteria;

use Maslosoft\Addendum\Interfaces\IAnnotated;
use Maslosoft\Mangan\Transformers\CriteriaArray;

/**
 * This class is used to decorate and sanitize conditions.
 * This should not be used directly. This is internal component of Criteria.
 *
 * @internal Criteria sub component
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ConditionDecorator
{

	/**
	 * Model instance
	 * @var IAnnotated
	 */
	private $model = null;

	public function __construct(IAnnotated $model = null)
	{
		if(!$model || !$model instanceof IAnnotated)
		{
			return;
		}
		$className = get_class($model);
		$this->model = new $className;
	}

	public function decorate($field, $value)
	{
		if(!$this->model)
		{
			return [
				$field => $value
			];
		}
		$this->model->$field = $value;
		$data = CriteriaArray::fromModel($this->model, [$field]);
		return $this->_flatten($field, $this->model->$field, $data[$field]);
	}

	private function _flatten($field, $srcValue, $data)
	{
		$value = $data;
		while(is_array($value))
		{
			$key = key($value);
			$value = $value[$key];
			$field = "$field.$key";
			if($srcValue === $value)
			{
				break;
			}
		}
		
		
		return [
			$field => $value
		];
	}
}

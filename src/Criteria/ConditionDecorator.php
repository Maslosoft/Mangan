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

namespace Maslosoft\Mangan\Criteria;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Interfaces\ConditionDecoratorInterface;
use Maslosoft\Mangan\Transformers\CriteriaArray;

/**
 * This class is used to decorate and sanitize conditions.
 * This should not be used directly. This is internal component of Criteria.
 *
 * @internal Criteria sub component
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ConditionDecorator implements ConditionDecoratorInterface
{

	/**
	 * Model instance
	 * @var AnnotatedInterface
	 */
	private $model = null;

	public function __construct(AnnotatedInterface $model = null)
	{
		if (!$model || !$model instanceof AnnotatedInterface)
		{
			return;
		}
		$className = get_class($model);
		$this->model = new $className;
	}

	public function decorate($field, $value = null)
	{
		if (!$this->model)
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
		while (is_array($value))
		{
			$key = key($value);
			$value = $value[$key];
			$field = "$field.$key";
			if ($srcValue === $value)
			{
				break;
			}
		}


		return [
			$field => $value
		];
	}

}

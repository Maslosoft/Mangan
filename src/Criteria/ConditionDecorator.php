<?php

/**
 * This software package is licensed under AGPL or Commercial license.
 *
 * @package maslosoft/mangan
 * @licence AGPL or Commercial
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com>
 * @copyright Copyright (c) Maslosoft
 * @copyright Copyright (c) Others as mentioned in code
 * @link https://maslosoft.com/mangan/
 */

namespace Maslosoft\Mangan\Criteria;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Interfaces\ConditionDecoratorInterface;
use Maslosoft\Mangan\Meta\ManganMeta;
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

	/**
	 * Metadata
	 * @var ManganMeta
	 */
	private $meta = null;

	public function __construct(AnnotatedInterface $model = null)
	{
		if (!$model || !$model instanceof AnnotatedInterface)
		{
			return;
		}
		// Clone is to prevent possible required constructor params issues
		$this->model = clone $model;
		$this->meta = ManganMeta::create($this->model);
	}

	public function decorate($field, $value = null)
	{
		// 1. Do not decorate if empty model or dot notation
		// 2. Ignore non existing fields
		if (!$this->model || strstr($field, '.') || $this->meta->$field === false)
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
			// Flat value traverse
			foreach ($value as $key => $val)
			{
				if ($srcValue === $val)
				{
					$value = $value[$key];
					$field = "$field.$key";
					break 2;
				}
			}

			// Nested value
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

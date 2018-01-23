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
use Maslosoft\Mangan\Interfaces\Decorators\ConditionDecoratorTypeAwareInterface;
use Maslosoft\Mangan\Interfaces\Decorators\ConditionDecoratorTypeInterface;
use Maslosoft\Mangan\Interfaces\InternationalInterface;
use Maslosoft\Mangan\Meta\ManganMeta;
use Maslosoft\Mangan\Transformers\CriteriaArray;

/**
 * This class is used to decorate and sanitize conditions.
 * This should not be used directly. This is internal component of Criteria.
 *
 * @internal Criteria sub component
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ConditionDecorator implements ConditionDecoratorInterface, ConditionDecoratorTypeInterface
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

	private $decoratorType = CriteriaArray::class;

	public function __construct(AnnotatedInterface $model = null)
	{
		if (!$model || !$model instanceof AnnotatedInterface)
		{
			return;
		}
		// Clone is to prevent possible required constructor params issues
		$this->model = clone $model;
		$this->meta = ManganMeta::create($this->model);

		/**
		 * NOTE: This is a workaround for:
		 * https://github.com/Maslosoft/Mangan/issues/82
		 * Condition decorator possibly fails on non-first language decoration of I18N field. #82
		 *
		 * TODO Should not depend on I18N here.
		 */
		if($this->model instanceof InternationalInterface)
		{
			$this->model->setLanguages([$this->model->getLang()]);
		}
	}

	/**
	 * @return AnnotatedInterface
	 */
	public function getModel()
	{
		return $this->model;
	}

	/**
	 * @param string $decoratorType
	 * @return string
	 */
	public function setDecoratorType($decoratorType = CriteriaArray::class)
	{
		$this->decoratorType = $decoratorType;
		return $this;
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

		$dt = $this->decoratorType;

		$data = $dt::fromModel($this->model, [$field]);

		return $this->_flatten($field, $this->model->$field, $data[$field]);
	}

	private function _flatten($field, $srcValue, $data)
	{
		$value = $data;
		while (is_array($value))
		{
			if(empty($value))
			{
				break;
			}
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

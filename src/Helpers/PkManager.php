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

namespace Maslosoft\Mangan\Helpers;

use Maslosoft\Addendum\Interfaces\IAnnotated;
use Maslosoft\Mangan\Criteria;
use Maslosoft\Mangan\Exceptions\CriteriaException;
use Maslosoft\Mangan\Helpers\PkManager;
use Maslosoft\Mangan\Helpers\Sanitizer\Sanitizer;
use Maslosoft\Mangan\Meta\ManganMeta;
use MongoId;

/**
 * Primary key manager
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class PkManager
{

	/**
	 * Prepare multi pk criteria
	 * @param IAnnotated $model
	 * @param mixed[] $pkValues
	 * @param Criteria|null $criteria
	 */
	public static function prepareAll($model, $pkValues, Criteria $criteria = null)
	{
		if (null === $criteria)
		{
			$criteria = new Criteria();
		}
		$conditions = [];
		foreach ($pkValues as $pkValue)
		{
			$c = PkManager::prepare($model, $pkValue);
			foreach ($c->getConditions() as $field => $value)
			{
				$conditions[$field][] = $value;
			}
		}
		foreach ($conditions as $field => $value)
		{
			$criteria->addCond($field, 'in', $value);
		}
		return $criteria;
	}

	/**
	 * Prepare pk criteria from user provided data
	 * @param IAnnotated $model
	 * @param mixed|mixed[] $pkValue
	 * @return Criteria
	 * @throws CriteriaException
	 */
	public static function prepare(IAnnotated $model, $pkValue)
	{
		$pkField = ManganMeta::create($model)->type()->primaryKey? : '_id';
		$criteria = new Criteria();

		if (is_array($pkField))
		{
			foreach ($pkField as $name)
			{
				if (!array_key_exists($name, $pkValue))
				{
					throw new CriteriaException(sprintf('Composite primary key field `%s` not specied for model `%s`, required fields: `%s`', $name, get_class($model), implode('`, `', $pkField)));
				}
				self::_prepareField($model, $name, $pkValue[$name], $criteria);
			}
		}
		else
		{
			self::_prepareField($model, $pkField, $pkValue, $criteria);
		}
		return $criteria;
	}

	/**
	 * Create pk criteria from model data
	 * @param IAnnotated $model
	 * @return Criteria
	 */
	public static function prepareFromModel(IAnnotated $model)
	{
		return self::prepare($model, self::getFromModel($model));
	}

	/**
	 * Get primary key from model
	 * @param IAnnotated $model
	 * @return MongoId|mixed|mixed[]
	 */
	public static function getFromModel(IAnnotated $model)
	{
		$pkField = ManganMeta::create($model)->type()->primaryKey? : '_id';
		$pkValue = [];
		$sanitizer = new Sanitizer($model);
		if (is_array($pkField))
		{
			foreach ($pkField as $name)
			{
				$pkValue[$name] = $sanitizer->write($name, $model->$name);
			}
		}
		else
		{
			$pkValue = $sanitizer->write($pkField, $model->$pkField);
		}
		return $pkValue;
	}

	/**
	 * Apply pk value to model
	 * @param IAnnotated $model
	 * @param MongoId|mixed|mixed[] $pkValue
	 * @return type
	 * @throws CriteriaException
	 */
	public static function applyToModel(IAnnotated $model, $pkValue)
	{
		$pkField = ManganMeta::create($model)->type()->primaryKey? : '_id';
		$sanitizer = new Sanitizer($model);
		if (is_array($pkField))
		{
			foreach ($pkField as $name)
			{
				if (!array_key_exists($name, $pkValue))
				{
					throw new CriteriaException(sprintf('Composite primary key field `%s` not specied for model `%s`, required fields: `%s`', $name, get_class($model), implode('`, `', $pkField)));
				}
				$model->$name = $sanitizer->read($name, $pkValue[$name]);
			}
		}
		else
		{
			$model->$pkField = $sanitizer->read($pkField, $pkValue);
		}
		return $pkValue;
	}

	/**
	 * Create pk criteria for single field
	 * @param IAnnotated $model Model instance
	 * @param string $name
	 * @param mixed $value
	 * @param Criteria $criteria
	 */
	private static function _prepareField(IAnnotated $model, $name, $value, Criteria &$criteria)
	{
		$sanitizer = new Sanitizer($model);
		$criteria->addCond($name, '==', $sanitizer->write($name, $value));
	}

}

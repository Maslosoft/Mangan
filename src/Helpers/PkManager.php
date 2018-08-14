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

namespace Maslosoft\Mangan\Helpers;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Criteria;
use Maslosoft\Mangan\Exceptions\CriteriaException;
use Maslosoft\Mangan\Helpers\PkManager;
use Maslosoft\Mangan\Helpers\Sanitizer\Sanitizer;
use Maslosoft\Mangan\Interfaces\CriteriaInterface;
use Maslosoft\Mangan\Meta\ManganMeta;
use MongoId;
use UnexpectedValueException;

/**
 * Primary key manager
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class PkManager
{

	/**
	 * Prepare multi pk criteria
	 * @param AnnotatedInterface     $model
	 * @param mixed[]                $pkValues
	 * @param CriteriaInterface|null $criteria
	 * @return Criteria|CriteriaInterface|null
	 */
	public static function prepareAll($model, $pkValues, CriteriaInterface $criteria = null)
	{
		if (null === $criteria)
		{
			$criteria = new Criteria();
		}
		assert($criteria instanceof Criteria, new UnexpectedValueException(sprintf("Unsupported criteria class, currently only `%s` is supported, `%s` given", Criteria::class, get_class($criteria))));
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
	 * @param AnnotatedInterface $model
	 * @param mixed|mixed[] $pkValue
	 * @return Criteria
	 * @throws CriteriaException
	 */
	public static function prepare(AnnotatedInterface $model, $pkValue)
	{
		$pkField = self::getPkKeys($model);
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
	 * @param AnnotatedInterface $model
	 * @return Criteria
	 */
	public static function prepareFromModel(AnnotatedInterface $model)
	{
		return self::prepare($model, self::getFromModel($model));
	}

	/**
	 * Get primary key from model
	 * @param AnnotatedInterface $model
	 * @return MongoId|mixed|mixed[]
	 */
	public static function getFromModel(AnnotatedInterface $model)
	{
		$pkField = self::getPkKeys($model);
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
	 * Get primary key(s).
	 *
	 * Might return single string value for one primary key, or array
	 * for composite keys.
	 *
	 *
	 * @param AnnotatedInterface $model
	 * @return string|string[]
	 */
	public static function getPkKeys(AnnotatedInterface $model)
	{
		return ManganMeta::create($model)->type()->primaryKey ?: '_id';
	}

	/**
	 * Get pk criteria from raw array
	 * @param mixed[] $data
	 * @param AnnotatedInterface $model
	 * @return mixed[]
	 */
	public static function getFromArray($data, AnnotatedInterface $model)
	{
		$pkField = ManganMeta::create($model)->type()->primaryKey ?: '_id';
		$pkValue = [];
		$sanitizer = new Sanitizer($model);
		if (is_array($pkField))
		{
			foreach ($pkField as $name)
			{
				$pkValue[$name] = $sanitizer->write($name, $data[$name]);
			}
		}
		else
		{
			$pkValue = $sanitizer->write($pkField, $data[$pkField]);
		}
		return $pkValue;
	}

	/**
	 * Apply pk value to model
	 * @param AnnotatedInterface $model
	 * @param MongoId|mixed|mixed[] $pkValue
	 * @return mixed Primary key value
	 * @throws CriteriaException
	 */
	public static function applyToModel(AnnotatedInterface $model, $pkValue)
	{
		$pkField = self::getPkKeys($model);

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
	 * Compare primary keys. For both params primary keys values or models can be used.
	 * Example use:
	 * <pre>
	 * <code>
	 * $model = new Model();
	 * $pk = ['_id' => new MongoId()];
	 * PkManager::compare($model, $pk);
	 *
	 * $pk1 = ['keyOne' => 1, 'keyTwo' => 2];
	 * $pk2 = ['keyOne' => 1, 'keyTwo' => 2];;
	 * PkManager::compare($pk1, $pk2);
	 *
	 * $model1 = new Model();
	 * $model2 = new Model();
	 * PkManager::compare($model1, $model2);
	 *
	 * </code>
	 * </pre>
	 * @param AnnotatedInterface|mixed[] $source
	 * @param AnnotatedInterface|mixed[] $target
	 * @return boolean true if pk's points to same document
	 */
	public static function compare($source, $target)
	{
		// Check if both params are models
		if ($source instanceof AnnotatedInterface && $target instanceof AnnotatedInterface)
		{
			// If different types return false
			if (!$source instanceof $target)
			{
				return false;
			}
		}

		$src = self::_compareNormalize($source);
		$trg = self::_compareNormalize($target);

		// Different pk's
		if (count($src) !== count($trg))
		{
			return false;
		}

		// Different pk keys
		if (array_keys($src) !== array_keys($trg))
		{
			return false;
		}

		// Compare values
		foreach ($src as $name => $srcVal)
		{
			// This is safe as keys are checked previously
			$trgVal = $trg[$name];

			// Special case for mongo id
			if ($srcVal instanceof \MongoId || $trgVal instanceof \MongoId)
			{
				if ((string) $srcVal !== (string) $trgVal)
				{
					return false;
				}
				continue;
			}

			// Finally compare values
			if ($srcVal !== $trgVal)
			{
				return false;
			}
		}

		return true;
	}

	private static function _compareNormalize($value)
	{
		if ($value instanceof AnnotatedInterface)
		{
			$value = self::getFromModel($value);
		}

		// Simple pk
		if (!is_array($value))
		{
			return [$value];
		}

		// Composite pk
		return $value;
	}

	/**
	 * Create pk criteria for single field
	 * @param AnnotatedInterface $model Model instance
	 * @param string $name
	 * @param mixed $value
	 * @param Criteria $criteria
	 */
	private static function _prepareField(AnnotatedInterface $model, $name, $value, Criteria &$criteria)
	{
		$sanitizer = new Sanitizer($model);
		$criteria->addCond($name, '==', $sanitizer->write($name, $value));
	}

}

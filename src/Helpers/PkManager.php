<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Helpers;

use Maslosoft\Mangan\Criteria;
use Maslosoft\Mangan\Exceptions\CriteriaException;
use Maslosoft\Mangan\Helpers\Sanitizer\Sanitizer;
use Maslosoft\Mangan\Meta\ManganMeta;

/**
 * Primary key manager
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class PkManager
{

	/**
	 * Prepare pk criteria from user provided data
	 * @param IAnnotated $model
	 * @param mixed|mixed[] $pkValue
	 * @return Criteria
	 * @throws CriteriaException
	 */
	public static function prepare($model, $pkValue)
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
	public static function prepareFromModel($model)
	{
		$pkField = ManganMeta::create($model)->type()->primaryKey? : '_id';
		$pkValue = [];
		if (is_array($pkField))
		{
			foreach ($pkField as $name)
			{
				$pkValue[$name] = $model->$name;
			}
		}
		else
		{
			$pkValue = $model->$pkField;
		}
		return self::prepare($model, $pkValue);
	}

	/**
	 * Create pk criteria for single field
	 * @param string $name
	 * @param mixed $value
	 * @param Criteria $criteria
	 */
	private static function _prepareField($model, $name, $value, Criteria &$criteria)
	{
		$sanitizer = new Sanitizer($model);
		$criteria->$name = $sanitizer->write($name, $value);
	}

}

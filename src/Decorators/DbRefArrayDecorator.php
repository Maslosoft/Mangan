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

namespace Maslosoft\Mangan\Decorators;

use Maslosoft\Mangan\Finder;
use Maslosoft\Mangan\Helpers\DbRefManager;
use Maslosoft\Mangan\Meta\ManganMeta;
use Maslosoft\Mangan\Model\DbRef;
use Maslosoft\Mangan\Transformers\RawArray;

/**
 * DbRefArray
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class DbRefArrayDecorator implements IDecorator
{

	public function read($model, $name, &$dbValues, $transformatorClass = ITransformator::class)
	{
		if (!$dbValues)
		{
			$fieldMeta = ManganMeta::create($model)->field($name);
			$model->$name = $fieldMeta->default;
			return;
		}
		/**
		 * TODO Optimize to retrieve documents with one query by Criteria $in.
		 * NOTE: Documents must be sorted as $dbRefs, however mongo does not guarantiee sorting.
		 * This require sorting in php.
		 * If document has composite key this must be taken care too while comparision for sorting is made.
		 */
		$refs = [];
		foreach ($dbValues as $key => $dbValue)
		{
			$dbValue['_class'] = DbRef::class;
			$dbRef = $transformatorClass::toModel($dbValue);
			/* @var $dbRef DbRef */
			$referenced = new $dbRef->class;
			$found = (new Finder($referenced))->findByPk($dbRef->pk);
			if(!$found)
			{
				continue;
			}
			$refs[$key] = $found;
		}
		$model->$name = $refs;
	}

	public function write($model, $name, &$dbValue, $transformatorClass = ITransformator::class)
	{
		$fieldMeta = ManganMeta::create($model)->field($name);
		$dbValue = $fieldMeta->default;
		
		// Empty
		if (!$model->$name)
		{
			return;
		}

		// Bogus data
		if (!is_array($model->$name))
		{
			return;
		}

		// Store DbRefs and optionally referenced model
		foreach ($model->$name as $key => $referenced)
		{
			$dbRef = DbRefManager::extractRef($model, $name, $referenced);
			if ($fieldMeta->dbRef->updatable)
			{
				DbRefManager::save($referenced, $dbRef);
			}
			$dbValue[$key] = $transformatorClass::fromModel($dbRef, false);
		}
	}

}

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

namespace Maslosoft\Mangan\Decorators;

use Maslosoft\Mangan\Helpers\DbRefManager;
use Maslosoft\Mangan\Helpers\PkManager;
use Maslosoft\Mangan\Helpers\RawFinder;
use Maslosoft\Mangan\Interfaces\Decorators\Property\IDecorator;
use Maslosoft\Mangan\Interfaces\Transformators\ITransformator;
use Maslosoft\Mangan\Meta\ManganMeta;
use Maslosoft\Mangan\Model\DbRef;

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
		 * NOTE: Documents must be sorted as $dbRefs,
		 * however mongo does not guarantiee sorting by list of id's.
		 * This require sorting in php.
		 * If document has composite key this must be taken care too
		 * while comparision for sorting is made.
		 */
		$refs = [];
		$unsortedRefs = [];
		$pks = [];
		$sort = [];

		// Collect primary keys
		foreach ($dbValues as $key => $dbValue)
		{
			$dbValue['_class'] = DbRef::class;
			$dbRef = $transformatorClass::toModel($dbValue);

			// Collect keys separatelly for each type
			$pks[$dbRef->class][$key] = $dbRef->pk;
			$sort[$key] = $dbRef->pk;
		}

		// Fetch all types of db ref's en masse
		$i = 0;
		$unsortedPks = [];
		foreach ($pks as $referenced => $pkValues)
		{
			// Find all referenced documents
			$refModel = new $referenced;
			$found = (new RawFinder($refModel))->findAllByPk($pkValues);

			if (!$found)
			{
				continue;
			}
			foreach ($found as $document)
			{
				// Collect unsorted documents
				$unsortedRefs[$i] = $document;

				// Collect pk's
				$unsortedPks[$i] = PkManager::getFromArray($document, $refModel);
				$i++;
			}
		}

		// Find existing documents
		$existing = [];
		foreach ($model->$name as $key => $document)
		{
			foreach ($sort as $i => $pk)
			{
				if (PkManager::compare($pk, $document))
				{
					// Set existing document with key same as in sort
					$existing[$i] = $document;
				}
			}
		}

		// Sort as stored ref
		foreach ($sort as $key => $pk)
		{
			foreach ($unsortedRefs as $i => $document)
			{
				if (PkManager::compare($pk, $unsortedPks[$i]))
				{
					if (array_key_exists($key, $existing))
					{
						// Update existing instance
						$refs[$key] = $transformatorClass::toModel($document, $existing[$key], $existing[$key]);
					}
					else
					{
						// Create new instance
						$refs[$key] = $transformatorClass::toModel($document);
					}
				}
			}
		}
		$model->$name = $refs;
	}

	public function write($model, $name, &$dbValue, $transformatorClass = ITransformator::class)
	{
		$fieldMeta = ManganMeta::create($model)->field($name);
		$dbValue[$name] = $fieldMeta->default;

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
			$dbValue[$name][$key] = $transformatorClass::fromModel($dbRef, false);
		}
	}

}

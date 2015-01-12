<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Decorators;

use Maslosoft\Mangan\Finder;
use Maslosoft\Mangan\Helpers\DbRefManager;
use Maslosoft\Mangan\Meta\ManganMeta;
use Maslosoft\Mangan\Model\DbRef as DbRef2;
use Maslosoft\Mangan\Sanitizers\DbRef;
use Maslosoft\Mangan\Transformers\FromDocument;
use Maslosoft\Mangan\Transformers\FromRawArray;

/**
 * DbRefArray
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class DbRefArrayDecorator implements IDecorator
{

	public function read($model, $name, &$dbValues)
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
			$dbValue['_class'] = DbRef2::class;
			$dbRef = FromRawArray::toDocument($dbValue);
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

	public function write($model, $name, &$dbValue)
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
			$dbValue[$key] = FromDocument::toRawArray($dbRef, false);
		}
	}

}

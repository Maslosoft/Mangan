<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Decorators;

use Maslosoft\Mangan\EntityManager;
use Maslosoft\Mangan\Finder;
use Maslosoft\Mangan\Helpers\DbRefManager;
use Maslosoft\Mangan\Meta\ManganMeta;
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
			$dbRef = FromRawArray::toDocument($dbValue);
			/* @var $dbRef DbRef */
			$referenced = new $dbRef->class;
			$finder = new Finder(new EntityManager($referenced));

			$refs[$key] = $finder->findByAttributes($dbRef->fields);
		}
		$model->$name = $refs;
	}

	public function write($model, $name, &$dbValue)
	{
		$fieldMeta = ManganMeta::create($model)->field($name);
		if (!$model->$name)
		{
			$dbValue = $fieldMeta->default;
			return;
		}
		if (!is_array($model->$name))
		{
			$dbValue = $fieldMeta->default;
			return;
		}
		$dbValue = $fieldMeta->default;
		foreach ($model->$name as $key => $referenced)
		{
			$dbRef = DbRefManager::extractRef($model, $name, $referenced);
			if ($fieldMeta->dbRef->updatable)
			{
				DbRefManager::save($referenced, $dbRef);
			}
			$dbValue[$key] = FromDocument::toRawArray($dbRef);
		}
	}

}

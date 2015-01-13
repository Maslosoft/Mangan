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
use Maslosoft\Mangan\Model\DbRef;
use Maslosoft\Mangan\Transformers\FromDocument;
use Maslosoft\Mangan\Transformers\FromRawArray;

/**
 * DbRefDecorator
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class DbRefDecorator implements IDecorator
{

	public function read($model, $name, &$dbValue)
	{
		if (!$dbValue)
		{
			$fieldMeta = ManganMeta::create($model)->field($name);
			$model->$name = $fieldMeta->default;
			return;
		}
		$dbValue['_class'] = DbRef::class;
		$dbRef = FromRawArray::toDocument($dbValue);
		/* @var $dbRef DbRef */
		$referenced = new $dbRef->class;
		$model->$name = (new Finder($referenced))->findByPk($dbRef->pk);
	}

	public function write($model, $name, &$dbValue)
	{
		if(!$model->$name)
		{
			return;
		}
		$dbRef = DbRefManager::extractRef($model, $name);
		$referenced = $model->$name;
		$fieldMeta = ManganMeta::create($model)->field($name);
		if ($fieldMeta->dbRef->updatable)
		{
			DbRefManager::save($referenced, $dbRef);
		}
		$dbValue = FromDocument::toRawArray($dbRef, false);
	}

}
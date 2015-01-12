<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Helpers;

use Maslosoft\Mangan\EntityManager;
use Maslosoft\Mangan\Helpers\Sanitizer\Sanitizer;
use Maslosoft\Mangan\Interfaces\IModel;
use Maslosoft\Mangan\Meta\ManganMeta;
use Maslosoft\Mangan\Model\DbRef;

/**
 * DbRefManager
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class DbRefManager
{

	/**
	 * Extract minimum set of data to create db reference
	 * @param IModel $model
	 * @param string $field
	 * @param IModel $referenced
	 */
	public static function extractRef($model, $field, $referenced = null)
	{
		if(null === $referenced)
		{
			$referenced = $model->$field;
		}
		$dbRef = new DbRef();
		$dbRef->pk = PkManager::getFromModel($referenced);
		$dbRef->class = get_class($referenced);
		return $dbRef;
	}

	/**
	 * Save referenced model
	 * @param IModel $referenced
	 * @param DbRef $dbRef
	 */
	public static function save($referenced, DbRef $dbRef)
	{
		// Ensure ref is same as referenced model
		PkManager::applyToModel($referenced, $dbRef->pk);
		$em = new EntityManager($referenced);
		$em->save();
	}

}

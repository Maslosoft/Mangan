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
	 */
	public static function extractRef($model, $field)
	{
		$referenced = $model->$field;
		$meta = ManganMeta::create($model);
		$fieldMeta = $meta->field($field);
		$dbRef = new DbRef();
		if ($fieldMeta->dbRef->field)
		{
			$refFields = [];
			if (is_array($fieldMeta->dbRef->field))
			{
				$refFields = $fieldMeta->dbRef->field;
			}
			else
			{
				$refFields = [$fieldMeta->dbRef->field];
			}
		}
		else
		{
			$refFields = PkManager::prepareFromModel($referenced)->getConditions();
		}
		$sanitizer = new Sanitizer($model);
		foreach ($refFields as $name => $value)
		{
			$dbRef->fields[$name] = $sanitizer->write($name, $value);
		}
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
		foreach ($dbRef->fields as $name => $value)
		{
			$referenced->$name = $value;
		}
		$em = new EntityManager($referenced);
		$em->save();
	}

}

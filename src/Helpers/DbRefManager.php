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

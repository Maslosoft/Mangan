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
use Maslosoft\Mangan\EntityManager;
use Maslosoft\Mangan\Finder;
use Maslosoft\Mangan\Helpers\PkManager;
use Maslosoft\Mangan\Model\DbRef;

/**
 * Helper class for db refs
 *
 * @internal
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class DbRefManager
{

	/**
	 * Extract minimum set of data to create db reference
	 * @param AnnotatedInterface $model
	 * @param string $field
	 * @param AnnotatedInterface $referenced
	 */
	public static function extractRef(AnnotatedInterface $model, $field, AnnotatedInterface $referenced = null)
	{
		if (null === $referenced)
		{
			$referenced = $model->$field;
		}
		$dbRef = new DbRef();
		$dbRef->pk = PkManager::getFromModel($referenced);
		$dbRef->class = get_class($referenced);
		return $dbRef;
	}

	public static function createInstanceFrom(DbRef $dbRef)
	{
		return (new Finder($dbRef->class))->findByPk($dbRef->pk);
	}

	public static function maybeCreateInstanceFrom($dbRef)
	{
		if($dbRef instanceof DbRef)
		{
			return (new Finder(new $dbRef->class))->findByPk($dbRef->pk);
		}
		return $dbRef;
	}

	/**
	 * Save referenced model
	 * @param AnnotatedInterface $referenced
	 * @param DbRef $dbRef
	 */
	public static function save(AnnotatedInterface $referenced, DbRef $dbRef)
	{
		// Ensure ref is same as referenced model
		PkManager::applyToModel($referenced, $dbRef->pk);
		$em = new EntityManager($referenced);
		$em->save();
	}

}

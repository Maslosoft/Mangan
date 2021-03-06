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

namespace Maslosoft\Mangan\Traits;

use Maslosoft\Mangan\Interfaces\CollectionNameInterface;
use Maslosoft\Mangan\Meta\ManganMeta;

/**
 * This trait contains default `getCollectionName` implementation. It will first
 * try to get collection name from annotations, then it will fall back to
 * current class name with slashes renamed to dots.
 *
 * Example auto generated collection name for class `\ManganExamples\Model\User`:
 * 
 * ```
 * ManganExamples.Model.User
 * ```
 *
 * @see CollectionNameInterface
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
trait CollectionNameTrait
{

	/**
	 * This method must return collection name for use with this model.
	 * By default it uses full class name, with slashes replaced by dots.
	 *
	 * If `CollectionName` annotation is defined, it will collection name defined
	 * by this annotation.
	 *
	 * @return string collection name
	 * @Ignored
	 */
	public function getCollectionName()
	{
		$collectionName = ManganMeta::create($this)->type()->collectionName;
		if (!empty($collectionName))
		{
			return $collectionName;
		}
		$name = get_class($this);
		return ltrim(str_replace('\\', '.', $name), '\\');
	}

}

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

namespace Maslosoft\Mangan\Transformers;

/**
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface ITransformator
{

	/**
	 * Returns the given object as an associative array
	 * @param IModel|object $model
	 * @param bool $withClassName Whenever to include special _class field
	 * @return array an associative array of the contents of this object
	 */
	public static function fromModel($model, $withClassName = true);

	/**
	 * Create document from array
	 * TODO Enforce $className if collection is homogenous
	 * @return object
	 */
	public static function toModel($data, $className = null);
}

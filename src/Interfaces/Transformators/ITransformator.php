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

namespace Maslosoft\Mangan\Interfaces\Transformators;

use Maslosoft\Addendum\Interfaces\IAnnotated;

/**
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface ITransformator
{

	/**
	 * Returns the given object as an associative array
	 * @param IAnnotated $model
	 * @param bool $withClassName Whenever to include special _class field
	 * @return array an associative array of the contents of this object
	 */
	public static function fromModel(IAnnotated $model, $withClassName = true);

	/**
	 * Create document from array
	 * TODO Enforce $className if collection is homogenous
	 *
	 * @param mixed[] $data
	 * @param string $className
	 * @param IAnnotated $instance
	 * @return IAnnotated Model instance
	 */
	public static function toModel($data, $className = null, IAnnotated $instance = null);
}

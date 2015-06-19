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

namespace Maslosoft\Mangan\Decorators;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Interfaces\Decorators\Property\DecoratorInterface;
use Maslosoft\Mangan\Interfaces\Transformators\TransformatorInterface;

/**
 * Undecorated
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class Undecorated implements DecoratorInterface
{

	/**
	 * This will be called when getting value.
	 * This should return end user value.
	 * @param AnnotatedInterface $model Document model which will be decorated
	 * @param string $name Field name
	 * @param mixed $dbValue
	 * @return bool Return true if value should be assigned to model
	 */
	public function read($model, $name, &$dbValue, $transformatorClass = TransformatorInterface::class)
	{
		$model->$name = $dbValue;
	}

	/**
	 * This will be called when setting value.
	 * This should return db acceptable value
	 * @param AnnotatedInterface $model Document model which will be decorated
	 * @param string $name Field name
	 * @param mixed $dbValues
	 * @return bool Return true to store value to database
	 */
	public function write($model, $name, &$dbValues, $transformatorClass = TransformatorInterface::class)
	{
		$dbValues[$name] = $model->$name;
	}

}

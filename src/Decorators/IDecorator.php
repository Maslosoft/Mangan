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

use Maslosoft\Addendum\Interfaces\IAnnotated;
use Maslosoft\Mangan\Transformers\ITransformator;

/**
 * This should modify fields bahavior
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface IDecorator
{

	/**
	 * This will be called when getting value.
	 * This should return end user value.
	 * @param IAnnotated $model Document model which will be decorated
	 * @param string $name Field name
	 * @param mixed $dbValue
	 * @param string $transformatorClass Transformator class used
	 * @return bool Return true if value should be assigned to model
	 */
	public function read($model, $name, &$dbValue, $transformatorClass = ITransformator::class);

	/**
	 * This will be called when setting value.
	 * This should return db acceptable value
	 * @param IAnnotated $model Model which is about to be decorated
	 * @param string $name Current field name
	 * @param mixed[] $dbValues Whole model values from database. This is associative array with keys same as model properties (use $name param to access value). This is passed by reference.
	 * @param string $transformatorClass Transformator class used
	 * @return bool Return true to store value to database
	 */
	public function write($model, $name, &$dbValues, $transformatorClass = ITransformator::class);
}

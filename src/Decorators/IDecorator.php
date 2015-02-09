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

namespace Maslosoft\Mangan\Decorators;

use Maslosoft\Mangan\EmbeddedDocument;
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
	 * @param EmbeddedDocument $model Document model which will be decorated
	 * @param string $name Field name
	 * @param mixed $dbValue
	 * @param string $transformatorClass Transformator class used
	 * @return bool Return true if value should be assigned to model
	 */
	public function read($model, $name, &$dbValue, $transformatorClass = ITransformator::class);

	/**
	 * This will be called when setting value.
	 * This should return db acceptable value
	 * @param EmbeddedDocument $model Document model which will be decorated
	 * @param string $name Field name
	 * @param mixed $dbValue
	 * @param string $transformatorClass Transformator class used
	 * @return bool Return true to store value to database
	 */
	public function write($model, $name, &$dbValue, $transformatorClass = ITransformator::class);
}

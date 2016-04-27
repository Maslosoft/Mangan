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

namespace Maslosoft\Mangan\Decorators\Property;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Interfaces\Decorators\Property\DecoratorInterface;
use Maslosoft\Mangan\Interfaces\Transformators\TransformatorInterface;
use Maslosoft\Mangan\Meta\ManganMeta;

/**
 * SecretDecorator
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class SecretDecorator implements DecoratorInterface
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
		return true;
	}

	/**
	 * This will be called when setting value.
	 * This should return db acceptable value
	 * @param AnnotatedInterface $model Document model which will be decorated
	 * @param string $name Field name
	 * @param mixed $dbValue
	 * @return bool Return true to store value to database
	 */
	public function write($model, $name, &$dbValue, $transformatorClass = TransformatorInterface::class)
	{
		$secretMeta = ManganMeta::create($model)->field($name)->secret;
		if (false === $secretMeta)
		{
			return true;
		}
		if (empty($secretMeta->callback) || !$secretMeta->callback)
		{
			return true;
		}
		if(!empty($model->$name))
		{
			$converted = call_user_func($secretMeta->callback, $model->$name);
			$dbValue[$name] = $converted;
		}
		return true;
	}

}

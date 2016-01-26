<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Decorators\Property;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Exceptions\ManganException;
use Maslosoft\Mangan\Interfaces\Decorators\Property\DecoratorInterface;
use Maslosoft\Mangan\Interfaces\InternationalInterface;
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
		$converted = call_user_func($secretMeta->callback, $model->$name);
		$dbValue[$name] = $converted;

		return true;
	}

}

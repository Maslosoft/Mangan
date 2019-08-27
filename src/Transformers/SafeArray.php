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

namespace Maslosoft\Mangan\Transformers;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\AspectManager;
use Maslosoft\Mangan\EntityManager;
use Maslosoft\Mangan\Interfaces\Transformators\TransformatorInterface;

/**
 * This transformer is configured to set only safe attributes - from any external source.
 * @see EntityManager::setAttributes()
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class SafeArray extends Transformer implements TransformatorInterface
{
	const AspectSafeArrayFromModel = 'AspectSafeArrayFromModel';
	const AspectSafeArrayToModel = 'AspectSafeArrayToModel';

	public static function fromModel(AnnotatedInterface $model, $fields = [])
	{
		AspectManager::addAspect($model, self::AspectSafeArrayFromModel);
		$data = parent::fromModel($model, $fields);
		AspectManager::removeAspect($model, self::AspectSafeArrayFromModel);
		return $data;
	}

	public static function toModel($data, $className = null, AnnotatedInterface $instance = null, AnnotatedInterface $parent = null, $parentField = '')
	{
		AspectManager::addAspect($instance, self::AspectSafeArrayToModel);
		$model = parent::toModel($data, $className, $instance, $parent, $parentField);
		AspectManager::removeAspect($instance, self::AspectSafeArrayToModel);
		AspectManager::removeAspect($model, self::AspectSafeArrayToModel);
		return $model;
	}
}

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
use Maslosoft\Mangan\Interfaces\Transformators\TransformatorInterface;

/**
 * YamlArray
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class YamlArray extends Transformer implements TransformatorInterface
{
	const AspectYamlArrayFromModel = 'AspectYamlArrayFromModel';
	const AspectYamlArrayToModel = 'AspectYamlArrayToModel';

	public static function fromModel(AnnotatedInterface $model, $fields = [])
	{
		AspectManager::addAspect($model, self::AspectYamlArrayFromModel);
		$data = parent::fromModel($model, $fields);
		AspectManager::removeAspect($model, self::AspectYamlArrayFromModel);
		return $data;
	}

	public static function toModel($data, $className = null, AnnotatedInterface $instance = null, AnnotatedInterface $parent = null, $parentField = '')
	{
		AspectManager::addAspect($instance, self::AspectYamlArrayToModel);
		$model = parent::toModel($data, $className, $instance, $parent, $parentField);
		AspectManager::removeAspect($instance, self::AspectYamlArrayToModel);
		AspectManager::removeAspect($model, self::AspectYamlArrayToModel);
		return $model;
	}
}

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
 * DocumentArray
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class DocumentArray extends Transformer implements TransformatorInterface
{
	const AspectDocumentArrayFromModel = 'AspectDocumentArrayFromModel';
	const AspectDocumentArrayToModel = 'AspectDocumentArrayToModel';

	public static function fromModel(AnnotatedInterface $model, $fields = [])
	{
		AspectManager::addAspect($model, self::AspectDocumentArrayFromModel);
		$data = parent::fromModel($model, $fields);
		AspectManager::removeAspect($model, self::AspectDocumentArrayFromModel);
		return $data;
	}

	public static function toModel($data, $className = null, AnnotatedInterface $instance = null, AnnotatedInterface $parent = null, $parentField = '')
	{
		AspectManager::addAspect($instance, self::AspectDocumentArrayToModel);
		$model = parent::toModel($data, $className, $instance, $parent, $parentField);
		AspectManager::removeAspect($instance, self::AspectDocumentArrayToModel);
		AspectManager::removeAspect($model, self::AspectDocumentArrayToModel);
		return $model;
	}
}

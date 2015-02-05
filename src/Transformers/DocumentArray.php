<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Transformers;

use Maslosoft\Mangan\Exceptions\TransformatorException;
use Maslosoft\Mangan\Helpers\Sanitizer\Sanitizer;
use Maslosoft\Mangan\Meta\DocumentPropertyMeta;
use Maslosoft\Mangan\Meta\ManganMeta;

/**
 * DocumentArray
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class DocumentArray implements ITransformator
{
	public static function fromModel($model, $withClassName = true)
	{
		$meta = ManganMeta::create($model);
		$arr = [];
		$sanitizer = new Sanitizer($model);
		foreach ($meta->fields() as $name => $field)
		{
			$model->$name = $sanitizer->write($name, $model->$name);
		}
		if ($withClassName)
		{
			$arr['_class'] = get_class($model);
		}
		return $arr;
	}

	public static function toModel($data, $className = null)
	{
		if (!$data)
		{
			return null;
		}
		if (is_object($className))
		{
			$className = get_class($className);
		}
		if (!$className)
		{
			if (array_key_exists('_class', $data))
			{
				$className = $data['_class'];
			}
			else
			{
				throw new TransformatorException('Could not determine document type');
			}
		}
		$model = new $className;
		$meta = ManganMeta::create($model);
		$sanitizer = new Sanitizer($model);
		foreach ($data as $name => $value)
		{
			$fieldMeta = $meta->$name;
			/* @var $fieldMeta DocumentPropertyMeta */
			if (!$fieldMeta)
			{
				continue;
			}
			if ($fieldMeta->toArray === false)
			{
				continue;
			}
			$model->$name = $sanitizer->read($name, $value);
		}
		return $model;
	}

}

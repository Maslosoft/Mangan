<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Transformers;

use Maslosoft\Addendum\Interfaces\IAnnotated;
use Maslosoft\Mangan\Exceptions\TransformatorException;
use Maslosoft\Mangan\Helpers\Decorator\Decorator;
use Maslosoft\Mangan\Helpers\Decorator\ModelDecorator;
use Maslosoft\Mangan\Helpers\PropertyFilter\Filter;
use Maslosoft\Mangan\Helpers\Sanitizer\Sanitizer;
use Maslosoft\Mangan\Interfaces\IModel;
use Maslosoft\Mangan\Meta\ManganMeta;

/**
 * Transformer
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
abstract class Transformer
{

	/**
	 * Returns the given object as an associative array
	 * @param IModel|object $model
	 * @param string[] $fields Fields to transform
	 * @return array an associative array of the contents of this object
	 */
	public static function fromModel($model, $fields = [])
	{
		$meta = ManganMeta::create($model);
		$calledClass = get_called_class();
		$decorator = new Decorator($model, $calledClass);
		$md = new ModelDecorator($model, $calledClass);
		$sanitizer = new Sanitizer($model);
		$filter = new Filter($model, $calledClass);
		$arr = [];
		foreach ($meta->fields() as $name => $fieldMeta)
		{
			if ((bool) $fields && !in_array($name, $fields))
			{
				continue;
			}
			if (!$filter->fromModel($model, $meta->$name))
			{
				continue;
			}
			$model->$name = $sanitizer->write($name, $model->$name);
			$decorator->write($name, $arr);
		}
		$md->write($arr);
		return $arr;
	}

	/**
	 * Create document from array
	 * TODO Enforce $className if collection is homogenous
	 * @param mixed[] $data
	 * @param string|object $className
	 * @param IAnnotated $instance
	 * @return IAnnotated
	 * @throws TransformatorException
	 */
	public static function toModel($data, $className = null, $instance = null)
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
		return self::_toDocument($className, $data, $instance);
	}

	private static function _toDocument($className, $data, $instance)
	{
		if ($instance)
		{
			$model = $instance;
		}
		else
		{
			$model = new $className;
		}
		$meta = ManganMeta::create($model);
		$calledClass = get_called_class();
		$decorator = new Decorator($model, $calledClass);
		$md = new ModelDecorator($model, $calledClass);
		$sanitizer = new Sanitizer($model);
		$filter = new Filter($model, $calledClass);
		foreach ($data as $name => $value)
		{
			$fieldMeta = $meta->$name;
			/* @var \Maslosoft\Mangan\Meta\DocumentPropertyMeta $fieldMeta */
			if (!$fieldMeta)
			{
				continue;
			}
			if (!$filter->toModel($model, $fieldMeta))
			{
				continue;
			}
			$decorator->read($name, $value);
			$model->$name = $sanitizer->read($name, $model->$name);
		}
		$md->read($data);
		return $model;
	}

}

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

namespace Maslosoft\Mangan\Transformers;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Exceptions\TransformatorException;
use Maslosoft\Mangan\Helpers\Decorator\Decorator;
use Maslosoft\Mangan\Helpers\Decorator\ModelDecorator;
use Maslosoft\Mangan\Helpers\Finalizer\FinalizingManager;
use Maslosoft\Mangan\Helpers\PropertyFilter\Filter;
use Maslosoft\Mangan\Helpers\Sanitizer\Sanitizer;
use Maslosoft\Mangan\Meta\DocumentPropertyMeta;
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
	 * @param AnnotatedInterface|object $model
	 * @param string[] $fields Fields to transform
	 * @return array an associative array of the contents of this object
	 */
	public static function fromModel(AnnotatedInterface $model, $fields = [])
	{
		$meta = ManganMeta::create($model);
		$calledClass = get_called_class();
		$decorator = new Decorator($model, $calledClass);
		$md = new ModelDecorator($model, $calledClass);
		$sanitizer = new Sanitizer($model, $calledClass);
		$filter = new Filter($model, $calledClass);
		$arr = [];
		foreach ($meta->fields() as $name => $fieldMeta)
		{
			if (!empty($fields) && !in_array($name, $fields))
			{
				continue;
			}
			if (!$filter->fromModel($model, $fieldMeta))
			{
				continue;
			}
			$model->$name = $sanitizer->write($name, $model->$name);
			$decorator->write($name, $arr);
		}
		$md->write($arr);
		return FinalizingManager::fromModel($arr, static::class, $model);
	}

	/**
	 * Create document from array
	 *
	 * @param mixed[] $data
	 * @param string|object $className
	 * @param AnnotatedInterface $instance
	 * @return AnnotatedInterface
	 * @throws TransformatorException
	 */
	public static function toModel($data, $className = null, AnnotatedInterface $instance = null)
	{
		$data = (array) $data;
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
		$sanitizer = new Sanitizer($model, $calledClass);
		$filter = new Filter($model, $calledClass);
		foreach ($data as $name => $value)
		{
			$fieldMeta = $meta->$name;
			/* @var $fieldMeta DocumentPropertyMeta */
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

		return FinalizingManager::toModel(static::class, $model);
	}

}

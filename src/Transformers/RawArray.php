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

namespace Maslosoft\Mangan\Transformers;

use Maslosoft\Mangan\Exceptions\TransformatorException;
use Maslosoft\Mangan\Helpers\Decorator\Decorator;
use Maslosoft\Mangan\Helpers\Sanitizer\Sanitizer;
use Maslosoft\Mangan\Meta\ManganMeta;

/**
 * RawArray
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class RawArray
{

	/**
	 * Returns the given object as an associative array
	 * @param IModel|object $model
	 * @param bool $withClassName Whenever to include special _class field
	 * @return array an associative array of the contents of this object
	 * @since v1.0.8
	 */
	public static function fromModel($model, $withClassName = true)
	{
		$meta = ManganMeta::create($model);
		$decorator = new Decorator($model);
		$arr = [];
		$sanitizer = new Sanitizer($model);
		foreach ($meta->fields() as $name => $field)
		{
			$model->$name = $sanitizer->write($name, $model->$name);
			$decorator->write($name, $arr[$name]);
		}
		if ($withClassName)
		{
			$arr['_class'] = get_class($model);
		}
		return $arr;
	}

	/**
	 * Create document from array
	 * TODO Enforce $className if collection is homogenous
	 * @return object
	 */
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
		return self::_toDocument($className, $data);
	}

	private static function _toDocument($className, $data)
	{
		$model = new $className;
		$meta = ManganMeta::create($model);
		$decorator = new Decorator($model);
		$sanitizer = new Sanitizer($model);
		foreach ($data as $name => $value)
		{
			$fieldMeta = $meta->$name;
			/* @var \Maslosoft\Mangan\Meta\DocumentPropertyMeta $fieldMeta */
			if (!$fieldMeta)
			{
				continue;
			}
			$decorator->read($name, $value);
			$model->$name = $sanitizer->read($name, $model->$name);
		}
		return $model;
	}

}

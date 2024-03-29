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
use Maslosoft\Mangan\Exceptions\TransformatorException;
use Maslosoft\Mangan\Interfaces\Transformators\TransformatorInterface;

/**
 * JsonString
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class JsonString implements TransformatorInterface
{

	/**
	 * Returns the given object as an associative array
	 * @param AnnotatedInterface|object $model
	 * @param string[] $fields Fields to transform
	 * @param int $options json_encode options
	 * @return array an associative array of the contents of this object
	 */
	public static function fromModel(AnnotatedInterface $model, $fields = [], $options = null)
	{
		$data = json_encode(JsonArray::fromModel($model, $fields), JSON_THROW_ON_ERROR | $options);
		return $data;
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
		$model = JsonArray::toModel(json_decode($data, true), $className, $instance);
		return $model;
	}

}

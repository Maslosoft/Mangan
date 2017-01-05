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

namespace Maslosoft\Mangan\Decorators;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Interfaces\Decorators\Property\DecoratorInterface;
use Maslosoft\Mangan\Interfaces\Transformators\TransformatorInterface;

/**
 * EmbeddedArrayDecorator
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class EmbeddedArrayDecorator extends EmbeddedDecorator implements DecoratorInterface
{

	public function read($model, $name, &$dbValue, $transformatorClass = TransformatorInterface::class)
	{
		if (is_array($dbValue))
		{
			$docs = [];
			foreach ($dbValue as $key => $data)
			{
				static::ensureClass($model, $name, $data);
				// Set ensured class to $dbValue
				$instance = $this->_getInstance($model->$name, $dbValue, $data);
				$embedded = $transformatorClass::toModel($data, $instance, $instance);
				$docs[] = $embedded;
			}
			$model->$name = $docs;
		}
		else
		{
			$model->$name = $dbValue;
		}
	}

	public function write($model, $name, &$dbValue, $transformatorClass = TransformatorInterface::class)
	{
		if (is_array($model->$name))
		{
			$dbValue[$name] = [];
			$key = 0;
			foreach ($model->$name as $key => $document)
			{
				$data = $transformatorClass::fromModel($document);
				$dbValue[$name][] = $data;
			}
		}
		else
		{
			$dbValue[$name] = $model->$name;
		}
	}

	/**
	 * TODO: This relies on _id
	 * @param AnnotatedInterface[] $instances
	 * @param mixed[] $dbValue
	 * @param mixed[] $data
	 * @return AnnotatedInterface|null
	 */
	private function _getInstance($instances, $dbValue, $data)
	{
		if (!count($instances))
		{
			return null;
		}
		$map = [];
		foreach ($dbValue as $val)
		{
			$id = (string) $val['_id'];
			$map[$id] = true;
		}
		foreach ($instances as $instance)
		{
			$id = (string) $instance->_id;
			if (isset($map[$id]) && $data['_id'] == $id && $instance instanceof $data['_class'])
			{
				return $instance;
			}
		}
		return null;
	}

}

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
use Symfony\Component\Yaml\Yaml;

/**
 * YamlString
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class YamlString implements TransformatorInterface
{
	const AspectYamlStringFromModel = 'AspectYamlStringFromModel';
	const AspectYamlStringToModel = 'AspectYamlStringToModel';

	/**
	 * Returns the given object as an associative array
	 * @param AnnotatedInterface|object $model
	 * @param string[] $fields Fields to transform
	 *
	 * @param int   $inline                 The level where you switch to inline YAML
	 * @param int   $indent                 The amount of spaces to use for indentation of nested nodes.
	 * @param bool  $exceptionOnInvalidType true if an exception must be thrown on invalid types (a PHP resource or object), false otherwise
	 * @return string YAML string with the contents of this object
	 */
	public static function fromModel(AnnotatedInterface $model, $fields = [], $inline = 2, $indent = 4, $exceptionOnInvalidType = false)
	{
		AspectManager::addAspect($model, self::AspectYamlStringFromModel);
		$data = Yaml::dump(YamlArray::fromModel($model, $fields), $inline, $indent, $exceptionOnInvalidType);
		AspectManager::removeAspect($model, self::AspectYamlStringFromModel);
		return $data;
	}

	/**
	 * Create document from array
	 *
	 * @param mixed[] $data
	 * @param string|object $className
	 * @param AnnotatedInterface $instance
	 *
	 * @param bool   $exceptionOnInvalidType True if an exception must be thrown on invalid types false otherwise
	 *
	 * @return AnnotatedInterface
	 * @throws TransformatorException
	 */
	public static function toModel($data, $className = null, AnnotatedInterface $instance = null, $exceptionOnInvalidType = false)
	{
		AspectManager::addAspect($instance, self::AspectYamlStringToModel);
		$model = YamlArray::toModel(Yaml::parse($data, $exceptionOnInvalidType), $className, $instance);
		AspectManager::removeAspect($instance, self::AspectYamlStringToModel);
		AspectManager::removeAspect($model, self::AspectYamlStringToModel);
		return $model;
	}

}

<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Transformers;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
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

	/**
	 * Returns the given object as an associative array
	 * @param AnnotatedInterface|object $model
	 * @param string[] $fields Fields to transform
	 *
	 * @param int   $inline                 The level where you switch to inline YAML
	 * @param int   $indent                 The amount of spaces to use for indentation of nested nodes.
	 * @param bool  $exceptionOnInvalidType true if an exception must be thrown on invalid types (a PHP resource or object), false otherwise
	 * @return array an associative array of the contents of this object
	 */
	public static function fromModel(AnnotatedInterface $model, $fields = [], $inline = 2, $indent = 4, $exceptionOnInvalidType = false)
	{
		return Yaml::dump(YamlArray::fromModel($model, $fields), $inline, $indent, $exceptionOnInvalidType);
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
		return YamlArray::toModel(Yaml::parse($data, $exceptionOnInvalidType), $className, $instance);
	}

}

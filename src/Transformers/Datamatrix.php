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

/**
 * Datamatrix
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class Datamatrix implements TransformatorInterface
{

	/**
	 * Returns the given object as an associative array
	 * @param AnnotatedInterface|object $model
	 * @param string[] $fields Fields to transform
	 * @return array an associative array of the contents of this object
	 */
	public static function fromModel(AnnotatedInterface $model, $fields = [])
	{
		$data = YamlString::fromModel($model, $fields, 1, 1);
		return (new \Dmtx\Writer())->encode($data)->dump();
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
		$data = (new \Dmtx\Reader())->decode($data);
		return YamlString::toModel($data, $className, $instance);
	}

}

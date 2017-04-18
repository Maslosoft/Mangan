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

use Dmtx\Reader;
use Dmtx\Writer;
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
		return (new Writer())->encode($data)->dump();
	}

	/**
	 * Create document from datamatrix code
	 *
	 * @param mixed[] $data
	 * @param string|object $className
	 * @param AnnotatedInterface $instance
	 * @return AnnotatedInterface
	 * @throws TransformatorException
	 */
	public static function toModel($data, $className = null, AnnotatedInterface $instance = null)
	{
		$data = (new Reader())->decode($data);
		return YamlString::toModel($data, $className, $instance);
	}

}

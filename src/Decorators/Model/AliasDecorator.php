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

namespace Maslosoft\Mangan\Decorators\Model;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Interfaces\Decorators\Model\ModelDecoratorInterface;
use Maslosoft\Mangan\Interfaces\Transformators\TransformatorInterface;
use Maslosoft\Mangan\Meta\DocumentPropertyMeta;
use Maslosoft\Mangan\Meta\DocumentTypeMeta;
use Maslosoft\Mangan\Meta\ManganMeta;

/**
 * AliasDecorator
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class AliasDecorator implements ModelDecoratorInterface
{

	/**
	 * This will be called when getting value.
	 * This should return end user value.
	 * @param AnnotatedInterface $model Document model which will be decorated
	 * @param mixed $dbValues
	 * @param string $transformatorClass Transformator class used
	 * @return bool Return true if value should be assigned to model
	 */
	public function read($model, &$dbValues, $transformatorClass = TransformatorInterface::class)
	{
		$meta = ManganMeta::create($model);

		$typeMeta = $meta->type();
		/* @var $typeMeta DocumentTypeMeta */
		foreach ($typeMeta->aliases as $from => $to)
		{
			$this->_readValue($meta, $from, $to, $model);
			$this->_readValue($meta, $to, $from, $model);
		}
	}

	/**
	 * This will be called when setting value.
	 * This should return db acceptable value
	 * @param AnnotatedInterface $model Model which is about to be decorated
	 * @param mixed[] $dbValues Whole model values from database. This is associative array with keys same as model properties (use $name param to access value). This is passed by reference.
	 * @param string $transformatorClass Transformator class used
	 * @return bool Return true to store value to database
	 */
	public function write($model, &$dbValues, $transformatorClass = TransformatorInterface::class)
	{
		$meta = ManganMeta::create($model);

		$typeMeta = $meta->type();
		/* @var $typeMeta DocumentTypeMeta */
		foreach ($typeMeta->aliases as $from => $to)
		{
			$this->_writeValue($meta, $from, $to, $dbValues, $model);
			$this->_writeValue($meta, $to, $from, $dbValues, $model);
		}
	}

	private function _readValue($meta, $from, $to, $model)
	{
		$fieldMeta = $meta->$from;
		if (!$fieldMeta)
		{
			return;
		}
		/* @var $fieldMeta DocumentPropertyMeta */
		if ($fieldMeta->default !== $model->$from)
		{
			$model->$to = $model->$from;
		}
	}

	private function _writeValue($meta, $from, $to, &$dbValues, $model)
	{
		$fieldMeta = $meta->$from;
		if (!$fieldMeta)
		{
			return;
		}
		if (!array_key_exists($from, $dbValues))
		{
			$dbValues[$from] = $fieldMeta->default;
		}
		if (!array_key_exists($to, $dbValues))
		{
			$dbValues[$to] = $fieldMeta->default;
		}
		/* @var $fieldMeta DocumentPropertyMeta */
		if ($fieldMeta->default !== $dbValues[$from])
		{
			$dbValues[$to] = $dbValues[$from];
		}
		// Set model value too
		if ($fieldMeta->default !== $model->$from)
		{
			$model->$to = $model->$from;
		}
	}

}

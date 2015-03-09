<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Decorators\Model;

use Maslosoft\Mangan\EmbeddedDocument;
use Maslosoft\Mangan\Meta\DocumentPropertyMeta;
use Maslosoft\Mangan\Meta\DocumentTypeMeta;
use Maslosoft\Mangan\Meta\ManganMeta;
use Maslosoft\Mangan\Transformers\ITransformator;

/**
 * AliasDecorator
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class AliasDecorator implements IModelDecorator
{

	/**
	 * This will be called when getting value.
	 * This should return end user value.
	 * @param EmbeddedDocument $model Document model which will be decorated
	 * @param mixed $dbValues
	 * @param string $transformatorClass Transformator class used
	 * @return bool Return true if value should be assigned to model
	 */
	public function read($model, &$dbValues, $transformatorClass = ITransformator::class)
	{
		$meta = ManganMeta::create($model);

		$typeMeta = $meta->type();
		/* @var $typeMeta DocumentTypeMeta */
		foreach ($typeMeta->aliases as $from => $to)
		{
			$this->_readValue($meta, $from, $to, $dbValues, $model);
			$this->_readValue($meta, $to, $from, $dbValues, $model);
		}
	}

	/**
	 * This will be called when setting value.
	 * This should return db acceptable value
	 * @param EmbeddedDocument $model Model which is about to be decorated
	 * @param mixed[] $dbValues Whole model values from database. This is associative array with keys same as model properties (use $name param to access value). This is passed by reference.
	 * @param string $transformatorClass Transformator class used
	 * @return bool Return true to store value to database
	 */
	public function write($model, &$dbValues, $transformatorClass = ITransformator::class)
	{
		$meta = ManganMeta::create($model);

		$typeMeta = $meta->type();
		/* @var $typeMeta DocumentTypeMeta */
		foreach ($typeMeta->aliases as $from => $to)
		{
			$this->_writeValue($meta, $from, $to, $dbValues);
			$this->_writeValue($meta, $to, $from, $dbValues);
		}
	}

	private function _readValue($meta, $from, $to, &$dbValues, $model)
	{
		if(!array_key_exists($from, $dbValues))
		{
			return;
		}
		$fieldMeta = $meta->$from;
		/* @var $fieldMeta DocumentPropertyMeta */
		if ($fieldMeta->default !== $dbValues[$from])
		{
			$model->$to = $model->$from;
		}
	}

	private function _writeValue($meta, $from, $to, &$dbValues)
	{
		if(!array_key_exists($from, $dbValues))
		{
			return;
		}
		$fieldMeta = $meta->$from;
		/* @var $fieldMeta DocumentPropertyMeta */
		if ($fieldMeta->default !== $dbValues[$from])
		{
			$dbValues[$to] = $dbValues[$from];
		}
	}

}

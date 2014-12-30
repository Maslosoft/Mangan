<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Decorators;

use Maslosoft\Mangan\Meta\DocumentPropertyMeta;
use Maslosoft\Mangan\Meta\ManganMeta;
use Maslosoft\Mangan\Transformers\FromDocument;
use Maslosoft\Mangan\Transformers\FromRawArray;

/**
 * EmbeddedDecorator
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class EmbeddedDecorator implements IDecorator
{

	public function read($model, $name, &$dbValue)
	{
		self::ensureClass($model, $name, $dbValue);
		$model->$name = FromRawArray::toDocument($dbValue);
	}

	public function write($model, $name, &$dbValue)
	{
		$dbValue = FromDocument::toRawArray($model->$name);
	}

	public static function ensureClass($model, $name, &$dbValue)
	{
		if (!array_key_exists('_class', $dbValue))
		{
			$meta = ManganMeta::create($model)->$name;
			/* @var $meta DocumentPropertyMeta */
			$dbValue['_class'] = $meta->embedded->class;
		}
	}
}

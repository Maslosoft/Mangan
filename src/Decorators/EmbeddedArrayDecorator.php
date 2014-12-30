<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Decorators;

use Maslosoft\Mangan\Transformers\FromDocument;
use Maslosoft\Mangan\Transformers\FromRawArray;

/**
 * EmbeddedArrayDecorator
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class EmbeddedArrayDecorator implements IDecorator
{

	public function read($model, $name, &$dbValue)
	{
		if (is_array($dbValue))
		{
			$model->$name = [];
			foreach ($dbValue as $data)
			{
				EmbeddedDecorator::ensureClass($model, $name, $data);
				$model->$name[] = FromRawArray::toDocument($data);
			}
		}
		else
		{
			$model->$name = $dbValue;
		}
	}

	public function write($model, $name, &$dbValue)
	{
		if (is_array($model->$name))
		{
			$dbValue = [];
			foreach ($model->$name as $document)
			{
				$dbValue[] = FromDocument::toRawArray($document);
			}
		}
		else
		{
			$dbValue = $model->$name;
		}
	}

}

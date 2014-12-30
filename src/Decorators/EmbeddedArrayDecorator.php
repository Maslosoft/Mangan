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
			$docs = [];
			$key = 0;
			foreach ($dbValue as $data)
			{
				EmbeddedDecorator::ensureClass($model, $name, $data);
				$key = isset($data['_key']) ? $data['_key'] : $key++;
				$docs[$key] = FromRawArray::toDocument($data);
			}
			$model->$name = $docs;
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
			$key = 0;
			foreach ($model->$name as $document)
			{
				$data = FromDocument::toRawArray($document);
				$data['_key'] = $key++;
				$dbValue[] = $data;
			}
		}
		else
		{
			$dbValue = $model->$name;
		}
	}

}

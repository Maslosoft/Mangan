<?php

/**
 * This software package is licensed under New BSD license.
 *
 * @package maslosoft/mangan
 * @licence New BSD
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com>
 * @copyright Copyright (c) Maslosoft
 * @copyright Copyright (c) Others as mentioned in code
 * @link http://maslosoft.com/mangan/
 */

namespace Maslosoft\Mangan\Decorators;

use Maslosoft\Mangan\Interfaces\IOwnered;
use Maslosoft\Mangan\Transformers\RawArray;

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
			foreach ($dbValue as $key => $data)
			{
				EmbeddedDecorator::ensureClass($model, $name, $data);
				$embedded = RawArray::toModel($data);
				if ($embedded instanceof IOwnered)
				{
					$embedded->setOwner($model);
				}
				$docs[$key] = $embedded;
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
			foreach ($model->$name as $key => $document)
			{
				$data = RawArray::fromModel($document);
				$dbValue[$key] = $data;
			}
		}
		else
		{
			$dbValue = $model->$name;
		}
	}

}

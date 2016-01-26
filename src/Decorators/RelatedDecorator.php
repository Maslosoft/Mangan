<?php

/**
 * This software package is licensed under AGPL or Commercial license.
 *
 * @package maslosoft/mangan
 * @licence AGPL or Commercial
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com>
 * @copyright Copyright (c) Maslosoft
 * @copyright Copyright (c) Others as mentioned in code
 * @link http://maslosoft.com/mangan/
 */

namespace Maslosoft\Mangan\Decorators;

use InvalidArgumentException;
use Maslosoft\Mangan\Criteria;
use Maslosoft\Mangan\EntityManager;
use Maslosoft\Mangan\Finder;
use Maslosoft\Mangan\Helpers\PkManager;
use Maslosoft\Mangan\Interfaces\Decorators\Property\DecoratorInterface;
use Maslosoft\Mangan\Interfaces\Transformators\TransformatorInterface;
use Maslosoft\Mangan\Meta\ManganMeta;
use Maslosoft\Mangan\Sort;

/**
 * RelatedRecorator
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class RelatedDecorator implements DecoratorInterface
{

	public function read($model, $name, &$dbValue, $transformatorClass = TransformatorInterface::class)
	{
		$fieldMeta = ManganMeta::create($model)->field($name);
		$relMeta = $fieldMeta->related;
		$relModel = new $relMeta->class;
		$criteria = new Criteria(null, $relModel);
		if (empty($relMeta->join))
		{
			throw new InvalidArgumentException(sprintf('Parameter `join` is required for Related annotation, model `%s`, field `%s`', get_class($model), $name));
		}
		foreach ($relMeta->join as $source => $rel)
		{
			assert($model->$source !== null);
			$criteria->addCond($rel, '==', $model->$source);
		}
		$criteria->setSort(new Sort($relMeta->sort));
		if ($relMeta->single)
		{
			$model->$name = (new Finder($relModel))->find($criteria);
		}
		else
		{
			$model->$name = (new Finder($relModel))->findAll($criteria);
		}
	}

	public function write($model, $name, &$dbValues, $transformatorClass = TransformatorInterface::class)
	{
		if (!empty($model->$name))
		{
			// Store empty field to trigger decorator read
			$dbValues[$name] = null;

			$fieldMeta = ManganMeta::create($model)->field($name);
			$relMeta = $fieldMeta->related;
			if ($relMeta->single)
			{
				$models = [
					$model->$name
				];
			}
			else
			{
				$models = $model->$name;
			}
			$order = 0;
			foreach ($models as $relModel)
			{
				$fields = [];
				foreach ($relMeta->join as $source => $rel)
				{
					$fields[] = $rel;
					assert(isset($model->$source));
					$relModel->$rel = $model->$source;
				}
				if (!empty($relMeta->orderField))
				{
					$fields[] = $relMeta->orderField;
					$fields = array_unique($fields);
					$relModel->order = $order;
					$order++;
				}
				$em = new EntityManager($relModel);
				if ($relMeta->updatable)
				{
					// Update whole model
					$em->upsert();
				}
				else
				{
					// Update only relation info
					$criteria = PkManager::prepareFromModel($relModel);
					$em->updateOne($criteria, $fields);
				}
			}
		}
	}

}

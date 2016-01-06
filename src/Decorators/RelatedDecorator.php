<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Decorators;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Criteria;
use Maslosoft\Mangan\EntityManager;
use Maslosoft\Mangan\Finder;
use Maslosoft\Mangan\Interfaces\CriteriaInterface;
use Maslosoft\Mangan\Interfaces\Decorators\Property\DecoratorInterface;
use Maslosoft\Mangan\Interfaces\Transformators\TransformatorInterface;
use Maslosoft\Mangan\Meta\ManganMeta;

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
		foreach ($relMeta->join as $source => $rel)
		{
			$criteria->addCond($rel, '==', $model->$source);
		}
		$model->$name = $this->find($relModel, $criteria);
	}

	protected function find(AnnotatedInterface $relModel, CriteriaInterface $criteria)
	{
		return (new Finder($relModel))->find($criteria);
	}

	public function write($model, $name, &$dbValues, $transformatorClass = TransformatorInterface::class)
	{
		if (!empty($model->$name) && $model->$name instanceof AnnotatedInterface)
		{
			// Store empty field to trigger decorator read
			$dbValues[$name] = null;

			$fieldMeta = ManganMeta::create($model)->field($name);
			$relMeta = $fieldMeta->related;
			$fields = [];
			foreach ($relMeta->join as $source => $rel)
			{
				$fields[] = $rel;
				$model->$name->$rel = $model->$source;
			}
			$em = new EntityManager($model->$name);
			if ($relMeta->updatable)
			{
				// Update whole model
				$em->update();
			}
			else
			{
				// Update only relation info
				$em->update($fields);
			}
		}
	}

}

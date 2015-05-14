<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Decorators\Model;

use Maslosoft\Addendum\Interfaces\IAnnotated;
use Maslosoft\Mangan\Interfaces\Decorators\Model\IModelDecorator;
use Maslosoft\Mangan\Interfaces\IOwnered;
use Maslosoft\Mangan\Interfaces\Transformators\ITransformator;
use Maslosoft\Mangan\Meta\DocumentPropertyMeta;
use Maslosoft\Mangan\Meta\ManganMeta;

/**
 * Apply owners
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class OwnerDecorator implements IModelDecorator
{

	/**
	 * This will be called when getting value.
	 * This should return end user value.
	 * @param IAnnotated $model Document model which will be decorated
	 * @param mixed $dbValues
	 * @param string $transformatorClass Transformator class used
	 * @return bool Return true if value should be assigned to model
	 */
	public function read($model, &$dbValues, $transformatorClass = ITransformator::class)
	{
		foreach (ManganMeta::create($model)->properties('owned') as $name => $metaProperty)
		{
			/* @var $metaProperty DocumentPropertyMeta */
			if (!isset($model->$name))
			{
				continue;
			}
			if ($model->$name instanceof IOwnered)
			{
				$model->$name->setOwner($model);
			}
			if (is_array($model->$name))
			{
				foreach ($model->$name as $document)
				{
					if ($document instanceof IOwnered)
					{
						$document->setOwner($model);
					}
				}
			}
		}
	}

	/**
	 * This will be called when setting value.
	 * This should return db acceptable value
	 * @param IAnnotated $model Model which is about to be decorated
	 * @param mixed[] $dbValues Whole model values from database. This is associative array with keys same as model properties (use $name param to access value). This is passed by reference.
	 * @param string $transformatorClass Transformator class used
	 * @return bool Return true to store value to database
	 */
	public function write($model, &$dbValues, $transformatorClass = ITransformator::class)
	{

	}

}

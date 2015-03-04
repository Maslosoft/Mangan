<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Helpers\Decorator;

use Maslosoft\Mangan\Decorators\IDecorator;
use Maslosoft\Mangan\Decorators\IModelDecorator;
use Maslosoft\Mangan\Transformers\ITransformator;

/**
 * CompoundModelDecorator
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class CompoundModelDecorator implements IModelDecorator
{
	/**
	 * Decorators
	 * @var IModelDecorator[]
	 */
	private $_decorators = [];

	/**
	 *
	 * @param IModelDecorator[] $decorators
	 */
	public function __construct($decorators)
	{
		$this->_decorators = $decorators;
	}

	public function read($model, &$dbValue, $transformatorClass = ITransformator::class)
	{
		foreach ($this->_decorators as $decorator)
		{
			$decorator->read($model, $dbValue, $transformatorClass);
		}
	}

	public function write($model, &$dbValue, $transformatorClass = ITransformator::class)
	{
		foreach ($this->_decorators as $decorator)
		{
			$decorator->write($model, $dbValue, $transformatorClass);
		}
	}

}

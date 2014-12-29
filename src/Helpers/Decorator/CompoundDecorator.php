<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Helpers\Decorator;

use Maslosoft\Mangan\Decorators\IDecorator;

/**
 * Container for decorators
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class CompoundDecorator implements IDecorator
{

	/**
	 * Decorators
	 * @var IDecorator[]
	 */
	private $_decorators = [];

	/**
	 *
	 * @param IDecorator[] $decorators
	 */
	public function __construct($decorators)
	{
		$this->_decorators = $decorators;
	}

	public function read($model, $name, &$dbValue)
	{
		foreach ($this->_decorators as $decorator)
		{
			$value = $decorator->read($model, $name, $value);
		}
		return $value;
	}

	public function write($model, $name, &$dbValue)
	{
		foreach ($this->_decorators as $decorator)
		{
			$value = $decorator->write($model, $name, $value);
		}
		return $value;
	}

}

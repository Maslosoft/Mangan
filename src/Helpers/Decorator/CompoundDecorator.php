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

namespace Maslosoft\Mangan\Helpers\Decorator;

use Maslosoft\Mangan\Interfaces\Decorators\Property\IDecorator;
use Maslosoft\Mangan\Interfaces\Transformators\ITransofmator;

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

	public function read($model, $name, &$dbValue, $transformatorClass = ITransformator::class)
	{
		foreach ($this->_decorators as $decorator)
		{
			$decorator->read($model, $name, $dbValue, $transformatorClass);
		}
	}

	public function write($model, $name, &$dbValue, $transformatorClass = ITransformator::class)
	{
		foreach ($this->_decorators as $decorator)
		{
			$decorator->write($model, $name, $dbValue, $transformatorClass);
		}
	}

}

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
			$decorator->read($model, $name, $dbValue);
		}
	}

	public function write($model, $name, &$dbValue)
	{
		foreach ($this->_decorators as $decorator)
		{
			$decorator->write($model, $name, $dbValue);
		}
	}

}

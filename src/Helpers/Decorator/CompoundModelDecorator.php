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

use Maslosoft\Mangan\Interfaces\Decorators\Model\IModelDecorator;
use Maslosoft\Mangan\Interfaces\Transformators\ITransformator;


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

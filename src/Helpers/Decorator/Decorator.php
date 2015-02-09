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
use Maslosoft\Mangan\Helpers\Transformator;
use Maslosoft\Mangan\Meta\DocumentPropertyMeta;
use Maslosoft\Mangan\Meta\DocumentTypeMeta;

/**
 * Decorator
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class Decorator extends Transformator
{

	/**
	 * Read value from database
	 * @param string $name
	 * @param mixed $dbValue
	 */
	public function read($name, &$dbValue)
	{
		$this->getFor($name)->read($this->getModel(), $name, $dbValue);
	}

	/**
	 * Write value into database
	 * @param string $name
	 * @param mixed $dbValue
	 */
	public function write($name, &$dbValue)
	{
		$this->getFor($name)->write($this->getModel(), $name, $dbValue);
	}

	/**
	 * Get transformer
	 * @param type $transformatorClass
	 * @param DocumentTypeMeta $modelMeta
	 * @param DocumentPropertyMeta $meta
	 * @return IDecorator
	 */
	protected function _getTransformer($transformatorClass, DocumentTypeMeta $modelMeta, DocumentPropertyMeta $meta)
	{
		return Factory::create($transformatorClass, $modelMeta, $meta);
	}

}

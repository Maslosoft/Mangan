<?php

/**
 * This software package is licensed under AGPL or Commercial license.
 *
 * @package maslosoft/mangan
 * @licence AGPL or Commercial
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com>
 * @copyright Copyright (c) Maslosoft
 * @copyright Copyright (c) Others as mentioned in code
 * @link https://maslosoft.com/mangan/
 */

namespace Maslosoft\Mangan\Helpers\Decorator;

use Maslosoft\Mangan\Interfaces\Decorators\Property\DecoratorInterface;
use Maslosoft\Mangan\Helpers\Transformator;
use Maslosoft\Mangan\Meta\DocumentPropertyMeta;
use Maslosoft\Mangan\Meta\DocumentTypeMeta;

/**
 * Decorator
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
		$this->getFor($name)->read($this->getModel(), $name, $dbValue, $this->getTransformatorClass());
	}

	/**
	 * Write value into database
	 * @param string $name
	 * @param mixed $dbValue
	 */
	public function write($name, &$dbValue)
	{
		$this->getFor($name)->write($this->getModel(), $name, $dbValue, $this->getTransformatorClass());
	}

	/**
	 * Get transformer
	 * @param string $transformatorClass
	 * @param DocumentTypeMeta $modelMeta
	 * @param DocumentPropertyMeta $meta
	 * @return DecoratorInterface
	 */
	protected function _getTransformer($transformatorClass, DocumentTypeMeta $modelMeta, DocumentPropertyMeta $meta)
	{
		return Factory::createForField($transformatorClass, $modelMeta, $meta);
	}

}

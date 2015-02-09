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

namespace Maslosoft\Mangan\Helpers\Sanitizer;

use Maslosoft\Mangan\Helpers\Transformator;
use Maslosoft\Mangan\Meta\DocumentPropertyMeta;
use Maslosoft\Mangan\Meta\DocumentTypeMeta;

/**
 * Sanitizer
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class Sanitizer extends Transformator
{

	public function read($name, $dbValue)
	{
		return $this->getFor($name)->read($this->getModel(), $dbValue);
	}

	public function write($name, $phpValue)
	{
		return $this->getFor($name)->write($this->getModel(), $phpValue);
	}

	protected function _getTransformer($transformatorClass, DocumentTypeMeta $modelMeta, DocumentPropertyMeta $meta)
	{
		return Factory::create($meta);
	}

}

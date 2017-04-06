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
		$sanitizer = $this->getFor($name);
		if(empty($sanitizer))
		{
			return $dbValue;
		}
		return $sanitizer->read($this->getModel(), $dbValue);
	}

	public function write($name, $phpValue)
	{
		$sanitizer = $this->getFor($name);
		if(empty($sanitizer))
		{
			return $phpValue;
		}
		return $sanitizer->write($this->getModel(), $phpValue);
	}

	protected function _getTransformer($transformatorClass, DocumentTypeMeta $modelMeta, DocumentPropertyMeta $meta)
	{
		return Factory::create($meta, $modelMeta, $transformatorClass);
	}

}

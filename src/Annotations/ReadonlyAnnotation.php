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

namespace Maslosoft\Mangan\Annotations;

use Maslosoft\Mangan\Meta\ManganPropertyAnnotation;

/**
 * Readonly indicator for mongo documents
 * @Target('property')
 * @template Readonly
 */
class ReadonlyAnnotation extends ManganPropertyAnnotation
{

	public $value = true;

	public function init()
	{
		$this->_entity->direct = false;
		$this->_entity->readonly = (bool) $this->value;
	}

	public function __toString()
	{
		return $this->value;
	}

}

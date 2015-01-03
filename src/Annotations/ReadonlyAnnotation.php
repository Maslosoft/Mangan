<?php

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

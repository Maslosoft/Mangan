<?php

namespace Maslosoft\Mangan\Annotations;

use Maslosoft\Mangan\Meta\ManganAnnotation;

/**
 * Readonly indicator for mongo documents
 * @template Readonly
 */
class ReadonlyAnnotation extends ManganAnnotation
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

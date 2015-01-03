<?php

namespace Maslosoft\Mangan\Annotations;

use Maslosoft\Mangan\Meta\ManganPropertyAnnotation;

/**
 * If true, value should be saved in database.
 * By default all public properties are stored into db, so use it only when
 * property should not be stored
 * @Target('property')
 * @template Persistent(${false})
 * @author Piotr
 */
class PersistentAnnotation extends ManganPropertyAnnotation
{

	public $value = true;

	public function init()
	{
		$this->_entity->persistent = (bool)$this->value;
	}

}

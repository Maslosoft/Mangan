<?php

namespace Maslosoft\Mangan\Annotations;

/**
 * @licence For licence @see LICENCE.html
 * 
 * @copyright Maslosoft
 * @link http://maslosoft.com/
 */

/**
 * Sanitizer
 * TODO Make sanitizer annotation and sanitizer classes, to ensure proper types and sanitize data
 * @template Sanitizer('${SanitizerClass}')
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class Sanitizer extends EComponentMetaAnnotation
{

	public $value = null;

	public function init()
	{
		$this->_entity->sanitizer = $this->value;
		$this->_entity->direct = false;
	}

}

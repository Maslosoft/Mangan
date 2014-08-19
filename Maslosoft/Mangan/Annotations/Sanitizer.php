<?php

/**
 * @licence For licence @see LICENCE.html
 *
 * @copyright Maslosoft
 * @link http://maslosoft.com/
 */

namespace Maslosoft\Mangan\Annotations;

use Maslosoft\Addendum\Collections\MetaAnnotation;

/**
 * Sanitizer
 * TODO Make sanitizer annotation and sanitizer classes, to ensure proper types and sanitize data
 * @template Sanitizer('${SanitizerClass}')
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class Sanitizer extends MetaAnnotation
{

	public $value = null;

	public function init()
	{
		$this->_entity->sanitizer = $this->value;
		$this->_entity->direct = false;
	}

}

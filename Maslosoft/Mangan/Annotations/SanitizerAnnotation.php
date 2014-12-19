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
 * Sanitizer. There cen be only one sanitizer
 * @template Sanitizer(${SanitizerClass})
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class SanitizerAnnotation extends MetaAnnotation
{

	public $value = null;

	public function init()
	{
		$this->_entity->sanitizer = $this->value;
	}

}

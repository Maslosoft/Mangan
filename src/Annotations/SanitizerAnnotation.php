<?php

/**
 * @licence For licence @see LICENCE.html
 *
 * @copyright Maslosoft
 * @link http://maslosoft.com/
 */

namespace Maslosoft\Mangan\Annotations;

use Maslosoft\Mangan\Meta\ManganAnnotation;

/**
 * Sanitizer. There cen be only one sanitizer
 * @template Sanitizer(${SanitizerClass})
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class SanitizerAnnotation extends ManganAnnotation
{

	public $value = null;

	public function init()
	{
		$this->_entity->sanitizer = $this->value;
	}

}

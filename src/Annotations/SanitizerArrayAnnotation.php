<?php

/**
 * This software package is licensed under AGPL or Commercial license.
 *
 * @package maslosoft/mangan
 * @licence AGPL or Commercial
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com>
 * @copyright Copyright (c) Maslosoft
 * @copyright Copyright (c) Others as mentioned in code
 * @link http://maslosoft.com/mangan/
 */

namespace Maslosoft\Mangan\Annotations;

/**
 * Sanitizer. There cen be only one sanitizer
 * @template SanitizerArray(${SanitizerClass})
 * @Target('property')
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class SanitizerArrayAnnotation extends SanitizerAnnotation
{

	public $value = null;

	public function init()
	{
		parent::init();
		$this->getEntity()->sanitizeArray = true;
	}

}

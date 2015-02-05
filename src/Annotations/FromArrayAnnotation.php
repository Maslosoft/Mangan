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
 * FromArray annotation
 * Use this annotation to ignore or include field when converting from array
 * @Target('field')
 * @template FromArray(false)
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class FromArrayAnnotation extends ManganPropertyAnnotation
{

	public $value;

	public function init()
	{
		$this->_entity->fromArray = (bool) $this->value;
	}

}

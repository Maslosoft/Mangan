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

use Maslosoft\Mangan\Meta\ManganPropertyAnnotation;

/**
 * ToArray annotation
 * Use this annotation to ignore or include field when converting to array
 * @Target('property')
 * @template ToArray(false)
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ToArrayAnnotation extends ManganPropertyAnnotation
{

	public $value;

	public function init()
	{
		$this->getEntity()->toArray = (bool) $this->value;
	}

}

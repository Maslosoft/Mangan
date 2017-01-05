<?php

/**
 * This software package is licensed under AGPL or Commercial license.
 *
 * @package maslosoft/mangan
 * @licence AGPL or Commercial
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com>
 * @copyright Copyright (c) Maslosoft
 * @copyright Copyright (c) Others as mentioned in code
 * @link https://maslosoft.com/mangan/
 */

namespace Maslosoft\Mangan\Annotations;

use Maslosoft\Mangan\Meta\ManganPropertyAnnotation;

/**
 * ToJson annotation
 * Use this annotation to ignore or include field when converting to JSON array
 * @Target('property')
 * @template ToJson(false)
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ToJsonAnnotation extends ManganPropertyAnnotation
{

	public $value;

	public function init()
	{
		$this->getEntity()->toJson = (bool)$this->value;
	}

}

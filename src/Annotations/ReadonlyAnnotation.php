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
 * Readonly indicator for mongo documents
 * @Target('property')
 * @template Readonly
 */
class ReadonlyAnnotation extends ManganPropertyAnnotation
{

	public $value = true;

	public function init()
	{
		$this->getEntity()->direct = false;
		$this->getEntity()->readonly = (bool) $this->value;
	}

	public function __toString()
	{
		return $this->value;
	}

}

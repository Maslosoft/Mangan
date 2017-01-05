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

use Maslosoft\Addendum\Utilities\ClassChecker;
use Maslosoft\Mangan\Meta\ManganTypeAnnotation;
use UnexpectedValueException;

/**
 * FinderAnnotation
 * 
 * @template Finder(${finderClassLiteral})
 * @Target('class')
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class FinderAnnotation extends ManganTypeAnnotation
{

	public $value = null;

	public function init()
	{
		if (!ClassChecker::exists($this->value))
		{
			throw new UnexpectedValueException(sprintf('Class `%s` not found on @Finder annotation, on model `%s`', $this->value, $this->name));
		}
		$this->getEntity()->finder = $this->value;
	}

}

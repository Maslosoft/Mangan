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

use Maslosoft\Mangan\Meta\ManganTypeAnnotation;

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
		$this->_entity->finder = $this->value;
	}

}

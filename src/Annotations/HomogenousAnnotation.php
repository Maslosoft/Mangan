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
 * HomogenousAnnotation
 * Default to true, set this to false to allow stoging arbitrary models types in collection
 * @template Homogenous(${isHomogenous})
 * @Target('class')
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class HomogenousAnnotation extends ManganTypeAnnotation
{

	public $value = true;

	public function init()
	{
		$this->_entity->homogenous = $this->value;
	}

}

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

use Maslosoft\Mangan\Meta\ManganTypeAnnotation;

/**
 * HomogenousAnnotation
 * Default to true, set this to false to allow storing arbitrary models types in collection
 * @template Homogenous(${isHomogenous})
 * @Target('class')
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class HomogenousAnnotation extends ManganTypeAnnotation
{

	public $value = true;

	public function init()
	{
		$this->getEntity()->homogenous = $this->value;
	}

}

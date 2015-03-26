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

namespace Maslosoft\Mangan\Annotations\Validators;

use Maslosoft\Mangan\Meta\ManganPropertyAnnotation;

/**
 * Safe validator marks the associated attributes to be safe for massive assignments.
 * This in fact is same as SafeAnnotation.
 * Could be used as simply as `&commat;Safe`.
 */
class SafeValidatorAnnotation extends ManganPropertyAnnotation
{

	/**
	 * 
	 * @var bool Whenever attribute is safe for mass assignment
	 */
	public $value = true;

	public function init()
	{
		$this->_entity->safe = (bool) $this->value;
	}

}

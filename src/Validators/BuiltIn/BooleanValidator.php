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

namespace Maslosoft\Mangan\Validators\BuiltIn;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Interfaces\Validators\ValidatorInterface;

/**
 * BooleanValidator
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class BooleanValidator implements ValidatorInterface
{

	use \Maslosoft\Mangan\Validators\Traits\Messages,
	  \Maslosoft\Mangan\Validators\Traits\OnScenario;

	/**
	 * @Label('Attribute must be either true or false')
	 * @var string
	 */
	public $msgBoolean = '';

	public function isValid(AnnotatedInterface $model, $field)
	{
		if (is_bool($model->$field))
		{
			return true;
		}
		$valid = filter_var($model->$field, FILTER_VALIDATE_BOOLEAN);
		if (!$valid)
		{
			$this->addError('msgBoolean');
			return false;
		}
		return true;
	}

}

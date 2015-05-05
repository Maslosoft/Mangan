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

use Maslosoft\Addendum\Interfaces\IAnnotated;
use Maslosoft\Mangan\Interfaces\Validators\IValidator;

/**
 * BooleanValidator
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class BooleanValidator implements IValidator
{

	public function isValid(IAnnotated $model, $field)
	{
		$valid = filter_var($model->$field, FILTER_VALIDATE_BOOLEAN);
		if (!$valid)
		{
			$this->addError('Attribute must be either true or false');
		}
		return true;
	}

	public function addError($message)
	{

	}

}

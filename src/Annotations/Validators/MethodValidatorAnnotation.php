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

namespace Maslosoft\Mangan\Annotations\Validators;

/**
 * Method Validator does validation by calling model method.
 *
 * Method signature:
 *
 * myValidation(ValidatorInterface $validator)
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class MethodValidatorAnnotation
{

	public function __construct()
	{
		throw new Exception('Not implemented, do not use (yet)');
	}

}

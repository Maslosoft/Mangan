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

namespace Maslosoft\Mangan\Validators\Proxy;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Interfaces\Validators\ValidatorInterface;
use Maslosoft\Mangan\Interfaces\Validators\ValidatorProxyInterface;

/**
 * Class Validator Proxy is validator attached directly into attribute.
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ClassValidatorProxy implements ValidatorInterface, ValidatorProxyInterface
{

	public function addError($message)
	{

	}

	public function getErrors()
	{

	}

	public function getValidator()
	{

	}

	public function isValid(AnnotatedInterface $model, $attribute)
	{

	}

	public function setValidator(ValidatorInterface $validator)
	{

	}

}

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

namespace Maslosoft\Mangan\Validators;

use Maslosoft\Mangan\Interfaces\Validators\ValidatorInterface;
use Maslosoft\Mangan\Interfaces\Validators\ValidatorProxyInterface;

/**
 * BaseProxy
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class DefaultProxy implements ValidatorInterface, ValidatorProxyInterface
{

	public function addError($message)
	{

	}

	public function getValidator()
	{

	}

	public function isValid(\Maslosoft\Addendum\Interfaces\AnnotatedInterface $model, $attribute)
	{

	}

	public function setValidator(ValidatorInterface $validator)
	{

	}

	public function getErrors()
	{

	}

}

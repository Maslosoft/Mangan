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

namespace Maslosoft\Mangan\Validators;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Interfaces\Validators\ValidatorInterface;
use Maslosoft\Mangan\Interfaces\Validators\ValidatorProxyInterface;

/**
 * BaseProxy
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
abstract class DefaultProxy implements ValidatorInterface, ValidatorProxyInterface
{

	/**
	 *
	 * @var ValidatorInterface
	 */
	private $validator = null;

	public function addError($message)
	{
		$this->validator->addError($message);
	}

	public function getValidator()
	{
		return $this->validator;
	}

	public function isValid(AnnotatedInterface $model, $attribute)
	{
		return $this->validator->isValid($model, $attribute);
	}

	public function setValidator(ValidatorInterface $validator)
	{
		$this->validator = $validator;
	}

	public function getErrors()
	{
		return $this->validator->getErrors();
	}

}

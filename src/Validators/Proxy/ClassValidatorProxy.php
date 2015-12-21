<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
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

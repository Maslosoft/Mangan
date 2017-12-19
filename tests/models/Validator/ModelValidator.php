<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\ManganTest\Models\Validator;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Interfaces\Validators\ValidatorInterface;

/**
 * ModelValidator
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ModelValidator implements ValidatorInterface
{

	use \Maslosoft\Mangan\Validators\Traits\Messages;

	const ValidValue = 1234;

	/**
	 * Ovverride valid value
	 * @var mixed
	 */
	public $validValue = '';

	public function isValid(AnnotatedInterface $model, $attribute)
	{
		if (!empty($this->validValue))
		{
			return $model->number === $this->validValue;
		}
		return $model->number === self::ValidValue;
	}

}

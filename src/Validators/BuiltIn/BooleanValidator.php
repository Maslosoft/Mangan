<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Validators\BuiltIn;

use Maslosoft\Mangan\Interfaces\IValidator;

/**
 * BooleanValidator
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class BooleanValidator implements IValidator
{
	public function isValid($model, $field)
	{
		$valid = filter_var($model->$field, FILTER_VALIDATE_BOOLEAN);
		if(!$valid)
		{
			$this->addError('Attribute must be either true or false');
		}
		return true;
	}

	public function addError($message)
	{

	}

}

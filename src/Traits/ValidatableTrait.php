<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Traits;

use Maslosoft\Mangan\Interfaces\IValidatable;

/**
 * ValidatableTrait
 * FIXME Need implementng
 * @see IValidatable
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
trait ValidatableTrait
{

	public function getErrors()
	{
		return [];
	}

	public function validate()
	{
		return true;
	}

}

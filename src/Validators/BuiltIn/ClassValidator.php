<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Validators\BuiltIn;

/**
 * This validator forwards validation to specified class.
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ClassValidator implements ValidatorInterface
{

	use \Maslosoft\Mangan\Validators\Traits\AllowEmpty,
	  \Maslosoft\Mangan\Validators\Traits\Messages,
	  \Maslosoft\Mangan\Validators\Traits\Strict;

	public $class = '';

}

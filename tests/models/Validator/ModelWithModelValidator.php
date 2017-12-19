<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\ManganTest\Models\Validator;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;

/**
 * ModelWithModelValidator
 * @Validator(ModelValidator)
 * @see ModelValidator
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ModelWithModelValidator extends AbstractValidatedModel
{

	/**
	 * Test number
	 * @var int
	 */
	public $number = 0;

}

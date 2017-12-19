<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\ManganTest\Models\Validator;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;

/**
 * ModelWithCustomValidator
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ModelWithCustomValidator extends AbstractValidatedModel
{

	/**
	 * @Validator(CustomValidator)
	 * @var int
	 */
	public $number = 1;

}

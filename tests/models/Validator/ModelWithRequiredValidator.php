<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\ManganTest\Models\Validator;

/**
 * ModelWithRequiredValidator
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ModelWithRequiredValidator extends AbstractValidatedModel
{

	/**
	 * @RequiredValidator
	 * @var string
	 */
	public $login = '';

}

<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\ManganTest\Models\Validator;

use Maslosoft\Mangan\Interfaces\ScenariosInterface;

/**
 * ModelWithValidationOnInsert
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ModelWithValidationOnInsertAndRegister extends AbstractValidatedModel implements ScenariosInterface
{

	use \Maslosoft\Mangan\Traits\ScenariosTrait;

	/**
	 * @RequiredValidator(on = {'insert', 'register'})
	 * @var string
	 */
	public $password = '';

}

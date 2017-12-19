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
class ModelWithValidationOnInsert extends AbstractValidatedModel implements ScenariosInterface
{

	use \Maslosoft\Mangan\Traits\ScenariosTrait;

	/**
	 * @RequiredValidator(on = 'insert')
	 * @var string
	 */
	public $password = '';

}

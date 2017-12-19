<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Validator;

use Codeception\TestCase\Test;
use Maslosoft\Mangan\Validator;
use Maslosoft\ManganTest\Models\Validator\ModelWithModelValidator;
use UnitTester;

/**
 * ModelValidatorTest
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ModelValidatorTest extends Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfWillValidateWithModelValidator()
	{
		$model = new ModelWithModelValidator();

		$validator1 = new Validator($model);
		$result1 = $validator1->validate();

		$this->assertFalse($result1, 'That validation is not passing');

		$model = new ModelWithModelValidator();
		$model->number = \Maslosoft\ManganTest\Models\Validator\ModelValidator::ValidValue;

		$validator2 = new Validator($model);
		$result2 = $validator2->validate();

		$this->assertTrue($result2, 'That validation is passing');
	}

}

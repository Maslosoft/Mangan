<?php

namespace Validator;

use Codeception\Test\Unit;
use Maslosoft\Mangan\Validator;
use Maslosoft\ManganTest\Models\Validator\CustomValidator;
use Maslosoft\ManganTest\Models\Validator\ModelWithCustomValidator;
use Maslosoft\ManganTest\Models\Validator\ModelWithCustomValidatorCustomValue;
use UnitTester;

class CustomTest extends Unit
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	public function testIfWillValidateWithCustomValidator()
	{
		$model = new ModelWithCustomValidator();
		$validator = new Validator($model);

		// Should fail
		$valid = $validator->validate();
		$this->assertFalse($valid);

		$model->number = CustomValidator::ValidValue;

		$valid2 = $validator->validate();

		$this->assertTrue($valid2);
	}

	public function testIfWillValidateWithCustomValidatorValue()
	{
		$model = new ModelWithCustomValidatorCustomValue();
		$validator = new Validator($model);

		// Should fail
		$valid = $validator->validate();
		$this->assertFalse($valid);

		$model->number = 666;

		$valid2 = $validator->validate();

		$this->assertTrue($valid2);
	}

}

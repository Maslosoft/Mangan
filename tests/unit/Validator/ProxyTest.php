<?php

namespace Validator;

use Codeception\TestCase\Test;
use Maslosoft\Mangan\Helpers\Validator\Factory;
use Maslosoft\Mangan\Meta\ManganMeta;
use Maslosoft\Mangan\Validator;
use Maslosoft\ManganTest\Models\ValidatorProxy\ModelWithCustomValidatorProxy;
use Maslosoft\ManganTest\Models\ValidatorProxy\RequiredValidator;
use UnitTester;

class ProxyTest extends Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfWillValidateModelUsingCustomValidator()
	{
		$model = new ModelWithCustomValidatorProxy();
		$validator = new Validator($model);
		$result = $validator->validate();

		// Should not pass validation
		$this->assertFalse($result);

		// Should pass validation
		$model->login = 'blah';
		$result2 = $validator->validate();
		$this->assertTrue($result2);

		// Get validators meta
		$validatorsMeta = ManganMeta::create($model)->login->validators;

		// should have one
		$this->assertSame(1, count($validatorsMeta));
		$validatorInstance = Factory::create($model, $validatorsMeta[0]);

		// Check type of validator
		$this->assertInstanceof(RequiredValidator::class, $validatorInstance);
	}

}

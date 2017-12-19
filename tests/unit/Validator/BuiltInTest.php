<?php

namespace Validator;

use Codeception\TestCase\Test;
use Maslosoft\Mangan\Validator;
use Maslosoft\ManganTest\Models\Validator\DocumentWithRequiredValidator;
use Maslosoft\ManganTest\Models\Validator\ModelWithRequiredValidator;
use Maslosoft\ManganTest\Models\Validator\ModelWithRequiredValueValidator;
use UnitTester;

class BuiltInTest extends Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfWillValidateModelUsingBuildInValidators()
	{
		$model = new ModelWithRequiredValidator();
		$validator = new Validator($model);
		$result = $validator->validate();

		// Should not pass validation
		$this->assertFalse($result);

		// Should pass validation
		$model->login = 'blah';
		$result2 = $validator->validate();
		$this->assertTrue($result2);
	}

	public function testIfWillValidateModelUsingBuildInValidatorsWithRequiredValueSet()
	{
		$model = new ModelWithRequiredValueValidator();
		$model->login = 'asd';
		$validator = new Validator($model);
		$result = $validator->validate();

		// Should not pass validation
		$this->assertFalse($result);

		// Should pass validation
		$model->login = ModelWithRequiredValueValidator::RequiredValue;
		$result2 = $validator->validate();
		$this->assertTrue($result2);
	}

	public function testIfWillValidateDocumentUsingBuildInValidators()
	{
		$model = new DocumentWithRequiredValidator();

		// Should not pass validation
		$this->assertFalse($model->validate());

		// Should pass validation
		$model->login = 'blah';
		$this->assertTrue($model->validate());
	}

	public function testIfWillSetErrorsWithExternalValidatorCallToDocumentInstance()
	{
		$model = new DocumentWithRequiredValidator();

		$validator = new Validator($model);

		// Should not pass validation
		$this->assertFalse($validator->validate());

		$errors = $model->getErrors();
		codecept_debug('Error message is: ' . $errors['login'][0]);
		$this->assertGreaterThan(0, count($errors));
		$this->assertSame(1, count($errors['login']));
		$this->assertTrue(is_string($errors['login'][0]));
	}

}

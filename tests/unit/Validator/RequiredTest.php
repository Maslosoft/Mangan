<?php

namespace Validator;

use Codeception\Test\Unit;
use Maslosoft\Mangan\Validators\BuiltIn\RequiredValidator;
use Maslosoft\ManganTest\Models\BaseAttributesAnnotations;
use UnitTester;

class RequiredTest extends Unit
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfWillValidateRequiredValue()
	{
		$validator = new RequiredValidator();

		$model1 = new BaseAttributesAnnotations();
		$model1->string = 'some value';

		$valid1 = $validator->isValid($model1, 'string');
		$this->assertTrue($valid1);


		$model2 = new BaseAttributesAnnotations();
		$model2->string = ' ';

		$valid2 = $validator->isValid($model2, 'string');
		$this->assertFalse($valid2);


		$model3 = new BaseAttributesAnnotations();
		$model3->string = null;

		$valid3 = $validator->isValid($model3, 'string');
		$this->assertFalse($valid3);

		$msg = sprintf('Validator messages: %s', implode(PHP_EOL, $validator->getErrors()));
		codecept_debug($msg);
	}

	public function testIfWillValidateRequiredValueWhenNotRequired()
	{
		$validator = new RequiredValidator();
		$validator->when = 'bool';

		$model1 = new BaseAttributesAnnotations();
		$model1->string = '';
		$model1->bool = true;

		$valid1 = $validator->isValid($model1, 'string');
		$this->assertFalse($valid1, 'That when required, this must be required');

		$model1->bool = false;

		$valid2 = $validator->isValid($model1, 'string');
		$this->assertTrue($valid2, 'That when NOT required, this might be omited');

		$msg = sprintf('Validator messages: %s', implode(PHP_EOL, $validator->getErrors()));
		codecept_debug($msg);
	}

	public function testIfWillValidateRequiredValueWhenNotRequiredWithComplexCriteria()
	{
		$validator = new RequiredValidator();
		$validator->when = ['stringSecond' => 'needThis'];

		$model1 = new BaseAttributesAnnotations();
		$model1->string = '';
		$model1->stringSecond = 'needThis';

		$valid1 = $validator->isValid($model1, 'string');
		$this->assertFalse($valid1, 'That when required, this must be required');

		$model1->stringSecond = 'dontNeed';

		$valid2 = $validator->isValid($model1, 'string');
		$this->assertTrue($valid2, 'That when NOT required, this might be omited');

		$msg = sprintf('Validator messages: %s', implode(PHP_EOL, $validator->getErrors()));
		codecept_debug($msg);
	}

	public function testIfWillValidateRequiredValueWithTrimDisabled()
	{
		$validator = new RequiredValidator();
		$validator->trim = false;

		$model1 = new BaseAttributesAnnotations();
		$model1->string = 'some value';

		$valid1 = $validator->isValid($model1, 'string');
		$this->assertTrue($valid1);


		$model2 = new BaseAttributesAnnotations();
		$model2->string = ' ';

		$valid2 = $validator->isValid($model2, 'string');
		$this->assertTrue($valid2);


		$model3 = new BaseAttributesAnnotations();
		$model3->string = null;

		$valid3 = $validator->isValid($model3, 'string');
		$this->assertFalse($valid3);

		$msg = sprintf('Validator messages: %s', implode(PHP_EOL, $validator->getErrors()));
		codecept_debug($msg);
	}

	public function testIfWillValidateRequiredConcreteValue()
	{
		$validator = new RequiredValidator();
		$validator->requiredValue = 'yes';

		$model1 = new BaseAttributesAnnotations();
		$model1->string = 'yes';

		$valid1 = $validator->isValid($model1, 'string');
		$this->assertTrue($valid1);

		$model2 = new BaseAttributesAnnotations();
		$model2->string = 'bogus value';

		$valid2 = $validator->isValid($model2, 'string');
		$this->assertFalse($valid2);

		$msg = sprintf('Validator messages: %s', implode(PHP_EOL, $validator->getErrors()));
		codecept_debug($msg);
	}

}

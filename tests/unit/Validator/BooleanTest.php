<?php

namespace Validator;

use Maslosoft\Mangan\Validators\BuiltIn\BooleanValidator;
use Maslosoft\ManganTest\Models\BaseAttributesAnnotations;
use UnitTester;

class BooleanTest extends \Codeception\Test\Unit
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfWillValidateBooleanValue()
	{
		$validator = new BooleanValidator();

		$model1 = new BaseAttributesAnnotations();
		$model1->bool = false;

		$valid1 = $validator->isValid($model1, 'bool');
		$this->assertTrue($valid1);

		$model2 = new BaseAttributesAnnotations();
		$model2->bool = '1';

		$valid2 = $validator->isValid($model2, 'bool');
		$this->assertTrue($valid2);

		$model3 = new BaseAttributesAnnotations();
		$model3->bool = 'bogus value';

		$valid3 = $validator->isValid($model3, 'bool');
		$this->assertFalse($valid3);

		$msg = sprintf('Validator messages: %s', implode(PHP_EOL, $validator->getErrors()));
		\codecept_debug($msg);
	}

	public function testIfWillValidateBooleanValueWithSetCustomMsgError()
	{
		$validator = new BooleanValidator();
		$validator->msgBoolean = 'Error For Boolean';

		$model = new BaseAttributesAnnotations();
		$model->bool = 'bogus value';

		$valid = $validator->isValid($model, 'bool');
		$this->assertFalse($valid);

		$this->assertSame('Error For Boolean', $validator->getErrors()[0]);

		$msg = sprintf('Validator messages: %s', implode(PHP_EOL, $validator->getErrors()));
		\codecept_debug($msg);
	}

	public function testIfWillValidateBooleanValueWithCustomGenericMessage()
	{
		$validator = new BooleanValidator();
		$validator->message = 'Error Generic';

		$model = new BaseAttributesAnnotations();
		$model->bool = 'bogus value';

		$valid = $validator->isValid($model, 'bool');
		$this->assertFalse($valid);

		$this->assertSame('Error Generic', $validator->getErrors()[0]);

		$msg = sprintf('Validator messages: %s', implode(PHP_EOL, $validator->getErrors()));
		\codecept_debug($msg);
	}

}

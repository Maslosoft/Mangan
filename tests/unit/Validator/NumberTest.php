<?php

namespace Validator;

use Codeception\Test\Unit;
use Maslosoft\Mangan\Validators\BuiltIn\NumberValidator;
use Maslosoft\ManganTest\Models\BaseAttributesAnnotations;
use UnitTester;
use function codecept_debug;
use function implode;
use function sprintf;
use const PHP_EOL;

class NumberTest extends Unit
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfWillValidateNumber()
	{
		$validator = new NumberValidator();

		$model1 = new BaseAttributesAnnotations();
		$model1->int = '2.3';

		$valid1 = $validator->isValid($model1, 'int');
		$this->assertTrue($valid1);

		$model2 = new BaseAttributesAnnotations();
		$model2->int = 2;

		$valid2 = $validator->isValid($model2, 'int');
		$this->assertTrue($valid2);

		$model3 = new BaseAttributesAnnotations();
		$model3->int = 'bogus value';

		$valid3 = $validator->isValid($model3, 'int');
		$this->assertFalse($valid3);

		$model4 = new BaseAttributesAnnotations();
		$model4->int = [1];

		$valid4 = $validator->isValid($model4, 'int');
		$this->assertFalse($valid4);

		$msg = sprintf('Validator messages: %s', implode(PHP_EOL, $validator->getErrors()));
		codecept_debug($msg);
	}

	public function testTooSmallValid()
	{
		$validator = new NumberValidator();
		$validator->min = 10;

		$model = new BaseAttributesAnnotations();
		$model->int = 12;

		$valid = $validator->isValid($model, 'int');

		$this->assertTrue($valid);
	}

	public function testTooSmallNotValid()
	{
		$validator = new NumberValidator();
		$validator->min = 10;

		$model = new BaseAttributesAnnotations();
		$model->int = 1;

		$valid = $validator->isValid($model, 'int');

		$this->assertFalse($valid);

		$msg = sprintf('Validator messages: %s', implode(PHP_EOL, $validator->getErrors()));
		codecept_debug($msg);
	}

	public function testTooLargeValid()
	{
		$validator = new NumberValidator();
		$validator->max = 10;

		$model = new BaseAttributesAnnotations();
		$model->int = 1;

		$valid = $validator->isValid($model, 'int');

		$this->assertTrue($valid);
	}

	public function testTooLargeNotValid()
	{
		$validator = new NumberValidator();
		$validator->max = 10;

		$model = new BaseAttributesAnnotations();
		$model->int = 100;

		$valid = $validator->isValid($model, 'int');

		$this->assertFalse($valid);

		$msg = sprintf('Validator messages: %s', implode(PHP_EOL, $validator->getErrors()));
		codecept_debug($msg);
	}

	public function testGtNotValid()
	{
		$validator = new NumberValidator();
		$validator->gt = 0;

		$model = new BaseAttributesAnnotations();
		$model->float = 0.0;

		$valid = $validator->isValid($model, 'float');

		$this->assertFalse($valid);

		$msg = sprintf('Validator messages: %s', implode(PHP_EOL, $validator->getErrors()));
		$this->assertStringContainsString('greater', $msg);
		codecept_debug($msg);

		$validator = new NumberValidator();
		$validator->gt = 10;

		$model = new BaseAttributesAnnotations();
		$model->float = 9.99;

		$valid = $validator->isValid($model, 'float');

		$this->assertFalse($valid);

		$msg = sprintf('Validator messages: %s', implode(PHP_EOL, $validator->getErrors()));
		$this->assertStringContainsString('greater', $msg);
		codecept_debug($msg);
	}

	public function testGtValid()
	{
		$validator = new NumberValidator();
		$validator->gt = 0;

		$model = new BaseAttributesAnnotations();
		$model->float = 0.01;

		$valid = $validator->isValid($model, 'float');

		$this->assertTrue($valid);

		$msg = sprintf('Validator messages: %s', implode(PHP_EOL, $validator->getErrors()));
		$this->assertStringNotContainsString('greater', $msg);
		codecept_debug($msg);

		$validator = new NumberValidator();
		$validator->gt = 10;

		$model = new BaseAttributesAnnotations();
		$model->float = 90.99;

		$valid = $validator->isValid($model, 'float');

		$this->assertTrue($valid);

		$msg = sprintf('Validator messages: %s', implode(PHP_EOL, $validator->getErrors()));
		$this->assertStringNotContainsString('greater', $msg);
		codecept_debug($msg);
	}

	public function testLtNotValid()
	{
		$validator = new NumberValidator();
		$validator->lt = 0;

		$model = new BaseAttributesAnnotations();
		$model->float = -0.0;

		$valid = $validator->isValid($model, 'float');

		$this->assertFalse($valid);

		$msg = sprintf('Validator messages: %s', implode(PHP_EOL, $validator->getErrors()));
		$this->assertStringContainsString('lesser', $msg);
		codecept_debug($msg);

		$validator = new NumberValidator();
		$validator->lt = 10;

		$model = new BaseAttributesAnnotations();
		$model->float = 19.99;

		$valid = $validator->isValid($model, 'float');

		$this->assertFalse($valid);

		$msg = sprintf('Validator messages: %s', implode(PHP_EOL, $validator->getErrors()));
		$this->assertStringContainsString('lesser', $msg);
		codecept_debug($msg);
	}

	public function testLtValid()
	{
		$validator = new NumberValidator();
		$validator->lt = 0;

		$model = new BaseAttributesAnnotations();
		$model->float = -0.1;

		$valid = $validator->isValid($model, 'float');

		$this->assertTrue($valid);

		$msg = sprintf('Validator messages: %s', implode(PHP_EOL, $validator->getErrors()));
		$this->assertStringNotContainsString('lesser', $msg);
		codecept_debug($msg);

		$validator = new NumberValidator();
		$validator->lt = 10;

		$model = new BaseAttributesAnnotations();
		$model->float = 9.99;

		$valid = $validator->isValid($model, 'float');

		$this->assertTrue($valid);

		$msg = sprintf('Validator messages: %s', implode(PHP_EOL, $validator->getErrors()));
		$this->assertStringNotContainsString('lesser', $msg);
		codecept_debug($msg);
	}

	public function testIntegerOnlyValid()
	{
		$validator = new NumberValidator();
		$validator->integerOnly = true;

		$model = new BaseAttributesAnnotations();
		$model->int = 1;

		$valid = $validator->isValid($model, 'int');

		$this->assertTrue($valid);
	}

	public function testIntegerOnlyNotValid()
	{
		$validator = new NumberValidator();
		$validator->integerOnly = true;

		$model = new BaseAttributesAnnotations();
		$model->int = 1.3;

		$valid = $validator->isValid($model, 'int');

		$this->assertFalse($valid);

		$msg = sprintf('Validator messages: %s', implode(PHP_EOL, $validator->getErrors()));
		codecept_debug($msg);
	}

}

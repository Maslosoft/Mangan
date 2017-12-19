<?php

namespace Validator;

use Codeception\TestCase\Test;
use Maslosoft\Mangan\Validators\BuiltIn\CompareValidator;
use Maslosoft\ManganTest\Models\BaseAttributesAnnotations;
use UnitTester;

class CompareTest extends Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	public function testCompareValid()
	{
		$validator = new CompareValidator();
		$validator->compareAttribute = 'stringSecond';

		$model = new BaseAttributesAnnotations();
		$model->string = 'abcd';
		$model->stringSecond = 'abcd';

		$valid = $validator->isValid($model, 'string');

		$this->assertTrue($valid);
	}

	public function testCompareNotValid()
	{
		$validator = new CompareValidator();
		$validator->compareAttribute = 'stringSecond';

		$model = new BaseAttributesAnnotations();
		$model->string = 'abcd';
		$model->stringSecond = 'abc';

		$valid = $validator->isValid($model, 'string');

		$this->assertFalse($valid);

		$msg = sprintf('Validator messages: %s', implode(PHP_EOL, $validator->getErrors()));
		codecept_debug($msg);
	}

	public function testCompareValueValid()
	{
		$validator = new CompareValidator();
		$validator->compareValue = 'abcd';

		$model = new BaseAttributesAnnotations();
		$model->string = 'abcd';

		$valid = $validator->isValid($model, 'string');

		$this->assertTrue($valid);
	}

	public function testCompareValueNotValid()
	{
		$validator = new CompareValidator();
		$validator->compareValue = 'abc';

		$model = new BaseAttributesAnnotations();
		$model->string = 'abcd';

		$valid = $validator->isValid($model, 'string');

		$this->assertFalse($valid);

		$msg = sprintf('Validator messages: %s', implode(PHP_EOL, $validator->getErrors()));
		codecept_debug($msg);
	}

	public function testCompareNeqValid()
	{
		$validator = new CompareValidator();
		$validator->operator = '!=';
		$validator->compareValue = 'abcd';

		$model = new BaseAttributesAnnotations();
		$model->string = 'abcdefg';

		$valid = $validator->isValid($model, 'string');

		$this->assertTrue($valid);
	}

	public function testCompareNeqNotValid()
	{
		$validator = new CompareValidator();
		$validator->operator = '!=';
		$validator->compareValue = 'abcd';

		$model = new BaseAttributesAnnotations();
		$model->string = 'abcd';

		$valid = $validator->isValid($model, 'string');

		$this->assertFalse($valid);

		$msg = sprintf('Validator messages: %s', implode(PHP_EOL, $validator->getErrors()));
		codecept_debug($msg);
	}

	public function testCompareGtValid()
	{
		$validator = new CompareValidator();
		$validator->operator = '>';
		$validator->compareValue = 10;

		$model = new BaseAttributesAnnotations();
		$model->int = 20;

		$valid = $validator->isValid($model, 'int');

		$this->assertTrue($valid);
	}

	public function testCompareGtNotValid()
	{
		$validator = new CompareValidator();
		$validator->operator = '>';
		$validator->compareValue = 10;

		$model = new BaseAttributesAnnotations();
		$model->int = 5;

		$valid = $validator->isValid($model, 'int');

		$this->assertFalse($valid);

		$msg = sprintf('Validator messages: %s', implode(PHP_EOL, $validator->getErrors()));
		codecept_debug($msg);
	}

	public function testCompareGteValid()
	{
		$validator = new CompareValidator();
		$validator->operator = '>=';
		$validator->compareValue = 10;

		$model = new BaseAttributesAnnotations();
		$model->int = 20;

		$valid = $validator->isValid($model, 'int');

		$this->assertTrue($valid);

		$model2 = new BaseAttributesAnnotations();
		$model2->int = 10;

		$valid2 = $validator->isValid($model2, 'int');

		$this->assertTrue($valid2);
	}

	public function testCompareGteNotValid()
	{
		$validator = new CompareValidator();
		$validator->operator = '>=';
		$validator->compareValue = 10;

		$model = new BaseAttributesAnnotations();
		$model->int = 5;

		$valid = $validator->isValid($model, 'int');

		$this->assertFalse($valid);

		$msg = sprintf('Validator messages: %s', implode(PHP_EOL, $validator->getErrors()));
		codecept_debug($msg);
	}

	public function testCompareLtValid()
	{
		$validator = new CompareValidator();
		$validator->operator = '<';
		$validator->compareValue = 10;

		$model = new BaseAttributesAnnotations();
		$model->int = 2;

		$valid = $validator->isValid($model, 'int');

		$this->assertTrue($valid);
	}

	public function testCompareLtNotValid()
	{
		$validator = new CompareValidator();
		$validator->operator = '<';
		$validator->compareValue = 10;

		$model = new BaseAttributesAnnotations();
		$model->int = 15;

		$valid = $validator->isValid($model, 'int');

		$this->assertFalse($valid);

		$msg = sprintf('Validator messages: %s', implode(PHP_EOL, $validator->getErrors()));
		codecept_debug($msg);
	}

	public function testCompareLteValid()
	{
		$validator = new CompareValidator();
		$validator->operator = '<=';
		$validator->compareValue = 10;

		$model = new BaseAttributesAnnotations();
		$model->int = 5;

		$valid = $validator->isValid($model, 'int');

		$this->assertTrue($valid);

		$model2 = new BaseAttributesAnnotations();
		$model2->int = 10;

		$valid2 = $validator->isValid($model2, 'int');

		$this->assertTrue($valid2);
	}

	public function testCompareLteNotValid()
	{
		$validator = new CompareValidator();
		$validator->operator = '<=';
		$validator->compareValue = 10;

		$model = new BaseAttributesAnnotations();
		$model->int = 15;

		$valid = $validator->isValid($model, 'int');

		$this->assertFalse($valid);

		$msg = sprintf('Validator messages: %s', implode(PHP_EOL, $validator->getErrors()));
		codecept_debug($msg);
	}

}

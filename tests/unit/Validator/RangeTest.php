<?php

namespace Validator;

use Codeception\TestCase\Test;
use Maslosoft\Mangan\Validators\BuiltIn\RangeValidator;
use Maslosoft\ManganTest\Models\BaseAttributesAnnotations;
use UnitTester;

class RangeTest extends Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	public function testRangeValid()
	{
		$validator = new RangeValidator();
		$validator->range = [1, 2, 3];

		$model = new BaseAttributesAnnotations();
		$model->int = '1';

		$valid = $validator->isValid($model, 'int');

		$this->assertTrue($valid);
	}

	public function testRangeNotValid()
	{
		$validator = new RangeValidator();
		$validator->range = [1, 2, 3];

		$model = new BaseAttributesAnnotations();
		$model->int = ' ';

		$valid = $validator->isValid($model, 'int');

		$this->assertFalse($valid);
	}

	public function testStrictRangeValid()
	{
		$validator = new RangeValidator();
		$validator->strict = true;
		$validator->range = [1, 2, 3];

		$model = new BaseAttributesAnnotations();
		$model->int = 1;

		$valid = $validator->isValid($model, 'int');

		$this->assertTrue($valid);
	}

	public function testStrictRangeNotValid()
	{
		$validator = new RangeValidator();
		$validator->strict = true;
		$validator->range = [1, 2, 3];

		$model = new BaseAttributesAnnotations();
		$model->int = '1';

		$valid = $validator->isValid($model, 'int');

		$this->assertFalse($valid);

		$msg = sprintf('Validator messages: %s', implode(PHP_EOL, $validator->getErrors()));
		codecept_debug($msg);
	}

	public function testNotRangeValid()
	{
		$validator = new RangeValidator();
		$validator->range = [1, 2, 3];
		$validator->not = true;

		$model = new BaseAttributesAnnotations();
		$model->int = 4;

		$valid = $validator->isValid($model, 'int');

		$this->assertTrue($valid);
	}

	public function testNotRangeNotValid()
	{
		$validator = new RangeValidator();
		$validator->range = [1, 2, 3];
		$validator->not = true;

		$model = new BaseAttributesAnnotations();
		$model->int = 1;

		$valid = $validator->isValid($model, 'int');

		$this->assertFalse($valid);

		$msg = sprintf('Validator messages: %s', implode(PHP_EOL, $validator->getErrors()));
		codecept_debug($msg);
	}

}

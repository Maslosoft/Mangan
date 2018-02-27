<?php

namespace Validator;

use Codeception\Test\Unit;
use Maslosoft\Mangan\Validators\BuiltIn\StringValidator;
use Maslosoft\ManganTest\Models\BaseAttributesAnnotations;
use UnitTester;

class StringTest extends Unit
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	public function testTooSmallValid()
	{
		$validator = new StringValidator();
		$validator->min = 10;

		$model = new BaseAttributesAnnotations();
		$model->string = 'asdfghjklzxc';

		$valid = $validator->isValid($model, 'string');

		$this->assertTrue($valid);
	}

	public function testWrongTypeNotValid()
	{
		$validator = new StringValidator();
		$validator->min = 10;

		$model = new BaseAttributesAnnotations();
		$model->string = 123;

		$valid = $validator->isValid($model, 'string');

		$this->assertFalse($valid);
	}

	public function testTooSmallNotValid()
	{
		$validator = new StringValidator();
		$validator->min = 10;

		$model = new BaseAttributesAnnotations();
		$model->string = 'asd';

		$valid = $validator->isValid($model, 'string');

		$this->assertFalse($valid);
	}

	public function testTooLargeValid()
	{
		$validator = new StringValidator();
		$validator->max = 10;

		$model = new BaseAttributesAnnotations();
		$model->string = 'asdfghj';

		$valid = $validator->isValid($model, 'string');

		$this->assertTrue($valid);
	}

	public function testTooLargeNotValid()
	{
		$validator = new StringValidator();
		$validator->max = 10;

		$model = new BaseAttributesAnnotations();
		$model->string = 'asdasdasdasd';

		$valid = $validator->isValid($model, 'string');

		$this->assertFalse($valid);
	}

	public function testExactValid()
	{
		$validator = new StringValidator();
		$validator->is = 10;

		$model = new BaseAttributesAnnotations();
		$model->string = 'asdfghjklz';

		$valid = $validator->isValid($model, 'string');

		$this->assertTrue($valid);
	}

	public function testExactNotValid()
	{
		$validator = new StringValidator();
		$validator->is = 10;

		$model = new BaseAttributesAnnotations();
		$model->string = 'asd';

		$valid = $validator->isValid($model, 'string');

		$this->assertFalse($valid);
	}

}

<?php

namespace Validator;

use Maslosoft\Mangan\Validators\BuiltIn\CountValidator;
use Maslosoft\ManganTest\Models\BaseAttributesAnnotations;
use Maslosoft\ManganTest\Models\CountableStub;
use UnitTester;

class CountTest extends \Codeception\Test\Unit
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	public function testTooSmallValid()
	{
		$validator = new CountValidator();
		$validator->min = 10;

		$model = new BaseAttributesAnnotations();
		$model->array = str_split('asdfghjklzxc');

		$valid = $validator->isValid($model, 'array');
		codecept_debug($validator->getErrors());
		$this->assertTrue($valid);
	}

	public function testCountableValid()
	{
		$validator = new CountValidator();
		$validator->min = 10;

		$model = new BaseAttributesAnnotations();
		$model->array = new CountableStub(11);

		$valid = $validator->isValid($model, 'array');
		codecept_debug($validator->getErrors());
		$this->assertTrue($valid);
	}

	public function testWrongTypeNotValid()
	{
		$validator = new CountValidator();
		$validator->min = 10;

		$model = new BaseAttributesAnnotations();
		$model->array = 123;

		$valid = $validator->isValid($model, 'array');

		$this->assertFalse($valid);
	}

	public function testTooSmallNotValid()
	{
		$validator = new CountValidator();
		$validator->min = 10;

		$model = new BaseAttributesAnnotations();
		$model->array = str_split('asd');

		$valid = $validator->isValid($model, 'array');
		codecept_debug($validator->getErrors());
		$this->assertFalse($valid);
	}

	public function testTooLargeValid()
	{
		$validator = new CountValidator();
		$validator->max = 10;

		$model = new BaseAttributesAnnotations();
		$model->array = str_split('asdfghj');

		$valid = $validator->isValid($model, 'array');
		codecept_debug($validator->getErrors());
		$this->assertTrue($valid);
	}

	public function testTooLargeNotValid()
	{
		$validator = new CountValidator();
		$validator->max = 10;

		$model = new BaseAttributesAnnotations();
		$model->array = str_split('asdasdasdasd');

		$valid = $validator->isValid($model, 'array');
		codecept_debug($validator->getErrors());
		$this->assertFalse($valid);
	}

	public function testExactValid()
	{
		$validator = new CountValidator();
		$validator->is = 10;

		$model = new BaseAttributesAnnotations();
		$model->array = str_split('asdfghjklz');

		$valid = $validator->isValid($model, 'array');
		codecept_debug($validator->getErrors());
		$this->assertTrue($valid);
	}

	public function testExactNotValid()
	{
		$validator = new CountValidator();
		$validator->is = 10;

		$model = new BaseAttributesAnnotations();
		$model->array = str_split('asd');

		$valid = $validator->isValid($model, 'array');
		codecept_debug($validator->getErrors());
		$this->assertFalse($valid);
	}

}

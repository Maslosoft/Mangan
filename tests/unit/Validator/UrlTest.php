<?php

namespace Validator;

use Codeception\TestCase\Test;
use Maslosoft\Mangan\Validators\BuiltIn\UrlValidator;
use Maslosoft\ManganTest\Models\BaseAttributesAnnotations;
use UnitTester;

class UrlTest extends Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	public function testHttpUrlValid()
	{
		$validator = new UrlValidator();

		$model = new BaseAttributesAnnotations();
		$model->string = 'http://example.com/';

		$valid = $validator->isValid($model, 'string');

		$this->assertTrue($valid);
	}

	public function testFtpUrlValid()
	{
		$validator = new UrlValidator();

		$model = new BaseAttributesAnnotations();
		$model->string = 'ftp://example.com/';

		$valid = $validator->isValid($model, 'string');

		$this->assertTrue($valid);
	}

	public function testDataUrlNotValid()
	{
		$validator = new UrlValidator();

		$model = new BaseAttributesAnnotations();
		$model->string = 'data:image/gif;base64,R0lGOD lhCwAOAMQfAP////7o9imAsB';

		$valid = $validator->isValid($model, 'string');

		$this->assertFalse($valid);

		$msg = sprintf('Validator messages: %s', implode(PHP_EOL, $validator->getErrors()));
		codecept_debug($msg);
	}

	public function testUrlNotValid()
	{
		$validator = new UrlValidator();

		$model = new BaseAttributesAnnotations();
		$model->string = 'http:///example.com/';

		$valid = $validator->isValid($model, 'string');

		$this->assertFalse($valid);

		$msg = sprintf('Validator messages: %s', implode(PHP_EOL, $validator->getErrors()));
		codecept_debug($msg);
	}

}

<?php

namespace Validator;

use Codeception\Test\Unit;
use Maslosoft\Mangan\Validators\BuiltIn\RegexValidator;
use Maslosoft\ManganTest\Models\BaseAttributesAnnotations;
use UnitTester;

class RegexTest extends Unit
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	public function testRegexValid()
	{
		$validator = new RegexValidator();
		$validator->pattern = '~^[a-z]{4}$~';

		$model = new BaseAttributesAnnotations();
		$model->string = 'abcd';

		$valid = $validator->isValid($model, 'string');

		$this->assertTrue($valid);
	}

	public function testRegexNotValid()
	{
		$validator = new RegexValidator();
		$validator->pattern = '~^[a-z]{4}$~';

		$model = new BaseAttributesAnnotations();
		$model->string = 'abcdxxx';

		$valid = $validator->isValid($model, 'string');

		$this->assertFalse($valid);

		$msg = sprintf('Validator messages: %s', implode(PHP_EOL, $validator->getErrors()));
		codecept_debug($msg);
	}

	public function testNotRegexValid()
	{
		$validator = new RegexValidator();
		$validator->not = true;
		$validator->pattern = '~^[a-z]{4}$~';

		$model = new BaseAttributesAnnotations();
		$model->string = 'abcdxxx';

		$valid = $validator->isValid($model, 'string');

		$this->assertTrue($valid);
	}

	public function testNotRegexNotValid()
	{
		$validator = new RegexValidator();
		$validator->not = true;
		$validator->pattern = '~^[a-z]{4}$~';

		$model = new BaseAttributesAnnotations();
		$model->string = 'abcd';

		$valid = $validator->isValid($model, 'string');

		$this->assertFalse($valid);

		$msg = sprintf('Validator messages: %s', implode(PHP_EOL, $validator->getErrors()));
		codecept_debug($msg);
	}

}

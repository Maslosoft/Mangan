<?php

namespace Validator;

use Codeception\Test\Unit;
use Maslosoft\Mangan\Validators\BuiltIn\EmailValidator;
use Maslosoft\ManganTest\Models\BaseAttributesAnnotations;
use UnitTester;

class EmailTest extends Unit
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	public function testIfWillValidateEmailValue()
	{
		$validator = new EmailValidator();

		$model1 = new BaseAttributesAnnotations();
		$model1->string = 'contact@example.com';

		$valid1 = $validator->isValid($model1, 'string');
		$this->assertTrue($valid1);


		$model2 = new BaseAttributesAnnotations();
		$model2->string = ' ';

		$valid2 = $validator->isValid($model2, 'string');
		$this->assertFalse($valid2);


		$model3 = new BaseAttributesAnnotations();
		$model3->string = 'contact@example.cx?';

		$valid3 = $validator->isValid($model3, 'string');
		$this->assertFalse($valid3);

		$msg = sprintf('Validator messages: %s', implode(PHP_EOL, $validator->getErrors()));
		codecept_debug($msg);
	}

	public function testIfWillValidateEmailWithIdnDomain()
	{
		$validator = new EmailValidator();

		$model1 = new BaseAttributesAnnotations();
		$model1->string = 'test@masełkowski.pl';

		$valid1 = $validator->isValid($model1, 'string');
		$this->assertTrue($valid1);
	}

	public function testIfWillValidateInternationalEmailValue()
	{
		$validator = new EmailValidator();

		$model1 = new BaseAttributesAnnotations();
		$model1->string = 'łączność@masełkowski.pl';

		$valid1 = $validator->isValid($model1, 'string');
		$this->assertTrue($valid1);

		$model2 = new BaseAttributesAnnotations();
		$model2->string = 'ącki@masełkowski.pl';

		$valid2 = $validator->isValid($model2, 'string');
		$this->assertTrue($valid2);
	}

	public function testIfWillValidateEmailWithDomainAndPortValue()
	{
		$validator = new EmailValidator();
		$validator->checkMX = true;
		// This is not recommended, enable only when really need to test
//		$validator->checkPort = true;

		$model1 = new BaseAttributesAnnotations();
		$model1->string = 'contact@maslosoft.com';

		$valid1 = $validator->isValid($model1, 'string');
		$this->assertTrue($valid1);

		$model3 = new BaseAttributesAnnotations();
		$model3->string = 'contact@non-existent-domain.example.com';

		$valid3 = $validator->isValid($model3, 'string');
		$this->assertFalse($valid3);

		$model4 = new BaseAttributesAnnotations();
		$model4->string = 'contact@dev.maslosoft.com';

		$valid4 = $validator->isValid($model4, 'string');
		$this->assertFalse($valid4);

		$msg = sprintf('Validator messages: %s', implode(PHP_EOL, $validator->getErrors()));
		codecept_debug($msg);
	}

}

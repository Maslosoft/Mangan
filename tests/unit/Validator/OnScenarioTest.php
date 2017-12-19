<?php

namespace Validator;

use Codeception\TestCase\Test;
use Maslosoft\Mangan\ScenarioManager;
use Maslosoft\Mangan\Validator;
use Maslosoft\ManganTest\Models\Validator\ModelWithValidationExceptInsertAndRegister;
use Maslosoft\ManganTest\Models\Validator\ModelWithValidationOnInsert;
use Maslosoft\ManganTest\Models\Validator\ModelWithValidationOnInsertAndRegister;
use UnitTester;

class OnScenarioTest extends Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfWillFailValidationOnlyOnInsert()
	{
		$model = new ModelWithValidationOnInsert();
		$validator = new Validator($model);

		$valid = $validator->validate();

		$this->assertFalse($valid);

		ScenarioManager::setScenario($model, 'update');

		$valid2 = $validator->validate();

		$this->assertTrue($valid2);
	}

	public function testIfWillFailValidationOnlyOnInsertAndRegister()
	{
		$model = new ModelWithValidationOnInsertAndRegister();
		$validator = new Validator($model);

		$valid = $validator->validate();

		$this->assertFalse($valid);

		ScenarioManager::setScenario($model, 'register');

		$valid2 = $validator->validate();

		$this->assertFalse($valid2);

		ScenarioManager::setScenario($model, 'update');

		$valid3 = $validator->validate();

		$this->assertTrue($valid3);
	}

	public function testIfWillFailValidationExceptInsertAndRegister()
	{
		$model = new ModelWithValidationExceptInsertAndRegister();
		$validator = new Validator($model);

		$valid = $validator->validate();

		$this->assertTrue($valid);

		ScenarioManager::setScenario($model, 'register');

		$valid2 = $validator->validate();

		$this->assertTrue($valid2);

		ScenarioManager::setScenario($model, 'update');

		$valid3 = $validator->validate();

		$this->assertFalse($valid3);
	}

}

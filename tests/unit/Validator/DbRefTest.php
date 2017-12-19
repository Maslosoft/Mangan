<?php

namespace Validator;

use Codeception\TestCase\Test;
use Maslosoft\Mangan\Validator;
use Maslosoft\ManganTest\Models\Validator\EmbeddedModelWithValidator;
use Maslosoft\ManganTest\Models\Validator\ModelWithDbRefArrayWithValidator;
use Maslosoft\ManganTest\Models\Validator\ModelWithDbRefArrayWithValidatorNotUpdatable;
use Maslosoft\ManganTest\Models\Validator\ModelWithDbRefWithValidator;
use UnitTester;

class DbRefTest extends Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfWillValidateDbRefDocument()
	{
		$model = new ModelWithDbRefWithValidator();
		$model->address = new EmbeddedModelWithValidator();

		$validator = new Validator($model);
		$result = $validator->validate();

		$errors = array_filter($validator->getErrors(), 'count');
		codecept_debug($errors);

		// Should fail
		$this->assertFalse($result, 'That embedded model has failed validation');

		// Should pass
		$model->address->street = 'Wall St.';

		$result2 = $validator->validate();

		$this->assertTrue($result2);
	}

	public function testIfWillValidateDbRefArrayOfDocuments()
	{
		$model = new ModelWithDbRefArrayWithValidator();
		$model->addresses[] = new EmbeddedModelWithValidator();
		$model->addresses[] = new EmbeddedModelWithValidator();

		$validator = new Validator($model);
		$result = $validator->validate();

		$errors = array_filter($validator->getErrors(), 'count');
		codecept_debug($errors);

		// Should fail
		$this->assertFalse($result, 'That first of embedded models has failed validation');

		// Should fail too
		$model->addresses[0]->street = 'Franklin St.';

		$result2 = $validator->validate();

		$errors = array_filter($validator->getErrors(), 'count');
		codecept_debug($errors);
		codecept_debug($validator->getErrors());

		$this->assertFalse($result2, 'That second of embedded models has failed validation');

		// Should pass
		$model->addresses[1]->street = 'Wall St.';

		$result3 = $validator->validate();

		$this->assertTrue($result3);
	}

	public function testIfWillValidateDbRefArrayOfDocumentsAndSkipNotUpdatableModels()
	{
		$model = new ModelWithDbRefArrayWithValidatorNotUpdatable();
		$model->addresses[] = new EmbeddedModelWithValidator();
		$model->addresses[] = new EmbeddedModelWithValidator();

		$validator = new Validator($model);
		$result = $validator->validate();

		$errors = array_filter($validator->getErrors(), 'count');
		codecept_debug($errors);

		// Should pass even when adress is not valid
		$this->assertTrue($result, 'That validation passed, even if db refs are not valid, as they are not updatable');
	}

}
